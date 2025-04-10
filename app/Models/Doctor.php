<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use App\Models\Appointment;  // Add this import

class Doctor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'communication_email',
        'password',
        'phone',
        'price',
        'description',
        'image',
        'latitude',
        'longitude',
        'specialty_id',
        'status',
        'rejection_reason',
        'rating',
        'ratings_count',
        'experience_years',
        'patients_count',
        'is_verified',
        'is_available',
        'available_days',
        'available_from',
        'available_to',
        'session_duration',
        'break_duration',
        'schedule_settings',
        'governorate_id'
    ];

    const DAYS = [
        'saturday' => 'السبت',
        'sunday' => 'الأحد',
        'monday' => 'الاثنين',
        'tuesday' => 'الثلاثاء',
        'wednesday' => 'الأربعاء',
        'thursday' => 'الخميس',
        'friday' => 'الجمعة'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'available_days' => 'array',
        'schedule_settings' => 'array',
        'session_duration' => 'integer',
        'break_duration' => 'integer'
    ];

    // Relationships
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function ratings()
    {
        return $this->hasMany(DoctorRating::class);
    }

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    // Schedule Management Methods
    public function generateTimeSlots($date)
    {
        $date = Carbon::parse($date);
        $dayName = strtolower($date->format('l'));

        if (!$this->isAvailableOnDay($dayName)) {
            return [];
        }

        $schedule = $this->getDaySchedule($dayName);
        if (!$schedule) {
            return [];
        }

        return $this->generateSlotsForSchedule($date, $schedule);
    }

    public function updateSchedule(array $scheduleData)
    {
        $this->validateScheduleData($scheduleData);

        $this->schedule_settings = $scheduleData;
        $this->available_days = collect($scheduleData)
            ->filter(fn($settings) => $settings['enabled'])
            ->keys()
            ->toArray();

        $this->save();

        $this->updateScheduleRecords($scheduleData);
        $this->clearConflictingAppointments();
    }

    // Helper Methods
    public function isAvailableOnDay($dayName): bool
    {
        return in_array($dayName, $this->available_days ?? []);
    }

    public function getDaySchedule($dayName): ?array
    {
        $schedule = $this->schedule_settings[$dayName] ?? null;
        return ($schedule && $schedule['enabled']) ? $schedule : null;
    }

    public function hasSchedule(): bool
    {
        return !empty($this->available_days) && !empty($this->schedule_settings);
    }

    public function isAvailableToday(): bool
    {
        if (!$this->is_available) {
            return false;
        }

        return $this->isAvailableOnDay(strtolower(now()->format('l')));
    }

    // Private Helper Methods
    private function generateSlotsForSchedule($date, array $schedule): array
    {
        $slots = [];
        $startTime = Carbon::parse($schedule['start_time'])->setDateFrom($date);
        $endTime = Carbon::parse($schedule['end_time'])->setDateFrom($date);

        while ($startTime->copy()->addMinutes($this->session_duration)->lte($endTime)) {
            $slotEnd = $startTime->copy()->addMinutes($this->session_duration);

            if (!$this->isSlotBooked($date, $startTime)) {
                $slots[] = [
                    'start' => $startTime->format('H:i'),
                    'end' => $slotEnd->format('H:i'),
                    'available' => true
                ];
            }

            $startTime->addMinutes($this->session_duration + $this->break_duration);
        }

        return $slots;
    }

    private function isSlotBooked($date, $startTime): bool
    {
        return $this->appointments()
            ->where('date', $date->format('Y-m-d'))
            ->where('start_time', $startTime->format('H:i:s'))
            ->exists();
    }

    private function validateScheduleData(array $scheduleData)
    {
        foreach ($scheduleData as $day => $settings) {
            if ($settings['enabled']) {
                if (empty($settings['start_time']) || empty($settings['end_time'])) {
                    throw new \InvalidArgumentException("Missing time settings for {$this->getDayName($day)}");
                }

                $startTime = Carbon::parse($settings['start_time']);
                $endTime = Carbon::parse($settings['end_time']);

                if ($startTime->greaterThanOrEqualTo($endTime)) {
                    throw new \InvalidArgumentException(
                        "End time must be after start time for {$this->getDayName($day)}"
                    );
                }
            }
        }
    }

    private function updateScheduleRecords(array $scheduleData)
    {
        $this->schedules()->delete();

        foreach ($scheduleData as $day => $settings) {
            if ($settings['enabled']) {
                $this->schedules()->create([
                    'day' => $day,
                    'start_time' => $settings['start_time'],
                    'end_time' => $settings['end_time'],
                    'is_available' => true
                ]);
            }
        }
    }

    private function clearConflictingAppointments()
    {
        $futureAppointments = $this->appointments()
            ->where('date', '>', now())
            ->get();

        foreach ($futureAppointments as $appointment) {
            $appointmentDay = strtolower(Carbon::parse($appointment->date)->format('l'));
            $daySchedule = $this->getDaySchedule($appointmentDay);

            if (!$daySchedule) {
                $appointment->delete();
                continue;
            }

            $appointmentStart = Carbon::parse($appointment->start_time);
            $appointmentEnd = Carbon::parse($appointment->end_time);
            $workStart = Carbon::parse($daySchedule['start_time']);
            $workEnd = Carbon::parse($daySchedule['end_time']);

            if ($appointmentStart->lt($workStart) || $appointmentEnd->gt($workEnd)) {
                $appointment->delete();
            }
        }
    }

    // Attribute Getters
    public function getFullNameAttribute(): string
    {
        return "د. {$this->first_name} {$this->last_name}";
    }

    public function getAvailabilityTimeAttribute(): ?string
    {
        if ($this->available_from && $this->available_to) {
            return date('h:i A', strtotime($this->available_from)) . ' - ' .
                date('h:i A', strtotime($this->available_to));
        }
        return null;
    }

    public function getFormattedAvailableDaysAttribute(): array
    {
        if (!$this->available_days) {
            return [];
        }

        return collect($this->available_days)->map(function ($day) {
            return self::DAYS[$day] ?? $day;
        })->toArray();
    }

    private function getDayName($day): string
    {
        return self::DAYS[$day] ?? $day;
    }

    public function getTotalPatientsAttribute()
    {
        return $this->appointments()
            ->where('status', 'completed')
            ->distinct('user_id')
            ->count('user_id');
    }
}
