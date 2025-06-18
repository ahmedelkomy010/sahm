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
                                  إدارة الجودة والرخص  {{ $workOrder->order_number }}
                            </h3>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> عودة الي تفاصيل أمر العمل  
                                </a>
                            </div>
                        </div>
                    </div> 
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-md-3 col-sm-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-hashtag text-primary me-2 fs-5"></i>
                                    <div>
                                        <small class="text-muted d-block">رقم الطلب</small>
                                        <strong class="text-primary fs-6">{{ $workOrder->order_number }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-tools text-warning me-2 fs-5"></i>
                                    <div>
                                        <small class="text-muted d-block">نوع العمل</small>
                                        <strong class="fs-6">{{ $workOrder->work_type }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user text-info me-2 fs-5"></i>
                                    <div>
                                        <small class="text-muted d-block">اسم المشترك</small>
                                        <strong class="fs-6">{{ $workOrder->subscriber_name }}</strong>
                                    </div>
                                </div>
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
                                           
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">رقم شهادة التنسيق</label>
                                        <input type="text" class="form-control" name="coordination_certificate_number" 
                                               value="{{ old('coordination_certificate_number', isset($license) ? $license->coordination_certificate_number : '') }}"
                                               placeholder="أدخل رقم شهادة التنسيق">
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
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-12 text-center">
                                                                                        <div class="d-grid gap-2 d-md-block">
                                                <button type="button" class="btn btn-success btn-lg px-5 shadow-lg me-2" onclick="saveCoordinationSection(true)">
                                                    <i class="fas fa-file-plus me-2"></i>
                                                 حفظ شهادة التنسيق
                                                </button>
                                                
                                            </div>
                                            
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- أزرار التبويبات الرئيسية -->
                        <div class="row mb-4 mt-4">
                            <div class="col-12">
                                <div class="btn-group d-flex flex-wrap justify-content-center" role="group">
                                    <button type="button" class="btn btn-outline-primary tab-btn" data-target="dig-license-section" onclick="showSection('dig-license-section')">
                                        <i class="fas fa-hard-hat me-2"></i>رخصة الحفر
                                    </button>
                                    <button type="button" class="btn btn-outline-primary tab-btn" data-target="lab-section" onclick="showSection('lab-section')">
                                        <i class="fas fa-flask me-2"></i>المختبر
                                    </button>
                                    <button type="button" class="btn btn-outline-primary tab-btn" data-target="evacuations-section" onclick="showSection('evacuations-section')">
                                        <i class="fas fa-truck me-2"></i>الإخلاءات
                                    </button>
                                    <button type="button" class="btn btn-outline-primary tab-btn" data-target="violations-section" onclick="showSection('violations-section')">
                                        <i class="fas fa-exclamation-triangle me-2"></i>المخالفات
                                    </button>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('admin.licenses.store') }}" method="POST" enctype="multipart/form-data" id="licenseForm">
                            @csrf
                            <!-- إضافة حقل مخفي لـ work_order_id -->
                            <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                            
                            <!-- قسم رخص الحفر -->
                            <div id="dig-license-section" class="tab-section" style="display: none;">
                                <div id="digLicenseForm" class="dig-license-form">
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
                                                           value="{{ old('license_number', isset($license) ? $license->license_number : '') }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="license_date" class="form-label"> تاريخ اصدار الرخصة</label>
                                                    <input type="date" class="form-control" id="license_date" name="license_date" 
                                                           value="{{ old('license_date', isset($license) ? $license->license_date : '') }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="license_type" class="form-label">نوع الرخصة</label>
                                                    <select class="form-select" id="license_type" name="license_type">
                                                        <option value="">اختر نوع الرخصة</option>
                                                        <option value="مشروع" {{ old('license_type', isset($license) ? $license->license_type : '') == 'مشروع' ? 'selected' : '' }}>مشروع</option>
                                                        <option value="طوارئ" {{ old('license_type', isset($license) ? $license->license_type : '') == 'طوارئ' ? 'selected' : '' }}>طوارئ</option>
                                                        <option value="عادي" {{ old('license_type', isset($license) ? $license->license_type : '') == 'عادي' ? 'selected' : '' }}>عادي</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="license_value" class="form-label">قيمة الرخصة</label>
                                                    <input type="number" step="0.01" class="form-control" id="license_value" name="license_value" 
                                                           value="{{ old('license_value', isset($license) ? $license->license_value : '') }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="extension_value" class="form-label">قيمة التمديدات</label>
                                                    <input type="number" step="0.01" class="form-control" id="extension_value" name="extension_value" 
                                                           value="{{ old('extension_value', isset($license) ? $license->extension_value : '') }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">طول الحفر</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" class="form-control" name="excavation_length"
                                                               value="{{ old('excavation_length', isset($license) ? $license->excavation_length : '') }}">
                                                        <span class="input-group-text">متر</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">عرض الحفر</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" class="form-control" name="excavation_width"
                                                               value="{{ old('excavation_width', isset($license) ? $license->excavation_width : '') }}">
                                                        <span class="input-group-text">متر</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">عمق الحفر</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" class="form-control" name="excavation_depth"
                                                               value="{{ old('excavation_depth', isset($license) ? $license->excavation_depth : '') }}">
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
                                                    <label class="form-label">تاريخ تفعيل الرخصة</label>
                                                    <input type="date" class="form-control" name="license_start_date" id="dig_license_start_date"
                                                           value="{{ old('license_start_date', isset($license) ? $license->license_start_date : '') }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">تاريخ نهاية الرخصة</label>
                                                    <input type="date" class="form-control" name="license_end_date" id="dig_license_end_date"
                                                           value="{{ old('license_end_date', isset($license) ? $license->license_end_date : '') }}">
                                                </div>
                                                <div class="col-md-4 d-flex align-items-end">
                                                    <div class="alert alert-info w-100 mb-0" id="dig_license_days_counter">
                                                        <i class="fas fa-clock me-2"></i>
                                                        <span id="dig_license_days_text">اختر التواريخ لحساب المدة</span>
                                                    </div>
                                                </div>
                                                
                                                <!-- قسم التمديدات المحسن -->
                                                <div class="col-12">
                                                    <hr class="my-3">
                                                    <h6 class="text-primary mb-3">
                                                        <i class="fas fa-calendar-plus me-2"></i>
                                                        تواريخ التمديدات
                                                    </h6>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <label class="form-label">
                                                        <i class="fas fa-play-circle text-success me-1"></i>
                                                        تاريخ بداية التمديد
                                                    </label>
                                                    <input type="date" class="form-control" name="extension_start_date" id="dig_extension_start_date"
                                                           value="{{ old('extension_start_date', isset($license) ? $license->extension_start_date : '') }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">
                                                        <i class="fas fa-stop-circle text-danger me-1"></i>
                                                        تاريخ نهاية التمديد
                                                    </label>
                                                    <input type="date" class="form-control" name="extension_end_date" id="dig_extension_end_date"
                                                           value="{{ old('extension_end_date', isset($license) ? $license->extension_end_date : '') }}">
                                                </div>
                                                
                                                <!-- عداد أيام التمديد -->
                                                <div class="col-md-4 d-flex align-items-end">
                                                    <div class="alert alert-warning w-100 mb-0" id="dig_extension_days_counter">
                                                        <i class="fas fa-clock me-2"></i>
                                                        <span id="dig_extension_days_text">اختر تواريخ التمديد</span>
                                                    </div>
                                                </div>
                                                
                                                <input type="hidden" name="license_alert_days" value="30">
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
                                                    <label class="form-label">إثبات سداد البنك</label>
                                                    <input type="file" class="form-control" name="payment_proof[]" multiple>
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
                                </div>
                            </div>

                            <!-- قسم المختبر -->
                            <div id="lab-section" class="tab-section" style="display: none;">
                                <div id="labForm" class="lab-form">
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
                                </div>
                            </div>

                            <!-- قسم الإخلاءات -->
                            <div id="evacuations-section" class="tab-section" style="display: none;">
                                <div id="evacuationsForm" class="evacuations-form">
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
                                                            <th rowspan="2" class="align-middle">طول الفسح</th>
                                                            <th rowspan="2" class="align-middle">طول المختبر</th>
                                                            <th rowspan="2" class="align-middle">نوع الشارع</th>
                                                            <th colspan="3" class="text-center">كمية المواد (متر مكعب)</th>
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
                                                            <th>نسبة الأسفلت / التدرج الحبيبي</th>
                                                            <th>تجربة مارشال</th>
                                                            <th>تقييم البلاط / البرودة</th>
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
                                </div>
                            </div>

                            <!-- قسم المخالفات -->
                            <div id="violations-section" class="tab-section" style="display: none;">
                                <div id="violationsForm" class="violations-form">
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
                                                    <label class="form-label">قيمة المخالفة</label>
                                                    <input type="number" step="0.01" class="form-control" name="violation_license_value"
                                                           value="{{ old('violation_license_value') }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">تاريخ المخالفة</label>
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
                                                    <label class="form-label">رقم فاتورة السداد </label>
                                                    <input type="text" class="form-control" name="violation_payment_number"
                                                           value="{{ old('violation_payment_number') }}">
                                                    </div>
                                                <div class="col-6">
                                                    <label class="form-label">المتسبب</label>
                                                    <input type="text" class="form-control" name="violation_cause"
                                                           value="{{ old('violation_cause') }}">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label">وصف المخالفة </label>
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
                                    <div id="notesForm" class="notes-form">
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
                                    </div>
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
                                    <div class="d-flex gap-2 flex-wrap">
                                        <span class="badge bg-primary">
                                            <i class="fas fa-list-ol me-1"></i>
                                            إجمالي الرخص: <span id="totalLicensesCount">{{ $workOrder->licenses->count() ?? 0 }}</span>
                                        </span>

                                        <button type="button" class="btn btn-info btn-sm" onclick="exportLicensesTable()">
                                            <i class="fas fa-download me-1"></i>
                                            تصدير
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
                                                <th style="min-width: 120px;">رقم شهادة التنسيق</th>
                                                <th style="min-width: 120px;">تاريخ البداية</th>
                                                <th style="min-width: 120px;">تاريخ النهاية</th>
                                                <th style="min-width: 100px;">أبعاد الحفر</th>
                                                <!-- التمديدات -->
                                                <th style="min-width: 120px;">بداية التمديد</th>
                                                <th style="min-width: 120px;">نهاية التمديد</th>
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
                                                    <td>
                                                        <span class="text-info fw-bold">{{ $license->coordination_certificate_number ?? 'غير محدد' }}</span>
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
                                                    <td>{{ $license->extension_start_date ? $license->extension_start_date->format('Y-m-d') : 'غير محدد' }}</td>
                                                    <td>{{ $license->extension_end_date ? $license->extension_end_date->format('Y-m-d') : 'غير محدد' }}</td>
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
                                                    <td colspan="32" class="text-center py-4">
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
        // متغير عام لتتبع الرخصة الحالية
        let currentLicenseId = null;
        let isNewLicenseCreated = false;
        let latestLicenseId = null; // متغير لتتبع آخر رخصة تم إنشاؤها
        
        // دالة لتحديد آخر رخصة تم إنشاؤها (تعمل تلقائياً)
        function updateLatestLicense() {
            const licensesData = @json($workOrder->licenses ?? []);
            console.log('All licenses:', licensesData);
            
            if (licensesData && licensesData.length > 0) {
                // ترتيب الرخص حسب تاريخ الإنشاء (الأحدث أولاً)
                const sortedLicenses = licensesData.sort((a, b) => {
                    const dateA = new Date(a.created_at || a.dig_license_date);
                    const dateB = new Date(b.created_at || b.dig_license_date);
                    return dateB - dateA;
                });
                
                latestLicenseId = sortedLicenses[0].id;
                console.log('Latest license ID set to:', latestLicenseId);
                
                // تحديث الرسالة التوضيحية
                const messageElement = document.getElementById('latestLicenseMessage');
                if (messageElement) {
                    messageElement.innerHTML = `سيتم حفظ البيانات في آخر رخصة تم إنشاؤها (رقم الرخصة: #${latestLicenseId}).`;
                }
                
                // إذا لم تكن هناك رخصة نشطة، استخدم آخر رخصة
                if (!currentLicenseId) {
                    currentLicenseId = latestLicenseId;
                    isNewLicenseCreated = true;
                    updateCurrentLicenseDisplay(currentLicenseId);
                    console.log('Set current license to latest:', currentLicenseId);
                }
                
                // عرض البيانات من آخر رخصة في جميع التبويبات
                loadLatestLicenseDataToForms();
            } else {
                // إذا لم توجد رخص
                const messageElement = document.getElementById('latestLicenseMessage');
                if (messageElement) {
                    messageElement.innerHTML = 'لا توجد رخص محفوظة بعد. ابدأ بإنشاء رخصة جديدة.';
                }
            }
        }
        
        // دالة لتحميل بيانات آخر رخصة إلى النماذج
        function loadLatestLicenseDataToForms() {
            if (!latestLicenseId) {
                console.log('No latest license ID available');
                return;
            }
            
            const licensesData = @json($workOrder->licenses ?? []);
            const latestLicense = licensesData.find(license => license.id === latestLicenseId);
            
            if (!latestLicense) {
                console.log('Latest license data not found');
                return;
            }
            
            console.log('Loading data from latest license:', latestLicense);
            
            // تحميل بيانات شهادة التنسيق
            loadCoordinationData(latestLicense);
            
            // تحميل بيانات رخصة الحفر
            loadDigLicenseData(latestLicense);
            
            // تحميل بيانات المختبر
            loadLabData(latestLicense);
            
            // تحميل بيانات الإخلاءات
            loadEvacuationsData(latestLicense);
            
            // تحميل بيانات المخالفات
            loadViolationsData(latestLicense);
            
            // تحميل بيانات الملاحظات
            loadNotesData(latestLicense);
            
            showNotification(`تم تحميل البيانات بنجاح من الرخصة #${latestLicenseId} (آخر رخصة تم إنشاؤها)`, 'success');
        }
        
        // دالة تحميل بيانات شهادة التنسيق
        function loadCoordinationData(license) {
            const fields = [
                'coordination_certificate_number',
                'coordination_certificate_date',
                'coordination_valid_until',
                'coordination_authority',
                'coordination_notes',
                'coordination_restriction_type',
                'coordination_restriction_authority'
            ];
            
            fields.forEach(field => {
                const element = document.getElementById(field);
                if (element && license[field]) {
                    if (element.type === 'checkbox') {
                        element.checked = license[field] === '1' || license[field] === true;
                    } else {
                        element.value = license[field];
                    }
                    console.log(`Loaded coordination ${field}:`, license[field]);
                }
            });
            
            // معالجة نوع القيود
            const restrictionType = document.getElementById('coordination_restriction_type');
            const restrictionField = document.getElementById('restriction_authority_field');
            if (restrictionType && license.coordination_restriction_type) {
                restrictionType.value = license.coordination_restriction_type;
                if (license.coordination_restriction_type === 'yes' && restrictionField) {
                    restrictionField.style.display = 'block';
                }
            }
        }
        
        // دالة تحميل بيانات رخصة الحفر
        function loadDigLicenseData(license) {
            const fields = [
                'dig_license_number',
                'dig_license_date',
                'dig_license_start_date',
                'dig_license_end_date',
                'dig_extension_start_date',
                'dig_extension_end_date',
                'dig_license_authority',
                'dig_license_notes'
            ];
            
            fields.forEach(field => {
                const element = document.getElementById(field);
                if (element && license[field]) {
                    element.value = license[field];
                    console.log(`Loaded dig license ${field}:`, license[field]);
                }
            });
            
            // إعادة حساب الأيام بعد تحميل التواريخ
            setTimeout(() => {
                    if (typeof calculateLicenseDays === 'function') {
                        calculateLicenseDays();
                    }
                    if (typeof calculateExtensionDays === 'function') {
                        calculateExtensionDays();
                    }
            }, 100);
        }
        
        // دالة تحميل بيانات المختبر
        function loadLabData(license) {
            // تحميل حقول المختبر العادية
            const labFields = [
                'lab_sample_number',
                'lab_sample_date',
                'lab_test_type',
                'lab_results',
                'lab_notes'
            ];
            
            labFields.forEach(field => {
                const element = document.getElementById(field);
                if (element && license[field]) {
                    element.value = license[field];
                    console.log(`Loaded lab ${field}:`, license[field]);
                }
            });
            
            // تحميل بيانات جداول المختبر
            if (license.lab_table1_data) {
                try {
                    const table1Data = JSON.parse(license.lab_table1_data);
                    loadTableData('labTable1Body', table1Data, 'lab_table1');
                } catch (e) {
                    console.error('Error loading lab table 1 data:', e);
                }
            }
            
            if (license.lab_table2_data) {
                try {
                    const table2Data = JSON.parse(license.lab_table2_data);
                    loadTableData('labTable2Body', table2Data, 'lab_table2');
                } catch (e) {
                    console.error('Error loading lab table 2 data:', e);
                }
            }
        }
        
        // دالة تحميل بيانات الإخلاءات
        function loadEvacuationsData(license) {
            const evacFields = [
                'evacuation_permit_number',
                'evacuation_permit_date',
                'evacuation_location',
                'evacuation_notes'
            ];
            
            evacFields.forEach(field => {
                const element = document.getElementById(field);
                if (element && license[field]) {
                    element.value = license[field];
                    console.log(`Loaded evacuation ${field}:`, license[field]);
                }
            });
            
            // تحميل بيانات جداول الإخلاءات
            if (license.evac_table1_data) {
                try {
                    const evacTable1Data = JSON.parse(license.evac_table1_data);
                    loadTableData('evacTable1Body', evacTable1Data, 'evac_table1');
                } catch (e) {
                    console.error('Error loading evacuation table 1 data:', e);
                }
            }
            
            if (license.evac_table2_data) {
                try {
                    const evacTable2Data = JSON.parse(license.evac_table2_data);
                    loadTableData('evacTable2Body', evacTable2Data, 'evac_table2');
                } catch (e) {
                    console.error('Error loading evacuation table 2 data:', e);
                }
            }
        }
        
        // دالة تحميل بيانات المخالفات
        function loadViolationsData(license) {
            const violationFields = [
                'violation_number',
                'violation_date',
                'violation_type',
                'violation_description',
                'violation_penalty',
                'violation_status',
                'violation_notes'
            ];
            
            violationFields.forEach(field => {
                const element = document.getElementById(field);
                if (element && license[field]) {
                    if (element.type === 'checkbox') {
                        element.checked = license[field] === '1' || license[field] === true;
                    } else {
                        element.value = license[field];
                    }
                    console.log(`Loaded violation ${field}:`, license[field]);
                }
            });
        }
        
        // دالة تحميل بيانات الملاحظات
        function loadNotesData(license) {
            const notesFields = [
                'additional_notes',
                'special_requirements',
                'safety_measures',
                'environmental_conditions',
                'final_remarks'
            ];
            
            notesFields.forEach(field => {
                const element = document.getElementById(field);
                if (element && license[field]) {
                    element.value = license[field];
                    console.log(`Loaded notes ${field}:`, license[field]);
                }
            });
        }
        
        // دالة مساعدة لتحميل بيانات الجداول
        function loadTableData(tableBodyId, data, tablePrefix) {
            const tbody = document.getElementById(tableBodyId);
            if (!tbody || !data || !Array.isArray(data)) {
                return;
            }
            
            // مسح الجدول الحالي
            tbody.innerHTML = '';
            
            // إضافة البيانات
            data.forEach((rowData, index) => {
                if (tableBodyId === 'evacTable1Body') {
                    addRowToEvacTable1();
                } else if (tableBodyId === 'evacTable2Body') {
                    addRowToEvacTable2();
                } else if (tableBodyId === 'labTable1Body') {
                    addRowToLabTable1();
                } else if (tableBodyId === 'labTable2Body') {
                    addRowToLabTable2();
                }
                
                // ملء البيانات في الصف الجديد
                const newRow = tbody.lastElementChild;
                if (newRow) {
                    const inputs = newRow.querySelectorAll('input, select, textarea');
                    inputs.forEach(input => {
                        if (input.name && input.name.includes('[')) {
                            const fieldName = input.name.split('[').pop().replace(']', '');
                            if (rowData[fieldName] !== undefined) {
                                if (input.type === 'checkbox') {
                                    input.checked = rowData[fieldName] === '1' || rowData[fieldName] === true;
                                } else {
                                    input.value = rowData[fieldName];
                                }
                            }
                        }
                    });
                }
            });
            
            console.log(`Loaded ${data.length} rows to ${tableBodyId}`);
        }
        
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
        async function saveCoordinationSection(forceNew = true) {
            console.log('saveCoordinationSection called with forceNew:', forceNew);
            
            // استخدام createCompleteFormData لحفظ جميع التبويبات في رخصة واحدة
            const formData = createCompleteFormData();
            
            // تعديل نوع القسم ليشمل جميع البيانات
            formData.set('section_type', 'complete_license_with_coordination');
            
            // التحكم في إنشاء رخصة جديدة
            if (forceNew) {
                formData.append('force_new', '1');
                console.log('Will create NEW license with ALL TABS DATA (forceNew = true)');
            } else {
                // تحديث آخر رخصة إذا كانت موجودة
                if (latestLicenseId) {
                    formData.append('current_license_id', latestLicenseId);
                    console.log('Will update existing license with all tabs data:', latestLicenseId);
                } else {
                    formData.append('force_new', '1');
                    console.log('Will create new license with all tabs data (no existing license found)');
                }
            }
            
            // التأكد من إضافة CSRF token
            formData.set('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            // Debug: طباعة بيانات النموذج
            console.log('=== FormData Contents for Coordination ===');
            for (let pair of formData.entries()) {
                console.log('FormData:', pair[0], '=', pair[1]);
            }
            
            try {
                const loadingMessage = forceNew ? 
                    'جاري إنشاء رخصة جديدة شاملة مع جميع بيانات التبويبات...' : 
                    'جاري حفظ جميع بيانات التبويبات في الرخصة الحالية...';
                    
                showGlobalLoadingState(loadingMessage);
                
                const response = await fetch('{{ route("admin.licenses.save-section") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                
                console.log('Response status:', response.status);
                
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Response not OK:', response.statusText, errorText);
                    throw new Error(`HTTP error! status: ${response.status} - ${errorText}`);
                }
                
                const result = await response.json();
                console.log('Response result:', result);
                
                if (result.success) {
                    // طباعة معلومات debug
                    console.log('Coordination section - Server response debug info:', result.debug_info);
                    
                    // رسالة النجاح حسب نوع العملية
                    const successMessage = forceNew ? 
                        'تم إنشاء رخصة جديدة شاملة بجميع بيانات التبويبات بنجاح!' + 
                        (result.license_id ? ' (رقم الرخصة: ' + result.license_id + ')' : '') :
                        'تم حفظ جميع بيانات التبويبات في الرخصة الحالية بنجاح!' + 
                        (result.license_id ? ' (رقم الرخصة: ' + result.license_id + ')' : '');
                    
                    // تحديث الرخصة الحالية باستخدام latest_license_id من الخادم
                    if (result.latest_license_id) {
                        currentLicenseId = result.latest_license_id;
                        latestLicenseId = result.latest_license_id; // تحديث آخر رخصة
                        isNewLicenseCreated = result.is_new || false;
                        updateCurrentLicenseDisplay(result.latest_license_id);
                        
                        console.log('Updated license IDs from coordination (using latest_license_id):', {
                            currentLicenseId,
                            latestLicenseId,
                            isNewLicenseCreated,
                            serverResponse: result.debug_info
                        });
                    }
                    
                    showSuccessMessage(successMessage);
                    
                    // تحديث عداد الرخص
                    const totalCount = document.getElementById('totalLicensesCount');
                    if (totalCount && result.total_licenses) {
                        totalCount.textContent = result.total_licenses;
                    }
                    
                    // تحديث الجدول بعد ثانية واحدة
                    setTimeout(() => {
                        refreshLicensesTable();
                    }, 1500);
                    
                    // إظهار رسالة توجيهية للمستخدم
                    setTimeout(() => {
                        const guideMessage = forceNew ? 
                            'تم إنشاء رخصة جديدة شاملة. جميع بيانات التبويبات محفوظة في هذه الرخصة.' :
                            'تم حفظ جميع بيانات التبويبات بنجاح.';
                        showSuccessMessage(guideMessage);
                    }, 2000);
                    
                } else {
                    console.error('Save failed:', result.message);
                    showErrorMessage('خطأ في حفظ بيانات الرخصة الشاملة: ' + (result.message || 'خطأ غير معروف'));
                }
            } catch (error) {
                console.error('Error saving complete license:', error);
                showErrorMessage('حدث خطأ أثناء حفظ بيانات الرخصة الشاملة: ' + error.message);
            } finally {
                hideGlobalLoadingState();
            }
        }

        async function saveDigLicenseSection(forceNew = false) {
            console.log('=== DEBUG: saveDigLicenseSection started ===');
            console.log('forceNew:', forceNew);
            console.log('currentLicenseId:', currentLicenseId);
            console.log('latestLicenseId:', latestLicenseId);
            console.log('isNewLicenseCreated:', isNewLicenseCreated);
            
            // دائماً الحفظ في آخر رخصة تم إنشاؤها
            const useLatestLicense = latestLicenseId ? true : false; // true إذا كان هناك آخر رخصة
            console.log('useLatestLicense:', useLatestLicense, 'latestLicenseId:', latestLicenseId);
            
            // أولاً، تأكد من أن قسم رخص الحفر مرئي
            const formSection = document.getElementById('dig-license-section');
            if (formSection && formSection.style.display === 'none') {
                console.log('Making dig license section visible...');
                formSection.style.display = 'block';
                
                // تفعيل زر التبويب
                const tabButton = document.querySelector('.tab-btn[data-target="dig-license-section"]');
                if (tabButton) {
                    // إزالة التفعيل من جميع الأزرار
                    document.querySelectorAll('.tab-btn').forEach(btn => {
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-outline-primary');
                    });
                    
                    // تفعيل زر رخص الحفر
                    tabButton.classList.remove('btn-outline-primary');
                    tabButton.classList.add('btn-primary');
                    
                    // إخفاء الأقسام الأخرى
                    document.querySelectorAll('.tab-section').forEach(section => {
                        section.style.display = 'none';
                    });
                    
                    // إظهار قسم رخص الحفر
                    formSection.style.display = 'block';
                }
            }
            
            // استخدام النموذج الرئيسي
            const mainForm = document.getElementById('licenseForm');
            if (!mainForm) {
                console.error('Main licenseForm not found!');
                showErrorMessage('لم يتم العثور على النموذج الرئيسي');
                return;
            }
            
            // التأكد من وجود قسم رخص الحفر
            const digSection = document.getElementById('digLicenseForm');
            if (!digSection) {
                console.error('digLicenseForm section not found!');
                showErrorMessage('لم يتم العثور على قسم رخص الحفر');
                return;
            }
            
            console.log('Main form found successfully:', mainForm);
            console.log('Dig section found successfully:', digSection);
            console.log('Form section display style:', formSection ? formSection.style.display : 'not found');
            
            // إنشاء FormData من جميع العناصر في قسم رخص الحفر
            const formData = new FormData();
            
            // إضافة الحقول المطلوبة
            const workOrderIdField = mainForm.querySelector('input[name="work_order_id"]');
            if (workOrderIdField) {
                formData.append('work_order_id', workOrderIdField.value);
            }
            
            formData.append('section_type', 'dig_license');
            
            // جمع البيانات من جميع العناصر في قسم رخص الحفر
            const inputs = digSection.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.type === 'file') {
                    if (input.files.length > 0) {
                        if (input.multiple) {
                            Array.from(input.files).forEach(file => {
                                formData.append(input.name, file);
                            });
                        } else {
                            formData.append(input.name, input.files[0]);
                        }
                    }
                } else if (input.type === 'checkbox') {
                    if (input.checked) {
                        formData.append(input.name, input.value);
                    }
                } else if (input.type === 'radio') {
                    if (input.checked) {
                        formData.append(input.name, input.value);
                    }
                } else if (input.value && input.value.trim() !== '') {
                    formData.append(input.name, input.value);
                }
            });
            
            console.log('Form data created');
            
            // Debug: طباعة جميع بيانات النموذج بشكل منظم
            console.log('=== FormData Contents ===');
            let hasData = false;
            for (let pair of formData.entries()) {
                console.log('FormData:', pair[0], '=', pair[1]);
                if (pair[1] && pair[1] !== '') {
                    hasData = true;
                }
            }
            console.log('Form has data:', hasData);
            
            // الحفظ في آخر رخصة أو إنشاء جديدة
            if (useLatestLicense && latestLicenseId) {
                formData.append('current_license_id', latestLicenseId);
                console.log('Will update latest license:', latestLicenseId);
            } else {
                formData.append('force_new', '1');
                console.log('Will create new license (no latest license found)');
            }
            
            try {
                const loadingMessage = useLatestLicense ? 
                    'جاري حفظ رخص الحفر في آخر رخصة...' : 
                    'جاري إنشاء رخصة جديدة مع رخص الحفر...';
                showLoadingState('digLicenseForm', loadingMessage);
                
                const response = await fetch('{{ route("admin.licenses.save-section") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                });
                
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                if (!response.ok) {
                    console.error('Response not OK:', response.statusText);
                    const errorText = await response.text();
                    console.error('Error response body:', errorText);
                    throw new Error(`HTTP error! status: ${response.status} - ${errorText}`);
                }
                
                const result = await response.json();
                console.log('Response result:', result);
                
                if (result.success) {
                    // طباعة معلومات debug
                    console.log('Server response debug info:', result.debug_info);
                    
                    // الحفظ في آخر رخصة أو إنشاء جديدة
                    const successMessage = useLatestLicense ? 
                        'تم حفظ رخص الحفر في آخر رخصة بنجاح!' + 
                        (result.license_id ? ' (رقم الرخصة: ' + result.license_id + ')' : '') :
                        'تم إنشاء رخصة جديدة مع بيانات الحفر بنجاح!' + 
                        (result.license_id ? ' (رقم الرخصة: ' + result.license_id + ')' : '');
                    
                    showSuccessMessage(successMessage);
                    
                    // تحديث معرفات الرخصة باستخدام latest_license_id من الخادم
                    if (result.latest_license_id) {
                        currentLicenseId = result.latest_license_id;
                        latestLicenseId = result.latest_license_id; // تحديث آخر رخصة
                        isNewLicenseCreated = result.is_new || false;
                        updateCurrentLicenseDisplay(result.latest_license_id);
                        
                        console.log('Updated license IDs from dig license (using latest_license_id):', {
                            currentLicenseId,
                            latestLicenseId,
                            isNewLicenseCreated,
                            serverResponse: result.debug_info
                        });
                    }
                    
                    if (result.refresh_table) {
                        setTimeout(() => refreshLicensesTable(), 1000);
                    }
                } else {
                    console.error('Save failed:', result.message);
                    showErrorMessage('خطأ في حفظ رخص الحفر: ' + (result.message || 'خطأ غير معروف'));
                }
            } catch (error) {
                console.error('Error saving dig license section:', error);
                console.error('Error stack:', error.stack);
                showErrorMessage('حدث خطأ أثناء حفظ رخص الحفر: ' + error.message);
            } finally {
                hideLoadingState('digLicenseForm', 'حفظ رخص الحفر');
                console.log('=== DEBUG: saveDigLicenseSection finished ===');
            }
        }
        
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
                <td><input type="text" class="form-control form-control-sm" name="evac_table1[${rowCount}][soil_check]" placeholder="تدقيق التربة"></td>
                <td><input type="text" step="0.01" class="form-control form-control-sm" name="evac_table1[${rowCount}][mc1_check]" placeholder="تدقيق MC1"></td>
                <td><input type="text" step="0.01" class="form-control form-control-sm" name="evac_table1[${rowCount}][asphalt_check]" placeholder="تدقيق أسفلت"></td>
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

        async function saveLabSection(forceNew = false) {
            console.log('=== DEBUG: saveLabSection started ===');
            console.log('currentLicenseId:', currentLicenseId);
            console.log('latestLicenseId:', latestLicenseId);
            console.log('isNewLicenseCreated:', isNewLicenseCreated);
            
            // دائماً الحفظ في آخر رخصة تم إنشاؤها
            const useLatestLicense = latestLicenseId ? true : false; // true إذا كان هناك آخر رخصة
            console.log('useLatestLicense:', useLatestLicense, 'latestLicenseId:', latestLicenseId);
            
            const mainForm = document.getElementById('licenseForm');
            if (!mainForm) {
                console.error('Main licenseForm not found!');
                showErrorMessage('لم يتم العثور على النموذج الرئيسي');
                return;
            }
            
            const labSection = document.getElementById('labForm');
            if (!labSection) {
                console.error('labForm section not found!');
                showErrorMessage('لم يتم العثور على قسم المختبر');
                return;
            }
            
            // إنشاء FormData من جميع العناصر في قسم المختبر
            const formData = new FormData();
            
            // إضافة الحقول المطلوبة
            const workOrderIdField = mainForm.querySelector('input[name="work_order_id"]');
            if (workOrderIdField) {
                formData.append('work_order_id', workOrderIdField.value);
            }
            
            formData.append('section_type', 'lab');
            
            // جمع البيانات من جميع العناصر في قسم المختبر
            const inputs = labSection.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.type === 'file') {
                    if (input.files.length > 0) {
                        if (input.multiple) {
                            Array.from(input.files).forEach(file => {
                                formData.append(input.name, file);
                            });
                        } else {
                            formData.append(input.name, input.files[0]);
                        }
                    }
                } else if (input.type === 'checkbox') {
                    if (input.checked) {
                        formData.append(input.name, input.value);
                    }
                } else if (input.type === 'radio') {
                    if (input.checked) {
                        formData.append(input.name, input.value);
                    }
                } else if (input.value && input.value.trim() !== '') {
                    formData.append(input.name, input.value);
                }
            });
            
            // الحفظ في آخر رخصة أو إنشاء جديدة
            if (useLatestLicense && latestLicenseId) {
                formData.append('current_license_id', latestLicenseId);
                console.log('Will update latest license:', latestLicenseId);
            } else {
                formData.append('force_new', '1');
                console.log('Will create new license (no latest license found)');
            }
            
            // معالجة بيانات جداول المختبر
            const labTable1Data = collectTableData('labTable1Body');
            const labTable2Data = collectTableData('labTable2Body');
            
            if (labTable1Data.length > 0) {
                formData.append('lab_table1_data', JSON.stringify(labTable1Data));
            }
            
            if (labTable2Data.length > 0) {
                formData.append('lab_table2_data', JSON.stringify(labTable2Data));
            }
            
            try {
                const loadingMessage = useLatestLicense ? 
                    'جاري حفظ بيانات المختبر في آخر رخصة...' : 
                    'جاري إنشاء رخصة جديدة مع بيانات المختبر...';
                showLoadingState('labForm', loadingMessage);
                
                const response = await fetch('{{ route("admin.licenses.save-section") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // الحفظ في آخر رخصة أو إنشاء جديدة
                    // طباعة معلومات debug
                    console.log('Lab section - Server response debug info:', result.debug_info);
                    
                    const successMessage = useLatestLicense ? 
                        'تم حفظ بيانات المختبر في آخر رخصة بنجاح!' + 
                        (result.license_id ? ' (رقم الرخصة: ' + result.license_id + ')' : '') :
                        'تم إنشاء رخصة جديدة مع بيانات المختبر بنجاح!' + 
                        (result.license_id ? ' (رقم الرخصة: ' + result.license_id + ')' : '');
                    
                    showSuccessMessage(successMessage);
                    
                    // تحديث معرفات الرخصة (دائماً رخصة جديدة)
                    if (result.license_id) {
                        // تحديث الرخصة الحالية باستخدام latest_license_id من الخادم
                        if (result.latest_license_id) {
                            currentLicenseId = result.latest_license_id;
                            latestLicenseId = result.latest_license_id; // تحديث آخر رخصة
                            isNewLicenseCreated = result.is_new || false;
                            updateCurrentLicenseDisplay(result.latest_license_id);
                            
                            console.log('Updated license IDs from lab section (using latest_license_id):', {
                                currentLicenseId,
                                latestLicenseId,
                                isNewLicenseCreated,
                                serverResponse: result.debug_info
                            });
                        }
                    }
                    
                    if (result.refresh_table) {
                        setTimeout(() => refreshLicensesTable(), 1000);
                    }
                } else {
                    showErrorMessage('خطأ في حفظ بيانات المختبر: ' + (result.message || 'خطأ غير معروف'));
                }
            } catch (error) {
                console.error('Error saving lab section:', error);
                showErrorMessage('حدث خطأ أثناء حفظ بيانات المختبر');
            } finally {
                hideLoadingState('labForm', 'حفظ بيانات المختبر');
            }
        }

        async function saveViolationsSection(forceNew = false) {
            console.log('=== DEBUG: saveViolationsSection started ===');
            console.log('currentLicenseId:', currentLicenseId);
            console.log('latestLicenseId:', latestLicenseId);
            console.log('isNewLicenseCreated:', isNewLicenseCreated);
            
            // دائماً الحفظ في آخر رخصة تم إنشاؤها
            const useLatestLicense = latestLicenseId ? true : false; // true إذا كان هناك آخر رخصة
            console.log('useLatestLicense:', useLatestLicense, 'latestLicenseId:', latestLicenseId);
            
            const mainForm = document.getElementById('licenseForm');
            if (!mainForm) {
                console.error('Main licenseForm not found!');
                showErrorMessage('لم يتم العثور على النموذج الرئيسي');
                return;
            }
            
            const violationsSection = document.getElementById('violationsForm');
            if (!violationsSection) {
                console.error('violationsForm section not found!');
                showErrorMessage('لم يتم العثور على قسم المخالفات');
                return;
            }
            
            // إنشاء FormData من جميع العناصر في قسم المخالفات
            const formData = new FormData();
            
            // إضافة الحقول المطلوبة
            const workOrderIdField = mainForm.querySelector('input[name="work_order_id"]');
            if (workOrderIdField) {
                formData.append('work_order_id', workOrderIdField.value);
            }
            
            formData.append('section_type', 'violations');
            
            // جمع البيانات من جميع العناصر في قسم المخالفات
            const inputs = violationsSection.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.type === 'file') {
                    if (input.files.length > 0) {
                        if (input.multiple) {
                            Array.from(input.files).forEach(file => {
                                formData.append(input.name, file);
                            });
                        } else {
                            formData.append(input.name, input.files[0]);
                        }
                    }
                } else if (input.type === 'checkbox') {
                    if (input.checked) {
                        formData.append(input.name, input.value);
                    }
                } else if (input.type === 'radio') {
                    if (input.checked) {
                        formData.append(input.name, input.value);
                    }
                } else if (input.value && input.value.trim() !== '') {
                    formData.append(input.name, input.value);
                }
            });
            
            // الحفظ في آخر رخصة أو إنشاء جديدة
            if (useLatestLicense && latestLicenseId) {
                formData.append('current_license_id', latestLicenseId);
                console.log('Will update latest license:', latestLicenseId);
            } else {
                formData.append('force_new', '1');
                console.log('Will create new license (no latest license found)');
            }
            
            try {
                const loadingMessage = useLatestLicense ? 
                    'جاري حفظ بيانات المخالفات في آخر رخصة...' : 
                    'جاري إنشاء رخصة جديدة مع بيانات المخالفات...';
                showLoadingState('violationsForm', loadingMessage);
                
                const response = await fetch('{{ route("admin.licenses.save-section") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // الحفظ في آخر رخصة أو إنشاء جديدة
                    const successMessage = useLatestLicense ? 
                        'تم حفظ بيانات المخالفات في آخر رخصة بنجاح!' + 
                        (result.license_id ? ' (رقم الرخصة: ' + result.license_id + ')' : '') :
                        'تم إنشاء رخصة جديدة مع بيانات المخالفات بنجاح!' + 
                        (result.license_id ? ' (رقم الرخصة: ' + result.license_id + ')' : '');
                    
                    showSuccessMessage(successMessage);
                    
                    // تحديث معرفات الرخصة (دائماً رخصة جديدة)
                    if (result.license_id) {
                        currentLicenseId = result.license_id;
                        isNewLicenseCreated = true;
                        latestLicenseId = result.license_id; // تحديث آخر رخصة
                        updateCurrentLicenseDisplay(result.license_id);
                        
                        console.log('Updated license IDs from violations section:', {
                            currentLicenseId,
                            latestLicenseId,
                            isNewLicenseCreated
                        });
                    }
                    
                    if (result.refresh_table) {
                        setTimeout(() => refreshLicensesTable(), 1000);
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

        async function saveNotesSection(forceNew = false) {
            console.log('=== DEBUG: saveNotesSection started ===');
            console.log('currentLicenseId:', currentLicenseId);
            console.log('latestLicenseId:', latestLicenseId);
            console.log('isNewLicenseCreated:', isNewLicenseCreated);
            
            // دائماً الحفظ في آخر رخصة تم إنشاؤها
            const useLatestLicense = latestLicenseId ? true : false; // true إذا كان هناك آخر رخصة
            console.log('useLatestLicense:', useLatestLicense, 'latestLicenseId:', latestLicenseId);
            
            const mainForm = document.getElementById('licenseForm');
            if (!mainForm) {
                console.error('Main licenseForm not found!');
                showErrorMessage('لم يتم العثور على النموذج الرئيسي');
                return;
            }
            
            const notesSection = document.getElementById('notesForm');
            if (!notesSection) {
                console.error('notesForm section not found!');
                showErrorMessage('لم يتم العثور على قسم الملاحظات');
                return;
            }
            
            // إنشاء FormData من جميع العناصر في قسم الملاحظات
            const formData = new FormData();
            
            // إضافة الحقول المطلوبة
            const workOrderIdField = mainForm.querySelector('input[name="work_order_id"]');
            if (workOrderIdField) {
                formData.append('work_order_id', workOrderIdField.value);
            }
            
            formData.append('section_type', 'notes');
            
            // جمع البيانات من جميع العناصر في قسم الملاحظات
            const inputs = notesSection.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.type === 'file') {
                    if (input.files.length > 0) {
                        if (input.multiple) {
                            Array.from(input.files).forEach(file => {
                                formData.append(input.name, file);
                            });
                        } else {
                            formData.append(input.name, input.files[0]);
                        }
                    }
                } else if (input.type === 'checkbox') {
                    if (input.checked) {
                        formData.append(input.name, input.value);
                    }
                } else if (input.type === 'radio') {
                    if (input.checked) {
                        formData.append(input.name, input.value);
                    }
                } else if (input.value && input.value.trim() !== '') {
                    formData.append(input.name, input.value);
                }
            });
            
            // الحفظ في آخر رخصة أو إنشاء جديدة
            if (useLatestLicense && latestLicenseId) {
                formData.append('current_license_id', latestLicenseId);
                console.log('Will update latest license:', latestLicenseId);
            } else {
                formData.append('force_new', '1');
                console.log('Will create new license (no latest license found)');
            }
            
            try {
                const loadingMessage = useLatestLicense ? 
                    'جاري حفظ الملاحظات في آخر رخصة...' : 
                    'جاري إنشاء رخصة جديدة مع الملاحظات...';
                showLoadingState('notesForm', loadingMessage);
                
                const response = await fetch('{{ route("admin.licenses.save-section") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // طباعة معلومات debug
                    console.log('Notes section - Server response debug info:', result.debug_info);
                    
                    const successMessage = useLatestLicense ? 
                        'تم حفظ الملاحظات في آخر رخصة بنجاح!' + 
                        (result.license_id ? ' (رقم الرخصة: ' + result.license_id + ')' : '') :
                        'تم إنشاء رخصة جديدة مع الملاحظات بنجاح!' + 
                        (result.license_id ? ' (رقم الرخصة: ' + result.license_id + ')' : '');
                    
                    showSuccessMessage(successMessage);
                    
                    // تحديث معرف آخر رخصة تم إنشاؤها باستخدام latest_license_id من الخادم
                    if (result.latest_license_id) {
                        currentLicenseId = result.latest_license_id;
                        latestLicenseId = result.latest_license_id; // تحديث آخر رخصة
                        isNewLicenseCreated = result.is_new || false;
                        updateCurrentLicenseDisplay(result.latest_license_id);
                        
                        console.log('Updated license IDs from notes section (using latest_license_id):', {
                            currentLicenseId,
                            latestLicenseId,
                            isNewLicenseCreated,
                            serverResponse: result.debug_info
                        });
                    }
                    
                    if (result.refresh_table) {
                        setTimeout(() => refreshLicensesTable(), 1000);
                    }
                } else {
                    showErrorMessage('خطأ في حفظ الملاحظات: ' + (result.message || 'خطأ غير معروف'));
                }
            } catch (error) {
                console.error('Error saving notes section:', error);
                showErrorMessage('حدث خطأ أثناء حفظ الملاحظات');
            } finally {
                hideLoadingState('notesForm', 'حفظ الملاحظات');
            }
        }

        // وظائف مساعدة لحالة التحميل والرسائل
        function showLoadingState(formId, message) {
            console.log('showLoadingState called for form:', formId);
            const form = document.getElementById(formId);
            if (!form) {
                console.error('Form not found:', formId);
                return;
            }
            
            let button = null;
            
            // محاولة العثور على الزر بطرق مختلفة
            if (formId === 'digLicenseForm') {
                button = form.querySelector('button[onclick*="saveDigLicenseSection"]');
            } else if (formId === 'coordinationForm') {
                button = form.querySelector('button[onclick*="saveCoordinationSection"]');
            } else if (formId === 'labForm') {
                button = form.querySelector('button[onclick*="saveLabSection"]');
            } else if (formId === 'evacuationsForm') {
                button = form.querySelector('button[onclick*="saveEvacuationsSection"]');
            } else if (formId === 'violationsForm') {
                button = form.querySelector('button[onclick*="saveViolationsSection"]');
            } else if (formId === 'notesForm') {
                button = form.querySelector('button[onclick*="saveNotesSection"]');
            }
            
            // إذا لم نجد الزر بالطريقة المخصصة، استخدم الطريقة العامة
            if (!button) {
                button = form.querySelector('button[onclick*="save"], button[type="button"]:last-child, button[type="submit"]');
            }
            
            console.log('Button found:', button);
            
            if (button) {
                button.disabled = true;
                button.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i>${message}`;
                console.log('Button loading state set');
            } else {
                console.error('No button found in form:', formId);
            }
        }

        function hideLoadingState(formId, originalText) {
            console.log('hideLoadingState called for form:', formId);
            const form = document.getElementById(formId);
            if (!form) {
                console.error('Form not found:', formId);
                return;
            }
            
            let button = null;
            
            // محاولة العثور على الزر بطرق مختلفة
            if (formId === 'digLicenseForm') {
                button = form.querySelector('button[onclick*="saveDigLicenseSection"]');
            } else if (formId === 'coordinationForm') {
                button = form.querySelector('button[onclick*="saveCoordinationSection"]');
            } else if (formId === 'labForm') {
                button = form.querySelector('button[onclick*="saveLabSection"]');
            } else if (formId === 'evacuationsForm') {
                button = form.querySelector('button[onclick*="saveEvacuationsSection"]');
            } else if (formId === 'violationsForm') {
                button = form.querySelector('button[onclick*="saveViolationsSection"]');
            } else if (formId === 'notesForm') {
                button = form.querySelector('button[onclick*="saveNotesSection"]');
            }
            
            // إذا لم نجد الزر بالطريقة المخصصة، استخدم الطريقة العامة
            if (!button) {
                button = form.querySelector('button[onclick*="save"], button[type="button"]:last-child, button[type="submit"]');
            }
            
            console.log('Button found for hide:', button);
            
            if (button) {
                button.disabled = false;
                button.innerHTML = `<i class="fas fa-save me-2"></i>${originalText}`;
                console.log('Button normal state restored');
            } else {
                console.error('No button found in form for hiding:', formId);
            }
        }

        function showSuccessMessage(message) {
            // إزالة الرسائل السابقة من نفس النوع لتجنب التراكم
            const existingAlerts = document.querySelectorAll('.alert-success.position-fixed');
            existingAlerts.forEach(alert => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            });
            
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 500px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
            alert.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alert);
            
            // إضافة تأثير صوتي (اختياري)
            if (typeof Audio !== 'undefined') {
                try {
                    // يمكن إضافة ملف صوتي للنجاح
                    // const audio = new Audio('/sounds/success.mp3');
                    // audio.play().catch(() => {}); // تجاهل الأخطاء
                } catch (e) {
                    // تجاهل أخطاء الصوت
                }
            }
            
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 6000);
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
            console.log('Refreshing licenses table...');
            showNotification('جاري تحديث جدول الرخص...', 'info');
            
            // تحديث آخر رخصة قبل إعادة تحميل الصفحة
            updateLatestLicense();
            
            // إضافة تأخير قصير لإظهار رسالة التحديث
            setTimeout(() => {
                location.reload();
            }, 500);
        }

        // دالة إنشاء رخصة جديدة
        async function createNewLicense() {
            // التحقق من وجود رخصة حالية
            if (currentLicenseId && isNewLicenseCreated) {
                if (!confirm('يوجد رخصة نشطة حالياً #' + currentLicenseId + '.\nهل تريد إنشاء رخصة جديدة منفصلة بجميع البيانات المدخلة؟\nسيتم إنشاء رخصة جديدة منفصلة تماماً.')) {
                    return;
                }
            } else {
                if (!confirm('هل تريد إنشاء رخصة جديدة بجميع البيانات المدخلة؟\nسيتم إنشاء رخصة جديدة منفصلة تماماً.')) {
                    return;
                }
            }
            
            try {
                showGlobalLoadingState('جاري إنشاء الرخصة الجديدة...');
                
                const formData = createCompleteFormData();
                formData.append('force_new', '1'); // إجبار إنشاء رخصة جديدة
                
                const response = await fetch('{{ route("admin.licenses.save-section") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // طباعة معلومات debug
                    console.log('Create new license - Server response debug info:', result.debug_info);
                    
                    showSuccessMessage('تم إنشاء الرخصة الجديدة بنجاح! (رقم الرخصة: ' + result.license_id + ')');
                    
                    // تحديث الرخصة الحالية باستخدام latest_license_id من الخادم
                    if (result.latest_license_id) {
                        currentLicenseId = result.latest_license_id;
                        latestLicenseId = result.latest_license_id; // تحديث آخر رخصة
                        isNewLicenseCreated = result.is_new || true;
                        updateCurrentLicenseDisplay(result.latest_license_id);
                        
                        console.log('Updated license IDs from createNewLicense (using latest_license_id):', {
                            currentLicenseId,
                            latestLicenseId,
                            isNewLicenseCreated,
                            serverResponse: result.debug_info
                        });
                    }
                    
                    // لا حاجة لإعادة تعيين النماذج - البيانات ستحفظ في الرخصة الجديدة
                    
                    // تحديث الجدول
                    setTimeout(() => {
                        refreshLicensesTable();
                    }, 1500);
                } else {
                    showErrorMessage('خطأ في إنشاء الرخصة: ' + (result.message || 'خطأ غير معروف'));
                }
            } catch (error) {
                console.error('Error creating new license:', error);
                showErrorMessage('حدث خطأ أثناء إنشاء الرخصة الجديدة');
            } finally {
                hideGlobalLoadingState();
            }
        }

        // دالة إعادة تعيين جميع النماذج
        function resetAllForms() {
            const shouldResetCurrentLicense = !currentLicenseId || !isNewLicenseCreated;
            let confirmMessage = 'هل تريد إعادة تعيين جميع النماذج؟ سيتم حذف جميع البيانات المدخلة.';
            
            if (currentLicenseId && isNewLicenseCreated) {
                confirmMessage = `هل تريد إعادة تعيين جميع النماذج؟\nسيتم حذف البيانات المدخلة ولكن ستبقى الرخصة النشطة #${currentLicenseId}.`;
            }
            
            if (!confirm(confirmMessage)) {
                return;
            }
            
            const forms = [
                'coordinationForm',
                'digLicenseForm', 
                'labForm',
                'evacuationsForm',
                'violationsForm',
                'notesForm'
            ];
            
            forms.forEach(formId => {
                const form = document.getElementById(formId);
                if (form) {
                    const inputs = form.querySelectorAll('input, select, textarea');
                    inputs.forEach(input => {
                        if (input.name !== 'work_order_id' && input.name !== '_token' && input.name !== 'section_type') {
                            if (input.type === 'file') {
                                input.value = '';
                            } else if (input.type === 'checkbox' || input.type === 'radio') {
                                input.checked = false;
                            } else {
                                input.value = '';
                            }
                        }
                    });
                }
            });
            
            // إعادة تعيين الجداول
            const tableIds = ['evacTable1Body', 'evacTable2Body', 'labTable1Body', 'labTable2Body'];
            tableIds.forEach(tableId => {
                const tbody = document.getElementById(tableId);
                if (tbody) {
                    tbody.innerHTML = '';
                }
            });
            
            // إعادة تعيين حقول الحظر
            const restrictionField = document.getElementById('restriction_authority_field');
            if (restrictionField) {
                restrictionField.style.display = 'none';
            }
            
            // إعادة تعيين عداد الأيام
            const daysCounter = document.getElementById('dig_license_days_text');
            const extensionCounter = document.getElementById('dig_extension_days_text');
            
            if (daysCounter) {
                daysCounter.innerHTML = '<i class="fas fa-clock me-2"></i>اختر التواريخ لحساب المدة';
                daysCounter.parentElement.className = 'alert alert-info w-100 mb-0';
            }
            
            if (extensionCounter) {
                extensionCounter.innerHTML = '<i class="fas fa-clock me-2"></i>اختر تواريخ التمديد';
                extensionCounter.parentElement.className = 'alert alert-warning w-100 mb-0';
            }
            
            let successMessage = 'تم إعادة تعيين جميع النماذج بنجاح!';
            if (currentLicenseId && isNewLicenseCreated) {
                successMessage += ` الرخصة النشطة #${currentLicenseId} لا تزال متاحة.`;
            }
            
            showNotification(successMessage, 'info');
        }

        // دالة لإنشاء FormData شامل
        function createCompleteFormData() {
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('work_order_id', '{{ $workOrder->id }}');
            formData.append('section_type', 'complete_license');
            
            // جمع البيانات من جميع النماذج
            const forms = [
                'coordinationForm',
                'digLicenseForm', 
                'labForm',
                'evacuationsForm',
                'violationsForm',
                'notesForm'
            ];
            
            forms.forEach(formId => {
                const form = document.getElementById(formId);
                if (form) {
                    const inputs = form.querySelectorAll('input, select, textarea');
                    inputs.forEach(input => {
                        if (input.name && input.name !== '_token') {
                            if (input.type === 'file') {
                                if (input.files.length > 0) {
                                    for (let i = 0; i < input.files.length; i++) {
                                        formData.append(input.name, input.files[i]);
                                    }
                                }
                            } else if (input.type === 'checkbox') {
                                formData.append(input.name, input.checked ? '1' : '0');
                            } else if (input.value) {
                                formData.append(input.name, input.value);
                            }
                        }
                    });
                }
            });
            
            // إضافة بيانات الجداول
            const evacTable1Data = collectTableData('evacTable1Body');
            const evacTable2Data = collectTableData('evacTable2Body');
            
            if (evacTable1Data.length > 0) {
                formData.append('evac_table1_data', JSON.stringify(evacTable1Data));
            }
            
            if (evacTable2Data.length > 0) {
                formData.append('evac_table2_data', JSON.stringify(evacTable2Data));
            }
            
            return formData;
        }

        // دوال حالة التحميل العامة
        function showGlobalLoadingState(message) {
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(btn => btn.disabled = true);
            
            const createBtn = document.querySelector('button[onclick*="createNewLicense"]');
            const resetBtn = document.querySelector('button[onclick*="resetAllForms"]');
            
            if (createBtn) {
                createBtn.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i>${message}`;
            }
            if (resetBtn) {
                resetBtn.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i>جاري المعالجة...`;
            }
        }

        function hideGlobalLoadingState() {
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(btn => btn.disabled = false);
            
            const createBtn = document.querySelector('button[onclick*="createNewLicense"]');
            const resetBtn = document.querySelector('button[onclick*="resetAllForms"]');
            
            if (createBtn) {
                createBtn.innerHTML = `<i class="fas fa-plus-circle me-2"></i>إنشاء رخصة جديدة شاملة`;
            }
            if (resetBtn) {
                resetBtn.innerHTML = `<i class="fas fa-refresh me-2"></i>إعادة تعيين النماذج`;
            }
        }

        // وظائف إدارة جداول المختبر
        function addRowToTable1() {
            const tbody = document.getElementById('labTable1Body');
            const rowCount = tbody.rows.length;
            const row = document.createElement('tr');
            
            row.innerHTML = `
                <td><input type="text" class="form-control form-control-sm" name="lab_table1[${rowCount}][clearance_number]" placeholder="رقم الفسح"></td>
                <td><input type="date" class="form-control form-control-sm" name="lab_table1[${rowCount}][clearance_date]"></td>
                <td><input type="checkbox" class="form-check-input" name="lab_table1[${rowCount}][is_dirt]" value="1"></td>
                <td><input type="checkbox" class="form-check-input" name="lab_table1[${rowCount}][is_asphalt]" value="1"></td>
                <td><input type="checkbox" class="form-check-input" name="lab_table1[${rowCount}][is_tile]" value="1"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="lab_table1[${rowCount}][length]" placeholder="الطول بالمتر"></td>
                <td><input type="text" class="form-control form-control-sm" name="lab_table1[${rowCount}][lab_check]" placeholder="تدقيق المختبر"></td>
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
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="lab_table2[${rowCount}][max_asphalt_density]" placeholder="الكثافة القصوى"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="lab_table2[${rowCount}][asphalt_percentage]" placeholder="نسبة الأسفلت"></td>
                <td><input type="text" class="form-control form-control-sm" name="lab_table2[${rowCount}][granular_gradient]" placeholder="التدرج الحبيبي"></td>
                <td><input type="text" class="form-control form-control-sm" name="lab_table2[${rowCount}][marshall_test]" placeholder="تجربة مارشال"></td>
                <td><input type="text" class="form-control form-control-sm" name="lab_table2[${rowCount}][tile_evaluation]" placeholder="تقييم البلاط"></td>
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
            
            if (licenseStartDate && typeof calculateLicenseDays === 'function') {
                licenseStartDate.addEventListener('change', calculateLicenseDays);
            }
            if (licenseEndDate && typeof calculateLicenseDays === 'function') {
                licenseEndDate.addEventListener('change', calculateLicenseDays);
            }
            if (extensionStartDate && typeof calculateExtensionDays === 'function') {
                extensionStartDate.addEventListener('change', calculateExtensionDays);
            }
            if (extensionEndDate && typeof calculateExtensionDays === 'function') {
                extensionEndDate.addEventListener('change', calculateExtensionDays);
            }
            
            if (typeof calculateLicenseDays === 'function') {
                calculateLicenseDays();
            }
            if (typeof calculateExtensionDays === 'function') {
                calculateExtensionDays();
            }
            
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
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table1[${rowCount}][length]" placeholder="الطول بالمتر"></td>
                <td><input type="text" class="form-control form-control-sm" name="evac_table1[${rowCount}][clearance_lab_number]" placeholder="طول المختبر"></td>
                <td><input type="text" class="form-control form-control-sm" name="evac_table1[${rowCount}][street_type]" placeholder="نوع الشارع"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table1[${rowCount}][dirt_quantity]" placeholder="كمية ترابي"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table1[${rowCount}][asphalt_quantity]" placeholder="كمية أسفلت"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table1[${rowCount}][tile_quantity]" placeholder="كمية بلاط"></td>
                <td><input type="text" class="form-control form-control-sm" name="evac_table1[${rowCount}][soil_check]" placeholder="تدقيق التربة"></td>
                <td><input type="text" step="0.01" class="form-control form-control-sm" name="evac_table1[${rowCount}][mc1_check]" placeholder="تدقيق MC1"></td>
                <td><input type="text" step="0.01" class="form-control form-control-sm" name="evac_table1[${rowCount}][asphalt_check]" placeholder="تدقيق أسفلت"></td>
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
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[${rowCount}][soil_compaction]" placeholder="دك التربة"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[${rowCount}][mc1rc2]" placeholder="MC1-RC2"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[${rowCount}][asphalt_compaction]" placeholder="دك أسفلت"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[${rowCount}][is_dirt]" placeholder="ترابي"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[${rowCount}][max_asphalt_density]" placeholder="الكثافة القصوى"></td>
                <td><input type="text" class="form-control form-control-sm" name="evac_table2[${rowCount}][asphalt_percentage_gradient]" placeholder="نسبة الأسفلت / التدرج"></td>
                <td><input type="text" class="form-control form-control-sm" name="evac_table2[${rowCount}][marshall_test]" placeholder="تجربة مارشال"></td>
                <td><input type="text" class="form-control form-control-sm" name="evac_table2[${rowCount}][tile_evaluation_coldness]" placeholder="تقييم البلاط / البرودة"></td>
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

        // دالة حساب تواريخ التمديدات المحسن
        function setupExtensionDateCalculations() {
            // التمديد الأول
            const extensionStartDate = document.getElementById('dig_extension_start_date');
            const extensionEndDate = document.getElementById('dig_extension_end_date');
            const extensionDaysText = document.getElementById('dig_extension_days_text');
            

            
            function calculateExtensionDays(startField, endField, textElement, label) {
                if (startField.value && endField.value) {
                    const startDate = new Date(startField.value);
                    const endDate = new Date(endField.value);
                    const diffTime = Math.abs(endDate - startDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    
                    if (endDate >= startDate) {
                        textElement.innerHTML = `
                            <i class="fas fa-calendar-check me-1"></i>
                            ${label}: ${diffDays} يوم
                            <small class="d-block mt-1">من ${formatArabicDate(startDate)} إلى ${formatArabicDate(endDate)}</small>
                        `;
                        textElement.parentElement.className = 'alert alert-success w-100 mb-0';
                    } else {
                        textElement.innerHTML = `
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            تاريخ النهاية يجب أن يكون بعد تاريخ البداية
                        `;
                        textElement.parentElement.className = 'alert alert-danger w-100 mb-0';
                    }
                } else {
                    textElement.innerHTML = `
                        <i class="fas fa-clock me-1"></i>
                        اختر تواريخ ${label}
                    `;
                    textElement.parentElement.className = 'alert alert-warning w-100 mb-0';
                }
            }
            
            // ربط الأحداث للتمديد
            if (extensionStartDate && extensionEndDate) {
                extensionStartDate.addEventListener('change', () => {
                    calculateExtensionDays(extensionStartDate, extensionEndDate, extensionDaysText, 'التمديد');
                });
                
                extensionEndDate.addEventListener('change', () => {
                    calculateExtensionDays(extensionStartDate, extensionEndDate, extensionDaysText, 'التمديد');
                });
            }
        }

        // دالة إظهار نموذج رخصة جديدة (غير مستخدمة - تم حذف الأزرار)
        function showNewLicenseForm() {
            // هذه الدالة لم تعد مستخدمة بعد حذف الأزرار
            console.log('showNewLicenseForm called but buttons were removed');
            showNotification('تم حذف هذه الوظيفة. استخدم "إنشاء رخصة جديدة شاملة" بدلاً من ذلك.', 'warning');
        }

        // دالة تصدير جدول الرخص
        function exportLicensesTable() {
            const table = document.getElementById('licensesTable');
            const workOrderNumber = '{{ $workOrder->order_number ?? "" }}';
            
            if (!table) {
                showNotification('لا يمكن العثور على الجدول للتصدير', 'error');
                return;
            }
            
            // إنشاء محتوى CSV
            let csvContent = '\uFEFF'; // BOM for UTF-8
            const headers = [];
            const headerCells = table.querySelectorAll('thead th');
            headerCells.forEach(cell => headers.push(cell.textContent.trim()));
            csvContent += headers.join(',') + '\n';
            
            // إضافة صفوف البيانات
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const rowData = [];
                const cells = row.querySelectorAll('td');
                cells.forEach(cell => {
                    // تنظيف النص وإزالة العناصر HTML
                    const cleanText = cell.textContent.trim().replace(/[\n\r]/g, ' ').replace(/,/g, '،');
                    rowData.push('"' + cleanText + '"');
                });
                csvContent += rowData.join(',') + '\n';
            });
            
            // تحميل الملف
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `رخص_امر_عمل_${workOrderNumber}_${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            showNotification('تم تصدير جدول الرخص بنجاح', 'success');
        }

        // دالة إعادة تعيين جميع النماذج (غير مستخدمة - تم حذف الزر)
        function resetAllForms() {
            // هذه الدالة لم تعد مستخدمة بعد حذف زر إعادة التعيين
            console.log('resetAllForms called but button was removed');
            showNotification('تم حذف هذه الوظيفة. البيانات ستحفظ في آخر رخصة تلقائياً.', 'warning');
        }

        // دالة عرض الإشعارات المحسنة
        function showNotification(message, type = 'info') {
            // إزالة الإشعارات السابقة
            const existingNotifications = document.querySelectorAll('.custom-notification');
            existingNotifications.forEach(notification => notification.remove());
            
            // إنشاء إشعار جديد
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show custom-notification`;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
                max-width: 500px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            `;
            
            const iconMap = {
                'success': 'fas fa-check-circle',
                'error': 'fas fa-exclamation-circle',
                'warning': 'fas fa-exclamation-triangle',
                'info': 'fas fa-info-circle'
            };
            
            notification.innerHTML = `
                <i class="${iconMap[type] || iconMap.info} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            // إزالة الإشعار تلقائياً بعد 5 ثوانٍ
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 5000);
        }

        // دالة تنسيق التاريخ العربي
        function formatArabicDate(date) {
            const options = { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                calendar: 'gregory'
            };
            return date.toLocaleDateString('ar-SA', options);
        }

        // دالة تحديث عرض الرخصة الحالية
        function updateCurrentLicenseDisplay(licenseId) {
            // إنشاء أو تحديث بطاقة الرخصة الحالية
            let currentLicenseCard = document.getElementById('currentLicenseCard');
            if (!currentLicenseCard) {
                currentLicenseCard = document.createElement('div');
                currentLicenseCard.id = 'currentLicenseCard';
                currentLicenseCard.className = 'alert alert-success border-0 shadow-sm mb-4';
                
                // إدراج البطاقة بعد هيدر الصفحة
                const mainContainer = document.querySelector('.card-body');
                if (mainContainer) {
                    mainContainer.insertBefore(currentLicenseCard, mainContainer.firstChild);
                }
            }
            
            currentLicenseCard.innerHTML = `
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-file-contract fa-2x text-success"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">
                            <i class="fas fa-check-circle me-2"></i>
                            الرخصة النشطة حالياً
                        </h6>
                        
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-secondary btn-sm me-2" onclick="viewCurrentLicenseInTable(${licenseId})">
                            <i class="fas fa-eye me-1"></i>
                            عرض في الجدول
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="resetCurrentLicense()">
                            <i class="fas fa-times me-1"></i>
                            إلغاء النشط
                        </button>
                    </div>
                </div>
            `;
            
            // تحديث أزرار التبويبات لإظهار أنها مرتبطة بالرخصة
            updateTabButtonsForCurrentLicense();
            
            // إظهار زر التحديث في قسم التنسيق
            showUpdateButtonsInSections();
        }
        
        // دالة إظهار أزرار التحديث في الأقسام
        function showUpdateButtonsInSections() {
            const updateCoordinationBtn = document.getElementById('updateCoordinationBtn');
            if (updateCoordinationBtn && currentLicenseId) {
                updateCoordinationBtn.style.display = 'inline-block';
                updateCoordinationBtn.innerHTML = `
                    <i class="fas fa-edit me-2"></i>
                    تحديث الرخصة #${currentLicenseId}
                `;
            }
        }
        
        // دالة إخفاء أزرار التحديث
        function hideUpdateButtonsInSections() {
            const updateCoordinationBtn = document.getElementById('updateCoordinationBtn');
            if (updateCoordinationBtn) {
                updateCoordinationBtn.style.display = 'none';
            }
        }
        
        // دالة عرض الرخصة الحالية في الجدول
        function viewCurrentLicenseInTable(licenseId) {
            // التمرير إلى الجدول
            const licensesTable = document.getElementById('licensesTable');
            if (licensesTable) {
                licensesTable.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // تمييز الصف الخاص بالرخصة الحالية
                setTimeout(() => {
                    const targetRow = licensesTable.querySelector(`tr[data-license-id="${licenseId}"]`);
                    if (targetRow) {
                        targetRow.style.background = 'linear-gradient(45deg, rgba(40, 167, 69, 0.2), rgba(32, 201, 151, 0.1))';
                        targetRow.style.border = '2px solid #28a745';
                        targetRow.style.transform = 'scale(1.02)';
                        
                        // إزالة التمييز بعد 3 ثواني
                        setTimeout(() => {
                            targetRow.style.background = '';
                            targetRow.style.border = '';
                            targetRow.style.transform = '';
                        }, 3000);
                    }
                }, 500);
            }
        }
        
        // دالة إعادة تعيين الرخصة الحالية
        function resetCurrentLicense() {
            if (confirm('هل تريد إلغاء الرخصة النشطة؟\n\nسيتم فصل البيانات التالية عن الرخصة الحالية وستحتاج لإنشاء رخصة جديدة.')) {
                currentLicenseId = null;
                isNewLicenseCreated = false;
                
                // إزالة بطاقة الرخصة الحالية بتأثير انتقالي
                const currentLicenseCard = document.getElementById('currentLicenseCard');
                if (currentLicenseCard) {
                    currentLicenseCard.style.transition = 'all 0.3s ease';
                    currentLicenseCard.style.opacity = '0';
                    currentLicenseCard.style.transform = 'translateY(-20px)';
                    
                    setTimeout(() => {
                        currentLicenseCard.remove();
                    }, 300);
                }
                
                // إخفاء أزرار التحديث
                hideUpdateButtonsInSections();
                
                // إعادة تعيين أزرار التبويبات
                updateTabButtonsForCurrentLicense();
                
                showNotification('تم إلغاء الرخصة النشطة. البيانات الجديدة ستنشئ رخصة منفصلة.', 'info');
            }
        }
        
        // دالة تحديث أزرار التبويبات
        function updateTabButtonsForCurrentLicense() {
            const tabButtons = document.querySelectorAll('.tab-btn:not([data-target="dig-license-section"])');
            
            tabButtons.forEach(button => {
                const originalText = button.getAttribute('data-original-text') || button.textContent.trim();
                button.setAttribute('data-original-text', originalText);
                
                if (currentLicenseId) {
                    const icon = button.querySelector('i').className;
                    const text = originalText.replace(/^[\s\S]*?>\s*/, ''); // إزالة الأيقونة من النص
                    button.innerHTML = `
                        <i class="${icon}"></i>
                        ${text}
                        <small class="d-block mt-1" style="font-size: 0.7rem;">
                            (سيُحفظ في الرخصة #${currentLicenseId})
                        </small>
                    `;
                    button.classList.add('border-success');
                } else {
                    button.innerHTML = `<i class="${button.querySelector('i').className}"></i> ${originalText.replace(/^[\s\S]*?>\s*/, '')}`;
                    button.classList.remove('border-success');
                }
            });
        }

        async function saveEvacuationsSection(forceNew = false) {
            console.log('=== DEBUG: saveEvacuationsSection started ===');
            console.log('currentLicenseId:', currentLicenseId);
            console.log('latestLicenseId:', latestLicenseId);
            console.log('isNewLicenseCreated:', isNewLicenseCreated);
            
            // دائماً الحفظ في آخر رخصة تم إنشاؤها
            const useLatestLicense = latestLicenseId ? true : false; // true إذا كان هناك آخر رخصة
            console.log('useLatestLicense:', useLatestLicense, 'latestLicenseId:', latestLicenseId);
            
            const mainForm = document.getElementById('licenseForm');
            if (!mainForm) {
                console.error('Main licenseForm not found!');
                showErrorMessage('لم يتم العثور على النموذج الرئيسي');
                return;
            }
            
            const evacSection = document.getElementById('evacuationsForm');
            if (!evacSection) {
                console.error('evacuationsForm section not found!');
                showErrorMessage('لم يتم العثور على قسم الإخلاءات');
                return;
            }
            
            // إنشاء FormData من جميع العناصر في قسم الإخلاءات
            const formData = new FormData();
            
            // إضافة الحقول المطلوبة
            const workOrderIdField = mainForm.querySelector('input[name="work_order_id"]');
            if (workOrderIdField) {
                formData.append('work_order_id', workOrderIdField.value);
            }
            
            formData.append('section_type', 'evacuations');
            
            // جمع البيانات من جميع العناصر في قسم الإخلاءات
            const inputs = evacSection.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.type === 'file') {
                    if (input.files.length > 0) {
                        if (input.multiple) {
                            Array.from(input.files).forEach(file => {
                                formData.append(input.name, file);
                            });
                        } else {
                            formData.append(input.name, input.files[0]);
                        }
                    }
                } else if (input.type === 'checkbox') {
                    if (input.checked) {
                        formData.append(input.name, input.value);
                    }
                } else if (input.type === 'radio') {
                    if (input.checked) {
                        formData.append(input.name, input.value);
                    }
                } else if (input.value && input.value.trim() !== '') {
                    formData.append(input.name, input.value);
                }
            });
            
            // الحفظ في آخر رخصة أو إنشاء جديدة
            if (useLatestLicense && latestLicenseId) {
                formData.append('current_license_id', latestLicenseId);
                console.log('Will update latest license:', latestLicenseId);
            } else {
                formData.append('force_new', '1');
                console.log('Will create new license (no latest license found)');
            }
            
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
                const loadingMessage = useLatestLicense ? 
                    'جاري حفظ بيانات الإخلاءات في آخر رخصة...' : 
                    'جاري إنشاء رخصة جديدة مع بيانات الإخلاءات...';
                showLoadingState('evacuationsForm', loadingMessage);
                
                const response = await fetch('{{ route("admin.licenses.save-section") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // طباعة معلومات debug
                    console.log('Evacuations section - Server response debug info:', result.debug_info);
                    
                    // الحفظ في آخر رخصة أو إنشاء جديدة
                    const successMessage = useLatestLicense ? 
                        'تم حفظ بيانات الإخلاءات في آخر رخصة بنجاح!' + 
                        (result.license_id ? ' (رقم الرخصة: ' + result.license_id + ')' : '') :
                        'تم إنشاء رخصة جديدة مع بيانات الإخلاءات بنجاح!' + 
                        (result.license_id ? ' (رقم الرخصة: ' + result.license_id + ')' : '');
                    
                    showSuccessMessage(successMessage);
                    
                    // تحديث معرفات الرخصة باستخدام latest_license_id من الخادم
                    if (result.latest_license_id) {
                        currentLicenseId = result.latest_license_id;
                        latestLicenseId = result.latest_license_id; // تحديث آخر رخصة
                        isNewLicenseCreated = result.is_new || false;
                        updateCurrentLicenseDisplay(result.latest_license_id);
                        
                        console.log('Updated license IDs from evacuations section (using latest_license_id):', {
                            currentLicenseId,
                            latestLicenseId,
                            isNewLicenseCreated,
                            serverResponse: result.debug_info
                        });
                    }
                    
                    if (result.refresh_table) {
                        setTimeout(() => refreshLicensesTable(), 1000);
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

        async function saveViolationsSection(forceNew = false) {
            console.log('=== DEBUG: saveViolationsSection started ===');
            console.log('currentLicenseId:', currentLicenseId);
            console.log('latestLicenseId:', latestLicenseId);
            console.log('isNewLicenseCreated:', isNewLicenseCreated);
            
            // دائماً الحفظ في آخر رخصة تم إنشاؤها
            const useLatestLicense = latestLicenseId ? true : false; // true إذا كان هناك آخر رخصة
            console.log('useLatestLicense:', useLatestLicense, 'latestLicenseId:', latestLicenseId);
            
            const mainForm = document.getElementById('licenseForm');
            if (!mainForm) {
                console.error('Main licenseForm not found!');
                showErrorMessage('لم يتم العثور على النموذج الرئيسي');
                return;
            }
            
            const violationsSection = document.getElementById('violationsForm');
            if (!violationsSection) {
                console.error('violationsForm section not found!');
                showErrorMessage('لم يتم العثور على قسم المخالفات');
                return;
            }
            
            // إنشاء FormData من جميع العناصر في قسم المخالفات
            const formData = new FormData();
            
            // إضافة الحقول المطلوبة
            const workOrderIdField = mainForm.querySelector('input[name="work_order_id"]');
            if (workOrderIdField) {
                formData.append('work_order_id', workOrderIdField.value);
            }
            
            formData.append('section_type', 'violations');
            
            // جمع البيانات من جميع العناصر في قسم المخالفات
            const inputs = violationsSection.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.type === 'file') {
                    if (input.files.length > 0) {
                        if (input.multiple) {
                            Array.from(input.files).forEach(file => {
                                formData.append(input.name, file);
                            });
                        } else {
                            formData.append(input.name, input.files[0]);
                        }
                    }
                } else if (input.type === 'checkbox') {
                    if (input.checked) {
                        formData.append(input.name, input.value);
                    }
                } else if (input.type === 'radio') {
                    if (input.checked) {
                        formData.append(input.name, input.value);
                    }
                } else if (input.value && input.value.trim() !== '') {
                    formData.append(input.name, input.value);
                }
            });
            
            // الحفظ في آخر رخصة أو إنشاء جديدة
            if (useLatestLicense && latestLicenseId) {
                formData.append('current_license_id', latestLicenseId);
                console.log('Will update latest license:', latestLicenseId);
            } else {
                formData.append('force_new', '1');
                console.log('Will create new license (no latest license found)');
            }
            
            try {
                const loadingMessage = useLatestLicense ? 
                    'جاري حفظ بيانات المخالفات في آخر رخصة...' : 
                    'جاري إنشاء رخصة جديدة مع بيانات المخالفات...';
                showLoadingState('violationsForm', loadingMessage);
                
                const response = await fetch('{{ route("admin.licenses.save-section") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // طباعة معلومات debug
                    console.log('Violations section - Server response debug info:', result.debug_info);
                    
                    const successMessage = useLatestLicense ? 
                        'تم حفظ بيانات المخالفات في آخر رخصة بنجاح!' + 
                        (result.license_id ? ' (رقم الرخصة: ' + result.license_id + ')' : '') :
                        'تم إنشاء رخصة جديدة مع بيانات المخالفات بنجاح!' + 
                        (result.license_id ? ' (رقم الرخصة: ' + result.license_id + ')' : '');
                    
                    showSuccessMessage(successMessage);
                    
                    // تحديث معرف الرخصة الحالية باستخدام latest_license_id من الخادم
                    if (result.latest_license_id) {
                        currentLicenseId = result.latest_license_id;
                        latestLicenseId = result.latest_license_id; // تحديث آخر رخصة
                        isNewLicenseCreated = result.is_new || false;
                        updateCurrentLicenseDisplay(result.latest_license_id);
                        
                        console.log('Updated license IDs from violations section (using latest_license_id):', {
                            currentLicenseId,
                            latestLicenseId,
                            isNewLicenseCreated,
                            serverResponse: result.debug_info
                        });
                    }
                    
                    if (result.refresh_table) {
                        setTimeout(() => refreshLicensesTable(), 1000);
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

        async function saveNotesSection(forceNew = false) {
            console.log('=== DEBUG: saveNotesSection started ===');
            console.log('currentLicenseId:', currentLicenseId);
            console.log('latestLicenseId:', latestLicenseId);
            console.log('isNewLicenseCreated:', isNewLicenseCreated);
            
            // دائماً الحفظ في آخر رخصة تم إنشاؤها
            const useLatestLicense = latestLicenseId ? true : false; // true إذا كان هناك آخر رخصة
            console.log('useLatestLicense:', useLatestLicense, 'latestLicenseId:', latestLicenseId);
            
            const mainForm = document.getElementById('licenseForm');
            if (!mainForm) {
                console.error('Main licenseForm not found!');
                showErrorMessage('لم يتم العثور على النموذج الرئيسي');
                return;
            }
            
            const notesSection = document.getElementById('notesForm');
            if (!notesSection) {
                console.error('notesForm section not found!');
                showErrorMessage('لم يتم العثور على قسم الملاحظات');
                return;
            }
            
            // إنشاء FormData من جميع العناصر في قسم الملاحظات
            const formData = new FormData();
            
            // إضافة الحقول المطلوبة
            const workOrderIdField = mainForm.querySelector('input[name="work_order_id"]');
            if (workOrderIdField) {
                formData.append('work_order_id', workOrderIdField.value);
            }
            
            formData.append('section_type', 'notes');
            
            // جمع البيانات من جميع العناصر في قسم الملاحظات
            const inputs = notesSection.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.type === 'file') {
                    if (input.files.length > 0) {
                        if (input.multiple) {
                            Array.from(input.files).forEach(file => {
                                formData.append(input.name, file);
                            });
                        } else {
                            formData.append(input.name, input.files[0]);
                        }
                    }
                } else if (input.type === 'checkbox') {
                    if (input.checked) {
                        formData.append(input.name, input.value);
                    }
                } else if (input.type === 'radio') {
                    if (input.checked) {
                        formData.append(input.name, input.value);
                    }
                } else if (input.value && input.value.trim() !== '') {
                    formData.append(input.name, input.value);
                }
            });
            
            // الحفظ في آخر رخصة أو إنشاء جديدة
            if (useLatestLicense && latestLicenseId) {
                formData.append('current_license_id', latestLicenseId);
                console.log('Will update latest license:', latestLicenseId);
            } else {
                formData.append('force_new', '1');
                console.log('Will create new license (no latest license found)');
            }
            
            try {
                const loadingMessage = useLatestLicense ? 
                    'جاري حفظ الملاحظات في آخر رخصة...' : 
                    'جاري إنشاء رخصة جديدة مع الملاحظات...';
                showLoadingState('notesForm', loadingMessage);
                
                const response = await fetch('{{ route("admin.licenses.save-section") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    const successMessage = useLatestLicense ? 
                        'تم حفظ الملاحظات في آخر رخصة بنجاح!' + 
                        (result.license_id ? ' (رقم الرخصة: ' + result.license_id + ')' : '') :
                        'تم إنشاء رخصة جديدة مع الملاحظات بنجاح!' + 
                        (result.license_id ? ' (رقم الرخصة: ' + result.license_id + ')' : '');
                    
                    showSuccessMessage(successMessage);
                    
                    // تحديث معرف الرخصة الحالية إذا تم إنشاء رخصة جديدة
                    if (!useLatestLicense && result.license_id) {
                        currentLicenseId = result.license_id;
                        isNewLicenseCreated = true;
                        latestLicenseId = result.license_id; // تحديث آخر رخصة
                        updateCurrentLicenseDisplay(result.license_id);
                        
                        console.log('Updated license IDs from notes section:', {
                            currentLicenseId,
                            latestLicenseId,
                            isNewLicenseCreated
                        });
                    }
                    
                    if (result.refresh_table) {
                        setTimeout(() => refreshLicensesTable(), 1000);
                    }
                } else {
                    showErrorMessage('خطأ في حفظ الملاحظات: ' + (result.message || 'خطأ غير معروف'));
                }
            } catch (error) {
                console.error('Error saving notes section:', error);
                showErrorMessage('حدث خطأ أثناء حفظ الملاحظات');
            } finally {
                hideLoadingState('notesForm', 'حفظ الملاحظات');
            }
        }

        // دالة لجمع بيانات الجداول
        function collectTableData(tableBodyId) {
            const tbody = document.getElementById(tableBodyId);
            if (!tbody) {
                console.warn('Table body not found:', tableBodyId);
                return [];
            }
            
            const rows = tbody.querySelectorAll('tr');
            const data = [];
            
            rows.forEach((row, index) => {
                const inputs = row.querySelectorAll('input, select, textarea');
                const rowData = {};
                let hasData = false;
                
                inputs.forEach(input => {
                    if (input.name && input.name.includes('[')) {
                        // استخراج اسم الحقل من name attribute
                        const fieldName = input.name.split('[').pop().replace(']', '');
                        
                        if (input.type === 'checkbox') {
                            rowData[fieldName] = input.checked;
                            if (input.checked) hasData = true;
                        } else if (input.type === 'number') {
                            const value = parseFloat(input.value) || 0;
                            rowData[fieldName] = value;
                            if (input.value.trim()) hasData = true;
                        } else if (input.type === 'date') {
                            rowData[fieldName] = input.value;
                            if (input.value.trim()) hasData = true;
                        } else {
                            rowData[fieldName] = input.value;
                            if (input.value.trim()) hasData = true;
                        }
                    }
                });
                
                if (hasData) {
                    rowData.row_index = index;
                    data.push(rowData);
                }
            });
            
            console.log(`Collected data from ${tableBodyId}:`, data);
            return data;
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
            0% { 
                background: rgba(25, 135, 84, 0.3) !important;
                transform: scale(1.02);
            }
            100% { 
                background: rgba(25, 135, 84, 0.1) !important;
                transform: scale(1);
            }
        }

        /* تحسين أزرار الحفظ الجديدة */
        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745, #20c997, #17a2b8);
        }

        .card .btn-lg {
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card .btn-lg:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .d-grid.gap-2.d-md-flex .btn + .btn {
            margin-left: 0.5rem;
        }

        @media (max-width: 768px) {
            .d-grid.gap-2.d-md-flex {
                gap: 0.5rem !important;
            }
            
            .d-grid.gap-2.d-md-flex .btn {
                margin-left: 0;
                margin-bottom: 0.5rem;
            }
        }

        @media (max-width: 768px) {
            .btn-group .btn {
                padding: 0.375rem 0.5rem;
                font-size: 0.75rem;
            }
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

        /* تحسين الرسائل التوضيحية */
        .alert-sm {
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            border-radius: 0.375rem;
            border: 1px solid rgba(0, 123, 255, 0.3);
            background: linear-gradient(45deg, rgba(13, 202, 240, 0.1), rgba(13, 110, 253, 0.1));
        }

        .alert-sm .fas {
            font-size: 0.9rem;
        }

        /* تحسين هيدر شهادة التنسيق */
        .bg-warning {
            background: linear-gradient(45deg, #ffc107, #ffca2c) !important;
            color: #212529 !important;
        }

        /* العرض على الشاشات الصغيرة */
        @media (max-width: 768px) {
            .btn-group .btn {
                padding: 0.375rem 0.5rem;
                font-size: 0.75rem;
            }
        }

        /* تحسين بطاقة الرخصة النشطة */
        #currentLicenseCard {
            border-left: 4px solid #28a745;
            background: linear-gradient(45deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
            animation: slideInFromTop 0.5s ease-out;
        }

        #currentLicenseCard .fa-file-contract {
            color: #28a745;
            text-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
        }

        @keyframes slideInFromTop {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* تحسين أزرار التبويبات المرتبطة بالرخصة */
        .tab-btn.border-success {
            border-color: #28a745 !important;
            background: linear-gradient(45deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.05));
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2);
        }

        .tab-btn.border-success:hover {
            background: linear-gradient(45deg, rgba(40, 167, 69, 0.15), rgba(32, 201, 151, 0.1));
            transform: translateY(-1px);
        }

        .tab-btn small {
            color: #28a745;
            font-weight: 600;
        }

        /* العرض على الشاشات الصغيرة */
        @media (max-width: 768px) {
            .btn-group .btn {
                padding: 0.375rem 0.5rem;
                font-size: 0.75rem;
            }
        }

        /* تحسين مجموعة الأزرار في التنسيق */
        .btn-group .btn {
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .btn-group .btn:hover {
            z-index: 2;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .btn-group .btn:first-child {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .btn-group .btn:last-child {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /* تحسينات انتقالية للبطاقات */
        .alert {
            transition: all 0.3s ease;
        }

        .alert.fade-out {
            opacity: 0;
            transform: translateY(-20px);
        }

        /* العرض على الشاشات الصغيرة */
        @media (max-width: 768px) {
            .btn-group .btn {
                padding: 0.375rem 0.5rem;
                font-size: 0.75rem;
            }
        }
        </style>
        
        <script>
        // استدعاء تحديث آخر رخصة عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            // تأخير قصير للتأكد من تحميل جميع العناصر
            setTimeout(() => {
                updateLatestLicense();
                console.log('Page loaded, latest license updated');
            }, 500);
        });
        
        // تحديث دالة showSection لتحديث البيانات عند الانتقال بين التبويبات
        function showSection(sectionId) {
            // إخفاء جميع الأقسام
            document.querySelectorAll('.tab-section').forEach(section => {
                section.style.display = 'none';
            });
            
            // إظهار القسم المطلوب
            const targetSection = document.getElementById(sectionId);
            if (targetSection) {
                targetSection.style.display = 'block';
            }
            
            // تحديث حالة الأزرار
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary');
            });
            
            const activeBtn = document.querySelector(`[data-target="${sectionId}"]`);
            if (activeBtn) {
                activeBtn.classList.add('btn-primary');
                activeBtn.classList.remove('btn-outline-primary');
            }
            
            // تحديث البيانات من آخر رخصة عند فتح التبويب
            if (latestLicenseId && sectionId !== 'saved-licenses-section') {
                setTimeout(() => {
                    loadLatestLicenseDataToForms();
                }, 100);
            }
        }
        </script>
        @endsection 