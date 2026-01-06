<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Tasks;
use App\Models\Meetings;
use App\Models\Division;
use App\Models\EventDivMember;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        $divisionIds = EventDivMember::where('user_id', $userId)
            ->pluck('division_id')
            ->unique();
        
        $eventIds = Division::whereIn('division_id', $divisionIds)
            ->pluck('event_id')
            ->unique();
        
        $totalEvents = $eventIds->count();
        
        $totalTasks = 0;
        $completedTasks = 0;
        $revisionTasks = 0;
        $nextMeeting = null;
        
        if ($eventIds->isNotEmpty()) {
            $totalTasks = Tasks::whereIn('event_id', $eventIds)->count();
            
            $completedTasks = Tasks::whereIn('event_id', $eventIds)
                ->where('status', 'Completed')
                ->count();
            
            $revisionTasks = Tasks::whereIn('event_id', $eventIds)
                ->where('status', 'Revision')
                ->count();
            
            $nextMeeting = Meetings::whereIn('event_id', $eventIds)
                ->where('meeting_date', '>=', now())
                ->orderBy('meeting_date', 'asc')
                ->first();
        }
        
        $events = Event::whereIn('event_id', $eventIds)
            ->withCount([
                'tasks as total_tasks',
                'tasks as completed_tasks' => function ($q) {
                    $q->where('status', 'Completed');
                }
            ])
            ->latest()
            ->get();
        
        $tasks = Tasks::whereIn('event_id', $eventIds)
            ->with(['assignee', 'division', 'event'])
            ->orderBy('deadline', 'asc')
            ->get();
        
        $userEvents = Event::whereIn('event_id', $eventIds)->get();
        
        return view('user.dashboard', compact(
            'totalEvents',
            'totalTasks',
            'completedTasks',
            'revisionTasks',
            'nextMeeting',
            'events',
            'tasks',
            'userEvents'
        ));
    }
}