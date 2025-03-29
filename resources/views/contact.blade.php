@extends('layouts.app')

@section('content')
    <div class="relative overflow-hidden bg-white hero-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center py-24 space-y-8">
                <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl leading-tight">
                    <span class="block">تواصل</span>
                    <span class="block gradient-text animate-fade-in">معنا</span>
                </h1>
                <p class="max-w-3xl mx-auto text-xl text-gray-500 leading-relaxed">
                    نحن هنا للاستماع إليك. يسعدنا تلقي استفساراتك واقتراحاتك
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                <!-- Contact Form -->
                <div class="bg-gray-50 p-10 rounded-3xl shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-cyan-500"></div>

                    <div class="text-center mb-10 relative z-10">
                        <h2 class="text-2xl font-bold text-gray-900">أرسل لنا رسالة</h2>
                        <p class="text-gray-500 mt-2">نحن نستمع باهتمام</p>
                    </div>

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-8 relative z-10">
                        @csrf
                        <div class="group relative">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i
                                        class="fas fa-user text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
                                </span>
                                <input type="text" name="name" id="name" required
                                    class="w-full px-4 py-3 pr-10 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300
                    placeholder-gray-400 group-focus-within:border-indigo-500"
                                    placeholder="أدخل اسمك الكامل">
                            </div>
                        </div>

                        <div class="group relative">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد
                                الإلكتروني</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i
                                        class="fas fa-envelope text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
                                </span>
                                <input type="email" name="email" id="email" required
                                    class="w-full px-4 py-3 pr-10 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300
                    placeholder-gray-400 group-focus-within:border-indigo-500"
                                    placeholder="example@email.com">
                            </div>
                        </div>

                        <div class="group relative">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف
                                (اختياري)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i
                                        class="fas fa-phone text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
                                </span>
                                <input type="tel" name="phone" id="phone"
                                    class="w-full px-4 py-3 pr-10 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300
                    placeholder-gray-400 group-focus-within:border-indigo-500"
                                    placeholder="رقم الهاتف">
                            </div>
                        </div>

                        <div class="group relative">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">رسالتك</label>
                            <div class="relative">
                                <span class="absolute top-3 right-0 flex items-start pr-3 pointer-events-none">
                                    <i
                                        class="fas fa-comment text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
                                </span>
                                <textarea name="message" id="message" rows="5" required
                                    class="w-full px-4 py-3 pr-10 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300
                    placeholder-gray-400 group-focus-within:border-indigo-500"
                                    placeholder="اكتب رسالتك هنا..."></textarea>
                            </div>
                        </div>

                        <div>
                            <button type="submit"
                                class="w-full btn-primary px-6 py-4 rounded-xl text-white font-bold text-lg
                hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1
                relative overflow-hidden group">
                                <span
                                    class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-indigo-500 to-cyan-500
                    opacity-0 group-hover:opacity-20 transition-opacity duration-300"></span>
                                إرسال الرسالة
                            </button>
                        </div>
                    </form>

                </div>

                <!-- Contact Information -->
                <div class="space-y-10">
                    <div
                        class="bg-indigo-50 p-8 mb-12 rounded-3xl flex items-center space-x-6 rtl:space-x-reverse shadow-md hover:shadow-lg transition-all duration-300">
                        <div class="bg-indigo-100 text-indigo-600 p-5 rounded-2xl">
                            <i class="fas fa-map-marker-alt text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">العنوان</h3>
                            <p class="text-gray-600 text-lg">الأقصر، جمهورية مصر العربية</p>
                        </div>
                    </div>

                    <div
                        class="bg-cyan-50 p-8 mb-12 rounded-3xl flex items-center space-x-6 rtl:space-x-reverse shadow-md hover:shadow-lg transition-all duration-300">
                        <div class="bg-cyan-100 text-cyan-600 p-5 rounded-2xl">
                            <i class="fas fa-envelope text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">البريد الإلكتروني</h3>
                            <p class="text-gray-600 text-lg">support@dactarah.com</p>
                        </div>
                    </div>

                    <div
                        class="bg-green-50 p-8 mb-12 rounded-3xl flex items-center space-x-6 rtl:space-x-reverse shadow-md hover:shadow-lg transition-all duration-300">
                        <div class="bg-green-100 text-green-600 p-5 rounded-2xl">
                            <i class="fas fa-phone text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">رقم الهاتف</h3>
                            <p class="text-gray-600 text-lg">01234567890</p>
                        </div>
                    </div>

                    <!-- Social Media Links -->
                    <div class="bg-gray-100 p-8 mb-10 rounded-3xl">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">تابعنا على وسائل التواصل الاجتماعي
                        </h3>
                        <div class="flex justify-center space-x-8 rtl:space-x-reverse">
                            @php
                                $socialLinks = [
                                    [
                                        'icon' => 'fab fa-facebook',
                                        'color' => 'text-blue-600 hover:text-blue-700',
                                        'link' => '#',
                                    ],
                                    [
                                        'icon' => 'fab fa-twitter',
                                        'color' => 'text-cyan-500 hover:text-cyan-600',
                                        'link' => '#',
                                    ],
                                    [
                                        'icon' => 'fab fa-instagram',
                                        'color' => 'text-pink-600 hover:text-pink-700',
                                        'link' => '#',
                                    ],
                                    [
                                        'icon' => 'fab fa-linkedin',
                                        'color' => 'text-blue-700 hover:text-blue-800',
                                        'link' => '#',
                                    ],
                                ];
                            @endphp

                            @foreach ($socialLinks as $social)
                                <a href="{{ $social['link'] }}"
                                    class="text-4xl {{ $social['color'] }} transition-all duration-300 transform hover:scale-110">
                                    <i class="{{ $social['icon'] }}"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase mb-4">الأسئلة الشائعة</h2>
                <p class="text-3xl leading-tight font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    هل لديك سؤال؟
                </p>
                <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">
                    إليك بعض الأسئلة الأكثر شيوعًا التي قد تساعدك
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                @php
                    $faqs = [
                        [
                            'question' => 'كيف يمكنني التسجيل كطبيب؟',
                            'answer' =>
                                'يمكنك التسجيل كطبيب من خلال صفحة التسجيل الخاصة بالأطباء وتعبئة النموذج المطلوب.',
                        ],
                        [
                            'question' => 'هل الخدمة مجانية؟',
                            'answer' => 'نقدم باقات مختلفة للأطباء، بعضها مجاني وبعضها مدفوع حسب المميزات.',
                        ],
                        [
                            'question' => 'كيف أحجز موعد؟',
                            'answer' => 'يمكنك البحث عن الطبيب المناسب واختيار الموعد المتاح مباشرة من خلال المنصة.',
                        ],
                        [
                            'question' => 'هل معلوماتي آمنة؟',
                            'answer' => 'نحرص على أعلى معايير الأمن لحماية معلوماتك الشخصية والطبية.',
                        ],
                    ];
                @endphp

                @foreach ($faqs as $faq)
                    <div class="bg-white p-8 rounded-3xl shadow-md hover:shadow-xl transition-all duration-300 group">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 group-hover:text-indigo-600 transition-colors">
                            {{ $faq['question'] }}
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            {{ $faq['answer'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in {
                animation: fadeIn 1s ease-out;
            }
        </style>
    @endpush
@endsection
