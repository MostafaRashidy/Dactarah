@php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
@endphp


<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left Side -->
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold gradient-text">
                        دكترة
                    </a>
                </div>

                <!-- Primary Navigation -->
                <div class="hidden sm:flex sm:space-x-8 sm:mr-6">
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')"
                               class="flex items-center px-3 py-2 text-sm font-medium ml-5">
                        <i class="fas fa-home ml-2"></i>
                        الرئيسية
                    </x-nav-link>

                    <x-nav-link :href="route('admin.doctors.index')" :active="request()->routeIs('admin.doctors.*')"
                               class="flex items-center px-3 py-2 text-sm font-medium">
                        <i class="fas fa-user-md ml-2"></i>
                        الأطباء
                    </x-nav-link>

                    <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')"
                               class="flex items-center px-3 py-2 text-sm font-medium">
                        <i class="fas fa-users ml-2"></i>
                        المستخدمين
                    </x-nav-link>

                    <x-nav-link :href="route('doctors.index')" :active="request()->routeIs('doctors.index')"
                        class="flex items-center px-3 py-2 text-sm font-medium">
                        <i class="fas fa-search ml-2"></i>
                        ابحث عن طبيب
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex sm:items-center sm:space-x-3">
                <!-- Pending Requests -->
                <x-nav-link :href="route('admin.doctors.pending')"
                           :active="request()->routeIs('admin.doctors.pending')"
                           class="relative flex items-center px-3 py-2 text-sm font-medium rounded-md ml-4
                                  {{ request()->routeIs('admin.doctors.pending') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-user-clock ml-2"></i>
                    <span>طلبات الانضمام</span>
                    @if($pendingDoctorsCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                            {{ $pendingDoctorsCount }}
                        </span>
                    @endif
                </x-nav-link>

                <!-- Settings Dropdown -->
                <div class="relative mr-3" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none">
                        <span>{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</span>
                        <i class="fas fa-chevron-down mr-2 text-xs"></i>
                    </button>

                    <div x-show="open"
                         @click.away="open = false"
                         class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                        <a href="{{ route('admin.profile.edit') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user ml-2"></i>
                            الملف الشخصي
                        </a>

                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit"
                                    class="block w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt ml-2"></i>
                                تسجيل الخروج
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }"
                              class="inline-flex"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }"
                              class="hidden"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div :class="{'block': open, 'hidden': !open}" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('admin.dashboard')"
                                 :active="request()->routeIs('admin.dashboard')"
                                 class="flex items-center">
                <i class="fas fa-home ml-2"></i>
                الرئيسية
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.doctors.index')"
                                 :active="request()->routeIs('admin.doctors.*')"
                                 class="flex items-center">
                <i class="fas fa-user-md ml-2"></i>
                الأطباء
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.users.index')"
                                 :active="request()->routeIs('admin.users.*')"
                                 class="flex items-center">
                <i class="fas fa-users ml-2"></i>
                المستخدمين
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.doctors.pending')"
                                 :active="request()->routeIs('admin.doctors.pending')"
                                 class="flex items-center">
                <i class="fas fa-user-clock ml-2"></i>
                طلبات الانضمام
                @if($pendingDoctorsCount > 0)
                    <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 mr-2">
                        {{ $pendingDoctorsCount }}
                    </span>
                @endif
            </x-responsive-nav-link>
        </div>

        <!-- Mobile Settings -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 py-3">
                <div class="text-base font-medium text-gray-800">
                    {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('admin.profile.edit')"
                                     class="flex items-center">
                    <i class="fas fa-user ml-2"></i>
                    الملف الشخصي
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('admin.logout')"
                                         onclick="event.preventDefault(); this.closest('form').submit();"
                                         class="flex items-center text-red-600">
                        <i class="fas fa-sign-out-alt ml-2"></i>
                        تسجيل الخروج
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    nav {
        font-family: 'Cairo', sans-serif;
    }

    .gradient-text {
        font-family: 'Cairo', sans-serif;
        font-weight: 700;
        background: linear-gradient(45deg, #4F46E5, #06B6D4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Active link styles */
    .nav-link-active {
        color: #4F46E5;
        background-color: #EEF2FF;
    }

    /* Hover effects */
    .nav-link:hover {
        background-color: #F3F4F6;
    }

    /* Dropdown shadow */
    .dropdown-shadow {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
</style>
