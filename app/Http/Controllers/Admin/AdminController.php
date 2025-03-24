<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Specialty;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_doctors' => Doctor::count(),
            'pending_doctors' => Doctor::where('status', 'pending')->count(),
            'active_doctors' => Doctor::where('status', 'active')->count(),
            'blocked_doctors' => Doctor::where('status', 'inactive')->count(),
        ];

        $pending_doctors = Doctor::where('status', 'pending')
            ->with('specialty')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pending_doctors'));
    }

    public function doctors()
    {
         $doctors = Doctor::where('status', 'active')
                    ->with('specialty')
                    ->latest()
                    ->paginate(10);

        $specialties = Specialty::all();

        return view('admin.doctors.index', compact('doctors', 'specialties'));
    }

    public function pendingDoctors()
    {
        $doctors = Doctor::where('status', 'pending')
            ->with('specialty')  // Eager load specialty relationship
            ->latest()
            ->paginate(10);

        $specialties = Specialty::all();  // Add this line

        return view('admin.doctors.pending', compact('doctors', 'specialties'));
    }

    public function showDoctor(Doctor $doctor)
    {
        return view('admin.doctors.show', compact('doctor'));
    }

    public function updateDoctorStatus(Doctor $doctor, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,rejected',
            'rejection_reason' => 'required_if:status,rejected|string|max:255',
        ]);

        $doctor->update([
            'status' => $validated['status'],
            'rejection_reason' => $validated['status'] === 'rejected' ? $validated['rejection_reason'] : null,
        ]);

        $message = $validated['status'] === 'active'
            ? 'تم قبول الطبيب بنجاح'
            : 'تم رفض الطبيب بنجاح';

        return back()->with('success', $message);
    }

    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }
}
