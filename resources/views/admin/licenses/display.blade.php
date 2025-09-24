@extends('layouts.admin')

@section('title', 'لوحة تحكم الجودة والرخص')

@section('styles')
<style>
    .stats-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .stats-section h4 {
        position: relative;
        padding-bottom: 8px;
    }
    
    .stats-section h4::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, #3B82F6, #10B981);
        border-radius: 2px;
    }
    
    .financial-stats .stats-card {
        border-left: 4px solid transparent;
    }
    
    .financial-stats .stats-card:nth-child(1) { border-left-color: #3B82F6; }
    .financial-stats .stats-card:nth-child(2) { border-left-color: #6366F1; }
    .financial-stats .stats-card:nth-child(3) { border-left-color: #EF4444; }
    .financial-stats .stats-card:nth-child(4) { border-left-color: #059669; }
    .financial-stats .stats-card:nth-child(5) { border-left-color: #EC4899; }
    .financial-stats .stats-card:nth-child(6) { border-left-color: #8B5CF6; }
    
    .count-stats .stats-card {
        border-left: 4px solid transparent;
    }
    
    .count-stats .stats-card:nth-child(1) { border-left-color: #10B981; }
    .count-stats .stats-card:nth-child(2) { border-left-color: #EF4444; }
    .count-stats .stats-card:nth-child(3) { border-left-color: #F59E0B; }
    .count-stats .stats-card:nth-child(4) { border-left-color: #EAB308; }
    .count-stats .stats-card:nth-child(5) { border-left-color: #059669; }
    .count-stats .stats-card:nth-child(6) { border-left-color: #EC4899; }
    .count-stats .stats-card:nth-child(7) { border-left-color: #8B5CF6; }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- رأس الصفحة -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex justify-between items-center">
                
                <h1 class="text-2xl font-bold text-gray-800">لوحة تحكم الجودة والرخص</h1>
                <p class="text-sm text-gray-600 mt-1">{{ $projectName ?? 'مشروع الرياض' }}</p>
            <div class="text-right">
            <a href="{{ route('admin.work-orders.index', ['project' => $project]) }}" class="btn btn-success btn-lg">
                    <i class="fas fa-arrow-right me-2"></i>
                    العودة لأوامر العمل
                </a>
            </div>
        </div>
    </div>

    <!-- فلتر التواريخ وحالة الرخصة -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-filter me-2"></i>الفلاتر والبحث
            </h3>
            @if(request()->hasAny(['start_date', 'end_date', 'status', 'quick_search', 'work_order_search']))
                <div class="flex items-center gap-2">
                    <span class="text-sm text-blue-600 bg-blue-100 px-2 py-1 rounded">
                        <i class="fas fa-info-circle me-1"></i>
                        فلاتر نشطة
                    </span>
                    <a href="{{ route('admin.licenses.display', ['project' => $project ?? 'riyadh']) }}" 
                       class="text-sm text-red-600 hover:text-red-800">
                        <i class="fas fa-times me-1"></i>مسح جميع الفلاتر
                    </a>
                </div>
            @endif
        </div>
        
        <form action="{{ route('admin.licenses.display') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <!-- حفظ project parameter -->
            <input type="hidden" name="project" value="{{ $project ?? 'riyadh' }}">
            
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
            <div class="flex-1 min-w-[200px]">
                <label for="work_order_search" class="block text-sm font-medium text-gray-700 mb-1">رقم أمر العمل</label>
                <input type="text" id="work_order_search" name="work_order_search" 
                       value="{{ request('work_order_search') }}"
                       placeholder="ابحث برقم أمر العمل..."
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label for="per_page" class="block text-sm font-medium text-gray-700 mb-1">عدد العناصر في الصفحة</label>
                <select id="per_page" name="per_page" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 عنصر</option>
                    <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100 عنصر</option>
                    <option value="400" {{ request('per_page') == '400' ? 'selected' : '' }}>400 عنصر</option>
                    <option value="700" {{ request('per_page') == '700' ? 'selected' : '' }}>700 عنصر</option>
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
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-chart-bar me-2"></i>الإحصائيات
            </h3>
            @if(request()->hasAny(['start_date', 'end_date', 'status', 'quick_search', 'work_order_search']))
                <span class="text-sm text-green-600 bg-green-100 px-2 py-1 rounded">
                    <i class="fas fa-filter me-1"></i>
                    الإحصائيات مفلترة
                </span>
            @else
                <span class="text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded">
                    <i class="fas fa-chart-pie me-1"></i>
                    جميع البيانات
                </span>
            @endif
        </div>
        
        <!-- الإحصائيات المالية -->
        <div class="mb-6 stats-section">
            <h4 class="text-md font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-dollar-sign me-2 text-green-600"></i>
                الإحصائيات المالية
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 financial-stats">
                <!-- إجمالي قيمة الرخص -->
                <div class="stats-card bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-md p-4 border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-600 mb-1 font-medium">إجمالي قيمة الرخص</p>
                            <h3 class="text-2xl font-bold text-blue-700">{{ number_format($totalLicenseValue ?? 0, 2) }} ريال</h3>
                        </div>
                        <div class="w-12 h-12 bg-blue-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-file-contract text-xl text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <!-- إجمالي قيمة التمديدات -->
                <div class="stats-card bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg shadow-md p-4 border border-indigo-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-indigo-600 mb-1 font-medium">إجمالي قيمة التمديدات</p>
                            <h3 class="text-2xl font-bold text-indigo-700">{{ number_format($totalExtensionValue ?? 0, 2) }} ريال</h3>
                        </div>
                        <div class="w-12 h-12 bg-indigo-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-xl text-indigo-600"></i>
                        </div>
                    </div>
                </div>

                <!-- إجمالي قيمة المخالفات -->
                <div class="stats-card bg-gradient-to-br from-red-50 to-red-100 rounded-lg shadow-md p-4 border border-red-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-red-600 mb-1 font-medium">إجمالي قيمة المخالفات</p>
                            <h3 class="text-2xl font-bold text-red-700">{{ number_format($totalViolationsValue ?? 0, 2) }} ريال</h3>
                        </div>
                        <div class="w-12 h-12 bg-red-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-xl text-red-600"></i>
                        </div>
                    </div>
                </div>

                <!-- إجمالي قيمة الاختبارات الناجحة -->
                <div class="stats-card bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg shadow-md p-4 border border-emerald-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-emerald-600 mb-1 font-medium">إجمالي قيمة الاختبارات الناجحة</p>
                            <h3 class="text-2xl font-bold text-emerald-700">{{ number_format($totalPassedTestsValue ?? 0, 2) }} ريال</h3>
                        </div>
                        <div class="w-12 h-12 bg-emerald-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-xl text-emerald-600"></i>
                        </div>
                    </div>
                </div>

                <!-- إجمالي قيمة الاختبارات الراسبة -->
                <div class="stats-card bg-gradient-to-br from-pink-50 to-pink-100 rounded-lg shadow-md p-4 border border-pink-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-pink-600 mb-1 font-medium">إجمالي قيمة الاختبارات الراسبة</p>
                            <h3 class="text-2xl font-bold text-pink-700">{{ number_format($totalFailedTestsValue ?? 0, 2) }} ريال</h3>
                        </div>
                        <div class="w-12 h-12 bg-pink-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-times-circle text-xl text-pink-600"></i>
                        </div>
                    </div>
                </div>

                <!-- إجمالي قيمة إخلاءات الرخص -->
                <div class="stats-card bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg shadow-md p-4 border border-purple-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-600 mb-1 font-medium">إجمالي قيمة إخلاءات الرخص</p>
                            <h3 class="text-2xl font-bold text-purple-700">{{ number_format($totalEvacuationLicenseValue ?? 0, 2) }} ريال</h3>
                        </div>
                        <div class="w-12 h-12 bg-purple-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-truck-loading text-xl text-purple-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الإحصائيات العددية -->
        <div class="mb-6 stats-section">
            <h4 class="text-md font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-chart-bar me-2 text-blue-600"></i>
                الإحصائيات العددية
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 count-stats">
                <!-- الرخص السارية -->
                <div class="stats-card bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-md p-4 border border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-600 mb-1 font-medium">الرخص السارية</p>
                            <h3 class="text-2xl font-bold text-green-700">{{ $activeCount ?? 0 }}</h3>
                            <p class="text-xs text-green-500 mt-1">رخصة نشطة</p>
                        </div>
                        <div class="w-12 h-12 bg-green-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-xl text-green-600"></i>
                        </div>
                    </div>
                </div>

                <!-- الرخص المنتهية -->
                <div class="stats-card bg-gradient-to-br from-red-50 to-red-100 rounded-lg shadow-md p-4 border border-red-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-red-600 mb-1 font-medium">الرخص المنتهية</p>
                            <h3 class="text-2xl font-bold text-red-700">{{ $expiredCount ?? 0 }}</h3>
                            <p class="text-xs text-red-500 mt-1">رخصة منتهية</p>
                        </div>
                        <div class="w-12 h-12 bg-red-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-times-circle text-xl text-red-600"></i>
                        </div>
                    </div>
                </div>

                <!-- إجمالي المخالفات -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg shadow-md p-4 border border-orange-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-orange-600 mb-1 font-medium">إجمالي المخالفات</p>
                            <h3 class="text-2xl font-bold text-orange-700">{{ $violationsCount ?? 0 }}</h3>
                            <p class="text-xs text-orange-500 mt-1">مخالفة مسجلة</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-xl text-orange-600"></i>
                        </div>
                    </div>
                </div>

                <!-- إجمالي التمديدات -->
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg shadow-md p-4 border border-yellow-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-yellow-600 mb-1 font-medium">إجمالي التمديدات</p>
                            <h3 class="text-2xl font-bold text-yellow-700">{{ $extensionsCount ?? 0 }}</h3>
                            <p class="text-xs text-yellow-500 mt-1">تمديد مطلوب</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-plus text-xl text-yellow-600"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- البحث السريع -->
        <div class="mb-6">
            <h4 class="text-md font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-search me-2 text-gray-600"></i>
                البحث السريع
            </h4>
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg shadow-md p-4 border border-gray-200">
                <form action="{{ route('admin.licenses.display') }}" method="GET" class="flex items-center">
                    <!-- حفظ المعاملات -->
                    <input type="hidden" name="project" value="{{ $project ?? 'riyadh' }}">
                    @if(request('start_date'))
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                    @endif
                    @if(request('end_date'))
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                    @endif
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    @if(request('work_order_search'))
                        <input type="hidden" name="work_order_search" value="{{ request('work_order_search') }}">
                    @endif
                    @if(request('per_page'))
                        <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                    @endif
                    
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

    

        <!-- معلومات النتائج -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-table me-2"></i>قائمة الرخص
                    </h3>
                    <p class="text-sm text-gray-600">
                        عرض {{ $licenses->firstItem() ?? 0 }} - {{ $licenses->lastItem() ?? 0 }} من أصل {{ $licenses->total() }} رخصة
                    </p>
                </div>
                @if($licenses->total() > 0)
                    <div class="text-sm text-blue-600 bg-blue-50 px-3 py-2 rounded">
                        <i class="fas fa-info-circle me-1"></i>
                        {{ $licenses->count() }} رخصة في الصفحة الحالية
                    </div>
                @endif
            </div>
        </div>

        <!-- جدول الرخص -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
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

        </div>
        
        <!-- الترقيم -->
        <div class="mt-4">
            {{ $licenses->links() }}
        </div>
        </div>
    </div>
</div>
@endsection 