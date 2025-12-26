<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class LoginController extends Controller
{
   public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            // Validate selected role
            if (strtolower($user->role) !== strtolower($request->role)) {
                Auth::logout();
                return redirect()->back()->withErrors(['role' => 'You selected the wrong role.']);
            }

            // 🔥 FORCE PASSWORD CHANGE FIRST (before dashboard redirect)
            if ($user->must_change_password) {
                return redirect()->route('password.changeForm')
                    ->with('info', 'You must change your password before continuing.');
            }

            // Normal role redirects
            if ($user->role === 'admin') return redirect('/admin/dashboard');
            if ($user->role === 'teacher') return redirect('/teacher/teacherDashboard');

            return redirect('/student/dashboard');
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home');
 // or your login route
    }
}