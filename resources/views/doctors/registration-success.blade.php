<!-- resources/views/doctors/registration-success.blade.php -->

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>

                    <h2 class="mt-4 text-2xl font-bold text-gray-900">
                        شكراً لتسجيلك معنا!
                    </h2>

                    <p class="mt-2 text-gray-600">
                        تم استلام طلبك بنجاح وسيتم مراجعته من قبل الإدارة.
                        سنقوم بإخطارك عبر البريد الإلكتروني بمجرد الموافقة على طلبك.
                    </p>

                    <div class="mt-6">
                        <a href="{{ route('home') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            العودة إلى الصفحة الرئيسية
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
