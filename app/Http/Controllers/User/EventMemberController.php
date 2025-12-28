<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\EventDivMember;
use Illuminate\Http\Request;

class EventMemberController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'division_id' => 'required|exists:divisions,division_id',
            'user_id' => 'required|exists:users,user_id',
            'role_in_division' => 'required|in :Member,Leader',
        ]);

        EventDivMember::create($request->all());

        return back()->with('success', 'Member added');
    }
}
