<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePinVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if (! $user->pin_hash) {
            return redirect()->route('pin.setup');
        }

        if (! (bool) $request->session()->get('pin_verified', false)) {
            return redirect()->route('pin.login');
        }

        return $next($request);
    }
}
