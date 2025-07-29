<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="flex flex-col items-center justify-center mb-8">
        <div class="relative w-64 h-24 mb-6">
            <div class="absolute inset-0 bg-blue-600 rounded-lg opacity-10 animate-pulse"></div>
            <img src="{{ asset('images/logo-sahm.png') }}" alt="سهم بلدي" class="w-full h-full object-contain relative z-10 animate-fade-in">
        </div>
        <div class="text-center">
            <h1 class="text-2xl font-bold text-blue-700 mb-2">تسجيل الدخول</h1>
            <p class="text-gray-600">أهلاً بك في نظام إدارة سهم بلدي</p>
        </div>
    </div>
    
    <form method="POST" action="{{ url('/') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('البريد الإلكتروني')" class="text-gray-700 font-medium" />
            <x-text-input id="email" class="block mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username"
                placeholder="أدخل البريد الإلكتروني" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('كلمة المرور')" class="text-gray-700 font-medium" />

            <x-text-input id="password" class="block mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition"
                type="password"
                name="password"
                required 
                autocomplete="current-password"
                placeholder="أدخل كلمة المرور" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <span class="mr-2 text-sm text-gray-600">{{ __('تذكرني') }}</span>
            </label>
            
            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:text-blue-800 focus:outline-none focus:underline transition" href="{{ route('password.request') }}">
                    {{ __('نسيت كلمة المرور؟') }}
                </a>
            @endif
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-center font-semibold rounded-md shadow-md hover:shadow-lg transition duration-200">
                {{ __('تسجيل الدخول') }}
            </button>
        </div>
        
        @if (Route::has('register'))
        <div class="text-center mt-4">
            <span class="text-gray-600 text-sm">ليس لديك حساب؟</span>
            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm mr-1">
                {{ __('تسجيل حساب جديد') }}
            </a>
        </div>
        @endif
    </form>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.8s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 0.1;
            }
            50% {
                opacity: 0.2;
            }
        }
    </style>
</x-guest-layout>
