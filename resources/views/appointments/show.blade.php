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
                <!-- Doctor Information -->
                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-user-md ml-2 text-indigo-600"></i>
                            معلومات الطبيب
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">الاسم</span>
                                <span class="font-medium">{{ $appointment->doctor->full_name }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">التخصص</span>
                                <span class="font-medium">{{ $appointment->doctor->specialty->name }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">رقم الهاتف</span>
                                <span class="font-medium">{{ $appointment->doctor->phone }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Details -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-calendar-alt ml-2 text-indigo-600"></i>
                            تفاصيل الموعد
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">التاريخ</span>
                                <span class="font-medium">
                                    {{ \Carbon\Carbon::parse($appointment->date)->format('Y/m/d') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">وقت البداية</span>
                                <span class="font-medium">
                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">وقت النهاية</span>
                                <span class="font-medium">
                                    {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">سعر الكشف</span>
                                <span class="font-medium">{{ $appointment->doctor->price }} جنيه</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="space-y-6">
                    <!-- Patient Notes -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-notes-medical ml-2 text-indigo-600"></i>
                            ملاحظاتك
                        </h3>
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            @if($appointment->note)
                                <p class="text-gray-700">{{ $appointment->note }}</p>
                            @else
                                <p class="text-gray-500 italic">لا توجد ملاحظات</p>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-4">
                        @if($appointment->status === 'pending')
                            <div class="flex space-x-4 rtl:space-x-reverse">
                                <button
                                    onclick="cancelAppointment('{{ $appointment->id }}')"
                                    class="w-full bg-red-500 text-white py-3 rounded-xl hover:bg-red-600 transition-colors">
                                    إلغاء الموعد
                                </button>
                            </div>
                        @elseif($appointment->status === 'completed' && !$appointment->hasRating())
                            <button
                                onclick="rateAppointment('{{ $appointment->id }}', '{{ $appointment->doctor->id }}')"
                                class="w-full bg-indigo-500 text-white py-3 rounded-xl hover:bg-indigo-600 transition-colors">
                                تقييم الموعد
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
                    window.location.href = '{{ route('appointments.index') }}';
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

async function rateAppointment(appointmentId, doctorId) {
    const { value: formValues } = await Swal.fire({
        title: 'تقييم الطبيب',
        html: `
            <div class="text-right mb-4">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">التقييم</label>
                    <div class="flex flex-row-reverse justify-center gap-2 rating-stars" dir="ltr">
                        <input type="radio" name="rating" value="5" class="hidden peer/5" id="star5">
                        <label for="star5" class="cursor-pointer text-2xl text-gray-300 hover:text-yellow-400">★</label>

                        <input type="radio" name="rating" value="4" class="hidden peer/4" id="star4">
                        <label for="star4" class="cursor-pointer text-2xl text-gray-300 hover:text-yellow-400">★</label>

                        <input type="radio" name="rating" value="3" class="hidden peer/3" id="star3">
                        <label for="star3" class="cursor-pointer text-2xl text-gray-300 hover:text-yellow-400">★</label>

                        <input type="radio" name="rating" value="2" class="hidden peer/2" id="star2">
                        <label for="star2" class="cursor-pointer text-2xl text-gray-300 hover:text-yellow-400">★</label>

                        <input type="radio" name="rating" value="1" class="hidden peer/1" id="star1">
                        <label for="star1" class="cursor-pointer text-2xl text-gray-300 hover:text-yellow-400">★</label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">تعليقك (اختياري)</label>
                    <textarea id="review" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="3"></textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'إرسال التقييم',
        cancelButtonText: 'إلغاء',
        preConfirm: () => {
            const rating = document.querySelector('input[name="rating"]:checked')?.value;
            const review = document.getElementById('review').value;

            if (!rating) {
                Swal.showValidationMessage('يرجى اختيار التقييم');
                return false;
            }

            return { rating, review };
        }
    });

    if (formValues) {
        try {
            const url = "{{ route('appointments.rate', $appointment->id) }}";

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formValues)
            });

            const data = await response.json();

            if (response.ok) {
                await Swal.fire({
                    title: 'تم',
                    text: 'تم إضافة تقييمك بنجاح',
                    icon: 'success'
                });
                window.location.reload();
            } else {
                throw new Error(data.message || 'حدث خطأ أثناء إضافة التقييم');
            }
        } catch (error) {
            console.error('Rating error:', error);
            Swal.fire({
                title: 'خطأ',
                text: error.message || 'حدث خطأ أثناء إضافة التقييم',
                icon: 'error'
            });
        }
    }
}
</script>

<style>
    .rating-stars {
        direction: ltr;
    }

    .rating-stars label {
        transition: all 0.2s ease;
    }

    .rating-stars label:hover,
    .rating-stars label:hover ~ label {
        color: #FBBF24;
    }

    .rating-stars input:checked ~ label {
        color: #FBBF24;
    }

    /* Add this to fix the hover effect direction */
    .rating-stars {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
    }

    .rating-stars label:hover ~ label,
    .rating-stars label:hover {
        color: #FBBF24 !important;
    }
</style>
@endpush
