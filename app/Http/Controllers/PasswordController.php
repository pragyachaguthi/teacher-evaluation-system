<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    public function showChangeForm()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Update password WITHOUT requiring current password for first login
        $user->update([
            'password' => Hash::make($request->new_password),
            'must_change_password' => false,
        ]);

        // Redirect based on role
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard')->with('success', 'Password changed successfully.');
        }

        if ($user->role === 'teacher') {
            return redirect('/teacher/teacherDashboard')->with('success', 'Password changed successfully.');
        }

        return redirect('/student/dashboard')->with('success', 'Password changed successfully.');
    }
}
