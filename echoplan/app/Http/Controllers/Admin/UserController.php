<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('user_id', 'desc')->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'full_name' => 'required',
            'password' => 'required|min:6',
            'role' => 'required|in:Admin,User,Owner',
            'status' => 'required|in:active,inactive',
            'phone_number' => 'nullable|string|max:20',
            'domicile' => 'nullable|string|max:100',
            'born_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'full_name' => $request->full_name,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
            'phone_number' => $request->phone_number,
            'domicile' => $request->domicile,
            'born_date' => $request->born_date,
            'gender' => $request->gender,
        ]);

        return redirect()->route('admin.users')->with('success', 'User created');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'full_name' => 'required',
            'role' => 'required|in:Admin,User,Owner',
            'status' => 'required|in:active,inactive',
            'phone_number' => 'nullable|string|max:20',
            'domicile' => 'nullable|string|max:100',
            'born_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'username' => 'required|unique:users,username,' . $user->user_id . ',user_id',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'password' => 'nullable|min:6',
        ]);

        $data = $request->except('password');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'User updated');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted');
    }
}
