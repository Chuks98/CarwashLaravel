<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    // ✅ Home page
    public function home()
    {
        $user = session('user');  // Get user data from session
        $role = session('role');  // 'admin' or 'user'

        return view('index.main-layout', [
            'page' => 'index.pages.index',
            'user' => $user,
            'role' => $role
        ]);
    }

    // ✅ Login page
    public function login()
    {
        if (session()->has('user')) {
            return redirect('/dashboard'); // already logged in → go to dashboard
        }
        return view('index.pages.login');
    }

    // ✅ Register page
    public function register()
    {
        if (session()->has('user')) {
            return redirect('/dashboard'); 
        }
        return view('index.pages.register');
    }

    // ✅ Logout
    public function logout(Request $request)
    {
        session()->forget(['user', 'role']); // Remove user data
        $request->session()->invalidate();   // Destroy session
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }

    // ✅ Dynamic catch-all pages (like /about, /services)
    public function dynamic($page)
    {
        $user = session('user');
        $role = session('role');

        return view('index.main-layout', [
            'page' => "index.pages.$page",
            'user' => $user,
            'role' => $role
        ]);
    }
}
