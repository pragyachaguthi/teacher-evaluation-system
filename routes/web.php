<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\EvaluationController;
use App\Http\Middleware\NoCache;





//

// Home
Route::get('/', function () {
    return view('home');
})->name('home');

// Registration
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Login
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



/*Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/student/evaluate/{teacher}', [StudentController::class, 'showEvaluationForm'])->name('student.evaluate.form');
    Route::post('/student/evaluate/{teacher}', [StudentController::class, 'submitEvaluation'])->name('student.evaluate.submit');
});*/




Route::middleware([NoCache::class,'auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Teacher Management
    Route::get('/admin/teachers', [AdminController::class, 'teachers'])->name('admin.teachers');
    Route::post('/admin/teachers', [AdminController::class, 'storeTeacher'])->name('admin.teachers.store');
     // 👇 Add these routes for edit, update, and delete
    Route::get('/admin/teachers/{id}/edit', [AdminController::class, 'editTeacher'])->name('admin.teachers.edit');
    Route::post('/admin/teachers/{id}/update', [AdminController::class, 'updateTeacher'])->name('admin.teachers.update');
    Route::delete('/admin/teachers/{id}', [AdminController::class, 'deleteTeacher'])->name('admin.teachers.delete');
    Route::put('/admin/teachers/{id}/update', [TeacherController::class, 'update'])->name('admin.teachers.update');


    // Student Management
    // Admin Students listing
    Route::get('/admin/students', [StudentController::class, 'index'])
        ->name('admin.students');

    Route::get('/admin/students', [StudentController::class, 'index'])->name('admin.students.index');
    // DELETE FIRST (to avoid Laravel matching it accidentally)
    Route::delete('/admin/students/{student}', [StudentController::class, 'destroy'])->name('admin.students.destroy');
    // PUT AFTER DELETE (safe order)
    Route::put('/admin/students/{student}', [StudentController::class, 'update'])->name('admin.students.update');
    //Route::get('/admin/students', [StudentController::class, 'index'])
    //->name('admin.students.index');
    Route::post('/admin/students', [StudentController::class, 'store'])
    ->name('admin.students.store');
    
    
    
    // criteria
    Route::get('/admin/criteria', [AdminController::class, 'criteria'])
         ->name('admin.criteria');
    // Store new criteria
    Route::post('/admin/criteria/store', [AdminController::class, 'storeCriteria'])
         ->name('admin.criteria.store');
         // Update criteria
    Route::put('/admin/criteria/{id}', [AdminController::class, 'updateCriteria'])
        ->name('admin.criteria.update');

    // Delete criteria
    Route::delete('/admin/criteria/{id}', [AdminController::class, 'deleteCriteria'])
        ->name('admin.criteria.delete');



    // teachers listing in wiew reports.
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/teachers', [AdminController::class, 'teachers'])->name('admin.teachers');

    // Teacher reports
    Route::get('/admin/reports', [AdminController::class, 'reportsList'])->name('admin.reports'); // List all teachers
    Route::get('/admin/report/{teacher}', [AdminController::class, 'report'])->name('admin.report');   // Detailed report

});


// Teacher Routes
Route::middleware([NoCache::class, 'auth', 'role:teacher'])->group(function () {
    Route::get('/teacher/teacherDashboard', [TeacherController::class, 'dashboard'])->name('teacher.teacherDashboard');
});


Route::middleware([NoCache::class, 'auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');

        // Evaluation form for a teacher
    Route::get('/student/evaluate/{teacher}', [EvaluationController::class, 'show'])->name('evaluate.show');

    // Submit evaluation
    Route::post('/student/evaluate', [EvaluationController::class, 'store'])->name('evaluate.store');

     /*// View list of teachers
    Route::get('/student/teachers', [StudentController::class, 'teachers'])->name('student.teachers');

    // Submit evaluation for a teacher
    Route::get('/student/evaluate/{teacher}', [StudentController::class, 'showEvaluationForm'])->name('student.evaluate');
    Route::post('/student/evaluate/{teacher}', [StudentController::class, 'submitEvaluation'])->name('student.evaluate.submit');*/
});


Route::middleware([NoCache::class, 'auth'])->group(function () {
    Route::get('/change-password', [PasswordController::class, 'showChangeForm'])->name('password.changeForm');
    Route::post('/change-password', [PasswordController::class, 'changePassword'])->name('password.change');
});


/*// Student evaluation form
Route::get('/evaluate', [EvaluationController::class, 'create'])->name('evaluate.show');

// Submit evaluation
Route::post('/evaluate', [EvaluationController::class, 'store'])->name('evaluate.store');*/





