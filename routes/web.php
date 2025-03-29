<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\Doctor\DoctorAuthController;
use App\Http\Controllers\Doctor\DoctorProfileController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminDoctorController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Doctor\DoctorScheduleController;
use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\Doctor\DoctorAppointmentController;
use App\Http\Controllers\Doctor\DoctorSettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Appointment;
use App\Http\Controllers\PageController;


Route::view('/', 'welcome')->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');

// Doctor Auth Routes (Move these BEFORE the general doctor routes)
Route::middleware('guest:doctor')->group(function () {
    Route::get('/doctors/login', [DoctorAuthController::class, 'showLoginForm'])->name('doctor.login');
    Route::post('/doctors/login', [DoctorAuthController::class, 'login'])->name('doctor.login.submit');
});

// Public doctor routes
Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
Route::get('/join', [DoctorController::class, 'create'])->name('doctors.create');
Route::post('/join', [DoctorController::class, 'store'])->name('doctors.store');
Route::get('/doctor/registration-success', function () { return view('doctors.registration-success'); })->name('doctors.registration.success');
Route::get('/doctors/{doctor}/time-slots', [DoctorController::class, 'getTimeSlots'])->name('doctors.time-slots');


// This should come AFTER more specific routes
Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');

// Authenticated user routes
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::post('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('medical-records.index');
    Route::post('/appointments/{appointment}/rate', [AppointmentController::class, 'rate'])
        ->name('appointments.rate')
        ->where('appointment', '[0-9]+');

    // User profile routes
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::post('/doctors/{doctor}/book', [DoctorController::class, 'book'])->name('doctors.book');

    Route::post('/doctors/{doctor}/rate', [DoctorController::class, 'rate'])->name('doctors.rate');
});


// Authenticated doctor routes
Route::middleware('auth:doctor')->group(function () {
    // Dashboard
    Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'index'])->name('doctor.dashboard');

    // Profile Management
    Route::prefix('doctor')->name('doctor.')->group(function () {
        // Profile routes
        Route::get('/profile', [DoctorProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [DoctorProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [DoctorProfileController::class, 'updatePassword'])->name('profile.password.update');

        // Schedule routes - Fix the naming here
        Route::get('/schedule', [DoctorScheduleController::class, 'index'])->name('schedule');
        Route::post('/schedule/update', [DoctorScheduleController::class, 'update'])->name('schedule.update');
        // Appointments routes
        Route::get('/appointments', [DoctorAppointmentController::class, 'index'])->name('appointments');
        Route::post('/appointments/{appointment}/update-status', [DoctorAppointmentController::class, 'updateStatus'])
            ->name('appointments.update-status');
        Route::get('/appointments/{appointment}', [DoctorAppointmentController::class, 'show'])->name('appointments.show');
        // Settings route
        Route::get('/settings', [DoctorSettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [DoctorSettingsController::class, 'update'])->name('settings.update');
        //Route::get('/schedule/slots', [DoctorScheduleController::class, 'getTimeSlots'])->name('schedule.slots');

        // Logout route
        Route::post('/logout', [DoctorAuthController::class, 'logout'])->name('logout');
    });
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    // Protected Admin Routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Doctor Management
        Route::controller(AdminDoctorController::class)->prefix('doctors')->name('doctors.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/pending', 'pending')->name('pending');
            Route::get('/{doctor}', 'show')->name('show');
            Route::get('/{doctor}/edit', 'edit')->name('edit');
            Route::put('/{doctor}', 'update')->name('update');
            Route::delete('/{doctor}', 'destroy')->name('destroy');
            Route::patch('/{doctor}/status', 'updateStatus')->name('update.status');
            Route::post('/{doctor}/approve', 'approve')->name('approve');
            Route::post('/{doctor}/reject', 'reject')->name('reject');
            Route::delete('/bulk-delete', 'bulkDelete')->name('bulk-delete');
            Route::get('/export', 'export')->name('export');
        });

        // User Management
        Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{user}', 'show')->name('show');
            Route::get('/{user}/edit', 'edit')->name('edit');
            Route::put('/{user}', 'update')->name('update');
            Route::delete('/{user}', 'destroy')->name('destroy');
            Route::delete('/bulk-delete', 'bulkDelete')->name('bulk-delete');
        });

        // Admin Profile
        Route::controller(AdminProfileController::class)->prefix('profile')->name('profile.')->group(function () {
            Route::get('/', 'edit')->name('edit');
            Route::patch('/', 'update')->name('update');
            Route::delete('/', 'destroy')->name('destroy');
            Route::put('/password', 'updatePassword')->name('password.update');
        });

        // Admin Logout
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});

require __DIR__.'/auth.php';
