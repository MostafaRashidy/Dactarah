<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
    public function boot()
    {
        View::composer('layouts.doctor-navigation', function ($view) {
            if (auth('doctor')->check()) {
                $pendingAppointmentsCount = auth('doctor')->user()
                    ->appointments()
                    ->where('status', 'pending')
                    ->count();

                $view->with('pendingAppointmentsCount', $pendingAppointmentsCount);
            }
        });
    }
}
