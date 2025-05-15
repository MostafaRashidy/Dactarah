{{-- @extends('layouts.app') --}}


@section('scripts')
    <script>


        // Map Implementation
        let map, marker, geocoder;

        function initMap() {
            // Initialize map
            map = L.map('map').setView([25.6872, 32.6396], 13);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Add search control
            geocoder = L.Control.geocoder({
                    defaultMarkGeocode: false,
                    placeholder: 'البحث عن موقع...',
                    errorMessage: 'لم يتم العثور على الموقع',
                    showResultIcons: true
                })
                .on('markgeocode', function(e) {
                    const latlng = e.geocode.center;
                    placeMarker(latlng);
                    map.setView(latlng, 16);
                })
                .addTo(map);

            // Add scale control
            L.control.scale({
                imperial: false,
                metric: true
            }).addTo(map);

            // Handle map clicks
            map.on('click', function(e) {
                placeMarker(e.latlng);
            });

            // Add location control
            L.control.locate({
                position: 'topright',
                strings: {
                    title: "موقعي الحالي"
                },
                locateOptions: {
                    enableHighAccuracy: true
                }
            }).addTo(map);
        }

        function placeMarker(latlng) {
            if (marker) {
                marker.setLatLng(latlng);
            } else {
                marker = L.marker(latlng, {
                    draggable: true
                }).addTo(map);

                // Handle marker drag events
                marker.on('dragend', function(e) {
                    updateLocationInputs(e.target.getLatLng());
                    reverseGeocode(e.target.getLatLng());
                });
            }

            updateLocationInputs(latlng);
            reverseGeocode(latlng);
        }

        function updateLocationInputs(latlng) {
            document.getElementById('latitude').value = latlng.lat.toFixed(6);
            document.getElementById('longitude').value = latlng.lng.toFixed(6);
        }

        async function reverseGeocode(latlng) {
            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?lat=${latlng.lat}&lon=${latlng.lng}&format=json`);
                const data = await response.json();
                if (data.display_name) {
                    // Add a popup to the marker with the address
                    marker.bindPopup(data.display_name).openPopup();
                }
            } catch (error) {
                console.error('Error reverse geocoding:', error);
            }
        }

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        const latlng = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        map.setView(latlng, 16);
                        placeMarker(latlng);
                    },
                    error => {
                        let message;
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                message = "يرجى السماح بالوصول إلى موقعك للاستمرار.";
                                break;
                            case error.POSITION_UNAVAILABLE:
                                message = "معلومات الموقع غير متوفرة.";
                                break;
                            case error.TIMEOUT:
                                message = "انتهت مهلة طلب الموقع.";
                                break;
                            default:
                                message = "حدث خطأ غير معروف.";
                        }
                        alert(message);
                    }, {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    }
                );
            } else {
                alert("متصفحك لا يدعم تحديد المواقع.");
            }
        }

        // Initialize map when page loads
        window.onload = initMap;


        // JavaScript for image preview
        function previewImage(input) {
            const preview = document.getElementById('preview');
            const uploadIcon = preview.querySelector('.upload-icon');

            if (input.files && input.files[0]) {
                const file = input.files[0];

                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('الرجاء اختيار ملف صورة صالح');
                    input.value = '';
                    return;
                }

                // Validate file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('حجم الصورة يجب أن لا يتجاوز 5 ميجابايت');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.style.backgroundImage = `url(${e.target.result})`;
                    preview.style.backgroundSize = 'cover';
                    preview.style.backgroundPosition = 'center';
                    uploadIcon.style.display = 'none';
                };

                reader.onerror = function() {
                    alert('حدث خطأ أثناء قراءة الملف');
                    input.value = '';
                };

                reader.readAsDataURL(file);
            } else {
                // Reset to default state
                preview.style.backgroundImage = 'none';
                preview.style.backgroundColor = '#f9fafb';
                uploadIcon.style.display = 'flex';
            }
        }

        // Password Toggle Function
        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.querySelector('input[name="password"]');
            const toggleButton = document.querySelector('.password-toggle');

            if (toggleButton) {
                toggleButton.addEventListener('click', function() {
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);

                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }
        });

        // Character Counter for Description
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.querySelector('textarea[name="description"]');
            const charCount = document.getElementById('charCount');
            const maxLength = 150;

            if (textarea && charCount) {
                function updateCharCount() {
                    const remaining = maxLength - textarea.value.length;
                    charCount.textContent = remaining;

                    // Optional: Change color when approaching limit
                    if (remaining < 20) {
                        charCount.style.color = '#dc3545';
                    } else {
                        charCount.style.color = '';
                    }
                }

                textarea.addEventListener('input', updateCharCount);
                textarea.addEventListener('keyup', updateCharCount);
                textarea.addEventListener('paste', updateCharCount);

                // Initial count
                updateCharCount();
            }
        });

        // Form Submission Handler
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('application');

            if (form) {
                form.addEventListener('submit', function(e) {
                    // Prevent default submission
                    e.preventDefault();

                    // Validate form
                    if (!validateForm()) {
                        return false;
                    }

                    // Ensure latitude and longitude are set
                    const latitudeField = document.getElementById('latitude');
                    const longitudeField = document.getElementById('longitude');

                    if (!latitudeField.value || !longitudeField.value) {
                        alert('يرجى تحديد موقع العيادة على الخريطة');
                        return false;
                    }

                    // Update button state
                    const submitButton = this.querySelector('.submit-button');
                    const submitText = submitButton.querySelector('.submit-text');
                    const loadingSpinner = submitButton.querySelector('.loading-spinner');

                    submitButton.disabled = true;
                    submitText.hidden = true;
                    loadingSpinner.hidden = false;

                    // Create FormData
                    const formData = new FormData(this);

                    // Log form data for debugging
                    for (let [key, value] of formData.entries()) {
                        console.log(`${key}: ${value}`);
                    }

                    // Submit form
                    this.submit();
                });
            }
        });

        // Form Validation
        function validateForm() {
            let isValid = true;
            const requiredFields = document.querySelectorAll('[required]');

            // requiredFields.forEach(field => {
            //     if (!field.value.trim()) {
            //         isValid = false;
            //         field.classList.add('error');

            //         // Add error message
            //         let errorMessage = field.nextElementSibling;
            //         if (!errorMessage || !errorMessage.classList.contains('error-message')) {
            //             errorMessage = document.createElement('div');
            //             errorMessage.classList.add('error-message');
            //             field.parentNode.insertBefore(errorMessage, field.nextSibling);
            //         }
            //         errorMessage.textContent = 'هذا الحقل مطلوب';
            //     } else {
            //         field.classList.remove('error');
            //         const errorMessage = field.nextElementSibling;
            //         if (errorMessage && errorMessage.classList.contains('error-message')) {
            //             errorMessage.remove();
            //         }
            //     }
            // });

            // Validate email format
            const emailField = document.querySelector('input[name="email"]');
            if (emailField && emailField.value) {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(emailField.value)) {
                    isValid = false;
                    emailField.classList.add('error');
                    let errorMessage = emailField.nextElementSibling;
                    if (!errorMessage || !errorMessage.classList.contains('error-message')) {
                        errorMessage = document.createElement('div');
                        errorMessage.classList.add('error-message');
                        emailField.parentNode.insertBefore(errorMessage, emailField.nextSibling);
                    }
                    errorMessage.textContent = 'يرجى إدخال بريد إلكتروني صحيح';
                }
            }

            // Validate phone format
            const phoneField = document.querySelector('input[name="phone"]');
            if (phoneField && phoneField.value) {
                // pattern for Egyptian numbers
                const phonePattern = /^01[0-2,5]\d{8}$/;
                const phoneNumber = phoneField.value.replace(/\s/g, '');

                if (!phonePattern.test(phoneNumber)) {
                    isValid = false;
                    phoneField.classList.add('error');
                    let errorMessage = phoneField.nextElementSibling;
                    if (!errorMessage || !errorMessage.classList.contains('error-message')) {
                        errorMessage = document.createElement('div');
                        errorMessage.classList.add('error-message');
                        phoneField.parentNode.insertBefore(errorMessage, phoneField.nextSibling);
                    }
                    errorMessage.textContent = 'يجب إدخال رقم هاتف مصري صحيح يبدأ بـ 010 أو 011 أو 012 أو 015';
                } else {
                    // Valid phone number
                    phoneField.classList.remove('error');
                    const errorMessage = phoneField.nextElementSibling;
                    if (errorMessage && errorMessage.classList.contains('error-message')) {
                        errorMessage.remove();
                    }
                }
            }

            // Governorate validation
            const governorateField = document.querySelector('select[name="governorate_id"]');
            if (governorateField && !governorateField.value) {
                isValid = false;
                governorateField.classList.add('error');
                let errorMessage = governorateField.nextElementSibling;
                if (!errorMessage || !errorMessage.classList.contains('error-message')) {
                    errorMessage = document.createElement('div');
                    errorMessage.classList.add('error-message');
                    governorateField.parentNode.insertBefore(errorMessage, governorateField.nextSibling);
                }
                errorMessage.textContent = 'يرجى اختيار المحافظة';
            }

            return isValid;
        }
    </script>
@endsection




<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100/50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <div class="relative bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/30 overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-400 via-purple-500 to-pink-500 animate-gradient-x"></div>

                <div class="p-8 md:p-12 space-y-8">
                    <div class="text-center space-y-4">
                        <h2 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">انضم لشبكة دكترة</h2>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">كن جزءاً من أكبر شبكة أطباء مبتكرة ومتطورة في المنطقة</p>
                    </div>

                    <form method="POST" action="{{ route('doctors.store') }}" enctype="multipart/form-data" class="space-y-8" id="application">
                        @csrf

                        <!-- Profile Image Upload -->
                        <div class="flex justify-center">
                            <div class="relative group">
                                <div id="preview" class="w-48 h-48 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 border-4 border-white shadow-lg flex items-center justify-center cursor-pointer transition-all duration-300 hover:scale-105 hover:shadow-xl group-hover:border-blue-300" onclick="document.getElementById('usr_img').click()">
                                    <div class="upload-icon text-center p-4 group-hover:opacity-70 transition-opacity">
                                        <i class="fas fa-camera text-4xl text-gray-500 group-hover:text-blue-600"></i>
                                        <p class="mt-2 text-sm text-gray-600 group-hover:text-blue-700">اضغط لرفع صورة</p>
                                    </div>
                                </div>
                                <input type="file" id="usr_img" name="usr_img" class="hidden" accept="image/*" onchange="previewImage(this)">
                            </div>
                            @error('usr_img')
                                <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Responsive Grid Layout -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Personal Information Column -->
                            <div class="space-y-6">
                                <!-- First Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        الاسم الأول <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="first_name" required value="{{ old('first_name') }}"
                                        class="w-full px-4 py-3 rounded-xl bg-gray-100/50 border border-gray-200 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all duration-300 hover:bg-blue-50/50">
                                    @error('first_name')
                                        <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Login Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        البريد الإلكتروني للدخول <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="login_email" required value="{{ old('login_email') }}"
                                        class="w-full px-4 py-3 rounded-xl bg-gray-100/50 border border-gray-200 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all duration-300 hover:bg-blue-50/50">
                                    @error('login_email')
                                        <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        رقم الهاتف <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="phone" required value="{{ old('phone') }}"
                                        class="w-full px-4 py-3 rounded-xl bg-gray-100/50 border border-gray-200 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all duration-300 hover:bg-blue-50/50">
                                    @error('phone')
                                        <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Professional Information Column -->
                            <div class="space-y-6">
                                <!-- Last Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        الاسم الأخير <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="last_name" required value="{{ old('last_name') }}"
                                        class="w-full px-4 py-3 rounded-xl bg-gray-100/50 border border-gray-200 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all duration-300 hover:bg-blue-50/50">
                                    @error('last_name')
                                        <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Communication Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        البريد الإلكتروني للتواصل <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="communication_email" required value="{{ old('communication_email') }}"
                                        class="w-full px-4 py-3 rounded-xl bg-gray-100/50 border border-gray-200 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all duration-300 hover:bg-blue-50/50">
                                    @error('communication_email')
                                        <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        كلمة المرور <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="password" name="password" required
                                            class="w-full px-4 py-3 rounded-xl bg-gray-100/50 border border-gray-200 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all duration-300 hover:bg-blue-50/50">
                                        <button type="button" class="password-toggle absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-600">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Fields -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Governorate -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    المحافظة <span class="text-red-500">*</span>
                                </label>
                                <select name="governorate_id" required
                                    class="w-full px-4 py-3 rounded-xl bg-gray-100/50 border border-gray-200 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all duration-300 hover:bg-blue-50/50">
                                    <option value="">اختر المحافظة</option>
                                    @foreach ($governorates as $governorate)
                                        <option value="{{ $governorate->id }}" {{ old('governorate_id') == $governorate->id ? 'selected' : '' }}>
                                            {{ $governorate->name_ar }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('governorate_id')
                                    <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Specialty -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    التخصص <span class="text-red-500">*</span>
                                </label>
                                <select name="specialty_id" required
                                    class="w-full px-4 py-3 rounded-xl bg-gray-100/50 border border-gray-200 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all duration-300 hover:bg-blue-50/50">
                                    <option value="">اختر تخصصك</option>
                                    @foreach ($specialties as $specialty)
                                        <option value="{{ $specialty->id }}" {{ old('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                            {{ $specialty->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('specialty_id')
                                    <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Full Width Fields -->
                        <div class="space-y-6">
                            <!-- Price -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    سعر الكشف <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="price" required value="{{ old('price') }}"
                                    class="w-full px-4 py-3 rounded-xl bg-gray-100/50 border border-gray-200 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all duration-300 hover:bg-blue-50/50">
                                @error('price')
                                    <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    نبذة عنك <span class="text-red-500">*</span>
                                </label>
                                <textarea name="description" rows="4" maxlength="150" required
                                    class="w-full px-4 py-3 rounded-xl bg-gray-100/50 border border-gray-200 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all duration-300 hover:bg-blue-50/50">{{ old('description') }}</textarea>
                                <div class="mt-2 text-sm text-gray-500 text-left">
                                    <span id="charCount">150</span> حرف متبقي
                                </div>
                                @error('description')
                                    <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Location -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    موقع العيادة <span class="text-red-500">*</span>
                                </label>
                                <button type="button" onclick="getLocation()"
                                    class="mb-4 inline-flex items-center px-4 py-2 bg-blue-50 border border-blue-200 rounded-xl text-sm font-medium text-blue-700 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300">
                                    <i class="fas fa-map-marker-alt ml-2"></i>
                                    تحديد الموقع الحالي
                                </button>
                                <div class="h-80 rounded-xl overflow-hidden border border-gray-300">
                                    <div id="map" class="h-full"></div>
                                </div>
                                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                                @error('latitude')
                                    <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                                @enderror
                                @error('longitude')
                                    <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit"
                                class="submit-button w-full py-4 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                                <span class="submit-text">إنشاء حساب</span>
                                <span class="loading-spinner hidden">جاري التسجيل...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes gradient-x {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-gradient-x {
            background-size: 200% 200%;
            animation: gradient-x 5s ease infinite;
        }
    </style>
    @endpush
</x-app-layout>
