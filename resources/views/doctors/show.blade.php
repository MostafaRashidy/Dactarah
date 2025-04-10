@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Profile Header -->
        <div class="bg-white shadow-xl rounded-3xl overflow-hidden animate-fade-in">
            <!-- Cover and Profile Section -->
            <div class="relative">
                <!-- Gradient Cover -->
                <div class="h-48 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
                    <div class="absolute inset-0 opacity-50 bg-pattern"></div>
                </div>

                <!-- Profile Image and Basic Info -->
                <div class="absolute -bottom-16 left-1/2 transform -translate-x-1/2">
                    <div class="relative interactive-element">
                        <img
                            src="{{ $doctor->image ? asset($doctor->image) : asset('images/default-avatar.png') }}"
                            alt="{{ $doctor->full_name }}"
                            class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white shadow-lg object-cover"
                        >
                        <span class="absolute bottom-0 right-0 bg-green-500 w-6 h-6 rounded-full border-2 border-white"></span>
                    </div>
                </div>
            </div>

            <!-- Doctor Details -->
            <div class="pt-20 pb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900">{{ $doctor->full_name }}</h1>
                <p class="text-indigo-600 text-xl mt-2">{{ $doctor->specialty->name }}</p>

                <!-- Stats Section -->
                <div class="mt-6 flex flex-wrap justify-center items-center gap-4">
                    <div class="stat-card">
                        <div class="bg-indigo-50 text-indigo-600 px-6 py-3 rounded-full flex items-center">
                            <i class="fas fa-money-bill-wave ml-2"></i>
                            <span>سعر الكشف: {{ $doctor->price }} جنيه</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="bg-indigo-50 text-indigo-600 px-6 py-3 rounded-full flex items-center">
                            <i class="fas fa-users ml-2"></i>
                            <span>{{ $doctor->total_patients }}+ مريض</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="bg-indigo-50 text-indigo-600 px-6 py-3 rounded-full flex items-center">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $doctor->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                                <span class="mr-2">({{ $doctor->ratings_count }})</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid lg:grid-cols-3 gap-8 mt-12">
            <!-- Left Column: Contact & Location -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Contact Card -->
                <div class="card-hover bg-white shadow-lg rounded-3xl p-6 transform transition-all duration-300">
                    <h2 class="text-2xl font-bold text-indigo-600 mb-6 flex items-center">
                        <i class="fas fa-address-card ml-3 text-xl"></i>
                        معلومات التواصل
                    </h2>
                    <div class="space-y-4">
                        <a href="tel:{{ $doctor->phone }}" class="contact-button group">
                            <div class="flex items-center gap-3 w-full">
                                <i class="fas fa-phone text-indigo-500 group-hover:scale-110 transition-transform"></i>
                                <span class="flex-1">{{ $doctor->phone }}</span>
                            </div>
                        </a>
                        <a href="mailto:{{ $doctor->communication_email }}" class="contact-button group">
                            <div class="flex items-center gap-3 w-full">
                                <i class="fas fa-envelope text-indigo-500 group-hover:scale-110 transition-transform"></i>
                                <span class="flex-1">{{ $doctor->communication_email }}</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Location Card -->
                <div class="card-hover bg-white shadow-lg rounded-3xl p-6">
                    <h2 class="text-2xl font-bold text-indigo-600 mb-6 flex items-center">
                        <i class="fas fa-map-marker-alt ml-3 text-xl"></i>
                        موقع العيادة
                    </h2>
                    <div class="mt-4 bg-gray-50 hover:bg-indigo-50 transition-colors duration-300 p-4 rounded-xl flex items-center mb-4">
                        <i class="fas fa-map-marker-alt text-indigo-500 ml-3"></i>
                        <span class="text-gray-700">
                            {{ $doctor->governorate ? $doctor->governorate->name_ar : 'المحافظة غير محددة' }}
                        </span>
                    </div>
                    <div id="map" class="h-64 rounded-xl shadow-inner"></div>
                </div>
            </div>

            <!-- Middle Column: Booking -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Availability Card -->
                <div class="card-hover bg-white shadow-lg rounded-3xl p-6" x-data="appointmentSystem()">
                    <h2 class="text-2xl font-bold text-indigo-600 mb-6 flex items-center">
                        <i class="fas fa-calendar-alt ml-3 text-xl"></i>
                        حجز موعد
                    </h2>

                    <!-- Working Days -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">أيام العمل:</h3>



                        @if($doctor->hasSchedule())
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                @foreach($doctor->available_days as $day)
                                    <div class="bg-indigo-50 text-indigo-600 px-3 py-2 rounded-lg text-center">
                                        {{ \App\Models\Doctor::DAYS[$day] ?? $day }}
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-gray-500 p-4 bg-gray-50 rounded-lg">
                                لم يتم تحديد أيام العمل بعد
                            </div>
                        @endif
                    </div>

                    <!-- Date Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">اختر اليوم</label>
                        <input
                            type="date"
                            x-model="selectedDate"
                            @change="fetchTimeSlots()"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            :min="today"
                        >
                    </div>

                    <!-- Time Slots -->
                    <div class="relative">
                        <!-- Loading State -->
                        <div x-show="isLoading" class="text-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
                        </div>

                        <!-- Available Slots -->
                        <div x-show="!isLoading && slots.length > 0" class="grid grid-cols-3 gap-2">
                            <template x-for="slot in slots" :key="slot.start">
                                <button
                                    class="time-slot-button"
                                    :class="{'bg-indigo-600 text-white': slot.selected, 'bg-gray-100 text-gray-700 hover:bg-gray-200': !slot.selected}"
                                    @click="selectSlot(slot)"
                                >
                                    <span x-text="slot.start"></span>
                                    <span class="text-xs" x-text="'- ' + slot.end"></span>
                                </button>
                            </template>
                        </div>

                        <!-- No Slots Available -->
                        <div x-show="!isLoading && slots.length === 0" class="text-center py-8">
                            <p class="text-gray-500">لا توجد مواعيد متاحة في هذا اليوم</p>
                        </div>
                    </div>

                    <!-- Booking Button -->
                    <div class="mt-6">
                        <template x-if="!isAuthenticated">
                            <a href="{{ route('login') }}"
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-4 rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 text-center block">
                                تسجيل الدخول للحجز
                            </a>
                        </template>

                        <template x-if="isAuthenticated && selectedSlot">
                            <button
                                @click="confirmBooking()"
                                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-4 rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300"
                            >
                                تأكيد الحجز
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Right Column: Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Description Card -->
                <div class="card-hover bg-white shadow-lg rounded-3xl p-6">
                    <h2 class="text-2xl font-bold text-indigo-600 mb-6 flex items-center">
                        <i class="fas fa-info-circle ml-3 text-xl"></i>
                        نبذة عن الطبيب
                    </h2>
                    <p class="text-gray-700 leading-relaxed">
                        {{ $doctor->description ?: 'لم يتم إضافة وصف للطبيب بعد.' }}
                    </p>
                </div>

                <!-- Session Info Card -->
                <div class="card-hover bg-white shadow-lg rounded-3xl p-6">
                    <h2 class="text-2xl font-bold text-indigo-600 mb-6 flex items-center">
                        <i class="fas fa-clock ml-3 text-xl"></i>
                        معلومات الجلسة
                    </h2>
                    <div class="space-y-4">
                        <div class="stat-row hover:scale-102 transition-transform">
                            <span class="text-gray-600">مدة الجلسة</span>
                            <div class="bg-indigo-500 text-white px-5 py-2.5 rounded-full">
                                {{ $doctor->session_duration }} دقيقة
                            </div>
                        </div>
                        <div class="stat-row hover:scale-102 transition-transform">
                            <span class="text-gray-600">وقت الراحة</span>
                            <div class="bg-blue-500 text-white px-5 py-2.5 rounded-full">
                                {{ $doctor->break_duration }} دقيقة
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
    /* Card Styles */
    .card-hover {
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    /* Stats Cards */
    .stat-card {
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: scale(1.05);
    }

    /* Time Slots */
    .time-slot-button {
        @apply px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 flex flex-col items-center justify-center;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .time-slot-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Contact Buttons */
    .contact-button {
        @apply flex items-center gap-3 p-4 bg-gray-50 rounded-xl text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-300;
    }

    /* Stats Row */
    .stat-row {
        @apply flex justify-between items-center bg-gray-50 p-4 rounded-xl transition-all duration-300;
    }

    .stat-row:hover {
        @apply bg-indigo-50;
    }

    /* Map Container */
    #map {
        @apply rounded-xl border-2 border-gray-100;
        min-height: 300px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    /* Gradient Background */
    .bg-pattern {
        background-image: linear-gradient(45deg, rgba(0, 0, 0, 0.1) 25%, transparent 25%),
                          linear-gradient(-45deg, rgba(0, 0, 0, 0.1) 25%, transparent 25%),
                          linear-gradient(45deg, transparent 75%, rgba(0, 0, 0, 0.1) 75%),
                          linear-gradient(-45deg, transparent 75%, rgba(0, 0, 0, 0.1) 75%);
        background-size: 20px 20px;
    }

    /* Date Input Styling */
    input[type="date"] {
        @apply px-4 py-3 rounded-xl border-gray-200 shadow-sm;
    }

    input[type="date"]:focus {
        @apply ring-2 ring-indigo-500 border-indigo-500;
    }

    /* Loading Animation */
    .animate-spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .stat-card {
            width: 100%;
        }

        .grid-cols-3 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .grid-cols-3 {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function appointmentSystem() {
    return {
        selectedDate: new Date().toISOString().split('T')[0],
        slots: [],
        isLoading: false,
        selectedSlot: null,
        today: new Date().toISOString().split('T')[0],
        isAuthenticated: {{
            auth()->guard('web')->check() ? 'true' : 'false'
        }},

        async fetchTimeSlots() {
            this.isLoading = true;
            this.slots = [];
            this.selectedSlot = null;

            try {
                const response = await fetch(`/doctors/${this.getDoctorId()}/time-slots?date=${this.selectedDate}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();

                if (data.slots) {
                    this.slots = data.slots.map(slot => ({
                        ...slot,
                        selected: false
                    }));
                }
            } catch (error) {
                console.error('Error fetching time slots:', error);
                Swal.fire({
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء تحميل المواعيد المتاحة',
                    icon: 'error'
                });
            } finally {
                this.isLoading = false;
            }
        },

        getDoctorId() {
            return '{{ $doctor->id }}';
        },

        checkAuthentication() {
            // Check if the authenticated user is an admin or doctor
            const authenticatedUserTypes = {
                doctor: {{ auth()->guard('doctor')->check() ? 'true' : 'false' }},
                admin: {{ auth()->guard('admin')->check() ? 'true' : 'false' }}
            };

            // Custom blocking messages
            const blockingMessages = {
                doctor: 'لا يمكن للطبيب حجز المواعيد',
                admin: 'لا يمكن للمشرف حجز المواعيد'
            };

            // If the user is a doctor, show an error message
            if (authenticatedUserTypes.doctor) {
                Swal.fire({
                    title: 'تنبيه',
                    text: blockingMessages.doctor,
                    icon: 'warning',
                    confirmButtonText: 'موافق'
                });
                return false;
            }

            // If the user is an admin, show an error message
            if (authenticatedUserTypes.admin) {
                Swal.fire({
                    title: 'تنبيه',
                    text: blockingMessages.admin,
                    icon: 'warning',
                    confirmButtonText: 'موافق'
                });
                return false;
            }

            // If not authenticated as a patient, prompt to log in
            if (!this.isAuthenticated) {
                Swal.fire({
                    title: 'تنبيه',
                    text: 'يجب تسجيل الدخول أولاً لحجز موعد',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'تسجيل الدخول',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route("login") }}';
                    }
                });
                return false;
            }

            return true;
        },

        selectSlot(slot) {
            // First check authentication and user type
            if (!this.checkAuthentication()) {
                return;
            }

            this.slots.forEach(s => s.selected = false);
            slot.selected = true;
            this.selectedSlot = slot;
        },

        async confirmBooking() {
            // Verify authentication and user type
            if (!this.checkAuthentication()) {
                return;
            }

            // Ensure a slot is selected
            if (!this.selectedSlot) {
                Swal.fire({
                    title: 'خطأ',
                    text: 'يرجى اختيار موعد',
                    icon: 'error'
                });
                return;
            }

            // Booking confirmation dialog
            const result = await Swal.fire({
                title: 'تأكيد الحجز',
                html: `
                    <div class="text-right">
                        <p class="mb-2">هل تريد تأكيد حجز موعد:</p>
                        <p class="text-indigo-600">اليوم: ${this.selectedDate}</p>
                        <p class="text-indigo-600">الوقت: ${this.selectedSlot.start} - ${this.selectedSlot.end}</p>
                        <p class="mt-2">سعر الكشف: ${{{ $doctor->price }}} جنيه</p>

                        <input
                            type="tel"
                            id="appointment-phone"
                            placeholder="رقم الهاتف"
                            class="w-full mt-4 p-2 border rounded-lg"
                            value="${this.getUserPhone()}"
                        />

                        <textarea
                            id="appointment-note"
                            placeholder="ملاحظات إضافية (اختياري)"
                            class="w-full mt-4 p-2 border rounded-lg"
                            rows="3"
                        ></textarea>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'تأكيد الحجز',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#4F46E5',
                preConfirm: () => {
                    return {
                        phone: document.getElementById('appointment-phone').value,
                        note: document.getElementById('appointment-note').value
                    }
                }
            });

            // Process booking if confirmed
            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/doctors/${this.getDoctorId()}/book`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            doctor_id: '{{ $doctor->id }}',
                            date: this.selectedDate,
                            start_time: this.selectedSlot.start,
                            end_time: this.selectedSlot.end,
                            note: result.value.note,
                            phone: result.value.phone
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            title: 'تم الحجز بنجاح',
                            text: 'سيتم التواصل معك قريباً لتأكيد الحجز',
                            icon: 'success'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'حدث خطأ أثناء الحجز');
                    }
                } catch (error) {
                    console.error('Booking error:', error);
                    Swal.fire({
                        title: 'خطأ',
                        text: error.message || 'حدث خطأ أثناء الحجز',
                        icon: 'error'
                    });
                }
            }
        },

        // Method to get user phone for patients
        getUserPhone() {
            return '{{ auth()->guard('web')->check() ? auth()->guard('web')->user()->phone : '' }}';
        }
    }
}

// Initialize map
document.addEventListener('DOMContentLoaded', function() {
    const map = L.map('map').setView([{{ $doctor->latitude }}, {{ $doctor->longitude }}], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    L.marker([{{ $doctor->latitude }}, {{ $doctor->longitude }}])
        .addTo(map)
        .bindPopup('{{ $doctor->full_name }}');
});
</script>
@endsection
