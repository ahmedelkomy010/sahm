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
                                <i class="fas fa-edit me-2"></i>
                                تعديل بيانات الرخصة
                            </h3>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                @if(isset($workOrder) && $workOrder)
                                    <a href="{{ route('admin.work-orders.license', $workOrder) }}" class="btn btn-back btn-sm">
                                        <i class="fas fa-arrow-right"></i> عودة للرخصة
                                    </a>
                                @elseif($license->workOrder)
                                    <a href="{{ route('admin.work-orders.license', $license->workOrder) }}" class="btn btn-back btn-sm">
                                        <i class="fas fa-arrow-right"></i> عودة للرخصة
                                    </a>
                                @else
                                    <a href="{{ route('admin.licenses.data') }}" class="btn btn-back btn-sm">
                                        <i class="fas fa-arrow-right"></i> عودة للبيانات
                                    </a>
                                @endif
                                <a href="{{ route('admin.licenses.show', $license->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> عرض التفاصيل
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-3 p-md-4">
                        <!-- أزرار التبويبات الرئيسية -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-2 justify-content-center">
                                    <button type="button" class="btn btn-outline-primary tab-btn" data-target="basic-info-section">
                                        <i class="fas fa-info-circle"></i> المعلومات الأساسية
                                    </button>
                                    <button type="button" class="btn btn-outline-success tab-btn" data-target="dates-section">
                                        <i class="fas fa-calendar-alt"></i> التواريخ والمدد
                                    </button>
                                    <button type="button" class="btn btn-outline-warning tab-btn" data-target="evacuations-section">
                                        <i class="fas fa-truck-moving"></i> الإخلاءات
                                    </button>
                                    <button type="button" class="btn btn-outline-danger tab-btn" data-target="violations-section">
                                        <i class="fas fa-exclamation-triangle"></i> المخالفات
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary tab-btn" data-target="files-section">
                                        <i class="fas fa-paperclip"></i> المرفقات
                                    </button>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('admin.licenses.update', $license->id) }}" method="POST" enctype="multipart/form-data" id="licenseEditForm">
                            @csrf
                            @method('PUT')
                            
                            <!-- قسم المعلومات الأساسية -->
                            <div id="basic-info-section" class="tab-section" style="display: none;">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h4 class="mb-0 fs-5">
                                            <i class="fas fa-info-circle me-2"></i>
                                            المعلومات الأساسية للرخصة
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="license_number" class="form-label">رقم الرخصة</label>
                                                <input type="text" class="form-control" id="license_number" name="license_number" 
                                                       value="{{ old('license_number', $license->license_number) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="license_type" class="form-label">نوع الرخصة</label>
                                                <select class="form-select" id="license_type" name="license_type">
                                                    <option value="">اختر نوع الرخصة</option>
                                                    <option value="مشروع" {{ old('license_type', $license->license_type) == 'مشروع' ? 'selected' : '' }}>مشروع</option>
                                                    <option value="طوارئ" {{ old('license_type', $license->license_type) == 'طوارئ' ? 'selected' : '' }}>طوارئ</option>
                                                    <option value="عادي" {{ old('license_type', $license->license_type) == 'عادي' ? 'selected' : '' }}>عادي</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="license_value" class="form-label">قيمة الرخصة</label>
                                                <input type="number" step="0.01" class="form-control" id="license_value" name="license_value" 
                                                       value="{{ old('license_value', $license->license_value) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="extension_value" class="form-label">قيمة التمديدات</label>
                                                <input type="number" step="0.01" class="form-control" id="extension_value" name="extension_value" 
                                                       value="{{ old('extension_value', $license->extension_value) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="has_restriction" class="form-label">يوجد حظر؟</label>
                                                <select class="form-select" name="has_restriction" id="has_restriction">
                                                    <option value="0" {{ old('has_restriction', $license->has_restriction) == 0 ? 'selected' : '' }}>لا</option>
                                                    <option value="1" {{ old('has_restriction', $license->has_restriction) == 1 ? 'selected' : '' }}>نعم</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6" id="restriction_authority_field" style="display: none;">
                                                <label for="restriction_authority" class="form-label">جهة الحظر</label>
                                                <input type="text" class="form-control" id="restriction_authority" name="restriction_authority" 
                                                       value="{{ old('restriction_authority', $license->restriction_authority) }}" 
                                                       placeholder="اسم الجهة المسؤولة عن الحظر">
                                            </div>
                                            <div class="col-12">
                                                <label for="restriction_reason" class="form-label">سبب الحظر</label>
                                                <textarea class="form-control" id="restriction_reason" name="restriction_reason" rows="3"
                                                          placeholder="اذكر سبب الحظر إن وجد">{{ old('restriction_reason', $license->restriction_reason) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- قسم التواريخ والمدد -->
                            <div id="dates-section" class="tab-section" style="display: none;">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-success text-white">
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
                                                       value="{{ old('license_start_date', $license->license_start_date ? $license->license_start_date->format('Y-m-d') : '') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">تاريخ نهاية الرخصة</label>
                                                <input type="date" class="form-control" name="license_end_date" id="license_end_date"
                                                       value="{{ old('license_end_date', $license->license_end_date ? $license->license_end_date->format('Y-m-d') : '') }}">
                                            </div>
                                            <div class="col-md-4 d-flex align-items-end">
                                                <div class="alert alert-info w-100 mb-0" id="license_days_counter">
                                                    <i class="fas fa-clock me-2"></i>
                                                    <span id="license_days_text">اختر التواريخ لحساب المدة</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">تاريخ بداية التمديد</label>
                                                <input type="date" class="form-control" name="license_extension_start_date" id="extension_start_date"
                                                       value="{{ old('license_extension_start_date', $license->license_extension_start_date ? $license->license_extension_start_date->format('Y-m-d') : '') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">تاريخ نهاية التمديد</label>
                                                <input type="date" class="form-control" name="license_extension_end_date" id="extension_end_date"
                                                       value="{{ old('license_extension_end_date', $license->license_extension_end_date ? $license->license_extension_end_date->format('Y-m-d') : '') }}">
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
                                                    <option value="0" {{ old('is_evacuated', $license->is_evacuated) == 0 ? 'selected' : '' }}>لا</option>
                                                    <option value="1" {{ old('is_evacuated', $license->is_evacuated) == 1 ? 'selected' : '' }}>نعم</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">رقم الرخصة</label>
                                                <input type="text" class="form-control" name="evac_license_number"
                                                       value="{{ old('evac_license_number', $license->evac_license_number) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">قيمة الرخصة</label>
                                                <input type="number" step="0.01" class="form-control" name="evac_license_value"
                                                       value="{{ old('evac_license_value', $license->evac_license_value) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">رقم سداد الرخصة</label>
                                                <input type="text" class="form-control" name="evac_payment_number"
                                                       value="{{ old('evac_payment_number', $license->evac_payment_number) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">تاريخ الإخلاء</label>
                                                <input type="date" class="form-control" name="evac_date"
                                                       value="{{ old('evac_date', $license->evac_date ? $license->evac_date->format('Y-m-d') : '') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">مبلغ الإخلاء</label>
                                                <input type="number" step="0.01" class="form-control" name="evac_amount"
                                                       value="{{ old('evac_amount', $license->evac_amount) }}">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">مرفق الإخلاءات الجديدة</label>
                                                <input type="file" class="form-control" name="evacuations_files[]" multiple accept=".pdf,.jpg,.jpeg,.png">
                                                <small class="text-muted">يمكنك رفع ملفات جديدة، الملفات الحالية ستبقى محفوظة</small>
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
                                                       value="{{ old('violation_license_number', $license->violation_license_number) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">قيمة الرخصة</label>
                                                <input type="number" step="0.01" class="form-control" name="violation_license_value"
                                                       value="{{ old('violation_license_value', $license->violation_license_value) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">تاريخ الرخصة</label>
                                                <input type="date" class="form-control" name="violation_license_date"
                                                       value="{{ old('violation_license_date', $license->violation_license_date ? $license->violation_license_date->format('Y-m-d') : '') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">آخر موعد سداد للمخالفة</label>
                                                <input type="date" class="form-control" name="violation_due_date"
                                                       value="{{ old('violation_due_date', $license->violation_due_date ? $license->violation_due_date->format('Y-m-d') : '') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">رقم المخالفة</label>
                                                <input type="text" class="form-control" name="violation_number"
                                                       value="{{ old('violation_number', $license->violation_number) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">رقم سداد المخالفة</label>
                                                <input type="text" class="form-control" name="violation_payment_number"
                                                       value="{{ old('violation_payment_number', $license->violation_payment_number) }}">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">المسبب</label>
                                                <input type="text" class="form-control" name="violation_cause"
                                                       value="{{ old('violation_cause', $license->violation_cause) }}">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">مرفق المخالفات الجديدة</label>
                                                <input type="file" class="form-control" name="violations_files[]" multiple accept=".pdf,.jpg,.jpeg,.png">
                                                <small class="text-muted">يمكنك رفع ملفات جديدة، الملفات الحالية ستبقى محفوظة</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- قسم المرفقات -->
                            <div id="files-section" class="tab-section" style="display: none;">
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
                                                <label class="form-label">شهادة التنسيق</label>
                                                <input type="file" class="form-control" name="coordination_certificate" accept=".pdf,.jpg,.jpeg,.png">
                                                @if($license->coordination_certificate_path)
                                                    <small class="text-success">✓ يوجد ملف محفوظ</small>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">الخطابات والتعهدات</label>
                                                <input type="file" class="form-control" name="letters_and_commitments" accept=".pdf,.jpg,.jpeg,.png">
                                                @if($license->letters_and_commitments_path)
                                                    <small class="text-success">✓ يوجد ملف محفوظ</small>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">ملف الرخصة</label>
                                                <input type="file" class="form-control" name="license_1" accept=".pdf,.jpg,.jpeg,.png">
                                                @if($license->license_1_path)
                                                    <small class="text-success">✓ يوجد ملف محفوظ</small>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">ملف تمديد الرخصة</label>
                                                <input type="file" class="form-control" name="license_extension_file" accept=".pdf,.jpg,.jpeg,.png">
                                                @if($license->license_extension_file_path)
                                                    <small class="text-success">✓ يوجد ملف محفوظ</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- زر الحفظ -->
                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-success btn-lg px-5">
                                        <i class="fas fa-save me-2"></i>
                                        حفظ التعديلات
                                    </button>
                                    @if(isset($workOrder) && $workOrder)
                                        <a href="{{ route('admin.work-orders.license', $workOrder) }}" class="btn btn-secondary btn-lg px-5 ms-3">
                                            <i class="fas fa-times me-2"></i>
                                            إلغاء
                                        </a>
                                    @elseif($license->workOrder)
                                        <a href="{{ route('admin.work-orders.license', $license->workOrder) }}" class="btn btn-secondary btn-lg px-5 ms-3">
                                            <i class="fas fa-times me-2"></i>
                                            إلغاء
                                        </a>
                                    @else
                                        <a href="{{ route('admin.licenses.data') }}" class="btn btn-secondary btn-lg px-5 ms-3">
                                            <i class="fas fa-times me-2"></i>
                                            إلغاء
                                        </a>
                                    @endif
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
            
            // إزالة الفئة النشطة من جميع الأزرار
            tabButtons.forEach(btn => {
                btn.classList.remove('btn-primary', 'btn-success', 'btn-warning', 'btn-danger', 'btn-secondary');
                btn.classList.add('btn-outline-primary');
            });
            
            // إظهار القسم المحدد
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                targetSection.style.display = 'block';
            }
            
            // تفعيل الزر المحدد بلون مناسب
            this.classList.remove('btn-outline-primary');
            if (targetId.includes('basic')) {
                this.classList.add('btn-primary');
            } else if (targetId.includes('dates')) {
                this.classList.add('btn-success');
            } else if (targetId.includes('evacuations')) {
                this.classList.add('btn-warning');
            } else if (targetId.includes('violations')) {
                this.classList.add('btn-danger');
            } else if (targetId.includes('files')) {
                this.classList.add('btn-secondary');
            }
        });
    });
    
    // إظهار القسم الأول افتراضياً
    if (tabButtons.length > 0) {
        tabButtons[0].click();
    }
    
    // إظهار/إخفاء حقل جهة الحظر
    const hasRestrictionSelect = document.getElementById('has_restriction');
    const restrictionAuthorityField = document.getElementById('restriction_authority_field');
    
    function toggleRestrictionAuthority() {
        if (hasRestrictionSelect.value == '1') {
            restrictionAuthorityField.style.display = 'block';
        } else {
            restrictionAuthorityField.style.display = 'none';
        }
    }
    
    hasRestrictionSelect.addEventListener('change', toggleRestrictionAuthority);
    toggleRestrictionAuthority(); // تشغيل عند التحميل
    
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
    document.getElementById('licenseEditForm').addEventListener('submit', function(e) {
        const confirmation = confirm('هل أنت متأكد من حفظ التعديلات؟');
        if (!confirmation) {
            e.preventDefault();
        }
    });
});
</script>
@endpush

<style>
/* نفس الأنماط من صفحة الرخصة الأساسية */
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

.btn-secondary {
    background: linear-gradient(45deg, #607D8B, #78909C);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #1976D2, #2196F3, #42A5F5);
}

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

.fas, .far {
    transition: transform 0.3s ease;
}

.btn:hover .fas,
.btn:hover .far {
    transform: scale(1.1);
}

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

.shadow-lg {
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
}

.border-0 {
    border: none !important;
}

.bg-primary { background: linear-gradient(45deg, #1976D2, #2196F3) !important; }
.bg-success { background: linear-gradient(45deg, #388E3C, #4CAF50) !important; }
.bg-info { background: linear-gradient(45deg, #0097A7, #00BCD4) !important; }
.bg-warning { background: linear-gradient(45deg, #F57C00, #FF9800) !important; }
.bg-danger { background: linear-gradient(45deg, #D32F2F, #F44336) !important; }
.bg-secondary { background: linear-gradient(45deg, #424242, #616161) !important; }
</style>
@endsection 