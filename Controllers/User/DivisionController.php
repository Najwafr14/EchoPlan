<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;

class DivisionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,event_id',
            'division_type_id' => 'required|exists:divisiontypes,division_type_id',
        ]);

        Division::create([
            'event_id' => $request->event_id,
            'division_type_id' => $request->division_type_id
        ]);

        return back()->with('success', 'Division added');
    }
}
