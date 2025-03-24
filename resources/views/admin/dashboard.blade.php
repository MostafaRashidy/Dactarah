<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة التحكم') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
    <!-- Total Users -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-3xl font-bold text-indigo-600">{{ $stats['total_users'] ?? 0 }}</div>
                <div class="text-gray-600">إجمالي المستخدمين</div>
            </div>
            <div class="text-indigo-500">
                <i class="fas fa-users text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Doctors -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-3xl font-bold text-indigo-600">{{ $stats['total_doctors'] ?? 0 }}</div>
                <div class="text-gray-600">إجمالي الأطباء</div>
            </div>
            <div class="text-indigo-500">
                <i class="fas fa-user-md text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Pending Doctors -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-3xl font-bold text-indigo-600">{{ $stats['pending_doctors'] ?? 0 }}</div>
                <div class="text-gray-600">طلبات الانضمام</div>
            </div>
            <div class="text-indigo-500">
                <i class="fas fa-clock text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Active Doctors -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-3xl font-bold text-indigo-600">{{ $stats['active_doctors'] ?? 0 }}</div>
                <div class="text-gray-600">الأطباء النشطين</div>
            </div>
            <div class="text-indigo-500">
                <i class="fas fa-check-circle text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Blocked Doctors -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-3xl font-bold text-red-600">{{ $stats['blocked_doctors'] ?? 0 }}</div>
                <div class="text-gray-600">الأطباء المحظورين</div>
            </div>
            <div class="text-red-500">
                <i class="fas fa-ban text-3xl"></i>
            </div>
        </div>
    </div>
</div>

            <!-- Pending Doctors Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center">
                            <h3 class="text-xl font-semibold text-gray-900">طلبات الانضمام الجديدة</h3>
                            <span class="mr-3 bg-indigo-100 text-indigo-800 text-sm font-medium px-3 py-1 rounded-full">
                                {{ count($pending_doctors ?? []) }} طلب
                            </span>
                        </div>
                    </div>

                    @if(count($pending_doctors ?? []) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="doctorsTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            الطبيب
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            التخصص
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            السعر
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            تاريخ الطلب
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            الإجراءات
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pending_doctors as $doctor)
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-12 w-12 relative">
                                                        <img class="h-12 w-12 rounded-full object-cover border-2 border-gray-200"
                                                             src="{{ $doctor->image ? asset($doctor->image) : asset('images/default-avatar.png') }}"
                                                             alt="{{ $doctor->full_name }}"
                                                             loading="lazy">
                                                        <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full bg-yellow-400 border-2 border-white"
                                                             title="في انتظار المراجعة"></div>
                                                    </div>
                                                    <div class="mr-4">
                                                        <div class="text-sm font-medium text-gray-900 hover:text-indigo-600">
                                                            <a href="{{ route('admin.doctors.show', $doctor) }}" class="hover:underline">
                                                                {{ $doctor->first_name }} {{ $doctor->last_name }}
                                                            </a>
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $doctor->email }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 flex items-center">
                                                            <svg class="h-4 w-4 text-gray-400 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                            </svg>
                                                            {{ $doctor->phone }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $doctor->specialty->name }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    <span class="font-semibold">{{ number_format($doctor->price, 2) }}</span>
                                                    <span class="text-gray-500">جنيه</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $doctor->created_at->format('Y/m/d') }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $doctor->created_at->diffForHumans() }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-left">
                                                <div class="flex justify-end space-x-2">
                                                    <a href="{{ route('admin.doctors.show', $doctor) }}"
                                                       class="bg-blue-100 text-blue-700 px-3 py-1 rounded-md text-sm hover:bg-blue-200 transition-colors duration-200 ml-2 flex items-center">
                                                        <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        عرض
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد طلبات انضمام جديدة</h3>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush
</x-admin-layout>
