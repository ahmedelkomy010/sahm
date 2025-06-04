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

                        <!-- شهادة التنسيق والمرفقات -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-warning text-dark">
                                <h4 class="mb-0 fs-5">
                                    <i class="fas fa-certificate me-2"></i>
                                    شهادة التنسيق والمرفقات
                                </h4>
                            </div>
                            <div class="card-body">
                                <form id="coordinationForm" class="coordination-form">
                                    @csrf
                                    <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                                    <input type="hidden" name="section_type" value="coordination">
                                    
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
                                    
                                    <div class="row mt-3">
                                        <div class="col-12 text-center">
                                            <button type="button" class="btn btn-warning btn-lg px-4" onclick="saveCoordinationSection()">
                                                <i class="fas fa-save me-2"></i>
                                                حفظ شهادة التنسيق والمرفقات
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <form action="{{ route('admin.licenses.store') }}" method="POST" enctype="multipart/form-data" id="licenseForm">
                            @csrf
                            <!-- إضافة حقل مخفي لـ work_order_id -->
                            <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                            
                            <!-- قسم رخص الحفر -->
                            <div id="dig-license-section" class="tab-section" style="display: none;">
                                <form id="digLicenseForm" class="dig-license-form">
                                    @csrf
                                    <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                                    <input type="hidden" name="section_type" value="dig_license">
                                    
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
                                                    <input type="date" class="form-control" name="license_start_date" id="dig_license_start_date"
                                                           value="{{ old('license_start_date') }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">تاريخ نهاية الرخصة</label>
                                                    <input type="date" class="form-control" name="license_end_date" id="dig_license_end_date"
                                                           value="{{ old('license_end_date') }}">
                                                </div>
                                                <div class="col-md-4 d-flex align-items-end">
                                                    <div class="alert alert-info w-100 mb-0" id="dig_license_days_counter">
                                                        <i class="fas fa-clock me-2"></i>
                                                        <span id="dig_license_days_text">اختر التواريخ لحساب المدة</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">تاريخ بداية التمديد</label>
                                                    <input type="date" class="form-control" name="extension_start_date" id="dig_extension_start_date"
                                                           value="{{ old('extension_start_date') }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">تاريخ نهاية التمديد</label>
                                                    <input type="date" class="form-control" name="extension_end_date" id="dig_extension_end_date"
                                                           value="{{ old('extension_end_date') }}">
                                                </div>
                                                <input type="hidden" name="license_alert_days" value="30">
                                                <div class="col-md-4 d-flex align-items-end">
                                                    <div class="alert alert-warning w-100 mb-0" id="dig_extension_days_counter">
                                                        <i class="fas fa-clock me-2"></i>
                                                        <span id="dig_extension_days_text">اختر تواريخ التمديد</span>
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
                                    
                                    <div class="row mt-3">
                                        <div class="col-12 text-center">
                                            <button type="button" class="btn btn-primary btn-lg px-4" onclick="saveDigLicenseSection()">
                                                <i class="fas fa-save me-2"></i>
                                                حفظ رخص الحفر
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- قسم المختبر -->
                            <div id="lab-section" class="tab-section" style="display: none;">
                                <form id="labForm" class="lab-form">
                                    @csrf
                                    <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                                    <input type="hidden" name="section_type" value="lab">
                                    
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

                                    

                                    <div class="row mt-3">
                                        <div class="col-12 text-center">
                                            <button type="button" class="btn btn-success btn-lg px-4" onclick="saveLabSection()">
                                                <i class="fas fa-save me-2"></i>
                                                حفظ المختبر
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- قسم الإخلاءات -->
                            <div id="evacuations-section" class="tab-section" style="display: none;">
                                <form id="evacuationsForm" class="evacuations-form">
                                    @csrf
                                    <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                                    <input type="hidden" name="section_type" value="evacuations">
                                    
                                <div class="card border-0 shadow-sm mb-4">
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

                                    <!-- جداول المختبر المنقولة -->
                                    <div class="card border-0 shadow-sm mb-4">
                                        <div class="card-header bg-info text-white">
                                            <h4 class="mb-0 fs-5">
                                                <i class="fas fa-table me-2"></i>
                                                جدول الفسح ونوع الشارع
                                            </h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="evacTable1">
                                                    <thead class="bg-primary text-white">
                                                        <tr>
                                                            <th rowspan="2" class="align-middle">رقم الفسح</th>
                                                            <th rowspan="2" class="align-middle">تاريخ الفسح</th>
                                                            <th colspan="3" class="text-center">كمية المواد (متر مكعب)</th>
                                                            <th rowspan="2" class="align-middle">نوع الشارع</th>
                                                            <th rowspan="2" class="align-middle">الطول</th>
                                                            <th rowspan="2" class="align-middle">رقم الفسح والمختبر</th>
                                                            <th colspan="3" class="text-center">تدقيق المختبر</th>
                                                            <th rowspan="2" class="align-middle">ملاحظات</th>
                                                            <th rowspan="2" class="align-middle">حذف</th>
                                                        </tr>
                                                        <tr>
                                                            <th>ترابي</th>
                                                            <th>أسفلت</th>
                                                            <th>بلاط</th>
                                                            <th>التربة</th>
                                                            <th>MC1</th>
                                                            <th>أسفلت</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="evacTable1Body">
                                                        <!-- سيتم إضافة الصفوف هنا -->
                                                    </tbody>
                                                </table>
                                            </div>
                                            <button type="button" class="btn btn-primary btn-sm mt-2" onclick="addRowToEvacTable1()">
                                                <i class="fas fa-plus"></i> إضافة صف جديد
                                            </button>
                                        </div>
                                    </div>

                                    <!-- الجدول الثاني: التفاصيل الفنية -->
                                    <div class="card border-0 shadow-sm mb-4">
                                        <div class="card-header bg-secondary text-white">
                                            <h4 class="mb-0 fs-5">
                                                <i class="fas fa-cogs me-2"></i>
                                                جدول التفاصيل الفنية للمختبر
                                            </h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="evacTable2">
                                                    <thead class="bg-dark text-white">
                                                        <tr>
                                                            <th>السنة</th>
                                                            <th>نوع العمل</th>
                                                            <th>العمق</th>
                                                            <th>دك التربة</th>
                                                            <th>MC1-RC2</th>
                                                            <th>دك أسفلت</th>
                                                            <th>ترابي</th>
                                                            <th>الكثافة القصوى للأسفلت</th>
                                                            <th>نسبة الأسفلت</th>
                                                            <th>التدرج الحبيبي</th>
                                                            <th>تجربة مارشال</th>
                                                            <th>تقييم البلاط</th>
                                                            <th>البرودة</th>
                                                            <th>تصنيف التربة</th>
                                                            <th>تجربة بروكتور</th>
                                                            <th>الخرسانة</th>
                                                            <th>ملاحظات</th>
                                                            <th>حذف</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="evacTable2Body">
                                                        <!-- سيتم إضافة الصفوف هنا -->
                                                    </tbody>
                                                </table>
                                            </div>
                                            <button type="button" class="btn btn-primary btn-sm mt-2" onclick="addRowToEvacTable2()">
                                                <i class="fas fa-plus"></i> إضافة صف جديد
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-12 text-center">
                                            <button type="button" class="btn btn-warning btn-lg px-4" onclick="saveEvacuationsSection()">
                                                <i class="fas fa-save me-2"></i>
                                                حفظ الإخلاءات
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- قسم المخالفات -->
                            <div id="violations-section" class="tab-section" style="display: none;">
                                <form id="violationsForm" class="violations-form">
                                    @csrf
                                    <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                                    <input type="hidden" name="section_type" value="violations">
                                    
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
                                    
                                    <div class="row mt-3">
                                        <div class="col-12 text-center">
                                            <button type="button" class="btn btn-danger btn-lg px-4" onclick="saveViolationsSection()">
                                                <i class="fas fa-save me-2"></i>
                                                حفظ المخالفات
                                            </button>
                                        </div>
                                    </div>
                                </form>
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
                                    <form id="notesForm" class="notes-form">
                                        @csrf
                                        <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                                        <input type="hidden" name="section_type" value="notes">
                                        
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
                                        
                                        <div class="row mt-3">
                                            <div class="col-12 text-center">
                                                <button type="button" class="btn btn-dark btn-lg px-4" onclick="saveNotesSection()">
                                                    <i class="fas fa-save me-2"></i>
                                                    حفظ الملاحظات الإضافية
                                                </button>
                                            </div>
                                        </div>
                                    </form>
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
                                                <!-- جداول المختبر -->
                                                <th style="min-width: 120px;">جداول المختبر الأول</th>
                                                <th style="min-width: 120px;">جداول المختبر الثاني</th>
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
                                                        @if($license->lab_table1_data)
                                                            <button type="button" class="btn btn-sm btn-info" onclick="viewLabTable1({{ $license->id }})" title="عرض جدول الفسح">
                                                                <i class="fas fa-table"></i> عرض الجدول الأول
                                                            </button>
                                                        @else
                                                            <span class="text-muted">لا يوجد</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($license->lab_table2_data)
                                                            <button type="button" class="btn btn-sm btn-info" onclick="viewLabTable2({{ $license->id }})" title="عرض جدول التفاصيل الفنية">
                                                                <i class="fas fa-table"></i> عرض الجدول الثاني
                                                            </button>
                                                        @else
                                                            <span class="text-muted">لا يوجد</span>
                                                        @endif
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
                                                    <td colspan="33" class="text-center py-4">
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

        <!-- Modal لعرض جداول المختبر -->
        <div class="modal fade" id="labTableModal" tabindex="-1" aria-labelledby="labTableModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="labTableModalLabel">جداول المختبر</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="labTableModalBody">
                        <!-- سيتم إدراج محتوى الجدول هنا -->
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
        // إضافة دوال عرض جداول المختبر
        function viewLabTable1(licenseId) {
            const license = @json($workOrder->licenses ?? []).find(l => l.id === licenseId);
            if (!license || !license.lab_table1_data) {
                alert('لا توجد بيانات للجدول الأول');
                return;
            }

            const tableData = JSON.parse(license.lab_table1_data);
            let tableHTML = `
                <h6 class="text-info mb-3">جدول الفسح ونوع الشارع</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>رقم الفسح</th>
                                <th>تاريخ الفسح</th>
                                <th>ترابي</th>
                                <th>أسفلت</th>
                                <th>بلاط</th>
                                <th>الطول</th>
                                <th>تدقيق المختبر</th>
                                <th>ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            tableData.forEach(row => {
                tableHTML += `
                    <tr>
                        <td>${row.clearance_number || '-'}</td>
                        <td>${row.clearance_date || '-'}</td>
                        <td>${row.is_dirt ? '✓' : '-'}</td>
                        <td>${row.is_asphalt ? '✓' : '-'}</td>
                        <td>${row.is_tile ? '✓' : '-'}</td>
                        <td>${row.length || '-'} م</td>
                        <td>${row.lab_check || '-'}</td>
                        <td>${row.notes || '-'}</td>
                    </tr>
                `;
            });

            tableHTML += `
                        </tbody>
                    </table>
                </div>
            `;

            document.getElementById('labTableModalBody').innerHTML = tableHTML;
            new bootstrap.Modal(document.getElementById('labTableModal')).show();
        }

        function viewLabTable2(licenseId) {
            const license = @json($workOrder->licenses ?? []).find(l => l.id === licenseId);
            if (!license || !license.lab_table2_data) {
                alert('لا توجد بيانات للجدول الثاني');
                return;
            }

            const tableData = JSON.parse(license.lab_table2_data);
            let tableHTML = `
                <h6 class="text-info mb-3">جدول التفاصيل الفنية</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th>السنة</th>
                                <th>نوع العمل</th>
                                <th>العمق</th>
                                <th>دك التربة</th>
                                <th>MC1-RC2</th>
                                <th>الكثافة القصوى</th>
                                <th>نسبة الأسفلت</th>
                                <th>التدرج الحبيبي</th>
                                <th>تجربة مارشال</th>
                                <th>تقييم البلاط</th>
                                <th>تصنيف التربة</th>
                                <th>تجربة بروكتور</th>
                                <th>الخرسانة</th>
                                <th>ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            tableData.forEach(row => {
                tableHTML += `
                    <tr>
                        <td>${row.year || '-'}</td>
                        <td>${row.work_type || '-'}</td>
                        <td>${row.depth || '-'}</td>
                        <td>${row.soil_compaction ? '✓' : '-'}</td>
                        <td>${row.mc1rc2 ? '✓' : '-'}</td>
                        <td>${row.max_asphalt_density || '-'}</td>
                        <td>${row.asphalt_percentage || '-'}</td>
                        <td>${row.granular_gradient || '-'}</td>
                        <td>${row.marshall_test || '-'}</td>
                        <td>${row.tile_evaluation || '-'}</td>
                        <td>${row.soil_classification || '-'}</td>
                        <td>${row.proctor_test || '-'}</td>
                        <td>${row.concrete || '-'}</td>
                        <td>${row.notes || '-'}</td>
                    </tr>
                `;
            });

            tableHTML += `
                        </tbody>
                    </table>
                </div>
            `;

            document.getElementById('labTableModalBody').innerHTML = tableHTML;
            new bootstrap.Modal(document.getElementById('labTableModal')).show();
        }
        
        // وظائف الحفظ المنفصل
        async function saveCoordinationSection() {
            console.log('saveCoordinationSection called');
            const form = document.getElementById('coordinationForm');
            const formData = new FormData(form);
            
            try {
                showLoadingState('coordinationForm', 'جاري حفظ شهادة التنسيق...');
                
                const response = await fetch('{{ route("admin.licenses.save-section") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showSuccessMessage('تم حفظ شهادة التنسيق والمرفقات بنجاح!');
                    if (result.refresh_table) {
                        refreshLicensesTable();
                    }
                } else {
                    showErrorMessage('خطأ في حفظ شهادة التنسيق: ' + (result.message || 'خطأ غير معروف'));
                }
            } catch (error) {
                console.error('Error saving coordination section:', error);
                showErrorMessage('حدث خطأ أثناء حفظ شهادة التنسيق');
            } finally {
                hideLoadingState('coordinationForm', 'حفظ شهادة التنسيق والمرفقات');
            }
        }

        async function saveDigLicenseSection() {
            const form = document.getElementById('digLicenseForm');
            const formData = new FormData(form);
            
            try {
                showLoadingState('digLicenseForm', 'جاري حفظ رخص الحفر...');
                
                const response = await fetch('{{ route("admin.licenses.save-section") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showSuccessMessage('تم حفظ رخص الحفر بنجاح!');
                    if (result.refresh_table) {
                        refreshLicensesTable();
                    }
                } else {
                    showErrorMessage('خطأ في حفظ رخص الحفر: ' + (result.message || 'خطأ غير معروف'));
                }
            } catch (error) {
                console.error('Error saving dig license section:', error);
                showErrorMessage('حدث خطأ أثناء حفظ رخص الحفر');
            } finally {
                hideLoadingState('digLicenseForm', 'حفظ رخص الحفر');
            }
        }

        async function saveLabSection() {
            const form = document.getElementById('labForm');
            const formData = new FormData(form);
            
            // معالجة بيانات جداول المختبر
            const table1Data = collectTableData('labTable1Body');
            const table2Data = collectTableData('labTable2Body');
            
            if (table1Data.length > 0) {
                formData.append('lab_table1_data', JSON.stringify(table1Data));
            }
            
            if (table2Data.length > 0) {
                formData.append('lab_table2_data', JSON.stringify(table2Data));
            }
            
            try {
                showLoadingState('labForm', 'جاري حفظ المختبر...');
                
                const response = await fetch('{{ route("admin.licenses.save-section") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showSuccessMessage('تم حفظ المختبر بنجاح!');
                    if (result.refresh_table) {
                        refreshLicensesTable();
                    }
                } else {
                    showErrorMessage('خطأ في حفظ المختبر: ' + (result.message || 'خطأ غير معروف'));
                }
            } catch (error) {
                console.error('Error saving lab section:', error);
                showErrorMessage('حدث خطأ أثناء حفظ المختبر');
            } finally {
                hideLoadingState('labForm', 'حفظ المختبر');
            }
        }

        // دالة لجمع بيانات الجداول
        function collectTableData(tableBodyId) {
            const tbody = document.getElementById(tableBodyId);
            if (!tbody) return [];
            
            const rows = tbody.querySelectorAll('tr');
            const data = [];
            
            rows.forEach(row => {
                const inputs = row.querySelectorAll('input, select, textarea');
                const rowData = {};
                let hasData = false;
                
                inputs.forEach(input => {
                    if (input.name && input.name.includes('[')) {
                        const fieldName = input.name.split('[').pop().replace(']', '');
                        
                        if (input.type === 'checkbox') {
                            rowData[fieldName] = input.checked;
                            if (input.checked) hasData = true;
                        } else if (input.type === 'number') {
                            rowData[fieldName] = parseFloat(input.value) || 0;
                            if (input.value.trim()) hasData = true;
                        } else {
                            rowData[fieldName] = input.value;
                            if (input.value.trim()) hasData = true;
                        }
                    }
                });
                
                if (hasData) {
                    data.push(rowData);
                }
            });
            
            return data;
        }

        async function saveEvacuationsSection() {
            const form = document.getElementById('evacuationsForm');
            const formData = new FormData(form);
            
            // معالجة بيانات جداول الإخلاءات
            const evacTable1Data = collectTableData('evacTable1Body');
            const evacTable2Data = collectTableData('evacTable2Body');
            
            if (evacTable1Data.length > 0) {
                formData.append('evac_table1_data', JSON.stringify(evacTable1Data));
            }
            
            if (evacTable2Data.length > 0) {
                formData.append('evac_table2_data', JSON.stringify(evacTable2Data));
            }
            
            try {
                showLoadingState('evacuationsForm', 'جاري حفظ الإخلاءات...');
                
                const response = await fetch('{{ route("admin.licenses.save-section") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showSuccessMessage('تم حفظ الإخلاءات بنجاح!');
                    if (result.refresh_table) {
                        refreshLicensesTable();
                    }
                } else {
                    showErrorMessage('خطأ في حفظ الإخلاءات: ' + (result.message || 'خطأ غير معروف'));
                }
            } catch (error) {
                console.error('Error saving evacuations section:', error);
                showErrorMessage('حدث خطأ أثناء حفظ الإخلاءات');
            } finally {
                hideLoadingState('evacuationsForm', 'حفظ الإخلاءات');
            }
        }

        async function saveViolationsSection() {
            const form = document.getElementById('violationsForm');
            const formData = new FormData(form);
            
            try {
                showLoadingState('violationsForm', 'جاري حفظ المخالفات...');
                
                const response = await fetch('{{ route("admin.licenses.save-section") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showSuccessMessage('تم حفظ المخالفات بنجاح!');
                    if (result.refresh_table) {
                        refreshLicensesTable();
                    }
                } else {
                    showErrorMessage('خطأ في حفظ المخالفات: ' + (result.message || 'خطأ غير معروف'));
                }
            } catch (error) {
                console.error('Error saving violations section:', error);
                showErrorMessage('حدث خطأ أثناء حفظ المخالفات');
            } finally {
                hideLoadingState('violationsForm', 'حفظ المخالفات');
            }
        }

        async function saveNotesSection() {
            const form = document.getElementById('notesForm');
            const formData = new FormData(form);
            
            try {
                showLoadingState('notesForm', 'جاري حفظ الملاحظات...');
                
                const response = await fetch('{{ route("admin.licenses.save-section") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showSuccessMessage('تم حفظ الملاحظات الإضافية بنجاح!');
                    if (result.refresh_table) {
                        refreshLicensesTable();
                    }
                } else {
                    showErrorMessage('خطأ في حفظ الملاحظات: ' + (result.message || 'خطأ غير معروف'));
                }
            } catch (error) {
                console.error('Error saving notes section:', error);
                showErrorMessage('حدث خطأ أثناء حفظ الملاحظات');
            } finally {
                hideLoadingState('notesForm', 'حفظ الملاحظات الإضافية');
            }
        }

        // وظائف مساعدة لحالة التحميل والرسائل
        function showLoadingState(formId, message) {
            const form = document.getElementById(formId);
            const button = form.querySelector('button[onclick*="save"], button[type="button"]:last-child');
            if (button) {
                button.disabled = true;
                button.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i>${message}`;
            }
        }

        function hideLoadingState(formId, originalText) {
            const form = document.getElementById(formId);
            const button = form.querySelector('button[onclick*="save"], button[type="button"]:last-child');
            if (button) {
                button.disabled = false;
                button.innerHTML = `<i class="fas fa-save me-2"></i>${originalText}`;
            }
        }

        function showSuccessMessage(message) {
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alert.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alert);
            
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 5000);
        }

        function showErrorMessage(message) {
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger alert-dismissible fade show position-fixed';
            alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alert.innerHTML = `
                <i class="fas fa-exclamation-circle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(alert);
            
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 7000);
        }

        function refreshLicensesTable() {
            location.reload();
        }

        // وظائف إدارة جداول المختبر
        function addRowToTable1() {
            const tbody = document.getElementById('labTable1Body');
            const rowCount = tbody.rows.length;
            const row = document.createElement('tr');
            
            row.innerHTML = `
                <td><input type="text" class="form-control form-control-sm" name="lab_table1[${rowCount}][clearance_number]" placeholder="رقم الفسح"></td>
                <td><input type="date" class="form-control form-control-sm" name="lab_table1[${rowCount}][clearance_date]"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="lab_table1[${rowCount}][dirt_quantity]" placeholder="كمية ترابي"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="lab_table1[${rowCount}][asphalt_quantity]" placeholder="كمية أسفلت"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="lab_table1[${rowCount}][tile_quantity]" placeholder="كمية بلاط"></td>
                <td><input type="text" class="form-control form-control-sm" name="lab_table1[${rowCount}][street_type]" placeholder="نوع الشارع"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="lab_table1[${rowCount}][length]" placeholder="الطول بالمتر"></td>
                <td><input type="text" class="form-control form-control-sm" name="lab_table1[${rowCount}][clearance_lab_number]" placeholder="رقم الفسح والمختبر"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="lab_table1[${rowCount}][soil_check]" placeholder="تدقيق التربة"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="lab_table1[${rowCount}][mc1_check]" placeholder="تدقيق MC1"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="lab_table1[${rowCount}][asphalt_check]" placeholder="تدقيق أسفلت"></td>
                <td><input type="text" class="form-control form-control-sm" name="lab_table1[${rowCount}][notes]" placeholder="ملاحظات"></td>
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
                <td><input type="number" class="form-control form-control-sm" name="lab_table2[${rowCount}][year]" value="${new Date().getFullYear()}"></td>
                <td><input type="text" class="form-control form-control-sm" name="lab_table2[${rowCount}][work_type]" placeholder="نوع العمل"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="lab_table2[${rowCount}][depth]" placeholder="العمق"></td>
                <td><input type="checkbox" class="form-check-input" name="lab_table2[${rowCount}][soil_compaction]" value="1"></td>
                <td><input type="checkbox" class="form-check-input" name="lab_table2[${rowCount}][mc1rc2]" value="1"></td>
                <td><input type="checkbox" class="form-check-input" name="lab_table2[${rowCount}][asphalt_compaction]" value="1"></td>
                <td><input type="checkbox" class="form-check-input" name="lab_table2[${rowCount}][is_dirt]" value="1"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="lab_table2[${rowCount}][max_asphalt_density]" placeholder="الكثافة القصوى"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="lab_table2[${rowCount}][asphalt_percentage]" placeholder="نسبة الأسفلت"></td>
                <td><input type="text" class="form-control form-control-sm" name="lab_table2[${rowCount}][granular_gradient]" placeholder="التدرج الحبيبي"></td>
                <td><input type="text" class="form-control form-control-sm" name="lab_table2[${rowCount}][marshall_test]" placeholder="تجربة مارشال"></td>
                <td><input type="text" class="form-control form-control-sm" name="lab_table2[${rowCount}][tile_evaluation]" placeholder="تقييم البلاط"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="lab_table2[${rowCount}][coldness]" placeholder="البرودة"></td>
                <td><input type="text" class="form-control form-control-sm" name="lab_table2[${rowCount}][soil_classification]" placeholder="تصنيف التربة"></td>
                <td><input type="text" class="form-control form-control-sm" name="lab_table2[${rowCount}][proctor_test]" placeholder="تجربة بروكتور"></td>
                <td><input type="text" class="form-control form-control-sm" name="lab_table2[${rowCount}][concrete]" placeholder="الخرسانة"></td>
                <td><input type="text" class="form-control form-control-sm" name="lab_table2[${rowCount}][notes]" placeholder="ملاحظات"></td>
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
        function viewLicenseDetails(licenseId) {
            const showUrl = "{{ route('admin.licenses.show', ':id') }}".replace(':id', licenseId);
            window.open(showUrl, '_blank');
        }

        function editLicenseDetails(licenseId) {
            const editUrl = "{{ route('admin.licenses.edit', ':id') }}".replace(':id', licenseId);
            window.open(editUrl, '_blank');
        }

        async function deleteLicenseRecord(licenseId) {
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
                    showSuccessMessage('تم حذف الرخصة بنجاح!');
                    refreshLicensesTable();
                } else {
                    showErrorMessage('فشل في حذف الرخصة: ' + (data.message || 'خطأ غير معروف'));
                }
            } catch (error) {
                console.error('Error deleting license:', error);
                showErrorMessage('حدث خطأ أثناء حذف الرخصة');
            }
        }

        // دوال إضافية
        function saveNewLicense() {
            const form = document.getElementById('licenseForm');
            if (!form) {
                alert('لم يتم العثور على النموذج');
                return;
            }
            
            const licenseNumber = form.querySelector('[name="license_number"]');
            if (!licenseNumber || !licenseNumber.value.trim()) {
                alert('يرجى إدخال رقم الرخصة');
                if (licenseNumber) licenseNumber.focus();
                return;
            }
            
            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn) {
                submitBtn.click();
            }
        }

        // تفعيل التبويبات والوظائف الأساسية
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // تفعيل التبويبات
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabSections = document.querySelectorAll('.tab-section');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    
                    tabSections.forEach(section => {
                        section.style.display = 'none';
                    });
                    
                    const targetSection = document.getElementById(targetId);
                    if (targetSection) {
                        targetSection.style.display = 'block';
                    }
                    
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
                toggleRestrictionFields();
            }
            
            // حساب عداد الأيام
            function calculateLicenseDays() {
                const startDate = document.getElementById('dig_license_start_date');
                const endDate = document.getElementById('dig_license_end_date');
                const daysText = document.getElementById('dig_license_days_text');
                
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
            
            function calculateExtensionDays() {
                const startDate = document.getElementById('dig_extension_start_date');
                const endDate = document.getElementById('dig_extension_end_date');
                const daysText = document.getElementById('dig_extension_days_text');
                
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
            const licenseStartDate = document.getElementById('dig_license_start_date');
            const licenseEndDate = document.getElementById('dig_license_end_date');
            const extensionStartDate = document.getElementById('dig_extension_start_date');
            const extensionEndDate = document.getElementById('dig_extension_end_date');
            
            if (licenseStartDate) licenseStartDate.addEventListener('change', calculateLicenseDays);
            if (licenseEndDate) licenseEndDate.addEventListener('change', calculateLicenseDays);
            if (extensionStartDate) extensionStartDate.addEventListener('change', calculateExtensionDays);
            if (extensionEndDate) extensionEndDate.addEventListener('change', calculateExtensionDays);
            
            calculateLicenseDays();
            calculateExtensionDays();
            
            // تأكيد الحفظ
            const form = document.getElementById('licenseForm');
            const submitBtn = document.getElementById('submitBtn');
            
            if (form && submitBtn) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> جاري الحفظ...';
                    
                    this.submit();
                });
            }
        });
        
        // وظائف إدارة جداول الإخلاءات
        function addRowToEvacTable1() {
            const tbody = document.getElementById('evacTable1Body');
            const rowCount = tbody.rows.length;
            const row = document.createElement('tr');
            
            row.innerHTML = `
                <td><input type="text" class="form-control form-control-sm" name="evac_table1[${rowCount}][clearance_number]" placeholder="رقم الفسح"></td>
                <td><input type="date" class="form-control form-control-sm" name="evac_table1[${rowCount}][clearance_date]"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table1[${rowCount}][dirt_quantity]" placeholder="كمية ترابي"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table1[${rowCount}][asphalt_quantity]" placeholder="كمية أسفلت"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table1[${rowCount}][tile_quantity]" placeholder="كمية بلاط"></td>
                <td><input type="text" class="form-control form-control-sm" name="evac_table1[${rowCount}][street_type]" placeholder="نوع الشارع"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table1[${rowCount}][length]" placeholder="الطول بالمتر"></td>
                <td><input type="text" class="form-control form-control-sm" name="evac_table1[${rowCount}][clearance_lab_number]" placeholder="رقم الفسح والمختبر"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table1[${rowCount}][soil_check]" placeholder="تدقيق التربة"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table1[${rowCount}][mc1_check]" placeholder="تدقيق MC1"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table1[${rowCount}][asphalt_check]" placeholder="تدقيق أسفلت"></td>
                <td><input type="text" class="form-control form-control-sm" name="evac_table1[${rowCount}][notes]" placeholder="ملاحظات"></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            
            tbody.appendChild(row);
        }

        function addRowToEvacTable2() {
            const tbody = document.getElementById('evacTable2Body');
            const rowCount = tbody.rows.length;
            const row = document.createElement('tr');
            
            row.innerHTML = `
                <td><input type="number" class="form-control form-control-sm" name="evac_table2[${rowCount}][year]" value="${new Date().getFullYear()}"></td>
                <td><input type="text" class="form-control form-control-sm" name="evac_table2[${rowCount}][work_type]" placeholder="نوع العمل"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[${rowCount}][depth]" placeholder="العمق"></td>
                <td><input type="checkbox" class="form-check-input" name="evac_table2[${rowCount}][soil_compaction]" value="1"></td>
                <td><input type="checkbox" class="form-check-input" name="evac_table2[${rowCount}][mc1rc2]" value="1"></td>
                <td><input type="checkbox" class="form-check-input" name="evac_table2[${rowCount}][asphalt_compaction]" value="1"></td>
                <td><input type="checkbox" class="form-check-input" name="evac_table2[${rowCount}][is_dirt]" value="1"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[${rowCount}][max_asphalt_density]" placeholder="الكثافة القصوى"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[${rowCount}][asphalt_percentage]" placeholder="نسبة الأسفلت"></td>
                <td><input type="text" class="form-control form-control-sm" name="evac_table2[${rowCount}][granular_gradient]" placeholder="التدرج الحبيبي"></td>
                <td><input type="text" class="form-control form-control-sm" name="evac_table2[${rowCount}][marshall_test]" placeholder="تجربة مارشال"></td>
                <td><input type="text" class="form-control form-control-sm" name="evac_table2[${rowCount}][tile_evaluation]" placeholder="تقييم البلاط"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[${rowCount}][coldness]" placeholder="البرودة"></td>
                <td><input type="text" class="form-control form-control-sm" name="evac_table2[${rowCount}][soil_classification]" placeholder="تصنيف التربة"></td>
                <td><input type="text" class="form-control form-control-sm" name="evac_table2[${rowCount}][proctor_test]" placeholder="تجربة بروكتور"></td>
                <td><input type="text" class="form-control form-control-sm" name="evac_table2[${rowCount}][concrete]" placeholder="الخرسانة"></td>
                <td><input type="text" class="form-control form-control-sm" name="evac_table2[${rowCount}][notes]" placeholder="ملاحظات"></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            
            tbody.appendChild(row);
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
    min-width: 2500px; /* عرض أدنى للجدول */
    font-size: 0.85rem;
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
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

#licensesTable tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #dee2e6;
}

#licensesTable tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.08);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

#licensesTable tbody td {
    padding: 0.6rem 0.4rem;
    vertical-align: middle;
    border-right: 1px solid #dee2e6;
    font-size: 0.85rem;
    min-height: 50px;
}

#licensesTable tbody td:last-child {
    border-right: none;
}

/* تحسين البيانات في الجدول */
#licensesTable .badge {
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
    border-radius: 12px;
}

#licensesTable .btn-group .btn {
    padding: 0.25rem 0.4rem;
    font-size: 0.75rem;
    border-radius: 4px;
    margin: 0 1px;
}

#licensesTable .text-truncate {
    max-width: 120px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* تحسين التمرير الأفقي */
.table-responsive {
    max-height: 80vh;
    overflow-x: auto;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.table-responsive::-webkit-scrollbar {
    height: 12px;
    width: 12px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 6px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: linear-gradient(45deg, #6c757d, #495057);
    border-radius: 6px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(45deg, #495057, #343a40);
}

/* تحسين عداد الرخص */
.badge.bg-primary {
    background: linear-gradient(45deg, #0d6efd, #0b5ed7) !important;
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
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

/* تحسين أزرار التحديث */
.btn-light {
    background: linear-gradient(45deg, #f8f9fa, #e9ecef);
    border: 1px solid #ced4da;
    color: #495057;
}

.btn-light:hover {
    background: linear-gradient(45deg, #e9ecef, #dee2e6);
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

/* تحسين رسالة عدم وجود بيانات */
#noLicensesRow {
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
}

#noLicensesRow .fa-inbox {
    color: #6c757d;
    opacity: 0.4;
    animation: fadeIn 2s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 0.4; transform: translateY(0); }
}

/* تحسين العرض على الشاشات الصغيرة */
@media (max-width: 1200px) {
    #licensesTable {
        font-size: 0.75rem;
    }
    
    #licensesTable thead th {
        padding: 0.5rem 0.25rem;
        font-size: 0.7rem;
    }
    
    #licensesTable tbody td {
        padding: 0.4rem 0.25rem;
    }
    
    #licensesTable .btn-group .btn {
        padding: 0.2rem 0.3rem;
        font-size: 0.7rem;
    }
}

@media (max-width: 768px) {
    .table-responsive {
        max-height: 60vh;
    }
    
    #licensesTable {
        font-size: 0.7rem;
    }
    
    #licensesTable .badge {
        font-size: 0.65rem;
        padding: 0.2rem 0.4rem;
    }
}

@keyframes successGlow {
</style>
@endsection 