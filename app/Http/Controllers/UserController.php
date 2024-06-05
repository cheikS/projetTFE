<?php

namespace App\Http\Controllers;

use App\Models\Course;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
class UserController extends Controller
{

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
        $courses = auth()->user()->instructedCourses; // Récupérer les cours de l'instructeur connecté
        return Inertia::render('Instructor/InstructorDashboard', [
            'courses' => $courses
        ]);
    }

    public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully');
}

public function changeRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|in:admin,instructor,student',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->role = $request->role;
        $user->save();

        // Rediriger l'utilisateur connecté vers le bon tableau de bord
        if (Auth::id() == $user->id) {
            if ($user->role == 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role == 'instructor') {
                return redirect()->route('instructor.dashboard');
            } else {
                return redirect()->route('student.dashboard');
            }
        }

        return back()->with('successMessage', 'User role updated successfully.');
    }

}
