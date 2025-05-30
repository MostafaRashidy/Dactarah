<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\DoctorSchedule;
use App\Models\Governorate;


class DoctorController extends Controller
{
    // Public doctor listing/search
    public function index(Request $request)
{
    // Start with active doctors only
    $query = Doctor::where('status', 'active')->with(['specialty', 'governorate']);

    // Name search
    if ($request->filled('name')) {
        $searchTerm = $request->name;
        $query->where(function($q) use ($searchTerm) {
            $q->where('first_name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('last_name', 'LIKE', "%{$searchTerm}%");
        });
    }

    // Specialty filter
    if ($request->filled('specialty')) {
        $query->where('specialty_id', $request->specialty);
    }

    // Governorate filter
    if ($request->filled('governorate')) {
        $query->where('governorate_id', $request->governorate);
    }

    // Available today filter
    if ($request->filled('available_today')) {
        $query->where('is_available', true);
    }

    // Highly rated filter
    if ($request->filled('highly_rated')) {
        $query->where('rating', '>=', 4.5);
    }

    // Sorting
    switch ($request->sort) {
        case 'rating':
            $query->orderBy('rating', 'desc');
            break;
        case 'price_low':
            $query->orderBy('price', 'asc');
            break;
        case 'price_high':
            $query->orderBy('price', 'desc');
            break;
        default:
            $query->orderBy('rating', 'desc')->orderBy('created_at', 'desc');
    }

    $doctors = $query->paginate(12)->withQueryString();
    $specialties = Specialty::all();
    $governorates = Governorate::all();

    return view('doctors.index', [
        'doctors' => $doctors,
        'specialties' => $specialties,
        'governorates' => $governorates,
        'getFilterLabel' => function($key, $value) {
            switch ($key) {
                case 'name':
                    return 'البحث: ' . $value;
                case 'specialty':
                    $specialty = Specialty::find($value);
                    return 'التخصص: ' . ($specialty ? $specialty->name : $value);
                case 'available_today':
                    return 'متاح اليوم';
                case 'highly_rated':
                    return 'الأعلى تقييماً';
                case 'sort':
                    return match($value) {
                        'rating' => 'الترتيب: الأعلى تقييماً',
                        'price_low' => 'الترتيب: السعر (الأقل)',
                        'price_high' => 'الترتيب: السعر (الأعلى)',
                        default => 'الترتيب: الأحدث'
                    };
                case 'governorate':
                    $governorate = Governorate::find($value);
                    return 'المحافظة: ' . ($governorate ? $governorate->name : $value);
                default:
                    return $key . ': ' . $value;
            }
        }
    ]);
}


    // Show individual doctor profile
    public function show(Doctor $doctor)
    {
        if ($doctor->status !== 'active') {
            abort(404);
        }
        return view('doctors.show', compact('doctor'));
    }

    // Doctor registration form
    public function create()
    {
        $specialties = Specialty::all();
        $governorates = Governorate::all();
        return view('doctors.create', compact('specialties', 'governorates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'login_email' => 'required|email|unique:doctors,email',
            'communication_email' => 'required|email|different:login_email',
            'password' => 'required|min:8',
            'phone' => 'required|regex:/^01[0-2]\d{8}$/|unique:doctors,phone',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|max:150',
            'usr_img' => 'required|image|mimes:jpeg,png,jpg|max:5048',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'specialty_id' => 'required|exists:specialties,id',
            'governorate_id' => 'required|exists:governorates,id',
        ], $this->messages());

        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('usr_img')) {
                $image = $request->file('usr_img');
                $imageName = time() . '_' . $request->first_name . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/uploads'), $imageName);
                $imagePath = 'images/uploads/' . $imageName;
            }

            // Create doctor
            $doctor = Doctor::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['login_email'],
                'communication_email' => $validated['communication_email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'price' => $validated['price'],
                'description' => $validated['description'],
                'image' => $imagePath,
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'specialty_id' => $validated['specialty_id'],
                'governorate_id' => $validated['governorate_id'],
                'status' => 'pending',
            ]);

            Log::info('New doctor registered', ['doctor_id' => $doctor->id]);
            return redirect()->route('doctors.registration.success');
        } catch (\Exception $e) {
            // Clean up uploaded image if exists
            if (isset($imagePath) && file_exists(public_path($imagePath))) {
                unlink(public_path($imagePath));
            }

            Log::error('Doctor registration failed', [
                'error' => $e->getMessage(),
                'data' => $request->except('password')
            ]);

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء التسجيل. برجاء المحاولة مرة أخرى.');
        }
    }


    // Doctor profile edit (authenticated doctors only)
    public function editProfile()
    {
        $doctor = auth('doctor')->user();
        $specialties = Specialty::all();
        return view('doctor.profile.edit', compact('doctor', 'specialties'));
    }

    // Update doctor profile (authenticated doctors only)
    public function updateProfile(Request $request)
    {
        $doctor = auth('doctor')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'login_email' => 'required|email|unique:doctors,email',
            'communication_email' => 'nullable|email',
            'phone' => 'required|regex:/^01[0-2,5]\d{8}$/|unique:doctors,phone,' . $doctor->id,
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|max:150',
            'usr_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'specialty_id' => 'required|exists:specialties,id',
        ], $this->messages());

        try {
            // Handle image upload if new image is provided
            if ($request->hasFile('usr_img')) {
                // Delete old image
                if ($doctor->image && file_exists(public_path($doctor->image))) {
                    unlink(public_path($doctor->image));
                }

                // Upload new image
                $image = $request->file('usr_img');
                $imageName = time() . '_' . $request->first_name . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/uploads'), $imageName);
                $validated['image'] = 'images/uploads/' . $imageName;
            }

            $doctor->update($validated);

            return redirect()
                ->route('doctor.profile.edit')
                ->with('success', 'تم تحديث البيانات بنجاح');

        } catch (\Exception $e) {
            Log::error('Doctor profile update failed', [
                'doctor_id' => $doctor->id,
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث البيانات');
        }
    }

    // Validation messages
    private function messages()
    {
        return [
            'first_name.required' => 'الاسم الأول مطلوب',
            'last_name.required' => 'الاسم الأخير مطلوب',
            'login_email.required' => 'بريد الدخول الإلكتروني مطلوب',
            'login_email.email' => 'بريد الدخول يجب أن يكون صحيحاً',
            'login_email.unique' => 'بريد الدخول مستخدم بالفعل',
            'communication_email.required' => 'بريد التواصل الإلكتروني مطلوب',
            'communication_email.email' => 'بريد التواصل يجب أن يكون صحيحاً',
            'communication_email.different' => 'بريد التواصل يجب أن يختلف عن بريد الدخول',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.regex' => 'يرجى إدخال رقم هاتف مصري صحيح',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل',
            'price.required' => 'سعر الكشف مطلوب',
            'price.numeric' => 'سعر الكشف يجب أن يكون رقماً',
            'price.min' => 'سعر الكشف يجب أن يكون 0 على الأقل',
            'description.required' => 'الوصف مطلوب',
            'description.max' => 'الوصف يجب ألا يتجاوز 150 حرف',
            'usr_img.required' => 'الصورة الشخصية مطلوبة',
            'usr_img.image' => 'يجب أن يكون الملف صورة',
            'usr_img.mimes' => 'يجب أن تكون الصورة من نوع: jpeg, png, jpg',
            'usr_img.max' => 'حجم الصورة لا يجب أن يتجاوز 2 ميجابايت',
            'latitude.required' => 'يرجى تحديد موقع العيادة',
            'longitude.required' => 'يرجى تحديد موقع العيادة',
            'specialty_id.required' => 'التخصص مطلوب',
            'specialty_id.exists' => 'التخصص غير موجود',
            'governorate_id.required' => 'المحافظة مطلوبة',
            'governorate_id.exists' => 'المحافظة غير موجودة',
        ];
    }

    public function getTimeSlots(Doctor $doctor, Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d|after_or_equal:today'
        ]);

        $slots = $doctor->generateTimeSlots($request->date);

        return response()->json([
            'slots' => $slots
        ]);
    }

    public function book(Doctor $doctor, Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required',
            'note' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20'
        ]);

        try {
            $appointment = $doctor->appointments()->create([
                'user_id' => auth()->id(),
                'date' => $validated['date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'status' => 'pending',
                'note' => $validated['note'] ?? null,
                'user_phone' => $validated['phone'] ?? auth()->user()->phone
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حجز الموعد بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حجز الموعد'
            ], 500);
        }
    }

    public function rate(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500',
            'appointment_id' => 'required|exists:appointments,id'
        ]);

        try {
            // Verify appointment belongs to user and doctor
            $appointment = Appointment::where('id', $validated['appointment_id'])
                ->where('doctor_id', $doctor->id)
                ->where('user_id', auth()->id())
                ->where('status', 'completed')
                ->firstOrFail();

            // Check if already rated
            if ($appointment->rating()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'تم تقييم هذا الموعد مسبقاً'
                ], 422);
            }

            // Create rating
            $rating = $appointment->rating()->create([
                'doctor_id' => $doctor->id,
                'user_id' => auth()->id(),
                'rating' => $validated['rating'],
                'review' => $validated['review']
            ]);

            // Update doctor's average rating
            $averageRating = $doctor->ratings()->avg('rating');
            $ratingsCount = $doctor->ratings()->count();

            $doctor->update([
                'rating' => round($averageRating, 1),
                'ratings_count' => $ratingsCount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة تقييمك بنجاح',
                'rating' => $rating
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة التقييم'
            ], 500);
        }
    }
}
