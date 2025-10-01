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
        
        <!-- نوع المستخدم -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">نوع المستخدم</h3>
            
            <div class="space-y-4">
                <!-- مشرف النظام -->
                <div class="flex items-start">
                    <input type="radio" id="user_type_admin" name="user_type" value="1" 
                        {{ $user->user_type == 1 ? 'checked' : '' }}
                        class="h-5 w-5 ml-2 mt-1 text-red-600 focus:ring-red-500 border-gray-300">
                    <div class="mr-2">
                        <label for="user_type_admin" class="text-sm font-medium text-red-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            مشرف النظام
                        </label>
                        <p class="text-xs text-red-600 mt-1">المشرف له صلاحية الوصول لجميع أجزاء النظام بدون قيود</p>
                    </div>
                </div>

                <!-- مدير فرع -->
                <div class="flex items-start">
                    <input type="radio" id="user_type_branch" name="user_type" value="2" 
                        {{ $user->user_type == 2 ? 'checked' : '' }}
                        class="h-5 w-5 ml-2 mt-1 text-blue-600 focus:ring-blue-500 border-gray-300">
                    <div class="mr-2">
                        <label for="user_type_branch" class="text-sm font-medium text-blue-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M7 7h10M7 11h10M7 15h10" />
                            </svg>
                            مدير فرع
                        </label>
                        <p class="text-xs text-blue-600 mt-1">مدير الفرع له صلاحية إدارة جميع العمليات في المدينة المحددة</p>
                    </div>
                </div>

                <!-- مستخدم عادي -->
                <div class="flex items-start">
                    <input type="radio" id="user_type_normal" name="user_type" value="0" 
                        {{ $user->user_type == 0 ? 'checked' : '' }}
                        class="h-5 w-5 ml-2 mt-1 text-gray-600 focus:ring-gray-500 border-gray-300">
                    <div class="mr-2">
                        <label for="user_type_normal" class="text-sm font-medium text-gray-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            مستخدم عادي
                        </label>
                        <p class="text-xs text-gray-600 mt-1">المستخدم العادي له صلاحيات محددة حسب الإعدادات</p>
                    </div>
                </div>
            </div>

            <!-- اختيار المدينة لمدير الفرع -->
            <div id="branch_city_section" class="mt-4 pt-4 border-t border-gray-200 {{ $user->user_type == 2 ? '' : 'hidden' }}">
                <h4 class="text-md font-medium text-gray-700 mb-2">اختيار المدينة:</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- الرياض -->
                    <div class="flex items-center">
                        <input type="radio" id="city_riyadh" name="city" value="riyadh" 
                            {{ $user->city == 'riyadh' ? 'checked' : '' }}
                            class="h-4 w-4 ml-2 text-green-600 focus:ring-green-500 border-gray-300">
                        <label for="city_riyadh" class="text-sm text-gray-700 mr-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M7 7h10M7 11h10M7 15h10" />
                            </svg>
                            الرياض
                        </label>
                    </div>

                    <!-- المدينة المنورة -->
                    <div class="flex items-center">
                        <input type="radio" id="city_madinah" name="city" value="madinah" 
                            {{ $user->city == 'madinah' ? 'checked' : '' }}
                            class="h-4 w-4 ml-2 text-blue-600 focus:ring-blue-500 border-gray-300">
                        <label for="city_madinah" class="text-sm text-gray-700 mr-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M7 7h10M7 11h10M7 15h10" />
                            </svg>
                            المدينة المنورة
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- صلاحيات المستخدم -->
        <div class="mt-3 pt-3 border-t border-gray-200">
            <p class="text-xs text-gray-500">ملاحظة: المستخدم المشرف سيكون لديه جميع الصلاحيات تلقائياً بغض النظر عن الخيارات المحددة</p>
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
                    
                    <!-- الصلاحيات الفرعية للعقد الموحد -->
                    <div id="unified_contracts_cities" class="mt-3 pr-7 border-r-2 border-blue-200 {{ (is_array($user->permissions) && in_array('access_unified_contracts', $user->permissions)) ? '' : 'hidden' }}">
                        <div class="text-xs text-blue-700 font-medium mb-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            اختيار المدن المسموحة:
                        </div>
                        
                        <div class="grid grid-cols-1 gap-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="access_riyadh_contracts" name="permissions[]" value="access_riyadh_contracts" 
                                    {{ (is_array($user->permissions) && in_array('access_riyadh_contracts', $user->permissions)) ? 'checked' : '' }}
                                    class="h-4 w-4 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="access_riyadh_contracts" class="text-xs text-blue-700 mr-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h4M7 7h10M7 11h10M7 15h10" />
                                    </svg>
                                    عقود الرياض
                                </label>
                            </div>

                            <!-- صلاحيات الرياض التفصيلية -->
                            <div id="riyadh_detailed_permissions" class="mr-6 mt-2 border-r-2 border-blue-100 pr-4 {{ (is_array($user->permissions) && in_array('access_riyadh_contracts', $user->permissions)) ? '' : 'hidden' }}">
                                <div class="text-xs text-blue-600 mb-2">صلاحيات الرياض:</div>
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="riyadh_manage_materials" name="permissions[]" value="riyadh_manage_materials" 
                                            {{ (is_array($user->permissions) && in_array('riyadh_manage_materials', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="riyadh_manage_materials" class="text-xs text-blue-700 mr-2">إدارة المواد</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="riyadh_manage_survey" name="permissions[]" value="riyadh_manage_survey" 
                                            {{ (is_array($user->permissions) && in_array('riyadh_manage_survey', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="riyadh_manage_survey" class="text-xs text-blue-700 mr-2">إدارة المسح</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="riyadh_manage_quality" name="permissions[]" value="riyadh_manage_quality" 
                                            {{ (is_array($user->permissions) && in_array('riyadh_manage_quality', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="riyadh_manage_quality" class="text-xs text-blue-700 mr-2">إدارة الجودة</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="riyadh_manage_execution" name="permissions[]" value="riyadh_manage_execution" 
                                            {{ (is_array($user->permissions) && in_array('riyadh_manage_execution', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="riyadh_manage_execution" class="text-xs text-blue-700 mr-2">إدارة التنفيذ</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="riyadh_manage_post_execution" name="permissions[]" value="riyadh_manage_post_execution" 
                                            {{ (is_array($user->permissions) && in_array('riyadh_manage_post_execution', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="riyadh_manage_post_execution" class="text-xs text-blue-700 mr-2">إدارة إجراءات ما بعد التنفيذ</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="riyadh_manage_safety" name="permissions[]" value="riyadh_manage_safety" 
                                            {{ (is_array($user->permissions) && in_array('riyadh_manage_safety', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="riyadh_manage_safety" class="text-xs text-blue-700 mr-2">إدارة السلامة</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="riyadh_materials_overview" name="permissions[]" value="riyadh_materials_overview" 
                                            {{ (is_array($user->permissions) && in_array('riyadh_materials_overview', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="riyadh_materials_overview" class="text-xs text-blue-700 mr-2">تفاصيل عامة للمواد</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="riyadh_execution_productivity" name="permissions[]" value="riyadh_execution_productivity" 
                                            {{ (is_array($user->permissions) && in_array('riyadh_execution_productivity', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="riyadh_execution_productivity" class="text-xs text-blue-700 mr-2">انتاجية التنفيذ</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="riyadh_license_details" name="permissions[]" value="riyadh_license_details" 
                                            {{ (is_array($user->permissions) && in_array('riyadh_license_details', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="riyadh_license_details" class="text-xs text-blue-700 mr-2">تفاصيل الرخص والجودة</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="riyadh_manage_revenues" name="permissions[]" value="riyadh_manage_revenues" 
                                            {{ (is_array($user->permissions) && in_array('riyadh_manage_revenues', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="riyadh_manage_revenues" class="text-xs text-blue-700 mr-2">إدارة الإيرادات</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="riyadh_create_work_order" name="permissions[]" value="riyadh_create_work_order" 
                                            {{ (is_array($user->permissions) && in_array('riyadh_create_work_order', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="riyadh_create_work_order" class="text-xs text-blue-700 mr-2">إنشاء أمر عمل</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="riyadh_edit_work_order" name="permissions[]" value="riyadh_edit_work_order" 
                                            {{ (is_array($user->permissions) && in_array('riyadh_edit_work_order', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="riyadh_edit_work_order" class="text-xs text-blue-700 mr-2">تعديل أمر عمل</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="riyadh_export_excel" name="permissions[]" value="riyadh_export_excel" 
                                            {{ (is_array($user->permissions) && in_array('riyadh_export_excel', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="riyadh_export_excel" class="text-xs text-blue-700 mr-2">تصدير إكسل</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center mt-3">
                                <input type="checkbox" id="access_madinah_contracts" name="permissions[]" value="access_madinah_contracts" 
                                    {{ (is_array($user->permissions) && in_array('access_madinah_contracts', $user->permissions)) ? 'checked' : '' }}
                                    class="h-4 w-4 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="access_madinah_contracts" class="text-xs text-blue-700 mr-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h4M7 7h10M7 11h10M7 15h10" />
                                    </svg>
                                    عقود المدينة المنورة
                                </label>
                            </div>

                            <!-- صلاحيات المدينة التفصيلية -->
                            <div id="madinah_detailed_permissions" class="mr-6 mt-2 border-r-2 border-blue-100 pr-4 {{ (is_array($user->permissions) && in_array('access_madinah_contracts', $user->permissions)) ? '' : 'hidden' }}">
                                <div class="text-xs text-blue-600 mb-2">صلاحيات المدينة المنورة:</div>
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="madinah_manage_materials" name="permissions[]" value="madinah_manage_materials" 
                                            {{ (is_array($user->permissions) && in_array('madinah_manage_materials', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="madinah_manage_materials" class="text-xs text-blue-700 mr-2">إدارة المواد</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="madinah_manage_survey" name="permissions[]" value="madinah_manage_survey" 
                                            {{ (is_array($user->permissions) && in_array('madinah_manage_survey', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="madinah_manage_survey" class="text-xs text-blue-700 mr-2">إدارة المسح</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="madinah_manage_quality" name="permissions[]" value="madinah_manage_quality" 
                                            {{ (is_array($user->permissions) && in_array('madinah_manage_quality', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="madinah_manage_quality" class="text-xs text-blue-700 mr-2">إدارة الجودة</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="madinah_manage_execution" name="permissions[]" value="madinah_manage_execution" 
                                            {{ (is_array($user->permissions) && in_array('madinah_manage_execution', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="madinah_manage_execution" class="text-xs text-blue-700 mr-2">إدارة التنفيذ</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="madinah_manage_post_execution" name="permissions[]" value="madinah_manage_post_execution" 
                                            {{ (is_array($user->permissions) && in_array('madinah_manage_post_execution', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="madinah_manage_post_execution" class="text-xs text-blue-700 mr-2">إدارة إجراءات ما بعد التنفيذ</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="madinah_manage_safety" name="permissions[]" value="madinah_manage_safety" 
                                            {{ (is_array($user->permissions) && in_array('madinah_manage_safety', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="madinah_manage_safety" class="text-xs text-blue-700 mr-2">إدارة السلامة</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="madinah_materials_overview" name="permissions[]" value="madinah_materials_overview" 
                                            {{ (is_array($user->permissions) && in_array('madinah_materials_overview', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="madinah_materials_overview" class="text-xs text-blue-700 mr-2">تفاصيل عامة للمواد</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="madinah_execution_productivity" name="permissions[]" value="madinah_execution_productivity" 
                                            {{ (is_array($user->permissions) && in_array('madinah_execution_productivity', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="madinah_execution_productivity" class="text-xs text-blue-700 mr-2">انتاجية التنفيذ</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="madinah_license_details" name="permissions[]" value="madinah_license_details" 
                                            {{ (is_array($user->permissions) && in_array('madinah_license_details', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="madinah_license_details" class="text-xs text-blue-700 mr-2">تفاصيل الرخص والجودة </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="madinah_manage_revenues" name="permissions[]" value="madinah_manage_revenues" 
                                            {{ (is_array($user->permissions) && in_array('madinah_manage_revenues', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="madinah_manage_revenues" class="text-xs text-blue-700 mr-2">إدارة الإيرادات</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="madinah_create_work_order" name="permissions[]" value="madinah_create_work_order" 
                                            {{ (is_array($user->permissions) && in_array('madinah_create_work_order', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="madinah_create_work_order" class="text-xs text-blue-700 mr-2">إنشاء أمر عمل</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="madinah_edit_work_order" name="permissions[]" value="madinah_edit_work_order" 
                                            {{ (is_array($user->permissions) && in_array('madinah_edit_work_order', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="madinah_edit_work_order" class="text-xs text-blue-700 mr-2">تعديل أمر عمل</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="madinah_export_excel" name="permissions[]" value="madinah_export_excel" 
                                            {{ (is_array($user->permissions) && in_array('madinah_export_excel', $user->permissions)) ? 'checked' : '' }}
                                            class="h-3 w-3 ml-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="madinah_export_excel" class="text-xs text-blue-700 mr-2">تصدير إكسل</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-2 p-2 bg-blue-50 rounded text-xs text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            يجب اختيار مدينة واحدة على الأقل للوصول إلى عقود المدينة المحددة
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
    // تعيين سلوك حقل العقد الموحد لإظهار/إخفاء صلاحيات المدن
    document.getElementById('access_unified_contracts').addEventListener('change', function() {
        const citiesDiv = document.getElementById('unified_contracts_cities');
        const riyadhCheckbox = document.getElementById('access_riyadh_contracts');
        const madinahCheckbox = document.getElementById('access_madinah_contracts');
        const riyadhDetailedDiv = document.getElementById('riyadh_detailed_permissions');
        const madinahDetailedDiv = document.getElementById('madinah_detailed_permissions');
        
        if (this.checked) {
            citiesDiv.classList.remove('hidden');
        } else {
            citiesDiv.classList.add('hidden');
            // إلغاء تحديد صلاحيات المدن عند إلغاء تحديد العقد الموحد
            riyadhCheckbox.checked = false;
            madinahCheckbox.checked = false;
            riyadhDetailedDiv.classList.add('hidden');
            madinahDetailedDiv.classList.add('hidden');
            
            // إلغاء تحديد جميع الصلاحيات التفصيلية
            document.querySelectorAll('[id^="riyadh_"], [id^="madinah_"]').forEach(checkbox => {
                if (checkbox.id !== 'riyadh_detailed_permissions' && checkbox.id !== 'madinah_detailed_permissions') {
                    checkbox.checked = false;
                }
            });
        }
    });

    // تعيين سلوك صلاحيات الرياض
    document.getElementById('access_riyadh_contracts').addEventListener('change', function() {
        const riyadhDetailedDiv = document.getElementById('riyadh_detailed_permissions');
        const riyadhPermissions = document.querySelectorAll('[id^="riyadh_"]:not([id="riyadh_detailed_permissions"])');
        
        if (this.checked) {
            riyadhDetailedDiv.classList.remove('hidden');
        } else {
            riyadhDetailedDiv.classList.add('hidden');
            // إلغاء تحديد جميع صلاحيات الرياض
            riyadhPermissions.forEach(checkbox => {
                checkbox.checked = false;
            });
        }
    });

    // تعيين سلوك صلاحيات المدينة
    document.getElementById('access_madinah_contracts').addEventListener('change', function() {
        const madinahDetailedDiv = document.getElementById('madinah_detailed_permissions');
        const madinahPermissions = document.querySelectorAll('[id^="madinah_"]:not([id="madinah_detailed_permissions"])');
        
        if (this.checked) {
            madinahDetailedDiv.classList.remove('hidden');
        } else {
            madinahDetailedDiv.classList.add('hidden');
            // إلغاء تحديد جميع صلاحيات المدينة
            madinahPermissions.forEach(checkbox => {
                checkbox.checked = false;
            });
        }
    });

    // تعيين سلوك نوع المستخدم
    document.querySelectorAll('input[name="user_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const permissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]');
            const citiesDiv = document.getElementById('unified_contracts_cities');
            const riyadhDetailedDiv = document.getElementById('riyadh_detailed_permissions');
            const madinahDetailedDiv = document.getElementById('madinah_detailed_permissions');
            const branchCitySection = document.getElementById('branch_city_section');
            
            if (this.value === '1') { // مشرف النظام
                // تحديد وتعطيل جميع الصلاحيات
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                    checkbox.disabled = true;
                });
                // إظهار صلاحيات المدن وتعطيلها
                citiesDiv.classList.remove('hidden');
                riyadhDetailedDiv.classList.remove('hidden');
                madinahDetailedDiv.classList.remove('hidden');
                // إخفاء قسم اختيار المدينة
                branchCitySection.classList.add('hidden');
            } 
            else if (this.value === '2') { // مدير فرع
                // تفعيل الصلاحيات
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.disabled = false;
                });
                // إظهار قسم اختيار المدينة
                branchCitySection.classList.remove('hidden');
                // تحديد المدينة الأولى افتراضياً إذا لم يتم تحديد مدينة
                if (!document.querySelector('input[name="city"]:checked')) {
                    document.getElementById('city_riyadh').checked = true;
                }
                
                // تحديد العقد الموحد تلقائياً لمدير الفرع
                const unifiedContractsCheckbox = document.getElementById('access_unified_contracts');
                unifiedContractsCheckbox.checked = true;
                citiesDiv.classList.remove('hidden');
                
                // تحديد المدينة المناسبة بناءً على الاختيار
                const selectedCity = document.querySelector('input[name="city"]:checked');
                if (selectedCity) {
                    if (selectedCity.value === 'riyadh') {
                        const riyadhCheckbox = document.getElementById('access_riyadh_contracts');
                        riyadhCheckbox.checked = true;
                        riyadhDetailedDiv.classList.remove('hidden');
                        // تحديد كل صلاحيات الرياض
                        document.querySelectorAll('[id^="riyadh_manage_"]').forEach(checkbox => {
                            checkbox.checked = true;
                        });
                    } else if (selectedCity.value === 'madinah') {
                        const madinahCheckbox = document.getElementById('access_madinah_contracts');
                        madinahCheckbox.checked = true;
                        madinahDetailedDiv.classList.remove('hidden');
                        // تحديد كل صلاحيات المدينة المنورة
                        document.querySelectorAll('[id^="madinah_manage_"]').forEach(checkbox => {
                            checkbox.checked = true;
                        });
                    }
                }
            }
            else { // مستخدم عادي
                // تفعيل الصلاحيات
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.disabled = false;
                });
                // إخفاء قسم اختيار المدينة
                branchCitySection.classList.add('hidden');
                // إخفاء صلاحيات المدن إذا لم يتم تحديد العقد الموحد
                if (!document.getElementById('access_unified_contracts').checked) {
                    citiesDiv.classList.add('hidden');
                    riyadhDetailedDiv.classList.add('hidden');
                    madinahDetailedDiv.classList.add('hidden');
                }
            }
        });
    });

    // تعيين سلوك تغيير المدينة لمدير الفرع
    document.querySelectorAll('input[name="city"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const userType = document.querySelector('input[name="user_type"]:checked');
            if (userType && userType.value === '2') { // مدير فرع فقط
                const citiesDiv = document.getElementById('unified_contracts_cities');
                const riyadhCheckbox = document.getElementById('access_riyadh_contracts');
                const madinahCheckbox = document.getElementById('access_madinah_contracts');
                const riyadhDetailedDiv = document.getElementById('riyadh_detailed_permissions');
                const madinahDetailedDiv = document.getElementById('madinah_detailed_permissions');

                // إلغاء تحديد كل المدن أولاً
                riyadhCheckbox.checked = false;
                madinahCheckbox.checked = false;
                riyadhDetailedDiv.classList.add('hidden');
                madinahDetailedDiv.classList.add('hidden');
                
                // إلغاء تحديد كل الصلاحيات التفصيلية
                document.querySelectorAll('[id^="riyadh_manage_"], [id^="riyadh_materials_"], [id^="riyadh_execution_"], [id^="riyadh_license_"], [id^="riyadh_create_work_order"], [id^="riyadh_edit_work_order"], [id^="riyadh_export_excel"], [id^="madinah_manage_"], [id^="madinah_materials_"], [id^="madinah_execution_"], [id^="madinah_license_"], [id^="madinah_create_work_order"], [id^="madinah_edit_work_order"], [id^="madinah_export_excel"]').forEach(checkbox => {
                    checkbox.checked = false;
                });

                // تحديد المدينة المختارة وصلاحياتها
                if (this.value === 'riyadh') {
                    riyadhCheckbox.checked = true;
                    riyadhDetailedDiv.classList.remove('hidden');
                    // تحديد كل صلاحيات الرياض
                    document.querySelectorAll('[id^="riyadh_manage_"], [id^="riyadh_materials_"], [id^="riyadh_execution_"], [id^="riyadh_license_"], [id^="riyadh_create_work_order"], [id^="riyadh_edit_work_order"], [id^="riyadh_export_excel"]').forEach(checkbox => {
                        checkbox.checked = true;
                    });
                } else if (this.value === 'madinah') {
                    madinahCheckbox.checked = true;
                    madinahDetailedDiv.classList.remove('hidden');
                    // تحديد كل صلاحيات المدينة المنورة
                    document.querySelectorAll('[id^="madinah_manage_"], [id^="madinah_materials_"], [id^="madinah_execution_"], [id^="madinah_license_"], [id^="madinah_create_work_order"], [id^="madinah_edit_work_order"], [id^="madinah_export_excel"]').forEach(checkbox => {
                        checkbox.checked = true;
                    });
                }
            }
        });
    });
    
    // تحقق من حالة الحقول عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        const userType = document.querySelector('input[name="user_type"]:checked');
        const permissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]');
        const citiesDiv = document.getElementById('unified_contracts_cities');
        const riyadhDetailedDiv = document.getElementById('riyadh_detailed_permissions');
        const madinahDetailedDiv = document.getElementById('madinah_detailed_permissions');
        const branchCitySection = document.getElementById('branch_city_section');

        if (userType) {
            if (userType.value === '1') { // مشرف النظام
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                    checkbox.disabled = true;
                });
                citiesDiv.classList.remove('hidden');
                riyadhDetailedDiv.classList.remove('hidden');
                madinahDetailedDiv.classList.remove('hidden');
                branchCitySection.classList.add('hidden');
            } 
            else if (userType.value === '2') { // مدير فرع
                branchCitySection.classList.remove('hidden');
                if (!document.querySelector('input[name="city"]:checked')) {
                    document.getElementById('city_riyadh').checked = true;
                }
            }
        }
        
        // التحقق من حالة العقد الموحد
        if (document.getElementById('access_unified_contracts').checked) {
            document.getElementById('unified_contracts_cities').classList.remove('hidden');
        }
    });
</script>
@endsection 