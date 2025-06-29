@extends('layouts.admin')
@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'تفاصيل الرخصة رقم ' . $license->license_number)

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0 bg-primary text-white">
                <div class="card-body text-center py-4">
                    <h1 class="display-5 fw-bold mb-2">تفاصيل الرخصة</h1>
                    <h2 class="h3 mb-3">رقم الرخصة: {{ $license->license_number }}</h2>
                    <p class="mb-1">أمر العمل رقم: {{ $license->workOrder->order_number ?? 'غير محدد' }}</p>
                    <p class="mb-3">اسم المشترك: {{ $license->workOrder->subscriber_name ?? 'غير محدد' }}</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.work-orders.license', $license->workOrder) }}" class="btn btn-light">
                            <i class="fas fa-arrow-right me-2"></i>رجوع
                        </a>
                        <a href="{{ route('admin.licenses.edit', $license) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>تعديل
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-pills nav-justified" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#coordination" type="button" role="tab">
                        <i class="fas fa-handshake me-2"></i>شهادة التنسيق
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#laboratory" type="button" role="tab">
                            <i class="fas fa-flask me-2"></i>المختبر 
                            <span class="badge bg-success ms-2">{{ $license->license_number }}</span>
                        </button>
                </li>
                <li class="nav-item" role="presentation">
                                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#evacuations" type="button" role="tab">
                            <i class="fas fa-truck me-2"></i>الإخلاءات 
                            <span class="badge bg-warning text-dark ms-2">{{ $license->license_number }}</span>
                        </button>
                </li>
                <li class="nav-item" role="presentation">
                                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#violations" type="button" role="tab">
                            <i class="fas fa-exclamation-triangle me-2"></i>المخالفات 
                            <span class="badge bg-danger ms-2">{{ $license->license_number }}</span>
                        </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- شهادة التنسيق -->
        <div class="tab-pane fade show active" id="coordination" role="tabpanel">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-handshake me-2"></i>شهادة التنسيق 
                        <span class="badge bg-light text-primary ms-2 fs-6">رخصة {{ $license->license_number }}</span>
                    </h4>
                </div>
                <div class="card-body">
                        <!-- بيانات شهادة التنسيق -->
                    @if($license->coordination_certificate_number)
                    <div class="card bg-light mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-certificate me-2"></i>بيانات شهادة التنسيق</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong><i class="fas fa-hashtag me-2 text-primary"></i>رقم شهادة التنسيق:</strong></td>
                                            <td><span class="badge bg-primary fs-6">{{ $license->coordination_certificate_number }}</span></td>
                                        </tr>
                                        @if($license->coordination_certificate_notes)
                                        <tr>
                                            <td><strong><i class="fas fa-sticky-note me-2 text-info"></i>ملاحظات الشهادة:</strong></td>
                                            <td>{{ $license->coordination_certificate_notes }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <!-- حالة القيود -->
                                    <div class="alert {{ $license->has_restriction ? 'alert-warning' : 'alert-success' }} mb-3">
                                        <h6 class="mb-2">
                                            <i class="fas {{ $license->has_restriction ? 'fa-exclamation-triangle' : 'fa-check-circle' }} me-2"></i>
                                            حالة القيود
                                        </h6>
                                        @if($license->has_restriction)
                                            <p class="mb-0"><strong>يوجد قيود على الرخصة</strong></p>
                                            @if($license->restriction_authority)
                                                <small class="text-muted">جهة القيد: {{ $license->restriction_authority }}</small>
                                            @endif
                                        @else
                                            <p class="mb-0"><strong>لا توجد قيود على الرخصة</strong></p>
                                        @endif
                                    </div>
                                    
                                    @if($license->has_restriction && ($license->restriction_reason || $license->restriction_notes))
                                    <div class="card">
                                        <div class="card-body">
                                            @if($license->restriction_reason)
                                                <p><strong>سبب القيد:</strong> {{ $license->restriction_reason }}</p>
                                            @endif
                                            @if($license->restriction_notes)
                                                <p class="mb-0"><strong>ملاحظات القيد:</strong> {{ $license->restriction_notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- المرفقات -->
                    @if($license->coordination_certificate_path || $license->letters_commitments_file_path)
                    <div class="card bg-light mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-paperclip me-2"></i>مرفقات شهادة التنسيق</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($license->coordination_certificate_path)
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center p-3 border rounded bg-white">
                                        <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                                        <div>
                                            <h6 class="mb-1">ملف شهادة التنسيق</h6>
                                            @if(Storage::exists($license->coordination_certificate_path))
                                                <a href="{{ Storage::url($license->coordination_certificate_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i>عرض الملف
                                                </a>
                                            @else
                                                <span class="text-muted small">الملف غير متوفر</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                @if($license->letters_commitments_file_path)
                                <div class="col-md-6 mb-3">
                                    <div class="p-3 border rounded bg-white">
                                        <h6 class="mb-2"><i class="fas fa-folder me-2 text-warning"></i>الخطابات والتعهدات</h6>
                                        @php 
                                            $letterFiles = is_string($license->letters_commitments_file_path) 
                                                ? json_decode($license->letters_commitments_file_path, true) 
                                                : $license->letters_commitments_file_path;
                                        @endphp
                                        @if($letterFiles && is_array($letterFiles))
                                            @foreach($letterFiles as $index => $filePath)
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-file-alt text-primary me-2"></i>
                                                @if(Storage::exists($filePath))
                                                    <a href="{{ Storage::url($filePath) }}" target="_blank" class="text-decoration-none">
                                                        <small>ملف {{ $index + 1 }}</small>
                                                    </a>
                                                @else
                                                    <span class="text-muted small">ملف {{ $index + 1 }} (غير متوفر)</span>
                                                @endif
                                            </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- معلومات الرخصة الأساسية -->
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>معلومات الرخصة الأساسية</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td><strong>رقم الرخصة</strong></td>
                                            <td><span class="badge bg-primary">{{ $license->license_number }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>تاريخ الإصدار</strong></td>
                                            <td>{{ $license->license_date ? $license->license_date->format('Y-m-d') : 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>نوع الرخصة</strong></td>
                                            <td>
                                                @if($license->license_type == 'emergency')
                                                    <span class="badge bg-danger">طوارئ</span>
                                                @elseif($license->license_type == 'project')
                                                    <span class="badge bg-info">مشروع</span>
                                                @elseif($license->license_type == 'normal')
                                                    <span class="badge bg-success">عادي</span>
                                                @else
                                                    <span class="badge bg-secondary">غير محدد</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>قيمة الرخصة</strong></td>
                                            <td><span class="text-success fw-bold">{{ number_format($license->license_value ?? 0, 2) }} ريال</span></td>
                                        </tr>
                                        @if($license->license_start_date || $license->license_end_date)
                                        <tr>
                                            <td><strong>فترة الرخصة</strong></td>
                                            <td>
                                                @if($license->license_start_date && $license->license_end_date)
                                                    {{ $license->license_start_date->format('Y-m-d') }} إلى {{ $license->license_end_date->format('Y-m-d') }}
                                                @else
                                                    غير محددة
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="fas fa-ruler-combined me-2 text-info"></i>أبعاد الحفر</h6>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td><strong>الطول</strong></td>
                                            <td>{{ $license->excavation_length ?? 0 }} متر</td>
                                        </tr>
                                        <tr>
                                            <td><strong>العرض</strong></td>
                                            <td>{{ $license->excavation_width ?? 0 }} متر</td>
                                        </tr>
                                        <tr>
                                            <td><strong>العمق</strong></td>
                                            <td>{{ $license->excavation_depth ?? 0 }} متر</td>
                                        </tr>
                                        <tr>
                                            <td><strong>الحجم الإجمالي</strong></td>
                                            <td>
                                                @php
                                                    $volume = ($license->excavation_length ?? 0) * ($license->excavation_width ?? 0) * ($license->excavation_depth ?? 0);
                                                @endphp
                                                <span class="badge bg-info">{{ number_format($volume, 2) }} متر مكعب</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- سجل التمديدات المحفوظة -->
                    <div class="card mt-4">
                        <div class="card-header bg-warning text-dark">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar-plus me-2"></i>
                                    سجل التمديدات المحفوظة
                                    <span class="badge bg-primary ms-2">رخصة {{ $license->license_number }}</span>
                                </h5>
                                <div class="text-end">
                                    <div class="badge bg-secondary fs-6 px-3 py-2" id="extensions-total-badge">
                                        <i class="fas fa-calculator me-2"></i>
                                        <span id="extensions-total-arabic">إجمالي: 0 ريال</span>
                                    </div>
                                    <div class="mt-1">
                                        <small class="text-dark fw-bold" id="extensions-total-english" style="font-family: 'Arial', sans-serif;">
                                            Total: 0.00 SAR
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="extensionsTable">
                                    <thead class="table-warning">
                                        <tr>
                                            <th>#</th>
                                            <th>رقم الرخصة</th>
                                            <th>قيمة التمديد</th>
                                            <th>تاريخ البداية</th>
                                            <th>تاريخ النهاية</th>
                                            <th>عدد أيام التمديد</th>
                                            <th>المرفقات</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody id="extensions-table-body">
                                        <tr id="no-extensions-row">
                                            <td colspan="8" class="text-center text-muted">
                                                <i class="fas fa-calendar-times fa-2x mb-3 d-block"></i>
                                                لا توجد تمديدات محفوظة لهذه الرخصة
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @if(!$license->coordination_certificate_number && !$license->coordination_certificate_path)
                    <div class="text-center py-5">
                        <i class="fas fa-handshake fa-5x text-muted mb-3"></i>
                        <h4 class="text-muted">لا توجد بيانات شهادة تنسيق</h4>
                        <p class="text-muted">لم يتم إدخال بيانات شهادة التنسيق لهذه الرخصة بعد</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

                <!-- المختبر الديناميكي -->
        <div class="tab-pane fade" id="laboratory" role="tabpanel">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0 d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-flask me-2"></i>الاختبارات المعملية 
                            <span class="badge bg-light text-success ms-2 fs-6">رخصة {{ $license->license_number }}</span>
                        </span>
                        <div class="d-flex gap-2">
                            <span class="badge bg-white text-success fs-6" id="lab-passed-count">ناجح: 0</span>
                            <span class="badge bg-white text-danger fs-6" id="lab-failed-count">راسب: 0</span>
                            <span class="badge bg-white text-info fs-6" id="lab-total-value">إجمالي: 0 ريال</span>
                        </div>
                    </h4>
                </div>
                <div class="card-body">
                    @php
                        $basicLabTests = [
                            'has_depth_test' => [
                                'name' => 'اختبار العمق',
                                'icon' => 'fas fa-ruler-vertical',
                                'value_field' => 'depth_test_value',
                                'file_field' => 'depth_test_file_path'
                            ],
                            'has_soil_compaction_test' => [
                                'name' => 'اختبار دك التربة',
                                'icon' => 'fas fa-compress',
                                'value_field' => 'soil_compaction_test_value',
                                'file_field' => 'soil_compaction_test_file_path'
                            ],
                            'has_rc1_mc1_test' => [
                                'name' => 'اختبار RC1-MC1',
                                'icon' => 'fas fa-vial',
                                'value_field' => 'rc1_mc1_test_value',
                                'file_field' => 'rc1_mc1_test_file_path'
                            ],
                            'has_asphalt_test' => [
                                'name' => 'اختبار الأسفلت',
                                'icon' => 'fas fa-road',
                                'value_field' => 'asphalt_test_value',
                                'file_field' => 'asphalt_test_file_path'
                            ],
                            'has_soil_test' => [
                                'name' => 'اختبار التربة',
                                'icon' => 'fas fa-mountain',
                                'value_field' => 'soil_test_value',
                                'file_field' => 'soil_test_file_path'
                            ],
                            'has_interlock_test' => [
                                'name' => 'اختبار البلاط المتداخل',
                                'icon' => 'fas fa-th',
                                'value_field' => 'interlock_test_value',
                                'file_field' => 'interlock_test_file_path'
                            ]
                        ];
                    @endphp

                    <!-- الاختبارات الديناميكية -->
                    <div class="row">
                        @foreach($basicLabTests as $testField => $test)
                        @php
                            $testStatus = $license->$testField;
                            $testValue = $license->{$test['value_field']} ?? 0;
                            $testFile = $license->{$test['file_field']} ?? null;
                        @endphp
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card lab-test-card h-100" data-test="{{ $testField }}">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 d-flex align-items-center justify-content-between">
                                        <span>
                                            <i class="{{ $test['icon'] }} me-2"></i>
                                            {{ $test['name'] }}
                                        </span>
                                        <i class="test-status-icon fas fa-question-circle text-secondary"></i>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <!-- حالة الاختبار -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">حالة الاختبار:</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-status" 
                                                       type="radio" 
                                                       name="{{ $testField }}_status" 
                                                       value="passed" 
                                                       id="{{ $testField }}_passed"
                                                       {{ $testStatus === true ? 'checked' : '' }}>
                                                <label class="form-check-label text-success" for="{{ $testField }}_passed">
                                                    <i class="fas fa-check-circle me-1"></i>ناجح
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-status" 
                                                       type="radio" 
                                                       name="{{ $testField }}_status" 
                                                       value="failed" 
                                                       id="{{ $testField }}_failed"
                                                       {{ $testStatus === false ? 'checked' : '' }}>
                                                <label class="form-check-label text-danger" for="{{ $testField }}_failed">
                                                    <i class="fas fa-times-circle me-1"></i>راسب
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- قيمة الاختبار -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">القيمة (ريال):</label>
                                        <input type="number" 
                                               class="form-control test-value" 
                                               name="{{ $test['value_field'] }}" 
                                               value="{{ $testValue }}" 
                                               min="0" 
                                               step="0.01" 
                                               placeholder="أدخل القيمة">
                                    </div>

                                    <!-- المرفق -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">المرفق:</label>
                                        <div class="file-upload-section">
                                            @if($testFile)
                                                <div class="current-file mb-2">
                                                    <div class="d-flex align-items-center justify-content-between p-2 bg-success bg-opacity-10 rounded">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-file-pdf text-success me-2"></i>
                                                            <span class="text-success">يوجد مرفق</span>
                                                        </div>
                                                        <div class="d-flex gap-1">
                                                            <a href="{{ Storage::url($testFile) }}" 
                                                               target="_blank" 
                                                               class="btn btn-outline-success btn-sm" 
                                                               title="عرض">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <button type="button" 
                                                                    class="btn btn-outline-danger btn-sm delete-file-btn" 
                                                                    data-test="{{ $testField }}" 
                                                                    title="حذف">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <input type="file" 
                                                   class="form-control file-input" 
                                                   data-test="{{ $testField }}" 
                                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- أزرار التحكم -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn-primary" id="save-all-tests">
                                    <i class="fas fa-save me-2"></i>حفظ جميع التغييرات
                                </button>
                                <button type="button" class="btn btn-info" id="export-lab-report">
                                    <i class="fas fa-file-export me-2"></i>تصدير تقرير
                                </button>
                                <button type="button" class="btn btn-secondary" id="reset-lab-tests">
                                    <i class="fas fa-undo me-2"></i>إعادة تعيين
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



                    <!-- أسباب الرسوب -->
                    @if($license->test_failure_reasons)
                    <div class="card mb-4 border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                أسباب رسوب الاختبارات
                            </h5>
                            </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $license->test_failure_reasons }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- الجداول المعملية -->
                    @if($license->lab_table1_data || $license->lab_table2_data)
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-table me-2"></i>
                                الجداول المعملية التفصيلية
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- جدول الفسح ونوع الشارع -->
                            @if($license->lab_table1_data)
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-list-ul me-2"></i>
                                جدول الفسح ونوع الشارع
                            </h6>
                            @php
                                $labTable1Data = is_string($license->lab_table1_data) ? 
                                    json_decode($license->lab_table1_data, true) : 
                                    $license->lab_table1_data;
                            @endphp
                            @if($labTable1Data && is_array($labTable1Data))
                            <div class="table-responsive mb-4">
                                <table class="table table-striped table-bordered laboratory-table">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>#</th>
                                            <th>رقم الفسح</th>
                                            <th>تاريخ الفسح</th>
                                            <th>طول الفسح</th>
                                            <th>طول المختبر</th>
                                            <th>نوع الشارع</th>
                                            <th>كمية التربة</th>
                                            <th>كمية الأسفلت</th>
                                            <th>كمية البلاط</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($labTable1Data as $index => $row)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $row['clearance_number'] ?? '-' }}</td>
                                            <td>{{ $row['clearance_date'] ?? '-' }}</td>
                                            <td>{{ $row['length'] ?? '-' }}</td>
                                            <td>{{ $row['lab_length'] ?? '-' }}</td>
                                            <td>{{ $row['street_type'] ?? '-' }}</td>
                                            <td>{{ $row['soil_quantity'] ?? '-' }}</td>
                                            <td>{{ $row['asphalt_quantity'] ?? '-' }}</td>
                                            <td>{{ $row['tile_quantity'] ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            @endif

                            <!-- جدول التفاصيل الفنية -->
                            @if($license->lab_table2_data)
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-cogs me-2"></i>
                                جدول التفاصيل الفنية
                            </h6>
                            @php
                                $labTable2Data = is_string($license->lab_table2_data) ? 
                                    json_decode($license->lab_table2_data, true) : 
                                    $license->lab_table2_data;
                            @endphp
                            @if($labTable2Data && is_array($labTable2Data))
                            <div class="table-responsive mb-4">
                                <table class="table table-striped table-bordered laboratory-table">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>#</th>
                                            <th>السنة</th>
                                            <th>نوع العمل</th>
                                            <th>العمق</th>
                                            <th>دك التربة</th>
                                            <th>MC1-RC2</th>
                                            <th>دك أسفلت</th>
                                            <th>ترابي</th>
                                            <th>الكثافة القصوى للأسفلت</th>
                                            <th>نسبة الأسفلت</th>
                                            <th>تجربة مارشال</th>
                                            <th>تقييم البلاط</th>
                                            <th>تصنيف التربة</th>
                                            <th>تجربة بروكتور</th>
                                            <th>الخرسانة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($labTable2Data as $index => $row)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $row['year'] ?? '-' }}</td>
                                            <td>{{ $row['work_type'] ?? '-' }}</td>
                                            <td>{{ $row['depth'] ?? '-' }}</td>
                                            <td>{{ $row['soil_compaction'] ?? '-' }}</td>
                                            <td>{{ $row['mc1_test'] ?? '-' }}</td>
                                            <td>{{ $row['asphalt_test'] ?? '-' }}</td>
                                            <td>{{ $row['soil_type'] ?? '-' }}</td>
                                            <td>{{ $row['max_asphalt_density'] ?? '-' }}</td>
                                            <td>{{ $row['asphalt_percentage'] ?? '-' }}</td>
                                            <td>{{ $row['marshall_test'] ?? '-' }}</td>
                                            <td>{{ $row['tile_evaluation'] ?? '-' }}</td>
                                            <td>{{ $row['soil_classification'] ?? '-' }}</td>
                                            <td>{{ $row['proctor_test'] ?? '-' }}</td>
                                            <td>{{ $row['concrete'] ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- الاختبارات المتقدمة -->
                    @php
                                $advancedTests = [
                                    'max_dry_density_pro_test_file_path' => [
                                        'name' => 'اختبار الكثافة الجافة القصوى',
                                        'icon' => 'fas fa-weight-hanging',
                                        'color' => 'secondary'
                                    ],
                                    'asphalt_ratio_gradation_test_file_path' => [
                                        'name' => 'اختبار تدرج نسبة الأسفلت',
                                        'icon' => 'fas fa-chart-bar',
                                        'color' => 'info'
                                    ],
                                    'marshall_test_file_path' => [
                                        'name' => 'اختبار مارشال',
                                        'icon' => 'fas fa-hammer',
                                        'color' => 'dark'
                                    ],
                                    'concrete_molds_test_file_path' => [
                                        'name' => 'اختبار قوالب الخرسانة',
                                        'icon' => 'fas fa-cube',
                                        'color' => 'secondary'
                                    ],
                                    'excavation_bottom_test_file_path' => [
                                        'name' => 'اختبار قاع الحفر',
                                        'icon' => 'fas fa-arrow-down',
                                        'color' => 'warning'
                                    ],
                                    'protection_depth_test_file_path' => [
                                        'name' => 'اختبار عمق الحماية',
                                        'icon' => 'fas fa-shield-alt',
                                        'color' => 'success'
                                    ],
                                    'settlement_test_file_path' => [
                                        'name' => 'اختبار الهبوط',
                                        'icon' => 'fas fa-level-down-alt',
                                        'color' => 'danger'
                                    ],
                                    'concrete_temperature_test_file_path' => [
                                        'name' => 'اختبار درجة حرارة الخرسانة',
                                        'icon' => 'fas fa-thermometer-half',
                                        'color' => 'info'
                            ]
                        ];
                        
                                $hasAdvancedTests = false;
                                foreach($advancedTests as $field => $test) {
                                    if($license->$field) {
                                        $hasAdvancedTests = true;
                                        break;
                                    }
                                }
                            @endphp

                            @if($hasAdvancedTests)
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-microscope me-2"></i>
                                الاختبارات المتقدمة والمرفقات الإضافية
                            </h5>
                        </div>
                        <div class="card-body advanced-tests">
                            <div class="row">
                                @foreach($advancedTests as $field => $test)
                                    @if($license->$field)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card border-{{ $test['color'] }}">
                                            <div class="card-header bg-{{ $test['color'] }} text-white py-2">
                                                <small class="fw-bold">
                                                    <i class="{{ $test['icon'] }} me-1"></i>
                                                    {{ $test['name'] }}
                                                </small>
                                            </div>
                                            <div class="card-body py-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-file-alt me-1"></i>مرفق
                                                    </span>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ Storage::url($license->$field) }}" 
                                                           class="btn btn-outline-primary btn-sm" 
                                                           target="_blank" 
                                                           title="عرض الملف">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ Storage::url($license->$field) }}" 
                                                           class="btn btn-outline-success btn-sm" 
                                                           download 
                                                           title="تحميل الملف">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <small class="text-muted d-block mt-1">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $license->updated_at->format('Y-m-d H:i') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                                        </div>
                                        </div>
                    @endif



                </div>
            </div>
        </div>

        <!-- الإخلاءات -->
        <div class="tab-pane fade" id="evacuations" role="tabpanel">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-truck me-2"></i>الإخلاءات 
                        <span class="badge bg-dark text-warning ms-2 fs-6">رخصة {{ $license->license_number }}</span>
                    </h4>
                </div>
                <div class="card-body">
                    <!-- بيانات الإخلاءات التفصيلية -->
                    @php
                        $evacuationData = [];
                        if($license->evacuation_data) {
                            $evacuationData = is_string($license->evacuation_data) ? json_decode($license->evacuation_data, true) : $license->evacuation_data;
                            if(!is_array($evacuationData)) {
                                $evacuationData = [];
                            }
                        }
                    @endphp
                    @if($evacuationData && count($evacuationData) > 0)
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                                                         <h5 class="mb-0">
                                <i class="fas fa-clipboard-list me-2"></i>
                                بيانات الإخلاءات التفصيلية
                                <span class="badge bg-light text-success ms-2">{{ count($evacuationData) }} سجل</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-success">
                                        <tr>
                                            <th>#</th>
                                            <th>تم الإخلاء؟</th>
                                            <th>تاريخ الإخلاء</th>
                                            <th>مبلغ الإخلاء (ريال)</th>
                                            <th>تاريخ ووقت الإخلاء</th>
                                            <th>رقم سداد الإخلاء</th>
                                            <th>ملاحظات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($evacuationData as $index => $evacuation)
                                        <tr>
                                            <td class="fw-bold text-center">{{ $index + 1 }}</td>
                                            <td>
                                                @if($evacuation['is_evacuated'] == '1')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>نعم
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times me-1"></i>لا
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $evacuation['evacuation_date'] ?? '-' }}</td>
                                            <td>{{ number_format($evacuation['evacuation_amount'] ?? 0, 2) }}</td>
                                            <td>{{ $evacuation['evacuation_datetime'] ? \Carbon\Carbon::parse($evacuation['evacuation_datetime'])->format('Y-m-d H:i') : '-' }}</td>
                                            <td>{{ $evacuation['payment_number'] ?? '-' }}</td>
                                            <td>{{ $evacuation['notes'] ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- ملخص الإخلاءات -->
                            @php
                                $totalEvacuated = collect($evacuationData)->where('is_evacuated', '1')->count();
                                $totalNotEvacuated = collect($evacuationData)->where('is_evacuated', '0')->count();
                                $totalAmount = collect($evacuationData)->sum('evacuation_amount');
                            @endphp
                            
                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h3 class="mb-1">{{ $totalEvacuated }}</h3>
                                            <small>تم إخلاؤها</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-danger text-white">
                                        <div class="card-body text-center">
                                            <h3 class="mb-1">{{ $totalNotEvacuated }}</h3>
                                            <small>لم يتم إخلاؤها</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center">
                                            <h3 class="mb-1">{{ count($evacuationData) }}</h3>
                                            <small>المجموع الكلي</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-warning text-dark">
                                        <div class="card-body text-center">
                                            <h3 class="mb-1">{{ number_format($totalAmount, 2) }}</h3>
                                            <small>إجمالي المبلغ (ريال)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info mb-4">
                        <h5><i class="fas fa-info-circle"></i> لا توجد بيانات إخلاء تفصيلية</h5>
                        <p class="mb-2">لم يتم إدخال أي بيانات إخلاء تفصيلية لهذه الرخصة بعد</p>
                        <a href="{{ route('admin.work-orders.license', $license->work_order_id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>إضافة بيانات إخلاء
                        </a>
                    </div>
                    @endif
                    
                    @if($license->is_evacuated)
                        <div class="alert alert-success mb-4">
                            <h5><i class="fas fa-check-circle"></i> تم الإخلاء</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>رقم رخصة الإخلاء:</strong></td>
                                            <td>{{ $license->evac_license_number ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>قيمة رخصة الإخلاء:</strong></td>
                                            <td>{{ number_format($license->evac_license_value ?? 0, 2) }} ريال</td>
                                        </tr>
                                        <tr>
                                            <td><strong>تاريخ الإخلاء:</strong></td>
                                            <td>{{ $license->evac_date ? $license->evac_date->format('Y-m-d') : 'غير محدد' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>مبلغ الإخلاء:</strong></td>
                                            <td>{{ number_format($license->evac_amount ?? 0, 2) }} ريال</td>
                                        </tr>
                                        <tr>
                                            <td><strong>رقم سداد الإخلاء:</strong></td>
                                            <td>{{ $license->evac_payment_number ?? 'غير محدد' }}</td>
                                        </tr>
                                        @if($license->evac_notes)
                                        <tr>
                                            <td><strong>ملاحظات:</strong></td>
                                            <td>{{ $license->evac_notes }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info mb-4">
                            <h5><i class="fas fa-info-circle"></i> حالة الإخلاء</h5>
                            <p class="mb-0">لم يتم الإخلاء بعد</p>
                        </div>
                    @endif

                    <!-- جدول فسح الإخلاءات - قابل للتحرير -->
                    <div class="card mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-table me-2"></i>جدول فسح الإخلاءات</h6>
                            <div>
                                <button type="button" class="btn btn-success btn-sm me-2" onclick="addEvacTable1Row()">
                                    <i class="fas fa-plus me-1"></i>إضافة صف
                                </button>
                                <button type="button" class="btn btn-primary btn-sm" onclick="saveEvacTable1Data()">
                                    <i class="fas fa-save me-1"></i>حفظ الجدول
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="evacTable1">
                                    <thead class="table-warning">
                                        <tr>
                                            <th>رقم الفسح</th>
                                            <th>تاريخ الفسح</th>
                                            <th>طول الفسح</th>
                                            <th>طول المختبر</th>
                                            <th>نوع الشارع</th>
                                            <th>كمية التربة</th>
                                            <th>كمية الأسفلت</th>
                                            <th>كمية البلاط</th>
                                            <th>إجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $evacTable1Data = is_string($license->evac_table1_data) ? json_decode($license->evac_table1_data, true) : $license->evac_table1_data; @endphp
                                        @if($evacTable1Data && is_array($evacTable1Data))
                                            @foreach($evacTable1Data as $index => $row)
                                            <tr>
                                                <td><input type="text" class="form-control form-control-sm" name="clearance_number" value="{{ $row['clearance_number'] ?? '' }}"></td>
                                                <td><input type="date" class="form-control form-control-sm" name="clearance_date" value="{{ $row['clearance_date'] ?? '' }}"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm" name="length" value="{{ $row['length'] ?? '' }}"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm" name="lab_length" value="{{ $row['lab_length'] ?? '' }}"></td>
                                                <td><input type="text" class="form-control form-control-sm" name="street_type" value="{{ $row['street_type'] ?? '' }}"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm" name="soil_quantity" value="{{ $row['soil_quantity'] ?? '' }}"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm" name="asphalt_quantity" value="{{ $row['asphalt_quantity'] ?? '' }}"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm" name="tile_quantity" value="{{ $row['tile_quantity'] ?? '' }}"></td>
                                                <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteTableRow(this)"><i class="fas fa-trash"></i></button></td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr id="no-evac-table1-data">
                                                <td colspan="9" class="text-center">لا توجد بيانات - اضغط "إضافة صف" لإضافة بيانات جديدة</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- جدول التفاصيل الفنية للمختبر - قابل للتحرير -->
                    <div class="card mb-4">
                        <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-flask me-2"></i>جدول التفاصيل الفنية للمختبر</h6>
                            <div>
                                <button type="button" class="btn btn-success btn-sm me-2" onclick="addLabDetailsRow()">
                                    <i class="fas fa-plus me-1"></i>إضافة صف
                                </button>
                                <button type="button" class="btn btn-primary btn-sm" onclick="saveLabDetailsData()">
                                    <i class="fas fa-save me-1"></i>حفظ الجدول
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="labDetailsTable">
                                    <thead class="table-success">
                                        <tr>
                                            <th style="width: 80px;">السنة</th>
                                            <th style="width: 120px;">نوع العمل</th>
                                            <th style="width: 80px;">العمق</th>
                                            <th style="width: 100px;">دك التربة</th>
                                            <th style="width: 100px;">MC1-RC2</th>
                                            <th style="width: 100px;">دك أسفلت</th>
                                            <th style="width: 80px;">ترابي</th>
                                            <th style="width: 120px;">الكثافة القصوى للأسفلت</th>
                                            <th style="width: 120px;">نسبة الأسفلت</th>
                                            <th style="width: 120px;">تجربة مارشال</th>
                                            <th style="width: 120px;">تقييم البلاط</th>
                                            <th style="width: 120px;">تصنيف التربة</th>
                                            <th style="width: 120px;">تجربة بروكتور</th>
                                            <th style="width: 100px;">الخرسانة</th>
                                            <th style="width: 80px;">حذف</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $labDetailsData = is_string($license->lab_table2_data) ? json_decode($license->lab_table2_data, true) : $license->lab_table2_data; @endphp
                                        @if($labDetailsData && is_array($labDetailsData))
                                            @foreach($labDetailsData as $index => $row)
                                            <tr>
                                                <td><input type="number" class="form-control form-control-sm" name="lab_year" value="{{ $row['year'] ?? '' }}" min="2020" max="2030"></td>
                                                <td><input type="text" class="form-control form-control-sm" name="lab_work_type" value="{{ $row['work_type'] ?? '' }}" placeholder="نوع العمل"></td>
                                                <td><input type="number" class="form-control form-control-sm" name="lab_depth" value="{{ $row['depth'] ?? '' }}" step="0.01" placeholder="العمق"></td>
                                                <td><input type="number" class="form-control form-control-sm" name="lab_soil_compaction" value="{{ $row['soil_compaction'] ?? '' }}" step="0.01" placeholder="دك التربة %"></td>
                                                <td><input type="text" class="form-control form-control-sm" name="lab_mc1rc2" value="{{ $row['mc1rc2'] ?? $row['mc1_test'] ?? '' }}" placeholder="MC1-RC2"></td>
                                                <td><input type="number" class="form-control form-control-sm" name="lab_asphalt_compaction" value="{{ $row['asphalt_compaction'] ?? $row['asphalt_test'] ?? '' }}" step="0.01" placeholder="دك أسفلت %"></td>
                                                <td><input type="text" class="form-control form-control-sm" name="lab_soil_type" value="{{ $row['soil_type'] ?? $row['soil_test'] ?? '' }}" placeholder="ترابي"></td>
                                                <td><input type="number" class="form-control form-control-sm" name="lab_max_asphalt_density" value="{{ $row['max_asphalt_density'] ?? '' }}" step="0.01" placeholder="كغم/م³"></td>
                                                <td><input type="number" class="form-control form-control-sm" name="lab_asphalt_percentage" value="{{ $row['asphalt_percentage'] ?? '' }}" step="0.01" placeholder="نسبة الأسفلت %"></td>
                                                <td><input type="text" class="form-control form-control-sm" name="lab_marshall_test" value="{{ $row['marshall_test'] ?? '' }}" placeholder="نتيجة مارشال"></td>
                                                <td><input type="text" class="form-control form-control-sm" name="lab_tile_evaluation" value="{{ $row['tile_evaluation'] ?? '' }}" placeholder="تقييم البلاط"></td>
                                                <td><input type="text" class="form-control form-control-sm" name="lab_soil_classification" value="{{ $row['soil_classification'] ?? '' }}" placeholder="تصنيف التربة"></td>
                                                <td><input type="text" class="form-control form-control-sm" name="lab_proctor_test" value="{{ $row['proctor_test'] ?? '' }}" placeholder="نتيجة بروكتور"></td>
                                                <td><input type="text" class="form-control form-control-sm" name="lab_concrete" value="{{ $row['concrete'] ?? '' }}" placeholder="مقاومة الخرسانة"></td>
                                                <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteTableRow(this)" title="حذف الصف"><i class="fas fa-trash"></i></button></td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr id="no-lab-details-data">
                                                <td colspan="15" class="text-center">لا توجد بيانات - اضغط "إضافة صف" لإضافة بيانات جديدة</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @if(!$license->is_evacuated && !$license->evac_table1_data && !$license->evac_table2_data)
                    <div class="text-center py-5">
                        <i class="fas fa-truck-loading fa-5x text-muted mb-3"></i>
                        <h4 class="text-muted">لا توجد بيانات إخلاءات</h4>
                        <p class="text-muted">لم يتم إدخال أي بيانات إخلاءات لهذه الرخصة بعد</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- المخالفات -->
        <div class="tab-pane fade" id="violations" role="tabpanel">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>سجل المخالفات 
                        <span class="badge bg-light text-danger ms-2 fs-6">رخصة {{ $license->license_number }}</span>
                        @if($license->violations->count() > 0)
                            <span class="badge bg-warning text-dark ms-2">{{ $license->violations->count() }} مخالفة</span>
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    @if($license->violations->count() > 0)
                        <!-- إحصائيات المخالفات -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                        <h5>إجمالي المخالفات</h5>
                                        <h3>{{ $license->violations->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-warning text-dark">
                                    <div class="card-body text-center">
                                        <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                                        <h5>إجمالي قيم المخالفات</h5>
                                        <h3>{{ number_format($license->violations->sum('violation_amount'), 2) }} ريال</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                        <h5>المخالفات المتأخرة</h5>
                                        <h3>{{ $license->violations->filter(function($v) { return $v->status == 'overdue'; })->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- جدول المخالفات -->
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>رقم المخالفة</th>
                                        <th>تاريخ المخالفة</th>
                                        <th>نوع المخالفة</th>
                                        <th>الجهة المسؤولة</th>
                                        <th>قيمة المخالفة</th>
                                        <th>تاريخ الاستحقاق</th>
                                        <th>حالة الدفع</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($license->violations as $violation)
                                        <tr>
                                            <td>
                                                <strong class="text-primary">{{ $violation->violation_number }}</strong>
                                            </td>
                                            <td>
                                                {{ $violation->violation_date ? $violation->violation_date->format('Y-m-d') : 'غير محدد' }}
                                            </td>
                                            <td>{{ $violation->violation_type ?? 'غير محدد' }}</td>
                                            <td>{{ $violation->responsible_party ?? 'غير محدد' }}</td>
                                            <td>
                                                <strong class="text-danger">{{ number_format($violation->violation_amount ?? 0, 2) }} ريال</strong>
                                            </td>
                                            <td>
                                                {{ $violation->payment_due_date ? $violation->payment_due_date->format('Y-m-d') : 'غير محدد' }}
                                            </td>
                                            <td>
                                                @switch($violation->payment_status_text)
                                                    @case('paid')
                                                        <span class="badge bg-success">مدفوعة</span>
                                                        @break
                                                    @case('pending')
                                                        <span class="badge bg-warning">في الانتظار</span>
                                                        @break
                                                    @case('overdue')
                                                        <span class="badge bg-danger">متأخرة</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">غير محدد</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($violation->status == 'overdue')
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-clock"></i> متأخرة
                                                    </span>
                                                @else
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-hourglass-half"></i> في الانتظار
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    @if($violation->attachment_path)
                                                        <a href="{{ $violation->attachment_url }}" 
                                                           class="btn btn-outline-primary" 
                                                           target="_blank" 
                                                           title="عرض المرفق">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif
                                                    <button type="button" 
                                                            class="btn btn-outline-info" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#violationModal{{ $violation->id }}"
                                                            title="عرض التفاصيل">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shield-alt fa-5x text-success mb-3"></i>
                            <h4 class="text-success">لا توجد مخالفات مسجلة</h4>
                            <p class="text-muted">هذه الرخصة لا تحتوي على أي مخالفات</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- نوافذ منبثقة لتفاصيل المخالفات -->
        @foreach($license->violations as $violation)
            <div class="modal fade" id="violationModal{{ $violation->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                تفاصيل المخالفة رقم {{ $violation->violation_number }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-primary">معلومات أساسية</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>رقم الرخصة:</strong></td>
                                            <td>{{ $violation->license_number ?? $license->license_number }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>رقم المخالفة:</strong></td>
                                            <td>{{ $violation->violation_number }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>تاريخ المخالفة:</strong></td>
                                            <td>{{ $violation->violation_date ? $violation->violation_date->format('Y-m-d') : 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>نوع المخالفة:</strong></td>
                                            <td>{{ $violation->violation_type ?? 'غير محدد' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-warning">معلومات الدفع</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>قيمة المخالفة:</strong></td>
                                            <td class="text-danger fw-bold">{{ number_format($violation->violation_amount ?? 0, 2) }} ريال</td>
                                        </tr>
                                        <tr>
                                            <td><strong>تاريخ الاستحقاق:</strong></td>
                                            <td>{{ $violation->payment_due_date ? $violation->payment_due_date->format('Y-m-d') : 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>رقم إيصال الدفع:</strong></td>
                                            <td>{{ $violation->payment_invoice_number ?? 'لم يتم الدفع' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>حالة الدفع:</strong></td>
                                            <td>
                                                @switch($violation->payment_status_text)
                                                    @case('paid')
                                                        <span class="badge bg-success">مدفوعة</span>
                                                        @break
                                                    @case('pending')
                                                        <span class="badge bg-warning">في الانتظار</span>
                                                        @break
                                                    @case('overdue')
                                                        <span class="badge bg-danger">متأخرة</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">غير محدد</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            @if($violation->violation_description)
                                <div class="mt-3">
                                    <h6 class="fw-bold text-info">وصف المخالفة</h6>
                                    <div class="alert alert-light">
                                        {{ $violation->violation_description }}
                                    </div>
                                </div>
                            @endif

                            @if($violation->responsible_party)
                                <div class="mt-3">
                                    <h6 class="fw-bold text-secondary">الجهة المسؤولة</h6>
                                    <p class="mb-0">{{ $violation->responsible_party }}</p>
                                </div>
                            @endif

                            @if($violation->notes)
                                <div class="mt-3">
                                    <h6 class="fw-bold text-success">ملاحظات</h6>
                                    <div class="alert alert-info">
                                        {{ $violation->notes }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            @if($violation->attachment_path)
                                <a href="{{ $violation->attachment_url }}" 
                                   class="btn btn-primary" 
                                   target="_blank">
                                    <i class="fas fa-download me-1"></i>تحميل المرفق
                                </a>
                            @endif
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- تبويب شهادة التنسيق -->
        <div class="tab-pane fade" id="coordination-certificate" role="tabpanel">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-certificate me-2"></i>
                        شهادة التنسيق
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">رقم شهادة التنسيق:</label>
                                <p class="mb-0">{{ $license->coordination_certificate_number ?? 'غير محدد' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">ملاحظات:</label>
                                <p class="mb-0">{{ $license->coordination_certificate_notes ?? 'لا توجد ملاحظات' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- قسم المرفقات -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-paperclip me-2"></i>
                        مرفقات شهادة التنسيق
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- شهادة التنسيق -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-certificate me-1"></i>
                                        شهادة التنسيق
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if($license->coordination_certificate_path)
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">
                                                <i class="fas fa-file-pdf me-1"></i>
                                                ملف الشهادة
                                            </span>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ Storage::url($license->coordination_certificate_path) }}" 
                                                   class="btn btn-outline-primary" 
                                                   target="_blank">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ Storage::url($license->coordination_certificate_path) }}" 
                                                   class="btn btn-outline-success" 
                                                   download>
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="fas fa-file-upload fa-2x mb-2"></i>
                                            <p class="mb-0">لم يتم رفع الملف</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- الخطابات والتعهدات -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-file-signature me-1"></i>
                                        الخطابات والتعهدات
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $letterFiles = null;
                                        
                                        // معالجة ملفات الخطابات والتعهدات
                                        if ($license->letters_commitments_file_path) {
                                            if (is_string($license->letters_commitments_file_path)) {
                                                $decoded = json_decode($license->letters_commitments_file_path, true);
                                                $letterFiles = is_array($decoded) ? $decoded : [$license->letters_commitments_file_path];
                                            } else {
                                                $letterFiles = [$license->letters_commitments_file_path];
                                            }
                                        }
                                    @endphp

                                    @if($letterFiles)
                                        @foreach($letterFiles as $index => $filePath)
                                            @if(Storage::exists($filePath))
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="text-muted">
                                                        <i class="fas fa-file-signature me-1"></i>
                                                        ملف {{ $index + 1 }}
                                                    </span>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ Storage::url($filePath) }}" 
                                                           class="btn btn-outline-primary" 
                                                           target="_blank" title="عرض">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ Storage::url($filePath) }}" 
                                                           class="btn btn-outline-success" 
                                                           download title="تحميل">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-muted small mb-1">
                                                    <i class="fas fa-file-times me-1 text-danger"></i>
                                                    ملف {{ $index + 1 }} (غير متوفر)
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="fas fa-file-signature fa-2x mb-2"></i>
                                            <p class="mb-0">لم يتم رفع ملفات الخطابات</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- إيصالات الدفع -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0">
                                        <i class="fas fa-receipt me-1"></i>
                                        إيصالات الدفع
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $paymentInvoices = null;
                                        $paymentProofs = null;
                                        
                                        // معالجة إيصالات الدفع
                                        if ($license->payment_invoices_path) {
                                            if (is_string($license->payment_invoices_path)) {
                                                $decoded = json_decode($license->payment_invoices_path, true);
                                                $paymentInvoices = is_array($decoded) ? $decoded : [$license->payment_invoices_path];
                                            } else {
                                                $paymentInvoices = [$license->payment_invoices_path];
                                            }
                                        }
                                        
                                        // معالجة إثباتات الدفع
                                        if ($license->payment_proof_path) {
                                            if (is_string($license->payment_proof_path)) {
                                                $decoded = json_decode($license->payment_proof_path, true);
                                                $paymentProofs = is_array($decoded) ? $decoded : [$license->payment_proof_path];
                                            } else {
                                                $paymentProofs = [$license->payment_proof_path];
                                            }
                                        }
                                    @endphp

                                    @if($paymentInvoices)
                                        <div class="mb-3">
                                            <strong class="text-primary d-block mb-2">
                                                <i class="fas fa-file-invoice-dollar me-1"></i>إيصالات الدفع
                                            </strong>
                                            @foreach($paymentInvoices as $index => $filePath)
                                                @if(Storage::exists($filePath))
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <span class="text-muted small">
                                                            <i class="fas fa-file me-1"></i>إيصال {{ $index + 1 }}
                                                        </span>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="{{ Storage::url($filePath) }}" 
                                                               class="btn btn-outline-primary" 
                                                               target="_blank" title="عرض">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ Storage::url($filePath) }}" 
                                                               class="btn btn-outline-success" 
                                                               download title="تحميل">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($paymentProofs)
                                        <div class="mb-3">
                                            <strong class="text-success d-block mb-2">
                                                <i class="fas fa-check-circle me-1"></i>إثباتات الدفع
                                            </strong>
                                            @foreach($paymentProofs as $index => $filePath)
                                                @if(Storage::exists($filePath))
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <span class="text-muted small">
                                                            <i class="fas fa-file me-1"></i>إثبات {{ $index + 1 }}
                                                        </span>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="{{ Storage::url($filePath) }}" 
                                                               class="btn btn-outline-primary" 
                                                               target="_blank" title="عرض">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ Storage::url($filePath) }}" 
                                                               class="btn btn-outline-success" 
                                                               download title="تحميل">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif

                                    @if(!$paymentInvoices && !$paymentProofs)
                                        <div class="text-center text-muted">
                                            <i class="fas fa-file-invoice-dollar fa-2x mb-2"></i>
                                            <p class="mb-0">لم يتم رفع ملفات الدفع</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- ملف رخصة الحفر -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-hard-hat me-1"></i>
                                        ملف رخصة الحفر
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if($license->license_file_path)
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">
                                                <i class="fas fa-file-pdf me-1"></i>
                                                ملف الرخصة
                                            </span>
                                            <div class="btn-group btn-group-sm">
                                                @if(Storage::exists($license->license_file_path))
                                                    <a href="{{ Storage::url($license->license_file_path) }}" 
                                                       class="btn btn-outline-primary" 
                                                       target="_blank" title="عرض">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ Storage::url($license->license_file_path) }}" 
                                                       class="btn btn-outline-success" 
                                                       download title="تحميل">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @else
                                                    <span class="text-danger small">الملف غير متوفر</span>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="fas fa-hard-hat fa-2x mb-2"></i>
                                            <p class="mb-0">لم يتم رفع ملف الرخصة</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- ملاحظات ومرفقات إضافية -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-sticky-note me-1"></i>
                                        ملاحظات ومرفقات إضافية
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $notesFiles = null;
                                        
                                        // معالجة مرفقات الملاحظات
                                        if ($license->notes_attachments_path) {
                                            if (is_string($license->notes_attachments_path)) {
                                                $decoded = json_decode($license->notes_attachments_path, true);
                                                $notesFiles = is_array($decoded) ? $decoded : [$license->notes_attachments_path];
                                            } else {
                                                $notesFiles = [$license->notes_attachments_path];
                                            }
                                        }
                                    @endphp

                                    <!-- عرض الملاحظات النصية -->
                                    @if($license->notes)
                                        <div class="mb-3">
                                            <strong class="text-info d-block mb-2">
                                                <i class="fas fa-sticky-note me-1"></i>الملاحظات
                                            </strong>
                                            <div class="alert alert-light p-2">
                                                <small>{{ $license->notes }}</small>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- عرض المرفقات -->
                                    @if($notesFiles)
                                        <div class="mb-2">
                                            <strong class="text-secondary d-block mb-2">
                                                <i class="fas fa-paperclip me-1"></i>المرفقات
                                            </strong>
                                            @foreach($notesFiles as $index => $filePath)
                                                @if(Storage::exists($filePath))
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <span class="text-muted small">
                                                            <i class="fas fa-file me-1"></i>مرفق {{ $index + 1 }}
                                                        </span>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="{{ Storage::url($filePath) }}" 
                                                               class="btn btn-outline-primary" 
                                                               target="_blank" title="عرض">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ Storage::url($filePath) }}" 
                                                               class="btn btn-outline-success" 
                                                               download title="تحميل">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="text-muted small mb-1">
                                                        <i class="fas fa-file-times me-1 text-danger"></i>
                                                        مرفق {{ $index + 1 }} (غير متوفر)
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif

                                    @if(!$license->notes && !$notesFiles)
                                        <div class="text-center text-muted">
                                            <i class="fas fa-sticky-note fa-2x mb-2"></i>
                                            <p class="mb-0">لا توجد ملاحظات أو مرفقات</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- قسم معلومات ومرفقات رخصة الحفر -->
            @if($license->license_number || $license->license_file_path || $license->license_value || $license->excavation_length)
            <div class="card mt-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-hard-hat me-2"></i>
                        معلومات ومرفقات رخصة الحفر
                    </h5>
                </div>
                <div class="card-body">
                    <!-- بيانات رخصة الحفر -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-info-circle me-1"></i>
                                        معلومات الرخصة
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>رقم الرخصة:</strong></td>
                                            <td><span class="badge bg-primary">{{ $license->license_number ?? 'غير محدد' }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>نوع الرخصة:</strong></td>
                                            <td>{{ $license->license_type ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>تاريخ الرخصة:</strong></td>
                                            <td>{{ $license->license_date ? \Carbon\Carbon::parse($license->license_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>قيمة الرخصة:</strong></td>
                                            <td>
                                                @if($license->license_value)
                                                    <span class="badge bg-success">{{ number_format($license->license_value, 2) }} ريال</span>
                                                @else
                                                    غير محدد
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-ruler-combined me-1"></i>
                                        أبعاد الحفر
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>الطول:</strong></td>
                                            <td>{{ $license->excavation_length ? $license->excavation_length . ' متر' : 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>العرض:</strong></td>
                                            <td>{{ $license->excavation_width ? $license->excavation_width . ' متر' : 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>العمق:</strong></td>
                                            <td>{{ $license->excavation_depth ? $license->excavation_depth . ' متر' : 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>الحجم الإجمالي:</strong></td>
                                            <td>
                                                @if($license->excavation_length && $license->excavation_width && $license->excavation_depth)
                                                    <span class="badge bg-warning text-dark">
                                                        {{ number_format($license->excavation_length * $license->excavation_width * $license->excavation_depth, 2) }} متر مكعب
                                                    </span>
                                                @else
                                                    غير محسوب
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تواريخ الرخصة والتمديد -->
                    @if($license->license_start_date || $license->license_end_date || $license->extension_start_date || $license->extension_end_date)
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-calendar me-1"></i>
                                        فترة الرخصة الأساسية
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>تاريخ البداية:</strong></td>
                                            <td>{{ $license->license_start_date ? \Carbon\Carbon::parse($license->license_start_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>تاريخ الانتهاء:</strong></td>
                                            <td>{{ $license->license_end_date ? \Carbon\Carbon::parse($license->license_end_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                        </tr>
                                        @if($license->license_start_date && $license->license_end_date)
                                        <tr>
                                            <td><strong>المدة:</strong></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ \Carbon\Carbon::parse($license->license_start_date)->diffInDays(\Carbon\Carbon::parse($license->license_end_date)) }} يوم
                                                </span>
                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                        @if($license->extension_start_date || $license->extension_end_date || $license->extension_value)
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0">
                                        <i class="fas fa-calendar-plus me-1"></i>
                                        فترة التمديد
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>تاريخ بداية التمديد:</strong></td>
                                            <td>{{ $license->extension_start_date ? \Carbon\Carbon::parse($license->extension_start_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>تاريخ انتهاء التمديد:</strong></td>
                                            <td>{{ $license->extension_end_date ? \Carbon\Carbon::parse($license->extension_end_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>قيمة التمديد:</strong></td>
                                            <td>
                                                @if($license->extension_value)
                                                    <span class="badge bg-success">{{ number_format($license->extension_value, 2) }} ريال</span>
                                                @else
                                                    غير محدد
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- ملخص مالي -->
                    @if($license->license_value || $license->extension_value)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-money-bill-wave me-1"></i>
                                        الملخص المالي
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-4">
                                            <div class="p-3 border rounded">
                                                <h6 class="text-primary">قيمة الرخصة الأساسية</h6>
                                                <h4 class="text-success">{{ number_format($license->license_value ?? 0, 2) }} ريال</h4>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-3 border rounded">
                                                <h6 class="text-warning">قيمة التمديد</h6>
                                                <h4 class="text-info">{{ number_format($license->extension_value ?? 0, 2) }} ريال</h4>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-3 border rounded bg-primary text-white">
                                                <h6>إجمالي التكلفة</h6>
                                                <h4>{{ number_format(($license->license_value ?? 0) + ($license->extension_value ?? 0), 2) }} ريال</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- أزرار الإجراءات -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <div class="btn-group">
                <a href="{{ route('admin.work-orders.license', $license->workOrder) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>رجوع
                </a>
                <a href="{{ route('admin.licenses.edit', $license) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>تعديل
                </a>
                <button onclick="window.print()" class="btn btn-success">
                    <i class="fas fa-print me-2"></i>طباعة
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.bg-primary {
    background: linear-gradient(45deg, #007bff, #0056b3) !important;
}

/* تحسين مظهر badges عدد الأيام */
.badge.bg-warning {
    background: linear-gradient(45deg, #ffc107, #ffdd54) !important;
    color: #212529 !important;
    font-weight: bold;
    padding: 0.6em 1em;
    border-radius: 1rem;
    box-shadow: 0 2px 8px rgba(255,193,7,0.3);
    animation: pulse-warning 3s infinite;
}

@keyframes pulse-warning {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.05); opacity: 0.9; }
}

@keyframes pulse-success {
    0%, 100% { transform: scale(1); box-shadow: 0 2px 8px rgba(40,167,69,0.3); }
    50% { transform: scale(1.05); box-shadow: 0 4px 16px rgba(40,167,69,0.6); }
}

/* تحسين مظهر إجمالي التمديدات */
#extensions-total-badge {
    transition: all 0.3s ease;
    border-radius: 12px !important;
    font-weight: bold;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}

#extensions-total-english {
    font-family: 'Arial', sans-serif;
    letter-spacing: 0.5px;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}

/* تحسين modal التمديدات */
#extensionModal .modal-content {
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

#extensionModal .card {
    border-radius: 10px;
    transition: transform 0.3s ease;
}

#extensionModal .card:hover {
    transform: translateY(-5px);
}

/* تحسين أزرار الإجراءات في جدول التمديدات */
#extensions-table-body .btn-group .btn {
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 2px;
    transition: all 0.3s ease;
}

#extensions-table-body .btn-outline-info:hover {
    background: linear-gradient(45deg, #17a2b8, #20c997);
    border-color: transparent;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(23,162,184,0.4);
}

#extensions-table-body .btn-outline-danger:hover {
    background: linear-gradient(45deg, #dc3545, #e74c3c);
    border-color: transparent;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(220,53,69,0.4);
}

/* تأثيرات المرور على صفوف التمديدات */
#extensions-table-body tr:hover {
    background: linear-gradient(45deg, rgba(255,193,7,0.1), rgba(255,235,59,0.1));
    transform: scale(1.01);
    transition: all 0.3s ease;
}

/* تحسين عام لجدول التمديدات */
#extensionsTable {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

#extensionsTable thead th {
    font-weight: bold;
    text-align: center;
    vertical-align: middle;
    border: none;
    background: linear-gradient(45deg,rgb(199, 195, 183),rgb(180, 177, 171));
    color: #212529;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}

#extensionsTable tbody td {
    vertical-align: middle;
    text-align: center;
    border-color: rgba(0,0,0,0.1);
}

/* تحسين بطاقة التمديدات */
.card:has(#extensionsTable) {
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border: none;
    overflow: hidden;
}

.card:has(#extensionsTable) .card-header {
    background: linear-gradient(45deg,rgb(126, 186, 226),rgb(143, 162, 204)) !important;
    border: none;
    padding: 1.5rem;
}

/* أنماط جدول التفاصيل الفنية للمختبر */
.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

#labDetailsTable .form-control-sm {
    font-size: 0.8rem;
    padding: 0.25rem 0.4rem;
}

#labDetailsTable th {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    font-weight: 600;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    border: 1px solid rgba(255,255,255,0.2);
}

#labDetailsTable td {
    padding: 0.3rem;
    vertical-align: middle;
}

#labDetailsTable .btn-danger {
    padding: 0.25rem 0.5rem;
    font-size: 0.8rem;
}

/* تأثيرات المرور للصفوف الجديدة */
#labDetailsTable .table-success {
    background: linear-gradient(135deg, rgba(40,167,69,0.1), rgba(32,201,151,0.1)) !important;
    animation: highlightRow 2s ease-in-out;
}

@keyframes highlightRow {
    0% { background: linear-gradient(135deg, #28a745, #20c997); }
    100% { background: transparent; }
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تفعيل Bootstrap tabs
    var triggerTabList = [].slice.call(document.querySelectorAll('[data-bs-toggle="pill"]'));
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl);
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault();
            tabTrigger.show();
        });
    });
    
    // تحميل التمديدات عند تحميل الصفحة
    loadLicenseExtensions();
});

// دالة تنسيق العملة بالعربية
function formatCurrency(amount) {
    if (!amount || amount === 0) return '0 ريال';
    
    const formatted = parseFloat(amount).toLocaleString('ar-SA', {
        style: 'currency',
        currency: 'SAR',
        currencyDisplay: 'name'
    });
    
    return formatted.replace('ريال سعودي', 'ريال');
}

// دالة تنسيق العملة بالإنجليزية
function formatCurrencyEnglish(amount) {
    if (!amount || amount === 0) return '0.00 SAR';
    
    const formatted = parseFloat(amount).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    return formatted + ' SAR';
}

// دالة تحديث إجمالي التمديدات
function updateExtensionsTotal(extensions) {
    let total = 0;
    
    if (extensions && extensions.length > 0) {
        total = extensions.reduce((sum, extension) => {
            return sum + (parseFloat(extension.extension_value) || 0);
        }, 0);
    }
    
    // تحديث النص العربي
    const arabicElement = document.getElementById('extensions-total-arabic');
    if (arabicElement) {
        arabicElement.textContent = `إجمالي: ${formatCurrency(total)}`;
    }
    
    // تحديث النص الإنجليزي
    const englishElement = document.getElementById('extensions-total-english');
    if (englishElement) {
        englishElement.textContent = `Total: ${formatCurrencyEnglish(total)}`;
    }
    
    // إضافة تأثير بصري للإجمالي
    const badgeElement = document.getElementById('extensions-total-badge');
    if (badgeElement) {
        if (total > 0) {
            badgeElement.classList.remove('bg-secondary');
            badgeElement.classList.add('bg-success');
            badgeElement.style.animation = 'pulse-success 2s infinite';
        } else {
            badgeElement.classList.remove('bg-success');
            badgeElement.classList.add('bg-secondary');
            badgeElement.style.animation = 'none';
        }
    }
}

// دالة تحميل التمديدات لهذه الرخصة
function loadLicenseExtensions() {
    // التحقق من وجود العناصر المطلوبة قبل المتابعة
    const tbody = document.getElementById('extensions-table-body');
    if (!tbody) {
        console.warn('Extension table body element not found');
        return;
    }

    $.ajax({
        url: `/admin/licenses/extensions/by-license/{{ $license->id }}`,
        type: 'GET',
        success: function(response) {
            
            // مسح الجدول
            tbody.innerHTML = '';

            if (!response.extensions || response.extensions.length === 0) {
                const noDataRow = document.createElement('tr');
                noDataRow.id = 'no-extensions-row';
                noDataRow.innerHTML = `
                    <td colspan="8" class="text-center text-muted">
                        <i class="fas fa-calendar-times fa-2x mb-3 d-block"></i>
                        لا توجد تمديدات محفوظة لهذه الرخصة
                    </td>
                `;
                tbody.appendChild(noDataRow);
                
                // تحديث الإجمالي إلى صفر
                updateExtensionsTotal([]);
                return;
            }

            response.extensions.forEach((extension, index) => {
                const startDate = extension.extension_start_date ? 
                    new Date(extension.extension_start_date).toLocaleDateString('en-GB') : '';
                const endDate = extension.extension_end_date ? 
                    new Date(extension.extension_end_date).toLocaleDateString('en-GB') : '';
                
                // حساب عدد الأيام إذا لم تكن محفوظة
                let extensionDays = extension.extension_days || 0;
                if (!extensionDays && extension.extension_start_date && extension.extension_end_date) {
                    const start = new Date(extension.extension_start_date);
                    const end = new Date(extension.extension_end_date);
                    const diffTime = Math.abs(end - start);
                    extensionDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                }
                
                const attachments = [];
                if (extension.extension_license_file) {
                    attachments.push(`<a href="${extension.extension_license_file}" target="_blank" class="btn btn-outline-primary btn-sm me-1" title="ملف الرخصة"><i class="fas fa-file-pdf"></i></a>`);
                }
                if (extension.extension_payment_proof) {
                    attachments.push(`<a href="${extension.extension_payment_proof}" target="_blank" class="btn btn-outline-success btn-sm me-1" title="إثبات السداد"><i class="fas fa-receipt"></i></a>`);
                }
                if (extension.extension_bank_proof) {
                    attachments.push(`<a href="${extension.extension_bank_proof}" target="_blank" class="btn btn-outline-info btn-sm" title="إثبات البنك"><i class="fas fa-university"></i></a>`);
                }
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td><strong class="text-primary">رخصة #${extension.license_number || '{{ $license->license_number }}'}</strong></td>
                    <td><strong class="text-success">${formatCurrency(extension.extension_value)}</strong></td>
                    <td><small>${startDate}</small></td>
                    <td><small>${endDate}</small></td>
                    <td>
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-clock me-1"></i>${extensionDays} يوم
                        </span>
                    </td>
                    <td>${attachments.join('') || '<span class="text-muted">لا توجد مرفقات</span>'}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-info" onclick="viewExtension(${extension.id})" title="عرض">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-danger" onclick="deleteExtension(${extension.id})" title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
            
            // تحديث إجمالي التمديدات
            updateExtensionsTotal(response.extensions);
        },
        error: function(xhr) {
            console.error('Error loading extensions:', xhr);
            
            // في حالة عدم وجود endpoint، عرض بيانات تجريبية
            if (xhr.status === 404) {
                loadSampleExtensions();
            } else {
                toastr.error('حدث خطأ في تحميل التمديدات');
            }
        }
    });
}

// دالة عرض بيانات تجريبية للتمديدات (للاختبار)
function loadSampleExtensions() {
    const tbody = document.getElementById('extensions-table-body');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    // بيانات تجريبية
    const sampleExtensions = [
        {
            id: 1,
            license_number: '{{ $license->license_number }}',
            extension_value: 5000,
            extension_start_date: '2024-01-15',
            extension_end_date: '2024-02-14',
            extension_days: 30,
            extension_license_file: '/path/to/license.pdf',
            extension_payment_proof: '/path/to/payment.pdf'
        },
        {
            id: 2,
            license_number: '{{ $license->license_number }}',
            extension_value: 3500,
            extension_start_date: '2024-02-15',
            extension_end_date: '2024-03-10',
            extension_days: 24,
            extension_bank_proof: '/path/to/bank.pdf'
        }
    ];
    
    sampleExtensions.forEach((extension, index) => {
        const startDate = new Date(extension.extension_start_date).toLocaleDateString('en-GB');
        const endDate = new Date(extension.extension_end_date).toLocaleDateString('en-GB');
        
        const attachments = [];
        if (extension.extension_license_file) {
            attachments.push(`<a href="${extension.extension_license_file}" target="_blank" class="btn btn-outline-primary btn-sm me-1" title="ملف الرخصة"><i class="fas fa-file-pdf"></i></a>`);
        }
        if (extension.extension_payment_proof) {
            attachments.push(`<a href="${extension.extension_payment_proof}" target="_blank" class="btn btn-outline-success btn-sm me-1" title="إثبات السداد"><i class="fas fa-receipt"></i></a>`);
        }
        if (extension.extension_bank_proof) {
            attachments.push(`<a href="${extension.extension_bank_proof}" target="_blank" class="btn btn-outline-info btn-sm me-1" title="إثبات البنك"><i class="fas fa-university"></i></a>`);
        }
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td><strong class="text-primary">رخصة #${extension.license_number}</strong></td>
            <td><strong class="text-success">${formatCurrency(extension.extension_value)}</strong></td>
            <td><small>${startDate}</small></td>
            <td><small>${endDate}</small></td>
            <td>
                <span class="badge bg-warning text-dark">
                    <i class="fas fa-clock me-1"></i>${extension.extension_days} يوم
                </span>
            </td>
            <td>${attachments.join('') || '<span class="text-muted">لا توجد مرفقات</span>'}</td>
            <td>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-info" onclick="viewExtension(${extension.id})" title="عرض">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-outline-danger" onclick="deleteExtension(${extension.id})" title="حذف">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
    
    // تحديث إجمالي التمديدات للبيانات التجريبية
    updateExtensionsTotal(sampleExtensions);
    
    // إضافة رسالة أن هذه بيانات تجريبية
    if (typeof toastr !== 'undefined') {
        toastr.info('تم عرض بيانات تجريبية للتمديدات', 'ملاحظة');
    }
}

// دالة عرض تفاصيل التمديد
function viewExtension(extensionId) {
    if (!extensionId) {
        alert('معرف التمديد غير صحيح');
        return;
    }
    
    // التحقق من وجود الجدول أولاً
    const tbody = document.getElementById('extensions-table-body');
    if (!tbody) {
        alert('جدول التمديدات غير موجود');
        return;
    }
    
    // البحث عن التمديد في الجدول للحصول على التفاصيل
    const button = document.querySelector(`#extensions-table-body tr td button[onclick="viewExtension(${extensionId})"]`);
    if (!button) {
        alert('لم يتم العثور على بيانات التمديد');
        return;
    }
    
    const row = button.closest('tr');
    if (!row) {
        alert('لم يتم العثور على بيانات التمديد');
        return;
    }
    
    const cells = row.cells;
    const extensionDetails = {
        number: cells[0].textContent,
        licenseNumber: cells[1].textContent,
        value: cells[2].textContent,
        startDate: cells[3].textContent,
        endDate: cells[4].textContent,
        days: cells[5].textContent
    };
    
    // إنشاء modal لعرض التفاصيل
    const modalHtml = `
        <div class="modal fade" id="extensionModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">
                            <i class="fas fa-calendar-plus me-2"></i>
                            تفاصيل التمديد #${extensionDetails.number}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="fas fa-certificate me-2"></i>بيانات الرخصة</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>رقم الرخصة:</strong> ${extensionDetails.licenseNumber}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class="fas fa-money-bill me-2"></i>بيانات التمديد</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>قيمة التمديد:</strong> ${extensionDetails.value}</p>
                                        <p><strong>عدد الأيام:</strong> ${extensionDetails.days}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0"><i class="fas fa-calendar me-2"></i>فترة التمديد</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>تاريخ البداية:</strong> ${extensionDetails.startDate}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>تاريخ النهاية:</strong> ${extensionDetails.endDate}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>إغلاق
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // إزالة أي modal موجود مسبقاً
    const existingModal = document.getElementById('extensionModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // إضافة modal جديد
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // إظهار modal
    const modal = new bootstrap.Modal(document.getElementById('extensionModal'));
    modal.show();
}

// دالة حذف التمديد
function deleteExtension(extensionId) {
    if (!extensionId) {
        alert('معرف التمديد غير صحيح');
        return;
    }

    if (!confirm('هل أنت متأكد من حذف هذا التمديد؟ سيتم حذفه نهائياً من الرخصة.')) {
        return;
    }

    $.ajax({
        url: `/admin/license-extensions/${extensionId}`,
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (typeof toastr !== 'undefined') {
                toastr.success('تم حذف التمديد بنجاح');
            } else {
                alert('تم حذف التمديد بنجاح');
            }
            
            // إعادة تحميل التمديدات
            loadLicenseExtensions();
        },
        error: function(xhr) {
            console.error('Error deleting extension:', xhr);
            
                         // في حالة عدم وجود endpoint، محاكاة الحذف
             if (xhr.status === 404) {
                 // حذف الصف من الجدول مباشرة (للاختبار)
                 const tbody = document.getElementById('extensions-table-body');
                 if (!tbody) {
                     console.warn('Extension table body not found');
                     return;
                 }
                 
                 const deleteBtn = tbody.querySelector(`button[onclick="deleteExtension(${extensionId})"]`);
                 const row = deleteBtn ? deleteBtn.closest('tr') : null;
                if (row) {
                    row.remove();
                    
                    if (typeof toastr !== 'undefined') {
                        toastr.success('تم حذف التمديد بنجاح (محاكاة)');
                    } else {
                        alert('تم حذف التمديد بنجاح');
                    }
                    
                                         // التحقق من وجود صفوف أخرى وتحديث الإجمالي
                     if (tbody && tbody.children.length === 0) {
                        const noDataRow = document.createElement('tr');
                        noDataRow.id = 'no-extensions-row';
                        noDataRow.innerHTML = `
                            <td colspan="8" class="text-center text-muted">
                                <i class="fas fa-calendar-times fa-2x mb-3 d-block"></i>
                                لا توجد تمديدات محفوظة لهذه الرخصة
                            </td>
                        `;
                        tbody.appendChild(noDataRow);
                        
                        // تحديث الإجمالي إلى صفر
                        updateExtensionsTotal([]);
                    } else {
                        // إعادة حساب الإجمالي من الصفوف المتبقية
                        const remainingExtensions = [];
                        const rows = tbody.querySelectorAll('tr:not(#no-extensions-row)');
                        rows.forEach(row => {
                            const valueCell = row.cells[2]; // عمود قيمة التمديد
                            if (valueCell) {
                                const valueText = valueCell.textContent.trim();
                                const value = parseFloat(valueText.replace(/[^\d.]/g, '')) || 0;
                                remainingExtensions.push({ extension_value: value });
                            }
                        });
                        updateExtensionsTotal(remainingExtensions);
                    }
                }
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.error('حدث خطأ في حذف التمديد');
                } else {
                    alert('حدث خطأ في حذف التمديد');
                }
            }
        }
    });
}

// ==================== دوال إدارة الجداول ====================



// دالة إضافة صف جديد لجدول فسح الإخلاءات
function addEvacTable1Row() {
    const tbody = document.querySelector('#evacTable1 tbody');
    const noDataRow = document.getElementById('no-evac-table1-data');
    
    if (noDataRow) {
        noDataRow.remove();
    }
    
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td><input type="text" class="form-control form-control-sm" name="clearance_number" placeholder="رقم الفسح"></td>
        <td><input type="date" class="form-control form-control-sm" name="clearance_date"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="length" placeholder="طول الفسح"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="lab_length" placeholder="طول المختبر"></td>
        <td><input type="text" class="form-control form-control-sm" name="street_type" placeholder="نوع الشارع"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="soil_quantity" placeholder="كمية التربة"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="asphalt_quantity" placeholder="كمية الأسفلت"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="tile_quantity" placeholder="كمية البلاط"></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteTableRow(this)"><i class="fas fa-trash"></i></button></td>
    `;
    tbody.appendChild(newRow);
}

// دالة إضافة صف جديد لجدول التفاصيل الفنية للإخلاءات
function addEvacTable2Row() {
    const tbody = document.querySelector('#evacTable2 tbody');
    const noDataRow = document.getElementById('no-evac-table2-data');
    
    if (noDataRow) {
        noDataRow.remove();
    }
    
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td><input type="number" class="form-control form-control-sm" name="year" placeholder="السنة"></td>
        <td><input type="text" class="form-control form-control-sm" name="work_type" placeholder="نوع العمل"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="depth" placeholder="العمق"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="soil_compaction" placeholder="دك التربة"></td>
        <td><input type="text" class="form-control form-control-sm" name="mc1_test" placeholder="MC1-RC2"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="asphalt_test" placeholder="دك أسفلت"></td>
        <td><input type="text" class="form-control form-control-sm" name="soil_test" placeholder="فحص التربة"></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteTableRow(this)"><i class="fas fa-trash"></i></button></td>
    `;
    tbody.appendChild(newRow);
}

// دالة حذف صف من أي جدول
function deleteTableRow(button) {
    if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
        const row = button.closest('tr');
        const tbody = row.closest('tbody');
        row.remove();
        
        // إذا لم تعد هناك صفوف، إضافة رسالة "لا توجد بيانات"
        if (tbody.children.length === 0) {
            const tableId = tbody.closest('table').id;
            let noDataId, colSpan;
            
            switch(tableId) {
                case 'evacTable1':
                    noDataId = 'no-evac-table1-data';
                    colSpan = 9;
                    break;
                case 'evacTable2':
                    noDataId = 'no-evac-table2-data';
                    colSpan = 8;
                    break;
                case 'labDetailsTable':
                    noDataId = 'no-lab-details-data';
                    colSpan = 15;
                    break;
                default:
                    colSpan = 5;
            }
            
            const noDataRow = document.createElement('tr');
            noDataRow.id = noDataId;
            noDataRow.innerHTML = `<td colspan="${colSpan}" class="text-center">لا توجد بيانات - اضغط "إضافة صف" لإضافة بيانات جديدة</td>`;
            tbody.appendChild(noDataRow);
        }
        
        toastr.success('تم حذف السجل بنجاح');
    }
}



// دالة حفظ بيانات جدول فسح الإخلاءات
function saveEvacTable1Data() {
    const rows = document.querySelectorAll('#evacTable1 tbody tr:not(#no-evac-table1-data)');
    if (rows.length === 0) {
        toastr.warning('لا توجد بيانات للحفظ');
        return;
    }
    
    const tableData = [];
    rows.forEach(row => {
        const inputs = row.querySelectorAll('input');
        const rowData = {};
        
        inputs.forEach(input => {
            if (input.value) {
                rowData[input.name] = input.value;
            }
        });
        
        if (Object.keys(rowData).length > 0) {
            tableData.push(rowData);
        }
    });
    
    if (tableData.length === 0) {
        toastr.warning('لا توجد بيانات صحيحة للحفظ');
        return;
    }
    
    // إرسال البيانات للخادم
    $.ajax({
        url: '{{ route("admin.licenses.save-section") }}',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            license_id: {{ $license->id }},
            section: 'evac_table1',
            data: tableData
        },
        success: function(response) {
            toastr.success('تم حفظ جدول فسح الإخلاءات بنجاح');
        },
        error: function(xhr) {
            console.error('خطأ في حفظ البيانات:', xhr);
            toastr.error('حدث خطأ أثناء حفظ البيانات');
        }
    });
}

// دالة حفظ بيانات جدول التفاصيل الفنية للإخلاءات
function saveEvacTable2Data() {
    const rows = document.querySelectorAll('#evacTable2 tbody tr:not(#no-evac-table2-data)');
    if (rows.length === 0) {
        toastr.warning('لا توجد بيانات للحفظ');
        return;
    }
    
    const tableData = [];
    rows.forEach(row => {
        const inputs = row.querySelectorAll('input');
        const rowData = {};
        
        inputs.forEach(input => {
            if (input.type === 'checkbox') {
                rowData[input.name] = input.checked;
            } else if (input.value) {
                rowData[input.name] = input.value;
            }
        });
        
        if (Object.keys(rowData).length > 0) {
            tableData.push(rowData);
        }
    });
    
    if (tableData.length === 0) {
        toastr.warning('لا توجد بيانات صحيحة للحفظ');
        return;
    }
    
    // إرسال البيانات للخادم
    $.ajax({
        url: '{{ route("admin.licenses.save-section") }}',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            license_id: {{ $license->id }},
            section: 'evac_table2',
            data: tableData
        },
        success: function(response) {
            toastr.success('تم حفظ جدول التفاصيل الفنية للإخلاءات بنجاح');
        },
        error: function(xhr) {
            console.error('خطأ في حفظ البيانات:', xhr);
            toastr.error('حدث خطأ أثناء حفظ البيانات');
        }
    });
}

// دالة إضافة صف جديد لجدول التفاصيل الفنية للمختبر
function addLabDetailsRow() {
    const labTable = document.getElementById('labDetailsTable');
    if (!labTable) {
        toastr.error('لم يتم العثور على جدول التفاصيل الفنية');
        return;
    }
    
    const tbody = labTable.querySelector('tbody');
    if (!tbody) {
        toastr.error('لم يتم العثور على body الجدول');
        return;
    }
    
    const noDataRow = document.getElementById('no-lab-details-data');
    
    if (noDataRow) {
        noDataRow.remove();
    }
    
    const newRow = document.createElement('tr');
    const currentYear = new Date().getFullYear();
    
    newRow.innerHTML = `
        <td><input type="number" class="form-control form-control-sm" name="lab_year" value="${currentYear}" min="2020" max="2030"></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_work_type" placeholder="نوع العمل"></td>
        <td><input type="number" class="form-control form-control-sm" name="lab_depth" step="0.01" placeholder="العمق"></td>
        <td><input type="number" class="form-control form-control-sm" name="lab_soil_compaction" step="0.01" placeholder="دك التربة %"></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_mc1rc2" placeholder="MC1-RC2"></td>
        <td><input type="number" class="form-control form-control-sm" name="lab_asphalt_compaction" step="0.01" placeholder="دك أسفلت %"></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_soil_type" placeholder="ترابي"></td>
        <td><input type="number" class="form-control form-control-sm" name="lab_max_asphalt_density" step="0.01" placeholder=""></td>
        <td><input type="number" class="form-control form-control-sm" name="lab_asphalt_percentage" step="0.01" placeholder="نسبة الأسفلت "></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_marshall_test" placeholder="نتيجة مارشال"></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_tile_evaluation" placeholder="تقييم البلاط"></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_soil_classification" placeholder="تصنيف التربة"></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_proctor_test" placeholder="نتيجة بروكتور"></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_concrete" placeholder="مقاومة الخرسانة"></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteLabDetailsRow(this)" title="حذف الصف"><i class="fas fa-trash"></i></button></td>
    `;
    
    tbody.appendChild(newRow);
    
    // إضافة تأثير تمييز
    newRow.classList.add('table-success');
    setTimeout(() => {
        newRow.classList.remove('table-success');
    }, 2000);
}

// دالة حذف صف من جدول التفاصيل الفنية للمختبر
function deleteLabDetailsRow(button) {
    if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
        const row = button.closest('tr');
        if (!row) {
            toastr.error('خطأ في العثور على الصف');
            return;
        }
        
        const tbody = row.closest('tbody');
        if (!tbody) {
            toastr.error('خطأ في العثور على tbody');
            return;
        }
        
        row.remove();
        
        // إذا لم تعد هناك صفوف، إضافة رسالة "لا توجد بيانات"
        if (tbody.children.length === 0) {
            const noDataRow = document.createElement('tr');
            noDataRow.id = 'no-lab-details-data';
            noDataRow.innerHTML = `<td colspan="15" class="text-center">لا توجد بيانات - اضغط "إضافة صف" لإضافة بيانات جديدة</td>`;
            tbody.appendChild(noDataRow);
        }
        
        toastr.success('تم حذف السجل بنجاح');
    }
}

// دالة حفظ بيانات جدول التفاصيل الفنية للمختبر
function saveLabDetailsData() {
    const labTable = document.getElementById('labDetailsTable');
    if (!labTable) {
        toastr.error('لم يتم العثور على جدول التفاصيل الفنية');
        return;
    }
    
    const rows = labTable.querySelectorAll('tbody tr:not(#no-lab-details-data)');
    if (rows.length === 0) {
        toastr.warning('لا توجد بيانات للحفظ');
        return;
    }
    
    const tableData = [];
    rows.forEach(row => {
        const inputs = row.querySelectorAll('input');
        const rowData = {};
        
        inputs.forEach(input => {
            if (input.value.trim()) {
                // إزالة 'lab_' من بداية الاسم
                const fieldName = input.name.replace('lab_', '');
                rowData[fieldName] = input.value.trim();
            }
        });
        
        if (Object.keys(rowData).length > 0) {
            tableData.push(rowData);
        }
    });
    
    if (tableData.length === 0) {
        toastr.warning('لا توجد بيانات صحيحة للحفظ');
        return;
    }
    
    // إرسال البيانات للخادم
    $.ajax({
        url: '{{ route("admin.licenses.save-section") }}',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            license_id: {{ $license->id }},
            section: 'lab_table2',
            data: tableData
        },
        success: function(response) {
            toastr.success('تم حفظ جدول التفاصيل الفنية للمختبر بنجاح');
        },
        error: function(xhr) {
            console.error('خطأ في حفظ البيانات:', xhr);
            toastr.error('حدث خطأ أثناء حفظ البيانات');
        }
    });
}

// ==================== وظائف المختبر الديناميكية ====================

// تحديث عدادات المختبر
function updateLabCounters() {
    let passedCount = 0;
    let failedCount = 0;
    let totalValue = 0;

    document.querySelectorAll('.lab-test-card').forEach(card => {
        const testField = card.dataset.test;
        const passedRadio = card.querySelector('input[value="passed"]:checked');
        const failedRadio = card.querySelector('input[value="failed"]:checked');
        const valueInput = card.querySelector('.test-value');
        const statusIcon = card.querySelector('.test-status-icon');
        
        // تحديث لون البطاقة وأيقونة الحالة
        card.classList.remove('border-success', 'border-danger', 'border-warning');
        
        if (passedRadio) {
            passedCount++;
            card.classList.add('border-success');
            statusIcon.className = 'test-status-icon fas fa-check-circle text-success';
            
            if (valueInput && valueInput.value) {
                totalValue += parseFloat(valueInput.value) || 0;
            }
        } else if (failedRadio) {
            failedCount++;
            card.classList.add('border-danger');
            statusIcon.className = 'test-status-icon fas fa-times-circle text-danger';
        } else {
            card.classList.add('border-warning');
            statusIcon.className = 'test-status-icon fas fa-question-circle text-secondary';
        }
    });

    // تحديث العدادات في الواجهة
    document.getElementById('lab-passed-count').textContent = `ناجح: ${passedCount}`;
    document.getElementById('lab-failed-count').textContent = `راسب: ${failedCount}`;
    document.getElementById('lab-total-value').textContent = `إجمالي: ${totalValue.toFixed(2)} ريال`;
}

// حفظ حالة اختبار واحد
function saveTestStatus(testField, status, value) {
    const licenseId = {{ $license->id }};
    
    const formData = new FormData();
    formData.append('license_id', licenseId);
    formData.append('test_field', testField);
    formData.append('status', status);
    
    if (value) {
        formData.append('value', value);
    }

    fetch('{{ route("admin.licenses.lab-test.save-status") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success(data.message);
            updateLabCounters();
        } else {
            toastr.error(data.message);
        }
    })
    .catch(error => {
        console.error('Error saving test status:', error);
        toastr.error('حدث خطأ أثناء حفظ حالة الاختبار');
    });
}

// رفع ملف اختبار
function uploadTestFile(testField, file) {
    const licenseId = {{ $license->id }};
    
    const formData = new FormData();
    formData.append('license_id', licenseId);
    formData.append('test_field', testField);
    formData.append('file', file);

    fetch('{{ route("admin.licenses.lab-test.upload-file") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success(data.message);
            
            // تحديث واجهة المرفق
            const card = document.querySelector(`[data-test="${testField}"]`);
            const fileSection = card.querySelector('.file-upload-section');
            
            fileSection.innerHTML = `
                <div class="current-file mb-2">
                    <div class="d-flex align-items-center justify-content-between p-2 bg-success bg-opacity-10 rounded">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-file-pdf text-success me-2"></i>
                            <span class="text-success">يوجد مرفق</span>
                        </div>
                        <div class="d-flex gap-1">
                            <a href="${data.file_url}" target="_blank" class="btn btn-outline-success btn-sm" title="عرض">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="btn btn-outline-danger btn-sm delete-file-btn" data-test="${testField}" title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <input type="file" class="form-control file-input" data-test="${testField}" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
            `;
        } else {
            toastr.error(data.message);
        }
    })
    .catch(error => {
        console.error('Error uploading file:', error);
        toastr.error('حدث خطأ أثناء رفع الملف');
    });
}

// حذف ملف اختبار
function deleteTestFile(testField) {
    const licenseId = {{ $license->id }};
    
    const formData = new FormData();
    formData.append('license_id', licenseId);
    formData.append('test_field', testField);

    fetch('{{ route("admin.licenses.lab-test.delete-file") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success(data.message);
            
            // تحديث واجهة المرفق
            const card = document.querySelector(`[data-test="${testField}"]`);
            const fileSection = card.querySelector('.file-upload-section');
            
            fileSection.innerHTML = `
                <input type="file" class="form-control file-input" data-test="${testField}" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
            `;
        } else {
            toastr.error(data.message);
        }
    })
    .catch(error => {
        console.error('Error deleting file:', error);
        toastr.error('حدث خطأ أثناء حذف الملف');
    });
}

// إضافة أحداث للاختبارات
document.addEventListener('DOMContentLoaded', function() {
    // تحديث العدادات عند التحميل
    updateLabCounters();
    
    // أحداث تغيير حالة الاختبار
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('test-status')) {
            const card = e.target.closest('.lab-test-card');
            const testField = card.dataset.test;
            const status = e.target.value;
            const valueInput = card.querySelector('.test-value');
            const value = valueInput ? valueInput.value : null;
            
            saveTestStatus(testField, status, value);
        }
        
        // أحداث تغيير القيمة
        if (e.target.classList.contains('test-value')) {
            const card = e.target.closest('.lab-test-card');
            const testField = card.dataset.test;
            const statusRadio = card.querySelector('input[type="radio"]:checked');
            
            if (statusRadio) {
                const status = statusRadio.value;
                const value = e.target.value;
                
                saveTestStatus(testField, status, value);
            }
        }
        
        // أحداث رفع الملفات
        if (e.target.classList.contains('file-input')) {
            const testField = e.target.dataset.test;
            const file = e.target.files[0];
            
            if (file) {
                uploadTestFile(testField, file);
            }
        }
    });
    
    // أحداث حذف الملفات
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-file-btn')) {
            const btn = e.target.closest('.delete-file-btn');
            const testField = btn.dataset.test;
            
            if (confirm('هل أنت متأكد من حذف هذا المرفق؟')) {
                deleteTestFile(testField);
            }
        }
    });
    
    // أزرار التحكم
    document.getElementById('save-all-tests')?.addEventListener('click', function() {
        const cards = document.querySelectorAll('.lab-test-card');
        let savedCount = 0;
        
        cards.forEach(card => {
            const testField = card.dataset.test;
            const statusRadio = card.querySelector('input[type="radio"]:checked');
            const valueInput = card.querySelector('.test-value');
            
            if (statusRadio) {
                const status = statusRadio.value;
                const value = valueInput ? valueInput.value : null;
                
                saveTestStatus(testField, status, value);
                savedCount++;
            }
        });
        
        if (savedCount > 0) {
            toastr.success(`تم حفظ ${savedCount} اختبار بنجاح`);
        } else {
            toastr.warning('لا توجد تغييرات للحفظ');
        }
    });
    
    document.getElementById('export-lab-report')?.addEventListener('click', function() {
        toastr.info('جارِ إعداد التقرير...');
        setTimeout(() => {
            window.print();
        }, 500);
    });
    
    document.getElementById('reset-lab-tests')?.addEventListener('click', function() {
        if (confirm('هل أنت متأكد من إعادة تعيين جميع الاختبارات؟')) {
            location.reload();
        }
    });
});

// CSS للمختبر الديناميكي
const labTestsCSS = `
<style>
.lab-test-card {
    transition: all 0.3s ease;
    border-width: 2px !important;
}

.lab-test-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.lab-test-card.border-success {
    border-color: #28a745 !important;
    background: linear-gradient(135deg, #ffffff 0%, #f8fff8 100%);
}

.lab-test-card.border-danger {
    border-color: #dc3545 !important;
    background: linear-gradient(135deg, #ffffff 0%, #fff8f8 100%);
}

.lab-test-card.border-warning {
    border-color: #ffc107 !important;
    background: linear-gradient(135deg, #ffffff 0%, #fffef8 100%);
}

.test-value {
    text-align: center;
    font-weight: bold;
}

.form-check-input:checked {
    transform: scale(1.1);
}

.file-upload-section .current-file {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
    .lab-test-card {
        margin-bottom: 1rem;
    }
}
</style>
`;

// إضافة CSS إلى الصفحة
document.head.insertAdjacentHTML('beforeend', labTestsCSS);


</script>
@endsection 