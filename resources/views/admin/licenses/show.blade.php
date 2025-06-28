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
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-ruler-vertical fa-2x text-primary mb-2"></i>
                                    <h6>اختبار العمق</h6>
                                    @if($license->has_depth_test)
                                        <span class="badge bg-success fs-6"><i class="fas fa-check me-1"></i>ناجح</span>
                                    @else
                                        <span class="badge bg-danger fs-6"><i class="fas fa-times me-1"></i>راسب</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-compress-arrows-alt fa-2x text-info mb-2"></i>
                                    <h6>اختبار دك التربة</h6>
                                    @if($license->has_soil_compaction_test)
                                        <span class="badge bg-success fs-6"><i class="fas fa-check me-1"></i>ناجح</span>
                                    @else
                                        <span class="badge bg-danger fs-6"><i class="fas fa-times me-1"></i>راسب</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-vial fa-2x text-warning mb-2"></i>
                                    <h6>اختبار RC1-MC1</h6>
                                    @if($license->has_rc1_mc1_test)
                                        <span class="badge bg-success fs-6"><i class="fas fa-check me-1"></i>ناجح</span>
                                    @else
                                        <span class="badge bg-danger fs-6"><i class="fas fa-times me-1"></i>راسب</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-road fa-2x text-danger mb-2"></i>
                                    <h6>اختبار الأسفلت</h6>
                                    @if($license->has_asphalt_test)
                                        <span class="badge bg-success fs-6"><i class="fas fa-check me-1"></i>ناجح</span>
                                    @else
                                        <span class="badge bg-danger fs-6"><i class="fas fa-times me-1"></i>راسب</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-seedling fa-2x text-success mb-2"></i>
                                    <h6>اختبار التربة</h6>
                                    @if($license->has_soil_test)
                                        <span class="badge bg-success fs-6"><i class="fas fa-check me-1"></i>ناجح</span>
                                    @else
                                        <span class="badge bg-danger fs-6"><i class="fas fa-times me-1"></i>راسب</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-th fa-2x text-primary mb-2"></i>
                                    <h6>اختبار البلاط المتداخل</h6>
                                    @if($license->has_interlock_test)
                                        <span class="badge bg-success fs-6"><i class="fas fa-check me-1"></i>ناجح</span>
                                    @else
                                        <span class="badge bg-danger fs-6"><i class="fas fa-times me-1"></i>راسب</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center h-100 border-info">
                                <div class="card-header bg-info text-white py-2">
                                    <h6 class="mb-0">إحصائيات الاختبارات</h6>
                                </div>
                                <div class="card-body">
                                    <i class="fas fa-chart-line fa-2x text-info mb-2"></i>
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
                                        $failedTestsCount = $totalTests - $passedTests;
                                        $successRate = $totalTests > 0 ? round(($passedTests / $totalTests) * 100, 1) : 0;
                                    @endphp
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <span class="badge bg-success fs-6 d-block mb-1">{{ $passedTests }}</span>
                                            <small class="text-muted">ناجح</small>
                                        </div>
                                        <div class="col-4">
                                            <span class="badge bg-danger fs-6 d-block mb-1">{{ $failedTestsCount }}</span>
                                            <small class="text-muted">راسب</small>
                                        </div>
                                        <div class="col-4">
                                            <span class="badge bg-info fs-6 d-block mb-1">{{ $successRate }}%</span>
                                            <small class="text-muted">معدل النجاح</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الاختبارات الراسبة -->
                    @php
                        $failedTestsList = [];
                        $labTests = [
                            'has_depth_test' => ['name' => 'اختبار العمق', 'icon' => 'fas fa-ruler-vertical', 'color' => 'primary'],
                            'has_soil_compaction_test' => ['name' => 'اختبار دك التربة', 'icon' => 'fas fa-compress-arrows-alt', 'color' => 'info'],
                            'has_rc1_mc1_test' => ['name' => 'اختبار RC1-MC1', 'icon' => 'fas fa-vial', 'color' => 'warning'],
                            'has_asphalt_test' => ['name' => 'اختبار الأسفلت', 'icon' => 'fas fa-road', 'color' => 'danger'],
                            'has_soil_test' => ['name' => 'اختبار التربة', 'icon' => 'fas fa-seedling', 'color' => 'success'],
                            'has_interlock_test' => ['name' => 'اختبار البلاط المتداخل', 'icon' => 'fas fa-th', 'color' => 'primary']
                        ];
                        
                        foreach($labTests as $field => $test) {
                            if(isset($license->$field) && !$license->$field) {
                                $failedTestsList[] = $test;
                            }
                        }
                    @endphp

                    @if(count($failedTestsList) > 0)
                    <div class="card mb-4 border-danger">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-times-circle me-2"></i>
                                الاختبارات الراسبة أو غير المكتملة
                                <span class="badge bg-light text-danger ms-2">{{ count($failedTestsList) }} اختبار</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($failedTestsList as $test)
                                <div class="col-md-4 mb-3">
                                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                                        <i class="{{ $test['icon'] }} fa-lg me-2"></i>
                                        <div>
                                            <strong>{{ $test['name'] }}</strong>
                                            <br><small>لم يتم اجتياز هذا الاختبار</small>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            @if($license->test_failure_reasons)
                            <div class="mt-3 p-3 bg-light rounded">
                                <h6 class="text-danger"><i class="fas fa-clipboard-list me-2"></i>أسباب الرسوب:</h6>
                                <p class="mb-0">{{ $license->test_failure_reasons }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- المرفقات المعملية -->
                    @php
                        $labAttachments = [
                            'depth_test_file_path' => ['name' => 'اختبار العمق', 'icon' => 'fas fa-ruler-vertical', 'color' => 'primary'],
                            'soil_compaction_test_file_path' => ['name' => 'اختبار دك التربة', 'icon' => 'fas fa-compress-arrows-alt', 'color' => 'info'],
                            'rc1_mc1_test_file_path' => ['name' => 'اختبار RC1-MC1', 'icon' => 'fas fa-vial', 'color' => 'warning'],
                            'asphalt_test_file_path' => ['name' => 'اختبار الأسفلت', 'icon' => 'fas fa-road', 'color' => 'danger'],
                            'soil_test_file_path' => ['name' => 'اختبار التربة', 'icon' => 'fas fa-seedling', 'color' => 'success'],
                            'interlock_test_file_path' => ['name' => 'اختبار البلاط المتداخل', 'icon' => 'fas fa-th', 'color' => 'primary'],
                            'max_dry_density_pro_test_file_path' => ['name' => 'اختبار الكثافة الجافة القصوى', 'icon' => 'fas fa-weight-hanging', 'color' => 'secondary'],
                            'asphalt_ratio_gradation_test_file_path' => ['name' => 'اختبار تدرج نسبة الأسفلت', 'icon' => 'fas fa-chart-bar', 'color' => 'info'],
                            'marshall_test_file_path' => ['name' => 'اختبار مارشال', 'icon' => 'fas fa-hammer', 'color' => 'dark'],
                            'concrete_molds_test_file_path' => ['name' => 'اختبار قوالب الخرسانة', 'icon' => 'fas fa-cube', 'color' => 'secondary'],
                            'excavation_bottom_test_file_path' => ['name' => 'اختبار قاع الحفر', 'icon' => 'fas fa-arrow-down', 'color' => 'warning'],
                            'protection_depth_test_file_path' => ['name' => 'اختبار عمق الحماية', 'icon' => 'fas fa-shield-alt', 'color' => 'success'],
                            'settlement_test_file_path' => ['name' => 'اختبار الهبوط', 'icon' => 'fas fa-level-down-alt', 'color' => 'danger'],
                            'concrete_temperature_test_file_path' => ['name' => 'اختبار درجة حرارة الخرسانة', 'icon' => 'fas fa-thermometer-half', 'color' => 'info'],
                            'field_density_atomic_test_file_path' => ['name' => 'اختبار الكثافة الحقلية الذرية', 'icon' => 'fas fa-atom', 'color' => 'primary'],
                            'moisture_content_test_file_path' => ['name' => 'اختبار المحتوى المائي', 'icon' => 'fas fa-tint', 'color' => 'info'],
                            'soil_layer_flatness_test_file_path' => ['name' => 'اختبار استواء طبقة التربة', 'icon' => 'fas fa-layer-group', 'color' => 'secondary'],
                            'concrete_sample_test_file_path' => ['name' => 'اختبار عينة الخرسانة', 'icon' => 'fas fa-flask', 'color' => 'warning'],
                            'asphalt_spray_rate_test_file_path' => ['name' => 'اختبار معدل رش الأسفلت', 'icon' => 'fas fa-spray-can', 'color' => 'danger'],
                            'asphalt_temperature_test_file_path' => ['name' => 'اختبار درجة حرارة الأسفلت', 'icon' => 'fas fa-temperature-high', 'color' => 'warning'],
                            'concrete_compression_test_file_path' => ['name' => 'اختبار ضغط الخرسانة', 'icon' => 'fas fa-compress', 'color' => 'dark'],
                            'soil_grain_analysis_test_file_path' => ['name' => 'اختبار تحليل حبيبات التربة', 'icon' => 'fas fa-microscope', 'color' => 'info'],
                            'liquidity_plasticity_test_file_path' => ['name' => 'اختبار حد السيولة والمرونة', 'icon' => 'fas fa-hand-paper', 'color' => 'primary'],
                            'proctor_test_file_path' => ['name' => 'اختبار بروكتور', 'icon' => 'fas fa-hammer', 'color' => 'secondary'],
                            'asphalt_layer_evenness_test_file_path' => ['name' => 'اختبار استواء طبقة الأسفلت', 'icon' => 'fas fa-level-up-alt', 'color' => 'warning'],
                            'asphalt_compaction_atomic_test_file_path' => ['name' => 'اختبار دك الأسفلت الذري', 'icon' => 'fas fa-atom', 'color' => 'danger'],
                            'bitumen_ratio_test_file_path' => ['name' => 'اختبار نسبة البيتومين', 'icon' => 'fas fa-percentage', 'color' => 'dark'],
                            'asphalt_gradation_test_file_path' => ['name' => 'اختبار تدرج الأسفلت', 'icon' => 'fas fa-sort-amount-up', 'color' => 'info'],
                            'asphalt_gmm_test_file_path' => ['name' => 'اختبار GMM للأسفلت', 'icon' => 'fas fa-weight', 'color' => 'primary'],
                            'marshall_density_test_file_path' => ['name' => 'اختبار كثافة مارشال', 'icon' => 'fas fa-weight-hanging', 'color' => 'secondary'],
                            'aggregate_ratio_test_file_path' => ['name' => 'اختبار نسبة الركام', 'icon' => 'fas fa-pebble', 'color' => 'warning'],
                            'stability_deficit_test_file_path' => ['name' => 'اختبار عجز الثبات', 'icon' => 'fas fa-balance-scale', 'color' => 'danger'],
                            'stability_degree_test_file_path' => ['name' => 'اختبار درجة الثبات', 'icon' => 'fas fa-certificate', 'color' => 'success'],
                            'backup_test_file_path' => ['name' => 'ملف اختبار احتياطي', 'icon' => 'fas fa-archive', 'color' => 'secondary']
                        ];
                        
                        $hasAttachments = false;
                        foreach($labAttachments as $field => $attachment) {
                            if($license->$field) {
                                $hasAttachments = true;
                                break;
                            }
                        }
                    @endphp

                    @if($hasAttachments)
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-paperclip me-2"></i>
                                المرفقات المعملية
                                <span class="badge bg-light text-info ms-2">الملفات المرفقة</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($labAttachments as $field => $attachment)
                                    @if($license->$field)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card border-{{ $attachment['color'] }}">
                                            <div class="card-header bg-{{ $attachment['color'] }} text-white py-2">
                                                <small class="fw-bold">
                                                    <i class="{{ $attachment['icon'] }} me-1"></i>
                                                    {{ $attachment['name'] }}
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

                    <!-- عرض شامل لكل اختبار مع حالته ومرفقاته -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-list-check me-2"></i>
                                تفاصيل الاختبارات المعملية والمرفقات
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $allTests = [
                                    'has_depth_test' => [
                                        'name' => 'اختبار العمق',
                                        'icon' => 'fas fa-ruler-vertical',
                                        'color' => 'primary',
                                        'file_field' => 'depth_test_file_path'
                                    ],
                                    'has_soil_compaction_test' => [
                                        'name' => 'اختبار دك التربة',
                                        'icon' => 'fas fa-compress-arrows-alt',
                                        'color' => 'info',
                                        'file_field' => 'soil_compaction_test_file_path'
                                    ],
                                    'has_rc1_mc1_test' => [
                                        'name' => 'اختبار RC1-MC1',
                                        'icon' => 'fas fa-vial',
                                        'color' => 'warning',
                                        'file_field' => 'rc1_mc1_test_file_path'
                                    ],
                                    'has_asphalt_test' => [
                                        'name' => 'اختبار الأسفلت',
                                        'icon' => 'fas fa-road',
                                        'color' => 'danger',
                                        'file_field' => 'asphalt_test_file_path'
                                    ],
                                    'has_soil_test' => [
                                        'name' => 'اختبار التربة',
                                        'icon' => 'fas fa-seedling',
                                        'color' => 'success',
                                        'file_field' => 'soil_test_file_path'
                                    ],
                                    'has_interlock_test' => [
                                        'name' => 'اختبار البلاط المتداخل',
                                        'icon' => 'fas fa-th',
                                        'color' => 'secondary',
                                        'file_field' => 'interlock_test_file_path'
                                    ]
                                ];
                                
                                // اختبارات إضافية متقدمة
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
                                    ],
                                    'field_density_atomic_test_file_path' => [
                                        'name' => 'اختبار الكثافة الحقلية الذرية',
                                        'icon' => 'fas fa-atom',
                                        'color' => 'primary'
                                    ],
                                    'moisture_content_test_file_path' => [
                                        'name' => 'اختبار المحتوى المائي',
                                        'icon' => 'fas fa-tint',
                                        'color' => 'info'
                                    ],
                                    'soil_layer_flatness_test_file_path' => [
                                        'name' => 'اختبار استواء طبقة التربة',
                                        'icon' => 'fas fa-layer-group',
                                        'color' => 'secondary'
                                    ],
                                    'concrete_sample_test_file_path' => [
                                        'name' => 'اختبار عينة الخرسانة',
                                        'icon' => 'fas fa-flask',
                                        'color' => 'warning'
                                    ],
                                    'asphalt_spray_rate_test_file_path' => [
                                        'name' => 'اختبار معدل رش الأسفلت',
                                        'icon' => 'fas fa-spray-can',
                                        'color' => 'danger'
                                    ],
                                    'asphalt_temperature_test_file_path' => [
                                        'name' => 'اختبار درجة حرارة الأسفلت',
                                        'icon' => 'fas fa-temperature-high',
                                        'color' => 'warning'
                                    ],
                                    'concrete_compression_test_file_path' => [
                                        'name' => 'اختبار ضغط الخرسانة',
                                        'icon' => 'fas fa-compress',
                                        'color' => 'dark'
                                    ],
                                    'soil_grain_analysis_test_file_path' => [
                                        'name' => 'اختبار تحليل حبيبات التربة',
                                        'icon' => 'fas fa-microscope',
                                        'color' => 'info'
                                    ],
                                    'liquidity_plasticity_test_file_path' => [
                                        'name' => 'اختبار حد السيولة والمرونة',
                                        'icon' => 'fas fa-hand-paper',
                                        'color' => 'primary'
                                    ],
                                    'proctor_test_file_path' => [
                                        'name' => 'اختبار بروكتور',
                                        'icon' => 'fas fa-hammer',
                                        'color' => 'secondary'
                                    ],
                                    'asphalt_layer_evenness_test_file_path' => [
                                        'name' => 'اختبار استواء طبقة الأسفلت',
                                        'icon' => 'fas fa-level-up-alt',
                                        'color' => 'warning'
                                    ],
                                    'asphalt_compaction_atomic_test_file_path' => [
                                        'name' => 'اختبار دك الأسفلت الذري',
                                        'icon' => 'fas fa-atom',
                                        'color' => 'danger'
                                    ],
                                    'bitumen_ratio_test_file_path' => [
                                        'name' => 'اختبار نسبة البيتومين',
                                        'icon' => 'fas fa-percentage',
                                        'color' => 'dark'
                                    ],
                                    'asphalt_gradation_test_file_path' => [
                                        'name' => 'اختبار تدرج الأسفلت',
                                        'icon' => 'fas fa-sort-amount-up',
                                        'color' => 'info'
                                    ],
                                    'asphalt_gmm_test_file_path' => [
                                        'name' => 'اختبار GMM للأسفلت',
                                        'icon' => 'fas fa-weight',
                                        'color' => 'primary'
                                    ],
                                    'marshall_density_test_file_path' => [
                                        'name' => 'اختبار كثافة مارشال',
                                        'icon' => 'fas fa-weight-hanging',
                                        'color' => 'secondary'
                                    ],
                                    'aggregate_ratio_test_file_path' => [
                                        'name' => 'اختبار نسبة الركام',
                                        'icon' => 'fas fa-pebble',
                                        'color' => 'warning'
                                    ],
                                    'stability_deficit_test_file_path' => [
                                        'name' => 'اختبار عجز الثبات',
                                        'icon' => 'fas fa-balance-scale',
                                        'color' => 'danger'
                                    ],
                                    'stability_degree_test_file_path' => [
                                        'name' => 'اختبار درجة الثبات',
                                        'icon' => 'fas fa-certificate',
                                        'color' => 'success'
                                    ],
                                    'backup_test_file_path' => [
                                        'name' => 'ملف اختبار احتياطي',
                                        'icon' => 'fas fa-archive',
                                        'color' => 'secondary'
                                    ]
                                ];
                            @endphp

                            <!-- الاختبارات الأساسية -->
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-flask me-2"></i>الاختبارات الأساسية
                            </h6>
                            <div class="row">
                                @foreach($allTests as $testField => $test)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100 border-{{ $test['color'] }}">
                                        <div class="card-header bg-{{ $test['color'] }} text-white py-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="fw-bold">
                                                    <i class="{{ $test['icon'] }} me-1"></i>
                                                    {{ $test['name'] }}
                                                </small>
                                                <div>
                                                    @if(isset($license->$testField) && $license->$testField)
                                                        <span class="badge bg-light text-success">
                                                            <i class="fas fa-check-circle"></i> ناجح
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light text-danger">
                                                            <i class="fas fa-times-circle"></i> راسب
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body py-2">
                                            <!-- حالة الاختبار -->
                                            <div class="mb-2">
                                                @if(isset($license->$testField) && $license->$testField)
                                                    <div class="alert alert-success py-1 mb-2">
                                                        <small><i class="fas fa-check me-1"></i>تم اجتياز هذا الاختبار بنجاح</small>
                                                    </div>
                                                @else
                                                    <div class="alert alert-danger py-1 mb-2">
                                                        <small><i class="fas fa-times me-1"></i>لم يتم اجتياز هذا الاختبار</small>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- المرفقات -->
                                            @if(isset($test['file_field']) && $license->{$test['file_field']})
                                                <div class="border-top pt-2">
                                                    <small class="text-muted d-block mb-1">
                                                        <i class="fas fa-paperclip me-1"></i>المرفقات:
                                                    </small>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-file-alt me-1"></i>ملف مرفق
                                                        </span>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="{{ Storage::url($license->{$test['file_field']}) }}" 
                                                               class="btn btn-outline-primary btn-sm" 
                                                               target="_blank" 
                                                               title="عرض الملف">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ Storage::url($license->{$test['file_field']}) }}" 
                                                               class="btn btn-outline-success btn-sm" 
                                                               download 
                                                               title="تحميل الملف">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="border-top pt-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-file-circle-xmark me-1"></i>لا توجد مرفقات
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- الاختبارات المتقدمة -->
                            @php
                                $hasAdvancedTests = false;
                                foreach($advancedTests as $field => $test) {
                                    if($license->$field) {
                                        $hasAdvancedTests = true;
                                        break;
                                    }
                                }
                            @endphp

                            @if($hasAdvancedTests)
                            <h6 class="text-secondary border-bottom pb-2 mb-3 mt-4">
                                <i class="fas fa-microscope me-2"></i>الاختبارات المتقدمة والإضافية
                            </h6>
                            <div class="row">
                                @foreach($advancedTests as $field => $test)
                                    @if($license->$field)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card h-100 border-{{ $test['color'] }}">
                                            <div class="card-header bg-{{ $test['color'] }} text-white py-2">
                                                <small class="fw-bold">
                                                    <i class="{{ $test['icon'] }} me-1"></i>
                                                    {{ $test['name'] }}
                                                </small>
                                            </div>
                                            <div class="card-body py-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-file-alt me-1"></i>ملف مرفق
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
                            @endif

                            <!-- ملخص شامل -->
                            <div class="card bg-light mt-4">
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <h5 class="text-success mb-1">{{ $passedTests }}</h5>
                                            <small class="text-muted">اختبارات ناجحة</small>
                                        </div>
                                        <div class="col-md-3">
                                            <h5 class="text-danger mb-1">{{ $failedTestsCount }}</h5>
                                            <small class="text-muted">اختبارات راسبة</small>
                                        </div>
                                        <div class="col-md-3">
                                            <h5 class="text-info mb-1">{{ $successRate }}%</h5>
                                            <small class="text-muted">معدل النجاح</small>
                                        </div>
                                        <div class="col-md-3">
                                            @php
                                                $totalAttachments = 0;
                                                foreach($allTests as $test) {
                                                    if(isset($test['file_field']) && $license->{$test['file_field']}) {
                                                        $totalAttachments++;
                                                    }
                                                }
                                                foreach($advancedTests as $field => $test) {
                                                    if($license->$field) {
                                                        $totalAttachments++;
                                                    }
                                                }
                                            @endphp
                                            <h5 class="text-primary mb-1">{{ $totalAttachments }}</h5>
                                            <small class="text-muted">مرفقات معملية</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- جدول الفسح ونوع الشارع - قابل للتحرير -->
                    <div class="card mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-table me-2"></i>جدول الفسح ونوع الشارع</h6>
                            <div>
                                <button type="button" class="btn btn-success btn-sm me-2" onclick="addLabTable1Row()">
                                    <i class="fas fa-plus me-1"></i>إضافة صف
                                </button>
                                <button type="button" class="btn btn-primary btn-sm" onclick="saveLabTable1Data()">
                                    <i class="fas fa-save me-1"></i>حفظ الجدول
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="labTable1">
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
                                            <th>إجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $table1Data = is_string($license->lab_table1_data) ? json_decode($license->lab_table1_data, true) : $license->lab_table1_data; @endphp
                                        @if($table1Data && is_array($table1Data))
                                            @foreach($table1Data as $index => $row)
                                            <tr>
                                                <td><input type="text" class="form-control form-control-sm" name="clearance_number" value="{{ $row['clearance_number'] ?? '' }}"></td>
                                                <td><input type="date" class="form-control form-control-sm" name="clearance_date" value="{{ $row['clearance_date'] ?? '' }}"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm" name="clearance_length" value="{{ $row['clearance_length'] ?? '' }}"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm" name="lab_length" value="{{ $row['lab_length'] ?? '' }}"></td>
                                                <td><input type="text" class="form-control form-control-sm" name="street_type" value="{{ $row['street_type'] ?? '' }}"></td>
                                                <td><input type="checkbox" class="form-check-input" name="is_dirt" {{ isset($row['is_dirt']) && $row['is_dirt'] ? 'checked' : '' }}></td>
                                                <td><input type="checkbox" class="form-check-input" name="is_asphalt" {{ isset($row['is_asphalt']) && $row['is_asphalt'] ? 'checked' : '' }}></td>
                                                <td><input type="checkbox" class="form-check-input" name="is_tile" {{ isset($row['is_tile']) && $row['is_tile'] ? 'checked' : '' }}></td>
                                                <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteTableRow(this)"><i class="fas fa-trash"></i></button></td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr id="no-lab-table1-data">
                                                <td colspan="9" class="text-center">لا توجد بيانات - اضغط "إضافة صف" لإضافة بيانات جديدة</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- جدول التفاصيل الفنية للمختبر - قابل للتحرير -->
                    <div class="card">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-table me-2"></i>جدول التفاصيل الفنية للمختبر</h6>
                            <div>
                                <button type="button" class="btn btn-success btn-sm me-2" onclick="addLabTable2Row()">
                                    <i class="fas fa-plus me-1"></i>إضافة صف
                                </button>
                                <button type="button" class="btn btn-primary btn-sm" onclick="saveLabTable2Data()">
                                    <i class="fas fa-save me-1"></i>حفظ الجدول
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="labTable2">
                                    <thead class="table-success">
                                        <tr>
                                            <th>السنة</th>
                                            <th>نوع العمل</th>
                                            <th>العمق</th>
                                            <th>دك التربة</th>
                                            <th>MC1-RC2</th>
                                            <th>دك أسفلت</th>
                                            <th>ترابي</th>
                                            <th>إجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $table2Data = is_string($license->lab_table2_data) ? json_decode($license->lab_table2_data, true) : $license->lab_table2_data; @endphp
                                        @if($table2Data && is_array($table2Data))
                                            @foreach($table2Data as $index => $row)
                                            <tr>
                                                <td><input type="number" class="form-control form-control-sm" name="year" value="{{ $row['year'] ?? '' }}"></td>
                                                <td><input type="text" class="form-control form-control-sm" name="work_type" value="{{ $row['work_type'] ?? '' }}"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm" name="depth" value="{{ $row['depth'] ?? '' }}"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm" name="soil_compaction" value="{{ $row['soil_compaction'] ?? '' }}"></td>
                                                <td><input type="text" class="form-control form-control-sm" name="mc1rc2" value="{{ $row['mc1rc2'] ?? '' }}"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm" name="asphalt_compaction" value="{{ $row['asphalt_compaction'] ?? '' }}"></td>
                                                <td><input type="checkbox" class="form-check-input" name="is_dirt" {{ isset($row['is_dirt']) && $row['is_dirt'] ? 'checked' : '' }}></td>
                                                <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteTableRow(this)"><i class="fas fa-trash"></i></button></td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr id="no-lab-table2-data">
                                                <td colspan="8" class="text-center">لا توجد بيانات - اضغط "إضافة صف" لإضافة بيانات جديدة</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

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

                    <!-- جدول التفاصيل الفنية للإخلاءات - قابل للتحرير -->
                    <div class="card mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-table me-2"></i>جدول التفاصيل الفنية للإخلاءات</h6>
                            <div>
                                <button type="button" class="btn btn-success btn-sm me-2" onclick="addEvacTable2Row()">
                                    <i class="fas fa-plus me-1"></i>إضافة صف
                                </button>
                                <button type="button" class="btn btn-primary btn-sm" onclick="saveEvacTable2Data()">
                                    <i class="fas fa-save me-1"></i>حفظ الجدول
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="evacTable2">
                                    <thead class="table-warning">
                                        <tr>
                                            <th>السنة</th>
                                            <th>نوع العمل</th>
                                            <th>العمق</th>
                                            <th>دك التربة</th>
                                            <th>MC1-RC2</th>
                                            <th>دك أسفلت</th>
                                            <th>فحص التربة</th>
                                            <th>إجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $evacTable2Data = is_string($license->evac_table2_data) ? json_decode($license->evac_table2_data, true) : $license->evac_table2_data; @endphp
                                        @if($evacTable2Data && is_array($evacTable2Data))
                                            @foreach($evacTable2Data as $index => $row)
                                            <tr>
                                                <td><input type="number" class="form-control form-control-sm" name="year" value="{{ $row['year'] ?? '' }}"></td>
                                                <td><input type="text" class="form-control form-control-sm" name="work_type" value="{{ $row['work_type'] ?? '' }}"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm" name="depth" value="{{ $row['depth'] ?? '' }}"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm" name="soil_compaction" value="{{ $row['soil_compaction'] ?? '' }}"></td>
                                                <td><input type="text" class="form-control form-control-sm" name="mc1_test" value="{{ $row['mc1_test'] ?? '' }}"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm" name="asphalt_test" value="{{ $row['asphalt_test'] ?? '' }}"></td>
                                                <td><input type="text" class="form-control form-control-sm" name="soil_test" value="{{ $row['soil_test'] ?? '' }}"></td>
                                                <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteTableRow(this)"><i class="fas fa-trash"></i></button></td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr id="no-evac-table2-data">
                                                <td colspan="8" class="text-center">لا توجد بيانات - اضغط "إضافة صف" لإضافة بيانات جديدة</td>
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

// ==================== دوال إدارة الجداول ====================

// دالة إضافة صف جديد لجدول الفسح ونوع الشارع للمختبر
function addLabTable1Row() {
    const tbody = document.querySelector('#labTable1 tbody');
    const noDataRow = document.getElementById('no-lab-table1-data');
    
    if (noDataRow) {
        noDataRow.remove();
    }
    
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td><input type="text" class="form-control form-control-sm" name="clearance_number" placeholder="رقم الفسح"></td>
        <td><input type="date" class="form-control form-control-sm" name="clearance_date"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="clearance_length" placeholder="طول الفسح"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="lab_length" placeholder="طول المختبر"></td>
        <td><input type="text" class="form-control form-control-sm" name="street_type" placeholder="نوع الشارع"></td>
        <td><input type="checkbox" class="form-check-input" name="is_dirt"></td>
        <td><input type="checkbox" class="form-check-input" name="is_asphalt"></td>
        <td><input type="checkbox" class="form-check-input" name="is_tile"></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteTableRow(this)"><i class="fas fa-trash"></i></button></td>
    `;
    tbody.appendChild(newRow);
}

// دالة إضافة صف جديد لجدول التفاصيل الفنية للمختبر
function addLabTable2Row() {
    const tbody = document.querySelector('#labTable2 tbody');
    const noDataRow = document.getElementById('no-lab-table2-data');
    
    if (noDataRow) {
        noDataRow.remove();
    }
    
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td><input type="number" class="form-control form-control-sm" name="year" placeholder="السنة"></td>
        <td><input type="text" class="form-control form-control-sm" name="work_type" placeholder="نوع العمل"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="depth" placeholder="العمق"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="soil_compaction" placeholder="دك التربة"></td>
        <td><input type="text" class="form-control form-control-sm" name="mc1rc2" placeholder="MC1-RC2"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="asphalt_compaction" placeholder="دك أسفلت"></td>
        <td><input type="checkbox" class="form-check-input" name="is_dirt"></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteTableRow(this)"><i class="fas fa-trash"></i></button></td>
    `;
    tbody.appendChild(newRow);
}

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
                case 'labTable1':
                    noDataId = 'no-lab-table1-data';
                    colSpan = 9;
                    break;
                case 'labTable2':
                    noDataId = 'no-lab-table2-data';
                    colSpan = 8;
                    break;
                case 'evacTable1':
                    noDataId = 'no-evac-table1-data';
                    colSpan = 9;
                    break;
                case 'evacTable2':
                    noDataId = 'no-evac-table2-data';
                    colSpan = 8;
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

// دالة حفظ بيانات جدول الفسح ونوع الشارع للمختبر
function saveLabTable1Data() {
    const rows = document.querySelectorAll('#labTable1 tbody tr:not(#no-lab-table1-data)');
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
            section: 'lab_table1',
            data: tableData
        },
        success: function(response) {
            toastr.success('تم حفظ جدول الفسح ونوع الشارع للمختبر بنجاح');
        },
        error: function(xhr) {
            console.error('خطأ في حفظ البيانات:', xhr);
            toastr.error('حدث خطأ أثناء حفظ البيانات');
        }
    });
}

// دالة حفظ بيانات جدول التفاصيل الفنية للمختبر
function saveLabTable2Data() {
    const rows = document.querySelectorAll('#labTable2 tbody tr:not(#no-lab-table2-data)');
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
</script>
@endsection 