<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\Http\Request;

class AdminDoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with('specialty')->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhereHas('specialty', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Specialty Filter
        if ($request->filled('specialty')) {
            $query->whereHas('specialty', function($q) use ($request) {
                $q->where('id', $request->specialty);
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price');
                break;
            case 'price_desc':
                $query->orderByDesc('price');
                break;
            case 'rating':
                $query->orderByDesc('rating');
                break;
            default:
                $query->latest();
        }

        $doctors = $query->paginate(10)->withQueryString();
        $specialties = Specialty::all();

        return view('admin.doctors.index', compact('doctors', 'specialties'));
    }

    public function pending(Request $request)
    {
        $query = Doctor::where('status', 'pending')->with('specialty');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                ->orWhere('last_name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // Specialty filter
        if ($request->filled('specialty')) {
            $query->where('specialty_id', $request->specialty);
        }

        // Date filter
        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                    break;
            }
        }

        $doctors = $query->latest()->paginate(9);
        $specialties = Specialty::all();

        return view('admin.doctors.pending', compact('doctors', 'specialties'));
    }

    public function show(Doctor $doctor)
    {
        return view('admin.doctors.show', compact('doctor'));
    }

    // In AdminDoctorController
// In AdminDoctorController
public function updateStatus(Request $request, Doctor $doctor)
{
    $validated = $request->validate([
        'status' => 'required|in:active,inactive,rejected'
    ]);

    try {
        if ($validated['status'] === 'rejected') {
            // Delete the doctor if status is rejected
            if ($doctor->image && file_exists(public_path($doctor->image))) {
                unlink(public_path($doctor->image));
            }

            $doctor->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم رفض وحذف الطبيب بنجاح',
                'redirect' => route('admin.doctors.pending')
            ]);
        }

        // For other statuses, update as before
        $doctor->update([
            'status' => $validated['status']
        ]);

        return response()->json([
            'success' => true,
            'message' => match($validated['status']) {
                'active' => 'تم تفعيل الطبيب بنجاح',
                'inactive' => 'تم إيقاف الطبيب مؤقتًا',
                default => 'تم تحديث حالة الطبيب'
            }
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء معالجة الطلب'
        ], 500);
    }
}

    public function approve(Doctor $doctor)
    {
        $doctor->update(['status' => 'active']);

        return redirect()
            ->route('admin.doctors.pending')
            ->with('success', 'تم قبول الطبيب بنجاح');
    }

    public function reject(Request $request, Doctor $doctor)
{
    // Validate the request
    $validated = $request->validate([
        'rejection_reason' => 'required|string|max:500'
    ]);

    try {
        // Update doctor status to rejected
        $doctor->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason']
        ]);

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'تم رفض الطبيب بنجاح'
        ]);
    } catch (\Exception $e) {
        // Log the error
        \Log::error('Doctor rejection error: ' . $e->getMessage());

        // Return error response
        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء رفض الطبيب',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function destroy(Doctor $doctor)
    {
        try {
            if ($doctor->image && file_exists(public_path($doctor->image))) {
                unlink(public_path($doctor->image));
            }

            $doctor->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الطبيب بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الطبيب'
            ], 500);
        }
    }
}
