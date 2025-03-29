<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header Section -->
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center">
                            <h2 class="text-xl font-bold text-gray-800">المستخدمين</h2>
                            <span
                                class="mr-3 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $users->total() }} مستخدم
                            </span>
                        </div>

                        <!-- Actions Dropdown -->
                        <div class="relative">
                            {{-- <button id="userActionsDropdown" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md shadow">
                                إجراءات المستخدمين
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button> --}}

                            <div id="userActionsMenu"
                                class="hidden absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg z-20 border border-gray-200">
                                <div class="py-1">
                                    <a href="{{ route('admin.users.create') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-plus mr-2"></i>إضافة مستخدم جديد
                                    </a>
                                    <button id="bulkDeleteBtn"
                                        class="w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-trash mr-2"></i>حذف المحدد
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filter Section -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-md shadow-md border border-gray-200">
                        <form id="filterForm" method="GET" action="{{ route('admin.users.index') }}"
                            class="flex flex-wrap gap-4 items-center">
                            <!-- Hidden page input -->
                            <input type="hidden" name="page" value="{{ request('page', 1) }}">

                            <div class="flex-1 min-w-[200px] relative">
                                <input type="text" name="search" placeholder="بحث بالاسم أو البريد الإلكتروني..."
                                    value="{{ request('search') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm pr-10">
                                <div class="absolute inset-y-0 right-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                @if (request('search'))
                                    <button type="button" onclick="removeSearchFilter()"
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 hover:text-gray-600">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Table Section -->
                    <div class="overflow-x-auto bg-white rounded-md shadow">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3">
                                        <input type="checkbox" id="selectAllCheckbox"
                                            class="rounded border-gray-300 text-blue-600 shadow-sm">
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الاسم
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">البريد
                                        الإلكتروني</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ
                                        الإنشاء</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="usersTableBody">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200"
                                        data-user-id="{{ $user->id }}">
                                        <td class="px-4 py-3">
                                            <input type="checkbox" name="selected_users[]" value="{{ $user->id }}"
                                                class="user-checkbox rounded border-gray-300 text-blue-600 shadow-sm">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $user->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $user->created_at->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex justify-end space-x-2">
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                    class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')"
                                                        class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 text-sm rounded hover:bg-red-200">
                                                        حذف
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Ensure Swal is available
                if (typeof Swal === 'undefined') {
                    console.error('SweetAlert2 is not loaded');
                    return;
                }

                // Individual Delete
                document.querySelectorAll('form[method="POST"]').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        Swal.fire({
                            title: 'تأكيد الحذف',
                            text: 'هل أنت متأكد من حذف هذا المستخدم؟',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'نعم، احذف',
                            cancelButtonText: 'إلغاء'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Show loading
                                Swal.fire({
                                    title: 'جاري الحذف...',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });

                                // Perform deletion
                                fetch(this.action, {
                                        method: 'POST',
                                        body: new FormData(this),
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Accept': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').content
                                        }
                                    })
                                    .then(response => {
                                        // Ensure response is JSON
                                        const contentType = response.headers.get(
                                            'content-type');
                                        if (!contentType || !contentType.includes(
                                                'application/json')) {
                                            throw new Error('Response is not JSON');
                                        }

                                        // Check response status
                                        if (!response.ok) {
                                            throw new Error('Network response was not ok');
                                        }

                                        return response.json();
                                    })
                                    .then(data => {
                                        // Check if deletion was successful
                                        if (data.success) {
                                            Swal.fire({
                                                title: 'تم الحذف!',
                                                text: data.message ||
                                                    'تم حذف المستخدم بنجاح',
                                                icon: 'success'
                                            }).then(() => {
                                                // Reload or remove the row
                                                location.reload();
                                            });
                                        } else {
                                            // Handle unsuccessful deletion
                                            throw new Error(data.message ||
                                                'فشل حذف المستخدم');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Deletion error:', error);

                                        Swal.fire({
                                            title: 'خطأ!',
                                            text: error.message ||
                                                'حدث خطأ أثناء الحذف',
                                            icon: 'error'
                                        });
                                    });
                            }
                        });
                    });
                });

                // Bulk Delete
                const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
                if (bulkDeleteBtn) {
                    bulkDeleteBtn.addEventListener('click', function() {
                        const selectedUsers = document.querySelectorAll('.user-checkbox:checked');

                        if (selectedUsers.length === 0) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'تحذير',
                                text: 'الرجاء اختيار المستخدمين المراد حذفهم',
                                confirmButtonText: 'حسناً'
                            });
                            return;
                        }

                        Swal.fire({
                            title: 'تأكيد الحذف',
                            text: `هل أنت متأكد من حذف ${selectedUsers.length} مستخدم؟`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'نعم، احذف',
                            cancelButtonText: 'إلغاء'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Show loading
                                Swal.fire({
                                    title: 'جاري الحذف...',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });

                                const userIds = Array.from(selectedUsers).map(el => el.value);

                                fetch('{{ route('admin.users.bulk-delete') }}', {
                                        method: 'DELETE',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').content
                                        },
                                        body: JSON.stringify({
                                            users: userIds
                                        })
                                    })
                                    .then(response => {
                                        // Ensure response is JSON
                                        const contentType = response.headers.get('content-type');
                                        if (!contentType || !contentType.includes(
                                                'application/json')) {
                                            throw new Error('Response is not JSON');
                                        }

                                        // Check response status
                                        if (!response.ok) {
                                            throw new Error('Network response was not ok');
                                        }

                                        return response.json();
                                    })
                                    .then(data => {
                                        // Check if deletion was successful
                                        if (data.success) {
                                            Swal.fire({
                                                title: 'تم الحذف!',
                                                text: data.message ||
                                                    'تم حذف المستخدمين بنجاح',
                                                icon: 'success'
                                            }).then(() => {
                                                // Reload or update the page
                                                location.reload();
                                            });
                                        } else {
                                            // Handle unsuccessful deletion
                                            throw new Error(data.message || 'فشل حذف المستخدمين');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Bulk delete error:', error);

                                        Swal.fire({
                                            title: 'خطأ!',
                                            text: error.message || 'حدث خطأ غير متوقع',
                                            icon: 'error'
                                        });
                                    });
                            }
                        });
                    });
                }
            });
        </script>
    @endpush
</x-admin-layout>
