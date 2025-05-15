@php
use Illuminate\Support\Facades\Route;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'سهم بلدي') }}</title>

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

        <!-- Lightbox -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Tajawal', sans-serif;
            }
            .main-header {
                background: linear-gradient(to left, #3b82f6, #1e40af);
                color: white;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
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
            .nav-item {
                padding: 0.5rem 1rem;
                color: rgba(255, 255, 255, 0.9);
                transition: all 0.2s ease;
            }
            .nav-item:hover {
                color: white;
                background-color: rgba(255, 255, 255, 0.1);
                border-radius: 0.25rem;
            }
            .nav-item.active {
                color: white;
                font-weight: 600;
                border-bottom: 2px solid white;
            }
            .user-menu {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: white;
                font-size: 0.875rem;
            }
            .dropdown-toggle {
                cursor: pointer;
            }
            .dropdown-menu {
                min-width: 10rem;
                padding: 0.5rem 0;
                margin: 0.125rem 0 0;
                background-color: white;
                border-radius: 0.25rem;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <!-- Main Header with Navigation -->
        @if (!isset($hideNavbar) || !$hideNavbar)
        <header class="main-header">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center px-4 sm:px-6 lg:px-8 py-2">
                    <!-- Logo and App Name -->
                    <div class="flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center">
                            <span class="text-xl font-bold text-white">سهم بلدي</span>
                        </a>
                    </div>
                    
                    <!-- Main Navigation -->
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            الرئيسية
                        </a>
                        
                        @if(request()->routeIs('admin.work-orders.*') || session('project'))
                        <a href="{{ route('admin.work-orders.index') }}" class="nav-item {{ request()->routeIs('admin.work-orders.*') ? 'active' : '' }}">
                            أوامر العمل
                        </a>
                        @endif
                        
                        @can('admin')
                        <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            إدارة المستخدمين
                        </a>
                        <a href="{{ route('admin.settings') }}" class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                            الإعدادات
                        </a>
                        @endcan
                    </div>
                    
                    <!-- User Menu -->
                    @auth
                    <div class="flex items-center">
                        <div class="relative" x-data="{ open: false }">
                            <div class="user-menu" @click="open = !open">
                                <span><i class="fas fa-user-circle mr-1"></i>{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                            
                            <div x-show="open" 
                                 @click.away="open = false"
                                 class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 style="display: none;">
                                
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    حسابي
                                </a>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endauth
                    
                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button 
                            x-data="{ open: false }"
                            @click="open = !open"
                            class="text-white hover:text-gray-300 focus:outline-none">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path 
                                    :class="{'hidden': open, 'inline-flex': !open}" 
                                    class="inline-flex" 
                                    stroke-linecap="round" 
                                    stroke-linejoin="round" 
                                    stroke-width="2" 
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path 
                                    :class="{'hidden': !open, 'inline-flex': open}" 
                                    class="hidden" 
                                    stroke-linecap="round" 
                                    stroke-linejoin="round" 
                                    stroke-width="2" 
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </header>
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
        
        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- jQuery (if needed) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <!-- Alpine.js for dropdown functionality -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Additional Scripts -->
        @stack('scripts')

        <!-- Lightbox -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
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
        </script>
    </body>
</html>
