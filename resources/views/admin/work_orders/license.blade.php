@extends('layouts.app')

@section('content')
<style>
    .license-form {
        background: #ffffff;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .license-form .card-header {
        background: linear-gradient(135deg, #2c3e50, #3498db);
        border-radius: 15px 15px 0 0;
        padding: 1.5rem;
    }

    .license-form .card-header h3 {
        color: #ffffff;
        font-weight: 600;
        margin: 0;
    }

    .license-form .card-body {
        padding: 2rem;
    }

    .section-title {
        color: #2c3e50;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #3498db;
    }

    .form-section {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.25rem;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .form-section:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .form-label {
        color: #2c3e50;
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        font-size: 0.95rem;
    }

    .form-label i {
        margin-left: 0.5rem;
        font-size: 1rem;
    }

    .form-control {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 0.6rem 0.75rem;
        transition: all 0.3s ease;
        background-color: #fff;
        font-size: 0.95rem;
        height: auto;
    }

    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.15);
    }

    .form-check {
        margin-bottom: 0.4rem;
        padding-right: 1.5rem;
    }

    .form-check-input {
        cursor: pointer;
        width: 1.1rem;
        height: 1.1rem;
        margin-top: 0.2rem;
    }

    .form-check-label {
        cursor: pointer;
        color: #2c3e50;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .btn-info {
        background: linear-gradient(135deg, #00b894, #00a884);
        border: none;
        padding: 0.4rem 0.75rem;
    }

    .btn-info:hover {
        background: linear-gradient(135deg, #00a884, #009874);
        transform: translateY(-1px);
    }

    .file-upload-container {
        border: 2px dashed #e0e0e0;
        border-radius: 10px;
        padding: 1.25rem;
        text-align: center;
        transition: all 0.3s ease;
        background-color: #fff;
    }

    .file-upload-container:hover {
        border-color: #3498db;
        background: #f8f9fa;
    }

    .test-section {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 0.75rem;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }

    .test-section:hover {
        border-color: #3498db;
        box-shadow: 0 2px 8px rgba(52, 152, 219, 0.1);
    }

    .submit-section {
        text-align: center;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e0e0e0;
    }

    .submit-section .btn {
        min-width: 180px;
        padding: 0.6rem 1.5rem;
        font-size: 1rem;
    }

    /* تحسينات للجدول */
    .license-table {
        background: #ffffff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .license-table .card-header {
        background: linear-gradient(135deg, #2c3e50, #3498db);
        padding: 1.5rem;
    }

    .license-table .table {
        margin-bottom: 0;
    }

    .license-table .table th {
        background: #f8f9fa;
        color: #2c3e50;
        font-weight: 600;
        border-bottom: 2px solid #3498db;
    }

    .license-table .table td {
        vertical-align: middle;
        color: #2c3e50;
    }

    .license-table .table tr:hover {
        background-color: #f8f9fa;
    }

    .license-table .btn-info {
        padding: 0.5rem 1rem;
    }

    /* تحسينات جديدة للتصميم الاقتصادي */
    .multi-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
    }

    .compact-section {
        margin-bottom: 0.5rem;
    }

    .compact-section .row {
        margin-bottom: 0.5rem;
    }

    /* تحسين تنسيق الأزرار */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .license-files-section .file-upload-container {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        transition: all 0.3s ease;
    }

    .license-files-section .file-upload-container:hover {
        border-color: #3498db;
        background: #fff;
        box-shadow: 0 0 15px rgba(52, 152, 219, 0.1);
    }

    .license-dates-section .card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .license-dates-section .card-body {
        padding: 1.25rem;
    }

    .license-status-section .alert {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
    }

    .license-status-section .alert i {
        font-size: 1.2rem;
        margin-left: 0.75rem;
    }

    .license-status-section .alert strong {
        display: block;
        margin-bottom: 0.2rem;
        font-size: 1rem;
    }

    .license-status-section .alert p {
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    .date-input-container {
        background: #fff;
        border-radius: 8px;
        padding: 0.75rem;
        margin-top: 0.5rem;
        border: 1px solid #e0e0e0;
    }

    .restriction-details {
        background: #fff;
        border-radius: 8px;
        padding: 0.75rem;
        margin-top: 0.75rem;
        border: 1px solid #e0e0e0;
    }

    .extension-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-top: 1rem;
    }

    .extension-section h5 {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    /* تحسين المسافات في الشبكة */
    .row {
        margin-right: -0.75rem;
        margin-left: -0.75rem;
    }

    .col-md-4, .col-md-6 {
        padding-right: 0.75rem;
        padding-left: 0.75rem;
    }

    .g-3 {
        --bs-gutter-x: 0.75rem;
        --bs-gutter-y: 0.75rem;
    }

    .g-4 {
        --bs-gutter-x: 1rem;
        --bs-gutter-y: 1rem;
    }
</style>

<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card license-form">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">الرخص - أمر العمل #{{ $workOrder->id }}</h3>
                        <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-right"></i> عودة
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.work-orders.update-license', $workOrder) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- شهادات التنسيق -->
                        <div class="form-section mb-4">
                            <h4 class="section-title">شهادات التنسيق</h4>
                            <div class="file-upload-container">
                                <label for="coordination_certificate" class="form-label">رفع شهادات التنسيق</label>
                                <input type="file" class="form-control" id="coordination_certificate" name="coordination_certificate">
                                @if($workOrder->licenses->first()?->coordination_certificate_path)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $workOrder->licenses->first()->coordination_certificate_path) }}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> عرض الشهادة
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-4">
                            <!-- الحظر -->
                            <div class="col-md-6">
                                <div class="form-section h-100">
                                    <h4 class="section-title">الحظر</h4>
                                    <div class="compact-section">
                                        <label class="form-label">هل يوجد حظر؟</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="has_restriction" value="1" {{ $workOrder->licenses->first()?->has_restriction ? 'checked' : '' }}>
                                            <label class="form-check-label">نعم</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="has_restriction" value="0" {{ !$workOrder->licenses->first()?->has_restriction ? 'checked' : '' }}>
                                            <label class="form-check-label">لا</label>
                                        </div>
                                        <div class="restriction-details mt-3" style="display: {{ $workOrder->licenses->first()?->has_restriction ? 'block' : 'none' }};">
                                            <div class="mb-3">
                                                <label for="restriction_authority" class="form-label">جهة الحظر</label>
                                                <input type="text" class="form-control" id="restriction_authority" name="restriction_authority" value="{{ $workOrder->licenses->first()?->restriction_authority }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="restriction_reason" class="form-label">سبب الحظر</label>
                                                <textarea class="form-control" id="restriction_reason" name="restriction_reason" rows="2">{{ $workOrder->licenses->first()?->restriction_reason }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- الخطابات والتعهدات -->
                            <div class="col-md-6">
                                <div class="form-section h-100">
                                    <h4 class="section-title">الخطابات والتعهدات</h4>
                                    <div class="file-upload-container h-100">
                                        <label for="letters_and_commitments" class="form-label">رفع الخطابات والتعهدات</label>
                                        <input type="file" class="form-control" id="letters_and_commitments" name="letters_and_commitments" accept=".pdf,.jpg,.jpeg,.png">
                                        @if($workOrder->licenses->first()?->letters_and_commitments_path)
                                            <div class="mt-3">
                                                <a href="{{ asset('storage/' . $workOrder->licenses->first()->letters_and_commitments_path) }}" target="_blank" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> عرض المرفق
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- تفعيل الرخصة -->
                        <div class="form-section mb-4">
                            <h4 class="section-title mb-4">تفعيل الرخصة</h4>
                            <div class="license-files-section mb-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="file-upload-container h-100">
                                            <label for="license_1" class="form-label d-flex align-items-center mb-2">
                                                <i class="fas fa-file-alt ml-2 text-primary"></i>
                                                <span>الرخصة</span>
                                            </label>
                                            <input type="file" class="form-control" id="license_1" name="license_1" accept=".pdf,.jpg,.jpeg,.png">
                                            @if($workOrder->licenses->first()?->license_1_path)
                                                <div class="mt-2">
                                                    <a href="{{ asset('storage/' . $workOrder->licenses->first()->license_1_path) }}" target="_blank" class="btn btn-info w-100">
                                                        <i class="fas fa-eye ml-1"></i> عرض المرفق
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group h-100">
                                            <label for="license_length" class="form-label d-flex align-items-center mb-2">
                                                <i class="fas fa-ruler ml-2 text-primary"></i>
                                                <span>طول الرخصة (متر)</span>
                                            </label>
                                            <input type="number" class="form-control" id="license_length" name="license_length" value="{{ $workOrder->licenses->first()?->license_length }}" step="0.01" min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="license-dates-section">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <h5 class="card-title mb-4 d-flex align-items-center">
                                            <i class="fas fa-calendar-alt ml-2 text-primary"></i>
                                            <span>تواريخ الرخصة</span>
                                        </h5>
                                        <div class="row g-4">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="license_start_date" class="form-label d-flex align-items-center mb-2">
                                                        <i class="fas fa-calendar-plus ml-2 text-success"></i>
                                                        <span>تاريخ بداية الرخصة</span>
                                                    </label>
                                                    <input type="date" class="form-control" id="license_start_date" name="license_start_date" value="{{ $workOrder->licenses->first()?->license_start_date }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="license_end_date" class="form-label d-flex align-items-center mb-2">
                                                        <i class="fas fa-calendar-minus ml-2 text-danger"></i>
                                                        <span>تاريخ نهاية الرخصة</span>
                                                    </label>
                                                    <input type="date" class="form-control" id="license_end_date" name="license_end_date" value="{{ $workOrder->licenses->first()?->license_end_date }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="license_alert_days" class="form-label d-flex align-items-center mb-2">
                                                        <i class="fas fa-bell ml-2 text-warning"></i>
                                                        <span>أيام التنبيه قبل الانتهاء</span>
                                                    </label>
                                                    <input type="number" class="form-control" id="license_alert_days" name="license_alert_days" value="{{ $workOrder->licenses->first()?->license_alert_days ?? 30 }}" min="1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($workOrder->licenses->first()?->license_end_date)
                                <div class="license-status-section mt-4">
                                    @php
                                        $startDate = \Carbon\Carbon::parse($workOrder->licenses->first()->license_start_date);
                                        $endDate = \Carbon\Carbon::parse($workOrder->licenses->first()->license_end_date);
                                        $alertDays = $workOrder->licenses->first()->license_alert_days ?? 30;
                                        $daysUntilExpiry = $endDate->diffInDays(now());
                                        $isExpired = $endDate->isPast();
                                        $isNearExpiry = !$isExpired && $daysUntilExpiry <= $alertDays;
                                        $totalDuration = $startDate->diffInDays($endDate);
                                    @endphp
                                    
                                    <div class="row g-3">
                                        <div class="col-md-8">
                                            @if($isExpired)
                                                <div class="alert alert-danger d-flex align-items-center p-3">
                                                    <i class="fas fa-exclamation-triangle ml-2"></i>
                                                    <div>
                                                        <strong>الرخصة منتهية!</strong>
                                                        <p class="mb-0">منذ {{ $endDate->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                            @elseif($isNearExpiry)
                                                <div class="alert alert-warning d-flex align-items-center p-3">
                                                    <i class="fas fa-clock ml-2"></i>
                                                    <div>
                                                        <strong>تنبيه!</strong>
                                                        <p class="mb-0">الرخصة ستنتهي خلال {{ $daysUntilExpiry }} يوم</p>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="alert alert-success d-flex align-items-center p-3">
                                                    <i class="fas fa-check-circle ml-2"></i>
                                                    <div>
                                                        <strong>الرخصة صالحة</strong>
                                                        <p class="mb-0">متبقي {{ $daysUntilExpiry }} يوم</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <div class="alert alert-info d-flex align-items-center p-3 h-100">
                                                <i class="fas fa-calendar-alt ml-2"></i>
                                                <div>
                                                    <strong>مدة الرخصة</strong>
                                                    <p class="mb-0">{{ $totalDuration }} يوم</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- الاختبارات -->
                        <div class="form-section">
                            <h4 class="section-title">الاختبارات</h4>
                            <div class="multi-section">
                                <!-- اختبار العمق -->
                                <div class="test-section">
                                    <label class="form-label">اختبار العمق</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="has_depth_test" value="1" id="has_depth_test_yes" {{ $workOrder->licenses->first()?->has_depth_test ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_depth_test_yes">نعم</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="has_depth_test" value="0" id="has_depth_test_no" {{ !$workOrder->licenses->first()?->has_depth_test ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_depth_test_no">لا</label>
                                    </div>
                                    <div class="date-input-container" id="depth_test_date_container" style="display: {{ $workOrder->licenses->first()?->has_depth_test ? 'block' : 'none' }};">
                                        <input type="date" name="depth_test_date" class="form-control" value="{{ $workOrder->licenses->first()?->depth_test_date }}">
                                    </div>
                                </div>

                                <!-- اختبار دك التربة -->
                                <div class="test-section">
                                    <label class="form-label">اختبار دك التربة</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="has_soil_compaction_test" value="1" id="has_soil_compaction_test_yes" {{ $workOrder->licenses->first()?->has_soil_compaction_test ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_soil_compaction_test_yes">نعم</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="has_soil_compaction_test" value="0" id="has_soil_compaction_test_no" {{ !$workOrder->licenses->first()?->has_soil_compaction_test ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_soil_compaction_test_no">لا</label>
                                    </div>
                                    <div class="date-input-container" id="soil_compaction_test_date_container" style="display: {{ $workOrder->licenses->first()?->has_soil_compaction_test ? 'block' : 'none' }};">
                                        <input type="date" name="soil_compaction_test_date" class="form-control" value="{{ $workOrder->licenses->first()?->soil_compaction_test_date }}">
                                    </div>
                                </div>

                                <!-- اختبار RC1-MC1 -->
                                <div class="test-section">
                                    <label class="form-label">اختبار RC1-MC1</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="has_rc1_mc1_test" value="1" id="has_rc1_mc1_test_yes" {{ $workOrder->licenses->first()?->has_rc1_mc1_test ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_rc1_mc1_test_yes">نعم</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="has_rc1_mc1_test" value="0" id="has_rc1_mc1_test_no" {{ !$workOrder->licenses->first()?->has_rc1_mc1_test ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_rc1_mc1_test_no">لا</label>
                                    </div>
                                    <div class="date-input-container" id="rc1_mc1_test_date_container" style="display: {{ $workOrder->licenses->first()?->has_rc1_mc1_test ? 'block' : 'none' }};">
                                        <input type="date" name="rc1_mc1_test_date" class="form-control" value="{{ $workOrder->licenses->first()?->rc1_mc1_test_date }}">
                                    </div>
                                </div>

                                <!-- اختبار الأسفلت -->
                                <div class="test-section">
                                    <label class="form-label">اختبار الأسفلت</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="has_asphalt_test" value="1" id="has_asphalt_test_yes" {{ $workOrder->licenses->first()?->has_asphalt_test ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_asphalt_test_yes">نعم</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="has_asphalt_test" value="0" id="has_asphalt_test_no" {{ !$workOrder->licenses->first()?->has_asphalt_test ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_asphalt_test_no">لا</label>
                                    </div>
                                    <div class="date-input-container" id="asphalt_test_date_container" style="display: {{ $workOrder->licenses->first()?->has_asphalt_test ? 'block' : 'none' }};">
                                        <input type="date" name="asphalt_test_date" class="form-control" value="{{ $workOrder->licenses->first()?->asphalt_test_date }}">
                                    </div>
                                </div>

                                <!-- اختبار التربة -->
                                <div class="test-section">
                                    <label class="form-label">اختبار التربة</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="has_soil_test" value="1" id="has_soil_test_yes" {{ $workOrder->licenses->first()?->has_soil_test ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_soil_test_yes">نعم</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="has_soil_test" value="0" id="has_soil_test_no" {{ !$workOrder->licenses->first()?->has_soil_test ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_soil_test_no">لا</label>
                                    </div>
                                    <div class="date-input-container" id="soil_test_date_container" style="display: {{ $workOrder->licenses->first()?->has_soil_test ? 'block' : 'none' }};">
                                        <input type="date" name="soil_test_date" class="form-control" value="{{ $workOrder->licenses->first()?->soil_test_date }}">
                                    </div>
                                </div>

                                <!-- اختبار البلاط والانترلوك -->
                                <div class="test-section">
                                    <label class="form-label">اختبار البلاط والانترلوك</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="has_interlock_test" value="1" id="has_interlock_test_yes" {{ $workOrder->licenses->first()?->has_interlock_test ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_interlock_test_yes">نعم</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="has_interlock_test" value="0" id="has_interlock_test_no" {{ !$workOrder->licenses->first()?->has_interlock_test ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_interlock_test_no">لا</label>
                                    </div>
                                    <div class="date-input-container" id="interlock_test_date_container" style="display: {{ $workOrder->licenses->first()?->has_interlock_test ? 'block' : 'none' }};">
                                        <input type="date" name="interlock_test_date" class="form-control" value="{{ $workOrder->licenses->first()?->interlock_test_date }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="multi-section">
                        <!-- تمديد الرخصة -->
                        <div class="form-section">
                            <h4 class="section-title">تمديد الرخصة</h4>
                            <div class="file-upload-container">
                                <label class="form-label">رفع مرفق تمديد الرخصة</label>
                                <input type="file" name="license_extension_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                @if($workOrder->licenses->first()?->license_extension_file_path)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $workOrder->licenses->first()->license_extension_file_path) }}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> عرض المرفق
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="license_extension_start_date" class="form-label">تاريخ بداية التمديد</label>
                                        <input type="date" name="license_extension_start_date" class="form-control" id="license_extension_start_date" value="{{ $workOrder->licenses->first()?->license_extension_start_date }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="license_extension_end_date" class="form-label">تاريخ نهاية التمديد</label>
                                        <input type="date" name="license_extension_end_date" class="form-control" id="license_extension_end_date" value="{{ $workOrder->licenses->first()?->license_extension_end_date }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="license_extension_alert_days" class="form-label">أيام التنبيه قبل الانتهاء</label>
                                        <input type="number" name="license_extension_alert_days" class="form-control" id="license_extension_alert_days" value="{{ $workOrder->licenses->first()?->license_extension_alert_days ?? 30 }}" min="1">
                                    </div>
                                </div>
                            </div>
                            @if($workOrder->licenses->first()?->license_extension_end_date)
                                <div class="mt-2">
                                    @php
                                        $extensionEndDate = \Carbon\Carbon::parse($workOrder->licenses->first()->license_extension_end_date);
                                        $alertDays = $workOrder->licenses->first()->license_extension_alert_days ?? 30;
                                        $daysUntilExtensionExpiry = $extensionEndDate->diffInDays(now());
                                        $isExtensionExpired = $extensionEndDate->isPast();
                                        $isNearExtensionExpiry = !$isExtensionExpired && $daysUntilExtensionExpiry <= $alertDays;
                                    @endphp
                                    @if($isExtensionExpired)
                                        <div class="alert alert-danger">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            تمديد الرخصة منتهي منذ {{ $extensionEndDate->diffForHumans() }}
                                        </div>
                                    @elseif($isNearExtensionExpiry)
                                        <div class="alert alert-warning">
                                            <i class="fas fa-clock"></i>
                                            تمديد الرخصة سينتهي خلال {{ $daysUntilExtensionExpiry }} يوم
                                        </div>
                                    @else
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i>
                                            تمديد الرخصة صالح لمدة {{ $daysUntilExtensionExpiry }} يوم
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- تمديد الفاتورة -->
                        <div class="form-section">
                            <h4 class="section-title">تمديد الفاتورة</h4>
                            <div class="file-upload-container">
                                <label class="form-label">رفع مرفق تمديد الفاتورة</label>
                                <input type="file" name="invoice_extension_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                @if($workOrder->licenses->first()?->invoice_extension_file_path)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $workOrder->licenses->first()->invoice_extension_file_path) }}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> عرض المرفق
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="invoice_extension_start_date" class="form-label">تاريخ بداية التمديد</label>
                                        <input type="date" name="invoice_extension_start_date" class="form-control" id="invoice_extension_start_date" value="{{ $workOrder->licenses->first()?->invoice_extension_start_date }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="invoice_extension_end_date" class="form-label">تاريخ نهاية التمديد</label>
                                        <input type="date" name="invoice_extension_end_date" class="form-control" id="invoice_extension_end_date" value="{{ $workOrder->licenses->first()?->invoice_extension_end_date }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="invoice_extension_alert_days" class="form-label">أيام التنبيه قبل الانتهاء</label>
                                        <input type="number" name="invoice_extension_alert_days" class="form-control" id="invoice_extension_alert_days" value="{{ $workOrder->licenses->first()?->invoice_extension_alert_days ?? 30 }}" min="1">
                                    </div>
                                </div>
                            </div>
                            @if($workOrder->licenses->first()?->invoice_extension_end_date)
                                <div class="mt-2">
                                    @php
                                        $invoiceExtensionEndDate = \Carbon\Carbon::parse($workOrder->licenses->first()->invoice_extension_end_date);
                                        $alertDays = $workOrder->licenses->first()->invoice_extension_alert_days ?? 30;
                                        $daysUntilInvoiceExtensionExpiry = $invoiceExtensionEndDate->diffInDays(now());
                                        $isInvoiceExtensionExpired = $invoiceExtensionEndDate->isPast();
                                        $isNearInvoiceExtensionExpiry = !$isInvoiceExtensionExpired && $daysUntilInvoiceExtensionExpiry <= $alertDays;
                                    @endphp
                                    @if($isInvoiceExtensionExpired)
                                        <div class="alert alert-danger">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            تمديد الفاتورة منتهي منذ {{ $invoiceExtensionEndDate->diffForHumans() }}
                                        </div>
                                    @elseif($isNearInvoiceExtensionExpiry)
                                        <div class="alert alert-warning">
                                            <i class="fas fa-clock"></i>
                                            تمديد الفاتورة سينتهي خلال {{ $daysUntilInvoiceExtensionExpiry }} يوم
                                        </div>
                                    @else
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i>
                                            تمديد الفاتورة صالح لمدة {{ $daysUntilInvoiceExtensionExpiry }} يوم
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="multi-section">
                        <!-- نتائج الاختبار -->
                        <div class="form-section">
                            <h4 class="section-title">نتائج الاختبار</h4>
                            <div class="file-upload-container">
                                <label class="form-label">رفع مرفق نتائج الاختبار</label>
                                <input type="file" name="test_results_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                @if($workOrder->licenses->first()?->test_results_file_path)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $workOrder->licenses->first()->test_results_file_path) }}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> عرض المرفق
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- الفحص النهائي -->
                        <div class="form-section">
                            <h4 class="section-title">الفحص النهائي</h4>
                            <div class="file-upload-container">
                                <label class="form-label">رفع مرفق الفحص النهائي</label>
                                <input type="file" name="final_inspection_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                @if($workOrder->licenses->first()?->final_inspection_file_path)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $workOrder->licenses->first()->final_inspection_file_path) }}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> عرض المرفق
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- إخلاء وإغلاق الرخصة -->
                        <div class="form-section">
                            <h4 class="section-title">إخلاء وإغلاق الرخصة</h4>
                            <div class="file-upload-container">
                                <label class="form-label">رفع مرفق إخلاء وإغلاق الرخصة</label>
                                <input type="file" name="license_closure_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                @if($workOrder->licenses->first()?->license_closure_file_path)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $workOrder->licenses->first()->license_closure_file_path) }}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> عرض المرفق
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="submit-section mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save ml-1"></i> حفظ معلومات الرخصة
                        </button>
                        <a href="{{ route('admin.work-orders.licenses.data') }}" class="btn btn-info ms-2">
                            <i class="fas fa-eye ml-1"></i> عرض الرخص
                        </a>
                    </div>
                </form>

                <!-- عرض قسم البيانات المختصرة للرخصة -->
                <div class="alert alert-info mt-4 d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-info-circle me-2"></i>
                        <span>يمكنك عرض جميع بيانات الرخص في صفحة منفصلة</span>
                    </div>
                    <a href="{{ route('admin.work-orders.licenses.data') }}" class="btn btn-primary">
                        <i class="fas fa-table me-1"></i> عرض بيانات الرخص
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyCoordinates(coordinates) {
    navigator.clipboard.writeText(coordinates).then(() => {
        alert('تم نسخ الإحداثيات بنجاح');
    }).catch(err => {
        console.error('فشل نسخ الإحداثيات:', err);
    });
}

// Mostrar/ocultar sección de restricción según selección
document.addEventListener('DOMContentLoaded', function() {
    const restrictionRadios = document.querySelectorAll('input[name="has_restriction"]');
    const restrictionDetails = document.querySelector('.restriction-details');
    
    restrictionRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            restrictionDetails.style.display = this.value === '1' ? 'block' : 'none';
        });
    });
});
</script>
@endsection 