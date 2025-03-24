<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('طلبات انضمام الأطباء') }}
            </h2>
            <span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-3 py-1 rounded-full">
                {{ $doctors->total() }} طلب
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Search and Filter Section -->
                    <div class="mb-6">
                        <form id="filterForm" method="GET" action="{{ route('admin.doctors.pending') }}" class="space-y-4">
                            <div class="flex flex-wrap gap-4 items-center justify-between">
                                <div class="flex-1 min-w-[300px] relative">
                                    <input type="text"
                                        name="search"
                                        id="searchInput"
                                        value="{{ request('search') }}"
                                        placeholder="بحث بالاسم أو البريد الإلكتروني..."
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 pr-10">
                                    <div class="absolute inset-y-0 right-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    @if(request('search'))
                                        <button type="button"
                                                onclick="clearSearch()"
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>

                                <div class="flex gap-4 items-center">
                                    <!-- Specialty Filter -->
                                    <select
                                        name="specialty"
                                        id="specialtyFilter"
                                        onchange="autoApplyFilters()"
                                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    >
                                        <option value="">كل التخصصات</option>
                                        @foreach($specialties as $specialty)
                                            <option value="{{ $specialty->id }}"
                                                    {{ request('specialty') == $specialty->id ? 'selected' : '' }}>
                                                {{ $specialty->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <!-- Date Filter -->
                                    <select
                                        name="date_filter"
                                        id="dateFilter"
                                        onchange="autoApplyFilters()"
                                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    >
                                        <option value="">كل الفترات</option>
                                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>
                                            اليوم
                                        </option>
                                        <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>
                                            هذا الأسبوع
                                        </option>
                                        <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>
                                            هذا الشهر
                                        </option>
                                    </select>

                                    <!-- Search Button -->
                                    <button type="submit"
                                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700
                                                transition-colors duration-300 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        بحث
                                    </button>

                                    <!-- Clear Filters Button -->
                                    @if(request()->anyFilled(['search', 'specialty', 'date_filter']))
                                        <button type="button"
                                                onclick="clearFilters()"
                                                class="bg-red-50 text-red-600 px-4 py-2 rounded-lg hover:bg-red-100
                                                    transition-colors duration-300 flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            مسح الفلاتر
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>

                    @if($doctors->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($doctors as $doctor)
                                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col h-full" data-doctor-id="{{ $doctor->id }}">
                                    <div class="p-6 flex-grow">
                                        <!-- Doctor Info Header -->
                                        <div class="flex items-center mb-6">
                                            <div class="relative">
                                                <img class="h-16 w-16 rounded-full object-cover border-2 border-gray-200"
                                                     src="{{ $doctor->image ? asset($doctor->image) : asset('images/default-avatar.png') }}"
                                                     alt="{{ $doctor->full_name }}"
                                                     loading="lazy">
                                                <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full bg-yellow-400 border-2 border-white"
                                                     title="في انتظار المراجعة"></div>
                                            </div>
                                            <div class="mr-4">
                                                <div class="text-lg font-semibold text-gray-900">
                                                    {{ $doctor->first_name }} {{ $doctor->last_name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $doctor->email }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Doctor Details -->
                                        <div class="space-y-4">
                                            <div class="flex items-center p-2 bg-gray-50 rounded-lg">
                                                <svg class="h-5 w-5 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="text-sm text-gray-600">{{ $doctor->specialty->name }}</span>
                                            </div>

                                            <div class="flex items-center p-2 bg-gray-50 rounded-lg">
                                                <svg class="h-5 w-5 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="text-sm text-gray-600">{{ number_format($doctor->price, 2) }} جنيه</span>
                                            </div>

                                            <div class="flex items-center p-2 bg-gray-50 rounded-lg">
                                                <svg class="h-5 w-5 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="text-sm text-gray-600">{{ $doctor->created_at->format('Y/m/d') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="p-6 bg-gray-50 rounded-b-lg border-t">
                                        <div class="flex justify-center gap-2">
                                            <button onclick="window.location.href='{{ route('admin.doctors.show', $doctor) }}'"
                                                    class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                                <svg class="h-4 w-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                عرض
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">
                            {{ $doctors->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد طلبات انضمام جديدة</h3>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


<!-- Hidden div to preserve Tailwind classes -->
<div class="hidden">
    <!-- Button Base Classes -->
    <div class="bg-blue-600 bg-green-600 bg-red-600"></div>
    <!-- Button Hover States -->
    <div class="hover:bg-indigo-700 hover:bg-green-700 hover:bg-red-700"></div>
    <!-- Focus States -->
    <div class="focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-green-500 focus:ring-red-500"></div>
    <!-- Flex and Layout Classes -->
    <div class="flex-1 inline-flex justify-center items-center"></div>
    <!-- Padding and Text -->
    <div class="px-4 py-2 text-sm font-medium"></div>
    <!-- Colors -->
    <div class="text-white bg-blue-600 bg-green-600 bg-red-600"></div>
    <!-- Transitions -->
    <div class="transition-colors duration-200"></div>
    <!-- Rounded -->
    <div class="rounded-lg"></div>
</div>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Function to show loading
    function showLoading(message = 'جاري البحث...') {
        return Swal.fire({
            title: message,
            html: 'يرجى الانتظار',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    // Auto apply filters for select elements
    function autoApplyFilters() {
        showLoading();
        document.getElementById('filterForm').submit();
    }

    // Debounced search input
    const searchInput = document.getElementById('searchInput');
    const debouncedSearch = debounce(function() {
        const searchTerm = searchInput.value.trim();

        if (searchTerm.length > 0) {
            Swal.fire({
                title: 'تأكيد البحث',
                text: `هل تريد البحث عن "${searchTerm}"؟`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، ابحث',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    document.getElementById('filterForm').submit();
                }
            });
        }
    }, 500);

    // Add input event listener to search
    searchInput.addEventListener('input', debouncedSearch);

    // Clear search function
    function clearSearch() {
        searchInput.value = '';
        showLoading();
        document.getElementById('filterForm').submit();
    }

    // Clear filters function
    function clearFilters() {
        Swal.fire({
            title: 'مسح الفلاتر',
            text: 'هل أنت متأكد من مسح جميع الفلاتر؟',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'نعم، امسح',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to base page
                window.location.href = '{{ route('admin.doctors.pending') }}';
            }
        });
    }

    // Pagination links handling
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            showLoading('جاري تحميل الصفحة...');
            window.location.href = this.href;
        });
    });
</script>

<style>
    .swal2-popup {
        direction: rtl;
        font-family: 'Cairo', sans-serif;
    }

    .swal2-actions {
        flex-direction: row-reverse;
    }

    .swal2-confirm {
        margin-left: 10px !important;
        margin-right: 0 !important;
    }
</style>
@endpush
</x-admin-layout>
