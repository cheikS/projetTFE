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
        $course = Course::with('instructor')->findOrFail($id);
        return Inertia::render('Courses/Show', [
            'course' => $course,
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
    public function dashboard()
    {
        $user = auth()->user();
        $courses = $user->courses()->get(); // Récupérer tous les cours de l'utilisateur

        return view('dashboard', compact('courses'));
    }

}
