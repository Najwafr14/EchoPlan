<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Budget;
use App\Models\Division;
use App\Models\Tasks;
use App\Models\EventDivMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventReportController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::with('category', 'creator')
            ->orderBy('event_date', 'desc')
            ->get();
        
        $selectedEventId = $request->get('event_id');
        
        if (!$selectedEventId) {
            return view('owner.report.index', compact('events'));
        }
        
        $event = Event::with(['category', 'creator', 'primaryVenue'])
            ->findOrFail($selectedEventId);
        
        $chiefDivision = Division::with('members.user')
            ->where('event_id', $event->event_id)
            ->whereHas('divisionType', function($q) {
                $q->where('type_name', 'Chief of Committee');
            })
            ->first();
        
        $chiefOfCommittee = $chiefDivision?->members()
            ->where('role_in_division', 'Leader')
            ->first()
            ?->user;
        
        $totalTasks = Tasks::where('event_id', $event->event_id)->count();
        $completedTasks = Tasks::where('event_id', $event->event_id)
            ->where('status', 'Completed')
            ->count();
        
        $progressPercent = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100)
            : 0;
        
        $totalIncome = Budget::where('event_id', $event->event_id)
            ->where('transaction_type', 'income')
            ->sum('amount');
        
        $totalExpense = Budget::where('event_id', $event->event_id)
            ->where('transaction_type', 'expense')
            ->sum('amount');
        
        $currentBalance = $totalIncome - $totalExpense;
        
        $divisions = Division::with(['divisionType', 'members'])
            ->where('event_id', $event->event_id)
            ->get()
            ->map(function($division) {
                $divisionTasks = Tasks::where('division_id', $division->division_id)->get();
                $totalTasks = $divisionTasks->count();
                $completedTasks = $divisionTasks->where('status', 'Completed')->count();
                
                return [
                    'division_id' => $division->division_id,
                    'division_name' => $division->divisionType->type_name ?? $division->division_name,
                    'total_tasks' => $totalTasks,
                    'completed_tasks' => $completedTasks,
                    'progress_percent' => $totalTasks > 0 
                        ? round(($completedTasks / $totalTasks) * 100) 
                        : 0,
                    'members_count' => $division->members->count()
                ];
            })
            ->sortByDesc('progress_percent')
            ->values();
        
        $tasks = Tasks::with(['assignee', 'division.divisionType', 'event'])
            ->where('event_id', $event->event_id)
            ->orderBy('deadline', 'asc')
            ->get();
        
        $statusCounts = [
            'assigned' => Tasks::where('event_id', $event->event_id)->where('status', 'Assigned')->count(),
            'in_progress' => Tasks::where('event_id', $event->event_id)->where('status', 'InProgress')->count(),
            'revision' => Tasks::where('event_id', $event->event_id)->where('status', 'Revision')->count(),
            'blocked' => Tasks::where('event_id', $event->event_id)->where('status', 'Blocked')->count(),
            'under_review' => Tasks::where('event_id', $event->event_id)
                ->whereIn('status', ['UnderReview_Div', 'UnderReview_CC'])
                ->count(),
            'completed' => $completedTasks,
        ];
        
        return view('owner.report.index', compact(
            'events',
            'event',
            'selectedEventId',
            'chiefOfCommittee',
            'totalTasks',
            'completedTasks',
            'progressPercent',
            'totalIncome',
            'totalExpense',
            'currentBalance',
            'divisions',
            'tasks',
            'statusCounts'
        ));
    }
}