<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use App\Models\ProgramSemesterCode;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // 1️⃣ Validate user input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'program_code' => 'required|string|exists:program_semester_codes,code',
        ]);

        // 2️⃣ Fetch program info based on code
        $programData = ProgramSemesterCode::where('code', $request->program_code)->first();

        if (!$programData) {
            return back()->withErrors(['program_code' => 'Invalid program code.']);
        }

        // 3️⃣ Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        // 4️⃣ Create the student record
        Student::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'faculty' => $programData->faculty,
            'program' => $programData->program,
            'semester' => $programData->semester,
        ]);

        // 5️⃣ Redirect
        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }
}
