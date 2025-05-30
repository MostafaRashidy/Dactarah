<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'doctor_id',
        'user_id',
        'user_phone',
        'date',
        'start_time',
        'end_time',
        'status',
        'note',
        'rating',
        'review'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'rating' => 'integer'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function medical_record()
    // {
    //     return $this->hasOne(MedicalRecord::class);
    // }

    public function getStatusColorAttribute()
    {
        return [
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'completed' => 'green',
            'cancelled' => 'red'
        ][$this->status] ?? 'gray';
    }

    public function getStatusIconAttribute()
    {
        return [
            'pending' => 'clock',
            'confirmed' => 'check',
            'completed' => 'check-double',
            'cancelled' => 'times'
        ][$this->status] ?? 'info-circle';
    }

    public function getStatusMessageAttribute()
    {
        return [
            'pending' => 'موعد في انتظار التأكيد',
            'confirmed' => 'تم تأكيد الموعد',
            'completed' => 'تم إكمال الموعد',
            'cancelled' => 'تم إلغاء الموعد'
        ][$this->status] ?? 'تحديث حالة الموعد';
    }

    public function hasRating(): bool
    {
        return !is_null($this->rating);
    }

    public function canBeRated(): bool
    {
        return $this->status === 'completed' && !$this->hasRating();
    }

    // helper methods
    public function addRating(int $rating, ?string $review = null): void
    {
        $this->update([
            'rating' => $rating,
            'review' => $review
        ]);

        
        $this->updateDoctorRating();
    }

    protected function updateDoctorRating(): void
    {
        $doctor = $this->doctor;

        $averageRating = $doctor->appointments()
            ->whereNotNull('rating')
            ->avg('rating');

        $ratingsCount = $doctor->appointments()
            ->whereNotNull('rating')
            ->count();

        $doctor->update([
            'rating' => round($averageRating, 1),
            'ratings_count' => $ratingsCount
        ]);
    }
}
