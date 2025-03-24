<x-guest-layout>
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-gray-50 to-gray-100 overflow-hidden">
    <div class="mb-6">
        <a href="/" class="text-3xl font-bold gradient-text">
            دكترة
        </a>
    </div>

    <div class="w-full sm:max-w-md px-6 py-8 bg-white/90 backdrop-blur-lg shadow-2xl rounded-2xl border border-white/20">
        <h2 class="text-3xl font-bold text-center mb-8 text-gray-900">
            تسجيل دخول الأطباء
        </h2>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">عفواً! هناك خطأ ما.</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('doctor.login.submit') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block font-medium text-sm text-gray-700 mb-2">
                    البريد الإلكتروني
                </label>

                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="block w-full pl-10 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-0 transition-all"
                        required
                        autofocus
                    >
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block font-medium text-sm text-gray-700 mb-2">
                    كلمة المرور
                </label>

                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="block w-full pl-10 pr-12 py-3 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-0 transition-all"
                        required
                        autocomplete="current-password"
                    >
                    <button
                        type="button"
                        onclick="togglePassword()"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"
                    >
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input
                        id="remember_me"
                        type="checkbox"
                        name="remember"
                        class="rounded text-indigo-600 focus:ring-indigo-500"
                    >
                    <span class="mr-2 text-sm text-gray-600">تذكرني</span>
                </label>

                <a class="text-sm text-indigo-600 hover:text-indigo-900" href="{{ route('doctors.create') }}">
                    تسجيل كطبيب جديد
                </a>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 rounded-xl font-semibold hover:opacity-90 transition-all"
            >
                تسجيل الدخول
            </button>
        </form>
    </div>

    <style>
        body, html {
            overflow: hidden;
            overscroll-behavior: none;
        }

        .gradient-text {
            background: linear-gradient(45deg, #4F46E5, #06B6D4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const icon = event.currentTarget.querySelector('i');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</div>
</x-guest-layout>
