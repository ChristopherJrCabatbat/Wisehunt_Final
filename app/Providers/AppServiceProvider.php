<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Using a closure based composer to add user photo to all views
        View::composer('*', function ($view) {
            $userPhoto = null; // Default to null if user is not logged in
            
            if (Auth::check()) { // Check if user is logged in
                $userPhoto = Auth::user()->photo; // Get the logged-in user's photo
            }

            $view->with('userPhoto', $userPhoto); // Share `userPhoto` with all views
        });
    }
}
