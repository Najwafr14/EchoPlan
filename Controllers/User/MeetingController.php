<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Meetings;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    public function index()
    {
        $meetings = Meetings::with('event')
            ->orderBy('meeting_date', 'asc')
            ->orderBy('meeting_time', 'asc')
            ->get();

        // Get all events untuk dropdown
        $events = Event::orderBy('event_name')->get();

        return view('user.meetings.index', compact('meetings', 'events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,event_id',
            'meeting_name' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'meeting_time' => 'required',
            'meeting_place' => 'required|string|max:255',
            'agenda' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();

        Meetings::create($validated);

        return redirect()
            ->route('meetings.index')
            ->with('success', 'Meeting created successfully 📅');
    }

    public function update(Request $request, $meetingId)
    {
        $meeting = Meetings::findOrFail($meetingId);

        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,event_id',
            'meeting_name' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'meeting_time' => 'required',
            'meeting_place' => 'required|string|max:255',
            'agenda' => 'nullable|string',
        ]);

        $meeting->update($validated);

        return redirect()
            ->route('meetings.index')
            ->with('success', 'Meeting updated successfully ✨');
    }

    public function updateNotes(Request $request, $meetingId)
    {
        $meeting = Meetings::findOrFail($meetingId);

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $meeting->update($validated);

        return redirect()
            ->route('meetings.index')
            ->with('success', 'Meeting notes saved successfully 📝');
    }

    public function destroy($meetingId)
    {
        Meetings::findOrFail($meetingId)->delete();

        return redirect()
            ->route('meetings.index')
            ->with('success', 'Meeting deleted 🗑️');
    }
}