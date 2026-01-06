<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tasks;
use App\Models\Event;
use App\Models\Division;
use App\Models\EventDivMember;
use App\Models\TaskHistories;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        $eventIds = EventDivMember::where('user_id', $userId)
            ->pluck('division_id')
            ->map(function($divisionId) {
                return Division::find($divisionId)->event_id ?? null;
            })
            ->filter()
            ->unique();
        
        $tasks = Tasks::whereIn('event_id', $eventIds)
            ->with(['assignee', 'division', 'event'])
            ->orderBy('deadline', 'asc')
            ->get();
        
        $userEvents = Event::whereIn('event_id', $eventIds)->get();
        
        $stats = [
            'total' => $tasks->count(),
            'completed' => $tasks->where('status', 'Completed')->count(),
            'in_progress' => $tasks->whereIn('status', ['Revision', 'UnderReview_Div', 'UnderReview_CC'])->count(),
            'pending' => $tasks->where('status', 'Assigned')->count(),
            'blocked' => $tasks->where('status', 'Blocked')->count(),
        ];
        
        return view('user.task.index', compact('tasks', 'userEvents', 'stats'));
    }

    public function show(Tasks $task)
    {
        $userId = Auth::id();
        $hasAccess = EventDivMember::where('user_id', $userId)
            ->whereHas('division', function($q) use ($task) {
                $q->where('event_id', $task->event_id);
            })
            ->exists();
        
        if (!$hasAccess) {
            abort(403, 'You do not have access to this task.');
        }
        
        $task->load([
            'event',
            'division',
            'assignee',
            'histories.user'
        ]);
        
        return view('user.task.show', compact('task'));
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

        return back()->with('success', 'Progress updated ');
    }
}