<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Log;


class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['doctor.specialty'])
            ->where('user_id', auth()->id())
            ->latest();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->paginate(10)->withQueryString();

        return view('appointments.index', compact('appointments'));
    }

    public function cancel(Appointment $appointment)
    {
        if ($appointment->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح بهذا الإجراء'
            ], 403);
        }

        if ($appointment->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن إلغاء هذا الموعد'
            ], 422);
        }

        try {
            $appointment->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'تم إلغاء الموعد بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إلغاء الموعد'
            ], 500);
        }
    }

    public function store(Request $request)
{
    // Add logging to help diagnose the issue
    Log::info('Booking Request Data:', [
        'user' => auth()->user(),
        'request_data' => $request->all()
    ]);

    $validated = $request->validate([
        'doctor_id' => 'required|exists:doctors,id',
        'date' => 'required|date|after_or_equal:today',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
        'note' => 'nullable|string|max:500',
        'phone' => 'nullable|string|max:20'
    ]);

    // Log validated data
    Log::info('Validated Data:', $validated);

    try {
        $appointment = Appointment::create([
            'doctor_id' => $validated['doctor_id'],
            'user_id' => auth()->id(),
            'user_phone' => $validated['phone'] ?? auth()->user()->phone,
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => 'pending',
            'note' => $validated['note'] ?? null  // Fixed typo here
        ]);

        // Log created appointment
        Log::info('Created Appointment:', $appointment->toArray());

        return response()->json([
            'success' => true,
            'message' => 'تم حجز الموعد بنجاح',
            'appointment' => $appointment
        ]);

    } catch (\Exception $e) {
        // Log the full error
        Log::error('Appointment Booking Error:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء حجز الموعد: ' . $e->getMessage()
        ], 500);
    }
}

    public function rate(Request $request, Appointment $appointment)
{
    try {
        Log::info('Rating request:', [
            'appointment_id' => $appointment->id,
            'user_id' => auth()->id(),
            'data' => $request->all()
        ]);

        // Verify ownership
        if ($appointment->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح بهذا الإجراء'
            ], 403);
        }

        // Verify appointment can be rated
        if (!$appointment->canBeRated()) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن تقييم هذا الموعد'
            ], 422);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Update appointment with rating (removed review)
        $appointment->update([
            'rating' => $validated['rating'],
            'review' => null // Explicitly set review to null
        ]);

        // Update doctor's average rating
        $doctor = $appointment->doctor;
        $averageRating = $doctor->appointments()
            ->whereNotNull('rating')
            ->avg('rating');

        $ratingsCount = $doctor->appointments()
            ->whereNotNull('rating')
            ->count();

        $doctor->update([
            'rating' => round($averageRating, 1),
            'ratings_count' => $ratingsCount
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة تقييمك بنجاح'
        ]);

    } catch (\Exception $e) {
        Log::error('Rating error:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء إضافة التقييم'
        ], 500);
    }
}

    public function show(Appointment $appointment)
    {
        // Ensure the user can only view their own appointments
        if ($appointment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        // Eager load related models to prevent N+1 queries
        $appointment->load([
            'doctor',
            'doctor.specialty'
        ]);

        return view('appointments.show', [
            'appointment' => $appointment
        ]);
    }
}
