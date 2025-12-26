<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProgramSemesterCode;

class ProgramSemesterCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        $year = date('Y');

        $codes = [

            // ===== BIM (Management) =====
            ['faculty' => 'Management', 'program' => 'BIM', 'semester' => 1, 'code' => "BIM-1-$year"],
            ['faculty' => 'Management', 'program' => 'BIM', 'semester' => 2, 'code' => "BIM-2-$year"],
            ['faculty' => 'Management', 'program' => 'BIM', 'semester' => 3, 'code' => "BIM-3-$year"],
            ['faculty' => 'Management', 'program' => 'BIM', 'semester' => 4, 'code' => "BIM-4-$year"],
            ['faculty' => 'Management', 'program' => 'BIM', 'semester' => 5, 'code' => "BIM-5-$year"],
            ['faculty' => 'Management', 'program' => 'BIM', 'semester' => 6, 'code' => "BIM-6-$year"],
            ['faculty' => 'Management', 'program' => 'BIM', 'semester' => 7, 'code' => "BIM-7-$year"],
            ['faculty' => 'Management', 'program' => 'BIM', 'semester' => 8, 'code' => "BIM-8-$year"],

            // ===== BBA (Management) =====
            ['faculty' => 'Management', 'program' => 'BBA', 'semester' => 1, 'code' => "BBA-1-$year"],
            ['faculty' => 'Management', 'program' => 'BBA', 'semester' => 2, 'code' => "BBA-2-$year"],
            ['faculty' => 'Management', 'program' => 'BBA', 'semester' => 3, 'code' => "BBA-3-$year"],
            ['faculty' => 'Management', 'program' => 'BBA', 'semester' => 4, 'code' => "BBA-4-$year"],
            ['faculty' => 'Management', 'program' => 'BBA', 'semester' => 5, 'code' => "BBA-5-$year"],
            ['faculty' => 'Management', 'program' => 'BBA', 'semester' => 6, 'code' => "BBA-6-$year"],
            ['faculty' => 'Management', 'program' => 'BBA', 'semester' => 7, 'code' => "BBA-7-$year"],
            ['faculty' => 'Management', 'program' => 'BBA', 'semester' => 8, 'code' => "BBA-8-$year"],

            // ===== BSc CSIT (Science) =====
            ['faculty' => 'Science', 'program' => 'BSc CSIT', 'semester' => 1, 'code' => "CSIT-1-$year"],
            ['faculty' => 'Science', 'program' => 'BSc CSIT', 'semester' => 2, 'code' => "CSIT-2-$year"],
            ['faculty' => 'Science', 'program' => 'BSc CSIT', 'semester' => 3, 'code' => "CSIT-3-$year"],
            ['faculty' => 'Science', 'program' => 'BSc CSIT', 'semester' => 4, 'code' => "CSIT-4-$year"],
            ['faculty' => 'Science', 'program' => 'BSc CSIT', 'semester' => 5, 'code' => "CSIT-5-$year"],
            ['faculty' => 'Science', 'program' => 'BSc CSIT', 'semester' => 6, 'code' => "CSIT-6-$year"],
            ['faculty' => 'Science', 'program' => 'BSc CSIT', 'semester' => 7, 'code' => "CSIT-7-$year"],
            ['faculty' => 'Science', 'program' => 'BSc CSIT', 'semester' => 8, 'code' => "CSIT-8-$year"],
        ];

        foreach ($codes as $c) {
            ProgramSemesterCode::updateOrCreate(
                ['code' => $c['code']], // match by code (unique)
                [
                    'faculty' => $c['faculty'],
                    'program' => $c['program'],
                    'semester' => $c['semester'],
                ]
            );
        }

    }

}
