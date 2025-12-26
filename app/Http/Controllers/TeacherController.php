<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluation;
use App\Models\Teacher;
use App\Models\Criteria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class TeacherController extends Controller
{
    public function dashboard(Request $request)
    {
        $teacher = Teacher::where('name', Auth::user()->name)->first();
        if (!$teacher) {
            return redirect()->back()->with('error', 'No teacher profile found.');
        }

        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        
        $evaluations = Evaluation::where('teacher_id', $teacher->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        $totalEvaluations = $evaluations->count();
        
        // 🔥 CRITERIA SCORES (like your admin report)
        $criteriaList = \App\Models\Criteria::all();
        $criteriaScores = [];
        foreach ($criteriaList as $criteria) {
            $columnName = 'criteria' . $criteria->id;
            if (!\Schema::hasColumn('evaluations', $columnName)) continue;
            
            $avg = round($evaluations->avg($columnName), 1);
            $count = $evaluations->where($columnName, '!=', null)->count();
            
            $criteriaScores[] = [
                'name' => ucfirst($criteria->criteria),
                'avg' => $avg,
                'count' => $count,
                'id' => $criteria->id  // for chart ID
            ];
        }
        
        $overall = $criteriaScores ? round(collect($criteriaScores)->avg('avg'), 1) : 0;
        $feedbacks = $evaluations->pluck('feedback')->filter()->values();

        // 🔥 WORD CLOUD & STRENGTHS/WEAKNESSES
        $topWords = [];
        $strengths = [];
        $weaknesses = [];
        
        if ($feedbacks->isNotEmpty()) {
            $feedbackText = strtolower($feedbacks->implode(' '));
            $stopWords = ['the','is','are','and','to','a','of','in','for','that','with','on','it','was','as','be','but','this','they','very','will','can','you','your','teacher','class','student'];
            $words = str_replace(['.', ',', '?', '!', ';', '"', "'"], '', $feedbackText);
            $wordArray = array_filter(explode(' ', $words));
            
            $strengthKeywords = ['good','clear','friendly','helpful','excellent','knowledgeable','polite','great','nice','patient'];
            $weaknessKeywords = ['fast','slow','strict','late','boring','unclear','rude','confusing','hard','poor','bad'];
            
            $wordFrequency = [];
            foreach ($wordArray as $w) {
                if (strlen($w) <= 3 || in_array($w, $stopWords)) continue;
                if (in_array($w, array_merge($strengthKeywords, $weaknessKeywords))) {
                    $wordFrequency[$w] = ($wordFrequency[$w] ?? 0) + 1;
                }
            }
            
            arsort($wordFrequency);
            $topWords = array_slice($wordFrequency, 0, 12, true);
            
            foreach ($topWords as $word => $count) {
                if (in_array($word, $strengthKeywords)) $strengths[] = $word;
                if (in_array($word, $weaknessKeywords)) $weaknesses[] = $word;
            }
        }

        return view('teacher.teacherDashboard', compact(
            'teacher', 'evaluations', 'totalEvaluations', 'overall', 'feedbacks', 
            'year', 'month', 'criteriaScores', 'topWords', 'strengths', 'weaknesses'
        ));
    }


}
