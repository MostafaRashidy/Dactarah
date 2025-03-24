<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\DoctorScheduleController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Doctor Schedule Routes
Route::get('/doctors/{doctor}/time-slots', [DoctorScheduleController::class, 'getTimeSlots']);

// Appointment Routes
Route::middleware('auth')->group(function () {
    Route::post('/appointments', [AppointmentController::class, 'store'])
        ->name('appointments.store');
});
