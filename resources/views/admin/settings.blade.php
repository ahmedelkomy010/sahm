<!-- @extends('layouts.admin')

@section('title', 'الإعدادات')

@section('header', 'إعدادات النظام')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">الإعدادات العامة</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="app_name" class="block text-sm font-medium text-gray-700 mb-1">اسم التطبيق</label>
                    <input type="text" name="app_name" id="app_name" value="{{ old('app_name', config('app.name')) }}"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                
                <div>
                    <label for="app_url" class="block text-sm font-medium text-gray-700 mb-1">رابط التطبيق</label>
                    <input type="text" name="app_url" id="app_url" value="{{ old('app_url', config('app.url')) }}"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <div class="md:col-span-2">
                    <label for="app_description" class="block text-sm font-medium text-gray-700 mb-1">وصف العمل</label>
                    <textarea name="app_description" id="app_description" rows="4"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('app_description', config('app.description')) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="app_comment" class="block text-sm font-medium text-gray-700 mb-1">تعليق على العمل</label>
                    <textarea name="app_comment" id="app_comment" rows="4"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('app_comment', config('app.comment')) }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">يمكنك إضافة تعليقات إضافية أو ملاحظات هنا</p>
                </div>
            </div>
        </div>
        
        <div class="mb-6 border-t border-gray-200 pt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">إعدادات البريد الإلكتروني</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="mail_from_name" class="block text-sm font-medium text-gray-700 mb-1">اسم المرسل</label>
                    <input type="text" name="mail_from_name" id="mail_from_name" value="{{ old('mail_from_name', config('mail.from.name')) }}"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                
                <div>
                    <label for="mail_from_address" class="block text-sm font-medium text-gray-700 mb-1">عنوان البريد الإلكتروني للمرسل</label>
                    <input type="email" name="mail_from_address" id="mail_from_address" value="{{ old('mail_from_address', config('mail.from.address')) }}"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
            </div>
        </div>
        
        <div class="mb-6 border-t border-gray-200 pt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">إعدادات الأمان</h3>
            
            <div class="mb-4">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="app_debug" id="app_debug" {{ config('app.debug') ? 'checked' : '' }}
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    </div>
                    <div class="mr-3">
                        <label for="app_debug" class="font-medium text-gray-700">تفعيل وضع التصحيح (Debug Mode)</label>
                        <p class="text-gray-500 text-sm">هذا الوضع يساعد في تشخيص الأخطاء ولكن يجب إيقافه في الإنتاج</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="border-t border-gray-200 pt-4 flex justify-end">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                حفظ الإعدادات
            </button>
        </div>
    </form>
</div>
@endsection  -->