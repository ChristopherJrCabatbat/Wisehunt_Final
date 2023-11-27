<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $account = Session::get('account');
        // $nm = Session::get('name');

        // if ($nm == '') {
        //     return redirect()->route('login');
        // }

        // $user = Session::get('user');
        // if($user == ""){
        //     return redirect()->route("login");
        //     // return redirect("/LoginForm");
        // }


        // Log::info('Middleware Executed', ['session' => Session::all()]);

        // if (!Session::has('user')) {
        //     Log::info('Redirecting...', ['session' => Session::all()]);
        //     return redirect()->route('login');
        // }


        if (Auth()->user()->role == 'admin') 
        {
            return $next($request);
        }
        abort(401);

        // return $next($request);
    }
}
