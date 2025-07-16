@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">إنشاء مشروع جديد</h1>
                    <p class="text-gray-600">أدخل تفاصيل المشروع الجديد</p>
                </div>

                <form action="{{ route('projects.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Project Name -->
                    <div class="space-y-2">
                        <label for="name" class="block text-right text-sm font-medium text-gray-700">
                            اسم المشروع <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right"
                               placeholder="أدخل اسم المشروع"
                               required>
                        @error('name')
                            <p class="mt-1 text-right text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contract Number -->
                    <div class="space-y-2">
                        <label for="contract_number" class="block text-right text-sm font-medium text-gray-700">
                            رقم العقد <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="contract_number" 
                               id="contract_number"
                               value="{{ old('contract_number') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right"
                               placeholder="مثال: 4400015737"
                               required>
                        @error('contract_number')
                            <p class="mt-1 text-right text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Project Type -->
                    <div class="space-y-2">
                        <label for="project_type" class="block text-right text-sm font-medium text-gray-700">
                            نوع المشروع <span class="text-red-500">*</span>
                        </label>
                        <select name="project_type" 
                                id="project_type" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right"
                                required>
                            <option value="">اختر نوع المشروع</option>
                            <option value="civil" {{ old('project_type') == 'civil' ? 'selected' : '' }}>أعمال مدنية</option>
                            <option value="electrical" {{ old('project_type') == 'electrical' ? 'selected' : '' }}>أعمال كهربائية</option>
                            <option value="mixed" {{ old('project_type') == 'mixed' ? 'selected' : '' }}>أعمال مختلطة</option>
                        </select>
                        @error('project_type')
                            <p class="mt-1 text-right text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Project Location -->
                    <div class="space-y-2">
                        <label for="location" class="block text-right text-sm font-medium text-gray-700">
                            موقع المشروع <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="location" 
                               id="location"
                               value="{{ old('location') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right"
                               placeholder="أدخل موقع المشروع"
                               required>
                        @error('location')
                            <p class="mt-1 text-right text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Project Description -->
                    <div class="space-y-2">
                        <label for="description" class="block text-right text-sm font-medium text-gray-700">
                            وصف المشروع
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right"
                                  placeholder="اكتب وصفاً مختصراً للمشروع">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-right text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-between items-center pt-6">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform transition hover:scale-105 duration-200">
                            إنشاء المشروع
                        </button>
                        
                        <a href="{{ route('project.type-selection') }}" 
                           class="text-gray-600 hover:text-gray-800 font-medium">
                            العودة للصفحة السابقة
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 