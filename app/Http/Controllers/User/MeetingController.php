<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Meetings;
use App\Models\Event;
use App\Models\EventDivMember;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
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
        
        $meetings = Meetings::with('event')
            ->whereIn('event_id', $eventIds)
            ->orderBy('meeting_date', 'asc')
            ->orderBy('meeting_time', 'asc')
            ->get();
        
        $events = Event::whereIn('event_id', $eventIds)
            ->orderBy('event_name')
            ->get();
        
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
        
        if ($request->event_id) {
            $this->authorizeEventAccess($request->event_id);
        }
        
        $validated['created_by'] = Auth::id();
        
        Meetings::create($validated);
        
        return redirect()
            ->route('meetings.index')
            ->with('success', 'Meeting created successfully');
    }
    
    public function update(Request $request, $meetingId)
    {
        $meeting = Meetings::findOrFail($meetingId);
        
        $this->authorizeEventAccess($meeting->event_id);
        
        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,event_id',
            'meeting_name' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'meeting_time' => 'required',
            'meeting_place' => 'required|string|max:255',
            'agenda' => 'nullable|string',
        ]);
        
        if ($request->event_id && $request->event_id != $meeting->event_id) {
            $this->authorizeEventAccess($request->event_id);
        }
        
        $meeting->update($validated);
        
        return redirect()
            ->route('meetings.index')
            ->with('success', 'Meeting updated successfully');
    }
    
    public function updateNotes(Request $request, $meetingId)
    {
        $meeting = Meetings::findOrFail($meetingId);
        
        $this->authorizeEventAccess($meeting->event_id);
        
        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);
        
        $meeting->update($validated);
        
        return redirect()
            ->route('meetings.index')
            ->with('success', 'Meeting notes saved successfully');
    }
    
    public function destroy($meetingId)
    {
        $meeting = Meetings::findOrFail($meetingId);
        
        $this->authorizeEventAccess($meeting->event_id);
        
        $meeting->delete();
        
        return redirect()
            ->route('meetings.index')
            ->with('success', 'Meeting deleted');
    }
    

    private function authorizeEventAccess($eventId)
    {
        if (!$eventId) {
            return;
        }
        
        $userId = Auth::id();
        
        $isMember = EventDivMember::whereHas('division', function ($q) use ($eventId) {
                $q->where('event_id', $eventId);
            })
            ->where('user_id', $userId)
            ->exists();
        
        if (!$isMember) {
            abort(403, 'You are not authorized to access this event.');
        }
    }
}