<?php

namespace App\Http\Controllers\User\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\DivisionType;
use App\Models\Division;
use App\Models\EventDivMember;
use App\Models\User;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    public function index(Event $event)
    {
        $divisionTypes = DivisionType::orderBy('type_name')->get();

        $divisions = Division::with(['divisionType', 'members.user'])
            ->where('event_id', $event->event_id)
            ->get()
            ->keyBy('division_type_id'); 

        $users = User::where('role', 'User')
            ->orderBy('full_name')
            ->get();

        return view('user.event.committee.index', compact(
            'event',
            'divisionTypes',
            'divisions',
            'users'
        ));
    }


    public function store(Request $request, Event $event)
    {
        $request->validate([
            'division_type_id' => 'required|exists:divisiontypes,division_type_id',
            'user_id' => 'required|exists:users,user_id',
            'role_in_division' => 'required|in:Leader,Member',
        ]);

        $division = Division::firstOrCreate(
            [
                'event_id' => $event->event_id,
                'division_type_id' => $request->division_type_id,
            ],
            [
                'division_name' => null,
            ]
        );

        EventDivMember::create([
            'division_id' => $division->division_id,
            'user_id' => $request->user_id,
            'role_in_division' => $request->role_in_division,
        ]);

        return back()->with('success', 'Member added 🔥');
    }
}
