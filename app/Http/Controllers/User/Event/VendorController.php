<?php

namespace App\Http\Controllers\User\Event;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Event;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index($eventId)
    {
        $event = Event::findOrFail($eventId);
        $vendors = Vendor::where('event_id', $eventId)
            ->orderBy('vendor_name')
            ->get();

        return view('user.event.vendor.index', compact(
            'event',
            'vendors',
            'eventId'
        ));
    }

    public function store(Request $request, $eventId)
    {
        $validated = $request->validate([
            'vendor_name' => 'required|string|max:255',
            'vendor_service' => 'required|string|max:255',
            'cost' => 'nullable|numeric|min:0',
            'contact_info' => 'nullable|string|max:255',
        ]);

        $validated['event_id'] = $eventId;

        Vendor::create($validated);

        return redirect()
            ->route('event.vendor.index', $eventId)
            ->with('success', 'Vendor added successfully');
    }

    public function update(Request $request, $eventId, $vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);

        $validated = $request->validate([
            'vendor_name' => 'required|string|max:255',
            'vendor_service' => 'required|string|max:255',
            'cost' => 'nullable|numeric|min:0',
            'contact_info' => 'nullable|string|max:255',
        ]);

        $vendor->update($validated);

        return redirect()
            ->route('event.vendor.index', $eventId)
            ->with('success', 'Vendor updated successfully');
    }

    public function destroy($eventId, $vendorId)
    {
        Vendor::findOrFail($vendorId)->delete();

        return redirect()
            ->route('event.vendor.index', $eventId)
            ->with('success', 'Vendor deleted');
    }
}