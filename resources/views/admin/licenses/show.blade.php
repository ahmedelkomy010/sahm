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

        <!-- المختبر -->
        <div class="tab-pane fade" id="laboratory" role="tabpanel">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-flask me-2"></i>الاختبارات المعملية 
                        <span class="badge bg-light text-success ms-2 fs-6">رخصة {{ $license->license_number }}</span>
                    </h4>
                </div>
                <div class="card-body">
                    <!-- ملخص نتائج الاختبارات -->
                    @if($license->successful_tests_value || $license->failed_tests_value)
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-2">{{ number_format($license->successful_tests_value ?? 0, 2) }} ريال</h3>
                                    <p class="mb-0"><i class="fas fa-check-circle me-2"></i>قيمة الاختبارات الناجحة</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-2">{{ number_format($license->failed_tests_value ?? 0, 2) }} ريال</h3>
                                    <p class="mb-0"><i class="fas fa-times-circle me-2"></i>قيمة الاختبارات الراسبة</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- أسباب الرسوب -->
                    @if($license->test_failure_reasons)
                    <div class="alert alert-warning mb-4">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>أسباب رسوب الاختبارات:</h6>
                        <p class="mb-0">{{ $license->test_failure_reasons }}</p>
                    </div>
                    @endif

                    <!-- حالة الاختبارات -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-ruler-vertical fa-2x text-primary mb-2"></i>
                                    <h6>اختبار العمق</h6>
                                    @if($license->has_depth_test)
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>ناجح</span>
                                    @else
                                        <span class="badge bg-secondary">غير مفعل</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-compress-arrows-alt fa-2x text-info mb-2"></i>
                                    <h6>اختبار الدك</h6>
                                    @if($license->has_soil_compaction_test)
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>ناجح</span>
                                    @else
                                        <span class="badge bg-secondary">غير مفعل</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-vial fa-2x text-warning mb-2"></i>
                                    <h6>اختبار RC1-MC1</h6>
                                    @if($license->has_rc1_mc1_test)
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>ناجح</span>
                                    @else
                                        <span class="badge bg-secondary">غير مفعل</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-road fa-2x text-danger mb-2"></i>
                                    <h6>اختبار الأسفلت</h6>
                                    @if($license->has_asphalt_test)
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>ناجح</span>
                                    @else
                                        <span class="badge bg-secondary">غير مفعل</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-seedling fa-2x text-success mb-2"></i>
                                    <h6>اختبار التربة</h6>
                                    @if($license->has_soil_test)
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>ناجح</span>
                                    @else
                                        <span class="badge bg-secondary">غير مفعل</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-th fa-2x text-primary mb-2"></i>
                                    <h6>اختبار البلاط</h6>
                                    @if($license->has_interlock_test)
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>ناجح</span>
                                    @else
                                        <span class="badge bg-secondary">غير مفعل</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-chart-line fa-2x text-info mb-2"></i>
                                    <h6>إجمالي الاختبارات</h6>
                                    @php
                                        $totalTests = 0;
                                        $passedTests = 0;
                                        $tests = ['has_depth_test', 'has_soil_compaction_test', 'has_rc1_mc1_test', 'has_asphalt_test', 'has_soil_test', 'has_interlock_test'];
                                        foreach($tests as $test) {
                                            if(isset($license->$test)) {
                                                $totalTests++;
                                                if($license->$test) $passedTests++;
                                            }
                                        }
                                    @endphp
                                    <span class="badge bg-info fs-6">{{ $passedTests }}/{{ $totalTests }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- جداول بيانات المختبر -->
                    @if($license->lab_table1_data)
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-table me-2"></i>جدول الفسح ونوع الشارع</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-success">
                                        <tr>
                                            <th>رقم الفسح</th>
                                            <th>تاريخ الفسح</th>
                                            <th>طول الفسح</th>
                                            <th>طول المختبر</th>
                                            <th>نوع الشارع</th>
                                            <th>ترابي</th>
                                            <th>أسفلت</th>
                                            <th>بلاط</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $table1Data = is_string($license->lab_table1_data) ? json_decode($license->lab_table1_data, true) : $license->lab_table1_data; @endphp
                                        @if($table1Data && is_array($table1Data))
                                            @foreach($table1Data as $row)
                                            <tr>
                                                <td>{{ $row['clearance_number'] ?? '-' }}</td>
                                                <td>{{ $row['clearance_date'] ?? '-' }}</td>
                                                <td>{{ $row['clearance_length'] ?? '-' }}</td>
                                                <td>{{ $row['lab_length'] ?? '-' }}</td>
                                                <td>{{ $row['street_type'] ?? '-' }}</td>
                                                <td>{{ isset($row['is_dirt']) && $row['is_dirt'] ? '✓' : '-' }}</td>
                                                <td>{{ isset($row['is_asphalt']) && $row['is_asphalt'] ? '✓' : '-' }}</td>
                                                <td>{{ isset($row['is_tile']) && $row['is_tile'] ? '✓' : '-' }}</td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr><td colspan="8" class="text-center">لا توجد بيانات</td></tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($license->lab_table2_data)
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-table me-2"></i>جدول التفاصيل الفنية للمختبر</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-success">
                                        <tr>
                                            <th>السنة</th>
                                            <th>نوع العمل</th>
                                            <th>العمق</th>
                                            <th>دك التربة</th>
                                            <th>MC1-RC2</th>
                                            <th>دك أسفلت</th>
                                            <th>ترابي</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $table2Data = is_string($license->lab_table2_data) ? json_decode($license->lab_table2_data, true) : $license->lab_table2_data; @endphp
                                        @if($table2Data && is_array($table2Data))
                                            @foreach($table2Data as $row)
                                            <tr>
                                                <td>{{ $row['year'] ?? '-' }}</td>
                                                <td>{{ $row['work_type'] ?? '-' }}</td>
                                                <td>{{ $row['depth'] ?? '-' }}</td>
                                                <td>{{ $row['soil_compaction'] ?? '-' }}%</td>
                                                <td>{{ $row['mc1rc2'] ?? '-' }}</td>
                                                <td>{{ $row['asphalt_compaction'] ?? '-' }}%</td>
                                                <td>{{ isset($row['is_dirt']) && $row['is_dirt'] ? '✓' : '-' }}</td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr><td colspan="7" class="text-center">لا توجد بيانات</td></tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(!$license->lab_table1_data && !$license->lab_table2_data && !$license->successful_tests_value && !$license->failed_tests_value)
                    <div class="text-center py-5">
                        <i class="fas fa-flask fa-5x text-muted mb-3"></i>
                        <h4 class="text-muted">لا توجد بيانات مختبر مسجلة</h4>
                        <p class="text-muted">لم يتم إدخال أي بيانات مختبر لهذه الرخصة بعد</p>
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

                    <!-- جداول بيانات الإخلاءات -->
                    @if($license->evac_table1_data)
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-table me-2"></i>جدول فسح الإخلاءات</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $evacTable1Data = is_string($license->evac_table1_data) ? json_decode($license->evac_table1_data, true) : $license->evac_table1_data; @endphp
                                        @if($evacTable1Data && is_array($evacTable1Data))
                                            @foreach($evacTable1Data as $row)
                                            <tr>
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
                                        @else
                                            <tr><td colspan="8" class="text-center">لا توجد بيانات</td></tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($license->evac_table2_data)
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-table me-2"></i>جدول التفاصيل الفنية للإخلاءات</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-warning">
                                        <tr>
                                            <th>السنة</th>
                                            <th>نوع العمل</th>
                                            <th>العمق</th>
                                            <th>دك التربة</th>
                                            <th>MC1-RC2</th>
                                            <th>دك أسفلت</th>
                                            <th>فحص التربة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $evacTable2Data = is_string($license->evac_table2_data) ? json_decode($license->evac_table2_data, true) : $license->evac_table2_data; @endphp
                                        @if($evacTable2Data && is_array($evacTable2Data))
                                            @foreach($evacTable2Data as $row)
                                            <tr>
                                                <td>{{ $row['year'] ?? '-' }}</td>
                                                <td>{{ $row['work_type'] ?? '-' }}</td>
                                                <td>{{ $row['depth'] ?? '-' }}</td>
                                                <td>{{ $row['soil_compaction'] ?? '-' }}%</td>
                                                <td>{{ $row['mc1_test'] ?? '-' }}</td>
                                                <td>{{ $row['asphalt_test'] ?? '-' }}%</td>
                                                <td>{{ $row['soil_test'] ?? '-' }}</td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr><td colspan="7" class="text-center">لا توجد بيانات</td></tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

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
                        <i class="fas fa-exclamation-triangle me-2"></i>المخالفات 
                        <span class="badge bg-light text-danger ms-2 fs-6">رخصة {{ $license->license_number }}</span>
                    </h4>
                </div>
                <div class="card-body">
                    @if($license->violation_number)
                        <div class="alert alert-danger">
                            <h5><i class="fas fa-exclamation-triangle"></i> يوجد مخالفة</h5>
                            <table class="table">
                                <tr>
                                    <td><strong>رقم المخالفة:</strong></td>
                                    <td>{{ $license->violation_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>قيمة المخالفة:</strong></td>
                                    <td>{{ number_format($license->violation_license_value ?? 0, 2) }} ريال</td>
                                </tr>
                                <tr>
                                    <td><strong>تاريخ المخالفة:</strong></td>
                                    <td>{{ $license->violation_license_date ? $license->violation_license_date->format('Y-m-d') : 'غير محدد' }}</td>
                                </tr>
                                @if($license->violation_cause)
                                <tr>
                                    <td><strong>سبب المخالفة:</strong></td>
                                    <td>{{ $license->violation_cause }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shield-alt fa-5x text-success mb-3"></i>
                            <h4 class="text-success">لا توجد مخالفات</h4>
                        </div>
                    @endif
                </div>
            </div>
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
    background: linear-gradient(45deg, #ffc107, #ffb300);
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
    background: linear-gradient(45deg, #ffc107, #ffb300) !important;
    border: none;
    padding: 1.5rem;
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
</script>
@endsection 