<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specialty;
use App\Models\Governorate;
use App\Models\Doctor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class DoctorProfileController extends Controller
{
    public function edit()
    {
        $doctor = auth('doctor')->user();
        $specialties = Specialty::all();
        $governorates = Governorate::all();
        return view('doctors.profile.edit', compact('doctor', 'specialties', 'governorates'));
    }

    public function update(Request $request)
    {
        /** @var Doctor $doctor */
        $doctor = auth('doctor')->user();

        $validated = $request->validate([
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', 'unique:doctors,email,'.$doctor->id],
            'phone' => ['sometimes', 'string', 'max:255', 'unique:doctors,phone,'.$doctor->id],
            'communication_email' => ['sometimes', 'email', 'max:255'],
            'specialty_id' => ['sometimes', 'exists:specialties,id'],
            'governorate_id' => ['sometimes', 'exists:governorates,id'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'description' => ['sometimes', 'string', 'max:150'],
            'address' => ['sometimes', 'string'],
            'latitude' => ['sometimes', 'numeric'],
            'longitude' => ['sometimes', 'numeric'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $dataToUpdate = array_filter($request->only([
            'first_name',
            'last_name',
            'email',
            'phone',
            'communication_email',
            'specialty_id',
            'governorate_id',
            'price',
            'description',
            'address',
            'latitude',
            'longitude'
        ]), function($value) {
            return $value !== null && $value !== '';
        });

        if ($request->hasFile('image')) {
            // Check if the old image exists and delete it
            if ($doctor->image && file_exists(public_path($doctor->image))) {
                unlink(public_path($doctor->image));
            }
            
            // Store the new image consistently with DoctorController
            $image = $request->file('image');
            $imageName = time() . '_' . $doctor->first_name . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/uploads'), $imageName);
            $dataToUpdate['image'] = 'images/uploads/' . $imageName;
        }

        $doctor->fill($dataToUpdate);
        $doctor->save();

        return back()->with('status', 'تم تحديث الملف الشخصي بنجاح');
    }

    public function updatePassword(Request $request)
    {
        /** @var Doctor $doctor */
        $doctor = auth('doctor')->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password:doctor'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $doctor->fill([
            'password' => Hash::make($validated['password'])
        ])->save();

        return back()->with('status', 'تم تحديث كلمة المرور بنجاح');
    }
}
