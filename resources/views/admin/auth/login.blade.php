<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 overflow-hidden relative pb-16">
        <!-- Advanced Background Particles -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute top-[-5%] left-[-5%] w-[600px] h-[600px] bg-blue-300/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-[-5%] right-[-5%] w-[600px] h-[600px] bg-purple-300/10 rounded-full blur-3xl animate-pulse"></div>
        </div>

        <!-- Enhanced Login Card -->
        <div class="relative w-full max-w-5xl p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 bg-white/90 backdrop-blur-xl rounded-[40px] shadow-2xl border border-white/20 overflow-hidden">
                <!-- Left Side - Dynamic Visual Section -->
                <div class="hidden md:flex flex-col items-center justify-center text-center bg-gradient-to-br from-blue-500 to-purple-600 p-12 relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="w-48 h-48 bg-white/20 rounded-full flex items-center justify-center mb-6 mx-auto shadow-lg">
                            <i class="fas fa-user-shield text-5xl text-white/80"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-white mb-4">
                            مرحبًا بعودتك
                        </h3>
                        <p class="text-white/70 text-base max-w-md mx-auto">
                            سجل دخولك للوصول إلى لوحة التحكم الخاصة بك
                        </p>
                    </div>
                </div>

                <!-- Right Side - Login Form -->
                <div class="p-12 flex flex-col justify-center">
                    <h2 class="text-3xl font-bold text-gray-900 text-center mb-8">
                        تسجيل الدخول
                    </h2>

                    <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                        @csrf

                        <!-- Email Input -->
                        <div class="relative group">
                            <label class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400 group-focus-within:text-blue-500"></i>
                                </div>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="w-full pl-12 pr-4 py-3 bg-gray-100 rounded-xl border-2 border-transparent
                                           focus:border-blue-500 focus:ring-0"
                                    placeholder="أدخل بريدك الإلكتروني"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div class="relative group">
                            <label class="block text-gray-700 text-sm font-bold mb-2">كلمة المرور</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <i class="fas fa-lock text-gray-400 group-focus-within:text-blue-500"></i>
                                </div>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="w-full pl-12 pr-12 py-3 bg-gray-100 rounded-xl border-2 border-transparent
                                           focus:border-blue-500 focus:ring-0"
                                    placeholder="أدخل كلمة المرور"
                                    required
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

                        <!-- Remember Me and Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="w-4 h-4 rounded text-blue-500 focus:ring-blue-300"
                                >
                                <span class="text-sm text-gray-600">تذكرني</span>
                            </label>
                            <a href="#" class="text-sm text-blue-500 hover:underline">
                                نسيت كلمة المرور؟
                            </a>
                        </div>

                        <!-- Submit Button with Gradient -->
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-xl font-medium
                                   shadow-md hover:shadow-xl"
                        >
                            تسجيل الدخول
                        </button>
                    </form>

                    <!-- Security Footer -->
                    <div class="mt-6 text-center">
                        <div class="flex items-center justify-center text-sm text-gray-500 space-x-2">
                            <i class="fas fa-shield-alt text-green-500"></i>
                            <span>تسجيل دخول آمن ومحمي</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        body, html {
            overflow: hidden;
            overscroll-behavior: none;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .animate-pulse {
            animation: pulse 5s infinite;
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
</x-guest-layout>
