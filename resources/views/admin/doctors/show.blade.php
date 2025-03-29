@extends('layouts.app')

@section('content')
    <div class="doctor-profile">
        <div class="profile-header">
            <div class="profile-image">
                <img src="{{ $doctor->image ? asset($doctor->image) : asset('images/default-avatar.png') }}"
                    alt="{{ $doctor->full_name }}">
            </div>
            <div class="profile-info">
                <h1>{{ $doctor->full_name }}</h1>
                <p class="specialty">{{ $doctor->specialty->name }}</p>
                <div class="price-badge">
                    <i class="fas fa-money-bill-wave"></i>
                    سعر الكشف: {{ $doctor->price }} جنيه
                </div>
                <p class="description">{{ $doctor->description }}</p>
            </div>

            @auth('admin')
                <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
                    <div x-data="{ open: false }" class="relative inline-block">
                        <button @click="open = !open"
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            إجراءات الطبيب
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="absolute left-0 mt-2 w-56 bg-white rounded-md shadow-lg z-20 border border-gray-200">
                            <div class="py-1">
                                @if ($doctor->status === 'pending')
                                    <button onclick="updateDoctorStatus('active')"
                                        class="block w-full text-right px-4 py-2 text-sm text-green-700 hover:bg-green-50 hover:text-green-900">
                                        <i class="fas fa-check mr-2"></i>قبول الطبيب
                                    </button>
                                    <button onclick="showRejectModal()"
                                        class="block w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700">
                                        <i class="fas fa-times mr-2"></i>رفض الطبيب
                                    </button>
                                @else
                                    <button
                                        onclick="updateDoctorStatus('{{ $doctor->status === 'inactive' ? 'active' : 'inactive' }}')"
                                        class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                        <i class="fas fa-ban mr-2 text-yellow-600"></i>
                                        {{ $doctor->status === 'inactive' ? 'تفعيل الطبيب' : 'حظر الطبيب' }}
                                    </button>
                                    <button onclick="deleteDoctorConfirm()"
                                        class="block w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700">
                                        <i class="fas fa-trash mr-2"></i>حذف الطبيب
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endauth
        </div>


        <div class="profile-details">
            <div class="contact-info">
                @if (auth()->check())
                    <!-- Location Information -->
                    <div class="mt-4 bg-gray-50 p-3 rounded-xl flex items-center mb-3">
                        <i class="fas fa-map-marker-alt text-indigo-500 ml-3"></i>
                        <span class="text-gray-700">
                            {{ $doctor->governorate ? $doctor->governorate->name_ar : 'المحافظة غير محددة' }}
                        </span>
                    </div>
                    <a href="tel:{{ $doctor->phone }}" class="contact-button">
                        <i class="fas fa-phone"></i>
                        {{ $doctor->phone }}
                    </a>
                    <a href="mailto:{{ $doctor->email }}" class="contact-button">
                        <i class="fas fa-envelope"></i>
                        {{ $doctor->email }}
                    </a>
                    <a href="mailto:{{ $doctor->email }}" class="contact-button">
                        <i class="fas fa-envelope"></i>
                        {{ $doctor->communication_email }}
                    </a>
                @else
                    <div class="login-prompt">
                        <p>يرجى تسجيل الدخول لرؤية معلومات التواصل</p>
                        <a href="{{ route('login') }}" class="login-button">تسجيل الدخول</a>
                    </div>
                @endif
            </div>

            <div class="location-card">
                <h3><i class="fas fa-map-marker-alt"></i> موقع العيادة</h3>
                <div id="map"></div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="rejectForm" action="{{ route('admin.doctors.update.status', $doctor) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="rejected">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-right sm:w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    سبب رفض الطبيب
                                </h3>
                                <div class="mt-2">
                                    <textarea name="rejection_reason"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        rows="4" placeholder="اذكر سبب رفض الطبيب (اختياري)"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            تأكيد الرفض
                        </button>
                        <button type="button" onclick="hideRejectModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        .doctor-profile {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        .profile-header {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .profile-image img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary-color);
        }

        .profile-info h1 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .specialty {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .price-badge {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            margin-bottom: 1rem;
        }

        .description {
            line-height: 1.6;
            color: var(--text-secondary);
        }

        .profile-details {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 1.5rem;
        }

        .contact-info,
        .location-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .contact-button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            color: var(--text-primary);
            text-decoration: none;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .contact-button:hover {
            background: var(--primary-color);
            color: white;
        }

        #map {
            height: 300px;
            border-radius: 8px;
            margin-top: 1rem;
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .profile-image {
                margin: 0 auto;
            }

            .profile-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Leaflet map
            const map = L.map('map').setView([{{ $doctor->latitude }}, {{ $doctor->longitude }}], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            L.marker([{{ $doctor->latitude }}, {{ $doctor->longitude }}])
                .addTo(map)
                .bindPopup('{{ $doctor->full_name }}');
        }); // Added closing parenthesis and semicolon

        function updateDoctorStatus(status) {
            const statusTexts = {
                'active': 'تفعيل',
                'inactive': 'حظر'
            };

            Swal.fire({
                title: 'تأكيد',
                text: `هل أنت متأكد من ${statusTexts[status]} هذا الطبيب؟`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: status === 'inactive' ? '#d33' : '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Log request details
                    console.log('Attempting to update doctor status', {
                        doctorId: '{{ $doctor->id }}',
                        status: status,
                        url: '{{ route('admin.doctors.update.status', $doctor) }}'
                    });

                    // Send PATCH request to update status
                    axios.patch('{{ route('admin.doctors.update.status', $doctor) }}', {
                            status: status
                        })
                        .then(response => {
                            // Log successful response
                            console.log('Status Update Response:', response);

                            // Check if the response indicates success
                            if (response.data.success) {
                                Swal.fire({
                                    title: 'نجاح',
                                    text: response.data.message || 'تم تحديث حالة الطبيب بنجاح',
                                    icon: 'success'
                                }).then(() => {
                                    // Reload the page to reflect changes
                                    window.location.reload();
                                });
                            } else {
                                // Handle case where success is false
                                throw new Error(response.data.message || 'حدث خطأ غير متوقع');
                            }
                        })
                        .catch(error => {
                            // Comprehensive error logging
                            console.error('Full Error Object:', error);

                            // Detailed error information
                            if (error.response) {
                                console.error('Error Response Data:', error.response.data);
                                console.error('Error Response Status:', error.response.status);
                                console.error('Error Response Headers:', error.response.headers);
                            } else if (error.request) {
                                console.error('Error Request:', error.request);
                            } else {
                                console.error('Error Message:', error.message);
                            }

                            // Determine the most appropriate error message
                            const errorMessage = error.response?.data?.message ||
                                error.response?.data?.error ||
                                error.message ||
                                'حدث خطأ أثناء تحديث الحالة';

                            Swal.fire({
                                title: 'خطأ',
                                text: errorMessage,
                                icon: 'error',
                                footer: `<pre dir="ltr">${JSON.stringify(error.response?.data, null, 2)}</pre>`
                            });
                        });
                }
            });
        }

        function showRejectModal() {
            Swal.fire({
                title: 'تأكيد رفض الطبيب',
                text: 'هل أنت متأكد من رفض وحذف هذا الطبيب؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، رفض وحذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.patch('{{ route('admin.doctors.update.status', $doctor) }}', {
                            status: 'rejected'
                        })
                        .then(response => {
                            if (response.data.success) {
                                Swal.fire({
                                    title: 'نجاح',
                                    text: response.data.message || 'تم رفض وحذف الطبيب بنجاح',
                                    icon: 'success'
                                }).then(() => {
                                    // Redirect if a redirect URL is provided
                                    if (response.data.redirect) {
                                        window.location.href = response.data.redirect;
                                    } else {
                                        window.location.href = '{{ route('admin.doctors.pending') }}';
                                    }
                                });
                            } else {
                                throw new Error(response.data.message || 'حدث خطأ غير متوقع');
                            }
                        })
                        .catch(error => {
                            const errorMessage = error.response?.data?.message ||
                                error.message ||
                                'حدث خطأ أثناء رفض الطبيب';

                            Swal.fire({
                                title: 'خطأ',
                                text: errorMessage,
                                icon: 'error'
                            });

                            console.error('Rejection Error:', error);
                        });
                }
            });
        }

        function deleteDoctorConfirm() {
            Swal.fire({
                title: 'تأكيد الحذف',
                text: 'هل أنت متأكد من حذف هذا الطبيب؟ لن يمكنك التراجع عن هذا الإجراء.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('{{ route('admin.doctors.destroy', $doctor) }}')
                        .then(response => {
                            Swal.fire({
                                title: 'تم الحذف',
                                text: 'تم حذف الطبيب بنجاح',
                                icon: 'success'
                            }).then(() => {
                                window.location.href = '{{ route('admin.doctors.index') }}';
                            });
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'خطأ',
                                text: 'حدث خطأ أثناء حذف الطبيب',
                                icon: 'error'
                            });
                        });
                }
            });
        }
    </script>
@endsection
