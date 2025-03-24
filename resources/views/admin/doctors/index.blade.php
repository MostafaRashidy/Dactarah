<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header Section with Dropdown Actions -->
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center">
                            <h2 class="text-xl font-bold text-gray-800">الأطباء</h2>
                            <span class="mr-3 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $doctors->total() }} طبيب
                            </span>
                        </div>

                        <!-- Enhanced Dropdown Menu -->
                        <div class="relative">
                            {{-- <button id="doctorActionsDropdown" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md shadow transition duration-150 ease-in-out">
                                إجراءات الأطباء
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div id="doctorActionsMenu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg z-20 border border-gray-200 dropdown-transition">
                                <div class="py-1">
                                    <a href="{{ route('admin.doctors.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                        <i class="fas fa-plus mr-2"></i>إضافة طبيب جديد
                                    </a>
                                    <a href="{{ route('admin.doctors.pending') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                        <i class="fas fa-clock mr-2"></i>طلبات الانضمام
                                    </a>
                                    <a href="{{ route('admin.doctors.export') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                        <i class="fas fa-file-export mr-2"></i>تصدير البيانات
                                    </a>
                                    <button id="bulkDeleteBtn" class="w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700">
                                        <i class="fas fa-trash mr-2"></i>حذف المحدد
                                    </button>
                                </div>
                            </div> --}}
                        </div>
                    </div>

                    <!-- Search and Filter Section -->
<div class="mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <form id="filterForm" method="GET" action="{{ route('admin.doctors.index') }}" class="space-y-6">
                <!-- Add this hidden input at the top of the form -->
                <input type="hidden" name="page" value="{{ request('page', 1) }}">
                <!-- Top Row: Search and Quick Filters -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                    <!-- Search Input with Button -->
                    <div class="lg:col-span-2">
                        <div class="relative flex">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="بحث باسم الطبيب أو البريد الإلكتروني أو رقم الهاتف..."
                                class="w-full rounded-r-lg border-l-0 border-gray-300 pr-10 focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-l-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-150 flex items-center gap-2"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span>بحث</span>
                            </button>
                        </div>
                    </div>

                    <!-- Specialty Filter -->
                    <div>
                        <select
                            name="specialty"
                            onchange="this.form.submit()"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
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

                    <!-- Status Filter -->
                    <div>
                        <select
                            name="status"
                            onchange="this.form.submit()"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">كل الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                        </select>
                    </div>

                <!-- Bottom Row: Additional Filters and Sort -->
                <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-gray-200">
                    <!-- Active Filters -->
                    <div class="flex flex-wrap items-center gap-2">
                        @if(request()->anyFilled(['search', 'specialty', 'status']))
                            <span class="text-sm text-gray-600">الفلاتر النشطة:</span>

                            @if(request('search'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800">
                                    <span>البحث: {{ request('search') }}</span>
                                    <button type="button" onclick="removeFilter('search')" class="mr-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </span>
                            @endif

                            @if(request('specialty'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800">
                                    <span>التخصص: {{ $specialties->find(request('specialty'))->name }}</span>
                                    <button type="button" onclick="removeFilter('specialty')" class="mr-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </span>
                            @endif

                            @if(request('status'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800">
                                    <span>
                                        الحالة:
                                        {{
                                            request('status') == 'active' ? 'نشط' :
                                            (request('status') == 'inactive' ? 'غير نشط' : 'معلق')
                                        }}
                                    </span>
                                    <button type="button" onclick="removeFilter('status')" class="mr-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </span>
                            @endif

                            <button type="button" onclick="clearFilters()"
                                    class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                مسح الكل
                            </button>
                        @endif
                    </div>

                    <!-- Sort -->
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">ترتيب حسب:</span>
                        <select
                            name="sort"
                            onchange="this.form.submit()"
                            class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">الأحدث</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                السعر: من الأقل
                            </option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                السعر: من الأعلى
                            </option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>
                                التقييم
                            </option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
                    @if(request('search'))
                        <div class="bg-blue-100 p-2 rounded mb-4">
                            نتائج البحث عن: {{ request('search') }}
                            <a href="{{ route('admin.doctors.index') }}" class="text-red-500 mr-2">إلغاء البحث</a>
                        </div>
                    @endif


                    <!-- Table Section -->
                    <div class="overflow-x-auto bg-white rounded-md shadow">
                        <table class="w-full divide-y divide-gray-200 table-compact">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3">
                                        <input type="checkbox" id="selectAllCheckbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        معلومات الطبيب
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        التخصص
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        السعر
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الحالة
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الإجراءات
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="doctorsTableBody">
                                @foreach($doctors as $doctor)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200" data-doctor-id="{{ $doctor->id }}"
                                        data-specialty="{{ $doctor->specialty->name ?? '' }}"
                                        data-status="{{ $doctor->status }}">
                                        <td class="px-4 py-3">
                                            <input type="checkbox" name="selected_doctors[]" value="{{ $doctor->id }}"
                                                   class="doctor-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-9 w-9 relative">
                                                    <img class="h-9 w-9 rounded-full object-cover border border-gray-200"
                                                         src="{{ $doctor->image ? asset($doctor->image) : asset('images/default-avatar.png') }}"
                                                         alt="{{ $doctor->first_name }} {{ $doctor->last_name }}"
                                                         loading="lazy">
                                                    <div class="absolute bottom-0 right-0 h-2 w-2 rounded-full
                                                        {{ $doctor->status == 'active' ? 'bg-green-400' :
                                                           ($doctor->status == 'pending' ? 'bg-yellow-400' : 'bg-red-400') }}
                                                        border border-white"></div>
                                                </div>
                                                <div class="mr-3 flex-1">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $doctor->first_name }} {{ $doctor->last_name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 mt-0.5">
                                                        {{ $doctor->email }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 flex items-center mt-0.5">
                                                        <svg class="h-4 w-4 text-gray-400 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                        </svg>
                                                        {{ $doctor->phone }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-sm leading-5 font-medium rounded-full bg-blue-100 text-blue-800">
                                                {{ $doctor->specialty->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <span class="font-medium">{{ number_format($doctor->price, 2) }}</span>
                                                <span class="text-gray-500">جنيه</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-sm leading-5 font-medium rounded-full
                                                {{ $doctor->status == 'active' ? 'bg-green-100 text-green-800' :
                                                   ($doctor->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ $doctor->status == 'active' ? 'نشط' :
                                                   ($doctor->status == 'pending' ? 'معلق' : 'غير نشط') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('admin.doctors.show', $doctor) }}"
                                                   class="inline-flex items-center px-2 py-1 bg-indigo-100 text-indigo-700 text-sm rounded hover:bg-indigo-200 transition duration-150 ease-in-out">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $doctors->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
.dropdown-transition {
    transition: opacity 0.1s ease-in-out, transform 0.1s ease-in-out;
}

.dropdown-enter {
    opacity: 0;
    transform: translateY(-10px);
}

.dropdown-enter-active {
    opacity: 1;
    transform: translateY(0);
}

.dropdown-exit {
    opacity: 1;
    transform: translateY(0);
}

.dropdown-exit-active {
    opacity: 0;
    transform: translateY(-10px);
}

.search-highlight {
        background-color: yellow;
        font-weight: bold;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const sortSelect = filterForm.querySelector('select[name="sort"]');

    // Function to submit form with loading indicator
    function submitFormWithLoading() {
        // Add loading indicator
        const loadingOverlay = document.createElement('div');
        loadingOverlay.classList.add(
            'fixed', 'inset-0', 'bg-black', 'bg-opacity-10',
            'flex', 'items-center', 'justify-center', 'z-50'
        );
        loadingOverlay.innerHTML = `
            <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-indigo-600"></div>
        `;
        document.body.appendChild(loadingOverlay);

        // Reset page to 1
        const pageInput = filterForm.querySelector('input[name="page"]');
        if (pageInput) {
            pageInput.value = 1;
        }

        filterForm.submit();
    }

    // Sort select event listener
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            submitFormWithLoading();
        });
    }

    // Remove filter function
    window.removeFilter = function(filterName) {
        // Remove the specific filter input
        const filterInput = filterForm.querySelector(`[name="${filterName}"]`);
        if (filterInput) {
            if (filterInput.tagName === 'SELECT') {
                filterInput.selectedIndex = 0; // Reset to first option
            } else {
                filterInput.value = '';
            }
        }

        submitFormWithLoading();
    };

    // Clear all filters function
    window.clearFilters = function() {
        // Clear all filter inputs
        const filterInputs = filterForm.querySelectorAll('input[name="search"], select[name="specialty"], select[name="status"], select[name="sort"]');
        filterInputs.forEach(input => {
            if (input.tagName === 'SELECT') {
                input.selectedIndex = 0; // Reset to first option
            } else {
                input.value = '';
            }
        });

        submitFormWithLoading();
    };

    // Pagination links handling
    const paginationLinks = document.querySelectorAll('.pagination a');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = new URL(this.href);
            const page = url.searchParams.get('page');

            // Update page input
            const pageInput = filterForm.querySelector('input[name="page"]');
            if (pageInput) {
                pageInput.value = page;
            }

            submitFormWithLoading();
        });
    });
});
</script>
@endpush
</x-admin-layout>
