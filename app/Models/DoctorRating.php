<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorRating extends Model
{
    protected $fillable = [
        'doctor_id',
        'user_id',
        'appointment_id',
        'rating',
        'review'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
