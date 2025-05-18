@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- قسم تعديل الرخصة -->
            <div class="card license-form">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            تعديل الرخصة #{{ $license->id }} 
                            @if($workOrder)
                                - أمر العمل #{{ $workOrder->id }}
                            @endif
                        </h3>
                        <div>
                            <a href="{{ route('admin.work-orders.licenses.data') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-right"></i> العودة لقائمة الرخص
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle ml-1"></i>
                        أنت تقوم بتعديل بيانات الرخصة رقم {{ $license->id }}
                    </div>
                    
                    <form action="{{ route('admin.licenses.update', $license) }}" method="POST" enctype="multipart/form-data" id="licenseForm">
                        @csrf
                        @method('PUT')

                        <!-- شهادات التنسيق -->
                        <div class="form-section mb-4">
                            <h4 class="section-title">شهادات التنسيق</h4>
                            <div class="file-upload-container">
                                <label for="coordination_certificate" class="form-label">رفع شهادات التنسيق</label>
                                <input type="file" class="form-control" id="coordination_certificate" name="coordination_certificate">
                                @if($license->coordination_certificate_path)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $license->coordination_certificate_path) }}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> عرض الشهادة الحالية
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
                                        <label class="form-label d-flex align-items-center mb-2">
                                            <i class="fas fa-ban ml-2 text-danger"></i>
                                            <span>هل يوجد حظر؟</span>
                                        </label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="has_restriction" value="1" id="has_restriction_yes" {{ $license->has_restriction ? 'checked' : '' }} onchange="toggleRestrictionDetails()">
                                            <label class="form-check-label" for="has_restriction_yes">نعم</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="has_restriction" value="0" id="has_restriction_no" {{ !$license->has_restriction ? 'checked' : '' }} onchange="toggleRestrictionDetails()">
                                            <label class="form-check-label" for="has_restriction_no">لا</label>
                                        </div>
                                        <div class="restriction-details mt-3" id="restrictionDetails" style="display: {{ $license->has_restriction ? 'block' : 'none' }};">
                                            <div class="form-group mb-3">
                                                <label for="restriction_authority" class="form-label">جهة الحظر</label>
                                                <input type="text" class="form-control" id="restriction_authority" name="restriction_authority" value="{{ $license->restriction_authority }}" placeholder="أدخل جهة الحظر...">
                                            </div>
                                            <div class="form-group">
                                                <label for="restriction_reason" class="form-label">سبب الحظر</label>
                                                <textarea class="form-control" id="restriction_reason" name="restriction_reason" rows="3" placeholder="أدخل سبب الحظر هنا...">{{ $license->restriction_reason }}</textarea>
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
                                        @if($license->letters_and_commitments_path)
                                            <div class="mt-3">
                                                <a href="{{ asset('storage/' . $license->letters_and_commitments_path) }}" target="_blank" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> عرض المرفق الحالي
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
                                            @if($license->license_1_path)
                                                <div class="mt-2">
                                                    <a href="{{ asset('storage/' . $license->license_1_path) }}" target="_blank" class="btn btn-info w-100">
                                                        <i class="fas fa-eye ml-1"></i> عرض المرفق الحالي
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
                                            <input type="number" class="form-control" id="license_length" name="license_length" value="{{ $license->license_length }}" step="0.01" min="0">
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
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="license_start_date" class="form-label d-flex align-items-center mb-2">
                                                        <i class="fas fa-calendar-plus ml-2 text-success"></i>
                                                        <span>تاريخ بداية الرخصة</span>
                                                    </label>
                                                    <input type="date" class="form-control" id="license_start_date" name="license_start_date" value="{{ $license->license_start_date ? $license->license_start_date->format('Y-m-d') : '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="license_end_date" class="form-label d-flex align-items-center mb-2">
                                                        <i class="fas fa-calendar-minus ml-2 text-danger"></i>
                                                        <span>تاريخ نهاية الرخصة</span>
                                                    </label>
                                                    <input type="date" class="form-control" id="license_end_date" name="license_end_date" value="{{ $license->license_end_date ? $license->license_end_date->format('Y-m-d') : '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- الاختبارات - مختصر -->
                        <div class="form-section">
                            <h4 class="section-title">الاختبارات</h4>
                            <div class="tests-container">
                                <!-- اختبار العمق -->
                                <div class="test-item">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-ruler-vertical ml-2 text-primary"></i>
                                            <span class="test-name">اختبار العمق</span>
                                        </div>
                                        <div class="test-options">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_depth_test" value="1" id="has_depth_test_yes" {{ $license->has_depth_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_depth_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_depth_test" value="0" id="has_depth_test_no" {{ !$license->has_depth_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_depth_test_no">لا</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- اختبار دك التربة -->
                                <div class="test-item">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-compress-alt ml-2 text-primary"></i>
                                            <span class="test-name">اختبار دك التربة</span>
                                        </div>
                                        <div class="test-options">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_soil_compaction_test" value="1" id="has_soil_compaction_test_yes" {{ $license->has_soil_compaction_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_soil_compaction_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_soil_compaction_test" value="0" id="has_soil_compaction_test_no" {{ !$license->has_soil_compaction_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_soil_compaction_test_no">لا</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- اختبار RC1-MC1 -->
                                <div class="test-item">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-vial ml-2 text-primary"></i>
                                            <span class="test-name">اختبار RC1-MC1</span>
                                        </div>
                                        <div class="test-options">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_rc1_mc1_test" value="1" id="has_rc1_mc1_test_yes" {{ $license->has_rc1_mc1_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_rc1_mc1_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_rc1_mc1_test" value="0" id="has_rc1_mc1_test_no" {{ !$license->has_rc1_mc1_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_rc1_mc1_test_no">لا</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- اختبار الأسفلت -->
                                <div class="test-item">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-road ml-2 text-primary"></i>
                                            <span class="test-name">اختبار الأسفلت</span>
                                        </div>
                                        <div class="test-options">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_asphalt_test" value="1" id="has_asphalt_test_yes" {{ $license->has_asphalt_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_asphalt_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_asphalt_test" value="0" id="has_asphalt_test_no" {{ !$license->has_asphalt_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_asphalt_test_no">لا</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- اختبار التربة -->
                                <div class="test-item">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-mountain ml-2 text-primary"></i>
                                            <span class="test-name">اختبار التربة</span>
                                        </div>
                                        <div class="test-options">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_soil_test" value="1" id="has_soil_test_yes" {{ $license->has_soil_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_soil_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_soil_test" value="0" id="has_soil_test_no" {{ !$license->has_soil_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_soil_test_no">لا</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- اختبار البلاط والانترلوك -->
                                <div class="test-item">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-th ml-2 text-primary"></i>
                                            <span class="test-name">اختبار البلاط والانترلوك</span>
                                        </div>
                                        <div class="test-options">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_interlock_test" value="1" id="has_interlock_test_yes" {{ $license->has_interlock_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_interlock_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_interlock_test" value="0" id="has_interlock_test_no" {{ !$license->has_interlock_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_interlock_test_no">لا</label>
                                            </div>
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
                                    @if($license->license_extension_file_path)
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $license->license_extension_file_path) }}" target="_blank" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> عرض المرفق الحالي
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="license_extension_start_date" class="form-label">تاريخ بداية التمديد</label>
                                            <input type="date" name="license_extension_start_date" class="form-control date-input" id="license_extension_start_date" value="{{ $license->license_extension_start_date ? $license->license_extension_start_date->format('Y-m-d') : '' }}" onchange="calculateExtensionDays()">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="license_extension_end_date" class="form-label">تاريخ نهاية التمديد</label>
                                            <input type="date" name="license_extension_end_date" class="form-control date-input" id="license_extension_end_date" value="{{ $license->license_extension_end_date ? $license->license_extension_end_date->format('Y-m-d') : '' }}" onchange="calculateExtensionDays()">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- تمديد الفاتورة -->
                            <div class="form-section">
                                <h4 class="section-title">تمديد الفاتورة</h4>
                                <div class="file-upload-container">
                                    <label class="form-label">رفع مرفق تمديد الفاتورة</label>
                                    <input type="file" name="invoice_extension_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                    @if($license->invoice_extension_file_path)
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $license->invoice_extension_file_path) }}" target="_blank" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> عرض المرفق الحالي
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="invoice_extension_start_date" class="form-label">تاريخ بداية التمديد</label>
                                            <input type="date" name="invoice_extension_start_date" class="form-control date-input" id="invoice_extension_start_date" value="{{ $license->invoice_extension_start_date ? $license->invoice_extension_start_date->format('Y-m-d') : '' }}" onchange="calculateInvoiceExtensionDays()">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="invoice_extension_end_date" class="form-label">تاريخ نهاية التمديد</label>
                                            <input type="date" name="invoice_extension_end_date" class="form-control date-input" id="invoice_extension_end_date" value="{{ $license->invoice_extension_end_date ? $license->invoice_extension_end_date->format('Y-m-d') : '' }}" onchange="calculateInvoiceExtensionDays()">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="multi-section">
                            <!-- نتائج الاختبار -->
                            <div class="form-section">
                                <h4 class="section-title">نتائج الاختبار</h4>
                                <div class="file-upload-container">
                                    <label class="form-label">رفع مرفق نتائج الاختبار</label>
                                    <input type="file" name="test_results_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                    @if($license->test_results_file_path)
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $license->test_results_file_path) }}" target="_blank" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> عرض المرفق الحالي
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
                                    @if($license->final_inspection_file_path)
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $license->final_inspection_file_path) }}" target="_blank" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> عرض المرفق الحالي
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
                                    @if($license->license_closure_file_path)
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $license->license_closure_file_path) }}" target="_blank" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> عرض المرفق الحالي
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="submit-section mt-4">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save ml-1"></i> حفظ التعديلات
                            </button>
                            <a href="{{ route('admin.work-orders.licenses.data') }}" class="btn btn-secondary ms-2">
                                <i class="fas fa-times ml-1"></i> إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleRestrictionDetails() {
    const hasRestriction = document.getElementById('has_restriction_yes').checked;
    const restrictionDetails = document.getElementById('restrictionDetails');
    restrictionDetails.style.display = hasRestriction ? 'block' : 'none';
    
    if (!hasRestriction) {
        document.getElementById('restriction_authority').value = '';
        document.getElementById('restriction_reason').value = '';
    }
}

function calculateExtensionDays() {
    const startDate = document.getElementById('license_extension_start_date').value;
    const endDate = document.getElementById('license_extension_end_date').value;
    
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        if (start > end) {
            alert('تاريخ بداية تمديد الرخصة يجب أن يكون قبل تاريخ النهاية');
            document.getElementById('license_extension_end_date').value = '';
            return;
        }
    }
}

function calculateInvoiceExtensionDays() {
    const startDate = document.getElementById('invoice_extension_start_date').value;
    const endDate = document.getElementById('invoice_extension_end_date').value;
    
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        if (start > end) {
            alert('تاريخ بداية تمديد الفاتورة يجب أن يكون قبل تاريخ النهاية');
            document.getElementById('invoice_extension_end_date').value = '';
            return;
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleRestrictionDetails();
    
    // إضافة مستمعي أحداث للتحقق من التواريخ
    document.getElementById('license_extension_start_date').addEventListener('change', calculateExtensionDays);
    document.getElementById('license_extension_end_date').addEventListener('change', calculateExtensionDays);
    document.getElementById('invoice_extension_start_date').addEventListener('change', calculateInvoiceExtensionDays);
    document.getElementById('invoice_extension_end_date').addEventListener('change', calculateInvoiceExtensionDays);
});
</script>

<style>
/* الألوان الأساسية المريحة للعين */
:root {
    --primary-color: #4a90e2;
    --secondary-color: #6c757d;
    --success-color: #2ecc71;
    --danger-color: #e74c3c;
    --warning-color: #f1c40f;
    --info-color: #3498db;
    --light-bg: #f8f9fa;
    --dark-bg: #2c3e50;
    --border-color: #e9ecef;
    --text-color: #34495e;
    --text-muted: #7f8c8d;
}

/* تنسيق البطاقات */
.card {
    border: none;
    border-radius: 0.375rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    transition: all 0.2s ease;
    background-color: #fff;
}

.card-header {
    background-color: #fff;
    border-bottom: 1px solid var(--border-color);
    padding: 1rem;
}

.card-header h3 {
    color: var(--text-color);
    font-weight: 600;
    margin: 0;
    font-size: 1.1rem;
}

/* تنسيق النماذج والأقسام */
.form-section {
    background-color: #fff;
    padding: 1.25rem;
    border-radius: 0.375rem;
    margin-bottom: 1.25rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
}

.section-title {
    color: var(--text-color);
    font-weight: 600;
    margin-bottom: 1.25rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--border-color);
    font-size: 1rem;
}
</style>
@endsection 