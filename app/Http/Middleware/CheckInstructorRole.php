<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInstructorRole
{
    public function handle(Request $request, Closure $next): Response
    {
       if ($request->user() && in_array ($request->user()->role, ['instructor', 'admin'])) 
        {
            return $next($request);
        }

        abort(403, 'YOU DON`T HAVE ACCESS');
    }
}
