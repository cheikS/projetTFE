<?php

namespace App\Http\Controllers;


use App\Models\Course;
use Inertia\Inertia;
class UserController extends Controller
{
    // Autres méthodes existantes...

    public function adminDashboard()
    {
        $courses = Course::all();
        return Inertia::render('Admin/AdminDashboard', [
            'courses' => $courses,
            'successMessage' => session('success'),
        ]);
    }
    


    public function instructorDashboard()
    {
        return inertia('Instructor/InstructorDashboard');
    }
}
