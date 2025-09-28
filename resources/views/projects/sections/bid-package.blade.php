@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-orange-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="text-right">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">حزمة العطاء - {{ $project->name }}</h1>
                        <p class="text-gray-600">رقم العقد: {{ $project->contract_number }}</p>
                    </div>
                    <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
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

        <!-- Bid Package Components -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mr-4">المواصفات الفنية</h3>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">الملفات:</span>
                        <span class="font-medium">0</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">آخر تحديث:</span>
                        <span class="font-medium">-</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mr-4">المخططات</h3>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">الملفات:</span>
                        <span class="font-medium">0</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">آخر تحديث:</span>
                        <span class="font-medium">-</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mr-4">الكميات والأسعار</h3>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">البنود:</span>
                        <span class="font-medium">0</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">القيمة الإجمالية:</span>
                        <span class="font-medium">0 ريال</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bid Package Status -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 text-right">حالة حزمة العطاء</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600 mb-1">0%</div>
                        <div class="text-sm text-blue-700">إعداد الوثائق</div>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-yellow-600 mb-1">0%</div>
                        <div class="text-sm text-yellow-700">المراجعة</div>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-purple-600 mb-1">0%</div>
                        <div class="text-sm text-purple-700">الموافقة</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-green-600 mb-1">0%</div>
                        <div class="text-sm text-green-700">الإصدار النهائي</div>
                    </div>
                </div>

                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-orange-600 h-3 rounded-full" style="width: 0%"></div>
                </div>
                <p class="text-sm text-gray-600 mt-2 text-right">إجمالي التقدم: 0%</p>
            </div>
        </div>

        <!-- Document Upload -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4 text-right">رفع وثائق العطاء</h2>
                
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">لا توجد وثائق عطاء</h3>
                    <p class="text-gray-600">ابدأ برفع وثائق ومستندات العطاء</p>
                    
                    <button class="mt-4 bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-6 rounded-lg shadow-sm transition duration-150 ease-in-out">
                        رفع وثيقة جديدة
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
