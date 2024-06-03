<?php

namespace App\Http\Controllers;

use App\Models\Course;

use App\Models\User;
use Inertia\Inertia;
class UserController extends Controller
{
    // Autres méthodes existantes...

    public function adminDashboard()
    {
        $users = User::all();
        $courses = Course::all();
        return Inertia::render('Admin/AdminDashboard', [
            'courses' => $courses,
            'users' => $users,
            'successMessage' => session('success'),
        ]);
    }
    


    public function instructorDashboard()
    {
        return inertia('Instructor/InstructorDashboard');
    }

    public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully');
}

}
