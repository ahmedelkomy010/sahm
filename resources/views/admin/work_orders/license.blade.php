@extends('layouts.app')

@section('content')
<div id="app">
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-gradient-primary text-white py-3">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                            <h3 class="mb-0 fs-4 text-center text-md-start">
                                <i class="fas fa-certificate me-2"></i>
                                إدارة الجودة والرخص
                            </h3>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-back btn-sm">
                                    <i class="fas fa-arrow-right"></i> عودة لتفاصيل العمل
                                </a>
                                <a href="{{ route('admin.licenses.data') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-database"></i> بيانات الجودة
                                </a>
                                @if(isset($license) && $license->id)
                                    <a href="{{ route('admin.licenses.show', $license->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> عرض التفاصيل
                                    </a>
                                @endif
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

                        <form action="{{ route('admin.work-orders.upload-license', $workOrder) }}" method="POST" enctype="multipart/form-data" id="licenseForm">
                            @csrf
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
                                                         placeholder="أدخل ملاحظات حول شهادة التنسيق">{{ old('coordination_certificate_notes', $license->coordination_certificate_notes ?? '') }}</textarea>
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
                                                       value="{{ old('license_number', $license->license_number ?? '') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="license_date" class="form-label">تاريخ الرخصة</label>
                                                <input type="date" class="form-control" id="license_date" name="license_date" 
                                                       value="{{ old('license_date', $license->license_date ?? '') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="license_type" class="form-label">نوع الرخصة</label>
                                                <select class="form-select" id="license_type" name="license_type">
                                                    <option value="">اختر نوع الرخصة</option>
                                                    <option value="مشروع" {{ old('license_type', $license->license_type ?? '') == 'مشروع' ? 'selected' : '' }}>مشروع</option>
                                                    <option value="طوارئ" {{ old('license_type', $license->license_type ?? '') == 'طوارئ' ? 'selected' : '' }}>طوارئ</option>
                                                    <option value="عادي" {{ old('license_type', $license->license_type ?? '') == 'عادي' ? 'selected' : '' }}>عادي</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="license_value" class="form-label">قيمة الرخصة</label>
                                                <input type="number" step="0.01" class="form-control" id="license_value" name="license_value" 
                                                       value="{{ old('license_value', $license->license_value ?? '') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="extension_value" class="form-label">قيمة التمديدات</label>
                                                <input type="number" step="0.01" class="form-control" id="extension_value" name="extension_value" 
                                                       value="{{ old('extension_value', $license->extension_value ?? '') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="has_restriction" class="form-label">يوجد حظر؟</label>
                                                <select class="form-select" name="has_restriction" id="has_restriction">
                                                    <option value="0" {{ old('has_restriction', $license->has_restriction ?? 0) == 0 ? 'selected' : '' }}>لا</option>
                                                    <option value="1" {{ old('has_restriction', $license->has_restriction ?? 0) == 1 ? 'selected' : '' }}>نعم</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6" id="restriction_authority_field" style="display: none;">
                                                <label for="restriction_authority" class="form-label">جهة الحظر</label>
                                                <input type="text" class="form-control" id="restriction_authority" name="restriction_authority" 
                                                       value="{{ old('restriction_authority', $license->restriction_authority ?? '') }}" 
                                                       placeholder="اسم الجهة المسؤولة عن الحظر">
                                            </div>
                                            <div class="col-md-6" id="restriction_reason_field" style="display: none;">
                                                <label for="restriction_reason" class="form-label">سبب الحظر</label>
                                                <input type="text" class="form-control" id="restriction_reason" name="restriction_reason" 
                                                       value="{{ old('restriction_reason', $license->restriction_reason ?? '') }}" 
                                                       placeholder="أدخل سبب الحظر">
                                            </div>
                                            <div class="col-md-12" id="restriction_notes_field" style="display: none;">
                                                <label for="restriction_notes" class="form-label">ملاحظات الحظر الإضافية</label>
                                                <textarea class="form-control" id="restriction_notes" name="restriction_notes" rows="3" 
                                                         placeholder="أدخل ملاحظات إضافية حول الحظر">{{ old('restriction_notes', $license->restriction_notes ?? '') }}</textarea>
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
                                                       value="{{ old('license_start_date', $license->license_start_date ?? '') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">تاريخ نهاية الرخصة</label>
                                                <input type="date" class="form-control" name="license_end_date" id="license_end_date"
                                                       value="{{ old('license_end_date', $license->license_end_date ?? '') }}">
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
                                                       value="{{ old('extension_start_date', $license->license_extension_start_date ?? '') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">تاريخ نهاية التمديد</label>
                                                <input type="date" class="form-control" name="extension_end_date" id="extension_end_date"
                                                       value="{{ old('extension_end_date', $license->license_extension_end_date ?? '') }}">
                                            </div>
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
                                                                <option value="0" {{ old('has_depth_test', $license->has_depth_test ?? 0) == 0 ? 'selected' : '' }}>لا</option>
                                                                <option value="1" {{ old('has_depth_test', $license->has_depth_test ?? 0) == 1 ? 'selected' : '' }}>نعم</option>
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
                                                                <option value="0" {{ old('has_soil_compaction_test', $license->has_soil_compaction_test ?? 0) == 0 ? 'selected' : '' }}>لا</option>
                                                                <option value="1" {{ old('has_soil_compaction_test', $license->has_soil_compaction_test ?? 0) == 1 ? 'selected' : '' }}>نعم</option>
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
                                                                <option value="0" {{ old('has_rc1_mc1_test', $license->has_rc1_mc1_test ?? 0) == 0 ? 'selected' : '' }}>لا</option>
                                                                <option value="1" {{ old('has_rc1_mc1_test', $license->has_rc1_mc1_test ?? 0) == 1 ? 'selected' : '' }}>نعم</option>
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
                                                                <option value="0" {{ old('has_asphalt_test', $license->has_asphalt_test ?? 0) == 0 ? 'selected' : '' }}>لا</option>
                                                                <option value="1" {{ old('has_asphalt_test', $license->has_asphalt_test ?? 0) == 1 ? 'selected' : '' }}>نعم</option>
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
                                                                <option value="0" {{ old('has_soil_test', $license->has_soil_test ?? 0) == 0 ? 'selected' : '' }}>لا</option>
                                                                <option value="1" {{ old('has_soil_test', $license->has_soil_test ?? 0) == 1 ? 'selected' : '' }}>نعم</option>
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
                                                                <option value="0" {{ old('has_interlock_test', $license->has_interlock_test ?? 0) == 0 ? 'selected' : '' }}>لا</option>
                                                                <option value="1" {{ old('has_interlock_test', $license->has_interlock_test ?? 0) == 1 ? 'selected' : '' }}>نعم</option>
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

                                <!-- الجدول الأول للمختبر -->
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
                                            <button type="button" class="btn btn-success btn-sm" onclick="importExcelTable1()">
                                                <i class="fas fa-file-excel"></i> استيراد Excel
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm" onclick="exportExcelTable1()">
                                                <i class="fas fa-download"></i> تصدير Excel
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
                                                    <!-- سيتم إضافة الصفوف هنا ديناميكياً -->
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
                                            <button type="button" class="btn btn-success btn-sm" onclick="importExcelTable2()">
                                                <i class="fas fa-file-excel"></i> استيراد Excel
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm" onclick="exportExcelTable2()">
                                                <i class="fas fa-download"></i> تصدير Excel
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
                                                    <!-- سيتم إضافة الصفوف هنا ديناميكياً -->
                                                </tbody>
                                            </table>
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
                                                    <option value="0" {{ old('is_evacuated', $license->is_evacuated ?? 0) == 0 ? 'selected' : '' }}>لا</option>
                                                    <option value="1" {{ old('is_evacuated', $license->is_evacuated ?? 0) == 1 ? 'selected' : '' }}>نعم</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">رقم الرخصة</label>
                                                <input type="text" class="form-control" name="evac_license_number"
                                                       value="{{ old('evac_license_number', $license->evac_license_number ?? '') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">قيمة الرخصة</label>
                                                <input type="number" step="0.01" class="form-control" name="evac_license_value"
                                                       value="{{ old('evac_license_value', $license->evac_license_value ?? '') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">رقم سداد الرخصة</label>
                                                <input type="text" class="form-control" name="evac_payment_number"
                                                       value="{{ old('evac_payment_number', $license->evac_payment_number ?? '') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">تاريخ الإخلاء</label>
                                                <input type="date" class="form-control" name="evac_date"
                                                       value="{{ old('evac_date', $license->evac_date ?? '') }}">
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
                                                       value="{{ old('violation_license_number', $license->violation_license_number ?? '') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">قيمة الرخصة</label>
                                                <input type="number" step="0.01" class="form-control" name="violation_license_value"
                                                       value="{{ old('violation_license_value', $license->violation_license_value ?? '') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">تاريخ الرخصة</label>
                                                <input type="date" class="form-control" name="violation_license_date"
                                                       value="{{ old('violation_license_date', $license->violation_license_date ?? '') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">آخر موعد سداد للمخالفة</label>
                                                <input type="date" class="form-control" name="violation_due_date"
                                                       value="{{ old('violation_due_date', $license->violation_due_date ?? '') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">رقم المخالفة</label>
                                                <input type="text" class="form-control" name="violation_number"
                                                       value="{{ old('violation_number', $license->violation_number ?? '') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">رقم سداد المخالفة</label>
                                                <input type="text" class="form-control" name="violation_payment_number"
                                                       value="{{ old('violation_payment_number', $license->violation_payment_number ?? '') }}">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">المسبب</label>
                                                <input type="text" class="form-control" name="violation_cause"
                                                       value="{{ old('violation_cause', $license->violation_cause ?? '') }}">
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
                                                      placeholder="أدخل أي ملاحظات إضافية هنا...">{{ old('notes', $license->notes ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- زر الحفظ -->
                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-success btn-lg px-5">
                                        <i class="fas fa-save me-2"></i>
                                        حفظ جميع البيانات
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary');
        });
    });
    
    // إظهار/إخفاء حقول الحظر
    const hasRestrictionSelect = document.getElementById('has_restriction');
    const restrictionAuthorityField = document.getElementById('restriction_authority_field');
    const restrictionReasonField = document.getElementById('restriction_reason_field');
    const restrictionNotesField = document.getElementById('restriction_notes_field');
    
    function toggleRestrictionFields() {
        if (hasRestrictionSelect.value == '1') {
            restrictionAuthorityField.style.display = 'block';
            restrictionReasonField.style.display = 'block';
            restrictionNotesField.style.display = 'block';
        } else {
            restrictionAuthorityField.style.display = 'none';
            restrictionReasonField.style.display = 'none';
            restrictionNotesField.style.display = 'none';
        }
    }
    
    hasRestrictionSelect.addEventListener('change', toggleRestrictionFields);
    toggleRestrictionFields(); // تشغيل عند التحميل
    
    // حساب عداد الأيام للرخصة
    function calculateLicenseDays() {
        const startDate = document.getElementById('license_start_date').value;
        const endDate = document.getElementById('license_end_date').value;
        const daysText = document.getElementById('license_days_text');
        
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
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
        } else {
            daysText.innerHTML = 'اختر التواريخ لحساب المدة';
            daysText.parentElement.className = 'alert alert-info w-100 mb-0';
        }
    }
    
    // حساب عداد الأيام للتمديد
    function calculateExtensionDays() {
        const startDate = document.getElementById('extension_start_date').value;
        const endDate = document.getElementById('extension_end_date').value;
        const daysText = document.getElementById('extension_days_text');
        
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
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
        } else {
            daysText.innerHTML = 'اختر تواريخ التمديد';
            daysText.parentElement.className = 'alert alert-warning w-100 mb-0';
        }
    }
    
    // ربط الأحداث بحقول التاريخ
    document.getElementById('license_start_date').addEventListener('change', calculateLicenseDays);
    document.getElementById('license_end_date').addEventListener('change', calculateLicenseDays);
    document.getElementById('extension_start_date').addEventListener('change', calculateExtensionDays);
    document.getElementById('extension_end_date').addEventListener('change', calculateExtensionDays);
    
    // حساب الأيام عند التحميل
    calculateLicenseDays();
    calculateExtensionDays();
    
    // تأكيد الحفظ
    document.getElementById('licenseForm').addEventListener('submit', function(e) {
        const confirmation = confirm('هل أنت متأكد من حفظ جميع البيانات؟');
        if (!confirmation) {
            e.preventDefault();
        }
    });
    
    // تحميل البيانات الموجودة للجداول
    loadExistingTableData();
});

// وظائف إدارة الجدول الأول
function addRowToTable1() {
    const tbody = document.getElementById('labTable1Body');
    const rowCount = tbody.children.length;
    
    const row = document.createElement('tr');
    row.innerHTML = `
        <td><input type="text" class="form-control" name="lab_table1[${rowCount}][permit_number]" placeholder="رقم الفسح"></td>
        <td><input type="date" class="form-control" name="lab_table1[${rowCount}][permit_date]"></td>
        <td><input type="number" step="0.01" class="form-control" name="lab_table1[${rowCount}][length]" placeholder="الطول"></td>
        <td><input type="text" class="form-control" name="lab_table1[${rowCount}][street_type]" placeholder="نوع الشارع"></td>
        <td><input type="text" class="form-control" name="lab_table1[${rowCount}][soil]" placeholder="ترابي"></td>
        <td><input type="text" class="form-control" name="lab_table1[${rowCount}][asphalt]" placeholder="أسفلت"></td>
        <td><input type="text" class="form-control" name="lab_table1[${rowCount}][tiles]" placeholder="بلاط"></td>
        <td><input type="text" class="form-control" name="lab_table1[${rowCount}][soil_test]" placeholder="تربة"></td>
        <td><input type="text" class="form-control" name="lab_table1[${rowCount}][mc1_test]" placeholder="MC1"></td>
        <td><input type="text" class="form-control" name="lab_table1[${rowCount}][asphalt_test]" placeholder="أسفلت"></td>
        <td><input type="text" class="form-control" name="lab_table1[${rowCount}][notes]" placeholder="ملاحظات"></td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRowFromTable(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
}

// وظائف إدارة الجدول الثاني
function addRowToTable2() {
    const tbody = document.getElementById('labTable2Body');
    const rowCount = tbody.children.length;
    
    const row = document.createElement('tr');
    row.innerHTML = `
        <td><input type="number" class="form-control" name="lab_table2[${rowCount}][year]" placeholder="السنة"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][work_type]" placeholder="نوع العمل"></td>
        <td><input type="number" step="0.01" class="form-control" name="lab_table2[${rowCount}][depth]" placeholder="العمق"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][soil_compaction]" placeholder="دك التربة"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][mc1rc2]" placeholder="MC1RC2"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][asphalt_compaction]" placeholder="دك أسفلت"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][soil]" placeholder="ترابي"></td>
        <td><input type="number" step="0.01" class="form-control" name="lab_table2[${rowCount}][max_asphalt_density]" placeholder="الكثافة القصوى"></td>
        <td><input type="number" step="0.01" class="form-control" name="lab_table2[${rowCount}][asphalt_ratio]" placeholder="نسبة الأسفلت"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][gradation]" placeholder="التدرج الحبيبي"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][marshall_test]" placeholder="تجربة مارشال"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][tiles_evaluation]" placeholder="تقييم البلاط"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][cooling]" placeholder="البرودة"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][soil_classification]" placeholder="تصنيف التربة"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][proctor_test]" placeholder="تجربة بروكتور"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][concrete]" placeholder="الخرسانة"></td>
        <td><input type="text" class="form-control" name="lab_table2[${rowCount}][notes]" placeholder="ملاحظات"></td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRowFromTable(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
}

// حذف صف من الجدول
function removeRowFromTable(button) {
    if (confirm('هل أنت متأكد من حذف هذا الصف؟')) {
        button.closest('tr').remove();
    }
}

// استيراد Excel للجدول الأول
function importExcelTable1() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.xlsx,.xls,.csv';
    input.onchange = function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // هنا يمكن إضافة مكتبة لقراءة Excel مثل SheetJS
                alert('تم رفع الملف بنجاح! سيتم تطبيق هذه الميزة قريباً.');
            };
            reader.readAsArrayBuffer(file);
        }
    };
    input.click();
}

// استيراد Excel للجدول الثاني
function importExcelTable2() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.xlsx,.xls,.csv';
    input.onchange = function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // هنا يمكن إضافة مكتبة لقراءة Excel مثل SheetJS
                alert('تم رفع الملف بنجاح! سيتم تطبيق هذه الميزة قريباً.');
            };
            reader.readAsArrayBuffer(file);
        }
    };
    input.click();
}

// تصدير Excel للجدول الأول
function exportExcelTable1() {
    const table = document.getElementById('labTable1');
    const data = extractTableData(table);
    
    if (data.length === 0) {
        alert('لا توجد بيانات للتصدير');
        return;
    }
    
    // تحويل البيانات إلى CSV
    const csv = convertTableToCSV(data);
    downloadCSV(csv, 'جدول_الفسح_والاختبارات_الأول.csv');
}

// تصدير Excel للجدول الثاني
function exportExcelTable2() {
    const table = document.getElementById('labTable2');
    const data = extractTableData(table);
    
    if (data.length === 0) {
        alert('لا توجد بيانات للتصدير');
        return;
    }
    
    // تحويل البيانات إلى CSV
    const csv = convertTableToCSV(data);
    downloadCSV(csv, 'جدول_الاختبارات_التفصيلي_الثاني.csv');
}

// استخراج بيانات الجدول
function extractTableData(table) {
    const data = [];
    const headers = [];
    
    // استخراج العناوين
    const headerRow = table.querySelector('thead tr');
    headerRow.querySelectorAll('th').forEach(th => {
        headers.push(th.textContent.trim());
    });
    data.push(headers);
    
    // استخراج البيانات
    const bodyRows = table.querySelectorAll('tbody tr');
    bodyRows.forEach(row => {
        const rowData = [];
        row.querySelectorAll('input').forEach(input => {
            rowData.push(input.value || '');
        });
        if (rowData.some(cell => cell !== '')) { // إضافة الصف فقط إذا كان يحتوي على بيانات
            data.push(rowData);
        }
    });
    
    return data;
}

// تحويل البيانات إلى CSV
function convertTableToCSV(data) {
    return data.map(row => 
        row.map(cell => `"${cell}"`).join(',')
    ).join('\n');
}

// تحميل ملف CSV
function downloadCSV(csv, filename) {
    const blob = new Blob(['\ufeff' + csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}

// تحميل البيانات الموجودة للجداول
function loadExistingTableData() {
    // تحميل بيانات الجدول الأول إذا كانت موجودة
    @if(isset($license) && $license->lab_table1_data)
        const table1Data = @json($license->lab_table1_data);
        table1Data.forEach((row, index) => {
            addRowToTable1();
            const inputs = document.querySelectorAll(`#labTable1Body tr:last-child input`);
            Object.keys(row).forEach((key, i) => {
                if (inputs[i]) {
                    inputs[i].value = row[key] || '';
                }
            });
        });
    @endif
    
    // تحميل بيانات الجدول الثاني إذا كانت موجودة
    @if(isset($license) && $license->lab_table2_data)
        const table2Data = @json($license->lab_table2_data);
        table2Data.forEach((row, index) => {
            addRowToTable2();
            const inputs = document.querySelectorAll(`#labTable2Body tr:last-child input`);
            Object.keys(row).forEach((key, i) => {
                if (inputs[i]) {
                    inputs[i].value = row[key] || '';
                }
            });
        });
    @endif
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
</style>
@endsection 