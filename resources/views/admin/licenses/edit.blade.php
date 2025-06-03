@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-gradient-warning text-dark py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 fs-4">
                            <i class="fas fa-edit me-2"></i>
                            تعديل الرخصة - {{ $license->license_number ?? 'غير محدد' }}
                        </h3>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.licenses.show', $license) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye me-1"></i> عرض
                            </a>
                            <a href="{{ route('admin.work-orders.license', $license->workOrder) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-right me-1"></i> عودة
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('admin.licenses.update', $license) }}" method="POST" enctype="multipart/form-data" id="editLicenseForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- معلومات أساسية -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    المعلومات الأساسية
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <label for="license_number" class="form-label">رقم الرخصة</label>
                                <input type="text" class="form-control" id="license_number" name="license_number" 
                                       value="{{ old('license_number', $license->license_number) }}">
                            </div>
                            <div class="col-md-6">
                                <label for="license_date" class="form-label">تاريخ الرخصة</label>
                                <input type="date" class="form-control" id="license_date" name="license_date" 
                                       value="{{ old('license_date', $license->license_date ? $license->license_date->format('Y-m-d') : '') }}">
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
                        </div>

                        <!-- التواريخ -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-info mb-3">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    التواريخ
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <label for="license_start_date" class="form-label">تاريخ بداية الرخصة</label>
                                <input type="date" class="form-control" id="license_start_date" name="license_start_date" 
                                       value="{{ old('license_start_date', $license->license_start_date ? $license->license_start_date->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="license_end_date" class="form-label">تاريخ نهاية الرخصة</label>
                                <input type="date" class="form-control" id="license_end_date" name="license_end_date" 
                                       value="{{ old('license_end_date', $license->license_end_date ? $license->license_end_date->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="extension_start_date" class="form-label">تاريخ بداية التمديد</label>
                                <input type="date" class="form-control" id="extension_start_date" name="extension_start_date" 
                                       value="{{ old('extension_start_date', $license->license_extension_start_date ? $license->license_extension_start_date->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="extension_end_date" class="form-label">تاريخ نهاية التمديد</label>
                                <input type="date" class="form-control" id="extension_end_date" name="extension_end_date" 
                                       value="{{ old('extension_end_date', $license->license_extension_end_date ? $license->license_extension_end_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>

                        <!-- أبعاد الحفر -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-warning mb-3">
                                    <i class="fas fa-ruler-combined me-2"></i>
                                    أبعاد الحفر
                                </h5>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">طول الحفر</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control" name="excavation_length"
                                           value="{{ old('excavation_length', $license->excavation_length) }}">
                                    <span class="input-group-text">متر</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">عرض الحفر</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control" name="excavation_width"
                                           value="{{ old('excavation_width', $license->excavation_width) }}">
                                    <span class="input-group-text">متر</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">عمق الحفر</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control" name="excavation_depth"
                                           value="{{ old('excavation_depth', $license->excavation_depth) }}">
                                    <span class="input-group-text">متر</span>
                                </div>
                            </div>
                        </div>

                        <!-- حالة الحظر -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-danger mb-3">
                                    <i class="fas fa-ban me-2"></i>
                                    حالة الحظر
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <label for="has_restriction" class="form-label">يوجد حظر؟</label>
                                <select class="form-select" name="has_restriction" id="has_restriction">
                                    <option value="0" {{ old('has_restriction', $license->has_restriction) == 0 ? 'selected' : '' }}>لا</option>
                                    <option value="1" {{ old('has_restriction', $license->has_restriction) == 1 ? 'selected' : '' }}>نعم</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="restriction_authority_field" style="{{ $license->has_restriction ? 'display: block;' : 'display: none;' }}">
                                <label for="restriction_authority" class="form-label">جهة الحظر</label>
                                <input type="text" class="form-control" id="restriction_authority" name="restriction_authority" 
                                       value="{{ old('restriction_authority', $license->restriction_authority) }}" 
                                       placeholder="اسم الجهة المسؤولة عن الحظر">
                            </div>
                        </div>

                        <!-- الاختبارات المطلوبة -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-success mb-3">
                                    <i class="fas fa-flask me-2"></i>
                                    الاختبارات المطلوبة
                                </h5>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">اختبار العمق</label>
                                <select class="form-select" name="has_depth_test">
                                    <option value="0" {{ old('has_depth_test', $license->has_depth_test) == 0 ? 'selected' : '' }}>لا</option>
                                    <option value="1" {{ old('has_depth_test', $license->has_depth_test) == 1 ? 'selected' : '' }}>نعم</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">اختبار التربة</label>
                                <select class="form-select" name="has_soil_test">
                                    <option value="0" {{ old('has_soil_test', $license->has_soil_test) == 0 ? 'selected' : '' }}>لا</option>
                                    <option value="1" {{ old('has_soil_test', $license->has_soil_test) == 1 ? 'selected' : '' }}>نعم</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">اختبار الأسفلت</label>
                                <select class="form-select" name="has_asphalt_test">
                                    <option value="0" {{ old('has_asphalt_test', $license->has_asphalt_test) == 0 ? 'selected' : '' }}>لا</option>
                                    <option value="1" {{ old('has_asphalt_test', $license->has_asphalt_test) == 1 ? 'selected' : '' }}>نعم</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">اختبار دك التربة</label>
                                <select class="form-select" name="has_soil_compaction_test">
                                    <option value="0" {{ old('has_soil_compaction_test', $license->has_soil_compaction_test) == 0 ? 'selected' : '' }}>لا</option>
                                    <option value="1" {{ old('has_soil_compaction_test', $license->has_soil_compaction_test) == 1 ? 'selected' : '' }}>نعم</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">اختبار RC1/MC1</label>
                                <select class="form-select" name="has_rc1_mc1_test">
                                    <option value="0" {{ old('has_rc1_mc1_test', $license->has_rc1_mc1_test) == 0 ? 'selected' : '' }}>لا</option>
                                    <option value="1" {{ old('has_rc1_mc1_test', $license->has_rc1_mc1_test) == 1 ? 'selected' : '' }}>نعم</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">اختبار انترلوك</label>
                                <select class="form-select" name="has_interlock_test">
                                    <option value="0" {{ old('has_interlock_test', $license->has_interlock_test) == 0 ? 'selected' : '' }}>لا</option>
                                    <option value="1" {{ old('has_interlock_test', $license->has_interlock_test) == 1 ? 'selected' : '' }}>نعم</option>
                                </select>
                            </div>
                        </div>

                        <!-- الإخلاءات -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-warning mb-3">
                                    <i class="fas fa-truck-moving me-2"></i>
                                    معلومات الإخلاءات
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">تم الإخلاء؟</label>
                                <select class="form-select" name="is_evacuated">
                                    <option value="0" {{ old('is_evacuated', $license->is_evacuated) == 0 ? 'selected' : '' }}>لا</option>
                                    <option value="1" {{ old('is_evacuated', $license->is_evacuated) == 1 ? 'selected' : '' }}>نعم</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">رقم رخصة الإخلاء</label>
                                <input type="text" class="form-control" name="evac_license_number"
                                       value="{{ old('evac_license_number', $license->evac_license_number) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">قيمة رخصة الإخلاء</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control" name="evac_license_value"
                                           value="{{ old('evac_license_value', $license->evac_license_value) }}">
                                    <span class="input-group-text">ريال</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">رقم سداد الإخلاء</label>
                                <input type="text" class="form-control" name="evac_payment_number"
                                       value="{{ old('evac_payment_number', $license->evac_payment_number) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">تاريخ الإخلاء</label>
                                <input type="date" class="form-control" name="evac_date"
                                       value="{{ old('evac_date', $license->evac_date ? $license->evac_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>

                        <!-- المخالفات -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-danger mb-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    معلومات المخالفات
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">رقم المخالفة</label>
                                <input type="text" class="form-control" name="violation_number"
                                       value="{{ old('violation_number', $license->violation_number) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">قيمة رخصة المخالفة</label>
                                <input type="number" step="0.01" class="form-control" name="violation_license_value"
                                       value="{{ old('violation_license_value', $license->violation_license_value) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">تاريخ رخصة المخالفة</label>
                                <input type="date" class="form-control" name="violation_license_date"
                                       value="{{ old('violation_license_date', $license->violation_license_date ? $license->violation_license_date->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">آخر موعد سداد المخالفة</label>
                                <input type="date" class="form-control" name="violation_due_date"
                                       value="{{ old('violation_due_date', $license->violation_due_date ? $license->violation_due_date->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">مسبب المخالفة</label>
                                <input type="text" class="form-control" name="violation_cause"
                                       value="{{ old('violation_cause', $license->violation_cause) }}">
                            </div>
                        </div>

                        <!-- الملاحظات -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-secondary mb-3">
                                    <i class="fas fa-sticky-note me-2"></i>
                                    الملاحظات
                                </h5>
                            </div>
                            <div class="col-12">
                                <label class="form-label">ملاحظات</label>
                                <textarea class="form-control" name="notes" rows="4" 
                                          placeholder="أدخل أي ملاحظات إضافية هنا...">{{ old('notes', $license->notes) }}</textarea>
                            </div>
                        </div>

                        <!-- أزرار الحفظ والإلغاء -->
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success btn-lg px-5 me-3">
                                    <i class="fas fa-save me-2"></i>
                                    حفظ التعديلات
                                </button>
                                <a href="{{ route('admin.licenses.show', $license) }}" class="btn btn-secondary btn-lg px-5">
                                    <i class="fas fa-times me-2"></i>
                                    إلغاء
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
});
</script>
@endpush

<style>
.bg-gradient-warning {
    background: linear-gradient(135deg, #FF9800, #FFB74D, #FFCC02);
}

.card {
    border-radius: 1rem;
}

.card-header {
    border-radius: 1rem 1rem 0 0 !important;
}

h5 {
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 0.5rem;
}

.form-control, .form-select {
    border-radius: 0.5rem;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #2196F3;
    box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25);
}

.btn {
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
</style> 