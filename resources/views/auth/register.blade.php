<x-guest-layout>
    <div class="fixed inset-0 flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 overflow-hidden translate-y-10" style="font-family: 'Cairo', sans-serif;">
        <div class="w-full max-w-4xl mx-auto bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <!-- Left Side - Decorative Section -->
                <div class="hidden md:flex flex-col items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600 p-8 text-white">
                    <div class="w-48 h-48 bg-white/10 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-user-plus text-5xl text-white/80"></i>
                    </div>
                    <h2 class="text-3xl font-bold mb-3 text-center">إنشاء حساب جديد</h2>
                    <p class="text-center text-white/70 text-base max-w-md">
                        انضم إلينا واستمتع بتجربة مميزة
                    </p>
                </div>

                <!-- Right Side - Registration Form -->
                <div class="p-8 flex items-center justify-center">
                    <div class="w-full max-w-md">
                        <h2 class="text-2xl font-bold text-gray-900 text-center mb-6">
                            تسجيل حساب جديد
                        </h2>

                        <form method="POST" action="{{ route('register') }}" class="space-y-4">
                            @csrf

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-1">
                                    الاسم الكامل
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input
                                        id="name"
                                        type="text"
                                        name="name"
                                        :value="old('name')"
                                        required
                                        autofocus
                                        autocomplete="name"
                                        class="w-full pl-10 pr-4 py-2 bg-gray-100 rounded-xl border-2 border-transparent focus:border-indigo-500 focus:ring-0"
                                        placeholder="أدخل اسمك الكامل"
                                    />
                                </div>
                                @error('name')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-sm font-bold text-gray-700 mb-1">
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
                                        :value="old('email')"
                                        required
                                        autocomplete="username"
                                        class="w-full pl-10 pr-4 py-2 bg-gray-100 rounded-xl border-2 border-transparent focus:border-indigo-500 focus:ring-0"
                                        placeholder="أدخل بريدك الإلكتروني"
                                    />
                                </div>
                                @error('email')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-bold text-gray-700 mb-1">
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
                                        required
                                        autocomplete="new-password"
                                        class="w-full pl-10 pr-12 py-2 bg-gray-100 rounded-xl border-2 border-transparent focus:border-indigo-500 focus:ring-0"
                                        placeholder="أدخل كلمة المرور"
                                    />
                                    <button
                                        type="button"
                                        onclick="togglePassword('password')"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"
                                    >
                                        <i class="fas fa-eye text-lg"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-1">
                                    تأكيد كلمة المرور
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input
                                        id="password_confirmation"
                                        type="password"
                                        name="password_confirmation"
                                        required
                                        autocomplete="new-password"
                                        class="w-full pl-10 pr-12 py-2 bg-gray-100 rounded-xl border-2 border-transparent focus:border-indigo-500 focus:ring-0"
                                        placeholder="أعد إدخال كلمة المرور"
                                    />
                                    <button
                                        type="button"
                                        onclick="togglePassword('password_confirmation')"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"
                                    >
                                        <i class="fas fa-eye text-lg"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-xl font-medium hover:opacity-90 transition-all mt-4"
                            >
                                إنشاء حساب
                            </button>

                            <!-- Login Link -->
                            <div class="text-center mt-3">
                                <p class="text-sm text-gray-600">
                                    لديك حساب بالفعل؟
                                    <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                        تسجيل الدخول
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        body, html {
            overflow: hidden;
            overscroll-behavior: none;
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>

    @push('scripts')
    <script>
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const icon = event.currentTarget.querySelector('i');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
    @endpush
</x-guest-layout>
