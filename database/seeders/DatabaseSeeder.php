<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Teacher;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
         // Default Admin
     // Default Admin
    User::create([
        'name' => 'Super Admin',
        'email' => 'admin@example.com',
        'password' => Hash::make('password123'),
        'role' => 'admin',
    ]);

    // Default Student
    User::create([
        'name' => 'Test Student',
        'email' => 'student@example.com',
        'password' => Hash::make('password123'),
        'role' => 'student',
    ]);

        User::create([
        'name' => 'Test Teacher',
        'email' => 'teacher@example.com',
        'password' => Hash::make('password123'),
        'role' => 'teacher',
    ]);



   

    // Call other seeders
    $this->call([
        //TeacherSeeder::class,
        QuestionnaireSeeder::class,
    ]);
    }
}
