<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('لوحة التحكم') }}
            </h2>
            <div class="flex items-center gap-4">
                <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                    <i class="fas fa-circle text-xs text-green-500 mr-1"></i>
                    متصل
                </span>
                {{-- <span class="text-gray-500">
                    آخر تسجيل دخول: {{ auth()->user()->last_login_at ?? 'لم يتم تسجيل الدخول' }}
                </span> --}}
            </div>
        </div>
    </x-slot>

     <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Appointments -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-lg font-semibold text-gray-700">إجمالي المواعيد</div>
                        <div class="bg-blue-100 p-2 rounded-lg">
                            <i class="fas fa-calendar-check text-blue-500"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ $stats['total_appointments'] }}</div>
                </div>

                <!-- Pending Appointments -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-lg font-semibold text-gray-700">المواعيد المعلقة</div>
                        <div class="bg-yellow-100 p-2 rounded-lg">
                            <i class="fas fa-clock text-yellow-500"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ $stats['pending_appointments'] }}</div>
                </div>

                <!-- Completed Appointments -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-lg font-semibold text-gray-700">المواعيد المكتملة</div>
                        <div class="bg-green-100 p-2 rounded-lg">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ $stats['completed_appointments'] }}</div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Upcoming Appointments -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">المواعيد القادمة</h3>
                    </div>
                    <div class="p-6">
                        @forelse($upcomingAppointments as $appointment)
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl mb-4">
                                <div class="bg-blue-100 p-3 rounded-lg">
                                    <i class="fas fa-user-md text-blue-500"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-gray-900">{{ $appointment->doctor->full_name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $appointment->doctor->specialty->name }}</p>
                                </div>
                                <div class="text-left">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ Carbon\Carbon::parse($appointment->date)->format('Y/m/d') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button onclick="window.location.href='{{ route('appointments.show', $appointment) }}'"
                                        class="bg-white px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 border border-gray-200">
                                        التفاصيل
                                    </button>
                                    @if($appointment->status === 'pending')
                                        <button onclick="cancelAppointment('{{ $appointment->id }}')"
                                            class="bg-red-50 px-4 py-2 rounded-lg text-sm font-medium text-red-700 hover:bg-red-100 border border-red-200">
                                            إلغاء
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-4">
                                لا توجد مواعيد قادمة
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">النشاط الأخير</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($recentActivities as $activity)
                                <div class="flex items-center gap-4">
                                    <div class="bg-{{ $activity->status_color }}-100 p-2 rounded-lg">
                                        <i class="fas fa-{{ $activity->status_icon }} text-{{ $activity->status_color }}-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-900">
                                            {{ $activity->status_message }}
                                            مع {{ $activity->doctor->full_name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $activity->updated_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-gray-500">
                                    لا يوجد نشاط حديث
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">إجراءات سريعة</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <button onclick="window.location.href='{{ route('doctors.index') }}'"
                        class="flex items-center justify-center gap-2 p-4 rounded-xl bg-blue-50 hover:bg-blue-100 transition-colors">
                        <i class="fas fa-calendar-plus text-blue-500"></i>
                        <span class="text-sm font-medium text-gray-700">حجز موعد جديد</span>
                    </button>

                    <button onclick="window.location.href='{{ route('appointments.index') }}'"
                        class="flex items-center justify-center gap-2 p-4 rounded-xl bg-purple-50 hover:bg-purple-100 transition-colors">
                        <i class="fas fa-calendar-check text-purple-500"></i>
                        <span class="text-sm font-medium text-gray-700">مواعيدي</span>
                    </button>

                    <button onclick="window.location.href='{{ route('profile.edit') }}'"
                        class="flex items-center justify-center gap-2 p-4 rounded-xl bg-green-50 hover:bg-green-100 transition-colors">
                        <i class="fas fa-user-edit text-green-500"></i>
                        <span class="text-sm font-medium text-gray-700">تعديل الملف</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    async function cancelAppointment(appointmentId) {
        const result = await Swal.fire({
            title: 'تأكيد الإلغاء',
            text: 'هل أنت متأكد من إلغاء هذا الموعد؟',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم، إلغاء الموعد',
            cancelButtonText: 'لا، تراجع',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`/appointments/${appointmentId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    Swal.fire({
                        title: 'تم',
                        text: 'تم إلغاء الموعد بنجاح',
                        icon: 'success'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire({
                    title: 'خطأ',
                    text: error.message || 'حدث خطأ أثناء إلغاء الموعد',
                    icon: 'error'
                });
            }
        }
    }
    </script>
    @endpush
</x-app-layout>
