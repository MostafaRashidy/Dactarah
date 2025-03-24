<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get upcoming appointments
        $upcomingAppointments = Appointment::with('doctor.specialty')
            ->where('user_id', $user->id)
            ->where('date', '>=', Carbon::today())
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        // Get recent activities
        $recentActivities = Appointment::with('doctor')
            ->where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // Get stats
        $stats = [
            'total_appointments' => Appointment::where('user_id', $user->id)->count(),
            'pending_appointments' => Appointment::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'completed_appointments' => Appointment::where('user_id', $user->id)
                ->where('status', 'completed')
                ->count(),
        ];

        return view('dashboard', compact('upcomingAppointments', 'recentActivities', 'stats'));
    }
}
