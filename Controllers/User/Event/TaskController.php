<?php
namespace App\Http\Controllers\User\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tasks;
use App\Models\Event;
use App\Models\Division;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\EventDivMember;
use App\Models\TaskHistories;

class TaskController extends Controller
{
    // Ini untuk per EVENT (/user/event/{event}/task)
    public function index($eventId)
    {
        // Filter tasks by event
        $tasks = Tasks::where('event_id', $eventId)
            ->with(['assignee', 'division', 'event'])
            ->orderBy('deadline')
            ->get();
        
        $event = Event::findOrFail($eventId);
        $events = Event::all();
        
        // Filter divisions by event
        $divisions = Division::where('event_id', $eventId)->get();
        
        // Filter users by divisions in this event
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
        
        return view('user.event.task.index', compact('tasks', 'event', 'events', 'divisions', 'usersByDivision', 'eventId'));
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

        return view('user.event.task.show', compact('task'));
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

        // update task status
        $task->update(['status' => $validated['new_status']]);

        return back()->with('success', 'Progress updated 🚀');
    }

}