@extends('layouts.app')

@section('content')
<div class="py-2">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">لوحة تحكم الطبيب</h2>
                    <div class="flex items-center gap-4">
                        <span class="text-gray-500">
                            مرحباً، {{ $doctor->first_name }}
                        </span>
                        @php
                            $status = $doctor->status;
                            $statusClasses = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'active' => 'bg-green-100 text-green-800',
                                'inactive' => 'bg-gray-100 text-gray-800',
                                'rejected' => 'bg-red-100 text-red-800',
                            ][$status] ?? 'bg-gray-100 text-gray-800';
                            $statusText = [
                                'pending' => 'في انتظار المراجعة',
                                'active' => 'نشط',
                                'inactive' => 'غير نشط',
                                'rejected' => 'مرفوض',
                            ][$status] ?? 'غير معروف';
                        @endphp
                        <span class="px-3 py-1 rounded-full text-sm {{ $statusClasses }}">
                            {{ $statusText }}
                        </span>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-indigo-50 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="rounded-full p-3 bg-indigo-100">
                                <i class="fas fa-calendar-check text-indigo-600 text-xl"></i>
                            </div>
                            <div class="mr-4">
                                <div class="text-2xl font-bold text-indigo-600">{{ $todayAppointments }}</div>
                                <div class="text-sm text-gray-600">المواعيد اليوم</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="rounded-full p-3 bg-green-100">
                                <i class="fas fa-users text-green-600 text-xl"></i>
                            </div>
                            <div class="mr-4">
                                <div class="text-2xl font-bold text-green-600">{{ $totalPatients }}</div>
                                <div class="text-sm text-gray-600">إجمالي المرضى</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="rounded-full p-3 bg-blue-100">
                                <i class="fas fa-star text-blue-600 text-xl"></i>
                            </div>
                            <div class="mr-4">
                                <div class="text-2xl font-bold text-blue-600">{{ $totalRatings }}</div>
                                <div class="text-sm text-gray-600">التقييمات</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                    <a href="{{ route('doctor.appointments') }}" class="bg-white p-4 rounded-lg border border-gray-200 hover:border-indigo-500 transition-colors duration-200 group">
                        <div class="flex items-center">
                            <div class="rounded-full p-3 bg-indigo-50 group-hover:bg-indigo-100">
                                <i class="fas fa-calendar-plus text-indigo-600"></i>
                            </div>
                            <span class="mr-3 font-medium">إدارة المواعيد</span>
                        </div>
                    </a>

                    <a href="{{ route('doctor.profile.edit') }}" class="bg-white p-4 rounded-lg border border-gray-200 hover:border-indigo-500 transition-colors duration-200 group">
                        <div class="flex items-center">
                            <div class="rounded-full p-3 bg-indigo-50 group-hover:bg-indigo-100">
                                <i class="fas fa-user-edit text-indigo-600"></i>
                            </div>
                            <span class="mr-3 font-medium">تعديل الملف الشخصي</span>
                        </div>
                    </a>

                    <a href="{{ route('doctor.schedule')}}" class="bg-white p-4 rounded-lg border border-gray-200 hover:border-indigo-500 transition-colors duration-200 group">
                        <div class="flex items-center">
                            <div class="rounded-full p-3 bg-indigo-50 group-hover:bg-indigo-100">
                                <i class="fas fa-clock text-indigo-600"></i>
                            </div>
                            <span class="mr-3 font-medium">مواعيد العمل</span>
                        </div>
                    </a>
                </div>

                <!-- Upcoming Appointments -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold">المواعيد القادمة</h3>
                    </div>
                    <div class="p-4">
                        @if($upcomingAppointments->count() > 0)
                            <div class="max-h-[180px] overflow-y-auto"> {{-- Add this div with max height and overflow --}}
                                <div class="divide-y">
                                    @foreach($upcomingAppointments as $appointment)
                                        <div class="py-3 flex justify-between items-center">
                                            <div>
                                                <div class="font-medium">{{ $appointment->user->name }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ Carbon\Carbon::parse($appointment->date)->format('Y/m/d') }}
                                                    {{ Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}
                                                </div>
                                            </div>
                                            <div>
                                                <span class="px-3 py-1 rounded-full text-sm ml-6
                                                    {{ $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ $appointment->status === 'pending' ? 'في الانتظار' : 'مؤكد' }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4 text-gray-500">
                                لا توجد مواعيد قادمة
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
