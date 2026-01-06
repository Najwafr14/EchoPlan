<?php

namespace App\Http\Controllers\User\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategories;
use App\Models\Budget;
use App\Models\Division;
use App\Models\DivisionType;
use App\Models\EventDivMember;
use App\Models\Tasks;
use App\Models\Meetings;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::whereHas('members', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->withCount([
                'tasks as total_tasks',
                'tasks as completed_tasks' => function ($q) {
                    $q->where('status', 'Completed');
                }
            ]);
        
        if ($request->filled('search')) {
            $query->where('event_name', 'like', '%' . $request->search . '%');
        }
        
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'name_asc':
                $query->orderBy('event_name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('event_name', 'desc');
                break;
            case 'date_asc':
                $query->orderBy('event_date', 'asc');
                break;
            case 'date_desc':
                $query->orderBy('event_date', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }
        
        $events = $query->get();
        
        return view('user.event.index', compact('events'));
    }

    public function create()
    {
        $categories = EventCategories::orderBy('category_name')->get();

        return view('user.event.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_name'        => 'required|string|max:255',
            'event_description' => 'nullable|string',
            'event_date'        => 'required|date',
            'category_id'       => 'required|exists:eventcategories,category_id',
        ]);

        DB::transaction(function () use ($request) {

            $event = Event::create([
                'event_name'        => $request->event_name,
                'event_description' => $request->event_description,
                'event_date'        => $request->event_date,
                'category_id'       => $request->category_id,
                'created_by'        => Auth::id(),
            ]);

            $ccType = DivisionType::where('type_name', 'Chief of Committee')->firstOrFail();

            $division = Division::create([
                'event_id'         => $event->event_id,
                'division_type_id' => $ccType->division_type_id,
                'division_name'    => 'Chief of Committee',
            ]);

            EventDivMember::create([
                'division_id'      => $division->division_id,
                'user_id'          => Auth::id(),
                'role_in_division' => 'Leader',
            ]);
        });

        return redirect()
            ->route('event.index')
            ->with('success', 'Event berhasil dibuat 🎉');
    }

    public function show(Event $event)
    {
        $totalTasks = Tasks::where('event_id', $event->event_id)->count();
        $completedTasks = Tasks::where('event_id', $event->event_id)
            ->where('status', 'Completed')
            ->count();

        $revisionTasks = Tasks::where('event_id', $event->event_id)
            ->where('status', 'Revision')
            ->count();

        $needAttentionTasks = Tasks::where('event_id', $event->event_id)
            ->whereIn('status', ['Blocked', 'UnderReview_Div', 'UnderReview_CC'])
            ->count();

        $progressPercent = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100)
            : 0;

        $nextMeeting = Meetings::where('event_id', $event->event_id)
            ->where('meeting_date', '>=', now())
            ->orderBy('meeting_date', 'asc')
            ->first();

        $totalIncome = Budget::where('event_id', $event->event_id)
            ->where('transaction_type', 'income')
            ->sum('amount');
            
        $totalExpense = Budget::where('event_id', $event->event_id)
            ->where('transaction_type', 'expense')
            ->sum('amount');

        $primaryVenue = Venue::where('event_id', $event->event_id)
        ->where('is_primary', true)
        ->first();

        return view('user.event.show', compact(
            'event',
            'totalTasks',
            'completedTasks',
            'revisionTasks',
            'needAttentionTasks',
            'progressPercent',
            'nextMeeting',
            'totalIncome',
            'totalExpense',
            'primaryVenue'
        ));
    }
}
