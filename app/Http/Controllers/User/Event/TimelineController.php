<?php

namespace App\Http\Controllers\User\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tasks;
use App\Models\Event;

class TimelineController extends Controller
{
    public function index(Event $event)
    {
        $tasks = Tasks::where('event_id', $event->event_id)
                     ->orderBy('deadline')
                     ->get()
                     ->groupBy('phase');

        return view('user.event.timeline.index', compact('event', 'tasks'));
    }
}
