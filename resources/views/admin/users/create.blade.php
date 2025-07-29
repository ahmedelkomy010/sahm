@extends('layouts.admin')

@section('title', 'إضافة مستخدم جديد')

@section('header', 'إضافة مستخدم جديد')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-2">إضافة مستخدم جديد للنظام</h2>
        <p class="text-gray-600">قم بإدخال بيانات المستخدم الجديد أدناه. جميع الحقول المميزة بـ (*) مطلوبة.</p>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">الاسم الكامل <span class="text-red-600">*</span></label>
                <div class="relative rounded-md">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                        class="pr-10 mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('name') border-red-500 @enderror"
                        placeholder="أدخل الاسم الكامل للمستخدم">
                </div>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني <span class="text-red-600">*</span></label>
                <div class="relative rounded-md">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="pr-10 mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('email') border-red-500 @enderror"
                        placeholder="example@domain.com">
                </div>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">سيستخدم هذا البريد الإلكتروني لتسجيل الدخول وإستعادة كلمة المرور</p>
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور <span class="text-red-600">*</span></label>
                <div class="relative rounded-md">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input type="password" name="password" id="password" required
                        class="pr-10 mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('password') border-red-500 @enderror"
                        placeholder="********">
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">يجب أن تتكون كلمة المرور من 8 أحرف على الأقل</p>
            </div>
            
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">تأكيد كلمة المرور <span class="text-red-600">*</span></label>
                <div class="relative rounded-md">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="pr-10 mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        placeholder="********">
                </div>
                <p class="text-xs text-gray-500 mt-1">أعد إدخال كلمة المرور للتأكيد</p>
            </div>
        </div>

      
        
        
        <!-- صلاحيات المشاريع -->
        <div class="bg-indigo-50 p-4 rounded-lg mb-6">
            <h3 class="text-md font-bold text-indigo-800 mb-2 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h4M7 7h10M7 11h10M7 15h10" />
                </svg>
                صلاحيات الوصول إلى أنواع المشاريع
            </h3>
                
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-3 rounded-lg border border-blue-200">
                    <div class="flex items-start">
                        <input type="checkbox" id="access_unified_contracts" name="permissions[]" value="access_unified_contracts" 
                            {{ (is_array(old('permissions')) && in_array('access_unified_contracts', old('permissions'))) ? 'checked' : '' }}
                            class="h-5 w-5 ml-2 mt-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <div class="mr-2">
                            <label for="access_unified_contracts" class="text-sm font-medium text-blue-800 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                العقد الموحد
                            </label>
                            <p class="text-xs text-blue-600 mt-1">إدارة العقود الموحدة والوثائق الرسمية</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-3 rounded-lg border border-green-200">
                    <div class="flex items-start">
                        <input type="checkbox" id="access_turnkey_projects" name="permissions[]" value="access_turnkey_projects" 
                            {{ (is_array(old('permissions')) && in_array('access_turnkey_projects', old('permissions'))) ? 'checked' : '' }}
                            class="h-5 w-5 ml-2 mt-1 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <div class="mr-2">
                            <label for="access_turnkey_projects" class="text-sm font-medium text-green-800 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                تسليم مفتاح
                            </label>
                            <p class="text-xs text-green-600 mt-1">إدارة مشاريع تسليم المفتاح الجاهزة</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-3 rounded-lg border border-purple-200">
                    <div class="flex items-start">
                        <input type="checkbox" id="access_special_projects" name="permissions[]" value="access_special_projects" 
                            {{ (is_array(old('permissions')) && in_array('access_special_projects', old('permissions'))) ? 'checked' : '' }}
                            class="h-5 w-5 ml-2 mt-1 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <div class="mr-2">
                            <label for="access_special_projects" class="text-sm font-medium text-purple-800 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                مشاريع خاصة
                            </label>
                            <p class="text-xs text-purple-600 mt-1">تنفيذ المشاريع الخاصة والمخصصة</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-3 pt-3 border-t border-indigo-200">
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                    <div class="flex">
                        <svg class="flex-shrink-0 h-5 w-5 text-amber-400 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="mr-3">
                            <h3 class="text-sm font-medium text-amber-800">تنبيه مهم</h3>
                            <div class="mt-2 text-sm text-amber-700">
                                <p>• إذا لم يتم تحديد أي نوع من المشاريع، لن يتمكن المستخدم من الوصول إلى أي مشروع</p>
                                <p>• المستخدم المشرف يمكنه الوصول إلى جميع أنواع المشاريع تلقائياً</p>
                                <p>• يمكن تعديل هذه الصلاحيات لاحقاً من صفحة تعديل المستخدم</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="border-t border-gray-200 pt-6">
            <div class="flex justify-between items-center">
                <button onclick="generateRandomPassword()" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    توليد كلمة مرور عشوائية
                </button>
                
                <div>
                    <button type="button" onclick="resetForm()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        إعادة تعيين
                    </button>
                    <button type="submit" class="mr-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        حفظ المستخدم
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function generateRandomPassword() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
        let password = '';
        for (let i = 0; i < 10; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        
        document.getElementById('password').value = password;
        document.getElementById('password_confirmation').value = password;
        
        // إشعار بكلمة المرور
        alert('تم توليد كلمة مرور عشوائية: ' + password);
    }
    
    function resetForm() {
        // إعادة تعيين جميع حقول النموذج
        document.getElementById('createUserForm').reset();
        
        // تركيز على الحقل الأول
        document.getElementById('name').focus();
        
        // رسالة تأكيد
        const message = document.createElement('div');
        message.className = 'bg-blue-100 border-r-4 border-blue-500 text-blue-700 p-3 rounded mb-4';
        message.innerHTML = '<div class="flex"><div class="py-1"><svg class="fill-current h-6 w-6 text-blue-500 ml-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM6.7 9.29L9 11.6l4.3-4.3 1.4 1.42L9 14.4l-3.7-3.7 1.4-1.42z"/></svg></div><div><p class="font-bold">تم إعادة تعيين النموذج</p><p class="text-sm">يمكنك الآن إدخال بيانات مستخدم جديد</p></div></div>';
        
        // إضافة الرسالة في بداية النموذج
        const form = document.getElementById('createUserForm');
        form.parentNode.insertBefore(message, form);
        
        // إخفاء الرسالة بعد 3 ثواني
        setTimeout(() => {
            message.remove();
        }, 3000);
    }
    
    // تعيين سلوك حقل مشرف النظام
    document.getElementById('is_admin').addEventListener('change', function() {
        const permissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]');
        if (this.checked) {
            // إذا تم تحديد مشرف النظام، فحدد جميع الصلاحيات وعطلها
            permissionCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
                checkbox.disabled = true;
            });
        } else {
            // إذا تم إلغاء تحديد مشرف النظام، فعّل جميع الصلاحيات
            permissionCheckboxes.forEach(checkbox => {
                checkbox.disabled = false;
            });
        }
    });
    
    // تحقق من حالة حقل مشرف النظام عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('is_admin').checked) {
            const permissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]');
            permissionCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
                checkbox.disabled = true;
            });
        }
    });
</script>
@endsection 