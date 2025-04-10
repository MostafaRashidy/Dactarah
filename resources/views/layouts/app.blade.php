<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="دكترة - المنصة الطبية الأولى للربط بين الأطباء والمرضى">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>دكترة - منصة الأطباء الأولى</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Leaflet Maps -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>


        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ asset('css/join.css') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Additional Styles -->
        @yield('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @auth('admin')
                @include('layouts.admin-navigation')
            @elseauth('doctor')
                @include('layouts.doctor-navigation')
            @else
                @include('layouts.navigation')
            @endauth


            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
        </div>

        <!-- Core Scripts -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <script>
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            axios.defaults.headers.common['Accept'] = 'application/json';
        </script>


        <!-- Additional Scripts -->
        @yield('scripts')
        @stack('scripts')



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
    </body>
</html>
