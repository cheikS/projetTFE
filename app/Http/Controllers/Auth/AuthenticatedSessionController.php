<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException; 
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to log the user in
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            // If the authentication attempt fails, throw a validation exception
            throw ValidationException::withMessages([
                'email' => __('auth.failed'), // Use the appropriate translation for 'auth.failed'
            ]);
        }

        // Regenerate the session to prevent session fixation attacks
        $request->session()->regenerate();

        // Get the user's role after successful authentication
        $role = Auth::user()->role; // Assumes a 'role' attribute on the User model

        // Redirect based on the user's role
        return Inertia::location(route('redirect.role', ['role' => $role]));
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    
}
