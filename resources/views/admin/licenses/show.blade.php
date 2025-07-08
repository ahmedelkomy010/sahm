@extends('layouts.admin')
@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'تفاصيل الرخصة رقم ' . $license->license_number)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- شهادة التنسيق -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <h3 class="text-xl font-bold ml-2">شهادة التنسيق</h3>
                    <p class="text-lg text-gray-600">رقم الشهادة: {{ $license->coordination_certificate_number ?? 'غير متوفر' }}</p>
                </div>
                @if($license->coordination_certificate_path)
                    <a href="{{ asset('storage/' . $license->coordination_certificate_path) }}" 
                       target="_blank"
                       class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2 rounded-lg flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        تحميل الشهادة
                    </a>
                @endif
            </div>
            @if($license->coordination_certificate_notes)
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-medium mb-2">ملاحظات الشهادة</h4>
                    <p class="text-gray-700 whitespace-pre-line">{{ $license->coordination_certificate_notes }}</p>
                </div>
            @endif
        </div>

        <!-- خطابات التعهدات -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold">خطابات التعهدات</h3>
                @if($license->letters_commitments_file_path)
                    <a href="{{ asset('storage/' . $license->letters_commitments_file_path) }}" 
                       target="_blank"
                       class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2 rounded-lg flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        تحميل الخطابات
                    </a>
                @else
                    <div class="text-gray-500">
                        <svg class="w-5 h-5 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        لم يتم إضافة خطابات
                    </div>
                @endif
            </div>
        </div>

        <!-- معلومات الحظر -->
        <div class="mb-6 bg-gray-50 p-4 rounded-lg">
            <h3 class="text-xl font-bold mb-4">معلومات الحظر</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="mb-2">
                        <span class="font-medium">حالة الحظر:</span>
                        <span class="{{ $license->restriction_status_color }} px-2 py-1 rounded-full text-sm">
                            {{ $license->restriction_status_text }}
                        </span>
                    </p>
                    @if($license->is_restricted)
                        <p class="mb-2">
                            <span class="font-medium">جهة الحظر:</span>
                            <span class="text-gray-700">{{ $license->formatted_restriction_authority }}</span>
                        </p>
                        <p class="mb-2">
                            <span class="font-medium">سبب الحظر:</span>
                            <span class="text-gray-700">{{ $license->formatted_restriction_reason }}</span>
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">تفاصيل رخصة الحفر</h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.licenses.edit', $license) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    تعديل الرخصة
                </a>
            </div>
        </div>

        <!-- معلومات الرخصة الأساسية -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">معلومات الرخصة</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">رقم الرخصة:</span> {{ $license->license_number }}</p>
                    <p><span class="font-medium">نوع الرخصة:</span> {{ $license->license_type }}</p>
                    <p><span class="font-medium">قيمة الرخصة:</span> {{ $license->formatted_value }}</p>
                    <p><span class="font-medium">الحالة:</span> 
                        <span class="px-2 py-1 rounded text-sm" style="background-color: {{ $license->status_color }}">
                            {{ $license->status_text }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">تواريخ الرخصة</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">تاريخ الإصدار:</span> {{ $license->issue_date?->format('Y/m/d') }}</p>
                    <p><span class="font-medium">تاريخ التفعيل:</span> {{ $license->activation_date?->format('Y/m/d') }}</p>
                    <p><span class="font-medium">تاريخ الانتهاء:</span> {{ $license->expiry_date?->format('Y/m/d') }}</p>
                    <p><span class="font-medium">عدد الأيام:</span> {{ $license->license_days }} يوم</p>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">أبعاد الحفر</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">الطول:</span> {{ number_format($license->excavation_length, 2) }} متر</p>
                    <p><span class="font-medium">العرض:</span> {{ number_format($license->excavation_width, 2) }} متر</p>
                    <p><span class="font-medium">العمق:</span> {{ number_format($license->excavation_depth, 2) }} متر</p>
                    <p><span class="font-medium">الأبعاد الكلية:</span> {{ $license->formatted_dimensions }}</p>
                </div>
            </div>
        </div>

        <!-- سجل التمديدات -->
        <div class="bg-gray-50 p-6 rounded-lg mb-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">سجل التمديدات</h3>
                <div class="flex items-center space-x-4">
                    <div class="stats flex space-x-4 ml-4">
                        <div class="stat-item bg-blue-100 text-blue-800 px-4 py-2 rounded-lg">
                            <span class="font-medium">عدد التمديدات:</span>
                            <span class="ml-1">{{ $license->extensions?->count() ?? 0 }}</span>
                        </div>
                        <div class="stat-item bg-green-100 text-green-800 px-4 py-2 rounded-lg">
                            <span class="font-medium">إجمالي أيام التمديد:</span>
                            <span class="ml-1">{{ $license->extensions?->sum('days_count') ?? 0 }} يوم</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($license->extensions && $license->extensions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ التمديد</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عدد الأيام</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ البداية</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ النهاية</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السبب</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المرفقات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($license->extensions as $index => $extension)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $extension->created_at->format('Y/m/d') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $extension->days_count }} يوم</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $extension->start_date?->format('Y/m/d') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $extension->end_date?->format('Y/m/d') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $extension->reason }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($extension->attachments && is_array(json_decode($extension->attachments, true)))
                                            @foreach(json_decode($extension->attachments, true) as $index => $attachment)
                                                <a href="{{ asset('storage/' . $attachment) }}" 
                                                   target="_blank" 
                                                   class="text-blue-600 hover:text-blue-800 flex items-center mb-2">
                                                    <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    مرفق {{ $index + 1 }}
                                                </a>
                                            @endforeach
                                        @else
                                            <span class="text-gray-500">لا توجد مرفقات</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد تمديدات</h3>
                    <p class="mt-1 text-sm text-gray-500">لم يتم إضافة أي تمديدات للرخصة حتى الآن</p>
                </div>
            @endif
        </div>

        <!-- قسم الاختبارات المعملية -->
        <div class="bg-gray-50 p-6 rounded-lg mb-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">المختبر </h3>
                <div class="flex items-center space-x-4">
                    <div class="stats flex space-x-4 ml-4">
                        <div class="stat-item bg-green-100 text-green-800 px-4 py-2 rounded-lg">
                            <span class="font-medium">الناجحة:</span>
                            <span class="ml-1">{{ $license->successful_tests_count ?? 0 }}</span>
                        </div>
                        <div class="stat-item bg-red-100 text-red-800 px-4 py-2 rounded-lg">
                            <span class="font-medium">المرفوضة:</span>
                            <span class="ml-1">{{ $license->failed_tests_count ?? 0 }}</span>
                        </div>
                        <div class="stat-item bg-blue-100 text-blue-800 px-4 py-2 rounded-lg">
                            <span class="font-medium">الإجمالي:</span>
                            <span class="ml-1">{{ $license->total_tests_count ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($license->lab_tests_data)
                @php
                    $testsData = json_decode($license->lab_tests_data, true) ?? [];
                @endphp
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع الاختبار</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عدد النقاط</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السعر</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجمالي</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النتيجة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المرفق</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($testsData as $index => $test)
                                @php
                                    $testName = $test['name'] ?? '-';
                                    $pointsCount = $test['points'] ?? $test['number_of_points'] ?? $test['point_count'] ?? 0;
                                    $price = $test['price'] ?? $test['cost'] ?? 0;
                                    $total = $pointsCount * $price;
                                    $resultStatus = $test['result'] ?? $test['status'] ?? 'pending';
                                    
                                    $resultClasses = [
                                        'pass' => 'bg-green-100 text-green-800',
                                        'fail' => 'bg-red-100 text-red-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800'
                                    ];
                                    $resultText = [
                                        'pass' => 'ناجح',
                                        'fail' => 'راسب',
                                        'pending' => 'معلق'
                                    ];
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $testName }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pointsCount }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($price, 2) }} ريال</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($total, 2) }} ريال</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-sm rounded-full {{ $resultClasses[$resultStatus] ?? $resultClasses['pending'] }}">
                                            {{ $resultText[$resultStatus] ?? 'معلق' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if(isset($test['files']) && is_array($test['files']) && count($test['files']) > 0)
                                            @foreach($test['files'] as $file)
                                                <a href="{{ asset('storage/' . $file) }}" 
                                                   target="_blank" 
                                                   class="text-blue-600 hover:text-blue-800 flex items-center mb-2">
                                                    <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    تحميل المرفق {{ $loop->iteration }}
                                                </a>
                                            @endforeach
                                        @elseif(isset($test['file']) && $test['file'])
                                            <a href="{{ asset('storage/' . $test['file']) }}" 
                                               target="_blank" 
                                               class="text-blue-600 hover:text-blue-800 flex items-center">
                                                <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                تحميل المرفق
                                            </a>
                                        @else
                                            <span class="text-gray-400">لا يوجد مرفق</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50">
                                <td colspan="2" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">إجمالي عدد النقاط</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    @php
                                        $totalPoints = array_reduce($testsData, function($carry, $test) {
                                            return $carry + ($test['points'] ?? $test['number_of_points'] ?? $test['point_count'] ?? 0);
                                        }, 0);
                                    @endphp
                                    {{ $totalPoints }} نقطة
                                </td>
                                <td colspan="4"></td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td colspan="4" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">الإجمالي الكلي</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    @php
                                        $totalAmount = array_reduce($testsData, function($carry, $test) {
                                            $points = $test['points'] ?? $test['number_of_points'] ?? $test['point_count'] ?? 0;
                                            $price = $test['price'] ?? $test['cost'] ?? 0;
                                            return $carry + ($points * $price);
                                        }, 0);
                                    @endphp
                                    {{ number_format($totalAmount, 2) }} ريال
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M12 14h.01M12 16h.01M12 18h.01M12 20h.01M12 22h.01"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد بيانات</h3>
                    <p class="mt-1 text-sm text-gray-500">لم يتم إضافة أي اختبارات معملية بعد</p>
                </div>
            @endif

            <!-- ملخص الاختبارات -->
            @if($license->total_tests_count > 0)
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <h4 class="text-lg font-medium text-gray-800 mb-4">الاختبارات الناجحة</h4>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-3xl font-bold text-green-600">{{ $license->successful_tests_count }}</p>
                                <p class="text-sm text-gray-500">اختبار</p>
                            </div>
                            <div class="text-left">
                                <p class="text-lg font-semibold text-green-600">
                                    {{ number_format($license->successful_tests_amount ?? 0, 2) }} ريال
                                </p>
                                <p class="text-sm text-gray-500">القيمة الإجمالية</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <h4 class="text-lg font-medium text-gray-800 mb-4">الاختبارات المرفوضة</h4>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-3xl font-bold text-red-600">{{ $license->failed_tests_count }}</p>
                                <p class="text-sm text-gray-500">اختبار</p>
                            </div>
                            <div class="text-left">
                                <p class="text-lg font-semibold text-red-600">
                                    {{ number_format($license->failed_tests_amount ?? 0, 2) }} ريال
                                </p>
                                <p class="text-sm text-gray-500">القيمة الإجمالية</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <h4 class="text-lg font-medium text-gray-800 mb-4">إجمالي الاختبارات</h4>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-3xl font-bold text-blue-600">{{ $license->total_tests_count }}</p>
                                <p class="text-sm text-gray-500">اختبار</p>
                            </div>
                            <div class="text-left">
                                <p class="text-lg font-semibold text-blue-600">
                                    {{ number_format($license->total_tests_amount ?? 0, 2) }} ريال
                                </p>
                                <p class="text-sm text-gray-500">القيمة الإجمالية</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- المرفقات -->
        <div class="bg-gray-50 p-4 rounded-lg mb-8">
            <h3 class="text-lg font-semibold mb-4">المرفقات</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                <!-- ملف الرخصة -->
                <div class="border rounded p-3">
                    <h4 class="font-medium mb-2">ملف الرخصة</h4>
                    @if($license->license_file_path)
                        <a href="{{ asset('storage/' . $license->license_file_path) }}" 
                           target="_blank"
                           class="text-blue-600 hover:text-blue-800 flex items-center">
                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            تحميل الملف
                        </a>
                    @else
                        <div class="text-center py-2">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <p class="mt-1 text-sm text-gray-500">لم يتم إضافة ملف</p>
                        </div>
                    @endif
                </div>

                <!-- مرفقات إضافية -->
                <div class="border rounded p-3">
                    <h4 class="font-medium mb-2">مرفقات إضافية</h4>
                    @if($license->attachments && is_array(json_decode($license->attachments, true)))
                        @foreach(json_decode($license->attachments, true) as $index => $attachment)
                            <a href="{{ asset('storage/' . $attachment) }}" 
                               target="_blank"
                               class="text-blue-600 hover:text-blue-800 flex items-center mb-2">
                                <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                مرفق {{ $index + 1 }}
                            </a>
                        @endforeach
                    @else
                        <div class="text-center py-2">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="mt-1 text-sm text-gray-500">لم يتم إضافة مرفقات إضافية</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- المخالفات -->
        @if($license->violations->count() > 0)
        <div class="bg-gray-50 p-4 rounded-lg mb-8">
            <h3 class="text-lg font-semibold mb-4">المخالفات</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-100 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                            <th class="px-6 py-3 bg-gray-100 text-right text-xs font-medium text-gray-500 uppercase">الوصف</th>
                            <th class="px-6 py-3 bg-gray-100 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($license->violations as $violation)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $violation->created_at->format('Y/m/d') }}</td>
                            <td class="px-6 py-4">{{ $violation->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 rounded text-sm {{ $violation->status === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $violation->status === 'resolved' ? 'تم الحل' : 'معلق' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- ملاحظات -->
        @if($license->notes)
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-2">ملاحظات</h3>
            <p class="text-gray-700">{{ $license->notes }}</p>
        </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/license-details.css') }}">
@endsection 
