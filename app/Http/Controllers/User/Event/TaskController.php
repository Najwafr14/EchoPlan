<?php
namespace App\Http\Controllers\User\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tasks;
use App\Models\Event;
use App\Models\Division;
use App\Models\DivisionType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\EventDivMember;
use App\Models\TaskHistories;

class TaskController extends Controller
{
    public function index(Request $request, $eventId)
    {
        $query = Tasks::where('event_id', $eventId)
            ->with(['assignee', 'division', 'event']);
        
        if ($request->filled('search')) {
            $query->where('task_name', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $sortBy = $request->get('sort', 'deadline_asc');
        switch ($sortBy) {
            case 'deadline_desc':
                $query->orderBy('deadline', 'desc');
                break;
            case 'deadline_asc':
            default:
                $query->orderBy('deadline', 'asc');
                break;
        }
        
        $tasks = $query->get();
        
        $event = Event::findOrFail($eventId);
        $events = Event::all();
        
        $divisions = Division::with('divisionType')
            ->where('event_id', $eventId)
            ->get();
        
        $divisionIds = $divisions->pluck('division_id');
        $usersByDivision = EventDivMember::with('user')
            ->whereIn('division_id', $divisionIds)
            ->get()
            ->groupBy('division_id')
            ->map(function($members) {
                return $members->map(function($member) {
                    return $member->user;
                })->filter();
            });
        
        $allStatuses = ['Assigned', 'Revision', 'Blocked', 'Submitted', 'UnderReview_Div', 'UnderReview_CC', 'Completed'];
        
        return view('user.event.task.index', compact('tasks', 'event', 'events', 'divisions', 'usersByDivision', 'eventId', 'allStatuses'));
    }
    
    public function store(Request $request, $eventId)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,event_id',
            'division_id' => 'required|exists:divisions,division_id',
            'assigned_to' => 'nullable|exists:users,user_id',
            'task_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:Assigned,InProgress,Blocked,Submitted,UnderReview_Div,UnderReview_PM,Completed',
            'deadline' => 'required|date',
            'phase' => 'required|in:pre event,preparation,d day,post event',
        ]);
        
        Tasks::create($validated);
        
        return redirect()->route('user.event.task.index', $eventId)->with('success', 'Task added successfully!');
    }
    
    public function show(Tasks $task)
    {
        $task->load([
            'event',
            'division',
            'assignee',
            'histories.user'
        ]);
        
        $event = $task->event;
        
        return view('user.event.task.show', compact('task', 'event'));
    }
    
    public function storeProgress(Request $request, Tasks $task)
    {
        $validated = $request->validate([
            'new_status' => 'required|in:Assigned,Revision,Blocked,Submitted,UnderReview_Div,UnderReview_CC,Completed',
            'note' => 'nullable|string',
            'document' => 'nullable|file|max:5120'
        ]);
        
        $path = null;
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('task-documents', 'public');
        }
        
        TaskHistories::create([
            'task_id' => $task->task_id,
            'old_status' => $task->status,
            'new_status' => $validated['new_status'],
            'note' => $validated['note'],
            'changed_by' => Auth::id(),
            'document_path' => $path,
        ]);
        
        $task->update(['status' => $validated['new_status']]);
        
        return back()->with('success', 'Progress updated');
    }
}