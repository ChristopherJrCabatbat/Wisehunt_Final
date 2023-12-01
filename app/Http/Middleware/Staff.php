<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Staff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth()->user()->role == 'Staff') 
        {
            return $next($request);
        }
        return redirect()->back()->with('error','You need to log out log in as staff first.');
        // abort(401);
    }
}
