<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    protected $fillable = [
        'doctor_id',
        'day',
        'start_time',
        'end_time',
        'is_available'
    ];

    protected $casts = [
        'is_available' => 'boolean'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    
}
