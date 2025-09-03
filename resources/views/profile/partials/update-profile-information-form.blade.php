<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('المعلومات الشخصية') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("تحديث معلومات حسابك الشخصي وبريدك الإلكتروني.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- الاسم -->
        <div>
            <x-input-label for="name" :value="__('الاسم')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" dir="rtl" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- البريد الإلكتروني -->
        <div>
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" dir="rtl" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('بريدك الإلكتروني غير مؤكد.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('اضغط هنا لإعادة إرسال رسالة التأكيد.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('تم إرسال رابط تأكيد جديد إلى بريدك الإلكتروني.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- رقم الهاتف -->
        <div>
            <x-input-label for="phone" :value="__('رقم الهاتف')" />
            <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone', $user->phone ?? '')" dir="rtl" placeholder="05xxxxxxxx" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <!-- المسمى الوظيفي -->
        <div>
            <x-input-label for="job_title" :value="__('المسمى الوظيفي')" />
            <x-text-input id="job_title" name="job_title" type="text" class="mt-1 block w-full" :value="old('job_title', $user->job_title ?? '')" dir="rtl" />
            <x-input-error class="mt-2" :messages="$errors->get('job_title')" />
        </div>

        <!-- الصورة الشخصية -->
        <div>
            <x-input-label for="avatar" :value="__('الصورة الشخصية')" />
            <input type="file" id="avatar" name="avatar" class="mt-1 block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-md file:border-0
                file:text-sm file:font-semibold
                file:bg-blue-50 file:text-blue-700
                hover:file:bg-blue-100" accept="image/*" />
            <p class="mt-1 text-sm text-gray-500">يفضل رفع صورة مربعة بحجم لا يزيد عن 2 ميجابايت</p>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            
            @if($user->avatar)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="الصورة الشخصية" class="w-20 h-20 rounded-full object-cover">
                </div>
            @endif
        </div>

        <!-- أزرار الحفظ -->
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('حفظ التغييرات') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('تم الحفظ.') }}</p>
            @endif
        </div>
    </form>
</section>
