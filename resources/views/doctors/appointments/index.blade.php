@extends('layouts.app')

@section('content')
<div class="py-2">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">إدارة المواعيد</h2>
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
                                    المريض
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
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $appointment->user->name }}
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
                                            @if($appointment->status === 'pending')
                                                <button
                                                    onclick="updateStatus('{{ $appointment->id }}', 'confirmed')"
                                                    class="px-3 py-1 bg-green-100 text-green-700 rounded-full hover:bg-green-200 transition-colors">
                                                    تأكيد
                                                </button>
                                                <button
                                                    onclick="updateStatus('{{ $appointment->id }}', 'cancelled')"
                                                    class="px-3 py-1 bg-red-100 text-red-700 rounded-full hover:bg-red-200 transition-colors">
                                                    إلغاء
                                                </button>
                                            @endif
                                            @if($appointment->status === 'confirmed')
                                                <button
                                                    onclick="updateStatus('{{ $appointment->id }}', 'completed')"
                                                    class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors">
                                                    إكمال
                                                </button>
                                            @endif
                                            <a href="{{ route('doctor.appointments.show', $appointment) }}"
                                            class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full hover:bg-indigo-200 transition-colors">
                                                عرض
                                            </a>
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
@endsection

@push('scripts')
<script>
async function updateStatus(appointmentId, status) {
    // First, show confirmation dialog
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
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
    });

    if (!result.isConfirmed) {
        return;
    }

    // Show loading state
    Swal.fire({
        title: 'جاري التحديث...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
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

// Filter functionality
document.getElementById('status-filter')?.addEventListener('change', function() {
    const status = this.value;
    window.location.href = `{{ route('doctor.appointments') }}${status ? '?status=' + status : ''}`;
});
</script>
@endpush
