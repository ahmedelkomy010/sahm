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
    
    .btn-safety { background-color:rgb(3, 102, 26); }
    .btn-safety:hover { background-color:rgb(17, 216, 61); }
    
    .btn-execution { background-color: #FF9800; }
    .btn-execution:hover { background-color: #FB8C00; }
    
    .btn-actions { background-color: #607D8B; }
    .btn-actions:hover { background-color: #546E7A; }
    
    .btn-back { background-color: #795548; }
    .btn-back:hover { background-color: #6D4C41; }

    .custom-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 20px;
        background-color: #fff;
        transition: all 0.3s ease;
    }
    
    .custom-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }

    .custom-card .card-header {
        background: linear-gradient(45deg, #f8f9fa, #ffffff);
        border-bottom: 1px solid #eee;
        padding: 12px 16px;
        border-radius: 12px 12px 0 0;
        display: flex;
        align-items: center;
    }
    
    .custom-card .card-header h4 {
        margin: 0;
        font-size: 16px;
        color: #333;
        font-weight: 600;
        display: flex;
        align-items: center;
    }
    
    .custom-card .card-header h4 i {
        font-size: 18px;
        margin-left: 8px;
        opacity: 0.8;
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
        border-collapse: collapse;
        background-color: #fff;
    }
    
    .custom-table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .custom-table tbody tr:not(:last-child) {
        border-bottom: 1px solid #e9ecef;
    }

    .custom-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        padding: 12px 15px;
        border: 1px solid #e9ecef;
    }

    .custom-table td {
        padding: 8px 12px;
        border: 1px solid #e9ecef;
        vertical-align: middle;
        font-size: 14px;
        line-height: 1.4;
    }
    
    .custom-table td .badge {
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 4px;
    }
    
    .custom-table td .text-success {
        color: #28a745 !important;
    }
    
    .custom-table td .text-danger {
        color: #dc3545 !important;
    }
    
    .custom-table td .text-warning {
        color: #ffc107 !important;
    }
    
    .custom-table td .text-info {
        color: #17a2b8 !important;
    }
    
    .custom-table td .fw-bold {
        font-weight: 600 !important;
    }
    
    .custom-table th {
        padding: 8px 12px;
        font-size: 14px;
        line-height: 1.4;
        background-color: #f8f9fa;
        font-weight: 600;
        border: 1px solid #e9ecef;
        width: 35%;
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
                            <div class="btn-group">
                                @if((auth()->user()->hasPermission('riyadh_manage_survey') && $project == 'riyadh') || 
                                    (auth()->user()->hasPermission('madinah_manage_survey') && $project == 'madinah'))
                                <a href="{{ route('admin.work-orders.survey', $workOrder) }}" class="custom-btn btn-survey">
                                    <i class="fas fa-map-marked-alt me-1"></i> المسح
                                </a>
                                @endif

                                @if((auth()->user()->hasPermission('riyadh_manage_materials') && $project == 'riyadh') || 
                                    (auth()->user()->hasPermission('madinah_manage_materials') && $project == 'madinah'))
                                <a href="{{ route('admin.work-orders.materials.index', $workOrder) }}" class="custom-btn btn-materials">
                                    <i class="fas fa-boxes me-1"></i> المواد
                                </a>
                                @endif

                                @if((auth()->user()->hasPermission('riyadh_manage_quality') && $project == 'riyadh') || 
                                    (auth()->user()->hasPermission('madinah_manage_quality') && $project == 'madinah'))
                                <a href="{{ route('admin.work-orders.license', $workOrder) }}" class="custom-btn btn-license">
                                    <i class="fas fa-file-alt me-1"></i> الجودة
                                </a>
                                @endif

                                @if((auth()->user()->hasPermission('riyadh_manage_safety') && $project == 'riyadh') || 
                                    (auth()->user()->hasPermission('madinah_manage_safety') && $project == 'madinah'))
                                <a href="{{ route('admin.work-orders.safety', $workOrder) }}" class="custom-btn btn-safety">
                                    <i class="fas fa-shield-alt me-1"></i> السلامة
                                </a>
                                @endif

                                @if((auth()->user()->hasPermission('riyadh_manage_execution') && $project == 'riyadh') || 
                                    (auth()->user()->hasPermission('madinah_manage_execution') && $project == 'madinah'))
                                <a href="{{ route('admin.work-orders.execution', $workOrder) }}" class="custom-btn btn-execution">
                                    <i class="fas fa-tools me-1"></i> تنفيذ
                                </a>
                                @endif

                                @if((auth()->user()->hasPermission('riyadh_manage_post_execution') && $project == 'riyadh') || 
                                    (auth()->user()->hasPermission('madinah_manage_post_execution') && $project == 'madinah'))
                                <a href="{{ route('admin.work-orders.actions-execution', $workOrder) }}" class="custom-btn btn-actions">
                                    <i class="fas fa-cogs me-1"></i> إجراءات ما بعد التنفيذ
                                </a>
                                @endif
                            </div>
                            
                            <a href="{{ route('admin.work-orders.index', ['project' => $project]) }}" class="custom-btn btn-back">
                                <i class="fas fa-arrow-right me-1"></i> 
                                عودة لأوامر العمل 
                                @if($project == 'riyadh')
                                @else
                                @endif
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
                                                <th>رقم أمر العمل</th>
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
                                                <th>تاريخ اجراء تسليم 155 :</th>
                                                <td>{{ $workOrder->procedure_155_delivery_date ? $workOrder->procedure_155_delivery_date->format('Y-m-d') : 'غير متوفر' }}</td>
                                            </tr>
                                            
                                            
                                            <tr>
                                                <th>تاريخ الاعتماد</th>
                                                <td>
                                                    {{ date('Y-m-d', strtotime($workOrder->approval_date)) }}
                                                    @php
                                                        $approvalDate = \Carbon\Carbon::parse($workOrder->approval_date);
                                                        $procedure155Date = $workOrder->procedure_155_delivery_date ? \Carbon\Carbon::parse($workOrder->procedure_155_delivery_date) : null;
                                                        
                                                        if ($procedure155Date) {
                                                            // حساب عدد الأيام اللي انتهى فيها أمر العمل (من تاريخ الاعتماد لتاريخ إجراء 155)
                                                            $completionDays = (int)$approvalDate->diffInDays($procedure155Date);
                                                            $remainingDays = 0; //   تم التنفيذ
                                                            $overdueDays = 0;
                                                        } else {
                                                            // حساب الأيام المتبقية على أساس مدة التنفيذ
                                                            $now = \Carbon\Carbon::now();
                                                            $daysSinceApproval = (int)$now->diffInDays($approvalDate);
                                                            $manualDays = (int)($workOrder->manual_days ?? 0);
                                                            
                                                            // حساب تاريخ انتهاء المدة المفترضة (تاريخ الاعتماد + مدة التنفيذ)
                                                            $expectedEndDate = $approvalDate->copy()->addDays($manualDays);
                                                            $daysUntilDeadline = (int)$now->diffInDays($expectedEndDate, false);
                                                            
                                                            if ($daysUntilDeadline > 0) {
                                                                $remainingDays = $daysUntilDeadline;
                                                                $overdueDays = 0;
                                                            } else {
                                                                $remainingDays = 0;
                                                                $overdueDays = abs($daysUntilDeadline);
                                                            }
                                                        }
                                                    @endphp
                                                    <div class="mt-2">
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>
                                                            @if($workOrder->execution_status == 9)
                                                                <span class="text-danger fw-bold">
                                                                    <i class="fas fa-times-circle me-1"></i>
                                                                    ملغي
                                                                </span>
                                                            @elseif($procedure155Date)
                                                             
                                                                <span class="text-success">
                                                                   
                                                                      تم التنفيذ    {{ $completionDays }} يوم
                                                                </span>
                                                            @else
                                                                مدة التنفيذ: {{ $manualDays ?? 0 }} يوم
                                                                @if(isset($daysSinceApproval) && $daysSinceApproval > 0)
                                                                    <span class="mx-1">•</span>
                                                                    مر {{ $daysSinceApproval }} يوم من الاعتماد
                                                                @endif
                                                                @php
                                                                    $expectedEndDate = $approvalDate->copy()->addDays($manualDays);
                                                                @endphp
                                                                <span class="mx-1">•</span>
                                                                <small class="text-info">الموعد المتوقع: {{ $expectedEndDate->format('Y-m-d') }}</small>
                                                                @if($remainingDays > 0)
                                                                    <span class="mx-1">•</span>
                                                                    <span class="text-success">
                                                                        <i class="fas fa-hourglass-half me-1"></i>
                                                                        متبقي {{ $remainingDays }} يوم
                                                                    </span>
                                                                @else
                                                                    <span class="mx-1">•</span>
                                                                    <span class="text-danger">
                                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                                        متأخر {{ $overdueDays }} يوم
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        </small>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>حالة التنفيذ</th>
                                                <td>
                                                    @switch($workOrder->execution_status)
                                                        @case('1')
                                                            <span class="badge bg-info">جاري العمل بالموقع</span>
                                                            @break
                                                        @case('2')
                                                            <span class="badge bg-warning">تم التنفيذ بالموقع وجاري تسليم 155</span>
                                                            @break
                                                        @case('3')
                                                            <span class="badge bg-warning">تم تسليم 155 جاري اصدار شهادة الانجاز</span>
                                                            @break
                                                        @case('4')
                                                            <span class="badge bg-primary">اعداد مستخلص الدفعة الجزئية الاولي وجاري الصرف</span>
                                                            @break
                                                        @case('5')
                                                            <span class="badge bg-secondary">تم صرف مستخلص الدفعة الجزئية الاولي</span>
                                                            @break
                                                        @case('6')
                                                            <span class="badge bg-success">اعداد مستخلص الدفعة الجزئية الثانية وجاري الصرف</span>
                                                            @break
                                                        @case('7')
                                                            <span class="badge bg-dark">تم الصرف وتم الانتهاء</span>
                                                            @break
                                                        @case('8')
                                                            <span class="badge bg-success">تم اصدار شهادة الانجاز</span>
                                                            @break
                                                        @case('9')
                                                            <span class="badge bg-danger">تم الالغاء او تحويل امر العمل</span>
                                                            @break
                                                        @case('10')
                                                            <span class="badge bg-info">تم اعداد المستخلص الكلي وجاري الصرف</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">{{ $workOrder->execution_status }}</span>
                                                    @endswitch
                                                </td>
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
                                                <th>رقم المهمة</th>
                                                <td>{{ $workOrder->task_number ?? 'غير متوفر' }}</td>
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
                                                <th>رقم أمر الشراء</th>
                                                <td>{{ $workOrder->purchase_order_number ?? 'غير متوفر' }}</td>
                                            </tr>
                                            <tr>
                                                <th>رقم المستخلص</th>
                                                <td>{{ $workOrder->extract_number ?? 'غير متوفر' }}</td>
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
                                                <th>المرفقات</th>
                                                <td>
                                                    @php
                                                        $attachments = $workOrder->files()
                                                            ->where('file_category', 'basic_attachments')
                                                            ->get();
                                                    @endphp
                                                    
                                                    @if($attachments->count() > 0)
                                                        <div class="row g-2">
                                                            @foreach($attachments as $file)
                                                                <div class="col-12">
                                                                    <div class="d-flex align-items-center justify-content-between p-2 bg-light rounded">
                                                                        <div class="d-flex align-items-center">
                                                                            @php
                                                                                $fileExtension = strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION));
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
                                                                                <a href="{{ Storage::url($file->file_path) }}" 
                                                                                   target="_blank" 
                                                                                   class="text-decoration-none fw-bold">
                                                                                    {{ $file->original_filename }}
                                                                                </a>
                                                                                <br>
                                                                                <small class="text-muted">
                                                                                    <i class="fas fa-calendar me-1"></i>
                                                                                    {{ $file->created_at->format('Y-m-d H:i') }}
                                                                                    <span class="mx-2">|</span>
                                                                                    <i class="fas fa-weight-hanging me-1"></i>
                                                                                    {{ number_format($file->file_size / 1024 / 1024, 2) }} MB
                                                                                </small>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-1">
                                                                            <a href="{{ Storage::url($file->file_path) }}" 
                                                                               target="_blank" 
                                                                               class="btn btn-sm btn-outline-primary"
                                                                               title="عرض الملف">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>
                                                                            <a href="{{ Storage::url($file->file_path) }}" 
                                                                               download="{{ $file->original_filename }}" 
                                                                               class="btn btn-sm btn-outline-success"
                                                                               title="تحميل الملف">
                                                                                <i class="fas fa-download"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="text-center py-2">
                                                            <i class="fas fa-file-times text-muted me-2"></i>
                                                            <span class="text-muted">لا توجد مرفقات</span>
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
                                                <th style="width: 40%">قيمة أمر العمل المبدئية شامل الاستشاري بدون الضريبة</th>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span>{{ number_format($workOrder->order_value_with_consultant, 2) }}</span>
                                                        <img src="{{ asset('images/Saudi_Riyal.png') }}" alt="ريال" style="width:16px; height:16px; margin-left: 3px; vertical-align: middle;">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>قيمة أمر العمل المبدئية غير شامل الاستشاري بدون الضريبة</th>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span>{{ number_format($workOrder->order_value_without_consultant, 2) }}</span>
                                                        <img src="{{ asset('images/Saudi_Riyal.png') }}" alt="ريال" style="width:16px; height:16px; margin-left: 3px; vertical-align: middle;">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>قيمة التنفيذ الفعلي شامل الاستشاري بدون الضريبة</th>
                                                <td>
                                                    @if($workOrder->actual_execution_value_consultant)
                                                        <div class="d-flex align-items-center">
                                                            <span>{{ number_format($workOrder->actual_execution_value_consultant, 2) }}</span>
                                                            <img src="{{ asset('images/Saudi_Riyal.png') }}" alt="ريال" style="width:16px; height:16px; margin-left: 3px; vertical-align: middle;">
                                                        </div>
                                                    @else
                                                        غير متوفر
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>قيمة التنفيذ الفعلي غير شامل الاستشاري بدون الضريبة</th>
                                                <td>
                                                    @if($workOrder->actual_execution_value_without_consultant)
                                                        <div class="d-flex align-items-center">
                                                            <span>{{ number_format($workOrder->actual_execution_value_without_consultant, 2) }}</span>
                                                            <img src="{{ asset('images/Saudi_Riyal.png') }}" alt="ريال" style="width:16px; height:16px; margin-left: 3px; vertical-align: middle;">
                                                        </div>
                                                    @else
                                                        غير متوفر
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>قيمة الدفعة الجزئية الأولى غير شامل الضريبة</th>
                                                <td>
                                                    @if($workOrder->first_partial_payment_without_tax)
                                                        <div class="d-flex align-items-center">
                                                            <span>{{ number_format($workOrder->first_partial_payment_without_tax, 2, '.', '') }}</span>
                                                            <img src="{{ asset('images/Saudi_Riyal.png') }}" alt="ريال" style="width:16px; height:16px; margin-left: 3px; vertical-align: middle;">
                                                        </div>
                                                    @else
                                                        غير متوفر
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>قيمة الدفعة الجزئية الثانية غير شامل الضريبة</th>
                                                <td>
                                                    @if($workOrder->second_partial_payment_with_tax)
                                                        <div class="d-flex align-items-center">
                                                            <span>{{ number_format($workOrder->second_partial_payment_with_tax, 2, '.', '') }}</span>
                                                            <img src="{{ asset('images/Saudi_Riyal.png') }}" alt="ريال" style="width:16px; height:16px; margin-left: 3px; vertical-align: middle;">
                                                        </div>
                                                    @else
                                                        غير متوفر
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>القيمة الكلية النهائية غير شامل الضريبة</th>
                                                <td>
                                                    @if($workOrder->final_total_value)
                                                        <div class="d-flex align-items-center">
                                                            <span>{{ number_format($workOrder->final_total_value, 2, '.', '') }}</span>
                                                            <img src="{{ asset('images/Saudi_Riyal.png') }}" alt="ريال" style="width:16px; height:16px; margin-left: 3px; vertical-align: middle;">
                                                        </div>
                                                    @else
                                                        غير متوفر
                                                    @endif
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <th>قيمة الضريبة</th>
                                                <td>
                                                    @if($workOrder->tax_value)
                                                        <div class="d-flex align-items-center">
                                                            <span>{{ number_format($workOrder->tax_value, 2) }}</span>
                                                            <img src="{{ asset('images/Saudi_Riyal.png') }}" alt="ريال" style="width:16px; height:16px; margin-left: 3px; vertical-align: middle;">
                                                        </div>
                                                    @else
                                                        غير متوفر
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>قيمة اختبارات ما قبل التشغيل</th>
                                                <td>{{ $workOrder->pre_operation_tests ?? 'غير متوفر' }}</td>
                                            </tr>
                                            
                                            <tr>
                                                <th>قيمة غرامات التأخير</th>
                                                <td>
                                                    @if($workOrder->delay_penalties)
                                                        <div class="d-flex align-items-center">
                                                            <span class="text-danger fw-bold">{{ number_format($workOrder->delay_penalties, 2) }}</span>
                                                            <img src="{{ asset('images/Saudi_Riyal.png') }}" alt="ريال" style="width:16px; height:16px; margin-left: 3px; vertical-align: middle;">
                                                        </div>
                                                    @else
                                                        <span class="text-muted">لا توجد غرامات</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>قيمة مخالفات السلامة</th>
                                                <td>
                                                    @if($totalSafetyViolations > 0)
                                                        <span class="text-danger fw-bold">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            <div class="d-flex align-items-center">
                                                                <span>{{ number_format($totalSafetyViolations, 2) }}</span>
                                                                <img src="{{ asset('images/Saudi_Riyal.png') }}" alt="ريال" style="width:16px; height:16px; margin-left: 3px; vertical-align: middle;">
                                                            </div>
                                                        </span>
                                                        <small class="text-muted d-block">
                                                            ({{ $workOrder->safetyViolations->count() }} مخالفة)
                                                        </small>
                                                    @else
                                                        <span class="text-success">
                                                            <i class="fas fa-check-circle me-1"></i>
                                                            لا توجد مخالفات سلامة
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if($workOrder->licenses->count() > 0)
                                                <tr>
                                                    <th>عدد الرخص</th>
                                                    <td><span class="badge bg-info">{{ $workOrder->licenses->count() }} رخصة</span></td>
                                                </tr>
                                                <tr>
                                                    <th>قيمة رخص الحفر</th>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <span class="text-primary fw-bold">{{ number_format($licensesTotals['total_license_value'] ?? 0, 2) }}</span>
                                                            <img src="{{ asset('images/Saudi_Riyal.png') }}" alt="ريال" style="width:16px; height:16px; margin-left: 3px; vertical-align: middle;">
                                                        </div>
                                                    </td>
                                                </tr>
                                                @php
                                                    // حساب إجمالي عدد التمديدات وقيمتها
                                                    $totalExtensions = 0;
                                                    $totalExtensionValue = 0;
                                                    foreach($workOrder->licenses as $license) {
                                                        $totalExtensions += $license->extensions()->count();
                                                        $totalExtensionValue += $license->extensions()->sum('extension_value');
                                                    }
                                                    
                                                    // حساب إجمالي مبلغ الإخلاء لجميع الرخص
                                                    $totalEvacuationValue = 0;
                                                    $totalEvacuationCount = 0;
                                                    foreach($workOrder->licenses as $license) {
                                                        // جمع مبالغ الإخلاء من الحقول المختلفة
                                                        $evacuationAmount = 0;
                                                        $evacuationAmount += ($license->evac_amount ?? 0);
                                                        $evacuationAmount += ($license->evac_license_value ?? 0);
                                                        
                                                        // جمع بيانات الإخلاء من additional_details إذا وجدت
                                                        if ($license->additional_details && isset($license->additional_details['evacuation_data']) && is_array($license->additional_details['evacuation_data'])) {
                                                            foreach ($license->additional_details['evacuation_data'] as $evacData) {
                                                                if (isset($evacData['evacuation_amount']) && is_numeric($evacData['evacuation_amount'])) {
                                                                    $evacuationAmount += floatval($evacData['evacuation_amount']);
                                                                    $totalEvacuationCount++;
                                                                }
                                                            }
                                                        }
                                                        
                                                        $totalEvacuationValue += $evacuationAmount;
                                                    }
                                                @endphp
                                                <tr>
                                                    <th> قيمة تمديدات الرخص</th>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <span class="badge bg-info fs-6">
                                                                {{ $totalExtensions }} تمديد
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="text-info fw-bold">{{ number_format($totalExtensionValue, 2) }}</span>
                                                                <img src="{{ asset('images/Saudi_Riyal.png') }}" alt="ريال" style="width:16px; height:16px; margin-left: 3px; vertical-align: middle;">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th> قيمة اخلاءات الرخص</th>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            @if($totalEvacuationCount > 0)
                                                            <span class="badge bg-warning fs-6">
                                                                {{ $totalEvacuationCount }} إخلاء
                                                            </span>
                                                            @endif
                                                            <div class="d-flex align-items-center">
                                                                <span class="text-warning fw-bold">{{ number_format($totalEvacuationValue, 2) }}</span>
                                                                <img src="{{ asset('images/Saudi_Riyal.png') }}" alt="ريال" style="width:16px; height:16px; margin-left: 3px; vertical-align: middle;">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th> قيمة مخالفات الرخص</th>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <span class="text-danger fw-bold">{{ number_format($licensesTotals['total_violations_value'] ?? 0, 2) }}</span>
                                                            <img src="{{ asset('images/Saudi_Riyal.png') }}" alt="ريال" style="width:16px; height:16px; margin-left: 3px; vertical-align: middle;">
                                                        </div>
                                                        @if(($licensesTotals['total_violations_count'] ?? 0) > 0)
                                                            <br><small class="text-muted">({{ $licensesTotals['total_violations_count'] }} مخالفة)</small>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if(($licensesTotals['paid_violations_value'] ?? 0) > 0 || ($licensesTotals['pending_violations_value'] ?? 0) > 0)
                                               
                                                @endif
                                                @php
                                                    $grandTotal = ($licensesTotals['total_license_value'] ?? 0) + ($licensesTotals['total_extension_value'] ?? 0) + $totalEvacuationValue + ($licensesTotals['total_violations_value'] ?? 0);
                                                @endphp
                                               
                                                @php 
                                                    // حساب إجمالي الاختبارات لجميع الرخص
                                                    $allLicenses = $workOrder->licenses;
                                                    $totalSuccessfulTests = $allLicenses->sum('successful_tests_count') ?? 0;
                                                    $totalFailedTests = $allLicenses->sum('failed_tests_count') ?? 0;
                                                    $totalSuccessfulTestsValue = $allLicenses->sum('successful_tests_value') ?? 0;
                                                    $totalFailedTestsValue = $allLicenses->sum('failed_tests_value') ?? 0;
                                                @endphp
                                                @if($allLicenses->count() > 0)
                                                <tr>
                                                    <th>قيمة الاختبارات الناجحة</th>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            
                                                            <div class="d-flex align-items-center">
                                                                <span class="text-success fw-bold">{{ number_format($totalSuccessfulTestsValue, 2) }}</span>
                                                                <img src="{{ asset('images/Saudi_Riyal.png') }}" alt="ريال" style="width:16px; height:16px; margin-left: 3px; vertical-align: middle;">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>قيمة الاختبارات الراسبة</th>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            
                                                            <div class="d-flex align-items-center">
                                                                <span class="text-danger fw-bold">{{ number_format($totalFailedTestsValue, 2) }}</span>
                                                                <img src="{{ asset('images/Saudi_Riyal.png') }}" alt="ريال" style="width:16px; height:16px; margin-left: 3px; vertical-align: middle;">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                            @else
                                                <tr>
                                                    <th>معلومات الرخص</th>
                                                    <td><span class="text-muted">لا توجد رخص مرتبطة بهذا أمر العمل</span></td>
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
    
    <!-- موقف أمر العمل -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header" style="background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); color: white;">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-check me-2"></i>
                        ملاحظات علي أمر العمل
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <textarea 
                            id="workOrderStatusNote"
                            class="form-control" 
                            rows="4" 
                            placeholder="اكتب ملاحظات علي أمر العمل هنا... (الحفظ تلقائي)"
                            style="font-size: 1rem; line-height: 1.8; resize: vertical; min-height: 100px;">{{ old('work_order_status_note', $workOrder->work_order_status_note) }}</textarea>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span id="statusNoteSaveIndicator" class="text-muted small">
                                <i class="fas fa-clock me-1"></i>
                                <span id="statusNoteSaveText">سيتم الحفظ تلقائياً</span>
                            </span>
                        </div>
                        
                        <div id="statusNoteLastUpdateInfo" class="text-muted small {{ $workOrder->status_note_updated_by ? '' : 'd-none' }}">
                            <i class="fas fa-user me-1"></i>
                            <span id="statusNoteUpdateUserName">{{ $workOrder->statusNoteUpdatedBy?->name }}</span>
                            <span class="mx-2">|</span>
                            <i class="fas fa-clock me-1"></i>
                            <span id="statusNoteUpdateDateTime">{{ $workOrder->status_note_updated_at?->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- تاريخ إنشاء أمر العمل -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="text-center">
                <small class="text-muted">
                    <i class="fas fa-calendar-plus me-1"></i>
                    تم إنشاء أمر العمل في: {{ $workOrder->created_at->format('Y-m-d H:i') }}
                </small>
            </div>
        </div>
    </div>

    <!-- قسم الملاحظات -->
    <div class="row mt-4 mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="mb-0">
                        <i class="fas fa-sticky-note me-2"></i>
                       (خارجي) ملاحظات على الأمر 
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.work-orders.update', $workOrder) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="_notes_only" value="1">
                        
                        <div class="mb-3">
                            <textarea 
                                name="notes"
                                class="form-control @error('notes') is-invalid @enderror" 
                                rows="6" 
                                placeholder="اكتب ملاحظاتك هنا..."
                                style="font-size: 1rem; line-height: 1.8; resize: vertical; min-height: 150px;">{{ old('notes', $workOrder->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>
                                حفظ الملاحظات
                            </button>
                            
                            @if(session('notes_success'))
                                <div class="alert alert-success mb-0 py-2 px-3">
                                    <i class="fas fa-check-circle me-1"></i>
                                    {{ session('notes_success') }}
                                </div>
                            @endif
                        </div>
                    </form>
                    
                    @if($workOrder->notes_updated_by)
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="fas fa-user me-2"></i>
                        <strong>{{ $workOrder->notesUpdatedBy?->name }}</strong>
                        <span class="mx-2">|</span>
                        <i class="fas fa-clock me-1"></i>
                        {{ $workOrder->notes_updated_at?->format('Y-m-d H:i') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// الحفظ التلقائي لموقف أمر العمل
(function() {
    const textarea = document.getElementById('workOrderStatusNote');
    const saveIndicator = document.getElementById('statusNoteSaveIndicator');
    const saveText = document.getElementById('statusNoteSaveText');
    
    if (!textarea) return;
    
    let timeout = null;
    let isFirstLoad = true;
    
    // وظيفة للحفظ
    function saveStatusNote() {
        const content = textarea.value;
        
        // تغيير المؤشر
        saveIndicator.className = 'text-warning small';
        saveText.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> جاري الحفظ...';
        
        // إرسال البيانات
        fetch('{{ route("admin.work-orders.update-status-note", $workOrder) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                work_order_status_note: content
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // نجح الحفظ
                saveIndicator.className = 'text-success small';
                saveText.innerHTML = '<i class="fas fa-check-circle me-1"></i> تم الحفظ';
                
                // تحديث معلومات اليوزر
                if (data.user_name) {
                    const lastUpdateInfo = document.getElementById('statusNoteLastUpdateInfo');
                    const userName = document.getElementById('statusNoteUpdateUserName');
                    const dateTime = document.getElementById('statusNoteUpdateDateTime');
                    
                    if (lastUpdateInfo && userName && dateTime) {
                        userName.textContent = data.user_name;
                        dateTime.textContent = data.updated_at || '';
                        lastUpdateInfo.classList.remove('d-none');
                    }
                }
                
                // إرجاع المؤشر بعد 3 ثوانٍ
                setTimeout(() => {
                    saveIndicator.className = 'text-muted small';
                    saveText.innerHTML = '<i class="fas fa-clock me-1"></i> سيتم الحفظ تلقائياً';
                }, 3000);
            } else {
                // فشل الحفظ
                saveIndicator.className = 'text-danger small';
                saveText.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i> فشل الحفظ';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            saveIndicator.className = 'text-danger small';
            saveText.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i> حدث خطأ';
        });
    }
    
    // مراقبة التغييرات في النص
    textarea.addEventListener('input', function() {
        // إلغاء المؤقت السابق
        if (timeout) {
            clearTimeout(timeout);
        }
        
        // تخطي الحفظ في أول مرة
        if (isFirstLoad) {
            isFirstLoad = false;
            return;
        }
        
        // تعيين مؤقت جديد للحفظ بعد ثانيتين من التوقف عن الكتابة
        saveIndicator.className = 'text-info small';
        saveText.innerHTML = '<i class="fas fa-pencil-alt me-1"></i> جاري الكتابة...';
        
        timeout = setTimeout(saveStatusNote, 2000);
    });
    
    // إعادة تعيين العلامة عند التركيز
    textarea.addEventListener('focus', function() {
        isFirstLoad = false;
    });
})();
</script>
@endpush

@endsection