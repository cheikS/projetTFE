<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
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

    public function manageVideos(Course $course)
    {
        $videos = $course->videos; // Assuming a hasMany relationship with Video

        return Inertia::render('Instructor/ManageVideo', [
            'course' => $course,
            'videos' => $videos,
        ]);
    }

    public function destroyVideo(Course $course, Video $video)
    {
        // Ensure the video belongs to the course and is managed by the instructor
        if ($video->course_id !== $course->id) {
            abort(403, 'Unauthorized action.');
        }
    
        // Check if the authenticated user has permission to delete the video
        if (!auth()->user()->can('delete', $video)) {
            abort(403, 'Unauthorized action.');
        }
    
        $video->delete();
    
        return redirect()->route('instructor.courses.manage-videos', $course->id)->with('message', 'Video deleted successfully.');
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

        return Redirect::route('dashboard');
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
public function create()
{
    $instructors = User::whereRole('instructor')->get();
    return Inertia::render('Courses/Create', [
        'instructors' => $instructors
    ]);
}

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'instructor_id' => 'required|exists:users,id', 
        'duration' => 'required|numeric|min:0',
        'level' => 'required|string',
        'language' => 'required|string',
        'price' => 'required|numeric|min:0',
        'category' => 'required|string',
    ]);

    // Création du cours
    $course = Course::create($request->all());

    return redirect()->route('admin.dashboard')->with('success', 'Course updated successfully');
}



public function destroy($id)
{
    $course = Course::findOrFail($id);
    $course->delete();

    return redirect()->route('admin.dashboard')->with('success', 'Course deleted successfully');
}

public function edit()
{
    $instructors = User::whereRole('instructor')->get();
    $courses = Course::all(); // ou tout autre logique pour récupérer les cours à éditer
    return Inertia::render('Courses/Edit', [
        'courses' => $courses,
        'instructors' => $instructors,
        // Ajoutez d'autres données nécessaires pour l'édition
    ]);
}

public function update(Request $request, $id)
{
    $course = Course::findOrFail($id);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'instructor_id' => 'required|exists:users,id',
        'duration' => 'required|integer',
        'level' => 'required|string',
        'language' => 'required|string',
        'price' => 'required|numeric',
        'category' => 'required|string',
    ]);

    $course->update($validated);

    return redirect()->route('admin.dashboard')->with('success', 'Course updated successfully');
}

public function showAddVideoForm(Course $course)
    {
        return inertia('Courses/AddVideo', [
            'course' => $course
        ]);
    }

    // Enregistrer une nouvelle vidéo pour le cours
    public function storeVideo(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|url'
        ]);

        $course->videos()->create([
            'title' => $request->title,
            'description' => $request->description,
            'url' => $request->url
        ]);

        return redirect()->route('manage-videos', $course->id)->with('success', 'Video added successfully!');
    }

    public function showVideos(Course $course)
{
    // Charger les vidéos du cours
    $videos = $course->videos;

    // Inclure les informations du cours dans la réponse
    return Inertia::render('Courses/Videos', [
        'course' => $course, // Ajout de l'objet cours pour inclure son titre
        'videos' => $videos, // Collection de vidéos liées au cours
        'successMessage' => session('successMessage') // Message de succès si disponible
    ]);
}

public function showVideo(Course $course, Video $video)
{
    $comments = Comment::where('video_id', $video->id)
                        ->with('user')
                        ->orderBy('created_at', 'desc') // Trier par date de création décroissante
                        ->get()
                        ->map(function ($comment) {
                            return [
                                'id' => $comment->id,
                                'content' => $comment->content,
                                'created_at' => $comment->created_at->toDateTimeString(),
                                'user' => [
                                    'firstname' => $comment->user->firstname,
                                ],
                            ];
                        });

    return Inertia::render('Videos/VideoViewer', [
        'course' => $course,
        'videoId' => $video->id,
        'videoUrl' => $video->url,
        'comments' => $comments
    ]);
}



}
