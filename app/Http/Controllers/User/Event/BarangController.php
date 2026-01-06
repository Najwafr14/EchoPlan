<?php

namespace App\Http\Controllers\User\Event;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Event;
use App\Models\Vendor;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index($eventId)
    {
        $event = Event::findOrFail($eventId);
        $barangs = Barang::with('vendor')
            ->where('event_id', $eventId)
            ->orderBy('item_name')
            ->get();
        
        $vendors = Vendor::where('event_id', $eventId)
            ->orderBy('vendor_name')
            ->get();

        return view('user.event.barang.index', compact(
            'event',
            'barangs',
            'vendors',
            'eventId'
        ));
    }

    public function store(Request $request, $eventId)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'item_type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'item_status' => 'required|in:Sewa,Pinjam',
            'vendor_id' => 'nullable|exists:vendor,vendor_id',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $validated['event_id'] = $eventId;

        Barang::create($validated);

        return redirect()
            ->route('event.barang.index', $eventId)
            ->with('success', 'Item added successfully');
    }

    public function update(Request $request, $eventId, $itemId)
    {
        $barang = Barang::findOrFail($itemId);

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'item_type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'item_status' => 'required|in:Sewa,Pinjam',
            'vendor_id' => 'nullable|exists:vendor,vendor_id',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $barang->update($validated);

        return redirect()
            ->route('event.barang.index', $eventId)
            ->with('success', 'Item updated successfully');
    }

    public function destroy($eventId, $itemId)
    {
        Barang::findOrFail($itemId)->delete();

        return redirect()
            ->route('event.barang.index', $eventId)
            ->with('success', 'Item deleted');
    }
}