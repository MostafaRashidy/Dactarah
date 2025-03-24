<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DoctorAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $doctor = auth('doctor')->user();
        $query = $doctor->appointments()->with('user');

        // Apply status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $appointments = $query
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('doctors.appointments.index', compact('appointments'));
    }


    public function updateStatus(Request $request, Appointment $appointment)
    {
        // Verify that this appointment belongs to the authenticated doctor
        if ($appointment->doctor_id !== auth('doctor')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح بهذا الإجراء'
            ], 403);
        }

        // Validate the status
        $validated = $request->validate([
            'status' => 'required|in:confirmed,cancelled,completed'
        ]);

        try {
            $appointment->update([
                'status' => $validated['status']
            ]);

            // Get the Arabic status name
            $statusNames = [
                'confirmed' => 'مؤكد',
                'cancelled' => 'ملغي',
                'completed' => 'مكتمل'
            ];

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة الموعد إلى ' . $statusNames[$validated['status']],
                'appointment' => $appointment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الحالة'
            ], 500);
        }
    }

    public function show(Appointment $appointment)
    {
        // Ensure the doctor can only view their own appointments
        if (auth()->guard('doctor')->user()->id !== $appointment->doctor_id) {
            abort(403, 'Unauthorized access');
        }

        // Eager load related models to prevent N+1 queries
        $appointment->load([
            'user',
            'doctor',
            // 'medical_record'
        ]);

        return view('doctors.appointments.show', [
            'appointment' => $appointment
        ]);
    }
}
