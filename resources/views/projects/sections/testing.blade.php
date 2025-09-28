@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-yellow-50 via-white to-yellow-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="text-right">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">الاختبارات - {{ $project->name }}</h1>
                        <p class="text-gray-600">رقم العقد: {{ $project->contract_number }}</p>
                    </div>
                    <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        العودة للمشروع
                    </a>
                </div>
            </div>
        </div>

        <!-- Testing Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mr-4">اختبارات مجدولة</h3>
                </div>
                <div class="text-2xl font-bold text-yellow-600 mb-1">0</div>
                <div class="text-sm text-gray-600">اختبار في الانتظار</div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mr-4">اختبارات مكتملة</h3>
                </div>
                <div class="text-2xl font-bold text-green-600 mb-1">0</div>
                <div class="text-sm text-gray-600">اختبار مكتمل</div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mr-4">اختبارات فاشلة</h3>
                </div>
                <div class="text-2xl font-bold text-red-600 mb-1">0</div>
                <div class="text-sm text-gray-600">اختبار فاشل</div>
            </div>

        </div>

        <!-- Testing Schedule -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4 text-right">جدول الاختبارات</h2>
                
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">لا توجد اختبارات مجدولة</h3>
                    <p class="text-gray-600">ابدأ بإضافة اختبارات لمتابعة جودة العمل</p>
                    
                    <button class="mt-4 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-6 rounded-lg shadow-sm transition duration-150 ease-in-out">
                        إضافة اختبار جديد
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
