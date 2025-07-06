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
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="project-card bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg shadow-lg">
            <a href="{{ route('project.selection') }}" class="block p-6 text-center">
                <div class="icon-container bg-white rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">العقد الموحد</h3>
                <p class="text-blue-100 mb-3">إدارة العقود الموحدة والوثائق الرسمية</p>
                <div class="btn inline-block bg-white text-blue-600 font-bold py-2 px-4 rounded-full hover:bg-blue-100 transition-colors">
                    ابدأ الآن
                </div>
            </a>
        </div>
        
        <div class="project-card bg-gradient-to-br from-green-500 to-green-700 rounded-lg shadow-lg">
            <a href="{{ route('project.type-selection') }}" class="block p-6 text-center">
                <div class="icon-container bg-white rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">تسليم مفتاح</h3>
                <p class="text-green-100 mb-3">إدارة مشاريع تسليم المفتاح الجاهزة</p>
                <div class="btn inline-block bg-white text-green-600 font-bold py-2 px-4 rounded-full hover:bg-green-100 transition-colors">
                    ابدأ الآن
                </div>
            </a>
        </div>
        
        <div class="project-card bg-gradient-to-br from-purple-500 to-purple-700 rounded-lg shadow-lg">
            <a href="#" class="block p-6 text-center">
                <div class="icon-container bg-white rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">مشاريع خاصة</h3>
                <p class="text-purple-100 mb-3">تنفيذ المشاريع الخاصة والمخصصة</p>
                <div class="btn inline-block bg-white text-purple-600 font-bold py-2 px-4 rounded-full hover:bg-purple-100 transition-colors">
                    ابدأ الآن
                </div>
            </a>
        </div>
    </div>
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