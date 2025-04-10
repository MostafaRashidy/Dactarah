@php
    $user = auth('doctor')->user();
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold gradient-text">
                        دكترة
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:mr-10 sm:flex">
                    <div class="flex items-center">
                        <x-nav-link :href="route('doctor.dashboard')" :active="request()->routeIs('doctor.dashboard')"
                                class="ml-8">
                            <div class="flex items-center">
                                <i class="fas fa-chart-line ml-2"></i>
                                لوحة التحكم
                            </div>
                        </x-nav-link>

                        <x-nav-link href="{{ route('doctor.appointments') }}" :active="request()->routeIs('doctor.appointments')"
                                class="ml-8 relative"> <!-- Added relative positioning -->
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt ml-2"></i>
                                المواعيد
                            </div>
                            @if($pendingAppointmentsCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                    {{ $pendingAppointmentsCount }}
                                </span>
                            @endif
                        </x-nav-link>

                        <x-nav-link href="{{ route('doctor.schedule')}}" :active="request()->routeIs('doctor.schedule')"
                            class="ml-8"> <!-- Add margin to create space -->
                            <div class="flex items-center">
                                <i class="fas fa-clock ml-2"></i>
                                مواعيد العمل
                            </div>
                        </x-nav-link>

                        <x-nav-link href="{{ route('doctors.index')}}" :active="request()->routeIs('doctor.patients.*')"
                                class="ml-8"> <!-- Add margin to create space -->
                            <div class="flex items-center">
                                <i class="fas fa-search ml-2"></i>
                                ابحث عن طبيب
                            </div>
                        </x-nav-link>
                    </div>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="hidden sm:flex sm:items-center sm:mr-6">
                @php
                    $statusClasses = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'active' => 'bg-green-100 text-green-800',
                        'inactive' => 'bg-gray-100 text-gray-800',
                        'rejected' => 'bg-red-100 text-red-800',
                    ][$user->status] ?? 'bg-gray-100 text-gray-800';

                    $statusText = [
                        'pending' => 'في انتظار المراجعة',
                        'active' => 'نشط',
                        'inactive' => 'غير نشط',
                        'rejected' => 'مرفوض',
                    ][$user->status] ?? 'غير معروف';
                @endphp
                <span class="px-3 py-1 rounded-full text-sm {{ $statusClasses }}">
                    {{ $statusText }}
                </span>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:mr-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out">
                            <div class="flex items-center">
                                @if($user->image)
                                    <img src="{{ asset($user->image) }}" alt="{{ $user->full_name }}"
                                         class="w-8 h-8 rounded-full object-cover ml-2">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center ml-2">
                                        <i class="fas fa-user-md text-indigo-600"></i>
                                    </div>
                                @endif
                                <div>{{ $user->full_name }}</div>
                            </div>

                            <div class="mr-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('doctor.profile.edit')">
                            <i class="fas fa-user-edit ml-2"></i>
                            الملف الشخصي
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('doctor.logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('doctor.logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <i class="fas fa-sign-out-alt ml-2"></i>
                                تسجيل الخروج
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('doctor.dashboard')" :active="request()->routeIs('doctor.dashboard')">
                <i class="fas fa-chart-line ml-2"></i>
                لوحة التحكم
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('doctor.appointments') }}" :active="request()->routeIs('doctor.appointments')">
                <i class="fas fa-calendar-alt ml-2"></i>
                المواعيد
            </x-responsive-nav-link>

            <x-responsive-nav-link href="#" :active="request()->routeIs('doctor.patients.*')">
                <i class="fas fa-users ml-2"></i>
                المرضى
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('doctor.schedule')}}" :active="request()->routeIs('doctor.schedule')">
                <i class="fas fa-clock ml-2"></i>
                مواعيد العمل
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">
                    {{ $user->full_name }}
                </div>
                <div class="font-medium text-sm text-gray-500">
                    {{ $user->email }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('doctor.profile.edit')">
                    <i class="fas fa-user-edit ml-2"></i>
                    الملف الشخصي
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('doctor.logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('doctor.logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt ml-2"></i>
                        تسجيل الخروج
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
