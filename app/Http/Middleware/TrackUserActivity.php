<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            $request->user()->forceFill([
                'last_seen_at' => now(),
                'last_ip_address' => $request->ip(),
            ])->save();
        }

        return $next($request);
    }
}
