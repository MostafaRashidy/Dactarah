<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specialty;
use Illuminate\Support\Facades\Storage;

class DoctorProfileController extends Controller
{
    public function edit()
    {
        $doctor = auth('doctor')->user();
        $specialties = Specialty::all();
        return view('doctors.profile.edit', compact('doctor', 'specialties'));
    }

    public function update(Request $request)
    {
        $user = auth('doctor')->user();

        $validated = $request->validate([
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', 'unique:doctors,email,'.$user->id],
            'phone' => ['sometimes', 'string', 'max:255', 'unique:doctors,phone,'.$user->id],
            'specialty_id' => ['sometimes', 'exists:specialties,id'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'description' => ['sometimes', 'string'],
            'address' => ['sometimes', 'string'],
            'latitude' => ['sometimes', 'numeric'],
            'longitude' => ['sometimes', 'numeric'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        // Only update fields that were actually submitted
        $dataToUpdate = array_filter($request->only([
            'first_name',
            'last_name',
            'email',
            'phone',
            'specialty_id',
            'price',
            'description',
            'address',
            'latitude',
            'longitude'
        ]));

        // Handle image upload separately
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($user->image && Storage::exists('public/' . $user->image)) {
                Storage::delete('public/' . $user->image);
            }

            // Store new image
            $dataToUpdate['image'] = $request->file('image')->store('doctors', 'public');
        }

        $user->update($dataToUpdate);

        return back()->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password:doctor',
            'password' => 'required|string|min:8|confirmed',
        ]);

        auth('doctor')->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'تم تحديث كلمة المرور بنجاح');
    }
}
