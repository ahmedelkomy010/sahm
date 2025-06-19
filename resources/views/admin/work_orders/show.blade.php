@extends('layouts.app')

@push('styles')
<style>
    .custom-btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s;
        color: #fff;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin: 0 2px;
    }

    .custom-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        color: #fff;
    }

    .btn-materials { background-color: #4CAF50; }
    .btn-materials:hover { background-color: #43A047; }
    
    .btn-survey { background-color: #2196F3; }
    .btn-survey:hover { background-color: #1E88E5; }
    
    .btn-license { background-color: #9C27B0; }
    .btn-license:hover { background-color: #8E24AA; }
    
    .btn-execution { background-color: #FF9800; }
    .btn-execution:hover { background-color: #FB8C00; }
    
    .btn-actions { background-color: #607D8B; }
    .btn-actions:hover { background-color: #546E7A; }
    
    .btn-back { background-color: #795548; }
    .btn-back:hover { background-color: #6D4C41; }

    .custom-card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        background-color: #fff;
    }

    .custom-card .card-header {
        background: linear-gradient(45deg, #f8f9fa, #ffffff);
        border-bottom: 1px solid #eee;
        padding: 15px 20px;
        border-radius: 8px 8px 0 0;
    }

    .custom-card .card-header h4 {
        margin: 0;
        color: #333;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .custom-table {
        width: 100%;
        margin-bottom: 0;
    }

    .custom-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        padding: 12px 15px;
        border: 1px solid #e9ecef;
    }

    .custom-table td {
        padding: 12px 15px;
        border: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .header-gradient {
        background: linear-gradient(45deg, #1976D2, #2196F3);
        color: white;
        padding: 20px;
        border-radius: 8px 8px 0 0;
    }

    .badge {
        padding: 6px 10px;
        border-radius: 4px;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .custom-btn {
            padding: 6px 12px;
            font-size: 14px;
        }
        .custom-btn i {
            margin-right: 4px;
        }
        .custom-btn span {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="custom-card">
                <div class="header-gradient">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <h3 class="mb-0 fs-4">تفاصيل أمر العمل</h3>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <a href="{{ route('admin.work-orders.materials.index', $workOrder) }}" class="custom-btn btn-materials">
                                <i class="fas fa-boxes me-1"></i> المواد
                            </a>
                            <a href="{{ route('admin.work-orders.survey', $workOrder) }}" class="custom-btn btn-survey">
                                <i class="fas fa-map-marked-alt me-1"></i> المسح
                            </a>
                            <a href="{{ route('admin.work-orders.license', $workOrder) }}" class="custom-btn btn-license">
                                <i class="fas fa-file-alt me-1"></i> الجودة
                            </a>
                            <a href="{{ route('admin.work-orders.execution', $workOrder) }}" class="custom-btn btn-execution">
                                <i class="fas fa-tools me-1"></i> تنفيذ
                            </a>
                            <a href="{{ route('admin.work-orders.actions-execution', $workOrder) }}" class="custom-btn btn-actions">
                                <i class="fas fa-cogs me-1"></i> إجراءات ما بعد التنفيذ
                            </a>
                            
                            <a href="{{ route('admin.work-orders.index') }}" class="custom-btn btn-back">
                                <i class="fas fa-arrow-right me-1"></i> عودة لأوامر العمل   
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- معلومات أساسية -->
                        <div class="col-12 col-lg-6">
                            <div class="custom-card">
                                <div class="card-header">
                                    <h4>
                                        <i class="fas fa-info-circle text-primary me-2"></i>
                                        معلومات أساسية
                                    </h4>
                                </div>
                                <div class="card-body p-0">
                                    <table class="custom-table">
                                        <tbody>
                                            <tr>
                                                <th style="width: 40%">رقم المسلسل</th>
                                                <td>{{ $workOrder->id }}</td>
                                            </tr>
                                            <tr>
                                                <th>رقم الطلب</th>
                                                <td>{{ $workOrder->order_number }}</td>
                                            </tr>
                                            <tr>
                                                <th>رقم نوع العمل</th>
                                                <td>{{ $workOrder->work_type }}</td>
                                            </tr>
                                            <tr>
                                                <th>وصف العمل والتعليق</th>
                                                <td>{{ $workOrder->work_description ?? 'غير متوفر' }}</td>
                                            </tr>
                                            <tr>
                                                <th>تاريخ تسليم إجراء 155</th>
                                                <td>{{ $workOrder->procedure_155_delivery_date ? $workOrder->procedure_155_delivery_date->format('Y-m-d') : 'غير متوفر' }}</td>
                                            </tr>
                                            <tr>
                                                <th>اختبارات ما قبل التشغيل</th>
                                                <td>{{ $workOrder->pre_operation_tests ?? 'غير متوفر' }}</td>
                                            </tr>
                                            <tr>
                                                <th>حالة التنفيذ</th>
                                                <td>
                                                    @switch($workOrder->execution_status)
                                                        @case('1')
                                                            <span class="badge bg-info">جاري العمل ...</span>
                                                            @break
                                                        @case('2')
                                                            <span class="badge bg-warning">تم تسليم 155 ولم تصدر شهادة انجاز</span>
                                                            @break
                                                        @case('3')
                                                            <span class="badge bg-primary">صدرت شهادة ولم تعتمد</span>
                                                            @break
                                                        @case('4')
                                                            <span class="badge bg-secondary">تم اعتماد شهادة الانجاز</span>
                                                            @break
                                                        @case('5')
                                                            <span class="badge bg-success">مؤكد ولم تدخل مستخلص</span>
                                                            @break
                                                        @case('6')
                                                            <span class="badge bg-dark">دخلت مستخلص ولم تصرف</span>
                                                            @break
                                                        @case('7')
                                                            <span class="badge bg-success">منتهي تم الصرف</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">{{ $workOrder->execution_status }}</span>
                                                    @endswitch
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>تاريخ الاعتماد</th>
                                                <td>{{ date('Y-m-d', strtotime($workOrder->approval_date)) }}</td>
                                            </tr>
                                            <tr>
                                                <th>اسم المشترك</th>
                                                <td>{{ $workOrder->subscriber_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>الحي</th>
                                                <td>{{ $workOrder->district }}</td>
                                            </tr>
                                            <tr>
                                                <th>البلدية</th>
                                                <td>{{ $workOrder->municipality ?? 'غير متوفر' }}</td>
                                            </tr>
                                            <tr>
                                                <th>المكتب</th>
                                                <td>
                                                    @if($workOrder->office)
                                                        @switch($workOrder->office)
                                                            @case('خريص')
                                                                <span class="badge bg-primary">خريص</span>
                                                                @break
                                                            @case('الشرق')
                                                                <span class="badge bg-success">الشرق</span>
                                                                @break
                                                            @case('الشمال')
                                                                <span class="badge bg-info">الشمال</span>
                                                                @break
                                                            @case('الجنوب')
                                                                <span class="badge bg-warning">الجنوب</span>
                                                                @break
                                                            @case('الدرعية')
                                                                <span class="badge bg-secondary">الدرعية</span>
                                                                @break
                                                            @default
                                                                <span class="badge bg-light text-dark">{{ $workOrder->office }}</span>
                                                        @endswitch
                                                    @else
                                                        <span class="badge bg-light text-dark">غير محدد</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>رقم المحطة</th>
                                                <td>{{ $workOrder->station_number ?? 'غير متوفر' }}</td>
                                            </tr>
                                            <tr>
                                                <th>اسم الاستشاري</th>
                                                <td>{{ $workOrder->consultant_name ?? 'غير متوفر' }}</td>
                                            </tr>
                                            <tr>
                                                <th>اختبارات ما قبل التشغيل 211</th>
                                                <td>
                                                    @php
                                                        $preOperationTestsFile = $workOrder->files()
                                                            ->where('file_category', 'post_execution')
                                                            ->where('attachment_type', 'pre_operation_tests_file')
                                                            ->first();
                                                    @endphp
                                                    @if($preOperationTestsFile)
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                                                <div>
                                                                    <a href="{{ Storage::url($preOperationTestsFile->file_path) }}" 
                                                                       target="_blank" 
                                                                       class="text-decoration-none fw-bold">
                                                                        {{ $preOperationTestsFile->original_filename }}
                                                                    </a>
                                                                    <br>
                                                                    <small class="text-muted">
                                                                        <i class="fas fa-calendar me-1"></i>
                                                                        {{ $preOperationTestsFile->created_at->format('Y-m-d H:i') }}
                                                                        <span class="mx-2">|</span>
                                                                        <i class="fas fa-file-archive me-1"></i>
                                                                        {{ number_format($preOperationTestsFile->file_size / 1024 / 1024, 2) }} MB
                                                                    </small>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <a href="{{ Storage::url($preOperationTestsFile->file_path) }}" 
                                                                   target="_blank" 
                                                                   class="btn btn-sm btn-danger">
                                                                    <i class="fas fa-eye me-1"></i>عرض
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="text-center py-3">
                                                            <i class="fas fa-file-times fa-2x text-muted mb-2"></i>
                                                            <br>
                                                            <span class="text-muted">لا يوجد مرفق 211</span>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>مرفقات الفاتورة</th>
                                                <td>
                                                    @if($workOrder->invoiceAttachments->count() > 0)
                                                        <div class="row g-2">
                                                            @foreach($workOrder->invoiceAttachments as $attachment)
                                                                <div class="col-12 col-md-6">
                                                                    <div class="card h-100 border-0 shadow-sm">
                                                                        <div class="card-body p-2">
                                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                                <span class="badge bg-info">{{ $attachment->file_type ?? 'غير محدد' }}</span>
                                                                                <a href="{{ Storage::url($attachment->file_path) }}" 
                                                                                   target="_blank" 
                                                                                   class="btn btn-sm btn-info"
                                                                                   title="عرض الملف">
                                                                                    <i class="fas fa-eye"></i>
                                                                                </a>
                                                                            </div>
                                                                            <h6 class="card-title text-truncate mb-1" title="{{ $attachment->original_filename }}">
                                                                                {{ $attachment->original_filename }}
                                                                            </h6>
                                                                            @if($attachment->description)
                                                                                <p class="card-text small text-muted mb-1">{{ $attachment->description }}</p>
                                                                            @endif
                                                                            <small class="text-muted d-block">
                                                                                {{ $attachment->created_at->format('Y-m-d H:i') }}
                                                                            </small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="text-muted">لا توجد مرفقات</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>المرفق الاحتياطي</th>
                                                <td>
                                                    @php
                                                        $backupFile = $workOrder->files()
                                                            ->where('file_category', 'basic_attachments')
                                                            ->where('attachment_type', 'backup_1')
                                                            ->first();
                                                    @endphp
                                                    @if($backupFile)
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="d-flex align-items-center">
                                                                @php
                                                                    $fileExtension = strtolower(pathinfo($backupFile->original_filename, PATHINFO_EXTENSION));
                                                                    $fileIcon = 'fas fa-file';
                                                                    $iconColor = 'text-secondary';
                                                                    
                                                                    switch($fileExtension) {
                                                                        case 'pdf':
                                                                            $fileIcon = 'fas fa-file-pdf';
                                                                            $iconColor = 'text-danger';
                                                                            break;
                                                                        case 'doc':
                                                                        case 'docx':
                                                                            $fileIcon = 'fas fa-file-word';
                                                                            $iconColor = 'text-primary';
                                                                            break;
                                                                        case 'xls':
                                                                        case 'xlsx':
                                                                            $fileIcon = 'fas fa-file-excel';
                                                                            $iconColor = 'text-success';
                                                                            break;
                                                                        case 'jpg':
                                                                        case 'jpeg':
                                                                        case 'png':
                                                                        case 'gif':
                                                                            $fileIcon = 'fas fa-file-image';
                                                                            $iconColor = 'text-info';
                                                                            break;
                                                                    }
                                                                @endphp
                                                                <i class="{{ $fileIcon }} {{ $iconColor }} me-2"></i>
                                                                <div>
                                                                    <a href="{{ Storage::url($backupFile->file_path) }}" 
                                                                       target="_blank" 
                                                                       class="text-decoration-none fw-bold">
                                                                        {{ Str::limit($backupFile->original_filename, 30) }}
                                                                    </a>
                                                                    <br>
                                                                    <small class="text-muted">
                                                                        <i class="fas fa-calendar me-1"></i>
                                                                        {{ $backupFile->created_at->format('Y-m-d H:i') }}
                                                                        <span class="mx-2">|</span>
                                                                        <i class="fas fa-weight-hanging me-1"></i>
                                                                        {{ number_format($backupFile->file_size / 1024 / 1024, 2) }} MB
                                                                    </small>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex gap-1">
                                                                <a href="{{ Storage::url($backupFile->file_path) }}" 
                                                                   target="_blank" 
                                                                   class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-eye me-1"></i>عرض
                                                                </a>
                                                                <a href="{{ Storage::url($backupFile->file_path) }}" 
                                                                   download 
                                                                   class="btn btn-sm btn-outline-success">
                                                                    <i class="fas fa-download me-1"></i>تحميل
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="text-center py-2">
                                                            <i class="fas fa-file-times text-muted me-2"></i>
                                                            <span class="text-muted">لا يوجد مرفق</span>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- المعلومات المالية -->
                        <div class="col-12 col-lg-6">
                            <div class="custom-card">
                                <div class="card-header">
                                    <h4>
                                        <i class="fas fa-money-bill text-success me-2"></i>
                                        المعلومات المالية
                                    </h4>
                                </div>
                                <div class="card-body p-0">
                                    <table class="custom-table">
                                        <tbody>
                                            @php
                                                $license = $workOrder->licenses()->latest()->first();
                                                $totalTestsValue = 0;
                                                $totalLicenseValue = 0;
                                                
                                                if ($license) {
                                                    // قيمة الاختبارات
                                                    $successfulTests = $license->successful_tests_value ?? 0;
                                                    $failedTests = $license->failed_tests_value ?? 0;
                                                    $totalTestsValue = $successfulTests + $failedTests;
                                                    
                                                    // قيمة الرخص
                                                    $licenseValue = $license->license_value ?? 0;
                                                    $extensionValue = $license->extension_value ?? 0;
                                                    $evacuationValue = $license->evac_license_value ?? 0;
                                                    $totalLicenseValue = $licenseValue + $extensionValue + $evacuationValue;
                                                }
                                            @endphp
                                            
                                            <!-- قيم الاختبارات -->
                                            <tr>
                                                <th style="width: 40%">قيمة أمر العمل المبدئية شامل الاستشاري</th>
                                                <td>{{ number_format($workOrder->order_value_with_consultant, 2) }} ﷼</td>
                                            </tr>
                                            <tr>
                                                <th>قيمة أمر العمل المبدئية غير شامل الاستشاري</th>
                                                <td>{{ number_format($workOrder->order_value_without_consultant, 2) }} ﷼</td>
                                            </tr>
                                            <tr>
                                                <th>قيمة التنفيذ الفعلي شامل الاستشاري</th>
                                                <td>{{ $workOrder->actual_execution_value_consultant ? number_format($workOrder->actual_execution_value_consultant, 2) . ' ﷼' : 'غير متوفر' }}</td>
                                            </tr>
                                            <tr>
                                                <th>قيمة التنفيذ الفعلي غير شامل الاستشاري</th>
                                                <td>{{ $workOrder->actual_execution_value_without_consultant ? number_format($workOrder->actual_execution_value_without_consultant, 2) . ' ﷼' : 'غير متوفر' }}</td>
                                            </tr>
                                            <tr>
                                                <th>قيمة الدفعة الجزئية الأولى غير شامل الضريبة</th>
                                                <td>{{ $workOrder->first_partial_payment_without_tax ? number_format($workOrder->first_partial_payment_without_tax, 2, '.', '') . ' ﷼' : 'غير متوفر' }}</td>
                                            </tr>
                                            <tr>
                                                <th>قيمة الدفعة الجزئية الثانية غير شامل الضريبة</th>
                                                <td>{{ $workOrder->second_partial_payment_with_tax ? number_format($workOrder->second_partial_payment_with_tax, 2, '.', '') . ' ﷼' : 'غير متوفر' }}</td>
                                            </tr>
                                            <tr>
                                                <th>القيمة الكلية النهائية غير شامل الضريبة</th>
                                                <td>{{ $workOrder->final_total_value ? number_format($workOrder->final_total_value, 2, '.', '') . ' ﷼' : 'غير متوفر' }}</td>
                                            </tr>
                                            <tr>
                                                <th>قيمة اختبارات ما قبل التشغيل</th>
                                                <td>{{ $workOrder->pre_operation_tests ?? 'غير متوفر' }}</td>
                                            </tr>
                                            <tr>
                                                <th>قيمة الضريبة</th>
                                                <td>{{ $workOrder->tax_value ? number_format($workOrder->tax_value, 2) . ' ﷼' : 'غير متوفر' }}</td>
                                            </tr>
                                            <tr>
                                                <th>رقم أمر الشراء</th>
                                                <td>{{ $workOrder->purchase_order_number ?? 'غير متوفر' }}</td>
                                            </tr>
                                            <tr>
                                                <th>رقم المستخلص</th>
                                                <td>{{ $workOrder->extract_number ?? 'غير متوفر' }}</td>
                                            </tr>
                                            @if($workOrder->licenses->count() > 0)
                                                @php
                                                    $totalLicenseValue = 0;
                                                    $totalExtensionValue = 0;
                                                    $totalViolationValue = 0;
                                                    $totalEvacValue = 0;

                                                    foreach($workOrder->licenses as $license) {
                                                        $totalLicenseValue += $license->license_value ?? 0;
                                                        $totalExtensionValue += $license->extension_value ?? 0;
                                                        // استخدام النظام الجديد للمخالفات المتعددة
                                                        $totalViolationValue += $license->violations ? $license->violations->sum('violation_value') : 0;
                                                        $totalEvacValue += $license->evac_license_value ?? 0;
                                                    }
                                                @endphp
                                                <tr>
                                                    <th>قيمة الرخص</th>
                                                    <td>{{ number_format($totalLicenseValue, 2) }} ﷼</td>
                                                </tr>
                                                <tr>
                                                    <th>قيمة تمديد الرخص</th>
                                                    <td>{{ number_format($totalExtensionValue, 2) }} ﷼</td>
                                                </tr>
                                                <tr>
                                                    <th>قيمة المخالفات</th>
                                                    <td>{{ number_format($totalViolationValue, 2) }} ﷼</td>
                                                </tr>
                                                <tr>
                                                    <th>قيمة الإخلاءات</th>
                                                    <td>{{ number_format($totalEvacValue, 2) }} ﷼</td>
                                                </tr>
                                                <tr>
                                                    <th>قيمة الاختبارات الناجحة</th>
                                                    <td>
                                                        <span class="text-success">
                                                            {{ number_format($license->successful_tests_value ?? 0, 2) }} ﷼
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>قيمة الاختبارات الراسبة</th>
                                                    <td>
                                                        <span class="text-danger">
                                                            {{ number_format($license->failed_tests_value ?? 0, 2) }} ﷼
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 