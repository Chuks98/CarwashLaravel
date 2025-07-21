<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // ✅ Default dashboard page
    public function index()
    {
        // Get user and role from session
        $user = session('user');
        $role = session('role');

        // If not logged in, redirect to login
        if (!$user) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        return view('dashboard.home', [
            'page' => 'dashboard.pages.profile',
            'user' => $user,
            'role' => $role
        ]);
    }

    // ✅ Dynamic dashboard pages (/dashboard/{page})
    public function show($page)
    {
        // Get user and role from session
        $user = session('user');
        $role = session('role');

        // If not logged in, redirect to login
        if (!$user) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        return view('dashboard.home', [
            'page' => "dashboard.pages.$page",
            'user' => $user,
            'role' => $role
        ]);
    }
}

