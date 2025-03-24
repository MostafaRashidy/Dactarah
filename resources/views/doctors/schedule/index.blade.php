@extends('layouts.app')

@section('content')
<div x-data="{ preventScroll: true }"
     x-init="() => {
         if (preventScroll) document.body.style.overflow = 'hidden';
         return () => document.body.style.overflow = 'auto';
     }"
     class="min-h-screen bg-gray-50 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">إدارة جدول المواعيد</h1>
            <p class="mt-2 text-gray-600">قم بتخصيص أوقات عملك وإعدادات المواعيد</p>
        </div>

        <!-- Schedule Management Form -->
        <div class="bg-white rounded-lg shadow-lg p-6" x-data="scheduleManager()">
            <!-- Session Settings -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">إعدادات الجلسات</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">مدة الجلسة (بالدقائق)</label>
                        <input
                            type="number"
                            x-model="sessionDuration"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            min="5"
                            step="5"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">وقت الراحة بين الجلسات (بالدقائق)</label>
                        <input
                            type="number"
                            x-model="breakDuration"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            min="0"
                            step="5"
                        >
                    </div>
                </div>
            </div>

            <!-- Weekly Schedule -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">الجدول الأسبوعي</h2>
            <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3">
                <template x-for="(schedule, day) in schedules" :key="day">
                    <div
                        class="relative rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105"
                        :class="{
                            'bg-indigo-50 border-2 border-indigo-200': schedule.enabled,
                            'bg-gray-100 opacity-60': !schedule.enabled
                        }"
                    >
                        <div class="absolute top-2 right-2">
                            <input
                                type="checkbox"
                                x-model="schedule.enabled"
                                class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                :id="day"
                            >
                        </div>

                        <div class="p-4 text-center">
                            <div
                                class="mb-2 text-sm font-semibold"
                                :class="schedule.enabled ? 'text-indigo-700' : 'text-gray-500'"
                                x-text="getDayName(day)"
                            ></div>

                            <template x-if="schedule.enabled">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-center">
                                        <i class="fas fa-clock text-indigo-500 ml-2"></i>
                                        <input
                                            type="time"
                                            x-model="schedule.start_time"
                                            class="w-full text-center text-sm rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                    </div>
                                    <div class="flex items-center justify-center">
                                        <i class="fas fa-clock text-indigo-500 ml-2"></i>
                                        <input
                                            type="time"
                                            x-model="schedule.end_time"
                                            class="w-full text-center text-sm rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                    </div>
                                </div>
                            </template>

                            <template x-if="!schedule.enabled">
                                <div class="text-xs text-gray-400 mt-2">غير مفعل</div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4">
                <button
                    @click="saveSchedule"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    :disabled="isSaving"
                >
                    <span x-show="!isSaving">حفظ التغييرات</span>
                    <span x-show="isSaving">جاري الحفظ...</span>
                </button>
            </div>

            <!-- Error Message -->
            <div
                x-show="errorMessage"
                class="mt-4 bg-red-50 text-red-600 p-4 rounded-lg"
                x-text="errorMessage"
            ></div>
        </div>
    </div>
</div>
@endsection

<!-- Styling for extra flair -->
@push('styles')
<style>
    /* Glassmorphism-inspired effect */
    .schedule-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.125);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
    }

    body.schedule-page {
        overflow: hidden !important;
    }
</style>
@endpush

@push('scripts')
<script>
function scheduleManager() {
    return {
        sessionDuration: {{ $doctor->session_duration ?? 30 }},
        breakDuration: {{ $doctor->break_duration ?? 10 }},
        schedules: {
            sunday: { enabled: false, start_time: '09:00', end_time: '17:00' },
            monday: { enabled: false, start_time: '09:00', end_time: '17:00' },
            tuesday: { enabled: false, start_time: '09:00', end_time: '17:00' },
            wednesday: { enabled: false, start_time: '09:00', end_time: '17:00' },
            thursday: { enabled: false, start_time: '09:00', end_time: '17:00' },
            friday: { enabled: false, start_time: '09:00', end_time: '17:00' },
            saturday: { enabled: false, start_time: '09:00', end_time: '17:00' }
        },
        isSaving: false,
        errorMessage: '',

        init() {
            // Initialize schedules from doctor's settings
            const savedSchedules = @json($doctor->schedule_settings ?? null);
            const availableDays = @json($doctor->available_days ?? []);

            // Reset all schedules
            Object.keys(this.schedules).forEach(day => {
                this.schedules[day] = {
                    enabled: false,
                    start_time: '09:00',
                    end_time: '17:00'
                };
            });

            // Apply saved schedules
            if (savedSchedules) {
                Object.keys(savedSchedules).forEach(day => {
                    if (this.schedules[day]) {
                        this.schedules[day] = {
                            ...this.schedules[day],
                            ...savedSchedules[day]
                        };
                    }
                });
            }

            // Enable days from available_days
            if (availableDays && availableDays.length > 0) {
                availableDays.forEach(day => {
                    if (this.schedules[day]) {
                        this.schedules[day].enabled = true;
                    }
                });
            }

            // Add watchers for time validation
            Object.keys(this.schedules).forEach(day => {
                this.$watch(`schedules.${day}.start_time`, () => {
                    this.validateTimeRange(day);
                });
                this.$watch(`schedules.${day}.end_time`, () => {
                    this.validateTimeRange(day);
                });
            });
        },

        getDayName(day) {
            const days = {
                sunday: 'الأحد',
                monday: 'الاثنين',
                tuesday: 'الثلاثاء',
                wednesday: 'الأربعاء',
                thursday: 'الخميس',
                friday: 'الجمعة',
                saturday: 'السبت'
            };
            return days[day];
        },

        validateTimeRange(day) {
            const schedule = this.schedules[day];

            // Skip validation if not enabled
            if (!schedule.enabled) return;

            // Convert times to minutes for easy comparison
            const startMinutes = this.timeToMinutes(schedule.start_time);
            const endMinutes = this.timeToMinutes(schedule.end_time);

            // Ensure end time is after start time
            if (endMinutes <= startMinutes) {
                // Automatically adjust end time
                const adjustedEndTime = this.addMinutes(schedule.start_time, 480); // 8 hours
                schedule.end_time = this.minutesToTime(adjustedEndTime);
            }
        },

        // Convert time string (HH:MM) to minutes since midnight
        timeToMinutes(timeString) {
            const [hours, minutes] = timeString.split(':').map(Number);
            return hours * 60 + minutes;
        },

        // Convert minutes since midnight to time string (HH:MM)
        minutesToTime(minutes) {
            const hrs = Math.floor(minutes / 60);
            const mins = minutes % 60;
            return `${hrs.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}`;
        },

        // Add minutes to a time string
        addMinutes(timeString, minutesToAdd) {
            const totalMinutes = this.timeToMinutes(timeString) + minutesToAdd;
            return totalMinutes % (24 * 60); // Wrap around 24 hours
        },

        async saveSchedule() {
            this.isSaving = true;
            this.errorMessage = '';

            // Validate all time ranges before saving
            Object.keys(this.schedules).forEach(day => {
                this.validateTimeRange(day);
            });

            // Get enabled days
            const enabledDays = Object.entries(this.schedules)
                .filter(([_, schedule]) => schedule.enabled)
                .map(([day]) => day);

            try {
                const response = await fetch('{{ route("doctor.schedule.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        session_duration: this.sessionDuration,
                        break_duration: this.breakDuration,
                        schedules: this.schedules,
                        available_days: enabledDays
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    await Swal.fire({
                        title: 'تم الحفظ',
                        text: 'تم حفظ الجدول بنجاح',
                        icon: 'success',
                        confirmButtonText: 'حسناً'
                    });
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'حدث خطأ أثناء حفظ الجدول');
                }
            } catch (error) {
                this.errorMessage = error.message;
                await Swal.fire({
                    title: 'خطأ',
                    text: this.errorMessage,
                    icon: 'error',
                    confirmButtonText: 'حسناً'
                });
            } finally {
                this.isSaving = false;
            }
        }
    }
}
</script>
@endpush
