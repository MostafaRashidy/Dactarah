<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DoctorSettingsController extends Controller
{
    public function index()
    {
        $doctor = auth('doctor')->user();
        return view('doctor.settings.index', compact('doctor'));
    }

    public function update(Request $request)
    {
        $doctor = auth('doctor')->user();

        $validated = $request->validate([
            'notifications_enabled' => 'boolean',
            'sms_notifications' => 'boolean',
            'email_notifications' => 'boolean',
            // Add other settings as needed
        ]);

        $doctor->update($validated);

        return back()->with('success', 'تم تحديث الإعدادات بنجاح');
    }
}
