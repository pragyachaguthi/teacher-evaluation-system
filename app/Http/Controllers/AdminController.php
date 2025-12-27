<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Evaluation;
use App\Models\Criteria;
use App\Models\Student;

use Illuminate\Support\Str;
use Carbon\Carbon;


class AdminController extends Controller
{
    public function dashboard()
    {
        


        $teachers = Teacher::count();
        $students = Student::count();
        $evaluations = Evaluation::count();
        return view('admin.dashboard', compact('teachers', 'students', 'evaluations'));
    }

    public function teachers()
    {
        $teachers = Teacher::all();
        return view('admin.teachers', compact('teachers'));
    }

    public function storeTeacher(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Add email validation
        ]);

        // 1️⃣ Create a user first
        $randomPassword = Str::random(8); // auto-generate password
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($randomPassword),
            'role' => 'teacher', // optional if you have a role column
            'must_change_password' => true,
        ]);

        // 2️⃣ Then create a teacher linked to that user
        Teacher::create([
            'user_id' => $user->id, // make sure your teachers table has this column
            'name' => $request->name,
            'course' => $request->course,
        ]);

        return redirect()->route('admin.teachers')
                ->with('success', "Teacher added successfully. Temporary password: $randomPassword");   
    }

    public function editTeacher($id)
    {
        $teacher = Teacher::findOrFail($id);
        return response()->json($teacher); // For AJAX-based editing
    }

    public function updateTeacher(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'course' => 'required',
            'faculty' => 'required',
            'program' => 'required',
            'semester' => 'required',
        ]);

        $teacher = Teacher::findOrFail($id);
        $teacher->update([
            'name' => $request->name,
            'course' => $request->course,
            'faculty' => $request->faculty,
            'program' => $request->program,
            'semester' => $request->semester,
        ]);

        return redirect()->route('admin.teachers')->with('success', 'Teacher updated successfully');
    }

    public function deleteTeacher($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return redirect()->route('admin.teachers')->with('success', 'Teacher deleted successfully');
    }


        

    public function students()
    {
        $students = User::where('role', 'student')->get();
        return view('admin.students', compact('students'));
    }
     public function create() {
        return view('admin.students_create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
        ]);

        Student::create($request->all());

        return redirect()->route('admin.students')->with('success', 'Student added successfully.');
    }

    public function edit(Student $student) {
        return view('admin.students_edit', compact('student'));
    }

    public function update(Request $request, Student $student) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
        ]);

        $student->update($request->all());

        return redirect()->route('admin.students.update')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
     {
        $student->delete();
        return redirect()->route('admin.students.destroy')->with('success', 'Student deleted successfully.');
    }


    

    public function criteria()
    {
        $criterias = Criteria::all();
        return view('admin.criteria', compact('criterias'));
    }

    public function storeCriteria(Request $request)
    {
        $request->validate(['criteria' => 'required']);
        Criteria::create(['criteria' => $request->criteria]);
        return redirect()->route('admin.criteria')->with('success', 'criteria added');
    }

    public function updateCriteria(Request $request, $id)
    {
        $request->validate(['criteria' => 'required']);

        $criteria = Criteria::findOrFail($id);
        $criteria->update(['criteria' => $request->criteria]);

        return back()->with('success', 'Criteria updated successfully');
    }

    public function deleteCriteria($id)
    {
        Criteria::findOrFail($id)->delete();
        return back()->with('success', 'Criteria deleted successfully');
    }

    // report list
    public function reportsList()
    {
        $teachers = Teacher::all();
        return view('admin.reports', compact('teachers'));
    }



    public function report(Teacher $teacher , Request $request)
    {
        // 1) Read month/year from query, fallback to current
        $year  = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        // 2) Filter evaluations by teacher + month + year
        $evaluations = Evaluation::where('teacher_id', $teacher->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        // 3) If none for that month, you can either:
        //    a) show empty state on the same page
        //    b) or fall back to all evaluations
        if ($evaluations->isEmpty()) {
            // Option A: show message in view
            $totalEvaluations = 0;
            $criteriaScores = [];
            $overall = 0;
            $topWords = [];
            $strengths = [];
            $weaknesses = [];
            $feedbacks = collect();

            return view('admin.report', compact(
                'teacher',
                'criteriaScores',
                'overall',
                'topWords',
                'strengths',
                'weaknesses',
                'feedbacks',
                'totalEvaluations',
                'year',
                'month'
            ));
        }


        $totalEvaluations = $evaluations->count();

        // CRITERIA SCORES (your original code)
        $criteriaList = \App\Models\Criteria::all();
        $criteriaScores = [];

        foreach ($criteriaList as $criteria) {
            $columnName = 'criteria' . $criteria->id;
            if (!\Schema::hasColumn('evaluations', $columnName)) {
                continue;
            }

            $avg = round($evaluations->avg($columnName), 1);
            $count = $evaluations->where($columnName, '!=', null)->count();

            $criteriaScores[] = [
                'name' => ucfirst($criteria->criteria),
                'avg'  => $avg,
                'count' => $count
            ];
        }

        $overall = round(collect($criteriaScores)->avg('avg'), 1);

        // WORD CLOUD (your original code)
       $feedbackText = strtolower($evaluations->pluck('feedback')->filter()->implode(' '));

        // 1) Stronger stop word list (add common fillers + school words)
        $stopWords = [
            'the','is','are','and','to','a','of','in','for','that','with','on','it','was','as','be','but',
            'this','they','very','will','can','you','your','our','their','have','has','had','from','too',
            'also','just','more','most','really','there','here','his','her','him','she','he','them',
            'teacher','sir','madam','class','classes','student','students','course','subject'
        ];

        // 2) Clean punctuation
        $words = str_replace(['.', ',', '?', '!', ';', '"', "'"], '', $feedbackText);
        $wordArray = array_filter(explode(' ', $words));

        // 3) OPTIONAL: only keep “interesting” words using your existing keywords
        $strengthKeywords = ['good','clear','friendly','helpful','excellent','knowledgeable','polite','respectful','supportive','great','amazing','best','nice','patient','understanding','engaging','organized','prepared','inspiring'];
        $weaknessKeywords = ['fast','slow','strict','late','boring','unclear','rude','confusing','loud','difficult','hard','poor','bad','unprepared','disorganized','uninterested'];

        $interestingWords = array_unique(array_merge($strengthKeywords, $weaknessKeywords));

        $wordFrequency = [];
        foreach ($wordArray as $w) {
            if (strlen($w) <= 3) continue;                 // skip very short words
            if (in_array($w, $stopWords)) continue;        // skip boring words
            if (!in_array($w, $interestingWords)) continue; // focus only on opinion words

            $wordFrequency[$w] = ($wordFrequency[$w] ?? 0) + 1;
        }

        arsort($wordFrequency);
        $topWords = array_slice($wordFrequency, 0, 15, true);

        // STRENGTHS & WEAKNESSES
        $strengthKeywords = ['good','clear','friendly','helpful','excellent','knowledgeable','polite','respectful','supportive','great','amazing','best','nice','patient'];
        $weaknessKeywords = ['fast','slow','strict','late','boring','unclear','rude','confusing','loud','difficult','hard','poor','bad'];

        $strengths = [];
        $weaknesses = [];
        foreach ($topWords as $word => $count) {
            if (in_array($word, $strengthKeywords)) $strengths[] = $word;
            if (in_array($word, $weaknessKeywords)) $weaknesses[] = $word;
        }

        $strengths = array_unique(array_slice($strengths, 0, 8));
        $weaknesses = array_unique(array_slice($weaknesses, 0, 8));

        $feedbacks = $evaluations->pluck('feedback')->filter()->values();

        return view('admin.report', compact(
            'teacher', 'criteriaScores', 'overall', 'topWords', 
            'strengths', 'weaknesses', 'feedbacks', 'totalEvaluations', 'year', 'month'
        ));
    }






}
