@extends('layouts.admin')
@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'تفاصيل الرخصة رقم ' . $license->license_number)

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white shadow-lg border-0">
                <div class="card-body py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-2 fw-bold">
                                <i class="fas fa-file-contract me-3"></i>
                                تفاصيل رخصة الحفر
                            </h1>
                            <p class="mb-0 opacity-75">رقم الرخصة: {{ $license->license_number }}</p>
                            <p class="mb-0 opacity-75">أمر العمل: {{ $license->workOrder->order_number ?? 'غير محدد' }}</p>
                        </div>
                        <div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.licenses.export-pdf', $license->id) }}" 
                                   class="btn btn-light btn-lg">
                                    <i class="fas fa-file-pdf me-2"></i>
                                    تصدير PDF
                                </a>
                                <a href="{{ route('admin.work-orders.license', $license->workOrder) }}" 
                                   class="btn btn-light btn-lg">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    العودة لإدارة الرخص
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Right Column - Main Info -->
        <div class="col-lg-8">
            <!-- Basic License Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        المعلومات الأساسية
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="fw-bold text-muted small">رقم الرخصة</label>
                                <p class="mb-0 fs-5 fw-semibold text-primary">{{ $license->license_number }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="fw-bold text-muted small">نوع الرخصة</label>
                                <p class="mb-0">
                                    @switch($license->license_type)
                                        @case('emergency')
                                            <span class="badge bg-danger fs-6">طوارئ</span>
                                            @break
                                        @case('project')
                                            <span class="badge bg-info fs-6">مشروع</span>
                                            @break
                                        @case('normal')
                                            <span class="badge bg-success fs-6">عادي</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary fs-6">غير محدد</span>
                                    @endswitch
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="fw-bold text-muted small">قيمة الرخصة</label>
                                <p class="mb-0 fs-5 fw-bold text-success">
                                    {{ number_format($license->license_value ?? 0, 2) }} ريال
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="fw-bold text-muted small">حالة الرخصة</label>
                                <p class="mb-0">
                                    <span class="badge bg-warning fs-6">نشطة</span>
                    </p>
                            </div>
                        </div>
                </div>
            </div>
        </div>

            <!-- License Dates -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                        تواريخ الرخصة
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-calendar-plus text-info fa-2x mb-2"></i>
                                <h6 class="fw-bold">تاريخ الإصدار</h6>
                                <p class="mb-0 fw-semibold">
                                    {{ $license->license_date ? $license->license_date->format('Y/m/d') : 'غير محدد' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-play-circle text-success fa-2x mb-2"></i>
                                <h6 class="fw-bold">تاريخ التفعيل</h6>
                                <p class="mb-0 fw-semibold">
                                    {{ $license->license_start_date ? $license->license_start_date->format('Y/m/d') : 'غير محدد' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-stop-circle text-danger fa-2x mb-2"></i>
                                <h6 class="fw-bold">تاريخ الانتهاء</h6>
                                <p class="mb-0 fw-semibold">
                                    {{ $license->license_end_date ? $license->license_end_date->format('Y/m/d') : 'غير محدد' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-clock me-2"></i>
                            <strong>مدة الرخصة:</strong>
                            @if($license->license_start_date && $license->license_end_date)
                                {{ $license->license_start_date->diffInDays($license->license_end_date) }} يوم
                            @else
                                غير محدد
                    @endif
                        </div>
                </div>
            </div>
        </div>

            <!-- Excavation Dimensions -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-ruler-combined text-primary me-2"></i>
                        أبعاد الحفر
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-arrows-alt-h text-primary fa-2x mb-2"></i>
                                <h6 class="fw-bold">الطول</h6>
                                <p class="mb-0 fs-4 fw-bold text-primary">
                                    {{ number_format($license->excavation_length ?? 0, 2) }}
                                    <small class="text-muted">متر</small>
                    </p>
                </div>
            </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-arrows-alt-v text-success fa-2x mb-2"></i>
                                <h6 class="fw-bold">العرض</h6>
                                <p class="mb-0 fs-4 fw-bold text-success">
                                    {{ number_format($license->excavation_width ?? 0, 2) }}
                                    <small class="text-muted">متر</small>
                                </p>
                </div>
            </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-level-down-alt text-warning fa-2x mb-2"></i>
                                <h6 class="fw-bold">العمق</h6>
                                <p class="mb-0 fs-4 fw-bold text-warning">
                                    {{ number_format($license->excavation_depth ?? 0, 2) }}
                                    <small class="text-muted">متر</small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <div class="alert alert-secondary mb-0">
                            <i class="fas fa-cube me-2"></i>
                            <strong>الحجم الإجمالي:</strong>
                            {{ number_format(($license->excavation_length ?? 0) * ($license->excavation_width ?? 0) * ($license->excavation_depth ?? 0), 2) }} متر مكعب
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Left Column - Additional Info -->
        <div class="col-lg-4">
            <!-- Coordination Certificate -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-certificate me-2"></i>
                        شهادة التنسيق
                    </h5>
                        </div>
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-certificate fa-3x text-warning mb-3"></i>
                        <h6 class="fw-bold">رقم شهادة التنسيق</h6>
                        <p class="fs-5 fw-semibold text-primary">
                            {{ $license->coordination_certificate_number ?? 'غير محدد' }}
                        </p>
                    </div>
                    </div>
                </div>

            <!-- Restriction Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-ban me-2"></i>
                        معلومات الحظر
                    </h5>
                        </div>
                <div class="card-body">
                    <div class="text-center">
                        @if($license->has_restriction)
                            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                            <div class="alert alert-danger">
                                <h6 class="fw-bold">يوجد حظر</h6>
                                @if($license->restriction_authority)
                                    <p class="mb-1"><strong>جهة الحظر:</strong> {{ $license->restriction_authority }}</p>
                                @endif
                                @if($license->restriction_reason)
                                    <p class="mb-0"><strong>سبب الحظر:</strong> {{ $license->restriction_reason }}</p>
                                @endif
                            </div>
                        @else
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <div class="alert alert-success mb-0">
                                <h6 class="fw-bold mb-0">لا يوجد حظر</h6>
                            </div>
                        @endif
                    </div>
                    </div>
                </div>

            <!-- Quick Stats -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        إحصائيات سريعة
                    </h5>
                        </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-2 bg-light rounded">
                                <i class="fas fa-clock text-primary"></i>
                                <h6 class="small fw-bold mt-1 mb-1">التمديدات</h6>
                                <span class="badge bg-primary">{{ $license->extensions?->count() ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-2 bg-light rounded">
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                <h6 class="small fw-bold mt-1 mb-1">المخالفات</h6>
                                <span class="badge bg-warning">{{ $license->violations?->count() ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                    </div>
                </div>

    <!-- Attachments Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-paperclip me-2"></i>
                        مرفقات الرخصة
                    </h4>
                        </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- License File -->
                        <div class="col-md-3">
                            <div class="attachment-card text-center p-3 border rounded h-100">
                                <div class="attachment-icon mb-3">
                                    <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                </div>
                                <h6 class="fw-bold">ملف الرخصة</h6>
                                @if($license->license_file_path)
                                    <a href="{{ Storage::disk('public')->url($license->license_file_path) }}" 
                                       class="btn btn-outline-primary btn-sm" target="_blank">
                                        <i class="fas fa-eye me-1"></i>عرض
                            </a>
                        @else
                                    <span class="text-muted small">غير متوفر</span>
                        @endif
                    </div>
                </div>

                        <!-- Coordination Certificate -->
                        <div class="col-md-3">
                            <div class="attachment-card text-center p-3 border rounded h-100">
                                <div class="attachment-icon mb-3">
                                    <i class="fas fa-certificate fa-3x text-warning"></i>
                        </div>
                                <h6 class="fw-bold">شهادة التنسيق</h6>
                                @if($license->coordination_certificate_path)
                                    <a href="{{ Storage::disk('public')->url($license->coordination_certificate_path) }}" 
                                       class="btn btn-outline-warning btn-sm" target="_blank">
                                        <i class="fas fa-eye me-1"></i>عرض
                            </a>
                        @else
                                    <span class="text-muted small">غير متوفر</span>
                        @endif
                    </div>
                </div>

                        <!-- Payment Proof -->
                        <div class="col-md-3">
                            <div class="attachment-card text-center p-3 border rounded h-100">
                                <div class="attachment-icon mb-3">
                                    <i class="fas fa-receipt fa-3x text-success"></i>
                        </div>
                                <h6 class="fw-bold">إثبات السداد</h6>
                        @if($license->payment_proof_path)
                                    <a href="{{ Storage::disk('public')->url($license->payment_proof_path) }}" 
                                       class="btn btn-outline-success btn-sm" target="_blank">
                                        <i class="fas fa-eye me-1"></i>عرض
                            </a>
                        @else
                                    <span class="text-muted small">غير متوفر</span>
                        @endif
                    </div>
                </div>

                        <!-- Activation File -->
                        <div class="col-md-3">
                            <div class="attachment-card text-center p-3 border rounded h-100">
                                <div class="attachment-icon mb-3">
                                    <i class="fas fa-power-off fa-3x text-info"></i>
                        </div>
                                <h6 class="fw-bold">ملف التفعيل</h6>
                                @if($license->license_activation_path)
                                    <a href="{{ Storage::disk('public')->url($license->license_activation_path) }}" 
                                       class="btn btn-outline-info btn-sm" target="_blank">
                                        <i class="fas fa-eye me-1"></i>عرض
                                    </a>
                                @else
                                    <span class="text-muted small">غير متوفر</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
                </div>
            </div>

        <!-- Letters and Commitments Attachment Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-file-contract me-2"></i>
                        مرفقات الخطابات والتعهدات
                    </h5>
                </div>
                <div class="card-body">
                    @if($license->letters_commitments_file_path)
                        <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">الخطابات والتعهدات</h6>
                                    <p class="mb-0 text-muted small">
                                        <i class="fas fa-clock me-1"></i>
                                        تم الرفع: {{ $license->updated_at ? $license->updated_at->format('Y-m-d H:i') : 'غير محدد' }}
                                    </p>
                                </div>
                            </div>
                            <div class="btn-group">
                                @php
                                    $filePaths = json_decode($license->letters_commitments_file_path, true);
                                    $filePath = is_array($filePaths) ? $filePaths[0] : $license->letters_commitments_file_path;
                                @endphp
                                <a href="{{ Storage::disk('public')->url($filePath) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>
                                    عرض المرفق
                                </a>
                                <a href="{{ Storage::disk('public')->url($filePath) }}" 
                                   download 
                                   class="btn btn-outline-success">
                                    <i class="fas fa-download me-1"></i>
                                    تحميل
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-file-upload fa-3x mb-3"></i>
                            <p class="mb-0">لا يوجد مرفق للخطابات والتعهدات</p>
                            <small class="text-muted">يمكن إضافة المرفق من خلال صفحة تحرير الرخصة</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Extensions Record Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-calendar-plus me-2"></i>
                         سجل تمديد الرخص
                    </h5>
                </div>
                <div class="card-body">
            @if($license->extensions && $license->extensions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>رقم التمديد</th>
                                        <th>تاريخ التمديد</th>
                                        <th>المدة الجديدة</th>
                                        <th>تاريخ الانتهاء الجديد</th>
                                        <th>السبب</th>
                                        <th>الحالة</th>
                            </tr>
                        </thead>
                                <tbody>
                                    @foreach($license->extensions as $extension)
                                    <tr>
                                        <td><span class="badge bg-primary">{{ $extension->extension_number ?? 'غير محدد' }}</span></td>
                                        <td>
                                            <i class="fas fa-calendar-alt text-muted me-1"></i>
                                            {{ $extension->extension_date ? $extension->extension_date->format('Y-m-d') : 'غير محدد' }}
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $extension->new_duration ?? 'غير محدد' }} يوم</span>
                                        </td>
                                        <td>
                                            <i class="fas fa-calendar-check text-success me-1"></i>
                                            {{ $extension->new_end_date ? $extension->new_end_date->format('Y-m-d') : 'غير محدد' }}
                                        </td>
                                        <td>{{ $extension->reason ?? 'غير محدد' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $extension->status == 'approved' ? 'success' : ($extension->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ $extension->status == 'approved' ? 'معتمد' : ($extension->status == 'pending' ? 'قيد المراجعة' : 'مرفوض') }}
                                            </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-calendar-times fa-3x mb-3"></i>
                            <p class="mb-0">لا توجد تمديدات مسجلة لهذه الرخصة</p>
                </div>
            @endif
        </div>
                        </div>
                </div>
            </div>

    <!-- Laboratory Tests Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-flask me-2"></i>
                        جدول الاختبارات 
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $testsData = [];
                        if ($license->lab_tests_data) {
                            $testsDataRaw = json_decode($license->lab_tests_data, true);
                            if (is_array($testsDataRaw)) {
                                $testsData = $testsDataRaw;
                            }
                        }
                @endphp
                    
                    @if(!empty($testsData) && count($testsData) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>نوع الاختبار</th>
                                        <th>عدد النقاط</th>
                                        <th>السعر</th>
                                        <th>الإجمالي</th>
                                        <th>النتيجة</th>
                                        <th>المرفقات</th>
                            </tr>
                        </thead>
                                <tbody>
                            @foreach($testsData as $index => $test)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $index + 1 }}</span>
                                    </td>
                                        <td class="fw-bold">{{ $test['name'] ?? 'غير محدد' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ number_format($test['points'] ?? 0) }} نقطة</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ number_format($test['price'] ?? 0, 2) }} ريال</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ number_format($test['total'] ?? 0, 2) }} ريال</span>
                                        </td>
                                        <td>
                                            @if(isset($test['result']))
                                                <span class="badge bg-{{ $test['result'] == 'pass' ? 'success' : 'danger' }}">
                                                    <i class="fas fa-{{ $test['result'] == 'pass' ? 'check' : 'times' }} me-1"></i>
                                                    {{ $test['result'] == 'pass' ? 'نجح' : 'فشل' }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">غير محدد</span>
                                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($test['file_url']) && $test['file_url'])
                                                <a href="{{ $test['file_url'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-file me-1"></i>عرض المرفق
                                                </a>
                                            @elseif(isset($test['fileUrl']) && $test['fileUrl'])
                                                <a href="{{ $test['fileUrl'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-file me-1"></i>عرض المرفق
                                                </a>
                                        @else
                                                <span class="text-muted">لا يوجد</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="2" class="fw-bold">الإجمالي</td>
                                        <td>
                                            <span class="badge bg-info">{{ number_format(collect($testsData)->sum('points')) }} نقطة</span>
                                </td>
                                        <td></td>
                                        <td>
                                            <span class="badge bg-success">{{ number_format(collect($testsData)->sum('total'), 2) }} ريال</span>
                                </td>
                                        <td colspan="2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-success me-2">
                                                    <i class="fas fa-check me-1"></i>
                                                    {{ collect($testsData)->where('result', 'pass')->count() }} نجح
                                                </span>
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times me-1"></i>
                                                    {{ collect($testsData)->where('result', 'fail')->count() }} فشل
                                                </span>
                                            </div>
                                        </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-flask fa-3x mb-3"></i>
                            <p class="mb-0">لا توجد اختبارات مضافة لهذه الرخصة</p>
                </div>
            @endif
                            </div>
                            </div>
                        </div>
                    </div>

    <!-- Evacuations Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-truck-moving me-2"></i>
                        بيانات الإخلاء التفصيلية
                    </h5>
                            </div>
                <div class="card-body">
                @php
                            $evacuationData = [];
                        // Check for evacuation data in additional_details
                            if ($license->additional_details) {
                                $additionalDetails = json_decode($license->additional_details, true);
                            if (isset($additionalDetails['evacuation_data']) && is_array($additionalDetails['evacuation_data'])) {
                                    $evacuationData = $additionalDetails['evacuation_data'];
                                }
                            }
                        // Fallback to evac_table2_data if no data in additional_details
                        if (empty($evacuationData) && $license->evac_table2_data) {
                            $evacuationDataFromTable2 = json_decode($license->evac_table2_data, true);
                            if (is_array($evacuationDataFromTable2)) {
                                $evacuationData = $evacuationDataFromTable2;
                            }
                        }
                        @endphp
                        
                    @if(!empty($evacuationData) && count($evacuationData) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-warning">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>حالة الإخلاء</th>
                                        <th>تاريخ الإخلاء</th>
                                        <th>مبلغ الإخلاء</th>
                                        <th>وقت الإخلاء</th>
                                        <th>رقم السداد</th>
                                        <th>الملاحظات</th>
                            </tr>
                        </thead>
                                <tbody>
                                    @foreach($evacuationData as $index => $evacuation)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $index + 1 }}</span>
                                        </td>
                                        <td>
                                            @if(isset($evacuation['is_evacuated']))
                                                <span class="badge bg-{{ $evacuation['is_evacuated'] == '1' ? 'success' : 'danger' }}">
                                                    <i class="fas fa-{{ $evacuation['is_evacuated'] == '1' ? 'check' : 'times' }} me-1"></i>
                                                    {{ $evacuation['is_evacuated'] == '1' ? 'تم الإخلاء' : 'لم يتم الإخلاء' }}
                                        </span>
                                            @else
                                                <span class="badge bg-secondary">غير محدد</span>
                                            @endif
                                    </td>
                                        <td>
                                            @if(isset($evacuation['evacuation_date']))
                                                <i class="fas fa-calendar-alt text-muted me-1"></i>
                                                {{ $evacuation['evacuation_date'] }}
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($evacuation['evacuation_amount']))
                                                <span class="badge bg-info">{{ number_format($evacuation['evacuation_amount'], 2) }} ريال</span>
                                        @else
                                                <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                        <td>
                                            @if(isset($evacuation['evacuation_datetime']))
                                                <i class="fas fa-clock text-muted me-1"></i>
                                                {{ date('Y-m-d H:i', strtotime($evacuation['evacuation_datetime'])) }}
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                </td>
                                        <td>
                                            <span class="badge bg-warning text-dark">{{ $evacuation['payment_number'] ?? 'غير محدد' }}</span>
                                </td>
                                        <td>
                                            <small class="text-muted">{{ $evacuation['notes'] ?? 'لا توجد ملاحظات' }}</small>
                                        </td>
                            </tr>
                                    @endforeach
                                </tbody>
                    </table>
                    </div>
            @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-truck fa-3x mb-3"></i>
                            <p class="mb-0">لا توجد بيانات إخلاء تفصيلية مسجلة</p>
                            <small class="text-muted">يمكن إضافة بيانات الإخلاء من خلال صفحة تحرير الرخصة</small>
                </div>
            @endif
                        </div>
                        </div>
                    </div>
                </div>
                
    <!-- Clearance Table (Street Clearance Data) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-table me-2"></i>
                        جدول الفسح ونوع الشارع
                    </h5>
                        </div>
                <div class="card-body">
                    @php
                        $clearanceData = [];
                        if ($license->evac_table1_data) {
                            $clearanceDataRaw = json_decode($license->evac_table1_data, true);
                            if (is_array($clearanceDataRaw)) {
                                $clearanceData = $clearanceDataRaw;
                                    }
                                }
                        @endphp
                        
                    @if(!empty($clearanceData) && count($clearanceData) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-info">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>رقم الفسح</th>
                                        <th>تاريخ الفسح</th>
                                        <th>طول الفسح</th>
                                        <th>طول المختبر</th>
                                        <th>نوع الشارع</th>
                                        <th> التربة</th>
                                        <th> الأسفلت</th>
                                        <th> البلاط</th>
                                        <th>الملاحظات</th>
                        </tr>
                    </thead>
                                <tbody>
                                    @foreach($clearanceData as $index => $clearance)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $index + 1 }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $clearance['clearance_number'] ?? 'غير محدد' }}</span>
                                        </td>
                                        <td>
                                            @if(isset($clearance['clearance_date']))
                                                <i class="fas fa-calendar-alt text-muted me-1"></i>
                                                {{ $clearance['clearance_date'] }}
                                        @else
                                                <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                        <td>
                                            @if(isset($clearance['length']))
                                                <span class="badge bg-info">{{ number_format($clearance['length'], 2) }} م</span>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                    </td>
                                        <td>
                                            @if(isset($clearance['lab_length']))
                                                <span class="badge bg-warning text-dark">{{ number_format($clearance['lab_length'], 2) }} م</span>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                    </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-{{ $clearance['street_type'] == 'رئيسي' ? 'highway' : 'road' }} text-primary me-2"></i>
                                                <span class="fw-bold">{{ $clearance['street_type'] ?? 'غير محدد' }}</span>
                                            </div>
                                    </td>
                                        <td>
                                            @if(isset($clearance['soil_quantity']))
                                                <span class="badge bg-secondary">{{ number_format($clearance['soil_quantity'], 2) }} م³</span>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                    </td>
                                        <td>
                                            @if(isset($clearance['asphalt_quantity']))
                                                <span class="badge bg-dark text-white">{{ number_format($clearance['asphalt_quantity'], 2) }} م³</span>
                        @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                </td>
                                        <td>
                                            @if(isset($clearance['tile_quantity']))
                                                <span class="badge bg-danger">{{ number_format($clearance['tile_quantity'], 2) }} م²</span>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                        @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $clearance['notes'] ?? 'لا توجد ملاحظات' }}</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-table fa-3x mb-3"></i>
                            <p class="mb-0">لا توجد بيانات فسح مسجلة</p>
                            <small class="text-muted">يمكن إضافة بيانات الفسح من خلال صفحة تحرير الرخصة</small>
                </div>
            @endif
                </div>
            </div>
        </div>
    </div>

        <!-- Laboratory Technical Details Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-flask me-2"></i>
                        جدول التفاصيل الفنية للمختبر
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $labData = [];
                        if ($license->lab_table2_data) {
                            $labDataRaw = json_decode($license->lab_table2_data, true);
                            if (is_array($labDataRaw)) {
                                $labData = $labDataRaw;
                            }
                        }
                    @endphp
                    
                    @if(!empty($labData) && count($labData) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="bg-light">
                                    <tr class="text-secondary">
                                        <th class="text-center">#</th>
                                        <th>السنة</th>
                                        <th>نوع العمل</th>
                                        <th>العمق</th>
                                        <th>دك التربة</th>
                                        <th>MC1-RC2</th>
                                        <th>دك الأسفلت</th>
                                        <th>نوع التربة</th>
                                        <th>الكثافة القصوى</th>
                                        <th>نسبة الأسفلت</th>
                                        <th>مارشال</th>
                                        <th>تقييم البلاط</th>
                                        <th>تصنيف التربة</th>
                                        <th>بروكتور</th>
                                        <th>الخرسانة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($labData as $index => $lab)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $lab['year'] ?? 'غير محدد' }}</span>
                                        </td>
                                        <td class="fw-bold text-secondary">{{ $lab['work_type'] ?? 'غير محدد' }}</td>
                                        <td>
                                            @if(isset($lab['depth']))
                                                <span class="badge bg-secondary">{{ number_format($lab['depth'], 2) }} م</span>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($lab['soil_compaction']))
                                                <span class="badge bg-secondary">{{ number_format($lab['soil_compaction'], 2) }}%</span>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                        <td>{{ $lab['mc1rc2'] ?? 'غير محدد' }}</td>
                                        <td>
                                            @if(isset($lab['asphalt_compaction']))
                                                <span class="badge bg-secondary">{{ number_format($lab['asphalt_compaction'], 2) }}%</span>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                        <td>{{ $lab['soil_type'] ?? 'غير محدد' }}</td>
                                        <td>
                                            @if(isset($lab['max_asphalt_density']))
                                                <span class="badge bg-secondary">{{ number_format($lab['max_asphalt_density'], 2) }}</span>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($lab['asphalt_percentage']))
                                                <span class="badge bg-secondary">{{ number_format($lab['asphalt_percentage'], 2) }}%</span>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                        <td>{{ $lab['marshall_test'] ?? 'غير محدد' }}</td>
                                        <td>{{ $lab['tile_evaluation'] ?? 'غير محدد' }}</td>
                                        <td>{{ $lab['soil_classification'] ?? 'غير محدد' }}</td>
                                        <td>{{ $lab['proctor_test'] ?? 'غير محدد' }}</td>
                                        <td>{{ $lab['concrete'] ?? 'غير محدد' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-flask fa-3x mb-3"></i>
                            <p class="mb-0">لا توجد تفاصيل فنية للمختبر مسجلة</p>
                            <small class="text-muted">يمكن إضافة التفاصيل الفنية من خلال صفحة تحرير الرخصة</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
        
    <!-- Violations Record Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        سجل المخالفات
                    </h5>
                </div>
                <div class="card-body">
                    @if($license->violations && $license->violations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>رقم المخالفة</th>
                                        <th>نوع المخالفة</th>
                                        <th>تاريخ المخالفة</th>
                                        <th>الوصف</th>
                                        <th>قيمة المخالفة</th>
                                        <th>تاريخ الاستحقاق</th>
                                        <th>رقم فاتورة السداد</th>
                                        <th>الحالة</th>
                                        <th>الملاحظات</th>
                                        <th>المرفق</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($license->violations as $violation)
                                    <tr>
                                        <td>
                                            <span class="badge bg-danger">{{ $violation->violation_number ?? 'غير محدد' }}</span>
                                        </td>
                                        <td class="fw-bold">{{ $violation->violation_type ?? 'غير محدد' }}</td>
                                        <td>
                                            <i class="fas fa-calendar-alt text-muted me-1"></i>
                                            {{ $violation->violation_date ? $violation->violation_date->format('Y-m-d') : 'غير محدد' }}
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $violation->description ?? 'لا يوجد وصف' }}</small>
                                        </td>
                                        <td>
                                            @if($violation->violation_amount)
                                                <span class="badge bg-warning text-dark">{{ number_format($violation->violation_amount, 2) }} ريال</span>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($violation->payment_due_date)
                                                <i class="fas fa-calendar-due text-muted me-1"></i>
                                                {{ $violation->payment_due_date->format('Y-m-d') }}
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($violation->payment_invoice_number)
                                                <span class="badge bg-info text-white">{{ $violation->payment_invoice_number }}</span>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($violation->payment_status == 1)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>مسددة
                                                </span>
                                            @elseif($violation->payment_status == 2)
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i>قيد المعالجة
                                                </span>
                                            @elseif($violation->payment_status == 3)
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>متأخرة
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-question-circle me-1"></i>غير محدد
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($violation->notes)
                                                <div class="text-truncate" style="max-width: 200px;" title="{{ $violation->notes }}">
                                                    {{ $violation->notes }}
                                                </div>
                                            @else
                                                <span class="text-muted">لا توجد ملاحظات</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($violation->attachment_path)
                                                <a href="{{ asset('storage/' . $violation->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-file me-1"></i>عرض المرفق
                                                </a>
                                            @else
                                                <span class="text-muted">لا يوجد</span>
                                            @endif
                                        </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
        @else
                        <div class="text-center py-4 text-success">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <h5 class="text-success">لا توجد مخالفات مسجلة</h5>
                            <p class="text-muted mb-0">هذه الرخصة لا تحتوي على أي مخالفات</p>
        </div>
        @endif
    </div>
</div>
        </div>
    </div>
</div>

<style>
.info-item {
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 10px;
}

.attachment-card {
    transition: all 0.3s ease;
    min-height: 150px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.attachment-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.attachment-icon {
    transition: transform 0.3s ease;
}

.attachment-card:hover .attachment-icon {
    transform: scale(1.1);
}

.card {
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.card-header {
    border-bottom: 2px solid rgba(255,255,255,0.1);
}

.bg-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
}

.bg-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%) !important;
}

.bg-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important;
}

.bg-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
}

.bg-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
}

.bg-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%) !important;
}
</style>
@endsection