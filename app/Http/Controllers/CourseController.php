<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index(): Response
    {
        $courses = Course::get()->toArray();
        return Inertia::render('Courses/Index', [
            'courses' => $courses,
        ]);
    }
}
