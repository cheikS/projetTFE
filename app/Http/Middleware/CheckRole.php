<?php

// app/Http/Middleware/CheckRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            // Assurez-vous que cette redirection n'entraîne pas une boucle
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}
