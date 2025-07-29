@extends('layouts.admin')

@section('title', 'تعديل بيانات المستخدم')

@section('header', 'تعديل بيانات المستخدم')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required autofocus
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mb-6 border-t border-b border-gray-200 py-4">
            <h3 class="text-base font-medium text-gray-900 mb-4">تغيير كلمة المرور (اختياري)</h3>
            <p class="text-sm text-gray-600 mb-4">اترك الحقول فارغة إذا كنت لا تريد تغيير كلمة المرور</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور الجديدة</label>
                    <input type="password" name="password" id="password"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">تأكيد كلمة المرور الجديدة</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
            </div>
        </div>
        
        <!-- Is Admin -->
        <div class="mb-4 flex items-center">
            <input type="checkbox" id="is_admin" name="is_admin" value="1" class="mr-2 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ $user->is_admin ? 'checked' : '' }}>
            <x-input-label for="is_admin" :value="__('مشرف النظام')" class="mb-0" />
        </div>
        
        <!-- صلاحيات المستخدم -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="text-md font-bold text-gray-700 mb-4">صلاحيات المستخدم في النظام</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" id="can_manage_users" name="permissions[]" value="manage_users" 
                            {{ (is_array($user->permissions) && in_array('manage_users', $user->permissions)) ? 'checked' : '' }}
                            class="h-5 w-5 ml-2 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="can_manage_users" class="text-sm font-medium text-gray-700">إدارة المستخدمين</label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 mr-7">يمكنه عرض وتعديل بيانات المستخدمين</p>
                </div>
                
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" id="can_view_reports" name="permissions[]" value="view_reports" 
                            {{ (is_array($user->permissions) && in_array('view_reports', $user->permissions)) ? 'checked' : '' }}
                            class="h-5 w-5 ml-2 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="can_view_reports" class="text-sm font-medium text-gray-700">عرض التقارير</label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 mr-7">يمكنه عرض تقارير النظام والإحصائيات</p>
                </div>
                
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" id="can_manage_work_orders" name="permissions[]" value="manage_work_orders" 
                            {{ (is_array($user->permissions) && in_array('manage_work_orders', $user->permissions)) ? 'checked' : '' }}
                            class="h-5 w-5 ml-2 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="can_manage_work_orders" class="text-sm font-medium text-gray-700">إدارة أوامر العمل</label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 mr-7">يمكنه إنشاء وتعديل وحذف أوامر العمل</p>
                </div>
                
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" id="can_manage_materials" name="permissions[]" value="manage_materials" 
                            {{ (is_array($user->permissions) && in_array('manage_materials', $user->permissions)) ? 'checked' : '' }}
                            class="h-5 w-5 ml-2 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="can_manage_materials" class="text-sm font-medium text-gray-700">إدارة المواد</label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 mr-7">يمكنه إدارة المواد والمخزون</p>
                </div>
            </div>
            
            <div class="mt-3 pt-3 border-t border-gray-200">
                <p class="text-xs text-gray-500">ملاحظة: المستخدم المشرف سيكون لديه جميع الصلاحيات تلقائياً بغض النظر عن الخيارات المحددة</p>
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
            <p class="text-xs text-indigo-600 mb-4">حدد أنواع المشاريع التي يمكن للمستخدم الوصول إليها والعمل عليها</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-3 rounded-lg border border-blue-200">
                    <div class="flex items-start">
                        <input type="checkbox" id="access_unified_contracts" name="permissions[]" value="access_unified_contracts" 
                            {{ (is_array($user->permissions) && in_array('access_unified_contracts', $user->permissions)) ? 'checked' : '' }}
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
                            {{ (is_array($user->permissions) && in_array('access_turnkey_projects', $user->permissions)) ? 'checked' : '' }}
                            class="h-5 w-5 ml-2 mt-1 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <div class="mr-2">
                            <label for="access_turnkey_projects" class="text-sm font-medium text-green-800 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z" />
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
                            {{ (is_array($user->permissions) && in_array('access_special_projects', $user->permissions)) ? 'checked' : '' }}
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="border-t border-gray-200 pt-4">
            <div class="flex justify-end">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    إلغاء
                </a>
                <button type="submit" class="mr-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    تحديث
                </button>
            </div>
        </div>
    </form>
</div>

<script>
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