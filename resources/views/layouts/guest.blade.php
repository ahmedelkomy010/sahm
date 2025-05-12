<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'سهم بلدي') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Tajawal', sans-serif;
                background-color: #f5f7fa;
            }
            .login-container {
                background-image: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(37, 99, 235, 0.1) 100%);
                min-height: 100vh;
            }
            .login-card {
                background-color: white;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
                border-radius: 0.75rem;
            }
            .login-header {
                background: linear-gradient(to left, #3b82f6, #1e40af);
                color: white;
                padding: 1rem;
                border-radius: 0.5rem 0.5rem 0 0;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="login-container flex flex-col min-h-screen justify-center items-center pt-6 sm:pt-0">
            <div class="w-full sm:max-w-md px-6 py-0 overflow-hidden">
                <div class="login-header mb-0">
                    <div class="flex items-center justify-center py-2">
                        <span class="text-2xl font-bold">سهم بلدي</span>
                    </div>
                </div>
                
                <div class="login-card px-6 py-6">
                    {{ $slot }}
                </div>
                
                <div class="mt-4 text-center text-sm text-gray-600">
                    جميع الحقوق محفوظة &copy; {{ date('Y') }} سهم بلدي
                </div>
            </div>
        </div>
    </body>
</html>
