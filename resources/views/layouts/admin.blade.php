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
    
    <!-- تعطيل اشعارات الرخص CSS -->
    <link rel="stylesheet" href="{{ asset('css/disable-license-notifications.css') }}">
    
    <!-- Custom Toastr RTL CSS -->
    <link rel="stylesheet" href="{{ asset('css/toastr-rtl.css') }}">
    
    <!-- Laboratory Tab Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/laboratory-tab.css') }}">
    
    <!-- Tabs Handler CSS -->
    <link rel="stylesheet" href="{{ asset('css/tabs-handler.css') }}">
    
    <!-- Vite Assets (includes Tailwind CSS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <!-- تعطيل اشعارات الرخص -->
    <script src="{{ asset('js/simple-disable-toastr.js') }}"></script>
    
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

    <!-- Bootstrap Dropdowns Initialization -->
    <script>
    (function() {
        'use strict';
        
        // دالة لتفعيل الـ dropdowns
        function initializeDropdowns() {
            // تأكد من أن Bootstrap موجود
            if (typeof bootstrap === 'undefined' || !bootstrap.Dropdown) {
                console.warn('Bootstrap is not loaded yet, retrying...');
                return false;
            }
            
            console.log('Initializing Bootstrap dropdowns...');
            
            // تفعيل جميع الـ dropdowns
            const dropdownElements = document.querySelectorAll('[data-bs-toggle="dropdown"]');
            console.log(`Found ${dropdownElements.length} dropdown elements`);
            
            let successCount = 0;
            dropdownElements.forEach(function(element) {
                try {
                    // شيل أي instance قديم
                    const existingInstance = bootstrap.Dropdown.getInstance(element);
                    if (existingInstance) {
                        existingInstance.dispose();
                    }
                    // فعّل الـ dropdown
                    new bootstrap.Dropdown(element, {
                        autoClose: true,
                        boundary: 'viewport'
                    });
                    successCount++;
                } catch(e) {
                    console.error('Error initializing dropdown:', e);
                }
            });
            
            console.log(`Successfully initialized ${successCount}/${dropdownElements.length} dropdowns`);
            return true;
        }
        
        // حاول تفعيل الـ dropdowns عند DOMContentLoaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(initializeDropdowns, 100);
            });
        } else {
            setTimeout(initializeDropdowns, 100);
        }
        
        // حاول تاني بعد تحميل الصفحة بالكامل
        window.addEventListener('load', function() {
            setTimeout(initializeDropdowns, 200);
        });
        
        // Fallback: حاول كل شوية لحد ما ينجح
        let retryCount = 0;
        const maxRetries = 10;
        const retryInterval = setInterval(function() {
            if (initializeDropdowns() || retryCount >= maxRetries) {
                clearInterval(retryInterval);
            }
            retryCount++;
        }, 300);
    })();
    </script>

    <!-- تحميل عداد الإشعارات -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateAllBadges(unreadCount) {
            const badges = ['notificationBadge', 'notificationBadgeNav'];
            badges.forEach(badgeId => {
                const badge = document.getElementById(badgeId);
                if (badge) {
                    if (unreadCount > 0) {
                        badge.textContent = unreadCount;
                        badge.style.display = 'block';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            });
        }
        
        // تحميل أول مرة
        fetch('/admin/notifications?limit=1')
            .then(response => response.json())
            .then(data => {
                updateAllBadges(data.unread_count);
            })
            .catch(error => console.error('Error loading notification count:', error));
        
        // تحديث تلقائي كل دقيقة
        setInterval(function() {
            fetch('/admin/notifications?limit=1')
                .then(response => response.json())
                .then(data => {
                    updateAllBadges(data.unread_count);
                })
                .catch(error => console.error('Error updating notification count:', error));
        }, 60000); // كل دقيقة
    });
    </script>

    <script>
    // معالجة الأخطاء العامة
    window.addEventListener('error', function(e) {
        // تجاهل الأخطاء غير المهمة
        if (e.message && (
            e.message.includes('ResizeObserver') || 
            e.message.includes('Non-Error') ||
            e.message.includes('Script error')
        )) {
            e.preventDefault();
            return true;
        }
    });
    
    // التأكد من وجود jQuery و Toastr
    $(document).ready(function() {
        try {
            // Add CSRF token to all AJAX requests
            if (typeof $ !== 'undefined' && $.ajaxSetup) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            }
            
            // Configure Toastr options - تعطيل الاشعارات التلقائية
            if (typeof toastr !== 'undefined') {
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": true,
                    "onclick": null,
                    "showDuration": "0",
                    "hideDuration": "0",
                    "timeOut": "0",
                    "extendedTimeOut": "0",
                    "showEasing": "none",
                    "hideEasing": "none",
                    "showMethod": "none",
                    "hideMethod": "none",
                    "rtl": true
                };
                
                // تعطيل toastr بالكامل للرخص
                if (window.location.pathname.includes('licenses')) {
                    toastr.success = function() { return false; };
                    toastr.error = function() { return false; };
                    toastr.warning = function() { return false; };
                    toastr.info = function() { return false; };
                }
            }
        } catch (error) {
            console.error('Error in settings initialization:', error);
        }
    });
    </script>
</body>
</html> 