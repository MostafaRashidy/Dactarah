<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DoctorDashboardController extends Controller
{
    public function index()
    {
        $doctor = auth('doctor')->user();

        // Get today's appointments
        $todayAppointments = $doctor->appointments()
            ->whereDate('date', Carbon::today())
            ->count();

        // Get total patients (unique users who had appointments)
        $totalPatients = $doctor->appointments()
            ->distinct('user_id')
            ->count('user_id');

        // Get total ratings
        $totalRatings = $doctor->ratings_count ?? 0;

        // Get upcoming appointments
        $upcomingAppointments = $doctor->appointments()
            ->with('user')
            ->whereDate('date', '>=', Carbon::today())
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        return view('doctors.dashboard', compact(
            'doctor',
            'todayAppointments',
            'totalPatients',
            'totalRatings',
            'upcomingAppointments'
        ));
    }
}
