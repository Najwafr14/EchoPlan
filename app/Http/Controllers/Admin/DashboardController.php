<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $inactiveUsers = User::where('status', 'inactive')->count();

        $users = User::orderBy('user_id', 'desc')->get();

        return view('admin.dashboard', compact('totalUsers', 'activeUsers', 'inactiveUsers', 'users'));
    }
}
