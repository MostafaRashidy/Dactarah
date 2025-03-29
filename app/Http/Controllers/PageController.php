<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        // Check if view exists
        if (!view()->exists('about')) {
            // If view doesn't exist, return an error
            return "View not found. Please create resources/views/about.blade.php";
        }

        // Optional: Pass some data if needed
        return view('about', [
            'pageTitle' => 'About Us'
        ]);
    }

    public function contact()
    {
        return view('contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'message' => 'required|string|max:1000'
        ]);

        // Process the contact form (e.g., send email, save to database)
        // You can implement your preferred method of handling contact submissions

        return back()->with('success', 'تم إرسال رسالتك بنجاح');
    }
}
