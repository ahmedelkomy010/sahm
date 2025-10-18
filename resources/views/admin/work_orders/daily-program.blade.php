@extends('layouts.app')

@section('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
/* Responsive Styles for Mobile */
@media (max-width: 768px) {
    /* Header responsive */
    .mobile-header {
        font-size: 1.3rem !important;
        flex-direction: column;
        gap: 1rem;
    }
    
    .mobile-header h2 {
        font-size: 1.5rem !important;
        text-align: center;
    }
    
    /* Filter section responsive */
    .card-header .d-flex {
        flex-direction: column !important;
        gap: 0.5rem !important;
    }
    
    .card-header form {
        width: 100% !important;
    }
    
    .card-header .d-flex.gap-2 {
        width: 100%;
        justify-content: center;
    }
    
    /* Date input responsive */
    .card-header input[type="date"] {
        width: 100% !important;
        min-width: 100% !important;
    }
    
    /* Buttons responsive */
    .btn-sm {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    /* Table responsive - Card View */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Hide table on mobile, show cards instead */
    .desktop-table {
        display: none;
    }
    
    .mobile-cards {
        display: block;
    }
    
    .mobile-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 1rem;
        padding: 1rem;
        border-left: 4px solid #0d6efd;
    }
    
    .mobile-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    .mobile-card-header h5 {
        font-size: 1.1rem;
        font-weight: bold;
        color: #0d6efd;
        margin: 0;
    }
    
    .mobile-card-body {
        display: grid;
        gap: 0.5rem;
    }
    
    .mobile-field {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .mobile-field-label {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 600;
    }
    
    .mobile-field-value {
        font-size: 0.9rem;
        color: #212529;
    }
    
    .mobile-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
        padding-top: 0.75rem;
        border-top: 1px solid #e9ecef;
    }
    
    .mobile-actions .btn {
        flex: 1;
    }
    
    /* Modal responsive */
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .modal-xl {
        max-width: 95%;
    }
    
    .modal-body .row {
        margin: 0;
    }
    
    .modal-body .col-md-6,
    .modal-body .col-md-12 {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
}

@media (min-width: 769px) {
    .desktop-table {
        display: table;
    }
    
    .mobile-cards {
        display: none;
    }
}

/* Common responsive improvements */
.container-fluid {
    padding-left: 15px;
    padding-right: 15px;
}

@media (max-width: 576px) {
    .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
        padding-top: 1rem;
    }
    
    .card {
        margin-bottom: 1rem;
    }
    
    .btn {
        font-size: 0.8rem;
    }
}
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 mobile-header">
        <h2 class="mb-0" style="font-size:2.1rem; font-weight:800; color:#2c3e50; letter-spacing:1px; display:flex; align-items:center;">
            <i class="fas fa-calendar-day me-3" style="color:#ffc107;"></i>
            برنامج العمل اليومي
        </h2>
        <a href="{{ route('admin.work-orders.index', ['project' => $project]) }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-right"></i> عودة لأوامر العمل 
        </a>
    </div>

    {{-- رسائل التنبيه --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- فلتر التاريخ -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-warning text-white d-flex align-items-center justify-content-between flex-wrap gap-2">
            <form method="GET" class="d-flex align-items-center gap-3 flex-grow-1">
                <input type="hidden" name="project" value="{{ $project }}">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-calendar me-2"></i>
                    <label class="form-label mb-0 fw-bold">التاريخ:</label>
                    <input type="date" 
                           name="selected_date" 
                           class="form-control form-control-sm" 
                           value="{{ $selectedDate }}"
                           style="min-width: 180px; background: white; color: #000;">
                </div>
                <button type="submit" class="btn btn-light btn-sm">
                    <i class="fas fa-search me-1"></i>
                    عرض
                </button>
                <a href="{{ route('admin.work-orders.daily-program', ['project' => $project]) }}" class="btn btn-light btn-sm">
                    <i class="fas fa-sync me-1"></i>
                    اليوم
                </a>
                <a href="{{ route('admin.work-orders.daily-program', ['project' => $project, 'selected_date' => \Carbon\Carbon::tomorrow()->format('Y-m-d')]) }}" class="btn btn-light btn-sm">
                    <i class="fas fa-calendar-plus me-1"></i>
                    غداً
                </a>
            </form>
            <div class="d-flex gap-2">
                @if($programs->count() > 0)
                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#sendNotificationModal">
                    <i class="fas fa-bell me-1"></i>
                    إرسال البرنامج كإشعار
                </button>
                @endif
                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addToProgramModal">
                    <i class="fas fa-plus me-1"></i>
                       اضافة برنامج عمل يومي 
                </button>
            </div>
        </div>
    </div>

    <!-- الجدول -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-table me-2"></i>
            <strong>أوامر العمل المقررة اليوم ({{ $programs->count() }})</strong>
        </div>
        <div class="card-body p-0">
            @if($programs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th style="min-width: 80px;">#</th>
                            <th style="min-width: 150px;">رقم أمر العمل</th>
                            <th style="min-width: 200px;">نوع العمل</th>
                            <th style="min-width: 200px;">الموقع</th>
                            <th style="min-width: 200px;">إحداثيات جوجل</th>
                            <th style="min-width: 150px;">اسم الاستشاري</th>
                            <th style="min-width: 150px;">مهندس الموقع</th>
                            <th style="min-width: 150px;">المراقب</th>
                            <th style="min-width: 150px;">المصدر</th>
                            <th style="min-width: 150px;">المستلم</th>
                            <th style="min-width: 150px;">مسئول السلامة</th>
                            <th style="min-width: 150px;">مراقب الجودة</th>
                            <th style="min-width: 250px;">وصف العمل</th>
                            <th style="min-width: 250px;">ملاحظات</th>
                            <th style="min-width: 150px;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($programs as $index => $program)
                        <tr data-start-time="{{ $program->start_time ? \Carbon\Carbon::parse($program->start_time)->format('H:i') : '' }}"
                            data-end-time="{{ $program->end_time ? \Carbon\Carbon::parse($program->end_time)->format('H:i') : '' }}">
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.work-orders.show', $program->workOrder) }}" class="btn btn-link text-primary fw-bold">
                                    {{ $program->workOrder->order_number }}
                                </a>
                            </td>
                            <td>{{ $program->work_type ?? $program->workOrder->work_type }}</td>
                            <td>{{ $program->location }}</td>
                            <td>
                                @if($program->google_coordinates)
                                    <a href="https://www.google.com/maps?q={{ $program->google_coordinates }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-map-marker-alt"></i> فتح الخريطة
                                    </a>
                                @endif
                            </td>
                            <td>{{ $program->consultant_name }}</td>
                            <td>{{ $program->site_engineer }}</td>
                            <td>{{ $program->supervisor }}</td>
                            <td>{{ $program->issuer }}</td>
                            <td>{{ $program->receiver }}</td>
                            <td>{{ $program->safety_officer }}</td>
                            <td>{{ $program->quality_monitor }}</td>
                            <td>{{ $program->work_description }}</td>
                            <td>{{ $program->notes }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#editProgramModal{{ $program->id }}">
                                    <i class="fas fa-edit"></i> تعديل
                                </button>
                                <button type="button" class="btn btn-sm btn-info mb-1" data-bs-toggle="modal" data-bs-target="#attachmentsModal{{ $program->id }}">
                                    <i class="fas fa-paperclip"></i> مرفقات
                                </button>
                                <form action="{{ route('admin.work-orders.daily-program.destroy', $program) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <!-- <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                                        <i class="fas fa-trash"></i> حذف
                                    </button> -->
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editProgramModal{{ $program->id }}" tabindex="-1">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">تعديل بيانات البرنامج اليومي - {{ $program->workOrder->order_number }}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.work-orders.daily-program.update', $program) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">نوع العمل</label>
                                                    <input type="text" class="form-control" name="work_type" value="{{ $program->work_type }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">الموقع</label>
                                                    <input type="text" class="form-control" name="location" value="{{ $program->location }}">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label fw-bold">إحداثيات جوجل</label>
                                                    <input type="text" class="form-control" name="google_coordinates" value="{{ $program->google_coordinates }}" placeholder="25.123456, 45.654321">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">اسم الاستشاري</label>
                                                    <input type="text" class="form-control" name="consultant_name" value="{{ $program->consultant_name }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">مهندس الموقع</label>
                                                    <input type="text" class="form-control" name="site_engineer" value="{{ $program->site_engineer }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">المراقب</label>
                                                    <input type="text" class="form-control" name="supervisor" value="{{ $program->supervisor }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">المصدر</label>
                                                    <input type="text" class="form-control" name="issuer" value="{{ $program->issuer }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">المستلم</label>
                                                    <input type="text" class="form-control" name="receiver" value="{{ $program->receiver }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">مسئول السلامة</label>
                                                    <input type="text" class="form-control" name="safety_officer" value="{{ $program->safety_officer }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">مراقب الجودة</label>
                                                    <input type="text" class="form-control" name="quality_monitor" value="{{ $program->quality_monitor }}">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label fw-bold">وصف العمل</label>
                                                    <textarea class="form-control" name="work_description" rows="3">{{ $program->work_description }}</textarea>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label fw-bold">ملاحظات</label>
                                                    <textarea class="form-control" name="notes" rows="3">{{ $program->notes }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Attachments Modal -->
                        <div class="modal fade" id="attachmentsModal{{ $program->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-paperclip me-2"></i>
                                            مرفقات أمر العمل - {{ $program->workOrder->order_number }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        @php
                                            // مرفقات إنشاء أمر العمل
                                            $attachments = $program->workOrder->files()
                                                ->where('file_category', 'basic_attachments')
                                                ->get();
                                            
                                            // رخص الحفر المرتبطة مع المرفقات والتمديدات
                                            $licenses = $program->workOrder->licenses()->with(['attachments', 'extensions'])->get();
                                            
                                            // تصاريح السلامة
                                            $safetyPermitsImages = $program->workOrder->safety_permits_images ?? [];
                                            $safetyPermitsFiles = $program->workOrder->safety_permits_files ?? [];
                                        @endphp
                                        
                                        @if($attachments->count() > 0 || $licenses->count() > 0 || count($safetyPermitsImages) > 0 || count($safetyPermitsFiles) > 0)
                                            {{-- مرفقات إنشاء أمر العمل --}}
                                            @if($attachments->count() > 0)
                                                <h6 class="mb-3 text-primary">
                                                    <i class="fas fa-folder me-2"></i>
                                                    مرفقات إنشاء أمر العمل ({{ $attachments->count() }})
                                                </h6>
                                                <div class="list-group mb-4">
                                                    @foreach($attachments as $file)
                                                        <div class="list-group-item d-flex justify-content-between align-items-start">
                                                            <div class="d-flex align-items-center flex-grow-1">
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
                                                                <i class="{{ $fileIcon }} {{ $iconColor }} me-3 fa-2x"></i>
                                                                <div>
                                                                    <h6 class="mb-1">{{ $file->original_filename }}</h6>
                                                                    <small class="text-muted">
                                                                        <i class="fas fa-calendar me-1"></i>
                                                                        {{ $file->created_at->format('Y-m-d H:i') }}
                                                                        <span class="mx-2">|</span>
                                                                        <i class="fas fa-weight-hanging me-1"></i>
                                                                        {{ number_format($file->file_size / 1024 / 1024, 2) }} MB
                                                                    </small>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex gap-2">
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
                                                    @endforeach
                                                </div>
                                            @endif

                                            {{-- رخص الحفر --}}
                                            @if($licenses->count() > 0)
                                                @foreach($licenses as $license)
                                                    @php
                                                        // جمع كل ملفات الرخصة من الحقول المختلفة
                                                        $licenseFiles = [];
                                                        
                                                        // ملف الرخصة الأساسي
                                                        if($license->license_file_path && Storage::disk('public')->exists($license->license_file_path)) {
                                                            $licenseFiles[] = [
                                                                'path' => $license->license_file_path,
                                                                'name' => 'رخصة حفر رقم ' . $license->license_number,
                                                                'type' => 'رخصة حفر'
                                                            ];
                                                        }
                                                        
                                                        // شهادة التنسيق
                                                        if($license->coordination_certificate_path && Storage::disk('public')->exists($license->coordination_certificate_path)) {
                                                            $licenseFiles[] = [
                                                                'path' => $license->coordination_certificate_path,
                                                                'name' => 'شهادة تنسيق رقم ' . ($license->coordination_certificate_number ?? $license->license_number),
                                                                'type' => 'شهادة تنسيق'
                                                            ];
                                                        }
                                                        
                                                        // الرسائل والتعهدات
                                                        if($license->letters_and_commitments_path && Storage::disk('public')->exists($license->letters_and_commitments_path)) {
                                                            $licenseFiles[] = [
                                                                'path' => $license->letters_and_commitments_path,
                                                                'name' => 'رسائل وتعهدات',
                                                                'type' => 'رسائل وتعهدات'
                                                            ];
                                                        }
                                                        
                                                        // المرفقات من جدول license_attachments
                                                        if($license->attachments) {
                                                            foreach($license->attachments as $attachment) {
                                                                if(Storage::disk('public')->exists($attachment->file_path)) {
                                                                    $licenseFiles[] = [
                                                                        'path' => $attachment->file_path,
                                                                        'name' => $attachment->file_name ?? 'مرفق رخصة',
                                                                        'type' => $attachment->attachment_type ?? 'رخصة حفر'
                                                                    ];
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    
                                                    {{-- مرفقات الرخصة الأساسية --}}
                                                    @if(count($licenseFiles) > 0)
                                                        <h6 class="mb-3 text-success">
                                                            <i class="fas fa-file-contract me-2"></i>
                                                            رخصة الحفر رقم {{ $license->license_number }} ({{ count($licenseFiles) }})
                                                        </h6>
                                                        <div class="list-group mb-4">
                                                            @foreach($licenseFiles as $file)
                                                                <div class="list-group-item d-flex justify-content-between align-items-start">
                                                                    <div class="d-flex align-items-center flex-grow-1">
                                                                        <i class="fas fa-file-pdf text-danger me-3 fa-2x"></i>
                                                                        <div>
                                                                            <h6 class="mb-1">{{ $file['name'] }}</h6>
                                                                            <small class="text-muted">
                                                                                <span class="badge bg-success">{{ $file['type'] }}</span>
                                                                                @if($license->license_date)
                                                                                    <span class="mx-2">|</span>
                                                                                    <i class="fas fa-calendar me-1"></i>
                                                                                    {{ \Carbon\Carbon::parse($license->license_date)->format('Y-m-d') }}
                                                                                @endif
                                                                            </small>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex gap-2">
                                                                        <a href="{{ Storage::url($file['path']) }}" 
                                                                           target="_blank" 
                                                                           class="btn btn-sm btn-outline-primary"
                                                                           title="عرض الملف">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                        <a href="{{ Storage::url($file['path']) }}" 
                                                                           download 
                                                                           class="btn btn-sm btn-outline-success"
                                                                           title="تحميل الملف">
                                                                            <i class="fas fa-download"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- مرفقات التمديدات --}}
                                                    @if($license->extensions && $license->extensions->count() > 0)
                                                        @foreach($license->extensions as $extension)
                                                            @php
                                                                $extensionAttachments = is_array($extension->attachments) ? $extension->attachments : [];
                                                            @endphp
                                                            @if(count($extensionAttachments) > 0)
                                                                <h6 class="mb-3 text-warning">
                                                                    <i class="fas fa-clock me-2"></i>
                                                                    تمديد رخصة {{ $license->license_number }} - التمديد رقم {{ $loop->iteration }} ({{ count($extensionAttachments) }})
                                                                </h6>
                                                                <div class="list-group mb-4">
                                                                    @foreach($extensionAttachments as $filePath)
                                                                        @if($filePath)
                                                                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                                                                <div class="d-flex align-items-center flex-grow-1">
                                                                                    <i class="fas fa-file-pdf text-warning me-3 fa-2x"></i>
                                                                                    <div>
                                                                                        <h6 class="mb-1">
                                                                                            {{ basename($filePath) }}
                                                                                        </h6>
                                                                                        <small class="text-muted">
                                                                                            <span class="badge bg-warning text-dark">رخصة تمديد</span>
                                                                                            @if($extension->start_date && $extension->end_date)
                                                                                                <span class="mx-2">|</span>
                                                                                                من {{ \Carbon\Carbon::parse($extension->start_date)->format('Y-m-d') }}
                                                                                                إلى {{ \Carbon\Carbon::parse($extension->end_date)->format('Y-m-d') }}
                                                                                            @endif
                                                                                        </small>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="d-flex gap-2">
                                                                                    <a href="{{ Storage::url($filePath) }}" 
                                                                                       target="_blank" 
                                                                                       class="btn btn-sm btn-outline-primary"
                                                                                       title="عرض الملف">
                                                                                        <i class="fas fa-eye"></i>
                                                                                    </a>
                                                                                    <a href="{{ Storage::url($filePath) }}" 
                                                                                       download 
                                                                                       class="btn btn-sm btn-outline-success"
                                                                                       title="تحميل الملف">
                                                                                        <i class="fas fa-download"></i>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endif
                                            
                                            {{-- تصاريح السلامة (Permits) --}}
                                            @if(count($safetyPermitsImages) > 0 || count($safetyPermitsFiles) > 0)
                                                <h6 class="mb-3 text-danger">
                                                    <i class="fas fa-shield-alt me-2"></i>
                                                    تصاريح السلامة (Permits) ({{ count($safetyPermitsImages) + count($safetyPermitsFiles) }})
                                                </h6>
                                                <div class="list-group mb-4">
                                                    {{-- صور التصاريح --}}
                                                    @foreach($safetyPermitsImages as $index => $imagePath)
                                                        @if($imagePath && Storage::disk('public')->exists($imagePath))
                                                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                                                <div class="d-flex align-items-center flex-grow-1">
                                                                    <i class="fas fa-file-image text-info me-3 fa-2x"></i>
                                                                    <div>
                                                                        <h6 class="mb-1">صورة تصريح رقم {{ $index + 1 }}</h6>
                                                                        <small class="text-muted">
                                                                            <span class="badge bg-danger">تصريح سلامة</span>
                                                                            <span class="mx-2">|</span>
                                                                            <span class="badge bg-info">صورة</span>
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex gap-2">
                                                                    <a href="{{ Storage::url($imagePath) }}" 
                                                                       target="_blank" 
                                                                       class="btn btn-sm btn-outline-primary"
                                                                       title="عرض الصورة">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                    <a href="{{ Storage::url($imagePath) }}" 
                                                                       download 
                                                                       class="btn btn-sm btn-outline-success"
                                                                       title="تحميل الصورة">
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                    
                                                    {{-- ملفات التصاريح --}}
                                                    @foreach($safetyPermitsFiles as $index => $filePath)
                                                        @if($filePath && Storage::disk('public')->exists($filePath))
                                                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                                                <div class="d-flex align-items-center flex-grow-1">
                                                                    <i class="fas fa-file-pdf text-danger me-3 fa-2x"></i>
                                                                    <div>
                                                                        <h6 class="mb-1">{{ basename($filePath) }}</h6>
                                                                        <small class="text-muted">
                                                                            <span class="badge bg-danger">تصريح سلامة</span>
                                                                            <span class="mx-2">|</span>
                                                                            <span class="badge bg-warning text-dark">ملف PDF</span>
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex gap-2">
                                                                    <a href="{{ Storage::url($filePath) }}" 
                                                                       target="_blank" 
                                                                       class="btn btn-sm btn-outline-primary"
                                                                       title="عرض الملف">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                    <a href="{{ Storage::url($filePath) }}" 
                                                                       download 
                                                                       class="btn btn-sm btn-outline-success"
                                                                       title="تحميل الملف">
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        @else
                                            <div class="text-center py-5">
                                                <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                                                <p class="text-muted">لا توجد مرفقات لهذا الأمر</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                        <a href="{{ route('admin.work-orders.show', $program->workOrder) }}" class="btn btn-info" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>
                                            فتح صفحة أمر العمل
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <p class="text-muted fs-5">لا توجد أوامر عمل مقررة لليوم</p>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addToProgramModal">
                    <i class="fas fa-plus me-1"></i>
                    إضافة أمر عمل للبرنامج
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- جدول حالة البيانات المدخلة -->
    @if($programs->count() > 0)
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-check-circle me-2"></i>
                <strong>نسبة الالتزام اليومي</strong>
            </div>
            <a href="{{ route('admin.work-orders.daily-program.export-status', ['date' => request('date', now()->format('Y-m-d'))]) }}" 
               class="btn btn-light btn-sm">
                <i class="fas fa-file-excel me-1"></i>
                تصدير Excel
            </a>
        </div>
        <div class="card-body p-0">
            <!-- Desktop Table -->
            <div class="table-responsive desktop-table">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th style="min-width: 80px;">#</th>
                            <th style="min-width: 150px;">رقم أمر العمل</th>
                            <th style="min-width: 200px;">نوع العمل</th>
                            <th style="min-width: 120px;">المسح</th>
                            <th style="min-width: 120px;">المواد</th>
                            <th style="min-width: 120px;">الجودة</th>
                            <th style="min-width: 120px;">السلامة</th>
                            <th style="min-width: 120px;">التنفيذ</th>
                            <th style="min-width: 150px;">نسبة الالتزام</th>
                            <th style="min-width: 180px;">إدخال الإنتاجية اليومية</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($programs as $index => $program)
                        @php
                            $workOrder = $program->workOrder;
                            // التحقق من وجود بيانات المسح
                            $hasSurvey = $workOrder->surveys()->exists();
                            // التحقق من وجود المواد
                            $hasMaterials = $workOrder->materials()->exists() || $workOrder->workOrderMaterials()->exists();
                            // التحقق من وجود بيانات الجودة (شهادة تنسيق)
                            $hasQuality = $workOrder->licenses()
                                ->where(function($query) {
                                    $query->whereNotNull('coordination_certificate_path')
                                          ->orWhereNotNull('coordination_certificate_number');
                                })
                                ->exists();
                            // التحقق من وجود بيانات السلامة
                            $hasSafety = !empty($workOrder->safety_permits_images) || 
                                        !empty($workOrder->safety_permits_files) ||
                                        !empty($workOrder->safety_team_images) ||
                                        !empty($workOrder->safety_equipment_images) ||
                                        !empty($workOrder->safety_general_images) ||
                                        !empty($workOrder->safety_tbt_images) ||
                                        $workOrder->safetyViolations()->exists() ||
                                        $workOrder->safetyHistory()->exists();
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-link text-primary fw-bold">
                                    {{ $workOrder->order_number }}
                                </a>
                            </td>
                            <td>{{ $program->work_type ?? $workOrder->work_type }}</td>
                            <td class="text-center">
                                @if($hasSurvey)
                                    <i class="fas fa-check-circle text-success fa-2x" title="تم إدخال بيانات المسح"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger fa-2x" title="لم يتم إدخال بيانات المسح"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hasMaterials)
                                    <i class="fas fa-check-circle text-success fa-2x" title="تم إدخال بيانات المواد"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger fa-2x" title="لم يتم إدخال بيانات المواد"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hasQuality)
                                    <i class="fas fa-check-circle text-success fa-2x" title="تم إدخال بيانات الجودة"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger fa-2x" title="لم يتم إدخال بيانات الجودة"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hasSafety)
                                    <i class="fas fa-check-circle text-success fa-2x" title="تم إدخال بيانات السلامة"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger fa-2x" title="لم يتم إدخال بيانات السلامة"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input execution-checkbox" 
                                           type="checkbox" 
                                           id="execution_{{ $program->id }}"
                                           data-program-id="{{ $program->id }}"
                                           {{ $program->execution_completed ? 'checked' : '' }}
                                           style="width: 3rem; height: 1.5rem; cursor: pointer;">
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $program->execution_completed ? 'success' : 'danger' }} fs-6 execution-percentage" 
                                      id="percentage_{{ $program->id }}">
                                    {{ $program->execution_completed ? '100%' : '0%' }}
                                        </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.work-orders.execution', ['workOrder' => $workOrder->id]) }}" 
                                   class="btn btn-primary btn-sm"
                                   title="إدخال الإنتاجية اليومية">
                                    <i class="fas fa-tasks me-1"></i>
                                    التنفيذ
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                                    </div>
            
            <!-- Mobile Cards -->
            <div class="mobile-cards p-3">
                @foreach($programs as $index => $program)
                @php
                    $workOrder = $program->workOrder;
                    $hasSurvey = $workOrder->surveys()->exists();
                    $hasMaterials = $workOrder->materials()->exists() || $workOrder->workOrderMaterials()->exists();
                    $hasQuality = $workOrder->licenses()
                        ->where(function($query) {
                            $query->whereNotNull('coordination_certificate_path')
                                  ->orWhereNotNull('coordination_certificate_number');
                        })
                        ->exists();
                    $hasSafety = !empty($workOrder->safety_permits_images) || 
                                !empty($workOrder->safety_permits_files) ||
                                !empty($workOrder->safety_team_images) ||
                                !empty($workOrder->safety_equipment_images) ||
                                !empty($workOrder->safety_general_images) ||
                                !empty($workOrder->safety_tbt_images) ||
                                $workOrder->safetyViolations()->exists() ||
                                $workOrder->safetyHistory()->exists();
                @endphp
                <div class="mobile-card">
                    <div class="mobile-card-header">
                        <h5>#{{ $index + 1 }}</h5>
                        <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-sm btn-primary">
                            {{ $workOrder->order_number }}
                        </a>
                    </div>
                    <div class="mobile-card-body">
                        <div class="mobile-field">
                            <span class="mobile-field-label">نوع العمل</span>
                            <span class="mobile-field-value">{{ $program->work_type ?? $workOrder->work_type }}</span>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <div class="mobile-field text-center">
                                    <span class="mobile-field-label">المسح</span>
                                    @if($hasSurvey)
                                        <i class="fas fa-check-circle text-success fa-3x mt-2"></i>
                                @else
                                        <i class="fas fa-times-circle text-danger fa-3x mt-2"></i>
                                    @endif
                                    </div>
                                    </div>
                            <div class="col-6">
                                <div class="mobile-field text-center">
                                    <span class="mobile-field-label">المواد</span>
                                    @if($hasMaterials)
                                        <i class="fas fa-check-circle text-success fa-3x mt-2"></i>
                                    @else
                                        <i class="fas fa-times-circle text-danger fa-3x mt-2"></i>
                                @endif
                                    </div>
                            </div>
                            <div class="col-6 mt-3">
                                <div class="mobile-field text-center">
                                    <span class="mobile-field-label">الجودة</span>
                                    @if($hasQuality)
                                        <i class="fas fa-check-circle text-success fa-3x mt-2"></i>
                                @else
                                        <i class="fas fa-times-circle text-danger fa-3x mt-2"></i>
                                    @endif
                                    </div>
                                    </div>
                            <div class="col-6 mt-3">
                                <div class="mobile-field text-center">
                                    <span class="mobile-field-label">السلامة</span>
                                    @if($hasSafety)
                                        <i class="fas fa-check-circle text-success fa-3x mt-2"></i>
                                    @else
                                        <i class="fas fa-times-circle text-danger fa-3x mt-2"></i>
                                @endif
                                </div>
                            </div>
                            <div class="col-6 mt-3">
                                <div class="mobile-field text-center">
                                    <span class="mobile-field-label">التنفيذ</span>
                                    <div class="form-check form-switch d-flex justify-content-center mt-2">
                                        <input class="form-check-input execution-checkbox" 
                                               type="checkbox" 
                                               id="execution_mobile_{{ $program->id }}"
                                               data-program-id="{{ $program->id }}"
                                               {{ $program->execution_completed ? 'checked' : '' }}
                                               style="width: 3rem; height: 1.5rem; cursor: pointer;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mt-3">
                                <div class="mobile-field text-center">
                                    <span class="mobile-field-label">نسبة الالتزام</span>
                                    <span class="badge bg-{{ $program->execution_completed ? 'success' : 'danger' }} fs-5 mt-2 execution-percentage" 
                                          id="percentage_mobile_{{ $program->id }}">
                                        {{ $program->execution_completed ? '100%' : '0%' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mobile-actions">
                        <a href="{{ route('admin.work-orders.execution', ['workOrder' => $workOrder->id]) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-tasks me-1"></i>
                            إدخال الإنتاجية اليومية
                        </a>
                    </div>
                </div>
                        @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Add to Program Modal -->
<div class="modal fade" id="addToProgramModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">إضافة أمر عمل لبرنامج اليوم</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.work-orders.daily-program.store') }}" method="POST">
                @csrf
                <input type="hidden" name="program_date" value="{{ $selectedDate }}">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">اختر أمر العمل <span class="text-danger">*</span></label>
                            <select class="form-select work-order-select" id="workOrderSelect" name="work_order_id" required>
                                <option value="">-- ابحث عن رقم أمر العمل --</option>
                                @foreach($availableWorkOrders as $workOrder)
                                    @php
                                        $latestSurvey = $workOrder->surveys->first();
                                        $googleCoordinates = '';
                                        if ($latestSurvey) {
                                            if ($latestSurvey->start_coordinates) {
                                                $googleCoordinates = $latestSurvey->start_coordinates;
                                            } elseif ($latestSurvey->end_coordinates) {
                                                $googleCoordinates = $latestSurvey->end_coordinates;
                                            }
                                        }
                                    @endphp
                                    <option value="{{ $workOrder->id }}"
                                        data-work-type="{{ $workOrder->work_type }}"
                                        data-district="{{ $workOrder->district }}"
                                        data-consultant="{{ $workOrder->consultant_name }}"
                                        data-address="{{ $workOrder->address }}"
                                        data-google-coordinates="{{ $googleCoordinates }}">
                                        {{ $workOrder->order_number }} - {{ $workOrder->work_type }} - {{ $workOrder->district }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">نوع العمل</label>
                            <input type="text" class="form-control" id="workTypeInput" name="work_type">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">الموقع</label>
                            <input type="text" class="form-control" id="locationInput" name="location">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">إحداثيات جوجل</label>
                            <input type="text" class="form-control" id="googleCoordinatesInput" name="google_coordinates" placeholder="25.123456, 45.654321">
                            <div class="form-text">يمكنك الحصول على الإحداثيات من خرائط جوجل</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">اسم الاستشاري</label>
                            <input type="text" class="form-control" id="consultantInput" name="consultant_name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">مهندس الموقع</label>
                            <input type="text" class="form-control" id="siteEngineerInput" name="site_engineer">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">المراقب</label>
                            <input type="text" class="form-control" id="supervisorInput" name="supervisor">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">المصدر</label>
                            <input type="text" class="form-control" id="issuerInput" name="issuer">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">المستلم</label>
                            <input type="text" class="form-control" id="receiverInput" name="receiver">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">مسئول السلامة</label>
                            <input type="text" class="form-control" id="safetyOfficerInput" name="safety_officer">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">مراقب الجودة</label>
                            <input type="text" class="form-control" id="qualityMonitorInput" name="quality_monitor">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">وصف العمل</label>
                            <textarea class="form-control" name="work_description" rows="3"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">ملاحظات</label>
                            <textarea class="form-control" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>
                        إضافة للبرنامج
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Send Notification Modal -->
<div class="modal fade" id="sendNotificationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-bell me-2"></i>
                    إرسال برنامج العمل كإشعار
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    اختر المستخدمين الذين تريد إرسال برنامج العمل اليومي لهم كإشعار
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold mb-0">المستخدمين المتاحين:</label>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary" onclick="selectAllUsers()">
                                <i class="fas fa-check-double me-1"></i>
                                تحديد الكل
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="deselectAllUsers()">
                                <i class="fas fa-times me-1"></i>
                                إلغاء التحديد
                            </button>
                        </div>
                    </div>
                    
                    <div id="usersList" class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                        <p class="text-center text-muted">
                            <i class="fas fa-spinner fa-spin me-2"></i>
                            جاري تحميل المستخدمين...
                        </p>
                    </div>
                </div>
                
                <div id="selectedCount" class="alert alert-success d-none">
                    <i class="fas fa-users me-2"></i>
                    تم تحديد <strong><span id="countNumber">0</span></strong> مستخدم
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-info" onclick="sendToSelectedUsers()">
                    <i class="fas fa-paper-plane me-1"></i>
                    إرسال الإشعار
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .table-responsive {
        overflow-x: auto;
    }
    
    .table th, .table td {
        vertical-align: middle;
        white-space: nowrap;
    }
    
    .btn-link {
        text-decoration: none;
    }
    
    .btn-link:hover {
        text-decoration: underline;
    }

    /* Fix modal input issues */
    .modal {
        z-index: 1055 !important;
    }
    
    .modal-backdrop {
        z-index: 1050 !important;
    }
    
    .modal input,
    .modal select,
    .modal textarea {
        pointer-events: auto !important;
        user-select: text !important;
        -webkit-user-select: text !important;
        -moz-user-select: text !important;
        -ms-user-select: text !important;
    }
    
    .modal-body {
        pointer-events: auto !important;
    }
    
    .modal-content {
        pointer-events: auto !important;
    }

    /* Select2 styling */
    .select2-container {
        z-index: 9999 !important;
    }
    
    .select2-dropdown {
        z-index: 9999 !important;
    }
    
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
        font-size: 1rem;
    }
    
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
        padding-right: 15px;
        padding-left: 15px;
    }
    
    .select2-results__option {
        text-align: right;
        direction: rtl;
    }
    
    .select2-search__field {
        text-align: right;
        direction: rtl;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
// إصلاح مشكلة الكتابة في الـ modal
document.addEventListener('DOMContentLoaded', function() {
    // تفعيل Select2 على حقل اختيار أمر العمل
    $('#workOrderSelect').select2({
        theme: 'bootstrap-5',
        language: 'ar',
        placeholder: 'ابحث عن رقم أمر العمل',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#addToProgramModal')
    });

    // عند تغيير اختيار أمر العمل - ملء البيانات تلقائياً
    $('#workOrderSelect').on('change', function() {
        const selectedOption = $(this).find(':selected');
        
        if (selectedOption.val()) {
            // ملء نوع العمل
            $('#workTypeInput').val(selectedOption.data('work-type') || '');
            
            // ملء الموقع (الحي + العنوان)
            const district = selectedOption.data('district') || '';
            const address = selectedOption.data('address') || '';
            const location = district + (address ? ' - ' + address : '');
            $('#locationInput').val(location);
            
            // ملء اسم الاستشاري
            $('#consultantInput').val(selectedOption.data('consultant') || '');
            
            // ملء إحداثيات جوجل من آخر مسح
            const googleCoordinates = selectedOption.data('google-coordinates') || '';
            $('#googleCoordinatesInput').val(googleCoordinates);
            
            // إظهار رسالة نجاح
            console.log('✅ تم ملء البيانات الأساسية تلقائياً');
            if (googleCoordinates) {
                console.log('✅ تم ملء إحداثيات جوجل من المسح: ' + googleCoordinates);
            }
            
            // يمكن للمستخدم ملء باقي الحقول يدوياً
        } else {
            // مسح الحقول الأساسية
            $('#workTypeInput, #locationInput, #consultantInput, #googleCoordinatesInput').val('');
        }
    });

    // إعادة تهيئة Select2 عند فتح الـ modal
    $('#addToProgramModal').on('shown.bs.modal', function() {
        $('#workOrderSelect').select2({
            theme: 'bootstrap-5',
            language: 'ar',
            placeholder: 'ابحث عن رقم أمر العمل',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#addToProgramModal')
        });
    });

    // مسح الحقول عند إغلاق الـ modal
    $('#addToProgramModal').on('hidden.bs.modal', function() {
        $('#workOrderSelect').val('').trigger('change');
        $('#addToProgramModal input[type="text"], #addToProgramModal textarea').val('');
    });
    
    // عند فتح أي modal
    const modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        modal.addEventListener('shown.bs.modal', function() {
            // إلغاء أي events تمنع الكتابة
            const inputs = modal.querySelectorAll('input, select, textarea');
            inputs.forEach(function(input) {
                input.style.pointerEvents = 'auto';
                input.style.userSelect = 'text';
                input.removeAttribute('readonly');
                input.removeAttribute('disabled');
            });
        });
    });
});

// تصفية الجدول حسب الوقت
function filterByTime() {
    const startTime = document.getElementById('filterStartTime').value;
    const endTime = document.getElementById('filterEndTime').value;
    
    if (!startTime && !endTime) {
        alert('⚠️ من فضلك حدد على الأقل وقت البداية أو النهاية');
        return;
    }
    
    // حفظ القيم في localStorage
    if (startTime) {
        localStorage.setItem('dailyProgram_filterStartTime', startTime);
    }
    if (endTime) {
        localStorage.setItem('dailyProgram_filterEndTime', endTime);
    }
    
    const rows = document.querySelectorAll('tbody tr[data-start-time]');
    let visibleCount = 0;
    
    rows.forEach(function(row) {
        const rowStartTime = row.getAttribute('data-start-time');
        const rowEndTime = row.getAttribute('data-end-time');
        
        let shouldShow = true;
        
        // التصفية حسب وقت البداية
        if (startTime && rowStartTime) {
            shouldShow = shouldShow && (rowStartTime >= startTime);
        }
        
        // التصفية حسب وقت النهاية
        if (endTime && rowEndTime) {
            shouldShow = shouldShow && (rowEndTime <= endTime);
        }
        
        // إخفاء أو إظهار الصف
        if (shouldShow) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // تحديث العدد في الهيدر
    const headerText = document.querySelector('.card-header strong');
    if (headerText) {
        const totalCount = rows.length;
        headerText.innerHTML = `أوامر العمل المقررة اليوم (${visibleCount} من ${totalCount})`;
    }
    
    console.log(`✅ تم تصفية ${visibleCount} من ${rows.length} أوامر عمل`);
}

// إعادة تعيين التصفية
function resetTimeFilter() {
    // مسح القيم من الحقول
    document.getElementById('filterStartTime').value = '';
    document.getElementById('filterEndTime').value = '';
    
    // مسح القيم من localStorage
    localStorage.removeItem('dailyProgram_filterStartTime');
    localStorage.removeItem('dailyProgram_filterEndTime');
    
    // إظهار جميع الصفوف
    const rows = document.querySelectorAll('tbody tr[data-start-time]');
    rows.forEach(function(row) {
        row.style.display = '';
    });
    
    // تحديث العدد في الهيدر
    const headerText = document.querySelector('.card-header strong');
    if (headerText) {
        const totalCount = rows.length;
        headerText.innerHTML = `أوامر العمل المقررة اليوم (${totalCount})`;
    }
    
    console.log('✅ تم إعادة تعيين التصفية ومسح القيم المحفوظة');
}

// تحميل المستخدمين عند فتح modal الإشعارات
document.getElementById('sendNotificationModal').addEventListener('shown.bs.modal', function() {
    loadAvailableUsers();
});

// تحميل قائمة المستخدمين المتاحين
function loadAvailableUsers() {
    const project = '{{ $project }}';
    const usersList = document.getElementById('usersList');
    
    console.log('Loading users for project:', project);
    
    fetch('{{ route("admin.work-orders.daily-program.get-users") }}?project=' + project, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Users data:', data);
        
        if (data.success && data.users && data.users.length > 0) {
            let html = '<div class="row g-2">';
            data.users.forEach(user => {
                html += `
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input user-checkbox" type="checkbox" value="${user.id}" id="user_${user.id}" onchange="updateSelectedCount()">
                            <label class="form-check-label" for="user_${user.id}">
                                <i class="fas fa-user me-1 text-primary"></i>
                                ${user.name}
                            </label>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            usersList.innerHTML = html;
            console.log('Loaded', data.users.length, 'users');
        } else {
            const message = data.message || 'لا يوجد مستخدمون متاحون للمشروع المحدد';
            usersList.innerHTML = `<p class="text-center text-muted"><i class="fas fa-info-circle me-2"></i>${message}</p>`;
            console.log('No users found');
        }
    })
    .catch(error => {
        console.error('Error loading users:', error);
        usersList.innerHTML = `<p class="text-center text-danger"><i class="fas fa-exclamation-triangle me-2"></i>حدث خطأ أثناء تحميل المستخدمين: ${error.message}</p>`;
    });
}

// تحديد جميع المستخدمين
function selectAllUsers() {
    document.querySelectorAll('.user-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
    updateSelectedCount();
}

// إلغاء تحديد جميع المستخدمين
function deselectAllUsers() {
    document.querySelectorAll('.user-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateSelectedCount();
}

// تحديث عدد المستخدمين المحددين
function updateSelectedCount() {
    const selectedCount = document.querySelectorAll('.user-checkbox:checked').length;
    const countElement = document.getElementById('selectedCount');
    const countNumber = document.getElementById('countNumber');
    
    countNumber.textContent = selectedCount;
    
    if (selectedCount > 0) {
        countElement.classList.remove('d-none');
    } else {
        countElement.classList.add('d-none');
    }
}

// إرسال الإشعار للمستخدمين المحددين
function sendToSelectedUsers() {
    const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
    
    if (selectedUsers.length === 0) {
        alert('⚠️ من فضلك اختر مستخدم واحد على الأقل');
        return;
    }
    
    if (!confirm(`هل أنت متأكد من إرسال برنامج العمل اليومي لـ ${selectedUsers.length} مستخدم؟`)) {
        return;
    }
    
    const selectedDate = '{{ $selectedDate }}';
    const project = '{{ $project }}';
    
    // إظهار رسالة تحميل
    const sendBtn = event.target;
    const originalHTML = sendBtn.innerHTML;
    sendBtn.disabled = true;
    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> جاري الإرسال...';
    
    fetch('{{ route("admin.work-orders.daily-program.send-notification") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            selected_date: selectedDate,
            project: project,
            user_ids: selectedUsers
        })
    })
    .then(response => response.json())
    .then(data => {
        sendBtn.disabled = false;
        sendBtn.innerHTML = originalHTML;
        
        if (data.success) {
            alert('✅ ' + data.message);
            // إغلاق الـ modal
            bootstrap.Modal.getInstance(document.getElementById('sendNotificationModal')).hide();
        } else {
            alert('❌ ' + (data.message || 'حدث خطأ أثناء إرسال الإشعار'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        sendBtn.disabled = false;
        sendBtn.innerHTML = originalHTML;
        alert('❌ حدث خطأ أثناء إرسال الإشعار');
    });
}

// حفظ حالة التنفيذ
document.addEventListener('DOMContentLoaded', function() {
    const executionCheckboxes = document.querySelectorAll('.execution-checkbox');
    
    executionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const programId = this.getAttribute('data-program-id');
            const isCompleted = this.checked;
            
            // تحديث النسبة في كل الأماكن (desktop & mobile)
            updateExecutionPercentage(programId, isCompleted);
            
            // حفظ التغيير في الداتابيز
            saveExecutionStatus(programId, isCompleted);
        });
    });
});

function updateExecutionPercentage(programId, isCompleted) {
    // تحديث النسبة في Desktop
    const desktopPercentage = document.getElementById('percentage_' + programId);
    if (desktopPercentage) {
        desktopPercentage.textContent = isCompleted ? '100%' : '0%';
        desktopPercentage.classList.remove('bg-success', 'bg-danger');
        desktopPercentage.classList.add(isCompleted ? 'bg-success' : 'bg-danger');
    }
    
    // تحديث النسبة في Mobile
    const mobilePercentage = document.getElementById('percentage_mobile_' + programId);
    if (mobilePercentage) {
        mobilePercentage.textContent = isCompleted ? '100%' : '0%';
        mobilePercentage.classList.remove('bg-success', 'bg-danger');
        mobilePercentage.classList.add(isCompleted ? 'bg-success' : 'bg-danger');
    }
    
    // تزامن الـ checkboxes (desktop و mobile)
    const desktopCheckbox = document.getElementById('execution_' + programId);
    const mobileCheckbox = document.getElementById('execution_mobile_' + programId);
    
    if (desktopCheckbox) desktopCheckbox.checked = isCompleted;
    if (mobileCheckbox) mobileCheckbox.checked = isCompleted;
}

function saveExecutionStatus(programId, isCompleted) {
    fetch('{{ route("admin.work-orders.daily-program.update-execution") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            program_id: programId,
            execution_completed: isCompleted
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('✅ تم حفظ حالة التنفيذ بنجاح');
        } else {
            console.error('❌ فشل حفظ حالة التنفيذ');
            // إرجاع الـ checkbox للحالة السابقة
            updateExecutionPercentage(programId, !isCompleted);
        }
    })
    .catch(error => {
        console.error('❌ خطأ في حفظ حالة التنفيذ:', error);
        // إرجاع الـ checkbox للحالة السابقة
        updateExecutionPercentage(programId, !isCompleted);
    });
}
</script>
@endsection

