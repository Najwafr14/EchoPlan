<?php

namespace App\Http\Controllers\User\Event;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\Event;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function index($eventId)
    {
        $event = Event::findOrFail($eventId);

        $venues = Venue::where('event_id', $eventId)
            ->orderByDesc('is_primary')
            ->get();

        return view('user.event.venue.index', compact(
            'event',
            'venues',
            'eventId'
        ));
    }

    public function store(Request $request, $eventId)
    {
        $validated = $request->validate([
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'required|string',
            'venue_capacity' => 'nullable|integer|min:0',
            'venue_price' => 'nullable|numeric|min:0',
            'contact_person' => 'nullable|string|max:255',
            'is_primary' => 'nullable|boolean',
        ]);

        $validated['event_id'] = $eventId;
        $validated['is_primary'] = $request->has('is_primary');

        if ($validated['is_primary']) {
            Venue::where('event_id', $eventId)->update(['is_primary' => false]);
        }

        Venue::create($validated);

        return redirect()
            ->route('event.venue.index', $eventId)
            ->with('success', 'Venue added successfully 🏟️');
    }

    public function update(Request $request, $eventId, $venueId)
    {
        $venue = Venue::findOrFail($venueId);

        $validated = $request->validate([
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'required|string',
            'venue_capacity' => 'nullable|integer|min:0',
            'venue_price' => 'nullable|numeric|min:0',
            'contact_person' => 'nullable|string|max:255',
            'is_primary' => 'nullable|boolean',
        ]);

        $validated['is_primary'] = $request->has('is_primary');

        if ($validated['is_primary']) {
            Venue::where('event_id', $eventId)->update(['is_primary' => false]);
        }

        $venue->update($validated);

        return redirect()
            ->route('event.venue.index', $eventId)
            ->with('success', 'Venue updated successfully ✨');
    }

    public function destroy($eventId, $venueId)
    {
        Venue::findOrFail($venueId)->delete();

        return redirect()
            ->route('event.venue.index', $eventId)
            ->with('success', 'Venue deleted 🗑️');
    }
}
