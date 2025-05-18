@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <h3 class="mb-0 fs-4 text-center text-md-start">رخصة العمل</h3>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-back btn-sm">
                                <i class="fas fa-arrow-right"></i> عودة للتفاصيل
                            </a>
                            <a href="{{ route('admin.work-orders.licenses.data') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-list"></i> بيانات الرخص
                            </a>
                            @if(isset($license) && $license->id)
                                <a href="{{ route('admin.licenses.show', $license->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> عرض تفاصيل الرخصة
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body p-3 p-md-4">
                    <form action="{{ route('admin.work-orders.upload-license', $workOrder) }}" method="POST" enctype="multipart/form-data" id="licenseForm">
                        @csrf
                        <div class="row g-4">
                            <!-- معلومات الرخصة الأساسية -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h4 class="mb-0 fs-5">
                                            <i class="fas fa-file-alt text-primary me-2"></i>
                                            معلومات الرخصة الأساسية
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="license_number" class="form-label">رقم الرخصة</label>
                                                    <input type="text" class="form-control @error('license_number') is-invalid @enderror" 
                                                           id="license_number" name="license_number" 
                                                           value="{{ old('license_number', $license->license_number ?? '') }}" required>
                                                    @error('license_number')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="license_date" class="form-label">تاريخ الرخصة</label>
                                                    <input type="date" class="form-control @error('license_date') is-invalid @enderror" 
                                                           id="license_date" name="license_date" 
                                                           value="{{ old('license_date', $license->license_date ?? '') }}" required>
                                                    @error('license_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="license_type" class="form-label">نوع الرخصة</label>
                                                    <select class="form-select" id="license_type" name="license_type" required>
                                                        <option value="">اختر نوع الرخصة</option>
                                                        <option value="مشروع">مشروع</option>
                                                        <option value="طوارئ">طوارئ</option>
                                                        <option value="عادي">عادي</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="license_file" class="form-label">ملف الرخصة</label>
                                                    <input type="file" class="form-control @error('license_file') is-invalid @enderror" 
                                                           id="license_file" name="license_file" 
                                                           accept=".pdf,.jpg,.jpeg,.png" required>
                                                    <small class="text-muted">يمكن رفع ملفات PDF أو صور (JPG, PNG) بحد أقصى 10 ميجابايت</small>
                                                    @error('license_file')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- تواريخ الرخصة -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h4 class="mb-0 fs-5">
                                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                                            تواريخ الرخصة
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">تاريخ بداية الرخصة</label>
                                                <input type="date" class="form-control" name="license_start_date" id="license_start_date">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">تاريخ نهاية الرخصة</label>
                                                <input type="date" class="form-control" name="license_end_date" id="license_end_date">
                                            </div>
                                            <div class="col-md-4 d-flex align-items-end">
                                                <span id="license_days_left" class="fw-bold"></span>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">تاريخ بداية التمديد</label>
                                                <input type="date" class="form-control" name="extension_start_date" id="extension_start_date">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">تاريخ نهاية التمديد</label>
                                                <input type="date" class="form-control" name="extension_end_date" id="extension_end_date">
                                            </div>
                                            <div class="col-md-4 d-flex align-items-end">
                                                <span id="extension_days_left" class="fw-bold"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- المرفقات -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h4 class="mb-0 fs-5">
                                            <i class="fas fa-paperclip text-primary me-2"></i>
                                            المرفقات
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">شهادات التنسيق</label>
                                                <input type="file" class="form-control" name="coordination_certificates[]" multiple>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">الخطابات والتعهدات</label>
                                                <input type="file" class="form-control" name="letters_undertakings[]" multiple>
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
                                            <div class="col-md-6">
                                                <label class="form-label">فاتورة تمديد الرخصة</label>
                                                <input type="file" class="form-control" name="extension_invoice[]" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- معلومات إضافية -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h4 class="mb-0 fs-5">
                                            <i class="fas fa-info-circle text-primary me-2"></i>
                                            معلومات إضافية
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">يوجد حظر؟</label>
                                                <select class="form-select" name="has_restriction" id="has_restriction">
                                                    <option value="0">لا</option>
                                                    <option value="1">نعم</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6" id="restriction_agency_container" style="display:none;">
                                                <label class="form-label">جهة الحظر</label>
                                                <input type="text" class="form-control" name="restriction_agency">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">ملاحظات</label>
                                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                                          id="notes" name="notes" rows="3">{{ old('notes', $license->notes ?? '') }}</textarea>
                                                @error('notes')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- قسم الاختبارات -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h4 class="mb-0 fs-5">
                                            <i class="fas fa-vial text-primary me-2"></i>
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
                                                            <select class="form-select" name="has_depth_test" id="has_depth_test">
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
                                                            <select class="form-select" name="has_soil_compaction_test" id="has_soil_compaction_test">
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
                                                            <select class="form-select" name="has_rc1_mc1_test" id="has_rc1_mc1_test">
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
                                                            <select class="form-select" name="has_asphalt_test" id="has_asphalt_test">
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
                                                            <select class="form-select" name="has_soil_test" id="has_soil_test">
                                                                <option value="0" {{ old('has_soil_test', $license->has_soil_test ?? 0) == 0 ? 'selected' : '' }}>لا</option>
                                                                <option value="1" {{ old('has_soil_test', $license->has_soil_test ?? 0) == 1 ? 'selected' : '' }}>نعم</option>
                                                            </select>
                                                        </div>
                                                        <div class="mt-2">
                                                            <input type="file" class="form-control form-control-sm" name="soil_test_file" accept=".pdf,.jpg,.jpeg,.png">
                                                            <small class="text-muted d-block mt-1">مرفق صور التربة (حد أقصى 10 صور)</small>
                                                            <div class="soil-test-images-container">
                                                                <input type="file" class="form-control form-control-sm mt-1" name="soil_test_images[]" 
                                                                       id="soil_test_images" multiple accept=".jpg,.jpeg,.png" 
                                                                       onchange="previewSoilTestImages(this)">
                                                                <div id="soil_test_images_preview" class="row g-2 mt-2"></div>
                                                                <small class="text-danger" id="soil_test_images_error"></small>
                                                            </div>
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
                                                            <select class="form-select" name="has_interlock_test" id="has_interlock_test">
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
                            </div>

                            <!-- زر الحفظ -->
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i> حفظ الرخصة
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// تفعيل/تعطيل حقول الملفات بناءً على اختيار نعم/لا
document.querySelectorAll('select[name^="has_"]').forEach(select => {
    select.addEventListener('change', function() {
        const fileInputs = this.closest('.card-body').querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.disabled = this.value === '0';
            input.closest('.card').classList.toggle('border-primary', this.value === '1');
        });
    });
});

// تفعيل/تعطيل حقول الملفات عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('select[name^="has_"]').forEach(select => {
        const fileInputs = select.closest('.card-body').querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.disabled = select.value === '0';
            input.closest('.card').classList.toggle('border-primary', select.value === '1');
        });
    });
});

// حساب الأيام المتبقية للرخصة
function calculateDaysLeft(startDate, endDate, targetElement) {
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        const today = new Date();
        const daysLeft = Math.ceil((end - today) / (1000 * 60 * 60 * 24));
        
        if (daysLeft > 0) {
            targetElement.textContent = `متبقي ${daysLeft} يوم`;
            targetElement.className = 'fw-bold text-success';
        } else {
            targetElement.textContent = 'انتهت الرخصة';
            targetElement.className = 'fw-bold text-danger';
        }
    }
}

// تحديث الأيام المتبقية عند تغيير التواريخ
['license_start_date', 'license_end_date'].forEach(id => {
    document.getElementById(id).addEventListener('change', function() {
        calculateDaysLeft(
            document.getElementById('license_start_date').value,
            document.getElementById('license_end_date').value,
            document.getElementById('license_days_left')
        );
    });
});

['extension_start_date', 'extension_end_date'].forEach(id => {
    document.getElementById(id).addEventListener('change', function() {
        calculateDaysLeft(
            document.getElementById('extension_start_date').value,
            document.getElementById('extension_end_date').value,
            document.getElementById('extension_days_left')
        );
    });
});

// معاينة صور اختبار التربة
function previewSoilTestImages(input) {
    const preview = document.getElementById('soil_test_images_preview');
    const errorElement = document.getElementById('soil_test_images_error');
    preview.innerHTML = '';
    errorElement.textContent = '';

    if (input.files.length > 10) {
        errorElement.textContent = 'يمكنك رفع 10 صور كحد أقصى';
        input.value = '';
        return;
    }

    const totalSize = Array.from(input.files).reduce((acc, file) => acc + file.size, 0);
    if (totalSize > 10 * 1024 * 1024) { // 10MB
        errorElement.textContent = 'الحجم الإجمالي للصور يجب أن لا يتجاوز 10 ميجابايت';
        input.value = '';
        return;
    }

    Array.from(input.files).forEach((file, index) => {
        if (!file.type.match('image.*')) {
            errorElement.textContent = 'يرجى اختيار ملفات صور فقط';
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const col = document.createElement('div');
            col.className = 'col-4';
            col.innerHTML = `
                <div class="position-relative">
                    <img src="${e.target.result}" class="img-thumbnail" style="height: 100px; object-fit: cover;">
                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" 
                            onclick="removeSoilTestImage(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            preview.appendChild(col);
        };
        reader.readAsDataURL(file);
    });
}

// حذف صورة من المعاينة
function removeSoilTestImage(index) {
    const input = document.getElementById('soil_test_images');
    const dt = new DataTransfer();
    const files = input.files;

    for (let i = 0; i < files.length; i++) {
        if (i !== index) {
            dt.items.add(files[i]);
        }
    }

    input.files = dt.files;
    previewSoilTestImages(input);
}

// تحديث حالة حقل الصور عند تغيير اختيار الاختبار
document.getElementById('has_soil_test').addEventListener('change', function() {
    const container = document.querySelector('.soil-test-images-container');
    const preview = document.getElementById('soil_test_images_preview');
    const errorElement = document.getElementById('soil_test_images_error');
    
    if (this.value === '0') {
        container.style.display = 'none';
        preview.innerHTML = '';
        errorElement.textContent = '';
    } else {
        container.style.display = 'block';
    }
});

// تفعيل/تعطيل حقل الصور عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    const soilTestSelect = document.getElementById('has_soil_test');
    const container = document.querySelector('.soil-test-images-container');
    container.style.display = soilTestSelect.value === '1' ? 'block' : 'none';
});
</script>

<style>
/* تنسيق الأزرار */
.btn {
    transition: all 0.3s ease;
    border: none;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-back {
    background-color: #795548;
    color: white;
}

.btn-back:hover {
    background-color: #6D4C41;
    color: white;
}

.btn-primary {
    background-color: #2196F3;
    border-color: #2196F3;
}

.btn-primary:hover {
    background-color: #1976D2;
    border-color: #1976D2;
}

.btn-info {
    background-color: #00BCD4;
    border-color: #00BCD4;
    color: white;
}

.btn-info:hover {
    background-color: #0097A7;
    border-color: #0097A7;
    color: white;
}

.btn-danger {
    background-color: #F44336;
    border-color: #F44336;
}

.btn-danger:hover {
    background-color: #D32F2F;
    border-color: #D32F2F;
}

/* تحسين الهيدر */
.bg-gradient-primary {
    background: linear-gradient(45deg, #1976D2, #2196F3);
}

/* تحسين الأيقونات في الأزرار */
.btn i {
    transition: transform 0.3s ease;
}

.btn:hover i {
    transform: scale(1.1);
}

/* تحسين النماذج */
.form-control, .form-select {
    border-radius: 0.5rem;
    border: 1px solid #dee2e6;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #2196F3;
    box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25);
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

/* تحسين الجداول */
.table {
    margin-bottom: 0;
}

.table th {
    font-weight: 600;
    white-space: nowrap;
}

.table td {
    vertical-align: middle;
}

/* تحسين البطاقات */
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.card-header {
    border-bottom: 1px solid rgba(0,0,0,.125);
}

.card-header h4 {
    font-weight: 600;
}

/* تحسين عرض الأزرار على الشاشات الصغيرة */
@media (max-width: 768px) {
    .btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
    }
    
    .btn i {
        margin-right: 0.25rem;
    }
    
    .btn span {
        display: none;
    }
}

@media (min-width: 769px) {
    .btn {
        min-width: 120px;
    }
}

/* تنسيق معاينة صور اختبار التربة */
.soil-test-images-container {
    margin-top: 0.5rem;
}

#soil_test_images_preview {
    margin-top: 0.5rem;
}

#soil_test_images_preview img {
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: 0.25rem;
}

#soil_test_images_preview .btn-danger {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

#soil_test_images_preview .position-relative {
    margin-bottom: 0.5rem;
}

#soil_test_images_error {
    display: block;
    margin-top: 0.25rem;
}
</style>
@endsection 