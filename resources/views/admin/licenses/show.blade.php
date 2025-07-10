@extends('layouts.admin')
@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'تفاصيل الرخصة رقم ' . $license->license_number)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">تفاصيل رخصة الحفر</h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.licenses.edit', $license) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    تعديل الرخصة
                </a>
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

        <!-- مرفقات رخصة الحفر -->
        <div class="bg-gray-50 p-6 rounded-lg mb-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">مرفقات رخصة الحفر</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- ملف الرخصة -->
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-medium text-gray-800">ملف الرخصة</h4>
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
                            <div class="text-gray-500">
                                <svg class="w-5 h-5 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                لم يتم إضافة ملف
                            </div>
                        @endif
                    </div>
                </div>

                <!-- شهادة التنسيق -->
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-medium text-gray-800">شهادة التنسيق</h4>
                        @if($license->coordination_certificate_path)
                            <a href="{{ asset('storage/' . $license->coordination_certificate_path) }}" 
                               target="_blank"
                               class="text-blue-600 hover:text-blue-800 flex items-center">
                                <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                تحميل الشهادة
                            </a>
                        @else
                            <div class="text-gray-500">
                                <svg class="w-5 h-5 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                لم يتم إضافة ملف
                            </div>
                        @endif
                    </div>
                    @if($license->coordination_certificate_notes)
                        <p class="text-sm text-gray-600">{{ $license->coordination_certificate_notes }}</p>
                    @endif
                </div>

                <!-- خطابات التعهدات -->
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-medium text-gray-800">خطابات التعهدات</h4>
                        @if($license->letters_commitments_file_path)
                            <a href="{{ asset('storage/' . $license->letters_commitments_file_path) }}" 
                               target="_blank"
                               class="text-blue-600 hover:text-blue-800 flex items-center">
                                <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                تحميل الخطابات
                            </a>
                        @else
                            <div class="text-gray-500">
                                <svg class="w-5 h-5 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                لم يتم إضافة ملف
                            </div>
                        @endif
                    </div>
                </div>

                <!-- ملف تفعيل الرخصة -->
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-medium text-gray-800">ملف تفعيل الرخصة</h4>
                        @if($license->license_activation_path)
                            <a href="{{ asset('storage/' . $license->license_activation_path) }}" 
                               target="_blank"
                               class="text-blue-600 hover:text-blue-800 flex items-center">
                                <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                تحميل الملف
                            </a>
                        @else
                            <div class="text-gray-500">
                                <svg class="w-5 h-5 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                لم يتم إضافة ملف
                            </div>
                        @endif
                    </div>
                </div>

                @php
                    $attachmentTypes = [
                        'excavation_permit' => 'تصريح الحفر',
                        'site_plan' => 'مخطط الموقع',
                        'safety_plan' => 'خطة السلامة',
                        'traffic_diversion_plan' => 'خطة تحويل المرور',
                        'payment_receipt' => 'إيصال الدفع',
                        'contractor_letter' => 'خطاب المقاول',
                        'consultant_letter' => 'خطاب الاستشاري',
                        'other' => 'مرفقات أخرى'
                    ];
                @endphp

                @if($license->attachments && $license->attachments->isNotEmpty())
                    @foreach($license->attachments as $attachment)
                        @if(array_key_exists($attachment->attachment_type, $attachmentTypes))
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-lg font-medium text-gray-800">{{ $attachmentTypes[$attachment->attachment_type] }}</h4>
                                    <a href="{{ asset('storage/' . $attachment->file_path) }}" 
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-800 flex items-center group relative">
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="truncate max-w-xs">{{ $attachment->file_name }}</span>
                                        
                                        <!-- Tooltip -->
                                        <div class="absolute bottom-full right-0 mb-2 hidden group-hover:block bg-gray-800 text-white text-xs rounded py-1 px-2 whitespace-nowrap">
                                            <div>{{ $attachment->file_name }}</div>
                                            <div>تم الرفع: {{ \Carbon\Carbon::parse($attachment->created_at)->format('Y/m/d') }}</div>
                                            @if($attachment->description)
                                                <div>{{ $attachment->description }}</div>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                                @if($attachment->description)
                                    <p class="text-sm text-gray-600">{{ $attachment->description }}</p>
                                @endif
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="col-span-full text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M12 14h.01M12 16h.01M12 18h.01M12 20h.01M12 22h.01"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد مرفقات</h3>
                        <p class="mt-1 text-sm text-gray-500">لم يتم إضافة أي مرفقات للرخصة بعد</p>
                    </div>
                @endif
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
                                        @if($extension->attachments)
                                            @php
                                                $attachments = is_array($extension->attachments) 
                                                    ? $extension->attachments 
                                                    : (is_string($extension->attachments) ? json_decode($extension->attachments, true) : []);
                                            @endphp
                                            @if(is_array($attachments) && count($attachments) > 0)
                                                @foreach($attachments as $index => $attachment)
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
                                        @php
                                            $testType = str_replace(' ', '_', strtolower($testName));
                                            $attachments = $license->attachments ? $license->attachments->where('attachment_type', 'lab_test_' . $testType)->all() : [];
                                        @endphp
                                        
                                        @if(count($attachments) > 0)
                                            @foreach($attachments as $attachment)
                                                <div class="mb-2 last:mb-0">
                                                    <a href="{{ asset('storage/' . $attachment->file_path) }}" 
                                                       target="_blank" 
                                                       class="text-blue-600 hover:text-blue-800 flex items-center group relative">
                                                        <svg class="w-5 h-5 ml-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        <span class="truncate max-w-xs">{{ $attachment->file_name }}</span>
                                                        
                                                        <!-- Tooltip -->
                                                        <div class="absolute bottom-full right-0 mb-2 hidden group-hover:block bg-gray-800 text-white text-xs rounded py-1 px-2 whitespace-nowrap">
                                                            <div>{{ $attachment->file_name }}</div>
                                                            <div>تم الرفع: {{ \Carbon\Carbon::parse($attachment->created_at)->format('Y/m/d') }}</div>
                                                            @if($attachment->description)
                                                                <div>{{ $attachment->description }}</div>
                                                            @endif
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
                                        @elseif(isset($test['files']) && is_array($test['files']) && count($test['files']) > 0)
                                            @foreach($test['files'] as $file)
                                                <div class="mb-2 last:mb-0">
                                                    <a href="{{ asset('storage/' . $file) }}" 
                                                       target="_blank" 
                                                       class="text-blue-600 hover:text-blue-800 flex items-center">
                                                        <svg class="w-5 h-5 ml-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        <span class="truncate max-w-xs">مرفق {{ $loop->iteration }}</span>
                                                    </a>
                                                </div>
                                            @endforeach
                                        @elseif(isset($test['file']) && $test['file'])
                                            <div>
                                                <a href="{{ asset('storage/' . $test['file']) }}" 
                                                   target="_blank" 
                                                   class="text-blue-600 hover:text-blue-800 flex items-center">
                                                    <svg class="w-5 h-5 ml-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <span class="truncate max-w-xs">المرفق</span>
                                                </a>
                                            </div>
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

        <!-- قسم الإخلاءات -->
        <div class="bg-gray-50 p-6 rounded-lg mb-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">الإخلاءات</h3>
                <div class="flex items-center space-x-4">
                    <div class="stats flex space-x-4 ml-4">
                        @php
                            // بيانات الإخلاءات من additional_details
                            $evacuationData = [];
                            if ($license->additional_details) {
                                $additionalDetails = json_decode($license->additional_details, true);
                                if (is_array($additionalDetails) && isset($additionalDetails['evacuation_data'])) {
                                    $evacuationData = $additionalDetails['evacuation_data'];
                                }
                            }
                            
                            // بيانات جدول الإخلاءات التفصيلي
                            $evacTable1Data = $license->evac_table1_data ? json_decode($license->evac_table1_data, true) : [];
                            
                            // حساب إجمالي الإخلاءات والمبلغ
                            $totalEvacuations = is_array($evacuationData) ? count($evacuationData) : 0;
                            $totalEvacuationAmount = 0;
                            $totalAttachments = 0;
                            
                            if (is_array($evacuationData)) {
                                foreach ($evacuationData as $evacuation) {
                                    $totalEvacuationAmount += $evacuation['evacuation_amount'] ?? 0;
                                    
                                    // حساب عدد المرفقات
                                    if (isset($evacuation['attachments']) && is_array($evacuation['attachments'])) {
                                        $totalAttachments += count($evacuation['attachments']);
                                    } elseif (isset($evacuation['attachment']) && !empty($evacuation['attachment'])) {
                                        $totalAttachments += 1;
                                    }
                                }
                            }
                            
                            
                        @endphp
                        <div class="stat-item bg-blue-100 text-blue-800 px-4 py-2 rounded-lg">
                            <span class="font-medium">عدد الإخلاءات:</span>
                            <span class="ml-1">{{ $totalEvacuations }}</span>
                        </div>
                        <div class="stat-item bg-green-100 text-green-800 px-4 py-2 rounded-lg">
                            <span class="font-medium">إجمالي المبلغ:</span>
                            <span class="ml-1">{{ number_format($totalEvacuationAmount, 2) }} ريال</span>
                        </div>
                        <div class="stat-item bg-purple-100 text-purple-800 px-4 py-2 rounded-lg">
                            <span class="font-medium">عدد المرفقات:</span>
                            <span class="ml-1">{{ $totalAttachments }}</span>
                        </div>
                    </div>
                </div>
            </div>





            <!-- جدول بيانات الإخلاءات الأساسي -->
            @php
                // تحقق من وجود أي بيانات إخلاء أو مرفقات
                $hasEvacuationData = false;
                $hasCompleteEvacuationData = false;
                
                if (is_array($evacuationData) && count($evacuationData) > 0) {
                    $hasEvacuationData = true;
                    
                    // تحقق من وجود بيانات كاملة
                    foreach ($evacuationData as $evacuation) {
                        if (isset($evacuation['evacuation_date']) || isset($evacuation['evacuation_amount']) || isset($evacuation['is_evacuated'])) {
                            $hasCompleteEvacuationData = true;
                            break;
                        }
                    }
                }
            @endphp
            
            @if($hasCompleteEvacuationData)
                <div class="mb-8">
                    <h4 class="text-lg font-medium text-gray-800 mb-4">بيانات الإخلاءات الأساسية</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 bg-white rounded-lg shadow">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">حالة الإخلاء</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الإخلاء</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ ووقت الإخلاء</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم السداد</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الملاحظات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($evacuationData as $index => $evacuation)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if(($evacuation['is_evacuated'] ?? 0) == 1)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    تم الإخلاء
                                                </span>
                                            @elseif(isset($evacuation['is_evacuated']) && $evacuation['is_evacuated'] == 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    لم يتم الإخلاء
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-question-circle mr-1"></i>
                                                    غير محدد
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ isset($evacuation['evacuation_date']) && $evacuation['evacuation_date'] ? \Carbon\Carbon::parse($evacuation['evacuation_date'])->format('Y/m/d') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                            {{ number_format($evacuation['evacuation_amount'] ?? 0, 2) }} ريال
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ isset($evacuation['evacuation_datetime']) && $evacuation['evacuation_datetime'] ? \Carbon\Carbon::parse($evacuation['evacuation_datetime'])->format('Y/m/d H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $evacuation['payment_number'] ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $evacuation['notes'] ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-50">
                                    <td colspan="3" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">إجمالي المبلغ</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ number_format($totalEvacuationAmount, 2) }} ريال
                                    </td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif

            <!-- عرض المرفقات فقط إذا كانت موجودة ولكن البيانات ناقصة -->
            @if($hasEvacuationData && !$hasCompleteEvacuationData)
                <div class="mb-8">
                    <div class="bg-orange-50 p-6 rounded-lg border border-orange-200">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-info-circle text-orange-600 text-xl mr-3"></i>
                            <h4 class="text-lg font-medium text-orange-900">مرفقات الإخلاءات</h4>
                        </div>
                        
                        <div class="mb-4 p-3 bg-orange-100 rounded border border-orange-300">
                            <p class="text-sm text-orange-800">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                توجد مرفقات للإخلاء ولكن البيانات التفصيلية (التاريخ، المبلغ، إلخ) غير مكتملة. يرجى التحقق من البيانات أو إضافة المعلومات المفقودة.
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($evacuationData as $index => $evacuation)
                                @php
                                    $evacuationFiles = [];
                                    
                                    // البحث عن المرفقات
                                    if (isset($evacuation['attachments']) && is_array($evacuation['attachments']) && count($evacuation['attachments']) > 0) {
                                        $evacuationFiles = $evacuation['attachments'];
                                    } elseif (isset($evacuation['attachment']) && !empty($evacuation['attachment'])) {
                                        $evacuationFiles = [$evacuation['attachment']];
                                    }
                                @endphp
                                
                                @if(count($evacuationFiles) > 0)
                                    <div class="bg-white rounded-lg shadow p-4 border border-orange-200">
                                        <div class="flex items-center justify-between mb-3">
                                            <h5 class="text-sm font-medium text-orange-900">
                                                مرفقات إخلاء رقم {{ $index + 1 }}
                                            </h5>
                                            <span class="text-xs text-orange-600 bg-orange-100 px-2 py-1 rounded-full">
                                                {{ count($evacuationFiles) }} ملف
                                            </span>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            @foreach($evacuationFiles as $fileIndex => $attachment)
                                                @if(!empty($attachment))
                                                    <a href="{{ asset('storage/' . $attachment) }}" 
                                                       target="_blank" 
                                                       class="flex items-center justify-between p-2 bg-gray-50 hover:bg-gray-100 rounded border transition-colors duration-200">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-file text-blue-600 mr-2"></i>
                                                            <span class="text-sm text-gray-700">{{ basename($attachment) }}</span>
                                                        </div>
                                                        <i class="fas fa-external-link-alt text-gray-400 text-xs"></i>
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- قسم مرفقات الإخلاءات منفصل -->
            @if($hasEvacuationData)
                <div class="mb-8">
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-paperclip text-blue-600 text-xl mr-3"></i>
                            <h4 class="text-lg font-medium text-blue-900">مرفقات بيانات الإخلاءات</h4>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($evacuationData as $index => $evacuation)
                                @php
                                    $evacuationFiles = [];
                                    
                                    // البحث عن المرفقات الفردية
                                    if (isset($evacuation['attachments']) && is_array($evacuation['attachments']) && count($evacuation['attachments']) > 0) {
                                        $evacuationFiles = $evacuation['attachments'];
                                    } elseif (isset($evacuation['attachment']) && !empty($evacuation['attachment'])) {
                                        $evacuationFiles = [$evacuation['attachment']];
                                    }
                                @endphp
                                
                                @if(count($evacuationFiles) > 0)
                                    <div class="bg-white rounded-lg shadow p-4 border border-blue-200">
                                        <div class="flex items-center justify-between mb-3">
                                            <h5 class="text-sm font-medium text-blue-900">
                                                إخلاء رقم {{ $index + 1 }}
                                            </h5>
                                            <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full">
                                                {{ count($evacuationFiles) }} ملف
                                            </span>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            @if(isset($evacuation['evacuation_date']) && !empty($evacuation['evacuation_date']))
                                                <div class="text-xs text-gray-600">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($evacuation['evacuation_date'])->format('Y/m/d') }}
                                                </div>
                                            @endif
                                            
                                            @if(isset($evacuation['evacuation_amount']) && !empty($evacuation['evacuation_amount']))
                                                <div class="text-xs text-gray-600">
                                                    <i class="fas fa-money-bill mr-1"></i>
                                                    {{ number_format($evacuation['evacuation_amount'], 2) }} ريال
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="mt-3 space-y-2">
                                            @foreach($evacuationFiles as $fileIndex => $attachment)
                                                @if(!empty($attachment))
                                                    @php
                                                        $fileName = basename($attachment);
                                                        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                                                        $fileSize = '';
                                                        
                                                        // محاولة الحصول على حجم الملف
                                                        $fullPath = storage_path('app/public/' . $attachment);
                                                        if (file_exists($fullPath)) {
                                                            $fileSize = ' (' . round(filesize($fullPath) / 1024, 2) . ' KB)';
                                                        }
                                                        
                                                        // تحديد أيقونة الملف
                                                        $fileIcon = 'fas fa-file';
                                                        if (in_array(strtolower($fileExtension), ['pdf'])) {
                                                            $fileIcon = 'fas fa-file-pdf text-red-600';
                                                        } elseif (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif'])) {
                                                            $fileIcon = 'fas fa-image text-green-600';
                                                        } elseif (in_array(strtolower($fileExtension), ['doc', 'docx'])) {
                                                            $fileIcon = 'fas fa-file-word text-blue-600';
                                                        }
                                                    @endphp
                                                    
                                                    <a href="{{ asset('storage/' . $attachment) }}" 
                                                       target="_blank" 
                                                       class="flex items-center p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 group"
                                                       title="تحميل الملف: {{ $fileName }}">
                                                        <i class="{{ $fileIcon }} mr-2"></i>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="text-sm font-medium text-gray-900 truncate">
                                                                {{ Str::limit($fileName, 20) }}
                                                            </div>
                                                            <div class="text-xs text-gray-500">
                                                                {{ strtoupper($fileExtension) }}{{ $fileSize }}
                                                            </div>
                                                        </div>
                                                        <i class="fas fa-external-link-alt text-gray-400 group-hover:text-blue-600 ml-2"></i>
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                        
                                        @if(isset($evacuation['notes']) && !empty($evacuation['notes']))
                                            <div class="mt-3 p-2 bg-yellow-50 rounded border border-yellow-200">
                                                <div class="text-xs text-yellow-800">
                                                    <i class="fas fa-sticky-note mr-1"></i>
                                                    {{ $evacuation['notes'] }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                            
                            <!-- إضافة المرفقات العامة للإخلاءات إذا كانت موجودة -->
                            @if($license->evacuations_file_path)
                                @php
                                    $generalFiles = json_decode($license->evacuations_file_path, true);
                                @endphp
                                
                                @if(is_array($generalFiles) && count($generalFiles) > 0)
                                    <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                                        <div class="flex items-center justify-between mb-3">
                                            <h5 class="text-sm font-medium text-gray-900">
                                                مرفقات عامة للإخلاءات
                                            </h5>
                                            <span class="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded-full">
                                                {{ count($generalFiles) }} ملف
                                            </span>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            @foreach($generalFiles as $attachment)
                                                @if(!empty($attachment))
                                                    @php
                                                        $fileName = basename($attachment);
                                                        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                                                        $fileSize = '';
                                                        
                                                        // محاولة الحصول على حجم الملف
                                                        $fullPath = storage_path('app/public/' . $attachment);
                                                        if (file_exists($fullPath)) {
                                                            $fileSize = ' (' . round(filesize($fullPath) / 1024, 2) . ' KB)';
                                                        }
                                                        
                                                        // تحديد أيقونة الملف
                                                        $fileIcon = 'fas fa-file';
                                                        if (in_array(strtolower($fileExtension), ['pdf'])) {
                                                            $fileIcon = 'fas fa-file-pdf text-red-600';
                                                        } elseif (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif'])) {
                                                            $fileIcon = 'fas fa-image text-green-600';
                                                        } elseif (in_array(strtolower($fileExtension), ['doc', 'docx'])) {
                                                            $fileIcon = 'fas fa-file-word text-blue-600';
                                                        }
                                                    @endphp
                                                    
                                                    <a href="{{ asset('storage/' . $attachment) }}" 
                                                       target="_blank" 
                                                       class="flex items-center p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 group"
                                                       title="تحميل الملف: {{ $fileName }}">
                                                        <i class="{{ $fileIcon }} mr-2"></i>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="text-sm font-medium text-gray-900 truncate">
                                                                {{ Str::limit($fileName, 20) }}
                                                            </div>
                                                            <div class="text-xs text-gray-500">
                                                                {{ strtoupper($fileExtension) }}{{ $fileSize }}
                                                            </div>
                                                        </div>
                                                        <i class="fas fa-external-link-alt text-gray-400 group-hover:text-blue-600 ml-2"></i>
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                        
                        @if(collect($evacuationData)->filter(function($evacuation) {
                            return isset($evacuation['attachments']) && is_array($evacuation['attachments']) && count($evacuation['attachments']) > 0;
                        })->isEmpty() && (!$license->evacuations_file_path || !json_decode($license->evacuations_file_path, true)))
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-folder-open text-3xl text-gray-400 mb-2"></i>
                                <h3 class="text-sm font-medium text-gray-900">لا توجد مرفقات</h3>
                                <p class="text-xs text-gray-500">لم يتم إرفاق أي ملفات مع بيانات الإخلاءات</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- جدول بيانات الإخلاءات التفصيلي - الجدول الأول -->
            @if(is_array($evacTable1Data) && count($evacTable1Data) > 0)
                <div class="mb-8">
                    <h4 class="text-lg font-medium text-gray-800 mb-4">جدول الفسح ونوع الشارع للإخلاء</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الفسح</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الفسح</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">طول الفسح</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">طول المختبر</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع الشارع</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">كمية التربة</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">كمية الأسفلت</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">كمية البلاط</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">فحص التربة</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">فحص MC1</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">فحص الأسفلت</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ملاحظات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($evacTable1Data as $index => $row)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['clearance_number'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ isset($row['clearance_date']) ? \Carbon\Carbon::parse($row['clearance_date'])->format('Y/m/d') : '-' }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['length'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['lab_length'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['street_type'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['soil_quantity'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['asphalt_quantity'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['tile_quantity'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['soil_test'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['mc1_test'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['asphalt_test'] ?? '-' }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-900">{{ $row['notes'] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- جدول التفاصيل الفنية للمختبر -->
            @php
                $labTechnicalData = $license->lab_table2_data ? json_decode($license->lab_table2_data, true) : [];
            @endphp
            
            @if(is_array($labTechnicalData) && count($labTechnicalData) > 0)
                <div class="mb-8">
                    <h4 class="text-lg font-medium text-gray-800 mb-4">التفاصيل الفنية للمختبر</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 80px;">السنة</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 120px;">نوع العمل</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 80px;">العمق</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px;">دك التربة</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px;">MC1-RC2</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px;">دك أسفلت</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 80px;">ترابي</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 120px;">الكثافة القصوى للأسفلت</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 120px;">نسبة الأسفلت</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 120px;">تجربة مارشال</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 120px;">تقييم البلاط</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 120px;">تصنيف التربة</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 120px;">تجربة بروكتور</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px;">الخرسانة</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($labTechnicalData as $index => $row)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['year'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['work_type'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['depth'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['soil_compaction'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['mc1rc2'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['asphalt_compaction'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['soil_type'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['max_asphalt_density'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['asphalt_percentage'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['marshall_test'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['tile_evaluation'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['soil_classification'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['proctor_test'] ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['concrete'] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- رسالة عدم وجود بيانات -->
            @if(!$hasEvacuationData && (!is_array($evacTable1Data) || count($evacTable1Data) == 0) && (!is_array($labTechnicalData) || count($labTechnicalData) == 0))
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M12 14h.01M12 16h.01M12 18h.01M12 20h.01M12 22h.01"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد بيانات إخلاء</h3>
                    <p class="mt-1 text-sm text-gray-500">لم يتم إضافة أي بيانات إخلاء للرخصة بعد</p>
                </div>
            @endif
        </div>

        <!-- المخالفات -->
        @if($license->violations->count() > 0)
        <div class="bg-gray-50 p-4 rounded-lg mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                    المخالفات ({{ $license->violations->count() }})
                </h3>
                <div class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                    الإجمالي: {{ number_format($license->violations->sum('violation_amount'), 2) }} ريال
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 bg-white rounded-lg shadow">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم المخالفة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع المخالفة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ المخالفة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الجهة المسؤولة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قيمة المخالفة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الاستحقاق</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">حالة الدفع</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المرفقات</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($license->violations as $index => $violation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                {{ $violation->violation_number ?? '-' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $violation->violation_type ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $violation->violation_date ? $violation->violation_date->format('Y/m/d') : '-' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $violation->responsible_party ?? '-' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                {{ $violation->violation_amount ? number_format($violation->violation_amount, 2) . ' ريال' : '-' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($violation->payment_due_date)
                                    <span class="@if($violation->payment_due_date < now()) text-red-600 @else text-gray-900 @endif">
                                        {{ $violation->payment_due_date->format('Y/m/d') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                @if($violation->payment_status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        في انتظار السداد
                                    </span>
                                @elseif($violation->payment_status === '1' || $violation->payment_status === 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        تم السداد
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-question-circle mr-1"></i>
                                        غير محدد
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($violation->attachment_path)
                                    <a href="{{ asset('storage/' . $violation->attachment_path) }}" 
                                       target="_blank" 
                                       class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full hover:bg-blue-200 transition-colors duration-200"
                                       title="عرض المرفق">
                                        <i class="fas fa-paperclip mr-1"></i>
                                        عرض المرفق
                                    </a>
                                @else
                                    <span class="text-gray-500 text-xs">لا يوجد</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                @if($violation->status === 'resolved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        تم الحل
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        معلق
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @if($violation->violation_description || $violation->notes)
                        <tr class="bg-gray-50">
                            <td colspan="10" class="px-4 py-3 text-sm text-gray-700">
                                @if($violation->violation_description)
                                    <div class="mb-2">
                                        <strong class="text-gray-900">وصف المخالفة:</strong>
                                        <span class="ml-2">{{ $violation->violation_description }}</span>
                                    </div>
                                @endif
                                @if($violation->notes)
                                    <div>
                                        <strong class="text-gray-900">ملاحظات:</strong>
                                        <span class="ml-2">{{ $violation->notes }}</span>
                                    </div>
                                @endif
                                @if($violation->payment_invoice_number)
                                    <div class="mt-2">
                                        <strong class="text-gray-900">رقم فاتورة الدفع:</strong>
                                        <span class="ml-2 text-blue-600">{{ $violation->payment_invoice_number }}</span>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="bg-gray-50 p-8 rounded-lg mb-8 text-center">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-shield-alt text-4xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد مخالفات</h3>
            <p class="text-gray-500">لم يتم تسجيل أي مخالفات لهذه الرخصة</p>
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

@push('scripts')
<script src="{{ asset('js/license-handler.js') }}"></script>
<style>
/* CSS للتبويبات في حالة عدم وجود CSS مخصص */
.nav-tab-btn {
    margin: 5px;
    padding: 10px 20px;
    border: 1px solid #ddd;
    background-color: #f8f9fa;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.nav-tab-btn:hover {
    background-color: #e9ecef;
    border-color: #adb5bd;
}

.nav-tab-btn.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.tab-section {
    padding: 20px;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    margin-top: 10px;
}

/* تحسينات إضافية */
.section-body {
    text-align: center;
    margin-bottom: 20px;
}

.d-flex {
    display: flex;
}

.justify-content-center {
    justify-content: center;
}

.flex-wrap {
    flex-wrap: wrap;
}
</style>
@endpush