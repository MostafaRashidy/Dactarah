@php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center ml-10">
                <a href="{{ url('/') }}" class="flex items-center">
                    <span class="text-2xl font-bold gradient-text">دكترة</span>
                </a>
            </div>
                <style>
                    .gradient-text {
                    background: linear-gradient(45deg, #4F46E5, #06B6D4);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                    }
                </style>

                <!-- Navigation Links -->
                <div class="hidden sm:flex items-center space-x-8 rtl:space-x-reverse mx-10">
                    @auth
                        <x-nav-link
                        :href="route('dashboard')"
                        :active="request()->routeIs('dashboard')"
                        class="px-4 py-2 text-base font-medium transition-colors duration-200 hover:text-indigo-600 {{ request()->routeIs('dashboard') ? 'border-b-2 border-indigo-600' : '' }}"
                    >
                        <i class="fas fa-chart-line ml-2"></i>
                        لوحة التحكم
                    </x-nav-link>
                    @endauth

                    <x-nav-link
                        :href="route('doctors.index')"
                        :active="request()->routeIs('doctors.index')"
                        class="px-4 py-2 text-base font-medium transition-colors duration-200 hover:text-indigo-600 {{ request()->routeIs('doctors.index') ? 'border-b-2 border-indigo-600' : '' }}"
                    >
                        <i class="fas fa-search ml-2"></i>
                        ابحث عن طبيب
                    </x-nav-link>

                    {{-- <x-nav-link
                        :href="route('appointments.index')"
                        :active="request()->routeIs('appointments.index')"
                        class="px-4 py-2 text-base font-medium transition-colors duration-200 hover:text-indigo-600 {{ request()->routeIs('appointments.index') ? 'border-b-2 border-indigo-600' : '' }}"
                    >
                        <i class="fas fa-calendar-check ml-2"></i>
                        مواعيدي
                    </x-nav-link>

                    <x-nav-link
                        :href="route('messages')"
                        :active="request()->routeIs('messages')"
                        class="px-4 py-2 text-base font-medium transition-colors duration-200 hover:text-indigo-600 {{ request()->routeIs('messages') ? 'border-b-2 border-indigo-600' : '' }}"
                    >
                        <i class="fas fa-envelope ml-2"></i>
                        الرسائل
                    </x-nav-link> --}}
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                الملف الشخصي
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    تسجيل الخروج
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <!-- Guest Navigation -->
                    <div class="flex items-center gap-6">
                        <a href="{{ route('login') }}"
                           class="text-gray-700 hover:text-indigo-600 text-sm font-medium">
                            تسجيل الدخول
                        </a>

                        <a href="{{ route('register') }}"
                           class="text-gray-700 hover:text-indigo-600 text-sm font-medium">
                            حساب جديد
                        </a>

                        <a href="{{ route('doctors.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                            انضم كطبيب
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
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
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                لوحة التحكم
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('doctors.index')" :active="request()->routeIs('doctors.index')">
                ابحث عن طبيب
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        الملف الشخصي
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            تسجيل الخروج
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <!-- Responsive Guest Navigation -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        تسجيل الدخول
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        حساب جديد
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('doctors.create')" class="bg-indigo-50">
                        انضم كطبيب
                    </x-responsive-nav-link>
                </div>
            </div>
        @endauth
    </div>
</nav>
