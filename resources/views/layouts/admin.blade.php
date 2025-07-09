<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SahmBalady') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome CSS (CDN stable) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <!-- Custom Toastr RTL CSS -->
    <link rel="stylesheet" href="{{ asset('css/toastr-rtl.css') }}">
    
    <!-- Laboratory Tab Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/laboratory-tab.css') }}">
    
    <!-- Vite Assets (includes Tailwind CSS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            direction: rtl;
            background-color: #f3f4f6;
        }
        
        /* Estilos para el header */
        .nav-link {
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        /* Estilos para el logo */
        .logo-container {
            transition: all 0.3s ease;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        }
        
        .logo-container:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
        }
        
        /* Estilos para tarjetas de proyecto */
        .project-card {
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .project-card .icon-container {
            transition: all 0.3s ease;
        }
        
        .project-card:hover .icon-container {
            transform: scale(1.1);
        }
        
        .project-card .btn {
            transition: all 0.3s ease;
        }
        
        .project-card:hover .btn {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* Estilos para las tarjetas y contenedores */
        .card {
            border-radius: 0.5rem;
            overflow: hidden;
            transition: box-shadow 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        /* Estilos para botones y acciones */
        .btn-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        
        /* Estilos para tablas */
        .table th {
            background-color: #f9fafb;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .table td {
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }
        
        /* Estilos para paginación */
        .pagination {
            display: flex;
            justify-content: center;
            padding-top: 1rem;
        }
        
        .page-link {
            color: #3b82f6;
            background-color: white;
            border: 1px solid #e5e7eb;
            padding: 0.5rem 0.75rem;
            margin: 0 0.25rem;
            border-radius: 0.25rem;
            transition: all 0.2s ease;
        }
        
        .page-link:hover {
            background-color: #f3f4f6;
            border-color: #d1d5db;
        }
        
        .active .page-link {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen bg-gray-100">
        <!-- استخدام الهيدر الموحد -->
        <x-main-header />



        <!-- Main Content Container -->
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Page Heading -->
                <header class="bg-white shadow-sm rounded-lg mb-6">
                    <div class="py-4 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                        <h1 class="text-xl font-bold text-gray-900">
                            @yield('header')
                        </h1>
                        
                        <!-- Page-specific actions can go here -->
                        @yield('header_actions')
                    </div>
                </header>

                <!-- Page Content -->
                <main class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div class="bg-green-100 border-r-4 border-green-500 text-green-700 px-4 py-3 mb-4">
                            <div class="flex">
                                <div class="py-1"><svg class="fill-current h-6 w-6 text-green-500 ml-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM6.7 9.29L9 11.6l4.3-4.3 1.4 1.42L9 14.4l-3.7-3.7 1.4-1.42z"/></svg></div>
                                <div>
                                    <p class="font-bold">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border-r-4 border-red-500 text-red-700 px-4 py-3 mb-4">
                            <div class="flex">
                                <div class="py-1"><svg class="fill-current h-6 w-6 text-red-500 ml-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM10 13.41l2.83 2.83 1.41-1.41L11.41 12l2.83-2.83-1.41-1.41L10 10.59 7.17 7.76 5.76 9.17 8.59 12l-2.83 2.83 1.41 1.41L10 13.41z"/></svg></div>
                                <div>
                                    <p class="font-bold">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Main Content -->
                    <div class="p-6">
                        @yield('content')
                    </div>
                </main>
                
                <!-- Footer -->
                <footer class="mt-8 text-center text-sm text-gray-500">
                    <p>© {{ date('Y') }} سهم بلدي. جميع الحقوق محفوظة.</p>
                </footer>
            </div>
        </div>
    </div>


    
    <!-- Stack للسكريبتات -->
    @stack('scripts')

    <script>
    // Add CSRF token to all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // Configure Toastr options
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "rtl": true
    };
    </script>
</body>
</html> 