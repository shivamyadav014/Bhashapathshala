<?php

use App\Http\Controllers\Web\AdminDashboardController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\InstructorDashboardController;
use App\Http\Controllers\Web\LearningController;
use App\Http\Controllers\Web\StudentDashboardController;
use App\Models\Course;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $courses = Course::with('instructor')
        ->where('is_published', true)
        ->orderBy('title')
        ->take(6)
        ->get();

    return view('welcome', compact('courses'));
})->name('home');

Route::get('/courses', [LearningController::class, 'browse'])->name('courses.index');
Route::get('/courses/{course}', [LearningController::class, 'showCourse'])->name('courses.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'handleLogin']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'handleRegister']);

    // Forgot/Reset Password
    Route::get('/password/forgot', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'instructor' => redirect()->route('instructor.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default => redirect('/'),
        };
    })->name('dashboard');

    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::get('/settings', [AuthController::class, 'settings'])->name('settings');
    Route::put('/settings', [AuthController::class, 'updateSettings']);

    Route::post('/courses/{course}/enroll', [LearningController::class, 'enroll'])->name('courses.enroll');

    Route::get('/lessons/{lesson}', [LearningController::class, 'showLesson'])->name('lessons.show');
    Route::post('/lessons/{lesson}/complete', [LearningController::class, 'completeLesson'])->name('lessons.complete');

    Route::get('/exercises/{exercise}', [LearningController::class, 'showExercise'])->name('exercises.show');
    Route::post('/exercises/{exercise}/submit', [LearningController::class, 'submitExercise'])->name('exercises.submit');

    Route::get('/quizzes/{quiz}', [LearningController::class, 'showQuiz'])->name('quizzes.show');
    Route::post('/quizzes/{quiz}/submit', [LearningController::class, 'submitQuiz'])->name('quizzes.submit');
    Route::get('/quizzes/{quiz}/attempts', [LearningController::class, 'quizHistory'])->name('quizzes.history');

    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/my-courses', [StudentDashboardController::class, 'myCourses'])->name('my-courses');
        Route::get('/performance', [StudentDashboardController::class, 'performance'])->name('performance');
    });

    Route::middleware('role:instructor')->prefix('instructor')->name('instructor.')->group(function () {
        Route::get('/dashboard', [InstructorDashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/courses', [InstructorDashboardController::class, 'courses'])->name('courses');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/courses', [AdminDashboardController::class, 'courses'])->name('courses');
        Route::put('/courses/{course}/status', [AdminDashboardController::class, 'updateCourseStatus'])->name('courses.status');
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
        Route::put('/users/{user}/role', [AdminDashboardController::class, 'updateUserRole'])->name('users.role');
        Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');
    });
});
