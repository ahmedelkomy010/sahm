@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 project-selection-container">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <!-- Dashboard Link -->
        <div class="absolute right-4 top-0">
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded-lg shadow-sm hover:shadow transition-all duration-200">
               <i class="fas fa-home ml-2"></i>
               العودة إلى لوحة التحكم
            </a>
        </div>

        <!-- Header Section -->
        <div class="text-center mb-8 pt-12">
            <h1 class="text-3xl font-bold text-gray-800 mb-3">اختيار المشروع</h1>
            <p class="text-gray-600 text-lg mb-4">الرجاء اختيار المشروع الذي ترغب في العمل عليه</p>
            <div class="flex items-center justify-center">
                <div class="h-1 w-16 bg-blue-500 rounded-full"></div>
                <div class="h-1 w-32 bg-green-500 mx-3 rounded-full"></div>
                <div class="h-1 w-16 bg-blue-500 rounded-full"></div>
            </div>
        </div>

        <!-- Projects Section -->
        <div class="flex flex-col md:flex-row justify-center gap-8 mb-12">
            <!-- Riyadh Project Card -->
            <div class="w-full md:w-1/2 bg-white rounded-xl shadow-md overflow-hidden transform transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border border-gray-200">
                <div class="p-6 bg-gradient-to-b from-blue-50 to-white">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-4 border border-blue-200">
                            <i class="fas fa-city text-blue-500 text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-blue-700"> إدارة مدينة الرياض</h3>
                    </div>
                    <p class="text-gray-600 mb-6"> العقد الموحد رقم 4400015737</p>
                    <a href="{{ route('admin.work-orders.index', ['project' => 'riyadh']) }}" 
                        class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        الدخول إلى مشروع الرياض
                    </a>
                </div>
            </div>

            <!-- Madinah Project Card -->
            <div class="w-full md:w-1/2 bg-white rounded-xl shadow-md overflow-hidden transform transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border border-gray-200">
                <div class="p-6 bg-gradient-to-b from-green-50 to-white">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mr-4 border border-green-200">
                            <i class="fas fa-mosque text-green-500 text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-green-700">إدارة المدينة المنورة</h3>
                    </div>
                    <p class="text-gray-600 mb-6">العقد الموحد رقم 4400019706</p>
                    <a href="{{ route('admin.dashboard', ['project' => 'madinah']) }}" 
                        class="block w-full bg-green-600 hover:bg-green-700 text-white text-center font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        الدخول إلى مشروع المدينة المنورة
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .project-selection-container {
        background-image: linear-gradient(to bottom right, #f9fafb, #f3f4f6);
        min-height: calc(100vh - 120px);
    }
</style>
@endpush 