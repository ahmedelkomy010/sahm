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
            <div class="flex space-x-2 gap-2">
                <a href="{{ route('admin.work-orders.license', $license->workOrder) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    العودة إلى إدارة الجودة والرخص
                </a>
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
        <div class="mt-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">مرفقات رخصة الحفر</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- ملف الرخصة -->
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 mb-3 flex items-center justify-center rounded-full bg-blue-50">
                            <i class="fas fa-file-pdf text-2xl text-blue-500"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">ملف الرخصة</h4>
                        @if($license->license_file_path)
                            <a href="{{ route('admin.licenses.download', ['license' => $license, 'type' => 'license']) }}" 
                               class="text-blue-600 hover:text-blue-800 flex items-center">
                                <i class="fas fa-download ml-1"></i>
                                تحميل الملف
                            </a>
                        @else
                            <span class="text-gray-500">لم يتم إضافة ملف</span>
                        @endif
                    </div>
                </div>

                <!-- شهادة التنسيق -->
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 mb-3 flex items-center justify-center rounded-full bg-green-50">
                            <i class="fas fa-certificate text-2xl text-green-500"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">شهادة التنسيق</h4>
                        @if($license->coordination_certificate_path)
                            <a href="{{ route('admin.licenses.download', ['license' => $license, 'type' => 'coordination']) }}" 
                               class="text-blue-600 hover:text-blue-800 flex items-center">
                                <i class="fas fa-download ml-1"></i>
                                تحميل الملف
                            </a>
                        @else
                            <span class="text-gray-500">لم يتم إضافة ملف</span>
                        @endif
                    </div>
                </div>

                <!-- خطابات التعهدات -->
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 mb-3 flex items-center justify-center rounded-full bg-purple-50">
                            <i class="fas fa-file-contract text-2xl text-purple-500"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">خطابات التعهدات</h4>
                        @if($license->letters_commitments_file_path)
                            <a href="{{ route('admin.licenses.download', ['license' => $license, 'type' => 'commitments']) }}" 
                               class="text-blue-600 hover:text-blue-800 flex items-center">
                                <i class="fas fa-download ml-1"></i>
                                تحميل الملف
                            </a>
                        @else
                            <span class="text-gray-500">لم يتم إضافة ملف</span>
                        @endif
                    </div>
                </div>

                <!-- ملف تفعيل الرخصة -->
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 mb-3 flex items-center justify-center rounded-full bg-yellow-50">
                            <i class="fas fa-file-alt text-2xl text-yellow-500"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">ملف تفعيل الرخصة</h4>
                        @if($license->activation_file_path)
                            <a href="{{ route('admin.licenses.download', ['license' => $license, 'type' => 'activation']) }}" 
                               class="text-blue-600 hover:text-blue-800 flex items-center">
                                <i class="fas fa-download ml-1"></i>
                                تحميل الملف
                            </a>
                        @else
                            <span class="text-gray-500">لم يتم إضافة ملف</span>
                        @endif
                    </div>
                </div>

                <!-- فواتير السداد -->
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 mb-3 flex items-center justify-center rounded-full bg-red-50">
                            <i class="fas fa-file-invoice-dollar text-2xl text-red-500"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">فواتير السداد</h4>
                        @if($license->payment_invoices_path)
                            <a href="{{ route('admin.licenses.download', ['license' => $license, 'type' => 'payment_invoices']) }}" 
                               class="text-blue-600 hover:text-blue-800 flex items-center">
                                <i class="fas fa-download ml-1"></i>
                                تحميل الملف
                            </a>
                        @else
                            <span class="text-gray-500">لم يتم إضافة ملف</span>
                        @endif
                    </div>
                </div>

                <!-- إثبات سداد البنك -->
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 mb-3 flex items-center justify-center rounded-full bg-emerald-50">
                            <i class="fas fa-receipt text-2xl text-emerald-500"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">إثبات سداد البنك</h4>
                        @if($license->bank_payment_proof_path)
                            <a href="{{ route('admin.licenses.download', ['license' => $license, 'type' => 'bank_payment']) }}" 
                               class="text-blue-600 hover:text-blue-800 flex items-center">
                                <i class="fas fa-download ml-1"></i>
                                تحميل الملف
                            </a>
                        @else
                            <span class="text-gray-500">لم يتم إضافة ملف</span>
                        @endif
                    </div>
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
                
            @endif

           <!-- بيانات الإخلاءات -->
        <div class="mt-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4"> الإخلاءات</h3>
            
            <!-- ملخص الإخلاءات -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clipboard-list text-blue-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-blue-900">عدد الإخلاءات</h4>
                            <p class="mt-1 text-2xl font-semibold text-blue-700">
                                {{ isset($additionalDetails['evacuation_data']) ? count($additionalDetails['evacuation_data']) : 0 }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-money-bill-wave text-green-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-green-900">إجمالي المبالغ</h4>
                            <p class="mt-1 text-2xl font-semibold text-green-700">
                                {{ isset($additionalDetails['evacuation_data']) ? 
                                   number_format(collect($additionalDetails['evacuation_data'])->sum('evacuation_amount'), 2) : 
                                   '0.00' }} ريال
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-alt text-purple-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-purple-900">المرفقات</h4>
                            <p class="mt-1 text-2xl font-semibold text-purple-700">
                                {{ isset($additionalDetails['evacuation_data']) ? 
                                   collect($additionalDetails['evacuation_data'])->filter(function($item) {
                                       return isset($item['evacuation_file']);
                                   })->count() : 0 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- جدول بيانات الإخلاءات -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تم الإخلاء؟</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ الإخلاء</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">مبلغ الإخلاء</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ ووقت الإخلاء</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم سداد الإخلاء</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">ملاحظات</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المرفق</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($additionalDetails['evacuation_data']) && count($additionalDetails['evacuation_data']) > 0)
                            @foreach($additionalDetails['evacuation_data'] as $index => $evacuation)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($evacuation['is_evacuated'] == 1)
                                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                نعم
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                                لا
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $evacuation['evacuation_date'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                        {{ number_format($evacuation['evacuation_amount'] ?? 0, 2) }} ريال
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $evacuation['evacuation_datetime'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $evacuation['payment_number'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $evacuation['notes'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if(isset($evacuation['evacuation_file']))
                                            <a href="{{ route('licenses.evacuation-file', ['license' => $license->id, 'index' => $index]) }}" 
                                               class="text-blue-600 hover:text-blue-800 inline-flex items-center gap-1"
                                               target="_blank">
                                                <i class="fas fa-file-download"></i>
                                                عرض
                                            </a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    <i class="fas fa-inbox text-gray-400 text-3xl mb-2"></i>
                                    <p>لا توجد بيانات إخلاء مسجلة</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- مرفقات الإخلاء المنفصلة -->
        @if(isset($additionalDetails['evacuation_attachments']) && count($additionalDetails['evacuation_attachments']) > 0)
            <div class="mt-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-paperclip text-blue-500 me-2"></i>
                    مرفقات الإخلاء
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($additionalDetails['evacuation_attachments'] as $index => $attachment)
                        <div class="bg-white rounded-lg shadow border border-gray-200 hover:shadow-md transition-shadow p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $attachment['name'] ?? 'مرفق إخلاء ' . ($index + 1) }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            تاريخ الرفع: {{ isset($attachment['uploaded_at']) ? \Carbon\Carbon::parse($attachment['uploaded_at'])->format('Y/m/d H:i') : 'غير محدد' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 flex justify-center space-x-2 gap-2">
                                <a href="{{ \Storage::disk('public')->url($attachment['path']) }}" 
                                   target="_blank" 
                                   class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-eye mr-1"></i>
                                    عرض
                                </a>
                                <a href="{{ \Storage::disk('public')->url($attachment['path']) }}" 
                                   download="{{ $attachment['name'] ?? 'evacuation_attachment_' . ($index + 1) }}"
                                   class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-download mr-1"></i>
                                    تحميل
                                </a>
                            </div>
                        </div>
                    @endforeach
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

        
        <!-- ملاحظات -->
        @if($license->notes)
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-2">ملاحظات</h3>
            <p class="text-gray-700">{{ $license->notes }}</p>
        </div>
        @endif

        
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