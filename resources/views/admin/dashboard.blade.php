@extends('layouts.admin')

@section('title', ' SahmBlady')

@section('header')
    @if(isset($project))
        @if($project == 'riyadh')
            مرحبا بكم في مشروع الرياض
        @elseif($project == 'madinah')
            مرحبا بكم في مشروع المدينة المنورة
        @else
            مرحبا بكم
        @endif
    @else
        مرحبا بكم
    @endif
@endsection

@section('content')
<!-- Project Selection Banner -->
@if(isset($project))
<div class="mb-6 bg-{{ $project == 'riyadh' ? 'blue' : 'green' }}-100 border-l-4 border-{{ $project == 'riyadh' ? 'blue' : 'green' }}-500 p-4 rounded shadow">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-{{ $project == 'riyadh' ? 'city' : 'mosque' }} text-{{ $project == 'riyadh' ? 'blue' : 'green' }}-500"></i>
        </div>
        <div class="ml-3">
            <p class="text-sm text-{{ $project == 'riyadh' ? 'blue' : 'green' }}-700">
                أنت تعمل حاليًا على {{ $project == 'riyadh' ? 'مشروع الرياض' : 'مشروع المدينة المنورة' }}
                <a href="{{ route('project.selection') }}" class="font-bold text-{{ $project == 'riyadh' ? 'blue' : 'green' }}-800 hover:underline">تغيير المشروع</a>
            </p>
        </div>
    </div>
</div>
@endif

<!-- Botones de tipo de proyecto -->
<div class="mb-10">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">اختر نوع المشروع</h2>
        <p class="text-gray-600 mt-2">يمكنك اختيار نوع المشروع الذي ترغب في العمل عليه</p>
    </div>
    
    @php
        $allowedProjects = auth()->user()->getAllowedProjectTypes();
    @endphp
    
    @if(count($allowedProjects) > 0)
        <div class="grid grid-cols-1 md:grid-cols-{{ count($allowedProjects) > 2 ? '3' : count($allowedProjects) }} gap-6">
            @foreach($allowedProjects as $key => $project)
                @php
                    $colors = [
                        'blue' => 'from-blue-500 to-blue-700',
                        'green' => 'from-green-500 to-green-700',
                        'purple' => 'from-purple-500 to-purple-700'
                    ];
                    $iconColors = [
                        'blue' => 'text-blue-600',
                        'green' => 'text-green-600',
                        'purple' => 'text-purple-600'
                    ];
                    $textColors = [
                        'blue' => 'text-blue-100',
                        'green' => 'text-green-100',
                        'purple' => 'text-purple-100'
                    ];
                    $btnColors = [
                        'blue' => 'text-blue-600 hover:bg-blue-100',
                        'green' => 'text-green-600 hover:bg-green-100',
                        'purple' => 'text-purple-600 hover:bg-purple-100'
                    ];
                @endphp
                
                <div class="project-card bg-gradient-to-br {{ $colors[$project['color']] }} rounded-lg shadow-lg">
                    <a href="{{ $project['route'] != '#' ? route(str_replace('#', '', $project['route'])) : '#' }}" class="block p-6 text-center">
                        <div class="icon-container bg-white rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4 shadow-md">
                            @if($project['icon'] == 'document')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 {{ $iconColors[$project['color']] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            @elseif($project['icon'] == 'key')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 {{ $iconColors[$project['color']] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            @elseif($project['icon'] == 'briefcase')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 {{ $iconColors[$project['color']] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">{{ $project['name'] }}</h3>
                        <p class="{{ $textColors[$project['color']] }} mb-3">
                            @if($key == 'unified_contracts')
                                إدارة العقود الموحدة والوثائق الرسمية
                            @elseif($key == 'turnkey_projects')
                                إدارة مشاريع تسليم المفتاح الجاهزة
                            @elseif($key == 'special_projects')
                                تنفيذ المشاريع الخاصة والمخصصة
                            @endif
                        </p>
                        <div class="btn inline-block bg-white {{ $btnColors[$project['color']] }} font-bold py-2 px-4 rounded-full transition-colors">
                            ابدأ الآن
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mx-auto max-w-lg">
                <svg class="mx-auto h-12 w-12 text-yellow-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <h3 class="text-lg font-medium text-yellow-800 mb-2">لا توجد صلاحيات للوصول</h3>
                <p class="text-yellow-700">ليس لديك صلاحية للوصول إلى أي من المشاريع. يرجى التواصل مع مشرف النظام لمنحك الصلاحيات المناسبة.</p>
                @if(auth()->user()->isAdmin())
                    <div class="mt-4">
                        <a href="{{ route('admin.users.index') }}" class="inline-block bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors">
                            إدارة صلاحيات المستخدمين
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-indigo-100 p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="rounded-full bg-indigo-200 p-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div class="mr-4">
                <p class="text-sm text-indigo-700 font-medium">إجمالي المستخدمين</p>
                <p class="text-2xl font-bold text-indigo-800">{{ $usersCount ?? '0' }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-purple-100 p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="rounded-full bg-purple-200 p-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                </svg>
            </div>
            <div class="mr-4">
                <p class="text-sm text-purple-700 font-medium">معلومات أخرى</p>
                <p class="text-2xl font-bold text-purple-800">0</p>
            </div>
        </div>
    </div>
    
    <div class="bg-green-100 p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="rounded-full bg-green-200 p-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="mr-4">
                <p class="text-sm text-green-700 font-medium">إحصائيات</p>
                <p class="text-2xl font-bold text-green-800">0</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-8 bg-white p-6 rounded-lg shadow">
    <h2 class="text-lg font-medium text-gray-900 mb-4">آخر المستخدمين المسجلين</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">البريد الإلكتروني</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ التسجيل</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @if(isset($latestUsers) && count($latestUsers) > 0)
                    @foreach($latestUsers as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('Y-m-d') }}
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                            لا يوجد مستخدمين حتى الآن
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection 