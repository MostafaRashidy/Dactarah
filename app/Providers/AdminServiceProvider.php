<?php

namespace App\Providers;

use App\Models\Doctor;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('layouts.admin-navigation', function ($view) {
            $pendingDoctorsCount = Doctor::where('status', 'pending')->count();
            $view->with('pendingDoctorsCount', $pendingDoctorsCount);
        });
    }
}
