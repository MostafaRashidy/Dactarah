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

    public function updateStatus(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:255',
        ]);

        $doctor->update([
            'status' => $validated['status'],
            'rejection_reason' => $validated['rejection_reason'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الطبيب بنجاح'
        ]);
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
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:255'
        ]);

        $doctor->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason']
        ]);

        return redirect()
            ->route('admin.doctors.pending')
            ->with('success', 'تم رفض الطبيب بنجاح');
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
