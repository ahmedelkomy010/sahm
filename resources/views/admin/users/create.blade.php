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

        <!-- صلاحيات المستخدم -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="text-md font-bold text-gray-700 mb-4">صلاحيات المستخدم في النظام</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" id="is_admin" name="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }}
                            class="h-5 w-5 ml-2 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="is_admin" class="text-sm font-medium text-gray-700">مشرف النظام</label>
                        <span class="mr-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">صلاحيات كاملة</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 mr-7">المستخدم المشرف يمكنه إدارة المستخدمين وتنفيذ جميع العمليات في النظام</p>
                </div>
                
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" id="can_manage_users" name="permissions[]" value="manage_users" {{ (is_array(old('permissions')) && in_array('manage_users', old('permissions'))) ? 'checked' : '' }}
                            class="h-5 w-5 ml-2 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="can_manage_users" class="text-sm font-medium text-gray-700">إدارة المستخدمين</label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 mr-7">يمكنه عرض وتعديل بيانات المستخدمين</p>
                </div>
                
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" id="can_view_reports" name="permissions[]" value="view_reports" {{ (is_array(old('permissions')) && in_array('view_reports', old('permissions'))) ? 'checked' : '' }}
                            class="h-5 w-5 ml-2 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="can_view_reports" class="text-sm font-medium text-gray-700">عرض التقارير</label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 mr-7">يمكنه عرض تقارير النظام والإحصائيات</p>
                </div>
                
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" id="can_manage_work_orders" name="permissions[]" value="manage_work_orders" {{ (is_array(old('permissions')) && in_array('manage_work_orders', old('permissions'))) ? 'checked' : '' }}
                            class="h-5 w-5 ml-2 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="can_manage_work_orders" class="text-sm font-medium text-gray-700">إدارة أوامر العمل</label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 mr-7">يمكنه إنشاء وتعديل وحذف أوامر العمل</p>
                </div>
            </div>
            
            <div class="mt-3 pt-3 border-t border-gray-200">
                <p class="text-xs text-gray-500">ملاحظة: المستخدم المشرف سيكون لديه جميع الصلاحيات تلقائياً بغض النظر عن الخيارات المحددة</p>
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