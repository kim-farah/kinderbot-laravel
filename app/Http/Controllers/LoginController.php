<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Get user with role
        $user = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.email', $request->email)
            ->first();

        if ($user && password_verify($request->password, $user->password)) {
            // Store user in session
            session(['user_id' => $user->id, 'user_role' => $user->name]);

            // Redirect based on role
            if ($user->name == 'coordinator') {
                return redirect()->route('coordinator');
            } elseif ($user->name == 'teacher') {
                return redirect()->route('teacher');
            } elseif ($user->name == 'parent') {
                return redirect()->route('parent');
            }
        }

        return back()->withErrors(['email' => 'Invalid email or password']);
    }
}
