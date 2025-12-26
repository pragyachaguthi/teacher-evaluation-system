<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionnaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Questionnaire::create(['question' => 'Punctuality of the teacher']);
        Questionnaire::create(['question' => 'Knowledge of the subject']);
        Questionnaire::create(['question' => 'Communication skills']);
        Questionnaire::create(['question' => 'Use of teaching aids and methods']);
        Questionnaire::create(['question' => 'Encourages student participation']);
    }
    
}