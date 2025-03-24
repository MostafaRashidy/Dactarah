<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('مواعيدي') }}
        </h2>
    </x-slot> --}}

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">مواعيدي</h2>
                        <div class="flex gap-4">
                            <select id="status-filter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="" {{ !request('status') ? 'selected' : '' }}>كل المواعيد</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>مؤكدة</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتملة</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغية</option>
                            </select>
                        </div>
                    </div>

                    <!-- Appointments List -->
                    <div class="overflow-x-auto max-h-[400px] overflow-y-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الطبيب
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        التاريخ
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الوقت
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الحالة
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الإجراءات
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($appointments as $appointment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover"
                                                        src="{{ $appointment->doctor->image ? asset($appointment->doctor->image) : asset('images/default-avatar.png') }}"
                                                        alt="{{ $appointment->doctor->full_name }}">
                                                </div>
                                                <div class="mr-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $appointment->doctor->full_name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $appointment->doctor->specialty->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($appointment->date)->format('Y/m/d') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 rounded-full text-sm
                                                {{ $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $appointment->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $appointment->status === 'completed' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $appointment->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                                @php
                                                    $statusText = [
                                                        'pending' => 'قيد الانتظار',
                                                        'confirmed' => 'مؤكد',
                                                        'completed' => 'مكتمل',
                                                        'cancelled' => 'ملغي',
                                                    ][$appointment->status] ?? 'غير معروف';
                                                @endphp
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-2">
                                                <a href="{{ route('appointments.show', $appointment) }}"
                                                class="bg-indigo-50 px-4 py-2 rounded-lg text-sm font-medium text-indigo-700 hover:bg-indigo-100 border border-indigo-200 flex items-center gap-2">
                                                    <i class="fas fa-eye"></i>
                                                    التفاصيل
                                                </a>

                                                @if($appointment->status === 'completed' && !$appointment->hasRating())
                                                    <button
                                                        onclick="rateDoctor('{{ $appointment->doctor_id }}', {{ $appointment->id }})"
                                                        class="bg-yellow-50 px-4 py-2 rounded-lg text-sm font-medium text-yellow-700 hover:bg-yellow-100 border border-yellow-200 flex items-center gap-2">
                                                        <i class="fas fa-star"></i>
                                                        تقييم الطبيب
                                                    </button>
                                                @endif

                                                @if ($appointment->status === 'pending')
                                                    <button
                                                        onclick="cancelAppointment('{{ $appointment->id }}')"
                                                        class="bg-red-50 px-4 py-2 rounded-lg text-sm font-medium text-red-700 hover:bg-red-100 border border-red-200 flex items-center gap-2">
                                                        <i class="fas fa-times"></i>
                                                        إلغاء
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            لا توجد مواعيد
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6 flex justify-center items-center space-x-2 rtl:space-x-reverse">
                        @if ($appointments->lastPage() > 1)
                            {{-- Previous Page Link --}}
                            @if ($appointments->currentPage() > 1)
                                <a href="{{ $appointments->previousPageUrl() }}" class="px-4 py-2 rounded-lg bg-white text-gray-700 hover:bg-indigo-50 border border-gray-200 transition-colors flex items-center">
                                    <i class="fas fa-chevron-right ml-2"></i>
                                    السابق
                                </a>
                            @endif

                            {{-- Page Numbers --}}
                            @php
                                $start = max(1, $appointments->currentPage() - 2);
                                $end = min($appointments->lastPage(), $appointments->currentPage() + 2);
                            @endphp

                            @if ($start > 1)
                                <a href="{{ $appointments->url(1) }}" class="px-4 py-2 rounded-lg bg-white text-gray-700 hover:bg-indigo-50 border border-gray-200 transition-colors">
                                    1
                                </a>
                                @if ($start > 2)
                                    <span class="px-4 py-2 rounded-lg bg-white text-gray-700 border border-gray-200">...</span>
                                @endif
                            @endif

                            @for ($page = $start; $page <= $end; $page++)
                                @if ($page == $appointments->currentPage())
                                    <span class="px-4 py-2 rounded-lg bg-indigo-600 text-white border border-indigo-600">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $appointments->url($page) }}" class="px-4 py-2 rounded-lg bg-white text-gray-700 hover:bg-indigo-50 border border-gray-200 transition-colors">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endfor

                            @if ($end < $appointments->lastPage())
                                @if ($end < $appointments->lastPage() - 1)
                                    <span class="px-4 py-2 rounded-lg bg-white text-gray-700 border border-gray-200">...</span>
                                @endif
                                <a href="{{ $appointments->url($appointments->lastPage()) }}" class="px-4 py-2 rounded-lg bg-white text-gray-700 hover:bg-indigo-50 border border-gray-200 transition-colors">
                                    {{ $appointments->lastPage() }}
                                </a>
                            @endif

                            {{-- Next Page Link --}}
                            @if ($appointments->currentPage() < $appointments->lastPage())
                                <a href="{{ $appointments->nextPageUrl() }}" class="px-4 py-2 rounded-lg bg-white text-gray-700 hover:bg-indigo-50 border border-gray-200 transition-colors flex items-center">
                                    التالي
                                    <i class="fas fa-chevron-left mr-2"></i>
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Filter functionality
        document.getElementById('status-filter').addEventListener('change', function() {
            const status = this.value;
            window.location.href = `{{ route('appointments.index') }}${status ? '?status=' + status : ''}`;
        });

        async function rateDoctor(doctorId, appointmentId) {
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
                    const url = "{{ route('appointments.rate', ':id') }}".replace(':id', appointmentId);

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
</x-app-layout>
