@extends('layouts.app')

@push('styles')
<style>
    /* Base Styles */
    .bg-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .search-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .doctor-card {
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .doctor-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .image-gradient {
        background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.7) 100%);
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 50%;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .availability-badge {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        border-radius: 9999px;
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        color: #4F46E5;
        display: flex;
        align-items: center;
        gap: 0.375rem;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section with Search -->
    <div class="search-container relative overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-10"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative">
            <div class="text-center max-w-3xl mx-auto mb-6">
                <h1 class="text-5xl font-bold text-gray-900 mb-6 leading-tight">
                    ابحث عن طبيبك المتخصص
                </h1>
                <p class="text-xl text-gray-600 leading-relaxed">
                    اختر من بين أفضل الأطباء المتخصصين في مصر
                </p>
            </div>

            <!-- Search Form -->
<form action="{{ route('doctors.index') }}" method="GET" class="w-full">
    <div class="max-w-6xl mx-auto bg-white p-6 md:p-8 rounded-3xl shadow-2xl backdrop-blur-lg bg-opacity-95 border border-gray-100 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 md:gap-6">
            <!-- Name Search -->
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user-md ml-2 text-indigo-500"></i>
                    اسم الطبيب
                </label>
                <div class="relative">
                    <input type="text" name="name" value="{{ request('name') }}"
                           class="block w-full px-4 py-3 rounded-xl border border-gray-200
                                  bg-gray-50/50 shadow-sm
                                  focus:border-indigo-500 focus:ring-2 focus:ring-indigo-300/50
                                  transition-all duration-300
                                  hover:bg-indigo-50/30"
                           placeholder="ابحث باسم الطبيب">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Specialty Filter -->
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-stethoscope ml-2 text-indigo-500"></i>
                    التخصص
                </label>
                <div class="relative">
                    <select name="specialty"
                            class="block w-full px-4 py-3 rounded-xl border border-gray-200
                                bg-gray-50/50 shadow-sm
                                focus:border-indigo-500 focus:ring-2 focus:ring-indigo-300/50
                                transition-all duration-300
                                hover:bg-indigo-50/30
                                appearance-none
                                pr-10"
                            style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"12\" height=\"12\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-chevron-down\"><polyline points=\"6 9 12 15 18 9\"></polyline></svg>');
                                background-repeat: no-repeat;
                                background-position: left 0.75rem center;
                                background-size: 1rem;"
                    >
                        <option value="">كل التخصصات</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}"
                                    {{ request('specialty') == $specialty->id ? 'selected' : '' }}>
                                {{ $specialty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Governorate Filter -->
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-map-marker-alt ml-2 text-indigo-500"></i>
                    المحافظة
                </label>
                <div class="relative">
                    <select name="governorate"
                            class="block w-full px-4 py-3 rounded-xl border border-gray-200
                                bg-gray-50/50 shadow-sm
                                focus:border-indigo-500 focus:ring-2 focus:ring-indigo-300/50
                                transition-all duration-300
                                hover:bg-indigo-50/30
                                appearance-none
                                pr-10"
                            style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"12\" height=\"12\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-chevron-down\"><polyline points=\"6 9 12 15 18 9\"></polyline></svg>');
                                background-repeat: no-repeat;
                                background-position: left 0.75rem center;
                                background-size: 1rem;"
                    >
                        <option value="">كل المحافظات</option>
                        @foreach($governorates as $governorate)
                            <option value="{{ $governorate->id }}"
                                    {{ request('governorate') == $governorate->id ? 'selected' : '' }}>
                                {{ $governorate->name_ar }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Search Button -->
            <div class="md:col-span-3 flex items-end">
                <button type="submit"
                        class="w-full bg-indigo-600 text-white px-4 py-3.5 rounded-xl
                               hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500/50
                               transition-colors duration-200 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>بحث</span>
                </button>
            </div>

            <!-- Quick Filters -->
            <div class="md:col-span-12 mt-4">
                <div class="flex flex-wrap items-center gap-4">
                    <!-- Available Today -->
                    <label class="inline-flex items-center bg-gray-50 px-4 py-2 rounded-full cursor-pointer
                                  hover:bg-indigo-50 transition-colors duration-300">
                        <input type="checkbox" name="available_today" value="1"
                               {{ request('available_today') ? 'checked' : '' }}
                               class="form-checkbox h-4 w-4 text-indigo-600 ml-2
                                      focus:ring-indigo-500 border-gray-300 rounded">
                        <span class="text-sm text-gray-700">متاح اليوم</span>
                    </label>

                    <!-- Highly Rated -->
                    <label class="inline-flex items-center bg-gray-50 px-4 py-2 rounded-full cursor-pointer
                                  hover:bg-indigo-50 transition-colors duration-300">
                        <input type="checkbox" name="highly_rated" value="1"
                               {{ request('highly_rated') ? 'checked' : '' }}
                               class="form-checkbox h-4 w-4 text-indigo-600 ml-2
                                      focus:ring-indigo-500 border-gray-300 rounded">
                        <span class="text-sm text-gray-700">الأعلى تقييماً</span>
                    </label>

                    <!-- Sort Dropdown -->
                    <div class="relative">
                        <select onchange="sortDoctors(this.value)" name="sort"
                                class="block w-full px-4 py-2 rounded-full border border-gray-200
                                    bg-gray-50 text-sm
                                    focus:border-indigo-500 focus:ring-2 focus:ring-indigo-300/50
                                    appearance-none
                                    pr-8"
                                style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"12\" height=\"12\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-chevron-down\"><polyline points=\"6 9 12 15 18 9\"></polyline></svg>');
                                    background-repeat: no-repeat;
                                    background-position: left 0.5rem center;
                                    background-size: 1rem;"
                        >
                            <option value="">الترتيب</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>
                                الأعلى تقييماً
                            </option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                                السعر: من الأقل
                            </option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                                السعر: من الأعلى
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

    <!-- Results Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-0">
        <!-- Results Header -->
        <div class="flex items-center justify-between mb-8 mr-4">
            <h2 class="text-2xl font-bold text-gray-900">
                {{ $doctors->total() }} طبيب متاح
                @if(request()->anyFilled(['name', 'specialty']))
                    <span class="text-base font-normal text-gray-500">
                        (نتائج البحث)
                    </span>
                @endif
            </h2>

            {{-- <select onchange="sortDoctors(this.value)"
                class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>الأعلى تقييماً</option>
                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>السعر: من الأقل</option>
                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>السعر: من الأعلى</option>
                <option value="" {{ !request('sort') ? 'selected' : '' }}>الأحدث</option>
            </select> --}}
        </div>

       <!-- Active Filters -->
@if(request()->anyFilled(['name', 'specialty', 'available_today', 'highly_rated', 'sort']))
    <div class="mb-6 flex flex-wrap items-center gap-2">
        <span class="text-sm text-gray-600">الفلاتر النشطة:</span>

        @if(request('name'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800">
                <span>البحث: {{ request('name') }}</span>
                <button type="button"
                        onclick="removeFilter('name')"
                        class="mr-2 text-indigo-600 hover:text-indigo-800 focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </span>
        @endif

        @if(request('specialty'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800">
                <span>التخصص: {{ $specialties->find(request('specialty'))->name }}</span>
                <button type="button"
                        onclick="removeFilter('specialty')"
                        class="mr-2 text-indigo-600 hover:text-indigo-800 focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </span>
        @endif

        @if(request('available_today'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800">
                <span>متاح اليوم</span>
                <button type="button"
                        onclick="removeFilter('available_today')"
                        class="mr-2 text-indigo-600 hover:text-indigo-800 focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </span>
        @endif

        @if(request('highly_rated'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800">
                <span>الأعلى تقييماً</span>
                <button type="button"
                        onclick="removeFilter('highly_rated')"
                        class="mr-2 text-indigo-600 hover:text-indigo-800 focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </span>
        @endif

        @if(request('governorate'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800">
                <span>المحافظة: {{ $governorates->find(request('governorate'))->name_ar }}</span>
                <button type="button"
                        onclick="removeFilter('governorate')"
                        class="mr-2 text-indigo-600 hover:text-indigo-800 focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </span>
        @endif

        <button type="button"
                onclick="clearFilters()"
                class="text-sm text-indigo-600 hover:text-indigo-800 font-medium focus:outline-none">
            مسح الكل
        </button>
    </div>
@endif

<!-- Doctors Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($doctors as $doctor)
        <div class="doctor-card transition-all duration-300 ease-in-out transform hover:scale-105 hover:z-10
                    bg-white rounded-2xl shadow-lg overflow-hidden
                    hover:shadow-2xl border border-gray-100
                    relative">
            <div class="relative overflow-hidden">
                <img src="{{ $doctor->image ? asset($doctor->image) : asset('images/default-avatar.png') }}"
                     alt="{{ $doctor->full_name }}"
                     class="w-full h-56 object-cover transition-transform duration-300
                            group-hover:scale-110 origin-center"
                >

                <div class="absolute top-4 left-4 flex space-x-2">
                    <span class="bg-white/90 px-3 py-1 rounded-full text-sm text-indigo-600 font-medium">
                        {{ $doctor->specialty->name }}
                    </span>
                    @if($doctor->is_available)
                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm flex items-center gap-1">
                            <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                            متاح
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-6 transition-all duration-300 group-hover:p-8">

                <div class="flex items-center text-sm text-gray-600 mt-2">
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 616 0z" />
                    </svg>
                    {{ $doctor->governorate?->name_ar ?? 'غير محدد' }}
                </div>

                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 transition-all duration-300
                                   group-hover:text-2xl group-hover:text-indigo-700">
                            {{ $doctor->full_name }}
                        </h3>
                        <div class="flex items-center mt-1">
                            <div class="flex items-center ml-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $doctor->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                                <span class="text-sm text-gray-600 mr-2">
                                    ({{ $doctor->ratings_count }})
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="text-indigo-600 font-bold text-2xl transition-all duration-300
                                group-hover:text-3xl group-hover:text-indigo-800">
                        {{ $doctor->price }} <span class="text-sm">جنيه</span>
                    </div>
                </div>


                <div class="grid grid-cols-2 gap-3 mb-4 opacity-100 group-hover:opacity-100
                            transition-all duration-300">
                    <div class="bg-gray-100 rounded-lg p-3 text-center transition-all duration-300
                                group-hover:bg-indigo-50 group-hover:scale-105">
                        <div class="text-indigo-600 font-bold">{{ $doctor->total_patients }}+</div>
                        <div class="text-xs text-gray-600">المرضى</div>
                    </div>
                    <div class="bg-gray-100 rounded-lg p-3 text-center transition-all duration-300
                                group-hover:bg-indigo-50 group-hover:scale-105">
                        <div class="text-indigo-600 font-bold">{{ $doctor->rating * 20 }}%</div>
                        <div class="text-xs text-gray-600">التقييم</div>
                    </div>
                </div>

                <div class="flex space-x-3 mt-4">
                    <a href="{{ route('doctors.show', $doctor) }}"
                    class="flex-1 bg-indigo-600 text-white py-3 rounded-lg text-center font-medium
                            hover:bg-indigo-700 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        حجز موعد
                    </a>
                    {{-- <button class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center mr-5
                                hover:bg-indigo-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button> --}}
                </div>
            </div>
        </div>
        @endforeach

        {{-- <!-- Pagination -->
        <div class="mt-12">
            {{ $doctors->links() }}
        </div> --}}

</div>

@push('styles')
<style>
    .doctor-card {
        perspective: 1000px;
    }

    .doctor-card:hover {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }

    #scrollToTopBtn {
        transition: all 0.3s ease;
        transform: translateY(20px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    #scrollToTopBtn:hover {
        transform: translateY(0) scale(1.1);
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
    }

    #scrollToTopBtn:focus {
        outline: none;
        ring: 2px solid rgba(79, 70, 229, 0.5);
    }

</style>
@endpush


@push('scripts')
<script>

    document.addEventListener('DOMContentLoaded', function() {
        // Create scroll to top button
        const scrollToTopBtn = document.createElement('button');
        scrollToTopBtn.id = 'scrollToTopBtn';
        scrollToTopBtn.className = `
            fixed bottom-6 right-6 z-50
            bg-indigo-600 text-white
            w-12 h-12 rounded-full
            flex items-center justify-center
            shadow-lg hover:bg-indigo-700
            transition-all duration-300
            opacity-0 invisible
            focus:outline-none
        `;
        scrollToTopBtn.setAttribute('aria-label', 'العودة إلى الأعلى');
        scrollToTopBtn.innerHTML = `
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
        `;
        document.body.appendChild(scrollToTopBtn);

        // Scroll event listener
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.remove('opacity-0', 'invisible');
                scrollToTopBtn.classList.add('opacity-100', 'visible');
            } else {
                scrollToTopBtn.classList.remove('opacity-100', 'visible');
                scrollToTopBtn.classList.add('opacity-0', 'invisible');
            }
        });

        // Click event to scroll to top
        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });

        // Add keyframe animations
        const styleSheet = document.createElement('style');
        styleSheet.type = 'text/css';
        styleSheet.innerText = `
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateX(-10px) translateY(20px);
                }
                to {
                    opacity: 0.9;
                    transform: translateX(-10px) translateY(0);
                }
            }
            @keyframes fadeOut {
                from {
                    opacity: 0.9;
                    transform: translateX(-10px) translateY(0);
                }
                to {
                    opacity: 0;
                    transform: translateX(-10px) translateY(20px);
                }
            }
        `;
        document.head.appendChild(styleSheet);
    });

    // Existing filter and sort functions
    function removeFilter(filterName) {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.delete(filterName);
        currentUrl.searchParams.delete('page');
        window.location.assign(currentUrl.toString());
    }

    function clearFilters() {
        const baseUrl = window.location.pathname;
        window.location.assign(baseUrl);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Handle checkbox changes
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });

        // Handle sort selection
        const sortSelect = document.querySelector('select[onchange="sortDoctors(this.value)"]');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                const currentUrl = new URL(window.location.href);
                if (this.value) {
                    currentUrl.searchParams.set('sort', this.value);
                } else {
                    currentUrl.searchParams.delete('sort');
                }
                window.location.assign(currentUrl.toString());
            });
        }
    });
</script>
@endpush
@endsection
