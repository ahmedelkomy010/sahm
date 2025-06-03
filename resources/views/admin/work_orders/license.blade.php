@extends('layouts.app')

@section('content')
<!-- إضافة CSRF token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div id="app">
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-gradient-primary text-white py-3">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                            <h3 class="mb-0 fs-4 text-center text-md-start">
                                <i class="fas fa-plus-circle me-2"></i>
                                إنشاء رخصة جديدة - أمر العمل {{ $workOrder->order_number }}
                            </h3>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-back btn-sm">
                                    <i class="fas fa-arrow-right"></i> عودة لتفاصيل العمل
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-3 p-md-4">
                        <!-- أزرار التبويبات الرئيسية -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-2 justify-content-center">
                                    <button type="button" class="btn btn-outline-primary tab-btn" data-target="dig-license-section">
                                        <i class="fas fa-file-contract"></i>  رخص الحفر
                                    </button>
                                    <button type="button" class="btn btn-outline-success tab-btn" data-target="lab-section">
                                        <i class="fas fa-flask"></i> المختبر
                                    </button>
                                    <button type="button" class="btn btn-outline-warning tab-btn" data-target="evacuations-section">
                                        <i class="fas fa-truck-moving"></i> الإخلاءات
                                    </button>
                                    <button type="button" class="btn btn-outline-danger tab-btn" data-target="violations-section">
                                        <i class="fas fa-exclamation-triangle"></i> المخالفات
                                    </button>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('admin.licenses.store') }}" method="POST" enctype="multipart/form-data" id="licenseForm">
                            @csrf
                            <!-- إضافة حقل مخفي لـ work_order_id -->
                            <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                             <!-- شهادة التنسيق والمرفقات -->
                             <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-warning text-dark">
                                        <h4 class="mb-0 fs-5">
                                            <i class="fas fa-certificate me-2"></i>
                                            شهادة التنسيق والمرفقات
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">مرفق شهادة التنسيق</label>
                                                <input type="file" class="form-control" name="coordination_certificate_path" accept=".pdf,.jpg,.jpeg,.png">
                                                @if(isset($license) && $license->coordination_certificate_path)
                                                    <div class="mt-2">
                                                        <a href="#" class="btn btn-sm btn-outline-info">
                                                            <i class="fas fa-eye"></i> عرض الشهادة الحالية
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">ملاحظات شهادة التنسيق</label>
                                                <textarea class="form-control" name="coordination_certificate_notes" rows="3" 
                                                         placeholder=" ملاحظات شهادة التنسيق">{{ old('coordination_certificate_notes') }}</textarea>
                                            </div>
                                            
                                            <div class="col-12">
                                                <label class="form-label">مرفق الخطابات والتعهدات</label>
                                                <input type="file" class="form-control" name="letters_commitments_files[]" multiple accept=".pdf,.jpg,.jpeg,.png">
                                                @if(isset($license) && $license->letters_commitments_file_path)
                                                    <div class="mt-2">
                                                        <a href="#" class="btn btn-sm btn-outline-info">
                                                            <i class="fas fa-eye"></i> عرض الخطابات والتعهدات الحالية
                                                        </a>
                                                    </div>
                                                    
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <label for="has_restriction" class="form-label">يوجد حظر؟</label>
                                                <select class="form-select" name="has_restriction" id="has_restriction">
                                                    <option value="0" {{ old('has_restriction', 0) == 0 ? 'selected' : '' }}>لا</option>
                                                    <option value="1" {{ old('has_restriction', 0) == 1 ? 'selected' : '' }}>نعم</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6" id="restriction_authority_field" style="display: none;">
                                                <label for="restriction_authority" class="form-label">جهة الحظر</label>
                                                <input type="text" class="form-control" id="restriction_authority" name="restriction_authority" 
                                                       value="{{ old('restriction_authority') }}" 
                                                       placeholder="اسم الجهة المسؤولة عن الحظر">
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>

                            <!-- قسم رخص الحفر -->
                            <div id="dig-license-section" class="tab-section" style="display: none;">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h4 class="mb-0 fs-5">
                                            <i class="fas fa-file-contract me-2"></i>
                                            معلومات الرخصة الأساسية
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="license_number" class="form-label">رقم الرخصة</label>
                                                <input type="text" class="form-control" id="license_number" name="license_number" 
                                                       value="{{ old('license_number') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="license_date" class="form-label">تاريخ الرخصة</label>
                                                <input type="date" class="form-control" id="license_date" name="license_date" 
                                                       value="{{ old('license_date') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="license_type" class="form-label">نوع الرخصة</label>
                                                <select class="form-select" id="license_type" name="license_type">
                                                    <option value="">اختر نوع الرخصة</option>
                                                    <option value="مشروع" {{ old('license_type') == 'مشروع' ? 'selected' : '' }}>مشروع</option>
                                                    <option value="طوارئ" {{ old('license_type') == 'طوارئ' ? 'selected' : '' }}>طوارئ</option>
                                                    <option value="عادي" {{ old('license_type') == 'عادي' ? 'selected' : '' }}>عادي</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="license_value" class="form-label">قيمة الرخصة</label>
                                                <input type="number" step="0.01" class="form-control" id="license_value" name="license_value" 
                                                       value="{{ old('license_value') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="extension_value" class="form-label">قيمة التمديدات</label>
                                                <input type="number" step="0.01" class="form-control" id="extension_value" name="extension_value" 
                                                       value="{{ old('extension_value') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">طول الحفر</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" class="form-control" name="excavation_length"
                                                           value="{{ old('excavation_length') }}">
                                                    <span class="input-group-text">متر</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">عرض الحفر</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" class="form-control" name="excavation_width"
                                                           value="{{ old('excavation_width') }}">
                                                    <span class="input-group-text">متر</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">عمق الحفر</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" class="form-control" name="excavation_depth"
                                                           value="{{ old('excavation_depth') }}">
                                                    <span class="input-group-text">متر</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                               
                                <!-- تواريخ الرخصة مع عداد الأيام -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-info text-white">
                                        <h4 class="mb-0 fs-5">
                                            <i class="fas fa-calendar-alt me-2"></i>
                                            تواريخ الرخصة وعداد الأيام
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">تاريخ بداية الرخصة</label>
                                                <input type="date" class="form-control" name="license_start_date" id="license_start_date"
                                                       value="{{ old('license_start_date') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">تاريخ نهاية الرخصة</label>
                                                <input type="date" class="form-control" name="license_end_date" id="license_end_date"
                                                       value="{{ old('license_end_date') }}">
                                            </div>
                                            <div class="col-md-4 d-flex align-items-end">
                                                <div class="alert alert-info w-100 mb-0" id="license_days_counter">
                                                    <i class="fas fa-clock me-2"></i>
                                                    <span id="license_days_text">اختر التواريخ لحساب المدة</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">تاريخ بداية التمديد</label>
                                                <input type="date" class="form-control" name="extension_start_date" id="extension_start_date"
                                                       value="{{ old('extension_start_date') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">تاريخ نهاية التمديد</label>
                                                <input type="date" class="form-control" name="extension_end_date" id="extension_end_date"
                                                       value="{{ old('extension_end_date') }}">
                                            </div>
                                            <input type="hidden" name="license_alert_days" value="30">
                                            <div class="col-md-4 d-flex align-items-end">
                                                <div class="alert alert-warning w-100 mb-0" id="extension_days_counter">
                                                    <i class="fas fa-clock me-2"></i>
                                                    <span id="extension_days_text">اختر تواريخ التمديد</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- المرفقات -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-secondary text-white">
                                        <h4 class="mb-0 fs-5">
                                            <i class="fas fa-paperclip me-2"></i>
                                            المرفقات والوثائق
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">ملف الرخصة</label>
                                                <input type="file" class="form-control" name="license_file" accept=".pdf,.jpg,.jpeg,.png">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">فواتير السداد</label>
                                                <input type="file" class="form-control" name="payment_invoices[]" multiple>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">إثبات السداد</label>
                                                <input type="file" class="form-control" name="payment_proof[]" multiple>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">تفعيل الرخصة</label>
                                                <input type="file" class="form-control" name="license_activation[]" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- قسم المختبر -->
                            <div id="lab-section" class="tab-section" style="display: none;">
                                <!-- الاختبارات المطلوبة -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-success text-white">
                                        <h4 class="mb-0 fs-5">
                                            <i class="fas fa-flask me-2"></i>
                                            الاختبارات المطلوبة
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <!-- اختبار العمق -->
                                            <div class="col-md-4">
                                                <div class="card h-100 border-0 bg-light">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label class="form-label d-flex align-items-center">
                                                                <i class="fas fa-ruler-vertical text-primary me-2"></i>
                                                                اختبار العمق
                                                            </label>
                                                            <select class="form-select" name="has_depth_test">
                                                                <option value="0" {{ old('has_depth_test', 0) == 0 ? 'selected' : '' }}>لا</option>
                                                                <option value="1" {{ old('has_depth_test', 0) == 1 ? 'selected' : '' }}>نعم</option>
                                                            </select>
                                                        </div>
                                                        <div class="mt-2">
                                                            <input type="file" class="form-control form-control-sm" name="depth_test_file" accept=".pdf,.jpg,.jpeg,.png">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- اختبار دك التربة -->
                                            <div class="col-md-4">
                                                <div class="card h-100 border-0 bg-light">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label class="form-label d-flex align-items-center">
                                                                <i class="fas fa-layer-group text-primary me-2"></i>
                                                                اختبار دك التربة
                                                            </label>
                                                            <select class="form-select" name="has_soil_compaction_test">
                                                                <option value="0" {{ old('has_soil_compaction_test', 0) == 0 ? 'selected' : '' }}>لا</option>
                                                                <option value="1" {{ old('has_soil_compaction_test', 0) == 1 ? 'selected' : '' }}>نعم</option>
                                                            </select>
                                                        </div>
                                                        <div class="mt-2">
                                                            <input type="file" class="form-control form-control-sm" name="soil_compaction_file" accept=".pdf,.jpg,.jpeg,.png">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- اختبار MC1, RC2 -->
                                            <div class="col-md-4">
                                                <div class="card h-100 border-0 bg-light">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label class="form-label d-flex align-items-center">
                                                                <i class="fas fa-flask text-primary me-2"></i>
                                                                اختبار MC1, RC2
                                                            </label>
                                                            <select class="form-select" name="has_rc1_mc1_test">
                                                                <option value="0" {{ old('has_rc1_mc1_test', 0) == 0 ? 'selected' : '' }}>لا</option>
                                                                <option value="1" {{ old('has_rc1_mc1_test', 0) == 1 ? 'selected' : '' }}>نعم</option>
                                                            </select>
                                                        </div>
                                                        <div class="mt-2">
                                                            <input type="file" class="form-control form-control-sm" name="rc1_mc1_file" accept=".pdf,.jpg,.jpeg,.png">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- اختبار أسفلت -->
                                            <div class="col-md-4">
                                                <div class="card h-100 border-0 bg-light">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label class="form-label d-flex align-items-center">
                                                                <i class="fas fa-road text-primary me-2"></i>
                                                                اختبار أسفلت
                                                            </label>
                                                            <select class="form-select" name="has_asphalt_test">
                                                                <option value="0" {{ old('has_asphalt_test', 0) == 0 ? 'selected' : '' }}>لا</option>
                                                                <option value="1" {{ old('has_asphalt_test', 0) == 1 ? 'selected' : '' }}>نعم</option>
                                                            </select>
                                                        </div>
                                                        <div class="mt-2">
                                                            <input type="file" class="form-control form-control-sm" name="asphalt_test_file" accept=".pdf,.jpg,.jpeg,.png">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- اختبار تربة -->
                                            <div class="col-md-4">
                                                <div class="card h-100 border-0 bg-light">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label class="form-label d-flex align-items-center">
                                                                <i class="fas fa-mountain text-primary me-2"></i>
                                                                اختبار تربة
                                                            </label>
                                                            <select class="form-select" name="has_soil_test">
                                                                <option value="0" {{ old('has_soil_test', 0) == 0 ? 'selected' : '' }}>لا</option>
                                                                <option value="1" {{ old('has_soil_test', 0) == 1 ? 'selected' : '' }}>نعم</option>
                                                            </select>
                                                        </div>
                                                        <div class="mt-2">
                                                            <input type="file" class="form-control form-control-sm" name="soil_test_file" accept=".pdf,.jpg,.jpeg,.png">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- اختبار بلاط وانترلوك -->
                                            <div class="col-md-4">
                                                <div class="card h-100 border-0 bg-light">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label class="form-label d-flex align-items-center">
                                                                <i class="fas fa-th text-primary me-2"></i>
                                                                اختبار بلاط وانترلوك
                                                            </label>
                                                            <select class="form-select" name="has_interlock_test">
                                                                <option value="0" {{ old('has_interlock_test', 0) == 0 ? 'selected' : '' }}>لا</option>
                                                                <option value="1" {{ old('has_interlock_test', 0) == 1 ? 'selected' : '' }}>نعم</option>
                                                            </select>
                                                        </div>
                                                        <div class="mt-2">
                                                            <input type="file" class="form-control form-control-sm" name="interlock_test_file" accept=".pdf,.jpg,.jpeg,.png">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                            </div>

                            <!-- قسم الإخلاءات -->
                            <div id="evacuations-section" class="tab-section" style="display: none;">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-warning text-dark">
                                        <h4 class="mb-0 fs-5">
                                            <i class="fas fa-truck-moving me-2"></i>
                                            معلومات الإخلاءات
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">تم الإخلاء؟</label>
                                                <select class="form-select" name="is_evacuated" id="is_evacuated">
                                                    <option value="0" {{ old('is_evacuated', 0) == 0 ? 'selected' : '' }}>لا</option>
                                                    <option value="1" {{ old('is_evacuated', 0) == 1 ? 'selected' : '' }}>نعم</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">رقم الرخصة</label>
                                                <input type="text" class="form-control" name="evac_license_number"
                                                       value="{{ old('evac_license_number') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">قيمة الرخصة</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" class="form-control" name="evac_license_value"
                                                           value="{{ old('evac_license_value') }}">
                                                    <span class="input-group-text">ريال</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">رقم سداد الرخصة</label>
                                                <input type="text" class="form-control" name="evac_payment_number"
                                                       value="{{ old('evac_payment_number') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">تاريخ الإخلاء</label>
                                                <input type="date" class="form-control" name="evac_date"
                                                       value="{{ old('evac_date') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">مبلغ الإخلاء</label>
                                                <input type="number" step="0.01" class="form-control" name="evac_amount"
                                                       value="{{ old('evac_amount', $license->evac_amount ?? '') }}">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">مرفق الإخلاءات</label>
                                                <input type="file" class="form-control" name="evacuations_files[]" multiple accept=".pdf,.jpg,.jpeg,.png">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- جدول الفسح والاختبارات الأول -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                        <h4 class="mb-0 fs-5">
                                            <i class="fas fa-table me-2"></i>
                                            جدول الفسح والاختبارات الأول
                                        </h4>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-light btn-sm" onclick="addRowToTable1()">
                                                <i class="fas fa-plus"></i> إضافة صف
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover mb-0" id="labTable1">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th style="min-width: 100px;">رقم الفسح</th>
                                                        <th style="min-width: 120px;">تاريخ الفسح</th>
                                                        <th style="min-width: 120px;">الطول</th>
                                                        <th style="min-width: 120px;">نوع الشارع</th>
                                                        <th style="min-width: 80px;">ترابي</th>
                                                        <th style="min-width: 80px;">أسفلت</th>
                                                        <th style="min-width: 80px;">بلاط</th>
                                                        <th style="min-width: 80px;">تربة</th>
                                                        <th style="min-width: 80px;">MC1</th>
                                                        <th style="min-width: 80px;">أسفلت</th>
                                                        <th style="min-width: 150px;">ملاحظات</th>
                                                        <th style="min-width: 80px;">إجراءات</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="labTable1Body">
                                                    <!-- الجدول فارغ للإدخال الجديد -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- الجدول الثاني للمختبر -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                        <h4 class="mb-0 fs-5">
                                            <i class="fas fa-table me-2"></i>
                                            جدول الاختبارات التفصيلي الثاني
                                        </h4>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-light btn-sm" onclick="addRowToTable2()">
                                                <i class="fas fa-plus"></i> إضافة صف
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover mb-0" id="labTable2">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th style="min-width: 80px;">السنة</th>
                                                        <th style="min-width: 120px;">نوع العمل</th>
                                                        <th style="min-width: 80px;">العمق</th>
                                                        <th style="min-width: 100px;">دك التربة</th>
                                                        <th style="min-width: 100px;">MC1RC2</th>
                                                        <th style="min-width: 100px;">دك أسفلت</th>
                                                        <th style="min-width: 80px;">ترابي</th>
                                                        <th style="min-width: 120px;">الكثافة القصوى للأسفلت</th>
                                                        <th style="min-width: 100px;">نسبة الأسفلت</th>
                                                        <th style="min-width: 120px;">التدرج الحبيبي</th>
                                                        <th style="min-width: 120px;">تجربة مارشال</th>
                                                        <th style="min-width: 100px;">تقييم البلاط</th>
                                                        <th style="min-width: 80px;">البرودة</th>
                                                        <th style="min-width: 120px;">تصنيف التربة</th>
                                                        <th style="min-width: 120px;">تجربة بروكتور</th>
                                                        <th style="min-width: 100px;">الخرسانة</th>
                                                        <th style="min-width: 150px;">ملاحظات</th>
                                                        <th style="min-width: 80px;">إجراءات</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="labTable2Body">
                                                    <!-- الجدول فارغ للإدخال الجديد -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- قسم المخالفات -->
                            <div id="violations-section" class="tab-section" style="display: none;">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-danger text-white">
                                        <h4 class="mb-0 fs-5">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            معلومات المخالفات
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">رقم الرخصة التي عليها مخالفات</label>
                                                <input type="text" class="form-control" name="violation_license_number"
                                                       value="{{ old('violation_license_number') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">قيمة الرخصة</label>
                                                <input type="number" step="0.01" class="form-control" name="violation_license_value"
                                                       value="{{ old('violation_license_value') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">تاريخ الرخصة</label>
                                                <input type="date" class="form-control" name="violation_license_date"
                                                       value="{{ old('violation_license_date') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">آخر موعد سداد للمخالفة</label>
                                                <input type="date" class="form-control" name="violation_due_date"
                                                       value="{{ old('violation_due_date') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">رقم المخالفة</label>
                                                <input type="text" class="form-control" name="violation_number"
                                                       value="{{ old('violation_number') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">رقم سداد المخالفة</label>
                                                <input type="text" class="form-control" name="violation_payment_number"
                                                       value="{{ old('violation_payment_number') }}">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">المسبب</label>
                                                <input type="text" class="form-control" name="violation_cause"
                                                       value="{{ old('violation_cause') }}">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">مرفق المخالفات</label>
                                                <input type="file" class="form-control" name="violations_files[]" multiple accept=".pdf,.jpg,.jpeg,.png">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ملاحظات إضافية -->
                            <div class="card border-0 shadow-sm mt-4">
                                <div class="card-header bg-dark text-white">
                                    <h4 class="mb-0 fs-5">
                                        <i class="fas fa-sticky-note me-2"></i>
                                        ملاحظات إضافية
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">ملاحظات</label>
                                            <textarea class="form-control" name="notes" rows="4" 
                                                      placeholder="أدخل أي ملاحظات إضافية هنا...">{{ old('notes') }}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">مرفقات الملاحظات</label>
                                            <input type="file" class="form-control" name="notes_attachments[]" multiple accept=".pdf,.jpg,.jpeg,.png">
                                            @if(isset($license) && $license->notes_attachments_path)
                                                <div class="mt-2">
                                                    <a href="#" class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-eye"></i> عرض المرفقات الحالية
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- زر الحفظ -->
                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-success btn-lg px-5" id="submitBtn">
                                        <i class="fas fa-save me-2"></i>
                                        حفظ رخصة جديدة
                                    </button>
                                    
                                    <!-- زر حفظ مبسط إضافي -->
                                    <button type="button" class="btn btn-primary btn-lg px-5 ms-3" onclick="saveNewLicense()">
                                        <i class="fas fa-plus me-2"></i>
                                        إنشاء رخصة
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- جدول الرخص المحفوظة -->
                        <div class="card border-0 shadow-sm mt-5">
                            <div class="card-header bg-gradient-secondary text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0 fs-5">
                                        <i class="fas fa-table me-2"></i>
                                        جدول الرخص المحفوظة لأمر العمل {{ $workOrder->order_number }}
                                    </h4>
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-primary">
                                            <i class="fas fa-list-ol me-1"></i>
                                            إجمالي الرخص: <span id="totalLicensesCount">{{ $workOrder->licenses->count() ?? 0 }}</span>
                                        </span>
                                        <button type="button" class="btn btn-light btn-sm" onclick="refreshLicensesTable()">
                                            <i class="fas fa-sync-alt me-1"></i>
                                            تحديث
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped mb-0" id="licensesTable">
                                        <thead class="table-dark sticky-top">
                                            <tr>
                                                <th style="min-width: 60px;">#</th>
                                                <th style="min-width: 120px;">رقم الرخصة</th>
                                                <th style="min-width: 120px;">تاريخ الرخصة</th>
                                                <th style="min-width: 100px;">نوع الرخصة</th>
                                                <th style="min-width: 120px;">قيمة الرخصة</th>
                                                <th style="min-width: 120px;">قيمة التمديد</th>
                                                <th style="min-width: 120px;">تاريخ البداية</th>
                                                <th style="min-width: 120px;">تاريخ النهاية</th>
                                                <th style="min-width: 100px;">أبعاد الحفر</th>
                                                <th style="min-width: 80px;">حظر</th>
                                                <th style="min-width: 100px;">جهة الحظر</th>
                                                <!-- اختبارات المختبر -->
                                                <th style="min-width: 80px;">اختبار العمق</th>
                                                <th style="min-width: 80px;">اختبار التربة</th>
                                                <th style="min-width: 80px;">اختبار الأسفلت</th>
                                                <th style="min-width: 80px;">اختبار الدك</th>
                                                <th style="min-width: 80px;">اختبار RC1/MC1</th>
                                                <th style="min-width: 80px;">اختبار انترلوك</th>
                                                <!-- الإخلاءات -->
                                                <th style="min-width: 80px;">تم الإخلاء</th>
                                                <th style="min-width: 120px;">رقم رخصة الإخلاء</th>
                                                <th style="min-width: 120px;">قيمة رخصة الإخلاء</th>
                                                <th style="min-width: 120px;">رقم سداد الإخلاء</th>
                                                <th style="min-width: 120px;">تاريخ الإخلاء</th>
                                                <th style="min-width: 120px;">مبلغ الإخلاء</th>
                                                <!-- المخالفات -->
                                                <th style="min-width: 120px;">رقم رخصة المخالفة</th>
                                                <th style="min-width: 120px;">قيمة رخصة المخالفة</th>
                                                <th style="min-width: 120px;">تاريخ رخصة المخالفة</th>
                                                <th style="min-width: 120px;">آخر موعد سداد المخالفة</th>
                                                <th style="min-width: 120px;">رقم المخالفة</th>
                                                <th style="min-width: 120px;">رقم سداد المخالفة</th>
                                                <th style="min-width: 150px;">مسبب المخالفة</th>
                                                <!-- عام -->
                                                <th style="min-width: 150px;">ملاحظات</th>
                                                <th style="min-width: 100px;">تاريخ الإنشاء</th>
                                                <th style="min-width: 150px;" class="text-center">الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody id="licensesTableBody">
                                            <!-- سيتم ملء البيانات من خلال JavaScript أو من قاعدة البيانات -->
                                            @if(isset($workOrder->licenses) && $workOrder->licenses->count() > 0)
                                                @foreach($workOrder->licenses as $index => $license)
                                                <tr data-license-id="{{ $license->id }}">
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <span class="fw-bold text-primary">{{ $license->license_number ?? 'غير محدد' }}</span>
                                                    </td>
                                                    <td>{{ $license->license_date ? $license->license_date->format('Y-m-d') : 'غير محدد' }}</td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $license->license_type ?? 'غير محدد' }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="text-success fw-bold">{{ number_format($license->license_value ?? 0, 2) }} ر.س</span>
                                                    </td>
                                                    <td>
                                                        <span class="text-warning fw-bold">{{ number_format($license->extension_value ?? 0, 2) }} ر.س</span>
                                                    </td>
                                                    <td>{{ $license->license_start_date ? $license->license_start_date->format('Y-m-d') : 'غير محدد' }}</td>
                                                    <td>{{ $license->license_end_date ? $license->license_end_date->format('Y-m-d') : 'غير محدد' }}</td>
                                                    <td>
                                                        @if($license->excavation_length || $license->excavation_width || $license->excavation_depth)
                                                            <small>
                                                                الطول: {{ $license->excavation_length ?? 0 }} م<br>
                                                                العرض: {{ $license->excavation_width ?? 0 }} م<br>
                                                                العمق: {{ $license->excavation_depth ?? 0 }} م
                                                            </small>
                                                        @else
                                                            <span class="text-muted">غير محدد</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $license->has_restriction ? 'bg-danger' : 'bg-success' }}">
                                                            {{ $license->has_restriction ? 'نعم' : 'لا' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $license->restriction_authority ?? 'لا يوجد' }}</td>
                                                    <td>
                                                        <span class="badge {{ $license->has_depth_test ? 'bg-success' : 'bg-secondary' }}">
                                                            {{ $license->has_depth_test ? 'نعم' : 'لا' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $license->has_soil_test ? 'bg-success' : 'bg-secondary' }}">
                                                            {{ $license->has_soil_test ? 'نعم' : 'لا' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $license->has_asphalt_test ? 'bg-success' : 'bg-secondary' }}">
                                                            {{ $license->has_asphalt_test ? 'نعم' : 'لا' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $license->has_soil_compaction_test ? 'bg-success' : 'bg-secondary' }}">
                                                            {{ $license->has_soil_compaction_test ? 'نعم' : 'لا' }}
                                                        </span>
                                                    </td>
                                                    <!-- اختبارات إضافية -->
                                                    <td>
                                                        <span class="badge {{ $license->has_rc1_mc1_test ? 'bg-success' : 'bg-secondary' }}">
                                                            {{ $license->has_rc1_mc1_test ? 'نعم' : 'لا' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $license->has_interlock_test ? 'bg-success' : 'bg-secondary' }}">
                                                            {{ $license->has_interlock_test ? 'نعم' : 'لا' }}
                                                        </span>
                                                    </td>
                                                    <!-- الإخلاءات -->
                                                    <td>
                                                        <span class="badge {{ $license->is_evacuated ? 'bg-warning' : 'bg-secondary' }}">
                                                            {{ $license->is_evacuated ? 'نعم' : 'لا' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $license->evac_license_number ?? 'غير محدد' }}</td>
                                                    <td>
                                                        <span class="text-warning fw-bold">{{ $license->evac_license_value ? number_format($license->evac_license_value, 2) . ' ر.س' : 'غير محدد' }}</span>
                                                    </td>
                                                    <td>{{ $license->evac_payment_number ?? 'غير محدد' }}</td>
                                                    <td>{{ $license->evac_date ? $license->evac_date->format('Y-m-d') : 'غير محدد' }}</td>
                                                    <td>
                                                        <span class="text-info fw-bold">{{ $license->evac_amount ? number_format($license->evac_amount, 2) . ' ر.س' : 'غير محدد' }}</span>
                                                    </td>
                                                    <!-- المخالفات -->
                                                    <td>{{ $license->violation_license_number ?? 'لا يوجد' }}</td>
                                                    <td>
                                                        <span class="text-danger fw-bold">{{ $license->violation_license_value ? number_format($license->violation_license_value, 2) . ' ر.س' : 'لا يوجد' }}</span>
                                                    </td>
                                                    <td>{{ $license->violation_license_date ? $license->violation_license_date->format('Y-m-d') : 'لا يوجد' }}</td>
                                                    <td>{{ $license->violation_due_date ? $license->violation_due_date->format('Y-m-d') : 'لا يوجد' }}</td>
                                                    <td>{{ $license->violation_number ?? 'لا يوجد' }}</td>
                                                    <td>{{ $license->violation_payment_number ?? 'لا يوجد' }}</td>
                                                    <td>
                                                        @if($license->violation_cause)
                                                            <div class="text-truncate" style="max-width: 150px;" title="{{ $license->violation_cause }}">
                                                                {{ $license->violation_cause }}
                                                            </div>
                                                        @else
                                                            <span class="text-muted">لا يوجد</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $license->is_evacuated ? 'bg-warning' : 'bg-secondary' }}">
                                                            {{ $license->is_evacuated ? 'نعم' : 'لا' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($license->notes)
                                                            <div class="text-truncate" style="max-width: 150px;" title="{{ $license->notes }}">
                                                                {{ $license->notes }}
                                                            </div>
                                                        @else
                                                            <span class="text-muted">لا توجد ملاحظات</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">{{ $license->created_at->format('Y-m-d H:i') }}</small>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <button type="button" class="btn btn-outline-info" onclick="viewLicenseDetails({{ $license->id }})" title="عرض التفاصيل">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-primary" onclick="editLicenseDetails({{ $license->id }})" title="تعديل">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-danger" onclick="deleteLicenseRecord({{ $license->id }})" title="حذف">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr id="noLicensesRow">
                                                    <td colspan="31" class="text-center py-4">
                                                    <td colspan="19" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                                            <h5>لا توجد رخص محفوظة بعد</h5>
                                                            <p>قم بملء النموذج أعلاه وحفظ رخصة جديدة لعرضها هنا</p>
                                                        </div>
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

@push('scripts')
<script>
// وظيفة مبسطة لحفظ رخصة جديدة
function saveNewLicense() {
    const form = document.getElementById('licenseForm');
    if (!form) {
        alert('لم يتم العثور على النموذج');
        return;
    }
    
    // التحقق من الحقول المطلوبة
    const licenseNumber = form.querySelector('[name="license_number"]');
    if (!licenseNumber || !licenseNumber.value.trim()) {
        alert('يرجى إدخال رقم الرخصة');
        licenseNumber.focus();
        return;
    }
    
    // إرسال النموذج مباشرة
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        submitBtn.click();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // إعداد CSRF token لجميع طلبات AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // تفعيل التبويبات
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabSections = document.querySelectorAll('.tab-section');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            
            // إخفاء جميع الأقسام
            tabSections.forEach(section => {
                section.style.display = 'none';
            });
            
            // إظهار القسم المحدد
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                targetSection.style.display = 'block';
            }
            
            // تفعيل الزر المحدد
            tabButtons.forEach(btn => {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary');
            });
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary');
        });
    });
    
    // إظهار/إخفاء حقول الحظر
    const hasRestrictionSelect = document.getElementById('has_restriction');
    const restrictionAuthorityField = document.getElementById('restriction_authority_field');
    
    function toggleRestrictionFields() {
        if (hasRestrictionSelect && restrictionAuthorityField) {
            if (hasRestrictionSelect.value == '1') {
                restrictionAuthorityField.style.display = 'block';
            } else {
                restrictionAuthorityField.style.display = 'none';
            }
        }
    }
    
    if (hasRestrictionSelect) {
        hasRestrictionSelect.addEventListener('change', toggleRestrictionFields);
        toggleRestrictionFields(); // تشغيل عند التحميل
    }
    
    // حساب عداد الأيام للرخصة
    function calculateLicenseDays() {
        const startDate = document.getElementById('license_start_date');
        const endDate = document.getElementById('license_end_date');
        const daysText = document.getElementById('license_days_text');
        
        if (startDate && endDate && daysText && startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            const diffTime = end - start;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays > 0) {
                daysText.innerHTML = `<strong>${diffDays} يوم</strong>`;
                daysText.parentElement.className = 'alert alert-success w-100 mb-0';
            } else if (diffDays === 0) {
                daysText.innerHTML = '<strong>يوم واحد</strong>';
                daysText.parentElement.className = 'alert alert-warning w-100 mb-0';
            } else {
                daysText.innerHTML = '<strong>تاريخ غير صحيح</strong>';
                daysText.parentElement.className = 'alert alert-danger w-100 mb-0';
            }
        }
    }
    
    // حساب عداد الأيام للتمديد
    function calculateExtensionDays() {
        const startDate = document.getElementById('extension_start_date');
        const endDate = document.getElementById('extension_end_date');
        const daysText = document.getElementById('extension_days_text');
        
        if (startDate && endDate && daysText && startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            const diffTime = end - start;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays > 0) {
                daysText.innerHTML = `<strong>${diffDays} يوم تمديد</strong>`;
                daysText.parentElement.className = 'alert alert-success w-100 mb-0';
            } else if (diffDays === 0) {
                daysText.innerHTML = '<strong>يوم واحد تمديد</strong>';
                daysText.parentElement.className = 'alert alert-warning w-100 mb-0';
            } else {
                daysText.innerHTML = '<strong>تاريخ تمديد غير صحيح</strong>';
                daysText.parentElement.className = 'alert alert-danger w-100 mb-0';
            }
        }
    }
    
    // ربط الأحداث بحقول التاريخ
    const licenseStartDate = document.getElementById('license_start_date');
    const licenseEndDate = document.getElementById('license_end_date');
    const extensionStartDate = document.getElementById('extension_start_date');
    const extensionEndDate = document.getElementById('extension_end_date');
    
    if (licenseStartDate) licenseStartDate.addEventListener('change', calculateLicenseDays);
    if (licenseEndDate) licenseEndDate.addEventListener('change', calculateLicenseDays);
    if (extensionStartDate) extensionStartDate.addEventListener('change', calculateExtensionDays);
    if (extensionEndDate) extensionEndDate.addEventListener('change', calculateExtensionDays);
    
    // حساب الأيام عند التحميل
    calculateLicenseDays();
    calculateExtensionDays();
    
    // تأكيد الحفظ
    const form = document.getElementById('licenseForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // تعطيل زر الحفظ لمنع الضغط المتكرر
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> جاري الحفظ...';
            
            // إرسال النموذج مباشرة بدون AJAX معقد
            this.submit();
        });
    }
});

// وظائف إضافية للجداول
function addRowToTable1() {
    const tbody = document.getElementById('labTable1Body');
    const rowCount = tbody.rows.length;
    const row = document.createElement('tr');
    
    row.innerHTML = `
        <td><input type="text" class="form-control" name="lab_table1[${rowCount}][clearance_number]"></td>
        <td><input type="date" class="form-control" name="lab_table1[${rowCount}][clearance_date]"></td>
        <td><input type="number" step="0.01" class="form-control" name="lab_table1[${rowCount}][length]"></td>
        <td><input type="text" class="form-control" name="lab_table1[${rowCount}][street_type]"></td>
        <td><input type="checkbox" class="form-check-input" name="lab_table1[${rowCount}][is_dirt]" value="1"></td>
        <td><input type="checkbox" class="form-check-input" name="lab_table1[${rowCount}][is_asphalt]" value="1"></td>
        <td><input type="checkbox" class="form-check-input" name="lab_table1[${rowCount}][is_tile]" value="1"></td>
        <td><input type="checkbox" class="form-check-input" name="lab_table1[${rowCount}][is_soil]" value="1"></td>
        <td><input type="checkbox" class="form-check-input" name="lab_table1[${rowCount}][is_mc1]" value="1"></td>
        <td><input type="checkbox" class="form-check-input" name="lab_table1[${rowCount}][is_asphalt_final]" value="1"></td>
        <td><input type="text" class="form-control" name="lab_table1[${rowCount}][notes]"></td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
}

function addRowToTable2() {
    const tbody = document.getElementById('labTable2Body');
    const rowCount = tbody.rows.length;
    const row = document.createElement('tr');
    
    row.innerHTML = `
        <td><input type="number" class="form-control" name="lab_table2[${rowCount}][year]"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][work_type]"></td>
        <td><input type="number" step="0.01" class="form-control" name="lab_table2[${rowCount}][depth]"></td>
        <td><input type="checkbox" class="form-check-input" name="lab_table2[${rowCount}][soil_compaction]" value="1"></td>
        <td><input type="checkbox" class="form-check-input" name="lab_table2[${rowCount}][mc1rc2]" value="1"></td>
        <td><input type="checkbox" class="form-check-input" name="lab_table2[${rowCount}][asphalt_compaction]" value="1"></td>
        <td><input type="checkbox" class="form-check-input" name="lab_table2[${rowCount}][is_dirt]" value="1"></td>
        <td><input type="number" step="0.01" class="form-control" name="lab_table2[${rowCount}][max_asphalt_density]"></td>
        <td><input type="number" step="0.01" class="form-control" name="lab_table2[${rowCount}][asphalt_percentage]"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][granular_gradient]"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][marshall_test]"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][tile_evaluation]"></td>
        <td><input type="number" step="0.01" class="form-control" name="lab_table2[${rowCount}][coldness]"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][soil_classification]"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][proctor_test]"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][concrete]"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][notes]"></td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
}

function removeRow(button) {
    const row = button.closest('tr');
    row.remove();
}

// وظائف التعامل مع الأزرار
function viewLicense(licenseId) {
    const showUrl = "{{ route('admin.licenses.show', ':id') }}".replace(':id', licenseId);
    window.open(showUrl, '_blank');
}

function editLicense(licenseId) {
    const editUrl = "{{ route('admin.licenses.edit', ':id') }}".replace(':id', licenseId);
    window.open(editUrl, '_blank');
}

async function deleteLicense(licenseId) {
    if (!confirm('هل أنت متأكد من حذف هذه الرخصة؟\nلن يمكن التراجع عن هذا الإجراء.')) {
        return;
    }
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const response = await fetch(`{{ route('admin.licenses.destroy', ':id') }}`.replace(':id', licenseId), {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // إزالة الصف من الجدول
            const row = document.querySelector(`tr[data-license-id="${licenseId}"]`);
            if (row) {
                row.remove();
                
                // التحقق من وجود صفوف أخرى
                const tableBody = document.getElementById('licensesTableBody');
                if (tableBody.children.length === 0) {
                    const noLicensesRow = document.createElement('tr');
                    noLicensesRow.id = 'noLicensesRow';
                    noLicensesRow.innerHTML = `
                        <td colspan="31" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <h5>لا توجد رخص محفوظة بعد</h5>
                                <p>قم بملء النموذج أعلاه وحفظ رخصة جديدة لعرضها هنا</p>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(noLicensesRow);
                }
            }
            
            alert('تم حذف الرخصة بنجاح!');
        } else {
            alert('فشل في حذف الرخصة: ' + (data.message || 'خطأ غير معروف'));
        }
    } catch (error) {
        console.error('Error deleting license:', error);
        alert('حدث خطأ أثناء حذف الرخصة');
    }
}

function refreshLicensesTable() {
    location.reload();
}
</script>
@endpush

<style>
/* تنسيق الأزرار الاحترافي */
.btn {
    transition: all 0.3s ease;
    border: none;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-back {
    background: linear-gradient(45deg, #795548, #8D6E63);
    color: white;
}

.btn-back:hover {
    background: linear-gradient(45deg, #6D4C41, #795548);
    color: white;
}

.btn-primary {
    background: linear-gradient(45deg, #2196F3, #42A5F5);
    border-color: #2196F3;
}

.btn-outline-primary {
    border: 2px solid #2196F3;
    color: #2196F3;
    background: transparent;
}

.btn-outline-primary:hover {
    background: #2196F3;
    color: white;
}

.btn-success {
    background: linear-gradient(45deg, #4CAF50, #66BB6A);
}

.btn-info {
    background: linear-gradient(45deg, #00BCD4, #26C6DA);
    color: white;
}

.btn-warning {
    background: linear-gradient(45deg, #FF9800, #FFB74D);
    color: white;
}

.btn-danger {
    background: linear-gradient(45deg, #F44336, #EF5350);
}

.btn-light {
    background: linear-gradient(45deg, #FAFAFA, #F5F5F5);
    color: #333;
}

/* تحسين الهيدر */
.bg-gradient-primary {
    background: linear-gradient(135deg, #1976D2, #2196F3, #42A5F5);
}

/* تحسين البطاقات */
.card {
    transition: all 0.3s ease;
    border: none;
    border-radius: 1rem;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15) !important;
}

.card-header {
    border-radius: 1rem 1rem 0 0 !important;
    border-bottom: none;
    font-weight: 600;
}

/* تحسين النماذج */
.form-control, .form-select {
    border-radius: 0.5rem;
    border: 2px solid #e9ecef;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.form-control:focus, .form-select:focus {
    border-color: #2196F3;
    box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25);
    transform: scale(1.02);
}

.form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #495057;
}

/* تحسين التبويبات */
.tab-btn {
    margin: 0.25rem;
    min-width: 150px;
    font-weight: 600;
}

.tab-section {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* تحسين عداد الأيام */
.alert {
    border-radius: 0.75rem;
    border: none;
    font-weight: 600;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

/* تحسين الأيقونات */
.fas, .far {
    transition: transform 0.3s ease;
}

.btn:hover .fas,
.btn:hover .far {
    transform: scale(1.1);
}

/* تحسين عرض الشاشات الصغيرة */
@media (max-width: 768px) {
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        min-width: auto;
    }
    
    .tab-btn {
        min-width: 120px;
        margin: 0.125rem;
    }
    
    .card-body {
        padding: 1rem;
    }
}

/* تأثيرات إضافية */
.shadow-lg {
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
}

.border-0 {
    border: none !important;
}

/* تحسين الألوان */
.bg-primary { background: linear-gradient(45deg, #1976D2, #2196F3) !important; }
.bg-success { background: linear-gradient(45deg, #388E3C, #4CAF50) !important; }
.bg-info { background: linear-gradient(45deg, #0097A7, #00BCD4) !important; }
.bg-warning { background: linear-gradient(45deg, #F57C00, #FF9800) !important; }
.bg-danger { background: linear-gradient(45deg, #D32F2F, #F44336) !important; }
.bg-secondary { background: linear-gradient(45deg, #424242, #616161) !important; }
.bg-dark { background: linear-gradient(45deg, #212121, #424242) !important; }

/* تحسين الجداول */
.table-responsive {
    border-radius: 0.5rem;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.table {
    margin-bottom: 0;
    font-size: 0.875rem;
}

.table thead th {
    background: linear-gradient(45deg, #212529, #495057) !important;
    color: white !important;
    border: none;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    padding: 0.75rem 0.5rem;
    white-space: nowrap;
}

.table tbody td {
    padding: 0.5rem;
    vertical-align: middle;
    border: 1px solid #dee2e6;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    transition: all 0.3s ease;
}

.table input.form-control {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 0.375rem 0.5rem;
    font-size: 0.875rem;
    min-width: 80px;
}

.table input.form-control:focus {
    border-color: #2196F3;
    box-shadow: 0 0 0 0.1rem rgba(33, 150, 243, 0.25);
}

.btn-group .btn {
    margin: 0;
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-radius: 0.375rem 0 0 0.375rem;
}

.btn-group .btn:last-child {
    border-radius: 0 0.375rem 0.375rem 0;
}

/* تحسين عرض الجداول على الشاشات الصغيرة */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.75rem;
    }
    
    .table input.form-control {
        font-size: 0.75rem;
        padding: 0.25rem 0.375rem;
        min-width: 60px;
    }
    
    .table thead th {
        padding: 0.5rem 0.25rem;
        font-size: 0.75rem;
    }
    
    .btn-group .btn {
        padding: 0.375rem 0.5rem;
        font-size: 0.75rem;
    }
}

/* تحسين أزرار الإجراءات في الجداول */
.table .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 0.2rem;
}

/* تحسين مظهر الحقول المخفية */
[style*="display: none"] {
    transition: all 0.3s ease;
}

/* تحسين عداد الأيام */
#license_days_counter,
#extension_days_counter {
    transition: all 0.3s ease;
    font-weight: 600;
}

/* تحسين مربعات التحقق والخيارات */
.form-check-input {
    border-radius: 0.25rem;
    border: 2px solid #ced4da;
    transition: all 0.3s ease;
}

.form-check-input:checked {
    background-color: #2196F3;
    border-color: #2196F3;
}

/* تحسين رفع الملفات */
input[type="file"] {
    border: 2px dashed #ced4da;
    border-radius: 0.5rem;
    padding: 0.75rem;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

input[type="file"]:hover {
    border-color: #2196F3;
    background: #e3f2fd;
}

/* تحسين النصوص والتسميات */
.form-label {
    color: #495057;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

/* تحسين الأيقونات */
.fas, .far {
    margin-right: 0.25rem;
}

/* تحسين التبويبات المخفية */
.tab-section {
    min-height: 200px;
    padding: 1rem 0;
}

/* تحسين الرسائل والتنبيهات */
.alert {
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
}

.alert-info {
    background: linear-gradient(45deg, #e3f2fd, #bbdefb);
    color: #0277bd;
}

.alert-success {
    background: linear-gradient(45deg, #e8f5e8, #c8e6c9);
    color: #2e7d32;
}

.alert-warning {
    background: linear-gradient(45deg, #fff3e0, #ffcc02);
    color: #ef6c00;
}

.alert-danger {
    background: linear-gradient(45deg, #ffebee, #ffcdd2);
    color: #c62828;
}

/* تحسين الجدول الجديد */
.table th {
    font-size: 0.875rem;
    white-space: nowrap;
}

.table td {
    vertical-align: middle;
}

.editable-field {
    border: 1px solid #dee2e6;
    transition: all 0.15s ease-in-out;
}

.editable-field:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.table-success {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.sticky-top {
    top: 0;
    z-index: 1020;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, #6c757d, #868e96);
}

/* تحسين جدول الرخص المحفوظة */
#licensesTable {
    min-width: 1800px; /* عرض أدنى للجدول */
}

#licensesTable thead th {
    position: sticky;
    top: 0;
    z-index: 10;
    background: linear-gradient(45deg, #212529, #495057) !important;
    color: white !important;
    border: none;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    padding: 0.75rem 0.5rem;
    white-space: nowrap;
    font-size: 0.8rem;
}

#licensesTable tbody tr {
    transition: all 0.3s ease;
}

#licensesTable tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.1);
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

#licensesTable tbody td {
    padding: 0.5rem;
    vertical-align: middle;
    border: 1px solid #dee2e6;
    font-size: 0.875rem;
}

/* تأثيرات خاصة للبيانات */
.badge {
    font-size: 0.75rem;
}

.text-truncate {
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* تحسين أزرار الإجراءات */
.btn-group .btn {
    border-radius: 0.25rem;
    margin: 0 1px;
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: scale(1.1);
}

/* تحسين عرض الأرقام */
.fw-bold {
    font-weight: 600 !important;
}

/* تحسين العداد */
.badge.bg-primary {
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
}

/* تحسين الرسائل الفارغة */
#noLicensesRow .text-muted {
    color: #6c757d !important;
}

#noLicensesRow .fa-inbox {
    opacity: 0.3;
}

/* تحسين التأثيرات البصرية */
.table-success {
    background: linear-gradient(45deg, rgba(25, 135, 84, 0.1), rgba(40, 167, 69, 0.1)) !important;
    animation: successGlow 2s ease-in-out;
}

@keyframes successGlow {
    0% { 
        background: rgba(25, 135, 84, 0.3) !important;
        transform: scale(1.02);
    }
    100% { 
        background: rgba(25, 135, 84, 0.1) !important;
        transform: scale(1);
    }
}

@media (max-width: 768px) {
    .btn-group .btn {
        padding: 0.375rem 0.5rem;
        font-size: 0.75rem;
    }
}
</style>
@endsection 