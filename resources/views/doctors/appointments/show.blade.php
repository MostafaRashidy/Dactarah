@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
            <!-- Appointment Header -->
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 ml-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    تفاصيل الموعد
                </h2>
            <!-- Status -->
                                <div>
                                    @php
                                        $statusClasses =
                                            [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'confirmed' => 'bg-green-100 text-green-800',
                                                'completed' => 'bg-blue-100 text-blue-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                            ][$appointment->status] ?? 'bg-gray-100 text-gray-800';

                                        $statusText =
                                            [
                                                'pending' => 'قيد الانتظار',
                                                'confirmed' => 'مؤكد',
                                                'completed' => 'مكتمل',
                                                'cancelled' => 'ملغي',
                                            ][$appointment->status] ?? 'غير معروف';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-sm {{ $statusClasses }}">
                                        {{ $statusText }}
                                    </span>
                                </div>
            </div>

            <!-- Appointment Body -->
            <div class="grid md:grid-cols-2 gap-8 p-6">
                <!-- Patient Information -->
                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-2xl p-6 shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center border-b pb-3 border-gray-200">
                            <svg class="w-6 h-6 ml-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            معلومات المريض
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">الاسم</span>
                                <span class="font-medium text-gray-800">{{ $appointment->user->name }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">رقم الهاتف</span>
                                <span class="font-medium text-gray-800">{{ $appointment->user_phone ?? $appointment->user->phone }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Details -->
                    <div class="bg-gray-50 rounded-2xl p-6 shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center border-b pb-3 border-gray-200">
                            <svg class="w-6 h-6 ml-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            تفاصيل الموعد
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">التاريخ</span>
                                <span class="font-medium text-gray-800">
                                    {{ \Carbon\Carbon::parse($appointment->date)->format('Y/m/d') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">وقت البداية</span>
                                <span class="font-medium text-gray-800">
                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">وقت النهاية</span>
                                <span class="font-medium text-gray-800">
                                    {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">سعر الكشف</span>
                                <span class="font-medium text-gray-800">{{ $appointment->doctor->price }} جنيه</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="space-y-6">
                    <!-- Patient Notes -->
                    <div class="bg-gray-50 rounded-2xl p-6 shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center border-b pb-3 border-gray-200">
                            <svg class="w-6 h-6 ml-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            ملاحظات المريض
                        </h3>
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            @if($appointment->note)
                                <p class="text-gray-700 leading-relaxed">{{ $appointment->note }}</p>
                            @else
                                <p class="text-gray-500 italic text-center">لا توجد ملاحظات</p>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-4">
                        @if($appointment->status === 'pending')
                            <div class="flex space-x-4 rtl:space-x-reverse">
                                <button
                                    onclick="updateStatus('{{ $appointment->id }}', 'confirmed')"
                                    class="w-full bg-green-600 text-white py-3 rounded-xl hover:bg-emerald-500 transition-all duration-300 ease-in-out transform hover:-translate-y-1 shadow-md hover:shadow-lg">
                                    تأكيد الموعد
                                </button>
                                <button
                                    onclick="updateStatus('{{ $appointment->id }}', 'cancelled')"
                                    class="w-full bg-red-700 text-white py-3 rounded-xl hover:bg-rose-600 transition-all duration-300 ease-in-out transform hover:-translate-y-1 shadow-md hover:shadow-lg">
                                    إلغاء الموعد
                                </button>
                            </div>
                        @elseif($appointment->status === 'confirmed')
                            <button
                                onclick="updateStatus('{{ $appointment->id }}', 'completed')"
                                class="w-full bg-sky-500 text-white py-3 rounded-xl hover:bg-sky-600 transition-all duration-300 ease-in-out transform hover:-translate-y-1 shadow-md hover:shadow-lg">
                                إكمال الموعد
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
async function updateStatus(appointmentId, status) {
    const statusNames = {
        'confirmed': 'تأكيد',
        'cancelled': 'إلغاء',
        'completed': 'إكمال'
    };

    const result = await Swal.fire({
        title: 'تأكيد العملية',
        text: `هل أنت متأكد من ${statusNames[status]} هذا الموعد؟`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'نعم، متأكد',
        cancelButtonText: 'إلغاء',
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#ef4444'
    });

    if (!result.isConfirmed) return;

    Swal.fire({
        title: 'جاري التحديث...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    try {
        const response = await fetch(`/doctor/appointments/${appointmentId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status })
        });

        const data = await response.json();

        if (response.ok) {
            await Swal.fire({
                title: 'تم بنجاح!',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'حسناً'
            });
            window.location.reload();
        } else {
            throw new Error(data.message || 'حدث خطأ أثناء تحديث الحالة');
        }
    } catch (error) {
        await Swal.fire({
            title: 'خطأ!',
            text: error.message,
            icon: 'error',
            confirmButtonText: 'حسناً'
        });
    }
}
</script>
@endpush
