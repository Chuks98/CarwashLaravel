<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // ✅ Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $email = $request->email;
        $password = $request->password;

        // ✅ Check Admin table first
        $admin = Admin::where('email', $email)->first();
        if ($admin) {
            if (!Hash::check($password, $admin->password)) {
                return response()->json([
                    'message' => 'Incorrect password for admin account'
                ], 401);
            }

            // ✅ Correct password → log in & store role
            Auth::login($admin);
            session([
                'user' => $admin,   // or $user
                'role' => 'admin'   // or 'user'
            ]);
            info('Admin login successful');
            return response()->json([
                'message' => 'Admin login successful',
                'role'    => 'admin',
                'data'    => $admin
            ], 200);
        }

        // ✅ Check User table next
        $user = User::where('email', $email)->first();
        if ($user) {
            if (!Hash::check($password, $user->password)) {
                return response()->json([
                    'message' => 'Incorrect password for user account'
                ], 401);
            }

            // ✅ Correct password → log in & store role
            Auth::login($user);
            session([
                'user' => $user,
                'role' => 'user'
            ]);
            info('User login successful');
            return response()->json([
                'message' => 'User login successful',
                'role'    => 'user',
                'data'    => $user
            ], 200);
        }

        // ✅ Email not found in Admin or User
        info('No account found with this email');
        return response()->json([
            'message' => 'No account found with this email'
        ], 404);
    }
}
