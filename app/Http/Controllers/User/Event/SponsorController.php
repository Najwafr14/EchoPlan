<?php

namespace App\Http\Controllers\User\Event;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use App\Models\Event;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    public function index($eventId)
    {
        $event = Event::findOrFail($eventId);
        $sponsors = Sponsor::where('event_id', $eventId)
            ->orderByRaw("CASE 
                WHEN sponsor_type = 'Utama' THEN 1 
                WHEN sponsor_type = 'Pendukung' THEN 2 
                WHEN sponsor_type = 'Lainnya' THEN 3 
                ELSE 4 
            END")
            ->orderBy('sponsor_name')
            ->get();

        return view('user.event.sponsor.index', compact(
            'event',
            'sponsors',
            'eventId'
        ));
    }

    public function store(Request $request, $eventId)
    {
        $validated = $request->validate([
            'sponsor_name' => 'required|string|max:255',
            'sponsor_type' => 'required|in:Utama,Pendukung,Lainnya',
            'contribution_amount' => 'nullable|numeric|min:0',
            'contact_info' => 'nullable|string|max:255',
        ]);

        $validated['event_id'] = $eventId;

        Sponsor::create($validated);

        return redirect()
            ->route('event.sponsor.index', $eventId)
            ->with('success', 'Sponsor added successfully');
    }

    public function update(Request $request, $eventId, $sponsorId)
    {
        $sponsor = Sponsor::findOrFail($sponsorId);

        $validated = $request->validate([
            'sponsor_name' => 'required|string|max:255',
            'sponsor_type' => 'required|in:Utama,Pendukung,Lainnya',
            'contribution_amount' => 'nullable|numeric|min:0',
            'contact_info' => 'nullable|string|max:255',
        ]);

        $sponsor->update($validated);

        return redirect()
            ->route('event.sponsor.index', $eventId)
            ->with('success', 'Sponsor updated successfully');
    }

    public function destroy($eventId, $sponsorId)
    {
        Sponsor::findOrFail($sponsorId)->delete();

        return redirect()
            ->route('event.sponsor.index', $eventId)
            ->with('success', 'Sponsor deleted');
    }
}