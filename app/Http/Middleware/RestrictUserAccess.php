<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestrictUserAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user()?->fresh();

        if ($user && $user->is_restricted) {
            return redirect()->route('unavailable');
        }

        return $next($request);
    }
}
