<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meetings;

class MeetingController extends Controller
{
    public function index()
    {
        $meetings = Meetings::orderBy('meeting_date', 'asc')->get();
        return view('user.meetings.index', compact('meetings'));
    }

    public function create()
    {
        return view('user.meetings.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'meeting_name' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'meeting_time' => 'required',
            'meeting_place' => 'required|string|max:255',
            'agenda' => 'nullable|string',
        ]);

        Meetings::create([
            'event_id' => $request->event_id ?? null,
            'meeting_name' => $request->meeting_name,
            'meeting_date' => $request->meeting_date,
            'meeting_time' => $request->meeting_time,
            'meeting_place' => $request->meeting_place,
            'agenda' => $request->agenda,
            'notes' => null,
        ]);

        return redirect()
            ->route('user.meetings.index')
            ->with('success', 'Meeting created successfully');
    }

    public function show(Meetings $meeting)
    {
        return view('user.meetings.show', compact('meeting'));
    }
}
