@extends('layouts.app')

@section('content')
    <div class="relative overflow-hidden bg-white hero-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center py-20 space-y-6">
                <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl leading-tight">
                    <span class="block">عن</span>
                    <span class="block gradient-text animate-fade-in">دكترة</span>
                </h1>
                <p class="mt-4 max-w-3xl mx-auto text-xl text-gray-500 leading-relaxed">
                    منصة متكاملة لربط الأطباء بالمرضى وتسهيل إدارة العيادات الطبية
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center space-y-6">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">رؤيتنا</h2>
                <p class="text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    نحن نؤمن بتحسين الرعاية الصحية
                </p>
                <p class="max-w-2xl text-xl text-gray-500 lg:mx-auto leading-relaxed">
                    دكترة تهدف إلى تبسيط التواصل بين الأطباء والمرضى وتوفير تجربة صحية متكاملة
                </p>
            </div>

            <div class="mt-16 grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="feature-card p-8 rounded-3xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-heart text-2xl text-indigo-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">مهمتنا</h3>
                    <p class="text-gray-600 text-center leading-relaxed">
                        تمكين الأطباء من تقديم أفضل رعاية صحية من خلال توفير أدوات إدارية متطورة وسهلة الاستخدام
                    </p>
                </div>

                <div class="feature-card p-8 rounded-3xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-globe text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">قيمنا</h3>
                    <p class="text-gray-600 text-center leading-relaxed">
                        الشفافية، الابتكار، سهولة الاستخدام، والالتزام بتحسين تجربة الرعاية الصحية
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center space-y-6">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">كيف نعمل</h2>
                <p class="text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    منصة شاملة للرعاية الصحية
                </p>
            </div>

            <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-10">
                @php
                    $services = [
                        [
                            'icon' => 'fa-user-md',
                            'bg' => 'bg-indigo-100',
                            'text' => 'text-indigo-600',
                            'title' => 'للأطباء',
                            'description' => 'إدارة المواعيد، جدولة المرضى، وتتبع السجلات الطبية بسهولة'
                        ],
                        [
                            'icon' => 'fa-users',
                            'bg' => 'bg-cyan-100',
                            'text' => 'text-cyan-600',
                            'title' => 'للمرضى',
                            'description' => 'حجز المواعيد بسهولة، استعراض الأطباء، والوصول السريع للرعاية الصحية'
                        ],
                        [
                            'icon' => 'fa-laptop-medical',
                            'bg' => 'bg-green-100',
                            'text' => 'text-green-600',
                            'title' => 'التكنولوجيا',
                            'description' => 'منصة متطورة تضمن سهولة الاستخدام والأمان للجميع'
                        ]
                    ]
                @endphp

                @foreach($services as $service)
                    <div class="stats-card p-8 rounded-3xl shadow-lg text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="rounded-full p-4 {{ $service['bg'] }} {{ $service['text'] }} inline-block mb-6">
                            <i class="fas {{ $service['icon'] }} text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $service['title'] }}</h3>
                        <p class="text-gray-600 leading-relaxed">
                            {{ $service['description'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-indigo-600">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
            <div class="text-center md:text-right">
                <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                    <span class="block">انضم إلينا اليوم</span>
                    <span class="block text-indigo-200 mt-2">واستفد من مميزات دكترة</span>
                </h2>
            </div>
            <div>
                <a href="{{ route('doctors.create') }}"
                   class="inline-flex items-center justify-center px-10 py-4 border border-transparent text-base font-bold rounded-lg text-indigo-600 bg-white hover:bg-indigo-50 transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl">
                    انضم الآن
                </a>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 1s ease-out;
        }
    </style>
    @endpush
@endsection
