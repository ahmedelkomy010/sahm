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

                <!-- Warning Message -->
                <div class="bg-yellow-50 border-r-4 border-yellow-400 p-4 mb-6 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="mr-3">
                            <h3 class="text-sm font-medium text-yellow-800 text-right">تنبيه هام</h3>
                            <div class="mt-2 text-sm text-yellow-700 text-right">
                                <p>بعض البيانات لا يمكن تعديلها بعد إنشاء المشروع:</p>
                                <ul class="list-disc list-inside mt-1 mr-4">
                                    <li>رقم العقد</li>
                                    <li>نوع المشروع</li>
                                    <li>موقع المشروع</li>
                                </ul>
                                <p class="mt-1">يرجى التأكد من صحة البيانات قبل إنشاء المشروع.</p>
                            </div>
                        </div>
                    </div>
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
                    <div class="space-y-4">
                        <!-- Confirmation Checkbox -->
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <input type="checkbox" 
                                   id="confirm_warning" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                   required>
                            <label for="confirm_warning" class="text-sm text-gray-700">
                                أقر بأنني قرأت التنبيه وأتفهم أن بعض البيانات لا يمكن تعديلها بعد إنشاء المشروع
                            </label>
                        </div>

                        <div class="flex justify-between items-center">
                            <button type="submit" 
                                    id="submit_button"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform transition hover:scale-105 duration-200">
                                إنشاء المشروع
                            </button>
                            
                            <a href="{{ route('project.type-selection') }}" 
                               class="text-gray-600 hover:text-gray-800 font-medium">
                                العودة للصفحة السابقة
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitButton = document.getElementById('submit_button');
    const confirmCheckbox = document.getElementById('confirm_warning');

    form.addEventListener('submit', function(e) {
        if (!confirmCheckbox.checked) {
            e.preventDefault();
            alert('يرجى تأكيد قراءة التنبيه قبل إنشاء المشروع');
            return false;
        }
    });

    // تحديث حالة الزر بناءً على حالة مربع الاختيار
    confirmCheckbox.addEventListener('change', function() {
        submitButton.disabled = !this.checked;
        if (this.checked) {
            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
    });

    // تعيين الحالة الأولية للزر
    submitButton.disabled = true;
    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
});
</script>
@endsection 