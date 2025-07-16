@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">نظام إدارة المشاريع</h1>
            <p class="text-xl text-gray-600">قم بإنشاء وإدارة مشاريعك بكفاءة عالية</p>
        </div>

        <!-- Create New Project Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform hover:scale-105 transition duration-300">
            <div class="p-8">
                <div class="flex flex-col items-center">
                    <!-- Project Icon -->
                    <div class="bg-blue-100 p-4 rounded-full mb-6">
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>

                    <!-- Create Project Button -->
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">إنشاء مشروع جديد</h2>
                    <p class="text-gray-600 text-center mb-8">
                        قم بإنشاء مشروع جديد وابدأ في إدارة أعمالك بشكل فعال
                    </p>
                    
                    <button onclick="window.location.href='{{ route('projects.create') }}'" 
                            class="bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold py-3 px-8 rounded-lg shadow-lg transform transition hover:scale-105 duration-200 flex items-center">
                        <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 4v16m8-8H4"></path>
                        </svg>
                        إنشاء مشروع جديد
                    </button>
                </div>

                <!-- Features List -->
                <div class="mt-12 border-t pt-8">
                    <h3 class="text-xl font-semibold text-gray-900 text-center mb-6">مميزات النظام</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <div class="flex-shrink-0">
                                <div class="bg-green-100 rounded-full p-2">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" 
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" 
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <span class="text-gray-700">إدارة المشاريع بكفاءة</span>
                        </div>

                        <div class="flex items-center space-x-4 space-x-reverse">
                            <div class="flex-shrink-0">
                                <div class="bg-green-100 rounded-full p-2">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" 
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" 
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <span class="text-gray-700">متابعة تقدم العمل</span>
                        </div>

                        <div class="flex items-center space-x-4 space-x-reverse">
                            <div class="flex-shrink-0">
                                <div class="bg-green-100 rounded-full p-2">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" 
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" 
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <span class="text-gray-700">إدارة الموارد والمواد</span>
                        </div>

                        <div class="flex items-center space-x-4 space-x-reverse">
                            <div class="flex-shrink-0">
                                <div class="bg-green-100 rounded-full p-2">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" 
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" 
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <span class="text-gray-700">تقارير تفصيلية</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="fixed bottom-4 left-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg" 
         x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 3000)">
        {{ session('success') }}
    </div>
@endif
@endsection 