@extends('layouts.admin')

@section('title', 'لوحة تحكم الرخص')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- رأس الصفحة -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.work-orders.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 flex items-center">
                <i class="fas fa-arrow-right ml-2"></i>
                العودة لأوامر العمل
            </a>
            <h1 class="text-2xl font-bold text-gray-800">لوحة تحكم الرخص</h1>
        </div>
    </div>

    <!-- فلتر التواريخ وحالة الرخصة -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form action="{{ route('admin.licenses.display') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">تاريخ البداية</label>
                <input type="date" id="start_date" name="start_date" 
                       value="{{ request('start_date') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">تاريخ النهاية</label>
                <input type="date" id="end_date" name="end_date" 
                       value="{{ request('end_date') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">حالة الرخصة</label>
                <select id="status" name="status" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">جميع الحالات</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>سارية</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>منتهية</option>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    <i class="fas fa-search ml-2"></i>
                    عرض النتائج
                </button>
            </div>
        </form>
    </div>

    <!-- لوحة الإحصائيات -->
    <div class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- إجمالي قيمة الرخص -->
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">إجمالي قيمة الرخص</p>
                        <h3 class="text-2xl font-bold text-blue-600">{{ number_format($totalLicenseValue ?? 0, 2) }} ريال</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-money-bill text-xl text-blue-500"></i>
                    </div>
                </div>
            </div>

            <!-- إجمالي قيمة التمديدات -->
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">إجمالي قيمة التمديدات</p>
                        <h3 class="text-2xl font-bold text-indigo-600">{{ number_format($totalExtensionValue ?? 0, 2) }} ريال</h3>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-xl text-indigo-500"></i>
                    </div>
                </div>
            </div>

            <!-- الرخص السارية -->
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">الرخص السارية</p>
                        <h3 class="text-2xl font-bold text-green-600">{{ $activeCount ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-xl text-green-500"></i>
                    </div>
                </div>
            </div>

            <!-- الرخص المنتهية -->
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">الرخص المنتهية</p>
                        <h3 class="text-2xl font-bold text-red-600">{{ $expiredCount ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-times-circle text-xl text-red-500"></i>
                    </div>
                </div>
            </div>

            <!-- إجمالي المخالفات -->
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">إجمالي المخالفات</p>
                        <h3 class="text-2xl font-bold text-orange-600">{{ $violationsCount ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-xl text-orange-500"></i>
                    </div>
                </div>
            </div>

            <!-- إجمالي التمديدات -->
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">إجمالي التمديدات</p>
                        <h3 class="text-2xl font-bold text-yellow-600">{{ $extensionsCount ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-plus text-xl text-yellow-500"></i>
                    </div>
                </div>
            </div>

            <!-- الفحوصات الناجحة -->
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">الفحوصات الناجحة</p>
                        <h3 class="text-2xl font-bold text-emerald-600">{{ $passedTestsCount ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-vial text-xl text-emerald-500"></i>
                    </div>
                </div>
            </div>

            <!-- الفحوصات الراسبة -->
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">الفحوصات الراسبة</p>
                        <h3 class="text-2xl font-bold text-pink-600">{{ $failedTestsCount ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-times text-xl text-pink-500"></i>
                    </div>
                </div>
            </div>

            <!-- إجمالي الإخلاء -->
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">إجمالي الإخلاء</p>
                        <h3 class="text-2xl font-bold text-purple-600">{{ $evacuationsCount ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-truck-loading text-xl text-purple-500"></i>
                    </div>
                </div>
            </div>

            <!-- البحث السريع -->
            <div class="bg-white rounded-lg shadow p-4">
                <form action="{{ route('admin.licenses.display') }}" method="GET" class="flex items-center">
                    <input type="text" name="quick_search" placeholder="رقم الرخصة..." 
                           class="flex-1 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg text-sm"
                           value="{{ request('quick_search') }}">
                    <button type="submit" class="mr-2 bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    

        <!-- جدول الرخص -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الرخصة</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع الرخصة</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم أمر العمل</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قيمة الرخصة</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الإصدار</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الانتهاء</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($licenses as $license)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $license->license_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $license->license_type }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $license->workOrder->order_number ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($license->license_value, 2) }} ريال</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $license->issue_date ? $license->issue_date->format('Y/m/d') : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $license->expiry_date ? $license->expiry_date->format('Y/m/d') : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $license->status_color }}">
                                    {{ $license->status_text }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2 gap-2">
                                    <a href="{{ route('admin.licenses.show', $license) }}" class="text-blue-600 hover:text-blue-900">عرض</a>
                                    <a href="{{ route('admin.licenses.edit', $license) }}" class="text-indigo-600 hover:text-indigo-900">تعديل</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                لا توجد رخص مسجلة
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- الترقيم -->
        <div class="mt-4">
            {{ $licenses->links() }}
        </div>
    </div>
</div>
@endsection 