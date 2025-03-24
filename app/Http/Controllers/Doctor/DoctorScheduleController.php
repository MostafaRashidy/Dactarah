<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DoctorScheduleController extends Controller
{
    public function index()
    {
        $doctor = auth()->user();
        return view('doctors.schedule.index', compact('doctor'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'session_duration' => 'required|integer|min:5',
            'break_duration' => 'required|integer|min:0',
            'schedules' => 'required|array',
            'available_days' => 'required|array'
        ]);

        try {
            $doctor = auth()->user();

            // Validate each schedule
            foreach ($validated['schedules'] as $day => $schedule) {
                if ($schedule['enabled']) {
                    if (empty($schedule['start_time']) || empty($schedule['end_time'])) {
                        return response()->json([
                            'success' => false,
                            'message' => "يجب تحديد وقت البداية والنهاية ليوم {$this->getDayName($day)}"
                        ], 422);
                    }

                    $startTime = Carbon::parse($schedule['start_time']);
                    $endTime = Carbon::parse($schedule['end_time']);

                    if ($startTime->greaterThanOrEqualTo($endTime)) {
                        return response()->json([
                            'success' => false,
                            'message' => "وقت النهاية يجب أن يكون بعد وقت البداية ليوم {$this->getDayName($day)}"
                        ], 422);
                    }
                }
            }

            // Update doctor's schedule
            $doctor->update([
                'session_duration' => $validated['session_duration'],
                'break_duration' => $validated['break_duration'],
                'available_days' => $validated['available_days'],
                'schedule_settings' => $validated['schedules']
            ]);

            // Clear any existing appointments that might conflict with new schedule
            $this->clearConflictingAppointments($doctor);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الجدول بنجاح',
                'data' => [
                    'available_days' => $doctor->available_days,
                    'schedule_settings' => $doctor->schedule_settings
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الجدول: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getDayName($day)
    {
        $days = [
            'sunday' => 'الأحد',
            'monday' => 'الاثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة',
            'saturday' => 'السبت'
        ];

        return $days[$day] ?? $day;
    }

    private function clearConflictingAppointments($doctor)
    {
        // Get future appointments
        $futureAppointments = $doctor->appointments()
            ->where('date', '>', now())
            ->get();

        foreach ($futureAppointments as $appointment) {
            $appointmentDay = strtolower(Carbon::parse($appointment->date)->format('l'));

            // Check if day is still available
            if (!in_array($appointmentDay, $doctor->available_days)) {
                $appointment->delete();
                continue;
            }

            // Check if time is still within working hours
            $daySchedule = $doctor->schedule_settings[$appointmentDay] ?? null;
            if (!$daySchedule || !$daySchedule['enabled']) {
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
}
