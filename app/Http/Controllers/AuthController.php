<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Handle Login
    public function login(Request $request)
    {
        // 1. Get the data sent from the frontend
        $username = $request->input('username');
        $password = $request->input('password');

        // 2. Try to find the user in the database
        $user = AdminUser::where('username', $username)->first();

        // 3. Debug Check 1: Did we find the user?
        if (!$user) {
            return response()->json([
                'success' => false, 
                'error' => "Debug: Username '{$username}' not found in database!"
            ], 401);
        }

        // 4. Debug Check 2: Does the password match?
        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false, 
                'error' => "Debug: Incorrect password!"
            ], 401);
        }

        // 5. If both pass, log them in!
        session(['admin_logged_in' => true]);
        session(['admin_username' => $user->username]);
        
        return response()->json(['success' => true]);
    }

    // Check if user is logged in (for page refresh)
    public function check()
    {
        return response()->json([
            'success' => true,
            'logged_in' => session()->has('admin_logged_in')
        ]);
    }

    // Handle Logout
    public function logout()
    {
        session()->forget(['admin_logged_in', 'admin_username']);
        return response()->json(['success' => true]);
    }
}
