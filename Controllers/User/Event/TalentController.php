<?php

namespace App\Http\Controllers\User\Event;

use App\Http\Controllers\Controller;
use App\Models\Talent;
use App\Models\Event;
use Illuminate\Http\Request;

class TalentController extends Controller
{
    public function index($eventId)
    {
        $event = Event::findOrFail($eventId);
        $talents = Talent::where('event_id', $eventId)
            ->orderBy('performance_date', 'asc')
            ->get();

        // Summary by type
        $summary = [
            'Solo' => $talents->where('talent_type', 'Solo')->count(),
            'Band' => $talents->where('talent_type', 'Band')->count(),
            'Performer' => $talents->where('talent_type', 'Performer')->count(),
        ];

        return view('user.event.talent.index', compact('event', 'talents', 'summary', 'eventId'));
    }

    public function store(Request $request, $eventId)
    {
        $validated = $request->validate([
            'talent_name' => 'required|string|max:255',
            'talent_type' => 'required|in:Solo,Band,Performer',
            'talent_fee' => 'nullable|numeric|min:0',
            'performance_date' => 'nullable|date',
            'contact_info' => 'nullable|string|max:255',
        ]);

        $validated['event_id'] = $eventId;

        Talent::create($validated);

        return redirect()->route('event.talent.index', $eventId)->with('success', 'Talent added successfully! 🔥');
    }

    public function update(Request $request, $eventId, $talentId)
    {
        $talent = Talent::findOrFail($talentId);

        $validated = $request->validate([
            'talent_name' => 'required|string|max:255',
            'talent_type' => 'required|in:Solo,Band,Performer',
            'talent_fee' => 'nullable|numeric|min:0',
            'performance_date' => 'nullable|date',
            'contact_info' => 'nullable|string|max:255',
        ]);

        $talent->update($validated);

        return redirect()->route('event.talent.index', $eventId)->with('success', 'Talent updated successfully! 🎉');
    }

    public function destroy($eventId, $talentId)
    {
        $talent = Talent::findOrFail($talentId);
        $talent->delete();

        return redirect()->route('event.talent.index', $eventId)->with('success', 'Talent deleted successfully!');
    }
}