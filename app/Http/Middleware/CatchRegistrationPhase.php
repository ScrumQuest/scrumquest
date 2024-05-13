<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CatchRegistrationPhase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!env('APP_REGISTRATION_PHASE', true) || $request->user()->is_admin) {
            return $next($request);
        }
        return redirect(route('registration'));
    }
}
