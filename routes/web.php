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



// Group of routes that require authentication and email verification
Route::middleware(['auth', 'verified'])->group(function () {

    // General course-related routes accessible to all authenticated users
    Route::get('/courses/{course}/videos', [CourseController::class, 'showVideos'])->name('courses.videos');
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::post('/courses/{course}/register', [CourseController::class, 'register'])->name('courses.register');
    Route::get('/registered-courses', [CourseController::class, 'registeredCourses'])->name('registered.courses');

    // Routes for administrators, protected by 'role:admin' middleware
    Route::middleware('role:admin')->group(function () {
        Route::put('/admin/courses/{id}', [CourseController::class, 'update'])->name('courses.update');
        Route::get('/admin/courses/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::post('/admin/users/change-role', [UserController::class, 'changeRole'])->name('users.changeRole');
        Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/admin/dashboard', [UserController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::delete('/admin/courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');
        Route::get('/admin/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/admin/courses', [CourseController::class, 'store'])->name('courses.store');
    });

    // Routes for instructors, protected by 'role:instructor' middleware
    Route::middleware('role:instructor')->group(function () {
        Route::get('/instructor/dashboard', [UserController::class, 'instructorDashboard'])->name('instructor.dashboard');
        Route::get('/courses/{course}/add-video', [CourseController::class, 'showAddVideoForm'])->name('courses.add-video');
        Route::post('/courses/{course}/videos', [CourseController::class, 'storeVideo'])->name('courses.store-video');
    });

    // Messaging routes
    Route::get('/messages/sent', [MessageController::class, 'sentMessages'])->name('messages.sent');
    Route::post('/messages/reply', [MessageController::class, 'reply'])->name('messages.reply');
    Route::get('/messages/{id}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');

    // Additional dashboard route (possibly redundant if the first dashboard route is used)
    Route::get('/dashboard', [CourseController::class, 'dashboard'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include authentication routes
require __DIR__.'/auth.php';
