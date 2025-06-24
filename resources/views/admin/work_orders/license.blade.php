@extends('layouts.app')

@section('content')
<!-- إضافة CSRF token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- تضمين مكتبات JavaScript وCSS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- Custom Styles -->
<style>
    .license-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .section-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border: 1px solid #e9ecef;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-bottom: none;
    }
    
    .section-body {
        padding: 2rem;
    }
    
    .nav-tab-btn {
        border: 2px solid transparent;
        border-radius: 10px;
        padding: 12px 20px;
        margin: 5px;
        transition: all 0.3s ease;
        background: #f8f9fa;
        color: #495057;
        font-weight: 500;
    }
    
    .nav-tab-btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #667eea;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        color: white;
    }
    
    /* أنماط النظام التفاعلي للمختبر */
    .test-card {
        transition: all 0.3s ease;
        border: 2px solid #e9ecef;
    }

    .test-card:hover {
        border-color: #007bff;
        box-shadow: 0 4px 8px rgba(0,123,255,0.1);
    }

    .test-calculation {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 8px;
        padding: 15px;
        margin-top: 10px;
        border: 1px solid #dee2e6;
    }

    .form-control-sm:focus, .form-select-sm:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .text-primary {
        color: #007bff !important;
    }

    .badge {
        font-size: 0.875em;
    }

    .fs-2 {
        font-size: 1.5rem !important;
    }

    .fs-4 {
        font-size: 1.25rem !important;
    }

    .fs-6 {
        font-size: 1rem !important;
    }

    .bg-opacity-10 {
        background-color: rgba(var(--bs-success-rgb), 0.1) !important;
    }

    .bg-danger.bg-opacity-10 {
        background-color: rgba(var(--bs-danger-rgb), 0.1) !important;
    }

    .bg-warning.bg-opacity-10 {
        background-color: rgba(var(--bs-warning-rgb), 0.1) !important;
    }

    .border-info {
        border-color: #0dcaf0 !important;
    }

    .card-header.bg-info {
        background-color: #0dcaf0 !important;
    }

    .test-calculation .row {
        align-items: end;
    }

    .form-check-input:checked {
        background-color: #007bff;
        border-color: #007bff;
    }

    .test-card .card-body {
        padding: 1.25rem;
    }

    .small {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6c757d;
    }
</style>

<script>
    $(document).ready(function() {
        // Handle restriction fields visibility
        $('#has_restriction').change(function() {
            const hasRestriction = $(this).val() === '1';
            $('#restriction_authority_field, #letters_commitments_field').toggle(hasRestriction);
            
            if (!hasRestriction) {
                $('#restriction_authority').val('');
                $('input[name="letters_commitments_files[]"]').val('');
            }
        });

        // Initialize fields visibility based on initial value
        $('#has_restriction').trigger('change');

        // Handle test file upload fields visibility
        $('.test-radio').change(function() {
            const testName = $(this).data('test');
            const showUpload = $(this).val() === '1';
            $(`.${testName}-upload`).toggle(showUpload);
            
            if (!showUpload) {
                $(`.${testName}-upload input[type="file"]`).val('');
            }
        });

        // Initialize visibility for all tests
        $('.test-radio:checked').each(function() {
            $(this).trigger('change');
        });

        // Calculate days between license dates
        function calculateDays(startDate, endDate) {
            if (!startDate || !endDate) return '';
            const start = new Date(startDate);
            const end = new Date(endDate);
            const diffTime = Math.abs(end - start);
            return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        }

        // Update license days
        $('#license_start_date, #license_end_date').change(function() {
            const startDate = $('#license_start_date').val();
            const endDate = $('#license_end_date').val();
            $('#license_days').val(calculateDays(startDate, endDate));
        });

        // Update extension days
        $('#extension_start_date, #extension_end_date').change(function() {
            const startDate = $('#extension_start_date').val();
            const endDate = $('#extension_end_date').val();
            $('#extension_days').val(calculateDays(startDate, endDate));
        });

        // Load violations on page load
        loadViolations();
    });

    function saveCoordinationSection() {
        const formData = new FormData(document.getElementById('coordinationForm'));
        
        $.ajax({
            url: '{{ route("admin.licenses.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                toastr.success('تم حفظ شهادة التنسيق بنجاح');
                
                // Refresh the page after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || {};
                Object.values(errors).forEach(error => {
                    toastr.error(error[0]);
                });
            }
        });
    }

    function saveDigLicenseSection() {
        const formData = new FormData(document.getElementById('digLicenseForm'));
        
        $.ajax({
            url: '{{ route("admin.licenses.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                toastr.success('تم حفظ رخصة الحفر بنجاح');
                
                // Refresh the page after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || {};
                Object.values(errors).forEach(error => {
                    toastr.error(error[0]);
                });
            }
        });
    }

    function saveLabSection() {
        const formData = new FormData(document.getElementById('labForm'));
        
        $.ajax({
            url: '{{ route("admin.licenses.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                toastr.success('تم حفظ بيانات المختبر بنجاح');
                
                // Refresh the page after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || {};
                Object.values(errors).forEach(error => {
                    toastr.error(error[0]);
                });
            }
        });
    }

    function saveEvacuationSection() {
        toastr.success('تم حفظ بيانات الإخلاء بنجاح');
    }

    function loadViolations() {
        $.ajax({
            url: `/admin/violations/by-work-order/{{ $workOrder->id }}`,
            type: 'GET',
            success: function(response) {
                const tbody = document.getElementById('violations-table-body');
                if (!tbody) return;
                
                tbody.innerHTML = '';

                if (!response.violations || response.violations.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="11" class="text-center">لا توجد مخالفات</td>
                        </tr>
                    `;
                    return;
                }

                response.violations.forEach((violation, index) => {
                    // تحويل التاريخ إلى التقويم الميلادي
                    const violationDate = violation.violation_date ? 
                        new Date(violation.violation_date).toLocaleDateString('en-GB') : '';
                    const dueDate = violation.payment_due_date ? 
                        new Date(violation.payment_due_date).toLocaleDateString('en-GB') : '';
                    
                    // تحديد حالة السداد
                    const paymentStatusBadge = violation.payment_status == 1 ? 
                        '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>مسددة</span>' : 
                        '<span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>غير مسددة</span>';

                    tbody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td><strong class="text-primary">${violation.violation_number || ''}</strong></td>
                            <td>${violationDate}</td>
                            <td><span class="badge bg-info">${violation.violation_type || ''}</span></td>
                            <td>${violation.responsible_party || ''}</td>
                            <td>${violation.violation_description || '-'}</td>
                            <td><strong class="text-success">${violation.violation_amount || '0'} ريال</strong></td>
                            <td>${dueDate}</td>
                            <td>${violation.payment_invoice_number || '-'}</td>
                            <td>${paymentStatusBadge}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-info" onclick="viewViolation(${violation.id})" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="deleteViolation(${violation.id})" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            },
            error: function(xhr) {
                console.error('Error loading violations:', xhr);
                toastr.error('حدث خطأ في تحميل المخالفات');
            }
        });
    }

    function saveViolationSection() {
        const form = document.getElementById('violationForm');
        if (!form) {
            toastr.error('لم يتم العثور على نموذج المخالفة');
            return;
        }

        const formData = new FormData(form);
        
        // إضافة logging للبيانات المرسلة
        console.log('Sending violation data:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }
        
        $.ajax({
            url: '{{ route("admin.license-violations.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Success response:', response);
                toastr.success('تم حفظ المخالفة بنجاح');
                resetViolationForm();
                loadViolations();
            },
            error: function(xhr) {
                console.error('Error saving violation:', xhr);
                console.error('Response text:', xhr.responseText);
                console.error('Status:', xhr.status);
                console.error('Status text:', xhr.statusText);
                
                const errors = xhr.responseJSON?.errors || {};
                if (Object.keys(errors).length > 0) {
                    Object.values(errors).forEach(error => {
                        toastr.error(error[0]);
                    });
                } else {
                    const message = xhr.responseJSON?.message || 'حدث خطأ في حفظ المخالفة';
                    toastr.error(message);
                }
            }
        });
    }

    function viewViolation(violationId) {
        // يمكن إضافة modal لعرض تفاصيل المخالفة
        toastr.info('سيتم إضافة عرض التفاصيل قريباً');
    }

    function resetViolationForm() {
        const form = document.getElementById('violationForm');
        if (form) {
            form.reset();
            
            // إعادة تعيين القيم الافتراضية
            document.querySelector('[name="violation_date"]').value = '{{ date("Y-m-d") }}';
            document.querySelector('[name="payment_due_date"]').value = '{{ date("Y-m-d", strtotime("+30 days")) }}';
            document.querySelector('[name="payment_status"][value="0"]').checked = true;
            
            toastr.info('تم إعادة تعيين النموذج');
        }
    }

    function deleteViolation(violationId) {
        if (!confirm('هل أنت متأكد من حذف هذه المخالفة؟')) {
            return;
        }

        $.ajax({
            url: `/admin/license-violations/${violationId}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                toastr.success('تم حذف المخالفة بنجاح');
                loadViolations();
            },
            error: function(xhr) {
                console.error('Error deleting violation:', xhr);
                toastr.error('حدث خطأ في حذف المخالفة');
            }
        });
    }
</script>

<div id="app">
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="license-header">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="mb-2 display-6 fw-bold">
                        <i class="fas fa-certificate me-3"></i>
                        إدارة الجودة والرخص
                    </h1>
                    <p class="mb-0 fs-5 opacity-90">
                        أمر العمل رقم: <span class="fw-bold">{{ $workOrder->order_number }}</span>
                    </p>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-light btn-lg">
                        <i class="fas fa-arrow-right me-2"></i>
                        العودة لتفاصيل أمر العمل
                    </a>
                </div>
            </div>
        </div>

        <!-- شهادة التنسيق -->
        <div class="section-card">
            <div class="section-header">
                <h3 class="mb-0">
                    <i class="fas fa-certificate me-2"></i>
                    شهادة التنسيق والمرفقات
                </h3>
            </div>
            <div class="section-body">
                <form id="coordinationForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">مرفق شهادة التنسيق</label>
                            <input type="file" class="form-control" name="coordination_certificate_path">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">رقم شهادة التنسيق</label>
                            <input type="text" class="form-control" name="coordination_certificate_number">
                        </div>
                    </div>
                    
                    <!-- حقول الحظر -->
                    <div class="row g-3 mt-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">يوجد حظر</label>
                            <select class="form-select" name="has_restriction" id="has_restriction">
                                <option value="0">لا</option>
                                <option value="1">نعم</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="restriction_authority_field" style="display: none;">
                            <label class="form-label fw-bold">جهة الحظر</label>
                            <input type="text" class="form-control" name="restriction_authority" id="restriction_authority">
                        </div>
                        <div class="col-md-4" id="letters_commitments_field" style="display: none;">
                            <label class="form-label fw-bold">مرفق الخطابات والتعهدات</label>
                            <input type="file" class="form-control" name="letters_commitments_files[]" multiple>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-primary-custom btn-lg" onclick="saveCoordinationSection()">
                                <i class="fas fa-save me-2"></i>
                                حفظ شهادة التنسيق
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="section-card">
            <div class="section-body">
                <div class="d-flex flex-wrap justify-content-center">
                    <button type="button" class="btn nav-tab-btn active" onclick="showSection('dig-license-section')">
                        <i class="fas fa-hard-hat me-2"></i>رخصة الحفر
                    </button>
                    <button type="button" class="btn nav-tab-btn" onclick="showSection('lab-section')">
                        <i class="fas fa-flask me-2"></i>المختبر
                    </button>
                    <button type="button" class="btn nav-tab-btn" onclick="showSection('evacuations-section')">
                        <i class="fas fa-truck me-2"></i>الإخلاءات
                    </button>
                    <button type="button" class="btn nav-tab-btn" onclick="showSection('violations-section')">
                        <i class="fas fa-exclamation-triangle me-2"></i>المخالفات
                    </button>
                </div>
            </div>
        </div>

        <!-- قسم رخصة الحفر -->
        <div id="dig-license-section" class="tab-section" style="display: none;">
            <div class="section-card">
                <div class="section-header">
                    <h3 class="mb-0">
                        <i class="fas fa-hard-hat me-2"></i>
                        رخصة الحفر
                    </h3>
                </div>
                <div class="section-body">
                    <form id="digLicenseForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                        
                        <!-- معلومات الرخصة الأساسية -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">رقم الرخصة</label>
                                <input type="text" class="form-control" name="license_number" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">تاريخ إصدار الرخصة</label>
                                <input type="date" class="form-control" name="license_date" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">نوع الرخصة</label>
                                <select class="form-select" name="license_type" required>
                                    <option value="">اختر نوع الرخصة</option>
                                    <option value="emergency">طوارئ</option>
                                    <option value="project">مشروع</option>
                                    <option value="normal">عادي</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">قيمة الرخصة</label>
                                <input type="number" step="0.01" class="form-control" name="license_value" required>
                            </div>
                        </div>

                        <!-- أبعاد الحفر -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">طول الحفر (متر)</label>
                                <input type="number" step="0.01" class="form-control" name="excavation_length" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">عرض الحفر (متر)</label>
                                <input type="number" step="0.01" class="form-control" name="excavation_width" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">عمق الحفر (متر)</label>
                                <input type="number" step="0.01" class="form-control" name="excavation_depth" required>
                            </div>
                        </div>

                        <!-- تواريخ الرخصة -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">تاريخ تفعيل الرخصة</label>
                                <input type="date" class="form-control" name="license_start_date" id="license_start_date" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">تاريخ نهاية الرخصة</label>
                                <input type="date" class="form-control" name="license_end_date" id="license_end_date" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">عدد أيام الرخصة</label>
                                <input type="number" class="form-control" name="license_alert_days" id="license_days" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">قيمة التمديد</label>
                                <input type="number" step="0.01" class="form-control" name="extension_value">
                            </div>
                        </div>

                        <!-- تواريخ التمديد -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">تاريخ بداية التمديد</label>
                                <input type="date" class="form-control" name="extension_start_date" id="extension_start_date">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">تاريخ نهاية التمديد</label>
                                <input type="date" class="form-control" name="extension_end_date" id="extension_end_date">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">عدد أيام التمديد</label>
                                <input type="number" class="form-control" name="extension_alert_days" id="extension_days" readonly>
                            </div>
                        </div>

                        <!-- المرفقات -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">ملف الرخصة</label>
                                <input type="file" class="form-control" name="license_file_path" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">إثبات سداد البنك</label>
                                <input type="file" class="form-control" name="payment_proof_path" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">فواتير السداد</label>
                                <input type="file" class="form-control" name="payment_invoices_path" multiple>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-primary-custom btn-lg" onclick="saveDigLicenseSection()">
                                    <i class="fas fa-save me-2"></i>
                                    حفظ رخصة الحفر
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- قسم المختبر -->
        <div id="lab-section" class="tab-section" style="display: none;">
            <div class="section-card">
                <div class="section-header">
                    <h3 class="mb-0">
                        <i class="fas fa-flask me-2"></i>
                        المختبر
                    </h3>
                </div>
                <div class="section-body">
                    <form id="labForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                        
                        <!-- اختبارات المختبر مع النظام التفاعلي -->
                        <div class="row g-3 mb-4">
                            <!-- اختبار العمق -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-ruler-vertical me-2"></i>اختبار العمق
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_depth_test" value="1" id="depth_test_yes" data-test="depth_test" onchange="toggleTestCalculation('depth_test')">
                                                <label class="form-check-label" for="depth_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_depth_test" value="0" id="depth_test_no" data-test="depth_test" checked onchange="toggleTestCalculation('depth_test')">
                                                <label class="form-check-label" for="depth_test_no">لا</label>
                                            </div>
                                        </div>
                                        
                                        <div class="test-calculation depth_test-calculation" style="display: none;">
                                            <div class="row g-2 mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label small">القيمة المدخلة</label>
                                                    <input type="number" class="form-control form-control-sm" name="depth_test_input" step="0.01" onchange="calculateTest('depth_test')" placeholder="أدخل القيمة">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">المعامل</label>
                                                    <select class="form-select form-select-sm" name="depth_test_factor" onchange="calculateTest('depth_test')">
                                                        <option value="46">46</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">النتيجة</label>
                                                    <input type="number" class="form-control form-control-sm bg-light" name="depth_test_result" readonly>
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-2">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="depth_test_status" value="pass" id="depth_test_pass" onchange="updateTestTotals()">
                                                        <label class="form-check-label text-success fw-bold" for="depth_test_pass">
                                                            <i class="fas fa-check-circle me-1"></i>ناجح
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="depth_test_status" value="fail" id="depth_test_fail" onchange="updateTestTotals()">
                                                        <label class="form-check-label text-danger fw-bold" for="depth_test_fail">
                                                            <i class="fas fa-times-circle me-1"></i>راسب
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="depth_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- اختبار دك التربة -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-hammer me-2"></i>اختبار دك التربة
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_soil_compaction_test" value="1" id="soil_compaction_test_yes" data-test="soil_compaction_test" onchange="toggleTestCalculation('soil_compaction_test')">
                                                <label class="form-check-label" for="soil_compaction_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_soil_compaction_test" value="0" id="soil_compaction_test_no" data-test="soil_compaction_test" checked onchange="toggleTestCalculation('soil_compaction_test')">
                                                <label class="form-check-label" for="soil_compaction_test_no">لا</label>
                                            </div>
                                        </div>
                                        
                                        <div class="test-calculation soil_compaction_test-calculation" style="display: none;">
                                            <div class="row g-2 mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label small">القيمة المدخلة</label>
                                                    <input type="number" class="form-control form-control-sm" name="soil_compaction_test_input" step="0.01" onchange="calculateTest('soil_compaction_test')" placeholder="أدخل القيمة">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">المعامل</label>
                                                    <select class="form-select form-select-sm" name="soil_compaction_test_factor" onchange="calculateTest('soil_compaction_test')">
                                                        <option value="90">90</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">النتيجة</label>
                                                    <input type="number" class="form-control form-control-sm bg-light" name="soil_compaction_test_result" readonly>
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-2">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="soil_compaction_test_status" value="pass" id="soil_compaction_test_pass" onchange="updateTestTotals()">
                                                        <label class="form-check-label text-success fw-bold" for="soil_compaction_test_pass">
                                                            <i class="fas fa-check-circle me-1"></i>ناجح
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="soil_compaction_test_status" value="fail" id="soil_compaction_test_fail" onchange="updateTestTotals()">
                                                        <label class="form-check-label text-danger fw-bold" for="soil_compaction_test_fail">
                                                            <i class="fas fa-times-circle me-1"></i>راسب
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="soil_compaction_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- اختبار MC1, RC2 -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-vial me-2"></i>اختبار تقيم رش  MC1, RC2
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_rc1_mc1_test" value="1" id="rc1_mc1_test_yes" data-test="rc1_mc1_test" onchange="toggleTestCalculation('rc1_mc1_test')">
                                                <label class="form-check-label" for="rc1_mc1_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_rc1_mc1_test" value="0" id="rc1_mc1_test_no" data-test="rc1_mc1_test" checked onchange="toggleTestCalculation('rc1_mc1_test')">
                                                <label class="form-check-label" for="rc1_mc1_test_no">لا</label>
                                            </div>
                                        </div>
                                        
                                        <div class="test-calculation rc1_mc1_test-calculation" style="display: none;">
                                            <div class="row g-2 mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label small">القيمة المدخلة</label>
                                                    <input type="number" class="form-control form-control-sm" name="rc1_mc1_test_input" step="0.01" onchange="calculateTest('rc1_mc1_test')" placeholder="أدخل القيمة">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">المعامل</label>
                                                    <select class="form-select form-select-sm" name="rc1_mc1_test_factor" onchange="calculateTest('rc1_mc1_test')">
                                                        <option value="38">38</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">النتيجة</label>
                                                    <input type="number" class="form-control form-control-sm bg-light" name="rc1_mc1_test_result" readonly>
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-2">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="rc1_mc1_test_status" value="pass" id="rc1_mc1_test_pass" onchange="updateTestTotals()">
                                                        <label class="form-check-label text-success fw-bold" for="rc1_mc1_test_pass">
                                                            <i class="fas fa-check-circle me-1"></i>ناجح
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="rc1_mc1_test_status" value="fail" id="rc1_mc1_test_fail" onchange="updateTestTotals()">
                                                        <label class="form-check-label text-danger fw-bold" for="rc1_mc1_test_fail">
                                                            <i class="fas fa-times-circle me-1"></i>راسب
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="rc1_mc1_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- اختبار أسفلت -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-road me-2"></i>اختبار أسفلت
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_asphalt_test" value="1" id="asphalt_test_yes" data-test="asphalt_test" onchange="toggleTestCalculation('asphalt_test')">
                                                <label class="form-check-label" for="asphalt_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_asphalt_test" value="0" id="asphalt_test_no" data-test="asphalt_test" checked onchange="toggleTestCalculation('asphalt_test')">
                                                <label class="form-check-label" for="asphalt_test_no">لا</label>
                                            </div>
                                        </div>
                                        
                                        <div class="test-calculation asphalt_test-calculation" style="display: none;">
                                            <div class="row g-2 mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label small">القيمة المدخلة</label>
                                                    <input type="number" class="form-control form-control-sm" name="asphalt_test_input" step="0.01" onchange="calculateTest('asphalt_test')" placeholder="أدخل القيمة">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">المعامل</label>
                                                    <select class="form-select form-select-sm" name="asphalt_test_factor" onchange="calculateTest('asphalt_test')">
                                                        <option value="46">46</option>
                                                        <option value="90">90</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">النتيجة</label>
                                                    <input type="number" class="form-control form-control-sm bg-light" name="asphalt_test_result" readonly>
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-2">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="asphalt_test_status" value="pass" id="asphalt_test_pass" onchange="updateTestTotals()">
                                                        <label class="form-check-label text-success fw-bold" for="asphalt_test_pass">
                                                            <i class="fas fa-check-circle me-1"></i>ناجح
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="asphalt_test_status" value="fail" id="asphalt_test_fail" onchange="updateTestTotals()">
                                                        <label class="form-check-label text-danger fw-bold" for="asphalt_test_fail">
                                                            <i class="fas fa-times-circle me-1"></i>راسب
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="asphalt_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- اختبار تربة -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-seedling me-2"></i>اختبار تربة
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_soil_test" value="1" id="soil_test_yes" data-test="soil_test" onchange="toggleTestCalculation('soil_test')">
                                                <label class="form-check-label" for="soil_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_soil_test" value="0" id="soil_test_no" data-test="soil_test" checked onchange="toggleTestCalculation('soil_test')">
                                                <label class="form-check-label" for="soil_test_no">لا</label>
                                            </div>
                                        </div>
                                        
                                        <div class="test-calculation soil_test-calculation" style="display: none;">
                                            <div class="row g-2 mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label small">القيمة المدخلة</label>
                                                    <input type="number" class="form-control form-control-sm" name="soil_test_input" step="0.01" onchange="calculateTest('soil_test')" placeholder="أدخل القيمة">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">المعامل</label>
                                                    <select class="form-select form-select-sm" name="soil_test_factor" onchange="calculateTest('soil_test')">
                                                        <option value="178">178</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">النتيجة</label>
                                                    <input type="number" class="form-control form-control-sm bg-light" name="soil_test_result" readonly>
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-2">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="soil_test_status" value="pass" id="soil_test_pass" onchange="updateTestTotals()">
                                                        <label class="form-check-label text-success fw-bold" for="soil_test_pass">
                                                            <i class="fas fa-check-circle me-1"></i>ناجح
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="soil_test_status" value="fail" id="soil_test_fail" onchange="updateTestTotals()">
                                                        <label class="form-check-label text-danger fw-bold" for="soil_test_fail">
                                                            <i class="fas fa-times-circle me-1"></i>راسب
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="soil_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- اختبار بلاط وانترلوك -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-th me-2"></i>اختبار بلاط وانترلوك
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_interlock_test" value="1" id="interlock_test_yes" data-test="interlock_test" onchange="toggleTestCalculation('interlock_test')">
                                                <label class="form-check-label" for="interlock_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_interlock_test" value="0" id="interlock_test_no" data-test="interlock_test" checked onchange="toggleTestCalculation('interlock_test')">
                                                <label class="form-check-label" for="interlock_test_no">لا</label>
                                            </div>
                                        </div>
                                        
                                        <div class="test-calculation interlock_test-calculation" style="display: none;">
                                            <div class="row g-2 mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label small">القيمة المدخلة</label>
                                                    <input type="number" class="form-control form-control-sm" name="interlock_test_input" step="0.01" onchange="calculateTest('interlock_test')" placeholder="أدخل القيمة">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">المعامل</label>
                                                    <select class="form-select form-select-sm" name="interlock_test_factor" onchange="calculateTest('interlock_test')">
                                                        <option value="34">34</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">النتيجة</label>
                                                    <input type="number" class="form-control form-control-sm bg-light" name="interlock_test_result" readonly>
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-2">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="interlock_test_status" value="pass" id="interlock_test_pass" onchange="updateTestTotals()">
                                                        <label class="form-check-label text-success fw-bold" for="interlock_test_pass">
                                                            <i class="fas fa-check-circle me-1"></i>ناجح
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="interlock_test_status" value="fail" id="interlock_test_fail" onchange="updateTestTotals()">
                                                        <label class="form-check-label text-danger fw-bold" for="interlock_test_fail">
                                                            <i class="fas fa-times-circle me-1"></i>راسب
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="interlock_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- نتائج الاختبارات التفاعلية -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-chart-bar me-2"></i>
                                            ملخص نتائج الاختبارات
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <div class="text-center p-3 border rounded bg-light">
                                                    <i class="fas fa-calculator text-primary fs-2 mb-2"></i>
                                                    <h6 class="fw-bold">إجمالي نتائج الاختبارات</h6>
                                                    <span class="fs-4 fw-bold text-primary" id="total_tests_sum">0.00</span>
                                                    <input type="hidden" name="total_tests_value" id="total_tests_value" value="0">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center p-3 border rounded bg-success bg-opacity-10">
                                                    <i class="fas fa-check-circle text-success fs-2 mb-2"></i>
                                                    <h6 class="fw-bold text-success">الاختبارات الناجحة</h6>
                                                    <span class="fs-4 fw-bold text-success" id="passed_tests_sum">0.00</span>
                                                    <input type="hidden" name="successful_tests_value" id="successful_tests_value" value="0">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center p-3 border rounded bg-danger bg-opacity-10">
                                                    <i class="fas fa-times-circle text-danger fs-2 mb-2"></i>
                                                    <h6 class="fw-bold text-danger">الاختبارات الراسبة</h6>
                                                    <span class="fs-4 fw-bold text-danger" id="failed_tests_sum">0.00</span>
                                                    <input type="hidden" name="failed_tests_value" id="failed_tests_value" value="0">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center p-3 border rounded bg-warning bg-opacity-10">
                                                    <i class="fas fa-percentage text-warning fs-2 mb-2"></i>
                                                    <h6 class="fw-bold text-warning">نسبة النجاح</h6>
                                                    <span class="fs-4 fw-bold text-warning" id="success_percentage">0%</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">عدد الاختبارات المفعلة</label>
                                                <input type="number" class="form-control bg-light" id="active_tests_count" readonly value="0">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">الحالة العامة للمختبر</label>
                                                <div class="mt-2">
                                                    <span class="badge fs-6 p-2" id="overall_lab_status">غير محدد</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <label class="form-label fw-bold">أسباب الرسوب والملاحظات</label>
                                                <textarea class="form-control" name="test_failure_reasons" id="test_failure_reasons" rows="3" placeholder="سيتم تعبئة أسباب الرسوب تلقائياً عند وجود اختبارات راسبة..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-primary-custom btn-lg" onclick="saveLabSection()">
                                    <i class="fas fa-save me-2"></i>
                                    حفظ بيانات المختبر
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- قسم الإخلاءات -->
        <div id="evacuations-section" class="tab-section" style="display: none;">
            <div class="section-card">
                <div class="section-header">
                    <h3 class="mb-0">
                        <i class="fas fa-truck me-2"></i>
                        الإخلاءات
                    </h3>
                </div>
                <div class="section-body">
                    <form id="evacuationForm">
                        @csrf
                        <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                        
                        <!-- معلومات الإخلاء الأساسية -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">تم الإخلاء؟</label>
                                <select class="form-select" name="is_evacuated">
                                    <option value="0">لا</option>
                                    <option value="1">نعم</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">رقم رخصة الإخلاء</label>
                                <input type="text" class="form-control" name="evac_license_number" placeholder="رقم رخصة الإخلاء">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">مبلغ الإخلاء</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control" name="evac_amount" placeholder="0.00">
                                    <span class="input-group-text">ريال</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">مرفقات الإخلاءات</label>
                                <input type="file" class="form-control" name="evacuations_files[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            </div>
                        </div>

                        <!-- تواريخ الإخلاء -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">تاريخ الإخلاء</label>
                                <input type="date" class="form-control" name="evacuation_start_date">
                            </div>
                            
                        </div>

                        <!-- جدول الفسح للإخلاء -->
                        <div class="row mb-4 mt-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-road me-2"></i>
                                    جدول الفسح ونوع الشارع للإخلاء
                                </h5>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <button type="button" class="btn btn-success btn-sm" onclick="addNewEvacStreetRow()">
                                <i class="fas fa-plus me-1"></i>
                                إضافة صف جديد
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="saveAllEvacStreets()">
                                <i class="fas fa-save me-1"></i>
                                حفظ جميع الفسح
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="evacStreetTable">
                                <thead class="table-warning">
                                    <tr>
                                        <th>#</th>
                                        <th>رقم الفسح</th>
                                        <th>تاريخ الفسح</th>
                                        <th>طول الفسح</th>
                                        <th>طول المختبر</th>
                                        <th>نوع الشارع</th>
                                        <th>كمية التربة</th>
                                        <th>كمية الأسفلت</th>
                                        <th>كمية البلاط</th>
                                        <th>فحص التربة</th>
                                        <th>فحص MC1</th>
                                        <th>فحص الأسفلت</th>
                                        <th>ملاحظات</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="no-evac-streets-row">
                                        <td colspan="14" class="text-center">لا توجد فسح مسجلة للإخلاء</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- جدول التفاصيل الفنية للمختبر -->
                        <div class="row mb-4 mt-5">
                            <div class="col-12">
                                <h5 class="text-info mb-3">
                                    <i class="fas fa-cogs me-2"></i>
                                    جدول التفاصيل الفنية للمختبر
                                </h5>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <button type="button" class="btn btn-success btn-sm" onclick="addNewLabDetailsRow()">
                                <i class="fas fa-plus me-1"></i>
                                إضافة صف جديد
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="saveAllLabDetails()">
                                <i class="fas fa-save me-1"></i>
                                حفظ التفاصيل الفنية
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="labDetailsTable">
                                <thead class="table-info text-white">
                                    <tr>
                                        <th style="min-width: 80px;">السنة</th>
                                        <th style="min-width: 120px;">نوع العمل</th>
                                        <th style="min-width: 80px;">العمق</th>
                                        <th style="min-width: 100px;">دك التربة</th>
                                        <th style="min-width: 100px;">MC1-RC2</th>
                                        <th style="min-width: 100px;">دك أسفلت</th>
                                        <th style="min-width: 80px;">ترابي</th>
                                        <th style="min-width: 120px;">الكثافة القصوى للأسفلت</th>
                                        <th style="min-width: 120px;">نسبة الأسفلت</th>
                                        <th style="min-width: 120px;">تجربة مارشال</th>
                                        <th style="min-width: 120px;">تقييم البلاط</th>
                                        <th style="min-width: 120px;">تصنيف التربة</th>
                                        <th style="min-width: 120px;">تجربة بروكتور</th>
                                        <th style="min-width: 100px;">الخرسانة</th>
                                        <th style="min-width: 80px;">حذف</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="no-lab-details-row">
                                        <td colspan="15" class="text-center">لا توجد تفاصيل فنية مسجلة</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-primary-custom btn-lg" onclick="saveEvacuationSection()">
                                    <i class="fas fa-save me-2"></i>
                                    حفظ بيانات الإخلاء
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- قسم المخالفات -->
        <div id="violations-section" class="tab-section" style="display: none;">
            <div class="section-card">
                <div class="section-header">
                    <h3 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        المخالفات
                    </h3>
                </div>
                <div class="section-body">
                    <form id="violationForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                        
                                                 <!-- معلومات المخالفة -->
                         <div class="row g-3 mb-4">
                             <div class="col-md-3">
                                 <label class="form-label fw-bold">رقم المخالفة</label>
                                 <input type="text" class="form-control" name="violation_number" required>
                             </div>
                             <div class="col-md-3">
                                 <label class="form-label fw-bold">تاريخ المخالفة</label>
                                 <input type="date" class="form-control" name="violation_date" value="{{ date('Y-m-d') }}" required>
                             </div>
                             <div class="col-md-3">
                                 <label class="form-label fw-bold">نوع المخالفة</label>
                                 <input type="text" class="form-control" name="violation_type" required>
                             </div>
                             <div class="col-md-3">
                                 <label class="form-label fw-bold">المتسبب</label>
                                 <input type="text" class="form-control" name="responsible_party" required>
                             </div>
                         </div>

                         <div class="row g-3 mb-4">
                             <div class="col-md-4">
                                 <label class="form-label fw-bold">قيمة المخالفة</label>
                                 <div class="input-group">
                                     <input type="number" class="form-control" name="violation_amount" step="0.01" required>
                                     <span class="input-group-text">ريال</span>
                                 </div>
                             </div>
                             <div class="col-md-4">
                                 <label class="form-label fw-bold">تاريخ الاستحقاق</label>
                                 <input type="date" class="form-control" name="payment_due_date" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                             </div>
                             <div class="col-md-4">
                                 <label class="form-label fw-bold">رقم سداد المخالفة</label>
                                 <input type="text" class="form-control" name="payment_invoice_number" placeholder="رقم الفاتورة">
                             </div>
                         </div>

                         <div class="row g-3 mb-4">
                             <div class="col-md-8">
                                 <label class="form-label fw-bold">وصف المخالفة</label>
                                 <textarea class="form-control" name="violation_description" rows="3" placeholder="اكتب وصف المخالفة هنا..."></textarea>
                             </div>
                             <div class="col-md-4">
                                 <label class="form-label fw-bold">حالة الفاتورة</label>
                                 <div class="mt-2">
                                     <div class="form-check mb-2">
                                         <input class="form-check-input" type="radio" name="payment_status" id="payment_unpaid" value="0" checked>
                                         <label class="form-check-label fw-bold text-danger" for="payment_unpaid">
                                             <i class="fas fa-times-circle me-1"></i>
                                             غير مسددة
                                         </label>
                                     </div>
                                     <div class="form-check">
                                         <input class="form-check-input" type="radio" name="payment_status" id="payment_paid" value="1">
                                         <label class="form-check-label fw-bold text-success" for="payment_paid">
                                             <i class="fas fa-check-circle me-1"></i>
                                             مسددة
                                         </label>
                                     </div>
                                 </div>
                             </div>
                         </div>

                                                 <div class="row mt-4">
                             <div class="col-12 text-center">
                                 <button type="button" class="btn btn-primary-custom btn-lg me-2" onclick="saveViolationSection()">
                                     <i class="fas fa-save me-2"></i>
                                     حفظ المخالفة
                                 </button>
                                 <button type="button" class="btn btn-secondary btn-lg" onclick="resetViolationForm()">
                                     <i class="fas fa-undo me-2"></i>
                                     إعادة تعيين
                                 </button>
                             </div>
                         </div>
                    </form>

                                         <!-- جدول المخالفات -->
                     <div class="table-responsive mt-4">
                         <table class="table table-striped table-hover">
                             <thead class="table-warning">
                                 <tr>
                                     <th>#</th>
                                     <th>رقم المخالفة</th>
                                     <th>تاريخ المخالفة</th>
                                     <th>نوع المخالفة</th>
                                     <th>المتسبب</th>
                                     <th>وصف المخالفة</th>
                                     <th>القيمة</th>
                                     <th>تاريخ الاستحقاق</th>
                                     <th>رقم السداد</th>
                                     <th>حالة الدفع</th>
                                     <th>الإجراءات</th>
                                 </tr>
                             </thead>
                             <tbody id="violations-table-body">
                                 <tr>
                                     <td colspan="11" class="text-center">لا توجد مخالفات</td>
                                 </tr>
                             </tbody>
                         </table>
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// تحديث دالة إظهار الأقسام لتشمل جدول الفسح للإخلاء
function showSection(sectionId) {
    // إخفاء جميع الأقسام أولاً
    document.querySelectorAll('.tab-section').forEach(section => {
        section.style.display = 'none';
    });
    
    // إزالة الفئة النشطة من جميع الأزرار
    document.querySelectorAll('.nav-tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // إظهار القسم المحدد
    const selectedSection = document.getElementById(sectionId);
    if (selectedSection) {
        selectedSection.style.display = 'block';
        
        // تحميل المخالفات عند إظهار قسم المخالفات
        if (sectionId === 'violations-section') {
            loadViolations();
        }
    }
    
    // إضافة الفئة النشطة للزر المحدد
    const clickedButton = document.querySelector(`button[onclick="showSection('${sectionId}')"]`);
    if (clickedButton) {
        clickedButton.classList.add('active');
    }
}

// تهيئة الصفحة عند التحميل
document.addEventListener('DOMContentLoaded', function() {
    // إخفاء جميع الأقسام عند بداية التحميل
    document.querySelectorAll('.tab-section').forEach(section => {
        section.style.display = 'none';
    });
    
    // إزالة الفئة النشطة من جميع الأزرار
    document.querySelectorAll('.nav-tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
});

// دوال الحفظ
function saveCoordinationSection() {
    const formData = new FormData(document.getElementById('coordinationForm'));
    
    $.ajax({
        url: '{{ route("admin.licenses.store") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            toastr.success('تم حفظ شهادة التنسيق بنجاح');
            
            // Refresh the page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        },
        error: function(xhr) {
            const errors = xhr.responseJSON?.errors || {};
            Object.values(errors).forEach(error => {
                toastr.error(error[0]);
            });
        }
    });
}

function saveDigLicenseSection() {
    const formData = new FormData(document.getElementById('digLicenseForm'));
    
    $.ajax({
        url: '{{ route("admin.licenses.store") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
    toastr.success('تم حفظ رخصة الحفر بنجاح');
            
            // Refresh the page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        },
        error: function(xhr) {
            const errors = xhr.responseJSON?.errors || {};
            Object.values(errors).forEach(error => {
                toastr.error(error[0]);
            });
        }
    });
}

function saveLabSection() {
    const formData = new FormData(document.getElementById('labForm'));
    
    $.ajax({
        url: '{{ route("admin.licenses.store") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
    toastr.success('تم حفظ بيانات المختبر بنجاح');
            
            // Refresh the page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        },
        error: function(xhr) {
            const errors = xhr.responseJSON?.errors || {};
            Object.values(errors).forEach(error => {
                toastr.error(error[0]);
            });
        }
    });
}

function saveEvacuationSection() {
    toastr.success('تم حفظ بيانات الإخلاء بنجاح');
}

// وظائف جدول التفاصيل الفنية للمختبر
function addNewLabDetailsRow() {
    const tbody = document.querySelector('#labDetailsTable tbody');
    const noLabDetailsRow = document.getElementById('no-lab-details-row');
    if (noLabDetailsRow) {
        noLabDetailsRow.remove();
    }
    
    const newRow = document.createElement('tr');
    const currentYear = new Date().getFullYear();
    newRow.innerHTML = `
        <td><input type="number" class="form-control form-control-sm" name="lab_year" value="${currentYear}" min="2020" max="2030"></td>
        <td>
            <select class="form-select form-select-sm" name="lab_work_type">
                <option value="">اختر نوع العمل</option>
                <option value="حفر">حفر</option>
                <option value="ردم">ردم</option>
                <option value="أسفلت">أسفلت</option>
                <option value="بلاط">بلاط</option>
                <option value="خرسانة">خرسانة</option>
                <option value="أخرى">أخرى</option>
            </select>
        </td>
        <td><input type="number" class="form-control form-control-sm" name="lab_depth" step="0.01" placeholder="متر"></td>
        <td><input type="number" class="form-control form-control-sm" name="lab_soil_compaction" step="0.01" placeholder="%"></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_mc1rc2" placeholder="MC1-RC2"></td>
        <td><input type="number" class="form-control form-control-sm" name="lab_asphalt_compaction" step="0.01" placeholder="%"></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_soil_type" placeholder="ترابي"></td>
        <td><input type="number" class="form-control form-control-sm" name="lab_max_asphalt_density" step="0.01" placeholder="كغم/م³"></td>
        <td><input type="number" class="form-control form-control-sm" name="lab_asphalt_percentage" step="0.01" placeholder="%"></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_marshall_test" placeholder="نتيجة مارشال"></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_tile_evaluation" placeholder="تقييم البلاط"></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_soil_classification" placeholder="تصنيف التربة"></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_proctor_test" placeholder="نتيجة بروكتور"></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_concrete" placeholder="مقاومة الخرسانة"></td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="deleteLabDetailsRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(newRow);
}

function deleteLabDetailsRow(button) {
    if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
        const tbody = document.querySelector('#labDetailsTable tbody');
        button.closest('tr').remove();
        
        // إذا لم تعد هناك صفوف، أضف صف "لا توجد بيانات"
        if (tbody.children.length === 0) {
            const noDataRow = document.createElement('tr');
            noDataRow.id = 'no-lab-details-row';
            noDataRow.innerHTML = '<td colspan="15" class="text-center">لا توجد تفاصيل فنية مسجلة</td>';
            tbody.appendChild(noDataRow);
        }
        
        toastr.success('تم حذف السجل بنجاح');
    }
}

function saveAllLabDetails() {
    const rows = document.querySelectorAll('#labDetailsTable tbody tr:not(#no-lab-details-row)');
    if (rows.length === 0) {
        toastr.warning('لا توجد بيانات للحفظ');
        return;
    }
    
    const labDetailsData = [];
    rows.forEach(row => {
        const inputs = row.querySelectorAll('input, select');
        const rowData = {};
        inputs.forEach(input => {
            if (input.name && input.value) {
                rowData[input.name] = input.value;
            }
        });
        if (Object.keys(rowData).length > 0) {
            labDetailsData.push(rowData);
        }
    });
    
    if (labDetailsData.length > 0) {
        // هنا يمكن إرسال البيانات للخادم
        console.log('Lab Details Data:', labDetailsData);
        toastr.success('تم حفظ التفاصيل الفنية بنجاح');
    } else {
        toastr.warning('لا توجد بيانات صحيحة للحفظ');
    }
}

function addNewEvacStreetRow() {
    const tbody = document.getElementById('evacStreetTable').getElementsByTagName('tbody')[0];
    const noRowsMessage = document.getElementById('no-evac-streets-row');
    
    if (noRowsMessage) {
        noRowsMessage.remove();
    }

    const rowCount = tbody.rows.length + 1;
    const newRow = document.createElement('tr');
    
    newRow.innerHTML = `
        <td>${rowCount}</td>
        <td><input type="text" class="form-control form-control-sm" name="evac_streets[${rowCount}][clearance_number]" placeholder="رقم الفسح"></td>
        <td><input type="date" class="form-control form-control-sm" name="evac_streets[${rowCount}][clearance_date]"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_streets[${rowCount}][length]" placeholder="طول الفسح"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_streets[${rowCount}][lab_length]" placeholder="طول المختبر"></td>
        <td><input type="text" class="form-control form-control-sm" name="evac_streets[${rowCount}][street_type]" placeholder="نوع الشارع"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_streets[${rowCount}][soil_quantity]" placeholder="كمية التربة"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_streets[${rowCount}][asphalt_quantity]" placeholder="كمية الأسفلت"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_streets[${rowCount}][tile_quantity]" placeholder="كمية البلاط"></td>
        <td><input type="text" class="form-control form-control-sm" name="evac_streets[${rowCount}][soil_test]" placeholder="فحص التربة"></td>
        <td><input type="text" class="form-control form-control-sm" name="evac_streets[${rowCount}][mc1_test]" placeholder="فحص MC1"></td>
        <td><input type="text" class="form-control form-control-sm" name="evac_streets[${rowCount}][asphalt_test]" placeholder="فحص الأسفلت"></td>
        <td><input type="text" class="form-control form-control-sm" name="evac_streets[${rowCount}][notes]" placeholder="ملاحظات"></td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="deleteEvacStreetRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(newRow);
}

function deleteEvacStreetRow(button) {
    const row = button.closest('tr');
    row.remove();
    
    const tbody = document.getElementById('evacStreetTable').getElementsByTagName('tbody')[0];
    if (tbody.rows.length === 0) {
        tbody.innerHTML = `
            <tr id="no-evac-streets-row">
                <td colspan="14" class="text-center">لا توجد فسح مسجلة للإخلاء</td>
            </tr>
        `;
    }
}

function saveAllEvacStreets() {
    // تجميع بيانات الجدول
    const rows = document.getElementById('evacStreetTable').getElementsByTagName('tbody')[0].rows;
    const data = [];
    
    for (let i = 0; i < rows.length; i++) {
        if (rows[i].id !== 'no-evac-streets-row') {
            const row = rows[i];
            const rowData = {
                clearance_number: row.querySelector('[name$="[clearance_number]"]').value,
                clearance_date: row.querySelector('[name$="[clearance_date]"]').value,
                length: row.querySelector('[name$="[length]"]').value,
                lab_length: row.querySelector('[name$="[lab_length]"]').value,
                street_type: row.querySelector('[name$="[street_type]"]').value,
                soil_quantity: row.querySelector('[name$="[soil_quantity]"]').value,
                asphalt_quantity: row.querySelector('[name$="[asphalt_quantity]"]').value,
                tile_quantity: row.querySelector('[name$="[tile_quantity]"]').value,
                soil_test: row.querySelector('[name$="[soil_test]"]').value,
                mc1_test: row.querySelector('[name$="[mc1_test]"]').value,
                asphalt_test: row.querySelector('[name$="[asphalt_test]"]').value,
                notes: row.querySelector('[name$="[notes]"]').value
            };
            data.push(rowData);
        }
    }

    // إرسال البيانات إلى الخادم
    $.ajax({
        url: '/admin/licenses/update-evac-streets/{{ $workOrder->id }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            evac_streets: data
        },
        success: function(response) {
            toastr.success('تم حفظ بيانات الفسح بنجاح');
        },
        error: function(xhr) {
            toastr.error('حدث خطأ أثناء حفظ بيانات الفسح');
        }
    });
}

// ==================== دوال النظام التفاعلي للمختبر ====================

// دالة لإظهار/إخفاء حقول الحساب عند تفعيل الاختبار
function toggleTestCalculation(testName) {
    const calculationDiv = document.querySelector(`.${testName}-calculation`);
    const testRadio = document.querySelector(`input[name="has_${testName}"][value="1"]`);
    
    if (calculationDiv) {
        calculationDiv.style.display = testRadio.checked ? 'block' : 'none';
        
        // إذا تم إلغاء تفعيل الاختبار، نظف القيم وأعد حساب الإجماليات
        if (!testRadio.checked) {
            const inputField = document.querySelector(`input[name="${testName}_input"]`);
            const resultField = document.querySelector(`input[name="${testName}_result"]`);
            const statusRadios = document.querySelectorAll(`input[name="${testName}_status"]`);
            
            if (inputField) inputField.value = '';
            if (resultField) resultField.value = '';
            statusRadios.forEach(radio => radio.checked = false);
            
            updateTestTotals();
        }
    }
}

// دالة لحساب نتيجة الاختبار (القيمة × المعامل)
function calculateTest(testName) {
    const inputField = document.querySelector(`input[name="${testName}_input"]`);
    const factorField = document.querySelector(`select[name="${testName}_factor"]`);
    const resultField = document.querySelector(`input[name="${testName}_result"]`);
    
    if (inputField && factorField && resultField) {
        const inputValue = parseFloat(inputField.value) || 0;
        const factorValue = parseFloat(factorField.value) || 1;
        const result = inputValue * factorValue;
        
        resultField.value = result.toFixed(2);
        
        // تحديث الإجماليات
        updateTestTotals();
    }
}

// دالة لتحديث الإجماليات
function updateTestTotals() {
    const testNames = ['depth_test', 'soil_compaction_test', 'rc1_mc1_test', 'asphalt_test', 'soil_test', 'interlock_test'];
    
    let totalSum = 0;
    let passedSum = 0;
    let failedSum = 0;
    let activeTestsCount = 0;
    let passedTestsCount = 0;
    let failedTestsCount = 0;
    let failureReasons = [];
    
    testNames.forEach(testName => {
        const testRadio = document.querySelector(`input[name="has_${testName}"][value="1"]`);
        
        // تحقق من أن الاختبار مفعل
        if (testRadio && testRadio.checked) {
            activeTestsCount++;
            
            const resultField = document.querySelector(`input[name="${testName}_result"]`);
            const passRadio = document.querySelector(`input[name="${testName}_status"][value="pass"]`);
            const failRadio = document.querySelector(`input[name="${testName}_status"][value="fail"]`);
            
            const result = parseFloat(resultField?.value) || 0;
            totalSum += result;
            
            if (passRadio && passRadio.checked) {
                passedSum += result;
                passedTestsCount++;
            } else if (failRadio && failRadio.checked) {
                failedSum += result;
                failedTestsCount++;
                
                // إضافة سبب الرسوب
                const testLabel = getTestLabel(testName);
                failureReasons.push(`${testLabel}: نتيجة ${result.toFixed(2)}`);
            }
        }
    });
    
    // تحديث العرض
    document.getElementById('total_tests_sum').textContent = totalSum.toFixed(2);
    document.getElementById('passed_tests_sum').textContent = passedSum.toFixed(2);
    document.getElementById('failed_tests_sum').textContent = failedSum.toFixed(2);
    document.getElementById('active_tests_count').value = activeTestsCount;
    
    // تحديث الحقول المخفية
    document.getElementById('total_tests_value').value = totalSum.toFixed(2);
    document.getElementById('successful_tests_value').value = passedSum.toFixed(2);
    document.getElementById('failed_tests_value').value = failedSum.toFixed(2);
    
    // حساب نسبة النجاح
    let successPercentage = 0;
    if (activeTestsCount > 0) {
        successPercentage = (passedTestsCount / activeTestsCount) * 100;
    }
    document.getElementById('success_percentage').textContent = successPercentage.toFixed(1) + '%';
    
    // تحديث الحالة العامة
    updateOverallLabStatus(activeTestsCount, passedTestsCount, failedTestsCount);
    
    // تحديث أسباب الرسوب
    const failureReasonsTextarea = document.getElementById('test_failure_reasons');
    if (failureReasons.length > 0) {
        failureReasonsTextarea.value = 'أسباب الرسوب:\n' + failureReasons.join('\n');
    } else if (activeTestsCount > 0 && failedTestsCount === 0) {
        failureReasonsTextarea.value = 'جميع الاختبارات ناجحة - لا توجد أسباب رسوب';
    } else {
        failureReasonsTextarea.value = '';
    }
}

// دالة للحصول على اسم الاختبار بالعربية
function getTestLabel(testName) {
    const labels = {
        'depth_test': 'اختبار العمق',
        'soil_compaction_test': 'اختبار دك التربة',
        'rc1_mc1_test': 'اختبار MC1, RC2',
        'asphalt_test': 'اختبار أسفلت',
        'soil_test': 'اختبار تربة',
        'interlock_test': 'اختبار بلاط وانترلوك'
    };
    return labels[testName] || testName;
}

// دالة لتحديث الحالة العامة للمختبر
function updateOverallLabStatus(activeTests, passedTests, failedTests) {
    const statusElement = document.getElementById('overall_lab_status');
    
    if (activeTests === 0) {
        statusElement.textContent = 'لم يتم تفعيل أي اختبارات';
        statusElement.className = 'badge fs-6 p-2 bg-secondary';
    } else if (failedTests === 0 && passedTests > 0) {
        statusElement.textContent = 'جميع الاختبارات ناجحة';
        statusElement.className = 'badge fs-6 p-2 bg-success';
    } else if (failedTests > 0 && passedTests > 0) {
        statusElement.textContent = 'نجاح جزئي - يوجد اختبارات راسبة';
        statusElement.className = 'badge fs-6 p-2 bg-warning';
    } else if (failedTests > 0 && passedTests === 0) {
        statusElement.textContent = 'جميع الاختبارات راسبة';
        statusElement.className = 'badge fs-6 p-2 bg-danger';
    } else {
        statusElement.textContent = 'قيد التقييم';
        statusElement.className = 'badge fs-6 p-2 bg-info';
    }
}

// تهيئة النظام عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // تهيئة الإجماليات عند تحميل الصفحة
    setTimeout(() => {
        updateTestTotals();
    }, 500);
});
</script>

@endsection 