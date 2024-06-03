<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::put('/admin/courses/{id}', [CourseController::class, 'update'])->name('courses.update');
    Route::get('/admin/courses/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::delete('admin/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/instructor/dashboard', [UserController::class, 'instructorDashboard'])->name('instructor.dashboard');
    Route::get('/admin/dashboard', [UserController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::post('/messages/reply', [MessageController::class, 'reply'])->name('messages.reply');
    Route::get('/messages/{id}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/dashboard', [CourseController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('admin/courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::get('/admin/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/admin/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::post('/courses/{course}/register', [CourseController::class, 'register'])->name('courses.register');
    Route::get('/registered-courses', [CourseController::class, 'registeredCourses'])->name('registered.courses');
});






require __DIR__.'/auth.php';
