<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">تعديل بيانات الطبيب</h2>

                        <div class="flex space-x-2">
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    إجراءات الطبيب
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="open"
                                     @click.away="open = false"
                                     class="absolute left-0 mt-2 w-56 bg-white rounded-md shadow-lg z-20 border border-gray-200">
                                    <div class="py-1">
                                        <button id="blockDoctorBtn"
                                                class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                            <i class="fas fa-ban mr-2 text-yellow-600"></i>حظر الطبيب
                                        </button>
                                        <button id="deleteDoctorBtn"
                                                class="block w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700">
                                            <i class="fas fa-trash mr-2"></i>حذف الطبيب
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Existing form fields remain the same -->

                            <!-- Add Medical Information Section -->
                            <div class="col-span-1 md:col-span-2 bg-gray-50 p-4 rounded-md">
                                <h3 class="text-lg font-semibold mb-4 text-gray-800">معلومات طبية إضافية</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="medical_license" class="block text-sm font-medium text-gray-700">رقم الترخيص الطبي</label>
                                        <input type="text" name="medical_license" id="medical_license"
                                               value="{{ old('medical_license', $doctor->medical_license ?? '') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label for="graduation_year" class="block text-sm font-medium text-gray-700">سنة التخرج</label>
                                        <input type="number" name="graduation_year" id="graduation_year"
                                               value="{{ old('graduation_year', $doctor->graduation_year ?? '') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label for="consultation_duration" class="block text-sm font-medium text-gray-700">مدة الاستشارة (بالدقائق)</label>
                                        <input type="number" name="consultation_duration" id="consultation_duration"
                                               value="{{ old('consultation_duration', $doctor->consultation_duration ?? 15) }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label for="languages" class="block text-sm font-medium text-gray-700">اللغات</label>
                                        <select name="languages[]" id="languages" multiple
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="ar" {{ in_array('ar', $doctor->languages ?? []) ? 'selected' : '' }}>العربية</option>
                                            <option value="en" {{ in_array('en', $doctor->languages ?? []) ? 'selected' : '' }}>الإنجليزية</option>
                                            <option value="fr" {{ in_array('fr', $doctor->languages ?? []) ? 'selected' : '' }}>الفرنسية</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-between items-center">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600">
                                    تاريخ الانضمام: {{ $doctor->created_at->format('Y-m-d') }}
                                </span>
                                <span class="text-sm text-gray-600">
                                    آخر تحديث: {{ $doctor->updated_at->format('Y-m-d H:i') }}
                                </span>
                            </div>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                حفظ التعديلات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Block Doctor
            document.getElementById('blockDoctorBtn').addEventListener('click', function() {
                if (confirm('هل أنت متأكد من حظر هذا الطبيب؟')) {
                    axios.patch('{{ route("admin.doctors.update.status", $doctor) }}', {
                        status: 'inactive'
                    })
                    .then(response => {
                        alert('تم حظر الطبيب بنجاح');
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error blocking doctor:', error);
                        alert('حدث خطأ أثناء حظر الطبيب');
                    });
                }
            });

            // Delete Doctor
            document.getElementById('deleteDoctorBtn').addEventListener('click', function() {
                if (confirm('هل أنت متأكد من حذف هذا الطبيب؟ لن يمكنك التراجع عن هذا الإجراء.')) {
                    axios.delete('{{ route("admin.doctors.destroy", $doctor) }}')
                    .then(response => {
                        alert('تم حذف الطبيب بنجاح');
                        window.location.href = '{{ route("admin.doctors.index") }}';
                    })
                    .catch(error => {
                        console.error('Error deleting doctor:', error);
                        alert('حدث خطأ أثناء حذف الطبيب');
                    });
                }
            });


        });
    </script>
    @endpush
</x-admin-layout>
