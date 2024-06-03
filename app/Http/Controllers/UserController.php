<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    // Autres méthodes existantes...

    public function adminDashboard()
    {
        return inertia('Admin/AdminDashboard');
    }

    public function instructorDashboard()
    {
        return inertia('Instructor/InstructorDashboard');
    }
}
