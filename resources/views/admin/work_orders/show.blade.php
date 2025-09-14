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
                                @if(auth()->user()->is_admin || 
                                    (auth()->user()->hasPermission('riyadh_manage_survey') && $project == 'riyadh') || 
                                    (auth()->user()->hasPermission('madinah_manage_survey') && $project == 'madinah'))
                                <a href="{{ route('admin.work-orders.survey', $workOrder) }}" class="custom-btn btn-survey">
                                    <i class="fas fa-map-marked-alt me-1"></i> المسح
                                </a>
                                @endif

                                @if(auth()->user()->is_admin || 
                                    (auth()->user()->hasPermission('riyadh_manage_materials') && $project == 'riyadh') || 
                                    (auth()->user()->hasPermission('madinah_manage_materials') && $project == 'madinah'))
                                <a href="{{ route('admin.work-orders.materials.index', $workOrder) }}" class="custom-btn btn-materials">
                                    <i class="fas fa-boxes me-1"></i> المواد
                                </a>
                                @endif

                                @if(auth()->user()->is_admin || 
                                    (auth()->user()->hasPermission('riyadh_manage_quality') && $project == 'riyadh') || 
                                    (auth()->user()->hasPermission('madinah_manage_quality') && $project == 'madinah'))
                                <a href="{{ route('admin.work-orders.license', $workOrder) }}" class="custom-btn btn-license">
                                    <i class="fas fa-file-alt me-1"></i> الجودة
                                </a>
                                @endif

                                @if(auth()->user()->is_admin || 
                                    (auth()->user()->hasPermission('riyadh_manage_execution') && $project == 'riyadh') || 
                                    (auth()->user()->hasPermission('madinah_manage_execution') && $project == 'madinah'))
                                <a href="{{ route('admin.work-orders.execution', $workOrder) }}" class="custom-btn btn-execution">
                                    <i class="fas fa-tools me-1"></i> تنفيذ
                                </a>
                                @endif

                                @if(auth()->user()->is_admin || 
                                    (auth()->user()->hasPermission('riyadh_manage_post_execution') && $project == 'riyadh') || 
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
                                                            // إذا لم يكن هناك تاريخ تسليم إجراء 155، استخدم الحساب القديم
                                                            $now = \Carbon\Carbon::now();
                                                            $daysSinceApproval = (int)$now->diffInDays($approvalDate);
                                                            $manualDays = (int)($workOrder->manual_days ?? 0);
                                                            $remainingDays = (int)max(0, $manualDays - $daysSinceApproval);
                                                            $overdueDays = (int)max(0, $daysSinceApproval - $manualDays);
                                                        }
                                                    @endphp
                                                    <div class="mt-2">
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>
                                                            @if($procedure155Date)
                                                                تاريخ تسليم إجراء 155: {{ $procedure155Date->format('Y-m-d') }}
                                                                <span class="mx-1">•</span>
                                                                <span class="text-success">
                                                                    <i class="fas fa-check-circle me-1"></i>
                                                                      تم التنفيذ    {{ $completionDays }} يوم
                                                                </span>
                                                            @else
                                                                مدة التنفيذ: {{ $manualDays ?? 0 }} يوم
                                                                @if(isset($daysSinceApproval) && $daysSinceApproval > 0)
                                                                    <span class="mx-1">•</span>
                                                                    منذ {{ $daysSinceApproval }} يوم من الاعتماد
                                                                @endif
                                                                @if($remainingDays > 0)
                                                                    <span class="mx-1">•</span>
                                                                    <span class="text-success">متبقي {{ $remainingDays }} يوم</span>
                                                                @else
                                                                    <span class="mx-1">•</span>
                                                                    <span class="text-danger">متأخر {{ $overdueDays }} يوم</span>
                                                                @endif
                                                            @endif
                                                        </small>
                                                    </div>
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
                                                <th>اختبارات ما قبل التشغيل 211</th>
                                                <td>
                                                    @php
                                                        $preOperationTestsFiles = $workOrder->files()
                                                            ->where('file_category', 'post_execution_files')
                                                            ->where('attachment_type', 'pre_operation_tests_file')
                                                            ->get();
                                                    @endphp
                                                    @if($preOperationTestsFiles->count() > 0)
                                                        <div class="row g-2">
                                                            @foreach($preOperationTestsFiles as $file)
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
                                                <td>{{ number_format($workOrder->order_value_with_consultant, 2) }} ﷼</td>
                                            </tr>
                                            <tr>
                                                <th>قيمة أمر العمل المبدئية غير شامل الاستشاري بدون الضريبة</th>
                                                <td>{{ number_format($workOrder->order_value_without_consultant, 2) }} ﷼</td>
                                            </tr>
                                            <tr>
                                                <th>قيمة التنفيذ الفعلي شامل الاستشاري بدون الضريبة</th>
                                                <td>{{ $workOrder->actual_execution_value_consultant ? number_format($workOrder->actual_execution_value_consultant, 2) . ' ﷼' : 'غير متوفر' }}</td>
                                            </tr>
                                            <tr>
                                                <th>قيمة التنفيذ الفعلي غير شامل الاستشاري بدون الضريبة</th>
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
                                                <th>قيمة الضريبة</th>
                                                <td>{{ $workOrder->tax_value ? number_format($workOrder->tax_value, 2) . ' ﷼' : 'غير متوفر' }}</td>
                                            </tr>
                                            <tr>
                                                <th>قيمة اختبارات ما قبل التشغيل</th>
                                                <td>{{ $workOrder->pre_operation_tests ?? 'غير متوفر' }}</td>
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
                                                <tr>
                                                    <th>عدد الرخص</th>
                                                    <td><span class="badge bg-info">{{ $workOrder->licenses->count() }} رخصة</span></td>
                                                </tr>
                                                <tr>
                                                    <th>إجمالي قيمة رخص الحفر</th>
                                                    <td><span class="text-primary fw-bold">{{ number_format($licensesTotals['total_license_value'] ?? 0, 2) }} ﷼</span></td>
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
                                                        if ($license->additional_details) {
                                                            $additionalDetails = json_decode($license->additional_details, true);
                                                            if (isset($additionalDetails['evacuation_data']) && is_array($additionalDetails['evacuation_data'])) {
                                                                foreach ($additionalDetails['evacuation_data'] as $evacData) {
                                                                    if (isset($evacData['evacuation_amount']) && is_numeric($evacData['evacuation_amount'])) {
                                                                        $evacuationAmount += floatval($evacData['evacuation_amount']);
                                                                        $totalEvacuationCount++;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        
                                                        $totalEvacuationValue += $evacuationAmount;
                                                    }
                                                @endphp
                                                <tr>
                                                    <th>إجمالي قيمة تمديدات الرخص</th>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <span class="badge bg-info fs-6">
                                                                {{ $totalExtensions }} تمديد
                                                            </span>
                                                            <span class="text-info fw-bold">
                                                                {{ number_format($totalExtensionValue, 2) }} ﷼
                                                            </span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>إجمالي قيمة اخلاءات الرخص</th>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            @if($totalEvacuationCount > 0)
                                                            <span class="badge bg-warning fs-6">
                                                                {{ $totalEvacuationCount }} إخلاء
                                                            </span>
                                                            @endif
                                                            <span class="text-warning fw-bold">
                                                                {{ number_format($totalEvacuationValue, 2) }} ﷼
                                                            </span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>إجمالي قيمة مخالفات الرخص</th>
                                                    <td>
                                                        <span class="text-danger fw-bold">{{ number_format($licensesTotals['total_violations_value'] ?? 0, 2) }} ﷼</span>
                                                        @if(($licensesTotals['total_violations_count'] ?? 0) > 0)
                                                            <br><small class="text-muted">({{ $licensesTotals['total_violations_count'] }} مخالفة)</small>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if(($licensesTotals['paid_violations_value'] ?? 0) > 0 || ($licensesTotals['pending_violations_value'] ?? 0) > 0)
                                                <tr>
                                                    <th>المخالفات المدفوعة</th>
                                                    <td><span class="text-success">{{ number_format($licensesTotals['paid_violations_value'] ?? 0, 2) }} ﷼</span></td>
                                                </tr>
                                                <tr>
                                                    <th>المخالفات المتأخرة</th>
                                                    <td><span class="text-warning">{{ number_format($licensesTotals['pending_violations_value'] ?? 0, 2) }} ﷼</span></td>
                                                </tr>
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
                                                    <th>إجمالي الاختبارات الناجحة</th>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            
                                                            <span class="text-success fw-bold">
                                                                {{ number_format($totalSuccessfulTestsValue, 2) }} ﷼
                                                            </span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>إجمالي الاختبارات الراسبة</th>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            
                                                            <span class="text-danger fw-bold">
                                                                {{ number_format($totalFailedTestsValue, 2) }} ﷼
                                                            </span>
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
</div>
@endsection 