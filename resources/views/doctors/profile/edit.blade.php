@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">
                        معلومات الملف الشخصي
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        تحديث معلومات حسابك وصورتك الشخصية.
                    </p>
                </header>

                <form method="post" action="{{ route('doctor.profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <!-- Profile Image -->
                    <div>
                        <div class="flex items-center gap-6">
                            <!-- Image Preview -->
                            <div class="relative group">
                                <div id="imagePreview" class="w-32 h-32 rounded-full overflow-hidden border-4 border-indigo-100">
                                    @if(auth('doctor')->user()->image)
                                        <img src="{{ asset(auth('doctor')->user()->image) }}"
                                            alt="Profile Image"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-indigo-100 flex items-center justify-center">
                                            <i class="fas fa-user-md text-indigo-600 text-4xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <!-- Hover Overlay -->
                                <div class="absolute inset-0 bg-black bg-opacity-40 rounded-full opacity-0 group-hover:opacity-100
                                            transition-opacity duration-200 flex items-center justify-center">
                                    <span class="text-white text-sm">تغيير الصورة</span>
                                </div>
                            </div>

                            <div class="flex-1">
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                    الصورة الشخصية
                                </label>
                                <div class="relative">
                                    <input type="file"
                                        id="image"
                                        name="image"
                                        accept="image/*"
                                        class="block w-full text-sm text-gray-500
                                                file:mr-4 file:py-2.5 file:px-4
                                                file:rounded-full file:border-0
                                                file:text-sm file:font-semibold
                                                file:bg-indigo-50 file:text-indigo-700
                                                hover:file:bg-indigo-100
                                                cursor-pointer"
                                        onchange="previewImage(this)">
                                    <p class="mt-1 text-sm text-gray-500">
                                        يجب أن تكون الصورة بصيغة JPG أو PNG وحجم لا يتجاوز 2 ميجابايت
                                    </p>
                                </div>
                            </div>
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('image')" />
                    </div>

                    <!-- Name -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="first_name" value="الاسم الأول" />
                            <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full"
                                         :value="old('first_name', auth('doctor')->user()->first_name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                        </div>

                        <div>
                            <x-input-label for="last_name" value="الاسم الأخير" />
                            <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full"
                                         :value="old('last_name', auth('doctor')->user()->last_name)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" value="البريد الإلكتروني" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                     :value="old('email', auth('doctor')->user()->email)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <!-- Phone -->
                    <div>
                        <x-input-label for="phone" value="رقم الهاتف" />
                        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
                                     :value="old('phone', auth('doctor')->user()->phone)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>

                    <!-- Communication Email -->
                    <div>
                        <x-input-label for="communication_email" value="البريد الإلكتروني للتواصل" />
                        <x-text-input id="communication_email" name="communication_email" type="email" class="mt-1 block w-full"
                                     :value="old('communication_email', auth('doctor')->user()->communication_email)" />
                        <x-input-error class="mt-2" :messages="$errors->get('communication_email')" />
                    </div>

                    <!-- Governorate -->
                    <div>
                        <x-input-label for="governorate_id" value="المحافظة" />
                        <select id="governorate_id" name="governorate_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                       focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($governorates as $governorate)
                                <option value="{{ $governorate->id }}"
                                        {{ old('governorate_id', auth('doctor')->user()->governorate_id) == $governorate->id ? 'selected' : '' }}>
                                    {{ $governorate->name_ar }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('governorate_id')" />
                    </div>

                    <!-- Experience Years -->
                    <div>
                        {{-- <x-input-label for="experience_years" value="سنوات الخبرة" />
                        <x-text-input id="experience_years" name="experience_years" type="number" class="mt-1 block w-full"
                                     :value="old('experience_years', auth('doctor')->user()->experience_years)" min="0" />
                        <x-input-error class="mt-2" :messages="$errors->get('experience_years')" /> --}}
                    </div>

                    <!-- Specialty -->
                    <div>
                        <x-input-label for="specialty_id" value="التخصص" />
                        <select id="specialty_id" name="specialty_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                       focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty->id }}"
                                        {{ old('specialty_id', auth('doctor')->user()->specialty_id) == $specialty->id ? 'selected' : '' }}>
                                    {{ $specialty->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('specialty_id')" />
                    </div>

                    <!-- Price -->
                    <div>
                        <x-input-label for="price" value="سعر الكشف" />
                        <x-text-input id="price" name="price" type="number" class="mt-1 block w-full"
                                     :value="old('price', auth('doctor')->user()->price)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('price')" />
                    </div>

                    <!-- Description -->
                    <div>
                        <x-input-label for="description" value="نبذة عن الطبيب" />
                        <textarea id="description" name="description"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                         focus:border-indigo-500 focus:ring-indigo-500"
                                  rows="4">{{ old('description', auth('doctor')->user()->description) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <!-- Location -->
                    <!-- Location -->
<div>
    <x-input-label for="address" value="عنوان العيادة" />
    <x-text-input id="address" name="address" type="text" class="mt-1 block w-full"
                  :value="old('address', auth('doctor')->user()->address)" required />
    <x-input-error class="mt-2" :messages="$errors->get('address')" />

    <!-- Map Container -->
    <div id="map" class="mt-4"></div>

    <!-- Hidden Location Inputs -->
    <div class="grid grid-cols-2 gap-4 mt-4">
        <div>
            <x-input-label for="latitude" value="خط العرض" />
            <x-text-input id="latitude" name="latitude" type="text" class="mt-1 block w-full"
                         :value="old('latitude', auth('doctor')->user()->latitude)" required readonly />
        </div>
        <div>
            <x-input-label for="longitude" value="خط الطول" />
            <x-text-input id="longitude" name="longitude" type="text" class="mt-1 block w-full"
                         :value="old('longitude', auth('doctor')->user()->longitude)" required readonly />
        </div>
    </div>

    <!-- Location Controls -->
    <div class="mt-4">
        <button type="button" onclick="getLocation()"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <i class="fas fa-location-arrow mr-2"></i>
            استخدام موقعي الحالي
        </button>
    </div>
</div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>حفظ التغييرات</x-primary-button>

                        @if (session('status') === 'profile-updated')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-gray-600"
                            >تم الحفظ</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('doctors.profile.partials.update-password-form')
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
<style>
    #map {
        height: 400px;
        width: 100%;
        border-radius: 0.5rem;
        margin-top: 1rem;
        z-index: 1;
    }

    .profile-image-container {
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .profile-image-container:hover::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 9999px;
    }

    /* Image preview animation */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
</style>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.js"></script>
<script>
    let map, marker, geocoder;

    function initMap() {
        // Get doctor's current coordinates or default to Cairo
        const lat = {{ auth('doctor')->user()->latitude ?? 30.0444 }};
        const lng = {{ auth('doctor')->user()->longitude ?? 31.2357 }};

        // Initialize map
        map = L.map('map').setView([lat, lng], 13);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add search control
        const geocoder = L.Control.geocoder({
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

        // Add initial marker if coordinates exist
        if (lat && lng) {
            placeMarker({ lat, lng });
        }

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
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${latlng.lat}&lon=${latlng.lng}&format=json`);
            const data = await response.json();
            if (data.display_name) {
                document.getElementById('address').value = data.display_name;
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
                    switch(error.code) {
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
                }
            );
        } else {
            alert("متصفحك لا يدعم تحديد المواقع.");
        }
    }

    // Initialize map when page loads
    document.addEventListener('DOMContentLoaded', initMap);



    function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const file = input.files[0];

    if (file) {
        // Check file size (2MB limit)
        if (file.size > 2 * 1024 * 1024) {
            alert('حجم الصورة يجب أن لا يتجاوز 2 ميجابايت');
            input.value = '';
            return;
        }

        // Check file type
        if (!file.type.startsWith('image/')) {
            alert('يرجى اختيار ملف صورة صالح');
            input.value = '';
            return;
        }

        const reader = new FileReader();

        reader.onload = function(e) {
            // Create new image element
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-full h-full object-cover';

            // Clear preview div and add new image
            preview.innerHTML = '';
            preview.appendChild(img);

            // Add fade-in animation
            img.style.opacity = '0';
            setTimeout(() => {
                img.style.transition = 'opacity 0.3s ease-in-out';
                img.style.opacity = '1';
            }, 50);
        }

        reader.readAsDataURL(file);
    }
}


const changedFields = new Set();
const form = document.getElementById('profileForm');
const originalValues = {};

// Store original values
document.querySelectorAll('input, select, textarea').forEach(element => {
    if (element.name) {
        originalValues[element.name] = element.value;

        element.addEventListener('change', function() {
            if (this.value !== originalValues[this.name]) {
                changedFields.add(this.name);
            } else {
                changedFields.delete(this.name);
            }
            document.getElementById('changedFields').value = Array.from(changedFields).join(',');
        });
    }
});

// Handle form submission
form.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData();

    // Only append changed fields
    changedFields.forEach(fieldName => {
        const element = form.elements[fieldName];
        if (element.type === 'file' && element.files.length > 0) {
            formData.append(fieldName, element.files[0]);
        } else {
            formData.append(fieldName, element.value);
        }
    });

    // Always append CSRF token and method
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'PUT');

    // Submit the form with only changed fields
    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
</script>
@endsection
