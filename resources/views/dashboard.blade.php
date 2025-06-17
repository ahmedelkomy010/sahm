<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            الرئيسية
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-2">مرحباً {{ Auth::user()->name }}</h3>
                    <p class="text-gray-600">مرحباً بك في نظام إدارة شركة سهم بلدي للمقاولات</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Users Management Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users-cog text-3xl text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">إدارة المستخدمين</h4>
                                <p class="text-sm text-gray-600">إدارة المستخدمين والصلاحيات</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.users.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <i class="fas fa-arrow-left ml-2"></i>
                                إدارة المستخدمين
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Work Orders Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clipboard-list text-3xl text-green-600"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">أوامر العمل</h4>
                                <p class="text-sm text-gray-600">إدارة ومتابعة أوامر العمل</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('project.selection') }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <i class="fas fa-arrow-left ml-2"></i>
                                عرض أوامر العمل
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Profile Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-circle text-3xl text-purple-600"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">الملف الشخصي</h4>
                                <p class="text-sm text-gray-600">إدارة بيانات الحساب الشخصي</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('profile.edit') }}" 
                               class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <i class="fas fa-arrow-left ml-2"></i>
                                تعديل الملف الشخصي
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
