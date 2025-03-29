@php
    use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="دكترة - المنصة الطبية الأولى للربط بين الأطباء والمرضى">
    <title>دكترة - منصة الأطباء الأولى</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Base Styles */
        body {
            font-family: 'Cairo', sans-serif;
        }

        /* Advanced Gradient Text */
        .gradient-text {
            background: linear-gradient(45deg, #4F46E5, #06B6D4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            display: inline-block;
        }

        .gradient-text::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(45deg, #4F46E5, #06B6D4);
            transform: scaleX(0);
            transition: transform 0.3s ease;
            transform-origin: right;
        }

        .gradient-text:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        /* Enhanced Hero Pattern */
        .hero-pattern {
            background-color: #ffffff;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%234F46E5' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            position: relative;
        }

        .hero-pattern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(79, 70, 229, 0.05) 0%, rgba(6, 182, 212, 0.05) 100%);
        }

        /* Enhanced Cards */
        .feature-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(79, 70, 229, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.1);
            border-color: rgba(79, 70, 229, 0.3);
        }

        /* Enhanced Navigation */
        .floating-nav {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.9);
            border-bottom: 1px solid rgba(79, 70, 229, 0.1);
        }

        /* Enhanced Buttons */
        .btn-primary {
            background: linear-gradient(45deg, #4F46E5, #06B6D4);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #06B6D4, #4F46E5);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .btn-primary:hover::before {
            opacity: 1;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);
        }

        /* Enhanced Stats Cards */
        .stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
            border: 1px solid rgba(79, 70, 229, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(79, 70, 229, 0.1);
        }

        /* Advanced Animations */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }

            100% {
                background-position: 1000px 0;
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-pulse-custom {
            animation: pulse 3s ease-in-out infinite;
        }

        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite linear;
        }

        /* Glass Effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(79, 70, 229, 0.1);
        }

        /* Image Effects */
        .image-glow {
            box-shadow: 0 0 30px rgba(79, 70, 229, 0.2);
        }

        .image-glow:hover {
            box-shadow: 0 0 40px rgba(79, 70, 229, 0.3);
        }

        /* Responsive Design Enhancements */
        @media (max-width: 768px) {
            .hero-pattern {
                background-size: 30px 30px;
            }

            .floating-nav {
                backdrop-filter: blur(5px);
            }

            .glass-card {
                backdrop-filter: blur(5px);
            }
        }

        /* Loading States */
        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        /* Scroll Animations */
        .scroll-fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .scroll-fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body class="antialiased bg-gray-50">
    <!-- Floating Navigation -->
    <nav class="floating-nav fixed w-full z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <span class="text-2xl font-bold gradient-text">دكترة</span>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Common Link for All Users -->
                    <a href="{{ route('doctors.index') }}"
                        class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-search ml-1"></i>
                        ابحث عن طبيب
                    </a>

                    @auth('admin')
                        <!-- Admin Navigation -->
                        <a href="{{ route('admin.dashboard') }}"
                            class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-dashboard ml-1"></i>
                            لوحة التحكم
                        </a>
                        <a href="{{ route('admin.doctors.index') }}"
                            class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-user-md ml-1"></i>
                            إدارة الأطباء
                        </a>
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-red-500 text-white hover:bg-red-600 px-4 py-2 rounded-lg text-sm font-medium">
                                تسجيل الخروج
                            </button>
                        </form>
                    @elseif(Auth::guard('doctor')->check())
                        <!-- Doctor Navigation -->
                        <a href="{{ route('doctor.dashboard') }}"
                            class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-dashboard ml-1"></i>
                            لوحة التحكم
                        </a>
                        <a href="{{ route('doctor.profile.edit') }}"
                            class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-user-circle ml-1"></i>
                            الملف الشخصي
                        </a>
                        <form method="POST" action="{{ route('doctor.logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-red-500 text-white hover:bg-red-600 px-4 py-2 rounded-lg text-sm font-medium">
                                تسجيل الخروج
                            </button>
                        </form>
                    @elseif(Auth::check())
                        <!-- Regular User Navigation -->
                        <a href="{{ route('dashboard') }}"
                            class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-dashboard ml-1"></i>
                            لوحة التحكم
                        </a>
                        <a href="{{ route('profile.edit') }}"
                            class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-user-circle ml-1"></i>
                            الملف الشخصي
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-red-500 text-white hover:bg-red-600 px-4 py-2 rounded-lg text-sm font-medium">
                                تسجيل الخروج
                            </button>
                        </form>
                    @else
                        <!-- Guest Navigation -->
                        <a href="{{ route('login') }}"
                            class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            تسجيل الدخول
                        </a>
                        <a href="{{ route('doctor.login') }}"
                            class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-user-md ml-1"></i>
                            دخول الأطباء
                        </a>
                        <a href="{{ route('register') }}"
                            class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-lg text-sm font-medium">
                            حساب جديد
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-white hero-pattern">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:w-full lg:pb-28 xl:pb-32">
                <main class="mt-8 mx-auto max-w-7xl px-4 sm:mt-16 sm:px-6 md:mt-28 lg:mt-20 lg:px-8">
                    <div class="flex flex-col lg:flex-row items-center justify-between">
                        <!-- Text Content -->
                        <div class="lg:w-1/2 text-center lg:text-right">
                            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                                <span class="block">انضم إلى شبكة</span>
                                <span class="block gradient-text mt-2">دكترة الطبية</span>
                            </h1>
                            <p class="mt-6 text-base text-gray-500 sm:text-lg max-w-xl leading-relaxed">
                                انضم إلى أكبر شبكة أطباء في المنطقة. نوفر لك منصة متكاملة لإدارة عيادتك والوصول إلى
                                المزيد من المرضى.
                            </p>
                            <div class="mt-8 flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                                <a href="{{ route('doctors.create') }}"
                                    class="btn-primary px-8 py-4 rounded-xl text-white font-bold text-lg inline-flex items-center justify-center">
                                    <i class="fas fa-user-md ml-2"></i>
                                    انضم كطبيب
                                </a>
                                <a href="{{ route('doctors.index') }}"
                                    class="px-8 py-4 rounded-xl text-indigo-700 bg-indigo-50 hover:bg-indigo-100 font-bold text-lg inline-flex items-center justify-center transition-colors duration-200">
                                    <i class="fas fa-search ml-2"></i>
                                    ابحث عن طبيب
                                </a>
                            </div>
                        </div>

                        <!-- Hero Image -->
                        <div class="lg:w-1/2 mt-10 lg:mt-0">
                            <img src="{{ asset('images/private/1.png') }}" alt="Doctor with patient"
                                class="rounded-2xl shadow-2xl transform hover:scale-105 transition-transform duration-300"
                                style="clip-path: polygon(0 0, 100% 0, 100% 85%, 85% 100%, 0 100%);">
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="relative -mt-16 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="stats-card p-6 rounded-2xl shadow-lg">
                    <div class="flex items-center">
                        <div class="rounded-full p-3 bg-indigo-100 text-indigo-600">
                            <i class="fas fa-user-md text-xl"></i>
                        </div>
                        <div class="mr-4">
                            <div class="text-2xl font-bold text-gray-900">+1000</div>
                            <div class="text-sm text-gray-500">طبيب مسجل</div>
                        </div>
                    </div>
                </div>

                <div class="stats-card p-6 rounded-2xl shadow-lg">
                    <div class="flex items-center">
                        <div class="rounded-full p-3 bg-cyan-100 text-cyan-600">
                            <i class="fas fa-calendar-check text-xl"></i>
                        </div>
                        <div class="mr-4">
                            <div class="text-2xl font-bold text-gray-900">+5000</div>
                            <div class="text-sm text-gray-500">موعد شهرياً</div>
                        </div>
                    </div>
                </div>

                <div class="stats-card p-6 rounded-2xl shadow-lg">
                    <div class="flex items-center">
                        <div class="rounded-full p-3 bg-green-100 text-green-600">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div class="mr-4">
                            <div class="text-2xl font-bold text-gray-900">+10000</div>
                            <div class="text-sm text-gray-500">مريض مسجل</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">المميزات</h2>
                <p class="mt-2 text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    لماذا تختار دكترة؟
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                    نقدم لك مجموعة من المميزات التي تساعدك في إدارة عيادتك بكفاءة عالية
                </p>
            </div>

            <div class="mt-20">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Feature Cards -->
                    <div class="feature-card relative p-6 bg-white rounded-2xl shadow-lg border border-gray-100">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center mb-4">
                            <i class="fas fa-globe text-xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">وصول أوسع</h3>
                        <p class="text-gray-500">اجعل عيادتك متاحة لعدد أكبر من المرضى من خلال منصتنا الرقمية.</p>
                    </div>

                    <div class="feature-card relative p-6 bg-white rounded-2xl shadow-lg border border-gray-100">
                        <div class="w-12 h-12 rounded-full bg-cyan-100 flex items-center justify-center mb-4">
                            <i class="fas fa-tasks text-xl text-cyan-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">إدارة سهلة</h3>
                        <p class="text-gray-500">أدر مواعيدك وعيادتك بكل سهولة من خلال لوحة تحكم متطورة.</p>
                    </div>

                    <div class="feature-card relative p-6 bg-white rounded-2xl shadow-lg border border-gray-100">
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mb-4">
                            <i class="fas fa-comments text-xl text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">تواصل فعال</h3>
                        <p class="text-gray-500">تواصل مع مرضاك بشكل فعال وسهل من خلال منصتنا.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-indigo-600">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                <span class="block">جاهز للبدء؟</span>
                <span class="block text-indigo-200">انضم إلينا اليوم وابدأ رحلتك المهنية.</span>
            </h2>
            <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                <div class="inline-flex rounded-md shadow">
                    <a href="{{ route('doctors.create') }}"
                        class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 md:py-4 md:text-lg md:px-10">
                        ابدأ الآن
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-2">
                    <span class="text-2xl font-bold text-white">دكترة</span>
                    <p class="mt-4 text-gray-400 text-sm">
                        منصة طبية متكاملة تهدف إلى ربط الأطباء بالمرضى وتسهيل إدارة العيادات الطبية.
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">
                        روابط سريعة
                    </h3>
                    <ul class="mt-4 space-y-4">
                        <li>
                            <a href="{{ route('about') }}" class="text-base text-gray-300 hover:text-white">
                                عن دكترة
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contact') }}" class="text-base text-gray-300 hover:text-white">
                                تواصل معنا
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="social-links-container">
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase mb-6">
                        تابعنا
                    </h3>
                    <div class="flex justify-start space-x-6 rtl:space-x-reverse">
                        <a href="#"
                            class="social-link text-gray-400 hover:text-white transform hover:scale-110 transition-all duration-300 p-3">
                            <i class="fab fa-facebook text-2xl"></i>
                        </a>
                        <a href="#"
                            class="social-link text-gray-400 hover:text-white transform hover:scale-110 transition-all duration-300 p-3">
                            <i class="fab fa-twitter text-2xl"></i>
                        </a>
                        <a href="#"
                            class="social-link text-gray-400 hover:text-white transform hover:scale-110 transition-all duration-300 p-3">
                            <i class="fab fa-instagram text-2xl"></i>
                        </a>
                    </div>
                </div>

                <!-- Add these styles to your existing style section -->
                <style>
                    .social-links-container {
                        padding: 1.5rem;
                        border-radius: 0.75rem;
                        transition: all 0.3s ease;
                    }

                    .social-link {
                        position: relative;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        width: 3rem;
                        height: 3rem;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.1);
                        transition: all 0.3s ease;
                    }

                    .social-link:hover {
                        background: rgba(255, 255, 255, 0.2);
                        transform: translateY(-3px);
                        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
                    }

                    .social-link::after {
                        content: '';
                        position: absolute;
                        width: 100%;
                        height: 100%;
                        border-radius: 50%;
                        border: 2px solid transparent;
                        transition: all 0.3s ease;
                    }

                    .social-link:hover::after {
                        border-color: rgba(255, 255, 255, 0.2);
                        transform: scale(1.1);
                    }

                    .social-link:hover .fa-facebook {
                        color: #1877f2;
                    }

                    .social-link:hover .fa-twitter {
                        color: #1da1f2;
                    }

                    .social-link:hover .fa-instagram {
                        color: #e4405f;
                    }
                </style>
                <div class="mt-8 border-t border-gray-700 pt-8 md:flex md:items-center md:justify-between">
                    <p class="mt-8 text-base text-gray-400 md:mt-0">
                        &copy; {{ date('Y') }} دكترة. جميع الحقوق محفوظة.
                    </p>
                </div>
            </div>
    </footer>
</body>

</html>
