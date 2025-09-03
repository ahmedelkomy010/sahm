<?php
$user = auth()->user();
$allowedProjectTypes = $user->getAllowedProjectTypes();
?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('معلومات الصلاحيات') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('عرض صلاحياتك وأنواع المشاريع المتاحة لك في النظام.') }}
        </p>
    </header>

    <div class="mt-6 space-y-6">
        <!-- نوع المستخدم -->
        <div>
            <h3 class="text-md font-medium text-gray-700">نوع المستخدم:</h3>
            <p class="mt-2 text-sm text-gray-600">
                {{ $user->isAdmin() ? 'مشرف النظام (جميع الصلاحيات)' : 'مستخدم عادي' }}
            </p>
        </div>

        <!-- المشاريع المتاحة -->
        <div>
            <h3 class="text-md font-medium text-gray-700">المشاريع المتاحة:</h3>
            <div class="mt-2 grid grid-cols-1 gap-4 sm:grid-cols-2">
                @foreach($allowedProjectTypes as $type)
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-md bg-{{ $type['color'] }}-100 text-{{ $type['color'] }}-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($type['icon'] === 'document')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    @elseif($type['icon'] === 'key')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    @endif
                                </svg>
                            </span>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">{{ $type['name'] }}</h4>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if(!$user->isAdmin())
        <!-- الصلاحيات -->
        <div>
            <h3 class="text-md font-medium text-gray-700">الصلاحيات المتاحة:</h3>
            <div class="mt-2 grid grid-cols-1 gap-2">
                @foreach(\App\Models\User::PERMISSIONS as $permission => $label)
                    @if($user->hasPermission($permission))
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ $label }}
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- صلاحيات المدن -->
        <div>
            <h3 class="text-md font-medium text-gray-700">المدن المتاحة:</h3>
            <div class="mt-2 bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-1 gap-3">
                    @php
                        $hasCityAccess = false;
                    @endphp

                    <!-- الرياض -->
                    @if($user->hasPermission('access_riyadh_contracts'))
                        @php $hasCityAccess = true; @endphp
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-green-100">
                                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">الرياض</p>
                                <p class="text-xs text-gray-500">يمكنك الوصول إلى عقود الرياض</p>
                            </div>
                        </div>
                    @endif

                    <!-- المدينة المنورة -->
                    @if($user->hasPermission('access_madinah_contracts'))
                        @php $hasCityAccess = true; @endphp
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-blue-100">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">المدينة المنورة</p>
                                <p class="text-xs text-gray-500">يمكنك الوصول إلى عقود المدينة المنورة</p>
                            </div>
                        </div>
                    @endif

                    @if(!$hasCityAccess)
                        <div class="text-sm text-gray-500 text-center py-2">
                            لا توجد صلاحيات للوصول إلى أي مدينة
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- إحصائيات -->
        <div>
            <h3 class="text-md font-medium text-gray-700">إحصائيات:</h3>
            <div class="mt-2 text-sm text-gray-600">
                <p>عدد أوامر العمل: {{ $user->workOrders()->count() }}</p>
            </div>
        </div>
    </div>
</section>
