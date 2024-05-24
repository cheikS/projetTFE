<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;



class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index(): Response
    {
        $courses = Course::all();
        return Inertia::render('Courses/Index', [
            'courses' => $courses,
        ]);
    }

    /**
     * Display the specified course.
     */
    public function show($id): Response
{
    $user = Auth::user();
    $course = Course::with('instructor')->findOrFail($id);
    $isRegistered = $user ? $user->registrations()->where('course_id', $id)->exists() : false;

    return Inertia::render('Courses/Show', [
        'course' => $course,
        'isRegistered' => $isRegistered,
    ]);
}


    public function register(Request $request, Course $course)
    {
        $user = Auth::user();
        
        // Check if the user is already registered for the course
        $existingRegistration = Registration::where('user_id', $user->id)
                                             ->where('course_id', $course->id)
                                             ->first();

        if ($existingRegistration) {
            return redirect()->back()->withErrors(['You are already registered for this course.']);
        }

        // Register the user for the course
        Registration::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);

        return Redirect::route('courses.index');
    }
    
    public function registeredCourses()
{
    $user = auth()->user();
    $registrations = $user->registrations()->with('course.instructor')->get();

    $courses = $registrations->map(function($registration) {
        return $registration->course;
    });

    return inertia('RegisteredCourses', [
        'courses' => $courses
    ]);
}


public function dashboard()
{
    $user = auth()->user();
    $registrations = $user->registrations()->with('course')->get();

    $courses = $registrations->map(function($registration) {
        return $registration->course;
    });

    return Inertia::render('Dashboard', [
        'courses' => $courses
    ]);
}



}
