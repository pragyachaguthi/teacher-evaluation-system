<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Evaluation;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Criteria;


class StudentController extends Controller
{
    // Student Dashboard - SHOW ONLY MATCHING TEACHERS
    public function dashboard()
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        $teachers = Teacher::where('faculty', $student->faculty)
            ->where('program', $student->program)
            ->where('semester', $student->semester)
            ->get();

        // 🔥 Use current month/year (or pass them from a selector if you add one later)
        $year  = now()->year;
        $month = now()->month;

        // 🔥 Only count evaluations for THIS month
        $evaluated = Evaluation::where('student_id', Auth::id())
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->pluck('teacher_id');  // Collection of teacher IDs for this month only

        $uniqueEvaluated = $evaluated->unique()->count();
        $pending = $teachers->count() - $uniqueEvaluated;

        return view('student.dashboard', compact(
            'student',
            'teachers',
            'evaluated',
            'uniqueEvaluated',
            'pending'
        ));
    }



    /*// Show evaluation form
    public function showEvaluationForm(Teacher $teacher)
    {
        // prevent double evaluation
        $already = Evaluation::where('teacher_id', $teacher->id)
            ->where('student_id', Auth::id())
            ->exists();

        if ($already) {
            return redirect()->route('student.dashboard')
                ->with('error', 'You have already evaluated this teacher.');
        }
        $criterias = Criteria::all(); // fetch all evaluation criteria

        return view('student.evaluate', compact('teacher','criterias'));
    }*/

    // Submit evaluation
    /*public function submitEvaluation(Request $request, Teacher $teacher)
    {
        // Validate ratings
        $request->validate([
            'ratings' => 'required|array',
            'ratings.*' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string',
        ]);

        // Prevent duplicate
        $already = Evaluation::where('teacher_id', $teacher->id)
            ->where('student_id', Auth::id())
            ->exists();

        if ($already) {
            return redirect()->route('student.dashboard')
                ->with('error', 'You already evaluated this teacher.');
        }

        // Save evaluation per criteria
        foreach ($request->ratings as $criteria_id => $rating) {
           Evaluation::create([
            'student_id' => Auth::id(),
            'teacher_id' => $teacher->id,
            'criteria1' => $request->criteria1,
            'criteria2' => $request->criteria2,
            'criteria3' => $request->criteria3,
            'feedback' => $request->feedback,
        ]);

        }

        return redirect()->route('student.dashboard')
            ->with('success', 'Evaluation submitted successfully!');
    }*/


    // Admin index
        public function index(Request $request)
        {
            $query = Student::with('user');

            // ⭐ Search by name or ID
            if ($request->filled('q')) {
                $query->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('id', $request->q);
            }

            // ⭐ Filter by program
            if ($request->filled('program')) {
                $query->where('program', $request->program);
            }

            // ⭐ Filter by semester
            if ($request->filled('semester')) {
                $query->where('semester', $request->semester);
            }

            // ⭐ Filter by course
            if ($request->filled('course')) {
                $query->where('course', 'like', '%' . $request->course . '%');
            }

            // ⭐ Pagination (10 per page)
            $students = $query->paginate(10)->withQueryString();

            return view('admin.students', compact('students'));
        }


    public function update(Request $request, Student $student) {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $student->update([
            'name' => $request->name,
            'program' => $request->program,
            'semester'=> $request->semester,

        ]);

        $student->user->update([
            'name' => $request->name,
            

            
        ]);

        return redirect()->back()->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student) {
        $student->user->delete();
        $student->delete();
        return redirect()->back()->with('success', 'Student deleted successfully.');
    }
}
