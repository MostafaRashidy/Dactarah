@php
use Illuminate\Support\Facades\Route;
@endphp

<nav class="bg-white/80 backdrop-blur-lg shadow-md fixed top-0 left-0 right-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center">
                    <span class="text-2xl font-bold gradient-text">دكترة</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden sm:flex items-center space-x-4 rtl:space-x-reverse">
                <a href="{{ route('doctors.index') }}" class="text-gray-700 hover:text-indigo-600 text-sm font-medium transition-colors duration-200 px-3 py-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-search ml-2"></i>
                    ابحث عن طبيب
                </a>

                <!-- Guest Navigation Links -->
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 text-sm font-medium transition-colors duration-200 px-3 py-2 rounded-lg hover:bg-gray-100">
                        تسجيل الدخول
                    </a>

                    <a href="{{ route('register') }}" class="text-gray-700 hover:text-indigo-600 text-sm font-medium transition-colors duration-200 px-3 py-2 rounded-lg hover:bg-gray-100">
                        حساب جديد
                    </a>

                    <a href="{{ route('doctors.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                        انضم كطبيب
                    </a>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="sm:hidden flex items-center">
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="text-gray-500 hover:text-indigo-600 focus:outline-none focus:text-indigo-600"
                >
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="sm:hidden"
    >
        <div class="px-2 pt-2 pb-3 space-y-1 bg-white/90 backdrop-blur-lg">
            <a href="{{ route('doctors.index') }}" class="text-gray-700 hover:bg-gray-100 hover:text-indigo-600 block px-3 py-2 rounded-md text-base font-medium">
                <i class="fas fa-user-md ml-2"></i>
                ابحث عن طبيب
            </a>

            <a href="{{ route('login') }}" class="text-gray-700 hover:bg-gray-100 hover:text-indigo-600 block px-3 py-2 rounded-md text-base font-medium">
                تسجيل الدخول
            </a>

            <a href="{{ route('register') }}" class="text-gray-700 hover:bg-gray-100 hover:text-indigo-600 block px-3 py-2 rounded-md text-base font-medium">
                حساب جديد
            </a>

            <a href="{{ route('doctors.create') }}" class="bg-indigo-50 text-indigo-700 block px-3 py-2 rounded-md text-base font-medium">
                انضم كطبيب
            </a>
        </div>
    </div>

    <style>
        .gradient-text {
            background: linear-gradient(45deg, #4F46E5, #06B6D4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</nav>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('mobileMenu', () => ({
            mobileMenuOpen: false
        }))
    })
</script>
