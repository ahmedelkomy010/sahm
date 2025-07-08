@php
use Illuminate\Support\Facades\Route;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>إدارة سهم بلدي</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Additional Styles -->
        @stack('styles')
        @yield('styles')

        <!-- Lightbox -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
        
        <!-- Toastr CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Tajawal', sans-serif;
            }
            .page-header {
                background-color: #f8fafc;
                border-bottom: 1px solid #e5e7eb;
                padding: 1rem 0;
            }
            .header-title {
                font-weight: 700;
                color: #1e3a8a;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div id="app">
            <!-- Main Header with Navigation -->
            @if (!isset($hideNavbar) || !$hideNavbar)
                <x-main-header />
            @endif
            
            <div class="min-h-screen bg-gray-100">
                <!-- Project Context Info (optional) -->
                @if(session('project'))
                <div class="bg-blue-50 p-2 border-b border-blue-100">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center text-sm text-blue-700">
                            <i class="fas fa-info-circle ml-2"></i>
                            <span>أنت تعمل حاليًا على: <strong>مشروع {{ session('project') == 'riyadh' ? 'الرياض' : 'المدينة المنورة' }}</strong></span>
                            <a href="{{ route('project.selection') }}" class="mr-auto text-blue-600 hover:text-blue-800 font-medium">
                                <i class="fas fa-exchange-alt ml-1"></i>
                                تغيير المشروع
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Page Heading -->
                @isset($header)
                    <header class="page-header">
                        <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
                            <h2 class="text-xl header-title">
                                {{ $header }}
                            </h2>
                        </div>
                    </header>
                @else
                    @if(Route::currentRouteName() == 'project.selection')
                    <header class="page-header">
                        <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
                            <h2 class="text-xl header-title">
                                {{ __('العقد الموحد - اختيار المشروع') }}
                            </h2>
                        </div>
                    </header>
                    @endif
                @endisset

                <!-- Page Content -->
                <main>
                    @yield('content')
                </main>
            </div>
        </div>
        
        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Alpine.js for dropdown functionality -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Additional Scripts -->
        @stack('scripts')
        @yield('scripts')

        <!-- Lightbox -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
        
        <!-- Toastr JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'albumLabel': "صورة %1 من %2"
            });
        </script>

        <script>
            // Add CSRF token to all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            // Configure Toastr
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
        </script>
    </body>
</html>
