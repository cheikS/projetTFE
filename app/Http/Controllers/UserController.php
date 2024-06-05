<?php

namespace App\Http\Controllers;

use App\Models\Course;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
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
        return inertia('Instructor/InstructorDashboard');
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

        return Redirect::route('admin.dashboard')->with('success', 'User role updated successfully');
    }

}
