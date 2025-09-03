@extends('layouts.admin')

@section('title', 'تفاصيل المستخدم')

@section('header', 'تفاصيل المستخدم')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-medium text-gray-900">
            بيانات المستخدم: {{ $user->name }}
        </h2>
        <div class="flex space-x-2 space-x-reverse">
            @can('admin')
            <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                تعديل
            </a>
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    حذف
                </button>
            </form>
            @endcan
        </div>
    </div>
    
    <!-- User Avatar -->
    <div class="flex justify-center mb-6">
        @if($user->avatar)
            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover border-4 border-blue-200">
        @else
            <div class="w-32 h-32 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 text-4xl border-4 border-blue-200">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
        <div class="flex flex-col">
            <span class="text-sm text-gray-500">الاسم</span>
            <span class="mt-1 text-base font-medium text-gray-900">{{ $user->name }}</span>
        </div>
        
        <div class="flex flex-col">
            <span class="text-sm text-gray-500">البريد الإلكتروني</span>
            <p class="mt-1 text-gray-600">{{ $user->email }}</p>
        </div>

        <div class="flex flex-col">
            <span class="text-sm text-gray-500">رقم الهاتف</span>
            <p class="mt-1 text-gray-600">{{ $user->phone ?: 'غير محدد' }}</p>
        </div>

        <div class="flex flex-col">
            <span class="text-sm text-gray-500">المسمى الوظيفي</span>
            <p class="mt-1 text-gray-600">{{ $user->job_title ?: 'غير محدد' }}</p>
        </div>
        
        <div class="flex flex-col">
            <span class="text-sm text-gray-500">تاريخ التسجيل</span>
            <span class="mt-1 text-base font-medium text-gray-900">{{ $user->created_at->format('Y-m-d H:i') }}</span>
        </div>
        
        <div class="flex flex-col">
            <span class="text-sm text-gray-500">آخر تحديث</span>
            <span class="mt-1 text-base font-medium text-gray-900">{{ $user->updated_at->format('Y-m-d H:i') }}</span>
        </div>

        <!-- User Role and Permissions -->
        <div class="col-span-2">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">الصلاحيات:</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-600 mb-2">
                    <span class="font-medium">نوع المستخدم:</span> 
                    {{ $user->is_admin ? 'مشرف النظام' : 'مستخدم عادي' }}
                </p>
                @if(!$user->is_admin && is_array($user->permissions) && count($user->permissions) > 0)
                    <div class="mt-2">
                        <span class="font-medium text-gray-700">الصلاحيات المحددة:</span>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($user->permissions as $permission)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ \App\Models\User::PERMISSIONS[$permission] ?? $permission }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="mt-8 border-t border-gray-200 pt-4">
        <div class="flex justify-end">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                العودة للقائمة
            </a>
        </div>
    </div>
</div>
@endsection 