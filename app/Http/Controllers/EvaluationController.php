<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluation;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    // Show evaluation form for a single teacher
    public function show($teacherId)
    {
        $teacher = Teacher::findOrFail($teacherId);

        // Example criteria, can fetch from DB if you have a table
        $criterias = [
            (object)['criteria' => 'Knowledge'],
            (object)['criteria' => 'Teaching Skill'],
            (object)['criteria' => 'Communication'],
        ];

        return view('student.evaluate', compact('teacher', 'criterias'));
    }

    // Store evaluation
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'criteria1'  => 'required|integer|min:1|max:5',
            'criteria2'  => 'required|integer|min:1|max:5',
            'criteria3'  => 'required|integer|min:1|max:5',
            'feedback'   => 'nullable|string|max:500',
        ]);

        $studentId = Auth::id();
        $teacherId = $request->teacher_id;

        // 🔥 current month + year
        $year  = now()->year;
        $month = now()->month;

        // 🔥 has this student already evaluated this teacher THIS month?
        $alreadyEvaluated = Evaluation::where('student_id', $studentId)
            ->where('teacher_id', $teacherId)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->exists();

        if ($alreadyEvaluated) {
            return back()->with('error', 'You have already evaluated this teacher for this month.');
        }

        // ✅ allow one evaluation per teacher per month
        Evaluation::create([
            'student_id' => $studentId,
            'teacher_id' => $teacherId,
            'criteria1'  => $request->criteria1,
            'criteria2'  => $request->criteria2,
            'criteria3'  => $request->criteria3,
            'feedback'   => $request->feedback,
        ]);

        return redirect()->back()->with('success', 'Evaluation submitted successfully!');
    }

}



