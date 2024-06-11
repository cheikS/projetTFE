<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\UserController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/redirect-role/{role}', function ($role) {
    switch ($role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'instructor':
            return redirect()->route('instructor.dashboard');
        default:
            return redirect()->route('dashboard');
    }
})->name('redirect.role');


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/courses/{course}/videos/{video}', [CourseController::class, 'showVideo'])->name('videos.show');
    Route::put('/instructor/courses/{course}/videos/{video}', [VideoController::class, 'update'])->name('instructor.courses.update-video');
    Route::get('/instructor/courses/{course}/videos/{video}/edit', [VideoController::class, 'edit'])->name('instructor.courses.edit-video');
    Route::delete('/instructor/courses/{course}/videos/{video}', [VideoController::class, 'destroy'])->name('instructor.courses.destroy-video');
    Route::get('/instructor/courses/{course}/manage-videos', [CourseController::class, 'manageVideos'])->name('manage-videos');
    Route::get('/courses/{course}/videos', [CourseController::class, 'showVideos'])->name('courses.videos');
    Route::get('/courses/{course}/add-video', [CourseController::class, 'showAddVideoForm'])->name('courses.add-video');
    Route::post('/courses/{course}/videos', [CourseController::class, 'storeVideo'])->name('courses.store-video');
    Route::put('/admin/courses/{id}', [CourseController::class, 'update'])->name('courses.update');
    Route::get('/admin/courses/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::post('/admin/users/change-role', [UserController::class, 'changeRole'])->name('users.changeRole');
    Route::delete('admin/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/instructor/dashboard', [UserController::class, 'instructorDashboard'])->name('instructor.dashboard');
    Route::get('/admin/dashboard', [UserController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/messages/sent', [MessageController::class, 'sentMessages'])->name('messages.sent');
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
