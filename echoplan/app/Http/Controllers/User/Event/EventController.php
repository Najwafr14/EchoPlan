<?php

namespace App\Http\Controllers\User\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategories;
use App\Models\Division;
use App\Models\DivisionType;
use App\Models\EventDivMember;
use App\Models\Tasks;
use App\Models\Meetings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::whereHas('members', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->withCount([
                // total task
                'tasks as total_tasks',

                // completed task
                'tasks as completed_tasks' => function ($q) {
                    $q->where('status', 'Completed');
                }
            ])
            ->latest()
            ->get();

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

            // 1️⃣ CREATE EVENT
            $event = Event::create([
                'event_name'        => $request->event_name,
                'event_description' => $request->event_description,
                'event_date'        => $request->event_date,
                'category_id'       => $request->category_id,
                'created_by'        => Auth::id(),
            ]);

            // 2️⃣ AMBIL DIVISION TYPE "Ketua Pelaksana"
            $ccType = DivisionType::where('type_name', 'Chief of Committee')->firstOrFail();

            // 3️⃣ BUAT DIVISI KETUA PELAKSANA
            $division = Division::create([
                'event_id'         => $event->event_id,
                'division_type_id' => $ccType->division_type_id,
                'division_name'    => 'Chief of Committee',
            ]);

            // 4️⃣ AUTO JOIN USER SEBAGAI KETUA
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
        // Task statistics (placeholder-friendly)
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

        // Progress
        $progressPercent = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100)
            : 0;

        // Next meeting (nullable)
        $nextMeeting = Meetings::where('event_id', $event->event_id)
            ->orderBy('meeting_date', 'asc')
            ->first();

        return view('user.event.show', compact(
            'event',
            'totalTasks',
            'completedTasks',
            'revisionTasks',
            'needAttentionTasks',
            'progressPercent',
            'nextMeeting'
        ));
    }
}
