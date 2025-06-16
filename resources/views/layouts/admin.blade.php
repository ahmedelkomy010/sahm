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
    
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    
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
        <!-- Top Navigation -->
        <nav class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-md border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('admin.dashboard') }}" class="flex-shrink-0 flex items-center">
                                <div class="logo-container bg-white rounded-full p-1 mr-2">
                                    <img class="h-10 w-auto" src="{{ asset('images/logo-sahm.svg') }}" alt="Sahm Blady Logo">
                                </div>
                                <span class="mr-3 text-white font-bold text-xl">سهم بلدي</span>
                            </a>
                        </div>
                        
                        <!-- Top Navigation Links -->
                        <div class="hidden md:mr-8 md:flex md:space-x-8 md:space-x-reverse">
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-blue-500">الرئيسية</a>
                            <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-blue-500">المستخدمين</a>
                            @if(request()->routeIs('admin.work-orders.*'))
                            <a href="{{ route('admin.work-orders.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-blue-500">أوامر العمل</a>
                            @endif
                            @can('admin')
                            <a href="{{ route('admin.settings') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-blue-500">الإعدادات</a>
                            @endcan
                        </div>
                    </div>

                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <div class="mr-3 relative flex items-center">
                            <span class="text-white ml-2">{{ auth()->user()->name ?? 'مدير' }}</span>
                            <div class="h-9 w-9 rounded-full bg-white flex items-center justify-center text-indigo-700 shadow-md">
                                {{ substr(auth()->user()->name ?? 'مدير', 0, 1) }}
                            </div>
                            
                            <!-- Logout Button -->
                            <form method="POST" action="{{ route('logout') }}" class="mr-4">
                                @csrf
                                <button type="submit" class="flex items-center text-white text-sm hover:text-indigo-100">
                                    <i class="fas fa-sign-out-alt ml-1"></i> تسجيل الخروج
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button type="button" class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" id="mobile-menu-button" aria-expanded="false">
                            <span class="sr-only">فتح القائمة</span>
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div class="sm:hidden hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-blue-500">الرئيسية</a>
                    <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-blue-500">المستخدمين</a>
                    @if(request()->routeIs('admin.work-orders.*'))
                    <a href="{{ route('admin.work-orders.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-blue-500">أوامر العمل</a>
                    @endif
                    @can('admin')
                    <a href="{{ route('admin.settings') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-blue-500">الإعدادات</a>
                    @endcan
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="block w-full text-right px-3 py-2 rounded-md text-base font-medium text-white hover:bg-blue-500">
                            تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>
        </nav>

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

    <!-- JavaScript to handle mobile menu -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        });
    </script>
    
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