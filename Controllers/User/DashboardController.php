<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Tasks;
use App\Models\Meetings;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // ===== EVENT YANG DIA IKUTI (LEWAT TASK) =====
        $eventIds = Tasks::where('assigned_to', $userId)
            ->pluck('event_id')
            ->unique();

        // ===== SUMMARY CARDS =====
        // Total event yang user ikuti (sudah benar)
        $totalEvents = Event::whereIn('event_id', $eventIds)->count();

        // Task done milik user ini
        $taskDone = Tasks::where('assigned_to', $userId)
            ->where('status', 'Completed')
            ->count();

        // Task needs revision milik user ini
        $needsRevision = Tasks::where('assigned_to', $userId)
            ->where('status', 'Needs Revision')
            ->count();

        // Next meeting dari event yang dia ikuti
        $nextMeeting = Meetings::whereIn('event_id', $eventIds)
            ->where('meeting_date', '>=', now())
            ->orderBy('meeting_date', 'asc')
            ->first();

        // ===== ACTIVE EVENT PROGRESS =====
        // PERBAIKAN: Hitung progress dari TASK USER INI saja di setiap event
        // Filter hanya event yang belum selesai
        $activeEvents = Event::whereIn('event_id', $eventIds)
            ->where('event_date', '>=', now()) // Hanya event yang belum lewat
            ->get()
            ->map(function ($event) use ($userId) {
                // Ambil task user ini saja di event ini
                $userTasks = Tasks::where('event_id', $event->event_id)
                    ->where('assigned_to', $userId)
                    ->get();

                $totalTask = $userTasks->count();
                $doneTask = $userTasks->where('status', 'Completed')->count();

                return [
                    'name' => $event->event_name,
                    'deadline' => $event->event_date,
                    'progress' => $totalTask > 0
                        ? round(($doneTask / $totalTask) * 100)
                        : 0,
                    'total_task' => $totalTask,
                    'done_task' => $doneTask,
                ];
            })
            ->filter(function ($event) {
                // Buang event yang user tidak punya task sama sekali
                return $event['total_task'] > 0;
            })
            ->sortByDesc('progress') // Sort dari progress tertinggi
            ->take(5); // Limit 5 event

        // ===== RECENT TASK =====
        // Task terbaru milik user ini
        $recentTasks = Tasks::where('assigned_to', $userId)
            ->with(['event', 'division'])
            ->latest('created_at')
            ->limit(8)
            ->get();

        return view('user.dashboard', compact(
            'totalEvents',
            'taskDone',
            'needsRevision',
            'nextMeeting',
            'activeEvents',
            'recentTasks'
        ));
    }
}