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

    /* أنماط النظام الجديد مع التكرار */
    .test-row {
        background: rgba(255, 255, 255, 0.9);
        transition: all 0.3s ease;
        position: relative;
        border: 1px solid #e9ecef !important;
    }
    
    .test-row:hover {
        background: rgba(255, 255, 255, 1);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,123,255,0.15);
        border-color: #007bff !important;
    }
    
    .test-row .badge {
        animation: pulse 2s infinite;
        font-weight: 600;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.8; }
        50% { transform: scale(1.05); opacity: 1; }
        100% { transform: scale(1); opacity: 0.8; }
    }
    
    .btn-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
        border: none;
        transition: all 0.3s ease;
        font-weight: 600;
    }
    
    .btn-primary:hover {
        background: linear-gradient(45deg, #0056b3, #004085);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,123,255,0.4);
    }
    
    .btn-danger {
        background: linear-gradient(45deg, #dc3545, #c82333);
        border: none;
        transition: all 0.3s ease;
        font-weight: 600;
    }
    
    .btn-danger:hover {
        background: linear-gradient(45deg, #c82333, #bd2130);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(220,53,69,0.4);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        transform: scale(1.02);
        transition: all 0.3s ease;
    }
    
    .form-check-input:checked {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .form-check-label {
        font-weight: 600;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .form-check-label:hover {
        transform: scale(1.1);
    }
    
    .text-success {
        color: #28a745 !important;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }
    
    .bg-light {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    }
    
    .test-row .col-md-3 {
        padding: 0.25rem;
    }
    
    .test-row .form-label {
        margin-bottom: 0.25rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #495057;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 0.375rem;
    }
    
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
        transition: all 0.3s ease;
    }
    
    .btn-outline-secondary:hover {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
    }
    
    .test-row .d-flex.gap-2 {
        flex-wrap: nowrap;
    }
    
    .btn-outline-warning {
        color: #ffc107;
        border-color: #ffc107;
        transition: all 0.3s ease;
    }
    
    .btn-outline-warning:hover {
        color: #212529;
        background-color: #ffc107;
        border-color: #ffc107;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
    }
</style>

<script>

    
    $(document).ready(function() {
        // Handle restriction fields visibility
        $('#has_restriction').change(function() {
            const hasRestriction = $(this).val() === '1';
            $('#restriction_authority_field, #letters_commitments_field, #restriction_details_fields').toggle(hasRestriction);
            
            if (!hasRestriction) {
                $('#restriction_authority').val('');
                $('#restriction_reason').val('');
                $('#restriction_notes').val('');
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
        
        // تحميل رخص الحفر عند تحميل الصفحة
        loadDigLicenses();
        
        // تحميل قوائم الرخص في جميع التبويبات
        loadLicensesForSelectors();
        
        // منع الإرسال العادي للنموذج
        $('#digLicenseForm').on('submit', function(e) {
            e.preventDefault();
            return false;
        });
    });

    function saveCoordinationSection() {
        // التحقق من إدخال البيانات الأساسية
        const coordinationNumber = document.querySelector('input[name="coordination_certificate_number"]').value;
        
        if (!coordinationNumber) {
            toastr.warning('يجب إدخال رقم شهادة التنسيق قبل الحفظ');
            return;
        }
        
        // تأكيد من المستخدم قبل إنشاء رخصة جديدة
        if (!confirm('         بيانات شهادة التنسيق فيها. هل تريد المتابعة؟')) {
            return;
        }
        
        const formData = new FormData(document.getElementById('coordinationForm'));
        
        // إضافة معلومات القسم
        formData.set('section_type', 'coordination');
        formData.set('work_order_id', {{ $workOrder->id }});
        formData.set('force_new', true); // إجبار إنشاء رخصة جديدة
        
        // إظهار مؤشر التحميل
        const saveBtn = document.querySelector('button[onclick="saveCoordinationSection()"]');
        if (saveBtn) {
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري إنشاء الرخصة الجديدة...';
        }
        
        $.ajax({
            url: '{{ route("admin.licenses.save-section") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('License created successfully:', response);
                
                // إعادة تفعيل الزر
                if (saveBtn) {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fas fa-plus-circle me-2"></i>   بشهادة التنسيق';
                }
                
                // رسالة نجاح مفصلة
                toastr.success(`تم إنشاء رخصة جديدة برقم ${response.license_id} وحفظ شهادة التنسيق بنجاح!`);
                
                // تحديث جدول رخص الحفر
                loadDigLicenses();
                
                // إعادة تعيين النموذج
                document.getElementById('coordinationForm').reset();
                
                // التوجه لصفحة تفاصيل الرخصة الجديدة
                setTimeout(() => {
                    toastr.info('سيتم توجيهك لعرض تفاصيل الرخصة الجديدة...');
                    setTimeout(() => {
                        window.location.href = `/admin/licenses/${response.license_id}`;
                    }, 1000);
                }, 1500);
            },
            error: function(xhr) {
                console.error('Error saving coordination:', xhr);
                
                // إعادة تفعيل الزر
                if (saveBtn) {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fas fa-plus-circle me-2"></i> بشهادة التنسيق';
                }
                
                const errors = xhr.responseJSON?.errors || {};
                if (Object.keys(errors).length > 0) {
                    Object.values(errors).forEach(error => {
                        toastr.error(error[0]);
                    });
                } else {
                    const message = xhr.responseJSON?.message || 'حدث خطأ في إنشاء الرخصة ';
                    toastr.error(message);
                }
            }
        });
    }

    function saveDigLicenseSection() {
        // منع الإرسال العادي للنموذج
        event.preventDefault();
        
        const form = document.getElementById('digLicenseForm');
        if (!form) {
            toastr.error('لم يتم العثور على نموذج رخصة الحفر');
            return false;
        }

        const formData = new FormData(form);
        
        // إظهار loading على الزر
        const saveBtn = event.target;
        const originalText = saveBtn.innerHTML;
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';
        
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
                
                // إعادة تعيين النموذج
                form.reset();
                
                // تحديث جدول رخص الحفر
                loadDigLicenses();
                
                // إعادة تفعيل الزر
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalText;
            },
            error: function(xhr) {
                console.error('Error saving dig license:', xhr);
                
                // إعادة تفعيل الزر
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalText;
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    Object.values(errors).forEach(error => {
                        toastr.error(error[0]);
                    });
                } else {
                    const message = xhr.responseJSON?.message || 'حدث خطأ في حفظ رخصة الحفر';
                    toastr.error(message);
                }
            }
        });
        
        return false;
    }

    // دالة تحميل جدول رخص الحفر
    function loadDigLicenses() {
        $.ajax({
            url: `/admin/licenses/by-work-order/{{ $workOrder->id }}`,
            type: 'GET',
            success: function(response) {
                const tbody = document.getElementById('dig-licenses-table-body');
                if (!tbody) return;
                
                tbody.innerHTML = '';

                if (!response.licenses || response.licenses.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="10" class="text-center">لا توجد رخص حفر</td>
                        </tr>
                    `;

                    return;
                }



                response.licenses.forEach((license, index) => {
                    const licenseDate = license.license_date ? 
                        new Date(license.license_date).toLocaleDateString('ar-SA') : '';
                    const startDate = license.license_start_date ? 
                        new Date(license.license_start_date).toLocaleDateString('ar-SA') : '';
                    const endDate = license.license_end_date ? 
                        new Date(license.license_end_date).toLocaleDateString('ar-SA') : '';
                    
                    const licenseTypeBadge = getLicenseTypeBadge(license.license_type);
                    const dimensions = `${license.excavation_length || 0} × ${license.excavation_width || 0} × ${license.excavation_depth || 0} م`;
                    const period = startDate && endDate ? `${startDate} - ${endDate}` : '-';
                    
                    tbody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td><strong class="text-primary">${license.license_number || ''}</strong></td>
                            <td>${licenseDate}</td>
                            <td>${licenseTypeBadge}</td>
                            <td><strong class="text-success">${formatCurrency(license.license_value)}</strong></td>
                            <td><small>${dimensions}</small></td>
                            <td><small>${period}</small></td>
                            <td><span class="badge bg-info">${license.license_alert_days || 0} يوم</span></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    ${license.license_file_path ? `<a href="${license.license_file_path}" target="_blank" class="btn btn-outline-primary" title="ملف الرخصة"><i class="fas fa-file-pdf"></i></a>` : ''}
                                    ${license.payment_proof_path ? `<a href="${license.payment_proof_path}" target="_blank" class="btn btn-outline-success" title="إثبات السداد"><i class="fas fa-receipt"></i></a>` : ''}
                                </div>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-info" onclick="viewLicense(${license.id})" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" onclick="editLicense(${license.id})" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="deleteLicense(${license.id})" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                
                // تحديث قوائم الرخص في التبويبات الأخرى
                loadLicensesForSelectors();
            },
            error: function(xhr) {
                console.error('Error loading dig licenses:', xhr);
                toastr.error('حدث خطأ في تحميل رخص الحفر');
            }
                  });
      }



      // دالة للحصول على badge نوع الرخصة
    function getLicenseTypeBadge(type) {
        switch(type) {
            case 'emergency':
                return '<span class="badge bg-danger">طوارئ</span>';
            case 'project':
                return '<span class="badge bg-info">مشروع</span>';
            case 'normal':
                return '<span class="badge bg-success">عادي</span>';
            default:
                return '<span class="badge bg-secondary">غير محدد</span>';
        }
    }

    // دوال إضافية لإدارة الرخص
    function viewLicense(licenseId) {
        // التوجه إلى صفحة تفاصيل الرخصة المحددة
        if (licenseId) {
            window.location.href = `/admin/licenses/${licenseId}`;
        } else {
            toastr.error('معرف الرخصة غير صحيح');
        }
    }

    function editLicense(licenseId) {
        // التوجه إلى صفحة تعديل الرخصة المحددة
        if (licenseId) {
            window.location.href = `/admin/licenses/${licenseId}/edit`;
        } else {
            toastr.error('معرف الرخصة غير صحيح');
        }
    }

    function deleteLicense(licenseId) {
        if (!confirm('هل أنت متأكد من حذف هذه الرخصة؟')) {
            return;
        }

        $.ajax({
            url: `/admin/licenses/${licenseId}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                toastr.success('تم حذف الرخصة بنجاح');
                loadDigLicenses();
            },
            error: function(xhr) {
                console.error('Error deleting license:', xhr);
                toastr.error('حدث خطأ في حذف الرخصة');
            }
        });
    }

    function saveLabSection() {
        // التحقق من اختيار الرخصة
        const licenseId = document.getElementById('lab-license-id').value;
        const licenseSelector = document.getElementById('lab-license-selector');
        
        if (!licenseId) {
            toastr.warning('يجب اختيار رخصة قبل حفظ بيانات المختبر');
            return;
        }

        // الحصول على اسم الرخصة المختارة
        const selectedLicenseName = licenseSelector.options[licenseSelector.selectedIndex].text;
        
        // إظهار رسالة تأكيد
        if (!confirm(`هل أنت متأكد من حفظ بيانات المختبر في ${selectedLicenseName}؟`)) {
            return;
        }
        
        const formData = new FormData(document.getElementById('labForm'));
        
        // إضافة معلومات القسم والرخصة
        formData.set('section_type', 'lab');
        formData.set('license_id', licenseId);
        
        // تسجيل البيانات المرسلة للتحقق
        console.log('=== Lab Data Being Sent ===');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        console.log('========================');
        
        // إظهار مؤشر التحميل
        const saveBtn = document.getElementById('save-lab-btn');
        if (saveBtn) {
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';
        }
        
        $.ajax({
            url: '{{ route("admin.licenses.save-section") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Lab data saved successfully:', response);
                toastr.success(`تم حفظ بيانات المختبر بنجاح في ${selectedLicenseName}`);
                
                // إعادة تفعيل الزر
                if (saveBtn) {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>حفظ بيانات المختبر';
                }
                
                // تحديث البيانات بدلاً من إعادة تحميل الصفحة
                setTimeout(() => {
                    toastr.info('تم حفظ البيانات في الرخصة المختارة');
                }, 1000);
            },
            error: function(xhr) {
                // إعادة تفعيل الزر
                if (saveBtn) {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>حفظ بيانات المختبر';
                }
                
                const errors = xhr.responseJSON?.errors || {};
                if (Object.keys(errors).length > 0) {
                    Object.values(errors).forEach(error => {
                        toastr.error(error[0]);
                    });
                } else {
                    toastr.error('حدث خطأ في حفظ بيانات المختبر');
                }
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
                let totalAmount = 0;

                if (!response.violations || response.violations.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="11" class="text-center">لا توجد مخالفات</td>
                        </tr>
                    `;
                    updateTotalAmount(0);
                    return;
                }

                response.violations.forEach((violation, index) => {
                    const violationDate = violation.violation_date ? 
                        new Date(violation.violation_date).toLocaleDateString('en-GB') : '';
                    const dueDate = violation.payment_due_date ? 
                        new Date(violation.payment_due_date).toLocaleDateString('en-GB') : '';
                    
                    const paymentStatusBadge = violation.payment_status == 1 ? 
                        '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>مسددة</span>' : 
                        '<span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>غير مسددة</span>';

                    // تحديث إجمالي المبلغ
                    totalAmount += parseFloat(violation.violation_amount || 0);

                    tbody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td><strong class="text-primary">${violation.violation_number || ''}</strong></td>
                            <td>${violationDate}</td>
                            <td><span class="badge bg-info">${violation.violation_type || ''}</span></td>
                            <td>${violation.responsible_party || ''}</td>
                            <td>${violation.violation_description || '-'}</td>
                            <td><strong class="text-success">${formatCurrency(violation.violation_amount)}</strong></td>
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

                // تحديث إجمالي المبلغ في النهاية
                updateTotalAmount(totalAmount);
            },
            error: function(xhr) {
                console.error('Error loading violations:', xhr);
                toastr.error('حدث خطأ في تحميل المخالفات');
            }
        });
    }

    // دالة لتنسيق المبالغ
    function formatCurrency(amount) {
        return parseFloat(amount || 0).toLocaleString('ar-SA', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + ' ريال';
    }

    // دالة لتحديث إجمالي المبلغ
    function updateTotalAmount(amount) {
        const totalElement = document.getElementById('total-violations-amount');
        if (totalElement) {
            totalElement.innerHTML = `<strong>${formatCurrency(amount)}</strong>`;
        }
    }

    function saveViolationSection() {
        // التحقق من اختيار الرخصة
        const licenseId = document.getElementById('violation-license-id').value;
        if (!licenseId) {
            toastr.warning('يجب اختيار رخصة قبل حفظ بيانات المخالفات');
            return;
        }
        
        const form = document.getElementById('violationForm');
        if (!form) {
            toastr.error('لم يتم العثور على نموذج المخالفة');
            return;
        }

        const formData = new FormData(form);
        
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
                toastr.success('تم حفظ المخالفة بنجاح');
                resetViolationForm();
                loadViolations(); // سيقوم بتحديث الجدول والإجمالي
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || {};
                Object.values(errors).forEach(error => {
                    toastr.error(error[0]);
                });
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
                loadViolations(); // سيقوم بتحديث الجدول والإجمالي
            },
            error: function(xhr) {
                console.error('Error deleting violation:', xhr);
                toastr.error('حدث خطأ في حذف المخالفة');
            }
            });
}

// دوال إدارة اختيار الرخص في التبويبات المختلفة
function loadLicensesForSelectors() {
    $.ajax({
        url: `/admin/licenses/by-work-order/{{ $workOrder->id }}`,
        type: 'GET',
        success: function(response) {
            if (response.licenses && response.licenses.length > 0) {
                const labSelector = document.getElementById('lab-license-selector');
                const evacuationSelector = document.getElementById('evacuation-license-selector');
                const violationSelector = document.getElementById('violation-license-selector');
                
                // مسح الخيارات الحالية (عدا الخيار الافتراضي)
                [labSelector, evacuationSelector, violationSelector].forEach(selector => {
                    if (selector) {
                        // حفظ الخيار الافتراضي
                        const defaultOption = selector.querySelector('option[value=""]');
                        selector.innerHTML = '';
                        if (defaultOption) {
                            selector.appendChild(defaultOption.cloneNode(true));
                        }
                    }
                });
                
                // إضافة الرخص لكل قائمة
                response.licenses.forEach(license => {
                    const optionText = `رخصة #${license.license_number} - ${license.license_type || 'غير محدد'}`;
                    
                    [labSelector, evacuationSelector, violationSelector].forEach(selector => {
                        if (selector) {
                            const option = document.createElement('option');
                            option.value = license.id;
                            option.textContent = optionText;
                            selector.appendChild(option);
                        }
                    });
                });
                
                // إذا كان هناك رخصة واحدة فقط، اختارها تلقائياً
                if (response.licenses.length === 1) {
                    const licenseId = response.licenses[0].id;
                    const licenseText = `رخصة #${response.licenses[0].license_number}`;
                    
                    [labSelector, evacuationSelector, violationSelector].forEach(selector => {
                        if (selector) {
                            selector.value = licenseId;
                            // تشغيل دالة التحديد
                            if (selector.id === 'lab-license-selector') selectLabLicense();
                            else if (selector.id === 'evacuation-license-selector') selectEvacuationLicense();
                            else if (selector.id === 'violation-license-selector') selectViolationLicense();
                        }
                    });
                }
            }
        },
        error: function(xhr) {
            console.error('Error loading licenses for selectors:', xhr);
        }
    });
}

// دالة اختيار الرخصة للمختبر
function selectLabLicense() {
    const selector = document.getElementById('lab-license-selector');
    const licenseIdField = document.getElementById('lab-license-id');
    const infoDiv = document.getElementById('selected-lab-license-info');
    const displayElement = document.getElementById('lab-license-display');
    
    if (selector.value) {
        licenseIdField.value = selector.value;
        const selectedText = selector.options[selector.selectedIndex].text;
        displayElement.textContent = selectedText;
        infoDiv.style.display = 'block';
    } else {
        licenseIdField.value = '';
        infoDiv.style.display = 'none';
    }
}

// دالة اختيار الرخصة للإخلاءات
function selectEvacuationLicense() {
    const selector = document.getElementById('evacuation-license-selector');
    const licenseIdField = document.getElementById('evacuation-license-id');
    const infoDiv = document.getElementById('selected-evacuation-license-info');
    const displayElement = document.getElementById('evacuation-license-display');
    
    if (selector.value) {
        licenseIdField.value = selector.value;
        const selectedText = selector.options[selector.selectedIndex].text;
        displayElement.textContent = selectedText;
        infoDiv.style.display = 'block';
    } else {
        licenseIdField.value = '';
        infoDiv.style.display = 'none';
    }
}

// دالة اختيار الرخصة للمخالفات
function selectViolationLicense() {
    const selector = document.getElementById('violation-license-selector');
    const licenseIdField = document.getElementById('violation-license-id');
    const infoDiv = document.getElementById('selected-violation-license-info');
    const displayElement = document.getElementById('violation-license-display');
    
    if (selector.value) {
        licenseIdField.value = selector.value;
        const selectedText = selector.options[selector.selectedIndex].text;
        displayElement.textContent = selectedText;
        infoDiv.style.display = 'block';
    } else {
        licenseIdField.value = '';
        infoDiv.style.display = 'none';
    }
}
</script>

<div id="app">
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="license-header">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="mb-1 display-6 fw-bold">
                        <i class="fas fa-certificate me-2"></i>
                        إدارة الجودة والرخص
                    </h1>
                    
                </div>
                
                <div class="col-lg-4 text-end">
                    <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-light btn-lg">
                        <i class="fas fa-arrow-right me-2"></i>
                        العودة لتفاصيل أمر العمل
                    </a>
                </div>
            </div>
        </div>
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
                        
                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-flag-checkered text-success me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">حالة التنفيذ</small>
                                    <strong class="text-success fs-6">
                                        @switch($workOrder->execution_status)
                                            @case(2)
                                                تم تسليم 155
                                                @break
                                            @case(1)
                                                جاري العمل
                                                @break
                                            @case(3)
                                                صدرت شهادة
                                                @break
                                            @case(4)
                                                تم اعتماد الشهادة
                                                @break
                                            @case(5)
                                                مؤكد
                                                @break
                                            @case(6)
                                                دخل مستخلص
                                                @break
                                            @case(7)
                                                منتهي
                                                @break
                                            @default
                                                غير محدد
                                        @endswitch
                                    </strong>
                                </div>
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

                    <!-- حقول إضافية للقيود -->
                    <div class="row g-3 mb-3" id="restriction_details_fields" style="display: none;">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">سبب الحظر</label>
                            <input type="text" class="form-control" name="restriction_reason" id="restriction_reason" placeholder="اذكر سبب الحظر...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ملاحظات الحظر</label>
                            <textarea class="form-control" name="restriction_notes" id="restriction_notes" rows="2" placeholder="أي ملاحظات إضافية حول الحظر..."></textarea>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-success btn-lg fw-bold" onclick="saveCoordinationSection()">
                                <i class="fas fa-plus-circle me-2"></i>
                                حفظ شهادة التنسيق 
                            </button>
                            <div class="mt-2">
                                
                            </div>
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
                    <!-- نموذج إضافة رخصة الحفر -->
                    <div class="card border-primary mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-plus me-2"></i>
                                إضافة رخصة حفر جديدة
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="digLicenseForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                                
                                <!-- معلومات الرخصة الأساسية -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">رقم الرخصة</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                            <input type="text" class="form-control" name="license_number">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">تاريخ إصدار الرخصة</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            <input type="date" class="form-control" name="license_date">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">نوع الرخصة</label>
                                        <select class="form-select" name="license_type">
                                            <option value="">اختر نوع الرخصة</option>
                                            <option value="emergency">طوارئ</option>
                                            <option value="project">مشروع</option>
                                            <option value="normal">عادي</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">قيمة الرخصة</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-money-bill"></i></span>
                                            <input type="number" step="0.01" class="form-control" name="license_value">
                                            <span class="input-group-text">ريال</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- أبعاد الحفر -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">طول الحفر (متر)</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-ruler-horizontal"></i></span>
                                            <input type="number" step="0.01" class="form-control" name="excavation_length">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">عرض الحفر (متر)</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-arrows-alt-h"></i></span>
                                            <input type="number" step="0.01" class="form-control" name="excavation_width">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">عمق الحفر (متر)</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-ruler-vertical"></i></span>
                                            <input type="number" step="0.01" class="form-control" name="excavation_depth">
                                        </div>
                                    </div>
                                </div>

                                <!-- تواريخ الرخصة -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">تاريخ تفعيل الرخصة</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-play"></i></span>
                                            <input type="date" class="form-control" name="license_start_date" id="license_start_date">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">تاريخ نهاية الرخصة</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-stop"></i></span>
                                            <input type="date" class="form-control" name="license_end_date" id="license_end_date">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">عدد أيام الرخصة</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                            <input type="number" class="form-control bg-light" name="license_alert_days" id="license_days" readonly>
                                        </div>
                                    </div>
                                </div>

                                <!-- المرفقات -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">ملف الرخصة</label>
                                        <input type="file" class="form-control" name="license_file_path" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">إثبات سداد البنك</label>
                                        <input type="file" class="form-control" name="payment_proof_path" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">فواتير السداد</label>
                                        <input type="file" class="form-control" name="payment_invoices_path" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center">
                            <button type="button" class="btn btn-primary" onclick="saveDigLicenseSection()">
                                <i class="fas fa-save me-2"></i>
                                حفظ رخصة الحفر
                            </button>
                        </div>
                    </div>

                    <!-- جدول رخص الحفر -->
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-2"></i>
                                سجل رخص الحفر
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-info">
                                        <tr>
                                            <th>#</th>
                                            <th>رقم الرخصة</th>
                                            <th>تاريخ الإصدار</th>
                                            <th>نوع الرخصة</th>
                                            <th>قيمة الرخصة</th>
                                            <th>أبعاد الحفر</th>
                                            <th>فترة الرخصة</th>
                                            <th>عدد الأيام</th>
                                            <th>المرفقات</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dig-licenses-table-body">
                                        <tr>
                                            <td colspan="10" class="text-center">لا توجد رخص حفر</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- قسم التمديدات -->
                    <div class="card mt-4">
                        <div class="card-header bg-warning text-dark">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar-plus me-2"></i>
                                    تمديدات الرخص
                                </h5>
                                <button type="button" class="btn btn-success btn-sm" onclick="addNewExtension()">
                                    <i class="fas fa-plus me-1"></i>
                                    إضافة تمديد
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="extensionsTable">
                                    <thead class="table-warning">
                                        <tr>
                                            <th>#</th>
                                            <th>رقم الرخصة</th>
                                            <th>قيمة التمديد</th>
                                            <th>تاريخ البداية</th>
                                            <th>تاريخ النهاية</th>
                                            <th>عدد الأيام</th>
                                            <th>ملف الرخصة</th>
                                            <th>إثبات السداد</th>
                                            <th>إثبات سداد البنك</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody id="extensions-table-body">
                                        <tr id="no-extensions-row">
                                            <td colspan="10" class="text-center">لا توجد تمديدات</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
                    <!-- اختيار الرخصة للمختبر -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-clipboard-list me-2"></i>
                                        اختيار الرخصة للعمل عليها
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-search me-2"></i>اختر الرخصة:
                                    </label>
                                    <select class="form-select" id="lab-license-selector" onchange="selectLabLicense()">
                                        <option value="">-- اختر الرخصة للعمل عليها --</option>
                                    </select>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            يجب اختيار رخصة قبل إدخال بيانات المختبر
                                        </small>
                                    </div>
                                    <div id="selected-lab-license-info" class="mt-2" style="display: none;">
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-certificate me-2"></i>
                                            الرخصة المختارة: <strong id="lab-license-display"></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form id="labForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                        <input type="hidden" name="license_id" id="lab-license-id" value="">
                        
                        <!-- اختبارات المختبر مع النظام التفاعلي -->
                        <div class="row g-3 mb-4">
                            <!-- اختبار العمق -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-ruler-vertical me-2"></i>اختبار العمق (مراجعة البيانات العامة للحفريات)
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
                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('depth_test')">
                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('depth_test')">
                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                </button>
                            </div>
                            
                            <div id="depth_test_rows_container">
                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-info">القيمة #1</span>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('depth_test', 1)" style="display: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label small"> النقاط</label>
                                            <input type="number" class="form-control form-control-sm" name="depth_test_input_1" step="0.01" onchange="calculateTestRow('depth_test', 1)" placeholder="أدخل عدد النقاط">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">السعر</label>
                                            <select class="form-select form-select-sm" name="depth_test_factor_1" onchange="calculateTestRow('depth_test', 1)">
                                                <option value="46">46</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">النتيجة</label>
                                            <input type="number" class="form-control form-control-sm bg-light" name="depth_test_result_1" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">الحالة</label>
                                            <div class="d-flex gap-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="depth_test_status_1" value="pass" id="depth_test_pass_1" onchange="updateTestTotals()">
                                                    <label class="form-check-label text-success" for="depth_test_pass_1">
                                                        <i class="fas fa-check"></i> ناجح
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="depth_test_status_1" value="fail" id="depth_test_fail_1" onchange="updateTestTotals()">
                                                    <label class="form-check-label text-danger" for="depth_test_fail_1">
                                                        <i class="fas fa-times"></i> راسب
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
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
                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('soil_compaction_test')">
                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('soil_compaction_test')">
                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                </button>
                            </div>
                            
                            <div id="soil_compaction_test_rows_container">
                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-info">القيمة #1</span>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('soil_compaction_test', 1)" style="display: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label small"> النقاط</label>
                                            <input type="number" class="form-control form-control-sm" name="soil_compaction_test_input_1" step="0.01" onchange="calculateTestRow('soil_compaction_test', 1)" placeholder="أدخل عدد النقاط">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">السعر</label>
                                            <select class="form-select form-select-sm" name="soil_compaction_test_factor_1" onchange="calculateTestRow('soil_compaction_test', 1)">
                                                <option value="90">90</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">النتيجة</label>
                                            <input type="number" class="form-control form-control-sm bg-light" name="soil_compaction_test_result_1" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">الحالة</label>
                                            <div class="d-flex gap-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="soil_compaction_test_status_1" value="pass" id="soil_compaction_test_pass_1" onchange="updateTestTotals()">
                                                    <label class="form-check-label text-success" for="soil_compaction_test_pass_1">
                                                        <i class="fas fa-check"></i> ناجح
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="soil_compaction_test_status_1" value="fail" id="soil_compaction_test_fail_1" onchange="updateTestTotals()">
                                                    <label class="form-check-label text-danger" for="soil_compaction_test_fail_1">
                                                        <i class="fas fa-times"></i> راسب
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
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
                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('rc1_mc1_test')">
                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('rc1_mc1_test')">
                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                </button>
                            </div>
                            
                            <div id="rc1_mc1_test_rows_container">
                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-info">القيمة #1</span>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('rc1_mc1_test', 1)" style="display: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label small"> النقاط</label>
                                            <input type="number" class="form-control form-control-sm" name="rc1_mc1_test_input_1" step="0.01" onchange="calculateTestRow('rc1_mc1_test', 1)" placeholder="أدخل عدد النقاط">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">السعر</label>
                                            <select class="form-select form-select-sm" name="rc1_mc1_test_factor_1" onchange="calculateTestRow('rc1_mc1_test', 1)">
                                                <option value="38">38</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">النتيجة</label>
                                            <input type="number" class="form-control form-control-sm bg-light" name="rc1_mc1_test_result_1" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">الحالة</label>
                                            <div class="d-flex gap-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="rc1_mc1_test_status_1" value="pass" id="rc1_mc1_test_pass_1" onchange="updateTestTotals()">
                                                    <label class="form-check-label text-success" for="rc1_mc1_test_pass_1">
                                                        <i class="fas fa-check"></i> ناجح
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="rc1_mc1_test_status_1" value="fail" id="rc1_mc1_test_fail_1" onchange="updateTestTotals()">
                                                    <label class="form-check-label text-danger" for="rc1_mc1_test_fail_1">
                                                        <i class="fas fa-times"></i> راسب
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
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
                                            <i class="fas fa-road me-2"></i>اختبار دك أسفلت
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
                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('asphalt_test')">
                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('asphalt_test')">
                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                </button>
                            </div>
                            
                            <div id="asphalt_test_rows_container">
                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-info">القيمة #1</span>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('asphalt_test', 1)" style="display: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label small"> النقاط</label>
                                            <input type="number" class="form-control form-control-sm" name="asphalt_test_input_1" step="0.01" onchange="calculateTestRow('asphalt_test', 1)" placeholder="أدخل عدد النقاط">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">السعر</label>
                                            <select class="form-select form-select-sm" name="asphalt_test_factor_1" onchange="calculateTestRow('asphalt_test', 1)">
                                                <option value="84">84</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">النتيجة</label>
                                            <input type="number" class="form-control form-control-sm bg-light" name="asphalt_test_result_1" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">الحالة</label>
                                            <div class="d-flex gap-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="asphalt_test_status_1" value="pass" id="asphalt_test_pass_1" onchange="updateTestTotals()">
                                                    <label class="form-check-label text-success" for="asphalt_test_pass_1">
                                                    <i class="fas fa-check"></i> ناجح
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="asphalt_test_status_1" value="fail" id="asphalt_test_fail_1" onchange="updateTestTotals()">
                                                    <label class="form-check-label text-danger" for="asphalt_test_fail_1">
                                                    <i class="fas fa-check"></i> راسب
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
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
                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('soil_test')">
                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('soil_test')">
                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                </button>
                            </div>
                            
                            <div id="soil_test_rows_container">
                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-info">القيمة #1</span>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('soil_test', 1)" style="display: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label small"> النقاط</label>
                                            <input type="number" class="form-control form-control-sm" name="soil_test_input_1" step="0.01" onchange="calculateTestRow('soil_test', 1)" placeholder="أدخل عدد النقاط ">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">السعر</label>
                                            <select class="form-select form-select-sm" name="soil_test_factor_1" onchange="calculateTestRow('soil_test', 1)">
                                                <option value="178">178</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">النتيجة</label>
                                            <input type="number" class="form-control form-control-sm bg-light" name="soil_test_result_1" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">الحالة</label>
                                            <div class="d-flex gap-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="soil_test_status_1" value="pass" id="soil_test_pass_1" onchange="updateTestTotals()">
                                                    <label class="form-check-label text-success" for="soil_test_pass_1">
                                                    <i class="fas fa-check"></i> ناجح
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="soil_test_status_1" value="fail" id="soil_test_fail_1" onchange="updateTestTotals()">
                                                    <label class="form-check-label text-danger" for="soil_test_fail_1">
                                                    <i class="fas fa-check"></i> راسب
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
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
                                            <i class="fas fa-th me-2"></i>اختبار تقيم بلاط والارصفة والبردورات
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
                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('interlock_test')">
                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('interlock_test')">
                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                </button>
                            </div>
                            
                            <div id="interlock_test_rows_container">
                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-info">القيمة #1</span>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('interlock_test', 1)" style="display: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label small">النقاط</label>
                                            <input type="number" class="form-control form-control-sm" name="interlock_test_input_1" step="0.01" onchange="calculateTestRow('interlock_test', 1)" placeholder="أدخل عدد النقاط">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">السعر</label>
                                            <select class="form-select form-select-sm" name="interlock_test_factor_1" onchange="calculateTestRow('interlock_test', 1)">
                                                <option value="34">34</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">النتيجة</label>
                                            <input type="number" class="form-control form-control-sm bg-light" name="interlock_test_result_1" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">الحالة</label>
                                            <div class="d-flex gap-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="interlock_test_status_1" value="pass" id="interlock_test_pass_1" onchange="updateTestTotals()">
                                                    <label class="form-check-label text-success" for="interlock_test_pass_1">
                                                    <i class="fas fa-check"></i> ناجح
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="interlock_test_status_1" value="fail" id="interlock_test_fail_1" onchange="updateTestTotals()">
                                                    <label class="form-check-label text-danger" for="interlock_test_fail_1">
                                                    <i class="fas fa-check"></i> راسب
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
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

                        <!-- الاختبارات الإضافية الجديدة -->
                        <div class="row g-3 mb-4">
                            <!-- اختبار الكثافة الجافة القصوى (طريقة برو) -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-weight me-2"></i>اختبار الكثافة الجافة القصوى (طريقة برو)
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_max_dry_density_pro_test" value="1" id="max_dry_density_pro_test_yes" data-test="max_dry_density_pro_test" onchange="toggleTestCalculation('max_dry_density_pro_test')">
                                                <label class="form-check-label" for="max_dry_density_pro_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_max_dry_density_pro_test" value="0" id="max_dry_density_pro_test_no" data-test="max_dry_density_pro_test" checked onchange="toggleTestCalculation('max_dry_density_pro_test')">
                                                <label class="form-check-label" for="max_dry_density_pro_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation max_dry_density_pro_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('max_dry_density_pro_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('max_dry_density_pro_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="max_dry_density_pro_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('max_dry_density_pro_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="max_dry_density_pro_test_input_1" step="0.01" onchange="calculateTestRow('max_dry_density_pro_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="max_dry_density_pro_test_factor_1" onchange="calculateTestRow('max_dry_density_pro_test', 1)">
                                                                <option value="120">120</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="max_dry_density_pro_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="max_dry_density_pro_test_status_1" value="pass" id="max_dry_density_pro_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="max_dry_density_pro_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="max_dry_density_pro_test_status_1" value="fail" id="max_dry_density_pro_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="max_dry_density_pro_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="max_dry_density_pro_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- اختبار تعيين نسبة الاسفلت والتدرج الحبيبي -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-chart-line me-2"></i>اختبار تعيين نسبة الاسفلت والتدرج الحبيبي
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_asphalt_ratio_gradation_test" value="1" id="asphalt_ratio_gradation_test_yes" data-test="asphalt_ratio_gradation_test" onchange="toggleTestCalculation('asphalt_ratio_gradation_test')">
                                                <label class="form-check-label" for="asphalt_ratio_gradation_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_asphalt_ratio_gradation_test" value="0" id="asphalt_ratio_gradation_test_no" data-test="asphalt_ratio_gradation_test" checked onchange="toggleTestCalculation('asphalt_ratio_gradation_test')">
                                                <label class="form-check-label" for="asphalt_ratio_gradation_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation asphalt_ratio_gradation_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('asphalt_ratio_gradation_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('asphalt_ratio_gradation_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="asphalt_ratio_gradation_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('asphalt_ratio_gradation_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="asphalt_ratio_gradation_test_input_1" step="0.01" onchange="calculateTestRow('asphalt_ratio_gradation_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="asphalt_ratio_gradation_test_factor_1" onchange="calculateTestRow('asphalt_ratio_gradation_test', 1)">
                                                                <option value="145">145</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="asphalt_ratio_gradation_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="asphalt_ratio_gradation_test_status_1" value="pass" id="asphalt_ratio_gradation_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="asphalt_ratio_gradation_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="asphalt_ratio_gradation_test_status_1" value="fail" id="asphalt_ratio_gradation_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="asphalt_ratio_gradation_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="asphalt_ratio_gradation_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- اختبار تجربة مارشال -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-cogs me-2"></i>اختبار تجربة مارشال
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_marshall_test" value="1" id="marshall_test_yes" data-test="marshall_test" onchange="toggleTestCalculation('marshall_test')">
                                                <label class="form-check-label" for="marshall_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_marshall_test" value="0" id="marshall_test_no" data-test="marshall_test" checked onchange="toggleTestCalculation('marshall_test')">
                                                <label class="form-check-label" for="marshall_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation marshall_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('marshall_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('marshall_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="marshall_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('marshall_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="marshall_test_input_1" step="0.01" onchange="calculateTestRow('marshall_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="marshall_test_factor_1" onchange="calculateTestRow('marshall_test', 1)">
                                                                <option value="280">280</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="marshall_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="marshall_test_status_1" value="pass" id="marshall_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="marshall_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="marshall_test_status_1" value="fail" id="marshall_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="marshall_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="marshall_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- اختبار اعداد القوالب الخرسانية وصبها مع الكسر -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-cube me-2"></i>اختبار اعداد القوالب الخرسانية وصبها مع الكسر
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_concrete_molds_test" value="1" id="concrete_molds_test_yes" data-test="concrete_molds_test" onchange="toggleTestCalculation('concrete_molds_test')">
                                                <label class="form-check-label" for="concrete_molds_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_concrete_molds_test" value="0" id="concrete_molds_test_no" data-test="concrete_molds_test" checked onchange="toggleTestCalculation('concrete_molds_test')">
                                                <label class="form-check-label" for="concrete_molds_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation concrete_molds_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('concrete_molds_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('concrete_molds_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="concrete_molds_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('concrete_molds_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="concrete_molds_test_input_1" step="0.01" onchange="calculateTestRow('concrete_molds_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="concrete_molds_test_factor_1" onchange="calculateTestRow('concrete_molds_test', 1)">
                                                                <option value="95">95</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="concrete_molds_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="concrete_molds_test_status_1" value="pass" id="concrete_molds_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="concrete_molds_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="concrete_molds_test_status_1" value="fail" id="concrete_molds_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="concrete_molds_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="concrete_molds_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- اختبار قاع الحفر -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-excavator me-2"></i>اختبار قاع الحفر
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_excavation_bottom_test" value="1" id="excavation_bottom_test_yes" data-test="excavation_bottom_test" onchange="toggleTestCalculation('excavation_bottom_test')">
                                                <label class="form-check-label" for="excavation_bottom_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_excavation_bottom_test" value="0" id="excavation_bottom_test_no" data-test="excavation_bottom_test" checked onchange="toggleTestCalculation('excavation_bottom_test')">
                                                <label class="form-check-label" for="excavation_bottom_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation excavation_bottom_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('excavation_bottom_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('excavation_bottom_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="excavation_bottom_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('excavation_bottom_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="excavation_bottom_test_input_1" step="0.01" onchange="calculateTestRow('excavation_bottom_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="excavation_bottom_test_factor_1" onchange="calculateTestRow('excavation_bottom_test', 1)">
                                                                <option value="75">75</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="excavation_bottom_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="excavation_bottom_test_status_1" value="pass" id="excavation_bottom_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="excavation_bottom_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="excavation_bottom_test_status_1" value="fail" id="excavation_bottom_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="excavation_bottom_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="excavation_bottom_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- اختبار تحديد الاعماق لمواد الحماية -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-shield-alt me-2"></i>اختبار تحديد الاعماق لمواد الحماية من رمل او الخرسانه
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_protection_depth_test" value="1" id="protection_depth_test_yes" data-test="protection_depth_test" onchange="toggleTestCalculation('protection_depth_test')">
                                                <label class="form-check-label" for="protection_depth_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_protection_depth_test" value="0" id="protection_depth_test_no" data-test="protection_depth_test" checked onchange="toggleTestCalculation('protection_depth_test')">
                                                <label class="form-check-label" for="protection_depth_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation protection_depth_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('protection_depth_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('protection_depth_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="protection_depth_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('protection_depth_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="protection_depth_test_input_1" step="0.01" onchange="calculateTestRow('protection_depth_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="protection_depth_test_factor_1" onchange="calculateTestRow('protection_depth_test', 1)">
                                                                <option value="55">55</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="protection_depth_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="protection_depth_test_status_1" value="pass" id="protection_depth_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="protection_depth_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="protection_depth_test_status_1" value="fail" id="protection_depth_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="protection_depth_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="protection_depth_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- اختبار الهبوط -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-arrow-down me-2"></i>اختبار الهبوط
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_settlement_test" value="1" id="settlement_test_yes" data-test="settlement_test" onchange="toggleTestCalculation('settlement_test')">
                                                <label class="form-check-label" for="settlement_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_settlement_test" value="0" id="settlement_test_no" data-test="settlement_test" checked onchange="toggleTestCalculation('settlement_test')">
                                                <label class="form-check-label" for="settlement_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation settlement_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('settlement_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('settlement_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="settlement_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('settlement_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="settlement_test_input_1" step="0.01" onchange="calculateTestRow('settlement_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="settlement_test_factor_1" onchange="calculateTestRow('settlement_test', 1)">
                                                                <option value="85">85</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="settlement_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="settlement_test_status_1" value="pass" id="settlement_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="settlement_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="settlement_test_status_1" value="fail" id="settlement_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="settlement_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="settlement_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- اختبار درجة حرارة الخرسانة -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-thermometer-half me-2"></i>اختبار درجة حرارة الخرسانة
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_concrete_temperature_test" value="1" id="concrete_temperature_test_yes" data-test="concrete_temperature_test" onchange="toggleTestCalculation('concrete_temperature_test')">
                                                <label class="form-check-label" for="concrete_temperature_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_concrete_temperature_test" value="0" id="concrete_temperature_test_no" data-test="concrete_temperature_test" checked onchange="toggleTestCalculation('concrete_temperature_test')">
                                                <label class="form-check-label" for="concrete_temperature_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation concrete_temperature_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('concrete_temperature_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('concrete_temperature_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="concrete_temperature_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('concrete_temperature_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="concrete_temperature_test_input_1" step="0.01" onchange="calculateTestRow('concrete_temperature_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="concrete_temperature_test_factor_1" onchange="calculateTestRow('concrete_temperature_test', 1)">
                                                                <option value="25">25</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="concrete_temperature_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="concrete_temperature_test_status_1" value="pass" id="concrete_temperature_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="concrete_temperature_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="concrete_temperature_test_status_1" value="fail" id="concrete_temperature_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="concrete_temperature_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="concrete_temperature_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- اختبار الكثافة الحقلية باستخدام الجهاز الذري -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-atom me-2"></i>اختبار الكثافة الحقلية باستخدام الجهاز الذري
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_field_density_atomic_test" value="1" id="field_density_atomic_test_yes" data-test="field_density_atomic_test" onchange="toggleTestCalculation('field_density_atomic_test')">
                                                <label class="form-check-label" for="field_density_atomic_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_field_density_atomic_test" value="0" id="field_density_atomic_test_no" data-test="field_density_atomic_test" checked onchange="toggleTestCalculation('field_density_atomic_test')">
                                                <label class="form-check-label" for="field_density_atomic_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation field_density_atomic_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('field_density_atomic_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('field_density_atomic_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="field_density_atomic_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('field_density_atomic_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="field_density_atomic_test_input_1" step="0.01" onchange="calculateTestRow('field_density_atomic_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="field_density_atomic_test_factor_1" onchange="calculateTestRow('field_density_atomic_test', 1)">
                                                                <option value="90">90</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="field_density_atomic_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="field_density_atomic_test_status_1" value="pass" id="field_density_atomic_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="field_density_atomic_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="field_density_atomic_test_status_1" value="fail" id="field_density_atomic_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="field_density_atomic_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="field_density_atomic_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- اختبار محتوي الرطوبة -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-tint me-2"></i>اختبار محتوي الرطوبة
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_moisture_content_test" value="1" id="moisture_content_test_yes" data-test="moisture_content_test" onchange="toggleTestCalculation('moisture_content_test')">
                                                <label class="form-check-label" for="moisture_content_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_moisture_content_test" value="0" id="moisture_content_test_no" data-test="moisture_content_test" checked onchange="toggleTestCalculation('moisture_content_test')">
                                                <label class="form-check-label" for="moisture_content_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation moisture_content_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('moisture_content_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('moisture_content_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="moisture_content_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('moisture_content_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="moisture_content_test_input_1" step="0.01" onchange="calculateTestRow('moisture_content_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="moisture_content_test_factor_1" onchange="calculateTestRow('moisture_content_test', 1)">
                                                                <option value="35">35</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="moisture_content_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="moisture_content_test_status_1" value="pass" id="moisture_content_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="moisture_content_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="moisture_content_test_status_1" value="fail" id="moisture_content_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="moisture_content_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="moisture_content_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- اختبار استواء طبقة التربة -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-layer-group me-2"></i>اختبار استواء طبقة التربة للمواقع الترابية ذات الطبقة الواحدة
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_soil_layer_flatness_test" value="1" id="soil_layer_flatness_test_yes" data-test="soil_layer_flatness_test" onchange="toggleTestCalculation('soil_layer_flatness_test')">
                                                <label class="form-check-label" for="soil_layer_flatness_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_soil_layer_flatness_test" value="0" id="soil_layer_flatness_test_no" data-test="soil_layer_flatness_test" checked onchange="toggleTestCalculation('soil_layer_flatness_test')">
                                                <label class="form-check-label" for="soil_layer_flatness_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation soil_layer_flatness_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('soil_layer_flatness_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('soil_layer_flatness_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="soil_layer_flatness_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('soil_layer_flatness_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="soil_layer_flatness_test_input_1" step="0.01" onchange="calculateTestRow('soil_layer_flatness_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="soil_layer_flatness_test_factor_1" onchange="calculateTestRow('soil_layer_flatness_test', 1)">
                                                                <option value="42">42</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="soil_layer_flatness_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="soil_layer_flatness_test_status_1" value="pass" id="soil_layer_flatness_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="soil_layer_flatness_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="soil_layer_flatness_test_status_1" value="fail" id="soil_layer_flatness_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="soil_layer_flatness_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="soil_layer_flatness_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- اختبار اخذ عينة من الخرسانة بالموقع -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-cubes me-2"></i>اختبار اخذ عينة من الخرسانة بالموقع (مكعب اواسطوانة)
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_concrete_sample_test" value="1" id="concrete_sample_test_yes" data-test="concrete_sample_test" onchange="toggleTestCalculation('concrete_sample_test')">
                                                <label class="form-check-label" for="concrete_sample_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_concrete_sample_test" value="0" id="concrete_sample_test_no" data-test="concrete_sample_test" checked onchange="toggleTestCalculation('concrete_sample_test')">
                                                <label class="form-check-label" for="concrete_sample_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation concrete_sample_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('concrete_sample_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('concrete_sample_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="concrete_sample_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('concrete_sample_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="concrete_sample_test_input_1" step="0.01" onchange="calculateTestRow('concrete_sample_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="concrete_sample_test_factor_1" onchange="calculateTestRow('concrete_sample_test', 1)">
                                                                <option value="65">65</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="concrete_sample_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="concrete_sample_test_status_1" value="pass" id="concrete_sample_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="concrete_sample_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="concrete_sample_test_status_1" value="fail" id="concrete_sample_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="concrete_sample_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="concrete_sample_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- اختبار معدل الرش الاسفلتي -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-spray-can me-2"></i>اختبار معدل الرش الاسفلتي MC1/RC2
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_asphalt_spray_rate_test" value="1" id="asphalt_spray_rate_test_yes" data-test="asphalt_spray_rate_test" onchange="toggleTestCalculation('asphalt_spray_rate_test')">
                                                <label class="form-check-label" for="asphalt_spray_rate_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_asphalt_spray_rate_test" value="0" id="asphalt_spray_rate_test_no" data-test="asphalt_spray_rate_test" checked onchange="toggleTestCalculation('asphalt_spray_rate_test')">
                                                <label class="form-check-label" for="asphalt_spray_rate_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation asphalt_spray_rate_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('asphalt_spray_rate_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('asphalt_spray_rate_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="asphalt_spray_rate_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('asphalt_spray_rate_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="asphalt_spray_rate_test_input_1" step="0.01" onchange="calculateTestRow('asphalt_spray_rate_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="asphalt_spray_rate_test_factor_1" onchange="calculateTestRow('asphalt_spray_rate_test', 1)">
                                                                <option value="38">38</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="asphalt_spray_rate_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="asphalt_spray_rate_test_status_1" value="pass" id="asphalt_spray_rate_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="asphalt_spray_rate_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="asphalt_spray_rate_test_status_1" value="fail" id="asphalt_spray_rate_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="asphalt_spray_rate_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="asphalt_spray_rate_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- اختبار قياس درجة حرارة الاسفلت -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-thermometer-three-quarters me-2"></i>اختبار قياس درجة حرارة الاسفلت
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_asphalt_temperature_test" value="1" id="asphalt_temperature_test_yes" data-test="asphalt_temperature_test" onchange="toggleTestCalculation('asphalt_temperature_test')">
                                                <label class="form-check-label" for="asphalt_temperature_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_asphalt_temperature_test" value="0" id="asphalt_temperature_test_no" data-test="asphalt_temperature_test" checked onchange="toggleTestCalculation('asphalt_temperature_test')">
                                                <label class="form-check-label" for="asphalt_temperature_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation asphalt_temperature_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('asphalt_temperature_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('asphalt_temperature_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="asphalt_temperature_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('asphalt_temperature_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="asphalt_temperature_test_input_1" step="0.01" onchange="calculateTestRow('asphalt_temperature_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="asphalt_temperature_test_factor_1" onchange="calculateTestRow('asphalt_temperature_test', 1)">
                                                                <option value="25">25</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="asphalt_temperature_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="asphalt_temperature_test_status_1" value="pass" id="asphalt_temperature_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="asphalt_temperature_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="asphalt_temperature_test_status_1" value="fail" id="asphalt_temperature_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="asphalt_temperature_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="asphalt_temperature_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- اختبار تعيين مقاومة الضغط لخرسانة الاسطوانات -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-compress-arrows-alt me-2"></i>اختبار تعيين مقاومة الضغط لخرسانة الاسطوانات
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_concrete_compression_test" value="1" id="concrete_compression_test_yes" data-test="concrete_compression_test" onchange="toggleTestCalculation('concrete_compression_test')">
                                                <label class="form-check-label" for="concrete_compression_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_concrete_compression_test" value="0" id="concrete_compression_test_no" data-test="concrete_compression_test" checked onchange="toggleTestCalculation('concrete_compression_test')">
                                                <label class="form-check-label" for="concrete_compression_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation concrete_compression_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('concrete_compression_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('concrete_compression_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="concrete_compression_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('concrete_compression_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="concrete_compression_test_input_1" step="0.01" onchange="calculateTestRow('concrete_compression_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="concrete_compression_test_factor_1" onchange="calculateTestRow('concrete_compression_test', 1)">
                                                                <option value="105">105</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="concrete_compression_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="concrete_compression_test_status_1" value="pass" id="concrete_compression_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="concrete_compression_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="concrete_compression_test_status_1" value="fail" id="concrete_compression_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="concrete_compression_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="concrete_compression_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- اختبار التحليل الحبيبي للتربة -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-sitemap me-2"></i>اختبار التحليل الحبيبي للتربة
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_soil_grain_analysis_test" value="1" id="soil_grain_analysis_test_yes" data-test="soil_grain_analysis_test" onchange="toggleTestCalculation('soil_grain_analysis_test')">
                                                <label class="form-check-label" for="soil_grain_analysis_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_soil_grain_analysis_test" value="0" id="soil_grain_analysis_test_no" data-test="soil_grain_analysis_test" checked onchange="toggleTestCalculation('soil_grain_analysis_test')">
                                                <label class="form-check-label" for="soil_grain_analysis_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation soil_grain_analysis_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('soil_grain_analysis_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('soil_grain_analysis_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="soil_grain_analysis_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('soil_grain_analysis_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="soil_grain_analysis_test_input_1" step="0.01" onchange="calculateTestRow('soil_grain_analysis_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="soil_grain_analysis_test_factor_1" onchange="calculateTestRow('soil_grain_analysis_test', 1)">
                                                                <option value="125">125</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="soil_grain_analysis_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="soil_grain_analysis_test_status_1" value="pass" id="soil_grain_analysis_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="soil_grain_analysis_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="soil_grain_analysis_test_status_1" value="fail" id="soil_grain_analysis_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="soil_grain_analysis_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="soil_grain_analysis_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- اختبار تحديد حد السيولة واللدونة ومؤشر اللدونة -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-balance-scale me-2"></i>اختبار تحديد حد السيولة واللدونة ومؤشر اللدونة
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_liquidity_plasticity_test" value="1" id="liquidity_plasticity_test_yes" data-test="liquidity_plasticity_test" onchange="toggleTestCalculation('liquidity_plasticity_test')">
                                                <label class="form-check-label" for="liquidity_plasticity_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_liquidity_plasticity_test" value="0" id="liquidity_plasticity_test_no" data-test="liquidity_plasticity_test" checked onchange="toggleTestCalculation('liquidity_plasticity_test')">
                                                <label class="form-check-label" for="liquidity_plasticity_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation liquidity_plasticity_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('liquidity_plasticity_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('liquidity_plasticity_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="liquidity_plasticity_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('liquidity_plasticity_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="liquidity_plasticity_test_input_1" step="0.01" onchange="calculateTestRow('liquidity_plasticity_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="liquidity_plasticity_test_factor_1" onchange="calculateTestRow('liquidity_plasticity_test', 1)">
                                                                <option value="135">135</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="liquidity_plasticity_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="liquidity_plasticity_test_status_1" value="pass" id="liquidity_plasticity_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="liquidity_plasticity_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="liquidity_plasticity_test_status_1" value="fail" id="liquidity_plasticity_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="liquidity_plasticity_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="liquidity_plasticity_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- اختبار البروكتور -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-weight-hanging me-2"></i>اختبار البروكتور (علاقة الرطوبة المثلي و اقصي كثافة جافة)
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_proctor_test" value="1" id="proctor_test_yes" data-test="proctor_test" onchange="toggleTestCalculation('proctor_test')">
                                                <label class="form-check-label" for="proctor_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_proctor_test" value="0" id="proctor_test_no" data-test="proctor_test" checked onchange="toggleTestCalculation('proctor_test')">
                                                <label class="form-check-label" for="proctor_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation proctor_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('proctor_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('proctor_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="proctor_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('proctor_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="proctor_test_input_1" step="0.01" onchange="calculateTestRow('proctor_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="proctor_test_factor_1" onchange="calculateTestRow('proctor_test', 1)">
                                                                <option value="155">155</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="proctor_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="proctor_test_status_1" value="pass" id="proctor_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="proctor_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="proctor_test_status_1" value="fail" id="proctor_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="proctor_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="proctor_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- اختبار استواء طبقة الاسفلت -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-road me-2"></i>اختبار استواء طبقة الاسفلت (تطاير، نزف، هبوط، تشققات، لحامات، خشونة ونعومة)
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_asphalt_layer_evenness_test" value="1" id="asphalt_layer_evenness_test_yes" data-test="asphalt_layer_evenness_test" onchange="toggleTestCalculation('asphalt_layer_evenness_test')">
                                                <label class="form-check-label" for="asphalt_layer_evenness_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_asphalt_layer_evenness_test" value="0" id="asphalt_layer_evenness_test_no" data-test="asphalt_layer_evenness_test" checked onchange="toggleTestCalculation('asphalt_layer_evenness_test')">
                                                <label class="form-check-label" for="asphalt_layer_evenness_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation asphalt_layer_evenness_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('asphalt_layer_evenness_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('asphalt_layer_evenness_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="asphalt_layer_evenness_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('asphalt_layer_evenness_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="asphalt_layer_evenness_test_input_1" step="0.01" onchange="calculateTestRow('asphalt_layer_evenness_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="asphalt_layer_evenness_test_factor_1" onchange="calculateTestRow('asphalt_layer_evenness_test', 1)">
                                                                <option value="68">68</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="asphalt_layer_evenness_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="asphalt_layer_evenness_test_status_1" value="pass" id="asphalt_layer_evenness_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="asphalt_layer_evenness_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="asphalt_layer_evenness_test_status_1" value="fail" id="asphalt_layer_evenness_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="asphalt_layer_evenness_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="asphalt_layer_evenness_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- اختبار قياس قوة دمك الاسفلت بالجهاز الذري -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-hammer me-2"></i>اختبار قياس قوة دمك الاسفلت بالجهاز الذري
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_asphalt_compaction_atomic_test" value="1" id="asphalt_compaction_atomic_test_yes" data-test="asphalt_compaction_atomic_test" onchange="toggleTestCalculation('asphalt_compaction_atomic_test')">
                                                <label class="form-check-label" for="asphalt_compaction_atomic_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_asphalt_compaction_atomic_test" value="0" id="asphalt_compaction_atomic_test_no" data-test="asphalt_compaction_atomic_test" checked onchange="toggleTestCalculation('asphalt_compaction_atomic_test')">
                                                <label class="form-check-label" for="asphalt_compaction_atomic_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation asphalt_compaction_atomic_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('asphalt_compaction_atomic_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('asphalt_compaction_atomic_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="asphalt_compaction_atomic_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('asphalt_compaction_atomic_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="asphalt_compaction_atomic_test_input_1" step="0.01" onchange="calculateTestRow('asphalt_compaction_atomic_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="asphalt_compaction_atomic_test_factor_1" onchange="calculateTestRow('asphalt_compaction_atomic_test', 1)">
                                                                <option value="95">95</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="asphalt_compaction_atomic_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="asphalt_compaction_atomic_test_status_1" value="pass" id="asphalt_compaction_atomic_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="asphalt_compaction_atomic_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="asphalt_compaction_atomic_test_status_1" value="fail" id="asphalt_compaction_atomic_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="asphalt_compaction_atomic_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="asphalt_compaction_atomic_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- اختبار تعيين نسبة البترومين بالخلطة الاسفلتية -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-oil-can me-2"></i>اختبار تعيين نسبة البترومين بالخلطة الاسفلتية
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_bitumen_ratio_test" value="1" id="bitumen_ratio_test_yes" data-test="bitumen_ratio_test" onchange="toggleTestCalculation('bitumen_ratio_test')">
                                                <label class="form-check-label" for="bitumen_ratio_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_bitumen_ratio_test" value="0" id="bitumen_ratio_test_no" data-test="bitumen_ratio_test" checked onchange="toggleTestCalculation('bitumen_ratio_test')">
                                                <label class="form-check-label" for="bitumen_ratio_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation bitumen_ratio_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('bitumen_ratio_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('bitumen_ratio_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="bitumen_ratio_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('bitumen_ratio_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="bitumen_ratio_test_input_1" step="0.01" onchange="calculateTestRow('bitumen_ratio_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="bitumen_ratio_test_factor_1" onchange="calculateTestRow('bitumen_ratio_test', 1)">
                                                                <option value="195">195</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="bitumen_ratio_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="bitumen_ratio_test_status_1" value="pass" id="bitumen_ratio_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="bitumen_ratio_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="bitumen_ratio_test_status_1" value="fail" id="bitumen_ratio_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="bitumen_ratio_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="bitumen_ratio_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- اختبار التدرج الحبيبي للاسفلت -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-sort-amount-down me-2"></i>اختبار التدرج الحبيبي للاسفلت
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_asphalt_gradation_test" value="1" id="asphalt_gradation_test_yes" data-test="asphalt_gradation_test" onchange="toggleTestCalculation('asphalt_gradation_test')">
                                                <label class="form-check-label" for="asphalt_gradation_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_asphalt_gradation_test" value="0" id="asphalt_gradation_test_no" data-test="asphalt_gradation_test" checked onchange="toggleTestCalculation('asphalt_gradation_test')">
                                                <label class="form-check-label" for="asphalt_gradation_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation asphalt_gradation_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('asphalt_gradation_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('asphalt_gradation_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="asphalt_gradation_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('asphalt_gradation_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="asphalt_gradation_test_input_1" step="0.01" onchange="calculateTestRow('asphalt_gradation_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="asphalt_gradation_test_factor_1" onchange="calculateTestRow('asphalt_gradation_test', 1)">
                                                                <option value="145">145</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="asphalt_gradation_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="asphalt_gradation_test_status_1" value="pass" id="asphalt_gradation_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="asphalt_gradation_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="asphalt_gradation_test_status_1" value="fail" id="asphalt_gradation_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="asphalt_gradation_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="asphalt_gradation_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- اختبار الوزن النوعي لخليط الاسفلت GMM -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-weight me-2"></i>اختبار الوزن النوعي لخليط الاسفلت GMM
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_asphalt_gmm_test" value="1" id="asphalt_gmm_test_yes" data-test="asphalt_gmm_test" onchange="toggleTestCalculation('asphalt_gmm_test')">
                                                <label class="form-check-label" for="asphalt_gmm_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_asphalt_gmm_test" value="0" id="asphalt_gmm_test_no" data-test="asphalt_gmm_test" checked onchange="toggleTestCalculation('asphalt_gmm_test')">
                                                <label class="form-check-label" for="asphalt_gmm_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation asphalt_gmm_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('asphalt_gmm_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('asphalt_gmm_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="asphalt_gmm_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('asphalt_gmm_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="asphalt_gmm_test_input_1" step="0.01" onchange="calculateTestRow('asphalt_gmm_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="asphalt_gmm_test_factor_1" onchange="calculateTestRow('asphalt_gmm_test', 1)">
                                                                <option value="175">175</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="asphalt_gmm_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="asphalt_gmm_test_status_1" value="pass" id="asphalt_gmm_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="asphalt_gmm_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="asphalt_gmm_test_status_1" value="fail" id="asphalt_gmm_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="asphalt_gmm_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="asphalt_gmm_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- اختبار كثافة مارشال -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-weight-hanging me-2"></i>اختبار كثافة مارشال
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_marshall_density_test" value="1" id="marshall_density_test_yes" data-test="marshall_density_test" onchange="toggleTestCalculation('marshall_density_test')">
                                                <label class="form-check-label" for="marshall_density_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_marshall_density_test" value="0" id="marshall_density_test_no" data-test="marshall_density_test" checked onchange="toggleTestCalculation('marshall_density_test')">
                                                <label class="form-check-label" for="marshall_density_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation marshall_density_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('marshall_density_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('marshall_density_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="marshall_density_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('marshall_density_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="marshall_density_test_input_1" step="0.01" onchange="calculateTestRow('marshall_density_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="marshall_density_test_factor_1" onchange="calculateTestRow('marshall_density_test', 1)">
                                                                <option value="85">85</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="marshall_density_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="marshall_density_test_status_1" value="pass" id="marshall_density_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="marshall_density_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="marshall_density_test_status_1" value="fail" id="marshall_density_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="marshall_density_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="marshall_density_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- اختبار تحديد نسبة الحصمة الاجمالية بالخلطة التدقق -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-percentage me-2"></i>اختبار تحديد نسبة الحصمة الاجمالية بالخلطة التدقق
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_aggregate_ratio_test" value="1" id="aggregate_ratio_test_yes" data-test="aggregate_ratio_test" onchange="toggleTestCalculation('aggregate_ratio_test')">
                                                <label class="form-check-label" for="aggregate_ratio_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_aggregate_ratio_test" value="0" id="aggregate_ratio_test_no" data-test="aggregate_ratio_test" checked onchange="toggleTestCalculation('aggregate_ratio_test')">
                                                <label class="form-check-label" for="aggregate_ratio_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation aggregate_ratio_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('aggregate_ratio_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('aggregate_ratio_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="aggregate_ratio_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('aggregate_ratio_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="aggregate_ratio_test_input_1" step="0.01" onchange="calculateTestRow('aggregate_ratio_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="aggregate_ratio_test_factor_1" onchange="calculateTestRow('aggregate_ratio_test', 1)">
                                                                <option value="115">115</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="aggregate_ratio_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="aggregate_ratio_test_status_1" value="pass" id="aggregate_ratio_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="aggregate_ratio_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="aggregate_ratio_test_status_1" value="fail" id="aggregate_ratio_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="aggregate_ratio_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="aggregate_ratio_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- اختبار تحديد النقص في درجة الثبات -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-chart-line-down me-2"></i>اختبار تحديد النقص في درجة الثبات
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_stability_deficit_test" value="1" id="stability_deficit_test_yes" data-test="stability_deficit_test" onchange="toggleTestCalculation('stability_deficit_test')">
                                                <label class="form-check-label" for="stability_deficit_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_stability_deficit_test" value="0" id="stability_deficit_test_no" data-test="stability_deficit_test" checked onchange="toggleTestCalculation('stability_deficit_test')">
                                                <label class="form-check-label" for="stability_deficit_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation stability_deficit_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('stability_deficit_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('stability_deficit_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="stability_deficit_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('stability_deficit_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="stability_deficit_test_input_1" step="0.01" onchange="calculateTestRow('stability_deficit_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="stability_deficit_test_factor_1" onchange="calculateTestRow('stability_deficit_test', 1)">
                                                                <option value="65">65</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="stability_deficit_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="stability_deficit_test_status_1" value="pass" id="stability_deficit_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="stability_deficit_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="stability_deficit_test_status_1" value="fail" id="stability_deficit_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="stability_deficit_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="stability_deficit_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- اختبار قياس درجة الثبات -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-chart-bar me-2"></i>اختبار قياس درجة الثبات
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_stability_degree_test" value="1" id="stability_degree_test_yes" data-test="stability_degree_test" onchange="toggleTestCalculation('stability_degree_test')">
                                                <label class="form-check-label" for="stability_degree_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_stability_degree_test" value="0" id="stability_degree_test_no" data-test="stability_degree_test" checked onchange="toggleTestCalculation('stability_degree_test')">
                                                <label class="form-check-label" for="stability_degree_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation stability_degree_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('stability_degree_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('stability_degree_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="stability_degree_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('stability_degree_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="stability_degree_test_input_1" step="0.01" onchange="calculateTestRow('stability_degree_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="stability_degree_test_factor_1" onchange="calculateTestRow('stability_degree_test', 1)">
                                                                <option value="75">75</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="stability_degree_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="stability_degree_test_status_1" value="pass" id="stability_degree_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="stability_degree_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="stability_degree_test_status_1" value="fail" id="stability_degree_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="stability_degree_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="stability_degree_test_file_path">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- اختبار احتياطي -->
                            <div class="col-md-6">
                                <div class="card test-card">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-plus-circle me-2"></i>اختبار احتياطي (إضافي)
                                        </label>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_backup_test" value="1" id="backup_test_yes" data-test="backup_test" onchange="toggleTestCalculation('backup_test')">
                                                <label class="form-check-label" for="backup_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input test-radio" type="radio" name="has_backup_test" value="0" id="backup_test_no" data-test="backup_test" checked onchange="toggleTestCalculation('backup_test')">
                                                <label class="form-check-label" for="backup_test_no">لا</label>
                                            </div>
                                        </div>
                                        <div class="test-calculation backup_test-calculation" style="display: none;">
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addTestRow('backup_test')">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة جديدة
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllTestRows('backup_test')">
                                                    <i class="fas fa-eraser me-1"></i>مسح جميع القيم
                                                </button>
                                            </div>
                                            <div id="backup_test_rows_container">
                                                <div class="test-row border rounded p-3 mb-3" data-row="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-info">القيمة #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('backup_test', 1)" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النقاط</label>
                                                            <input type="number" class="form-control form-control-sm" name="backup_test_input_1" step="0.01" onchange="calculateTestRow('backup_test', 1)" placeholder="أدخل عدد النقاط">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">السعر</label>
                                                            <select class="form-select form-select-sm" name="backup_test_factor_1" onchange="calculateTestRow('backup_test', 1)">
                                                                <option value="50">50</option>
                                                                <option value="75">75</option>
                                                                <option value="100">100</option>
                                                                <option value="125">125</option>
                                                                <option value="150">150</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">النتيجة</label>
                                                            <input type="number" class="form-control form-control-sm bg-light" name="backup_test_result_1" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">الحالة</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="backup_test_status_1" value="pass" id="backup_test_pass_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-success" for="backup_test_pass_1">
                                                                    <i class="fas fa-check"></i> ناجح
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="backup_test_status_1" value="fail" id="backup_test_fail_1" onchange="updateTestTotals()">
                                                                    <label class="form-check-label text-danger" for="backup_test_fail_1">
                                                                    <i class="fas fa-check"></i> راسب
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">نوع الاختبار الاحتياطي</label>
                                                <input type="text" class="form-control form-control-sm" name="backup_test_type" placeholder="أدخل نوع الاختبار الاحتياطي">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">مرفق الاختبار</label>
                                                <input type="file" class="form-control form-control-sm" name="backup_test_file_path">
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
                                <button type="button" id="save-lab-btn" class="btn btn-primary-custom btn-lg" onclick="saveLabSection()">
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
                    <!-- اختيار الرخصة للإخلاءات -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0">
                                        <i class="fas fa-clipboard-list me-2"></i>
                                        اختيار الرخصة للعمل عليها
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-search me-2"></i>اختر الرخصة:
                                    </label>
                                    <select class="form-select" id="evacuation-license-selector" onchange="selectEvacuationLicense()">
                                        <option value="">-- اختر الرخصة للعمل عليها --</option>
                                    </select>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            يجب اختيار رخصة قبل إدخال بيانات الإخلاءات
                                        </small>
                                    </div>
                                    <div id="selected-evacuation-license-info" class="mt-2" style="display: none;">
                                        <div class="alert alert-warning mb-0">
                                            <i class="fas fa-certificate me-2"></i>
                                            الرخصة المختارة: <strong id="evacuation-license-display"></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form id="evacuationForm">
                        @csrf
                        <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                        <input type="hidden" name="license_id" id="evacuation-license-id" value="">
                        
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
                                <label class="form-label fw-bold">تاريخ ووقت الإخلاء</label>
                                <input type="datetime-local" class="form-control" name="evacuation_start_date">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">رقم سداد الإخلاء</label>
                                <input type="text" class="form-control" name="evac_payment_number" placeholder="رقم سداد الإخلاء">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">ملاحظات الإخلاء</label>
                                <textarea class="form-control" name="evac_notes" rows="3" placeholder="أدخل الملاحظات هنا..."></textarea>
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
                    <!-- اختيار الرخصة للمخالفات -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-clipboard-list me-2"></i>
                                        اختيار الرخصة للعمل عليها
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-search me-2"></i>اختر الرخصة:
                                    </label>
                                    <select class="form-select" id="violation-license-selector" onchange="selectViolationLicense()">
                                        <option value="">-- اختر الرخصة للعمل عليها --</option>
                                    </select>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            يجب اختيار رخصة قبل إدخال بيانات المخالفات
                                        </small>
                                    </div>
                                    <div id="selected-violation-license-info" class="mt-2" style="display: none;">
                                        <div class="alert alert-danger mb-0">
                                            <i class="fas fa-certificate me-2"></i>
                                            الرخصة المختارة: <strong id="violation-license-display"></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form id="violationForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                        <input type="hidden" name="license_id" id="violation-license-id" value="">
                        
                        <!-- معلومات المخالفة -->
                        <div class="card border-danger mb-4">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    تسجيل مخالفة جديدة
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- معلومات المخالفة الأساسية -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">رقم المخالفة</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                            <input type="text" class="form-control" name="violation_number" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">تصنيف المخالفة</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                            <input type="text" class="form-control" name="violation_type" required placeholder="أدخل تصنيف المخالفة">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">تاريخ رصد المخالفة</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            <input type="date" class="form-control" name="violation_date" value="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">تاريخ الاستحقاق</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            <input type="date" class="form-control" name="payment_due_date" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- تفاصيل المخالفة -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">وصف المخالفة</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                                            <textarea class="form-control" name="violation_description" rows="3" required placeholder="اكتب وصفاً تفصيلياً للمخالفة"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">المتسبب</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" class="form-control" name="responsible_party" required placeholder="اسم الشخص أو الجهة المتسببة">
                                        </div>
                                    </div>
                                </div>

                                <!-- معلومات السداد -->
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">قيمة المخالفة</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-money-bill"></i></span>
                                            <input type="number" class="form-control" name="violation_amount" step="0.01" required>
                                            <span class="input-group-text">ريال</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">رقم فاتورة السداد</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-receipt"></i></span>
                                            <input type="text" class="form-control" name="payment_invoice_number" placeholder="في حالة السداد">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">حالة السداد</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                            <select class="form-select" name="payment_status">
                                                <option value="0">في انتظار السداد</option>
                                                <option value="1">تم السداد</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <button type="button" class="btn btn-danger" onclick="saveViolationSection()">
                                    <i class="fas fa-save me-2"></i>
                                    حفظ المخالفة
                                </button>
                            </div>
                        </div>

                        <!-- جدول المخالفات -->
                        <div class="card">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">
                                    <i class="fas fa-list me-2"></i>
                                    سجل المخالفات
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-warning">
                                            <tr>
                                                <th>#</th>
                                                <th>رقم المخالفة</th>
                                                <th>تاريخ رصد المخالفة</th>
                                                <th>تصنيف المخالفة</th>
                                                <th>المتسبب</th>
                                                <th>وصف المخالفة </th>
                                                <th> قيمة المخالفة</th>
                                                <th>تاريخ الاستحقاق</th>
                                                <th>رقم فاتورة السداد</th>
                                                <th>حالة الدفع</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody id="violations-table-body">
                                            <tr>
                                                <td colspan="11" class="text-center">لا توجد مخالفات</td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="table-info">
                                            <tr>
                                                <td colspan="6" class="text-start">
                                                    <strong>إجمالي قيمة المخالفات:</strong>
                                                </td>
                                                <td class="text-start" id="total-violations-amount">
                                                    <strong>0.00 ريال</strong>
                                                </td>
                                                <td colspan="4"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
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
// تم نقل دالة saveCoordinationSection إلى أعلى الملف

function saveDigLicenseSection() {
    // منع الإرسال العادي للنموذج
    event.preventDefault();
    
    const form = document.getElementById('digLicenseForm');
    if (!form) {
        toastr.error('لم يتم العثور على نموذج رخصة الحفر');
        return false;
    }

    const formData = new FormData(form);
    
    // إضافة معلومات القسم
    formData.set('section_type', 'dig_license');
    
    // إظهار loading على الزر
    const saveBtn = event.target;
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';
    
    $.ajax({
        url: '{{ route("admin.licenses.save-section") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            toastr.success('تم حفظ رخصة الحفر بنجاح');
            
            // إعادة تعيين النموذج
            form.reset();
            
            // تحديث جدول رخص الحفر
            loadDigLicenses();
            
            // إعادة تفعيل الزر
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        },
        error: function(xhr) {
            console.error('Error saving dig license:', xhr);
            
            // إعادة تفعيل الزر
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
            
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                Object.values(errors).forEach(error => {
                    toastr.error(error[0]);
                });
            } else {
                const message = xhr.responseJSON?.message || 'حدث خطأ في حفظ رخصة الحفر';
                toastr.error(message);
            }
        }
    });
    
    return false;
}



    function saveEvacuationSection() {
        // التحقق من اختيار الرخصة
        const licenseId = document.getElementById('evacuation-license-id').value;
        const licenseSelector = document.getElementById('evacuation-license-selector');
        
        if (!licenseId) {
            toastr.warning('يجب اختيار رخصة قبل حفظ بيانات الإخلاءات');
            return;
        }

        // الحصول على اسم الرخصة المختارة
        const selectedLicenseName = licenseSelector.options[licenseSelector.selectedIndex].text;
        
        // إظهار رسالة تأكيد
        if (!confirm(`هل أنت متأكد من حفظ بيانات الإخلاءات في ${selectedLicenseName}؟`)) {
            return;
        }
        
        const formData = new FormData(document.getElementById('evacuationForm'));
        
        // إضافة معلومات القسم والرخصة
        formData.set('section_type', 'evacuations');
        formData.set('license_id', licenseId);
        
        $.ajax({
            url: '{{ route("admin.licenses.save-section") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                toastr.success(`تم حفظ بيانات الإخلاءات بنجاح في ${selectedLicenseName}`);
            },
            error: function(xhr) {
                console.error('Error saving evacuation:', xhr);
                const errors = xhr.responseJSON?.errors || {};
                if (Object.keys(errors).length > 0) {
                    Object.values(errors).forEach(error => {
                        toastr.error(error[0]);
                    });
                } else {
                    toastr.error('حدث خطأ في حفظ بيانات الإخلاءات');
                }
            }
        });
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

// ==================== دوال النظام التفاعلي للمختبر مع التكرار ====================

// متغير لتتبع عدد الصفوف لكل اختبار
let testRowCounters = {
    'depth_test': 1,
    'soil_compaction_test': 1,
    'rc1_mc1_test': 1,
    'asphalt_test': 1,
    'soil_test': 1,
    'interlock_test': 1
};

// دالة لإظهار/إخفاء حقول الحساب عند تفعيل الاختبار
function toggleTestCalculation(testName) {
    const calculationDiv = document.querySelector(`.${testName}-calculation`);
    const testRadio = document.querySelector(`input[name="has_${testName}"][value="1"]`);
    
    if (calculationDiv) {
        calculationDiv.style.display = testRadio.checked ? 'block' : 'none';
        
        // إذا تم إلغاء تفعيل الاختبار، نظف القيم وأعد حساب الإجماليات
        if (!testRadio.checked) {
            clearTestRows(testName);
            updateTestTotals();
        }
    }
}

// دالة لإضافة صف جديد لاختبار معين
function addTestRow(testName) {
    testRowCounters[testName]++;
    const rowNumber = testRowCounters[testName];
    const container = document.getElementById(`${testName}_rows_container`);
    
    // الحصول على جميع خيارات المعامل من الصف الأول
    const firstFactorSelect = container.querySelector(`select[name="${testName}_factor_1"]`);
    let factorOptions = '';
    
    if (firstFactorSelect) {
        Array.from(firstFactorSelect.options).forEach(option => {
            factorOptions += `<option value="${option.value}">${option.text}</option>`;
        });
    } else {
        factorOptions = '<option value="1">1</option>';
    }
    
    const newRow = document.createElement('div');
    newRow.className = 'test-row border rounded p-3 mb-3';
    newRow.setAttribute('data-row', rowNumber);
    
    newRow.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="badge bg-info">القيمة #${rowNumber}</span>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="copyFromFirstRow('${testName}', ${rowNumber})" title="نسخ من الصف الأول">
                    <i class="fas fa-copy"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeTestRow('${testName}', ${rowNumber})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <div class="row g-2 mb-3">
            <div class="col-md-3">
                <label class="form-label small"> النقاط</label>
                <input type="number" class="form-control form-control-sm" name="${testName}_input_${rowNumber}" step="0.01" onchange="calculateTestRow('${testName}', ${rowNumber})" placeholder="أدخل عدد النقاط">
            </div>
            <div class="col-md-3">
                <label class="form-label small">السعر</label>
                <select class="form-select form-select-sm" name="${testName}_factor_${rowNumber}" onchange="calculateTestRow('${testName}', ${rowNumber})">
                    ${factorOptions}
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">النتيجة</label>
                <input type="number" class="form-control form-control-sm bg-light" name="${testName}_result_${rowNumber}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label small">الحالة</label>
                <div class="d-flex gap-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="${testName}_status_${rowNumber}" value="pass" id="${testName}_pass_${rowNumber}" onchange="updateTestTotals()">
                        <label class="form-check-label text-success" for="${testName}_pass_${rowNumber}" title="ناجح">
                            <i class="fas fa-check"></i>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="${testName}_status_${rowNumber}" value="fail" id="${testName}_fail_${rowNumber}" onchange="updateTestTotals()">
                        <label class="form-check-label text-danger" for="${testName}_fail_${rowNumber}" title="راسب">
                            <i class="fas fa-times"></i>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(newRow);
    
    // إظهار أزرار الحذف إذا كان هناك أكثر من صف واحد
    updateDeleteButtons(testName);
    
    // إضافة تأثير بصري للصف الجديد
    newRow.style.opacity = '0';
    newRow.style.transform = 'translateY(20px)';
    setTimeout(() => {
        newRow.style.transition = 'all 0.5s ease';
        newRow.style.opacity = '1';
        newRow.style.transform = 'translateY(0)';
    }, 100);
}

// دالة لنسخ القيم من الصف الأول
function copyFromFirstRow(testName, targetRowNumber) {
    const container = document.getElementById(`${testName}_rows_container`);
    
    // الحصول على قيم الصف الأول
    const firstInput = container.querySelector(`input[name="${testName}_input_1"]`);
    const firstFactor = container.querySelector(`select[name="${testName}_factor_1"]`);
    const firstStatus = container.querySelector(`input[name="${testName}_status_1"]:checked`);
    
    // تطبيق القيم على الصف المستهدف
    const targetInput = container.querySelector(`input[name="${testName}_input_${targetRowNumber}"]`);
    const targetFactor = container.querySelector(`select[name="${testName}_factor_${targetRowNumber}"]`);
    
    if (firstInput && targetInput) {
        targetInput.value = firstInput.value;
    }
    
    if (firstFactor && targetFactor) {
        targetFactor.value = firstFactor.value;
    }
    
    // نسخ حالة النجاح/الرسوب
    if (firstStatus) {
        const targetStatus = container.querySelector(`input[name="${testName}_status_${targetRowNumber}"][value="${firstStatus.value}"]`);
        if (targetStatus) {
            targetStatus.checked = true;
        }
    }
    
    // حساب النتيجة للصف الجديد
    calculateTestRow(testName, targetRowNumber);
    
    // إشعار المستخدم
    toastr.success('تم نسخ القيم من الصف الأول بنجاح');
}

// دالة لحذف صف اختبار
function removeTestRow(testName, rowNumber) {
    const container = document.getElementById(`${testName}_rows_container`);
    const rowToRemove = container.querySelector(`[data-row="${rowNumber}"]`);
    
    if (rowToRemove) {
        rowToRemove.remove();
        updateTestTotals();
        updateDeleteButtons(testName);
    }
}

// دالة لتحديث ظهور أزرار الحذف
function updateDeleteButtons(testName) {
    const container = document.getElementById(`${testName}_rows_container`);
    const rows = container.querySelectorAll('.test-row');
    
    rows.forEach((row, index) => {
        const deleteButton = row.querySelector('.btn-danger');
        if (deleteButton) {
            deleteButton.style.display = rows.length > 1 ? 'inline-block' : 'none';
        }
    });
}

// دالة لحساب نتيجة صف اختبار معين
function calculateTestRow(testName, rowNumber) {
    const inputField = document.querySelector(`input[name="${testName}_input_${rowNumber}"]`);
    const factorField = document.querySelector(`select[name="${testName}_factor_${rowNumber}"]`);
    const resultField = document.querySelector(`input[name="${testName}_result_${rowNumber}"]`);
    
    if (inputField && factorField && resultField) {
        const inputValue = parseFloat(inputField.value) || 0;
        const factorValue = parseFloat(factorField.value) || 1;
        const result = inputValue * factorValue;
        
        resultField.value = result.toFixed(2);
        
        // تحديث الإجماليات
        updateTestTotals();
    }
}

// دالة لتنظيف جميع صفوف اختبار معين
function clearTestRows(testName) {
    const container = document.getElementById(`${testName}_rows_container`);
    const rows = container.querySelectorAll('.test-row');
    
    rows.forEach(row => {
        const inputs = row.querySelectorAll('input');
        const radios = row.querySelectorAll('input[type="radio"]');
        
        inputs.forEach(input => {
            if (input.type !== 'radio') {
                input.value = '';
            }
        });
        
        radios.forEach(radio => {
            radio.checked = false;
        });
    });
}

// دالة لمسح جميع القيم مع تأكيد المستخدم
function clearAllTestRows(testName) {
    if (confirm('هل أنت متأكد من مسح جميع القيم لهذا الاختبار؟')) {
        clearTestRows(testName);
        updateTestTotals();
        
        // إضافة تأثير بصري
        const container = document.getElementById(`${testName}_rows_container`);
        const rows = container.querySelectorAll('.test-row');
        
        rows.forEach((row, index) => {
            setTimeout(() => {
                row.style.transition = 'all 0.3s ease';
                row.style.transform = 'scale(0.95)';
                row.style.opacity = '0.7';
                
                setTimeout(() => {
                    row.style.transform = 'scale(1)';
                    row.style.opacity = '1';
                }, 200);
            }, index * 100);
        });
        
        toastr.success('تم مسح جميع القيم بنجاح');
    }
}

// دالة لتحديث الإجماليات مع دعم التكرار
function updateTestTotals() {
    const testNames = [
        'depth_test', 'soil_compaction_test', 'rc1_mc1_test', 'asphalt_test', 'soil_test', 'interlock_test',
        'max_dry_density_pro_test', 'asphalt_ratio_gradation_test', 'marshall_test', 'concrete_molds_test',
        'excavation_bottom_test', 'protection_depth_test', 'settlement_test', 'concrete_temperature_test',
        'field_density_atomic_test', 'moisture_content_test', 'soil_layer_flatness_test', 'concrete_sample_test',
        'asphalt_spray_rate_test', 'asphalt_temperature_test', 'concrete_cylinder_compression_test',
        'soil_particle_analysis_test', 'liquid_plastic_limit_test', 'proctor_test', 'asphalt_layer_flatness_test',
        'asphalt_compaction_atomic_test', 'bitumen_ratio_test', 'asphalt_gradation_test', 'asphalt_mix_gmm_test',
        'marshall_density_test', 'aggregate_ratio_test', 'stability_deficiency_test', 'stability_degree_test',
        'backup_test'
    ];
    
    let totalSum = 0;
    let passedSum = 0;
    let failedSum = 0;
    let activeTestsCount = 0;
    let passedRowsCount = 0;
    let failedRowsCount = 0;
    let totalRowsCount = 0;
    let failureReasons = [];
    
    testNames.forEach(testName => {
        const testRadio = document.querySelector(`input[name="has_${testName}"][value="1"]`);
        
        // تحقق من أن الاختبار مفعل
        if (testRadio && testRadio.checked) {
            activeTestsCount++;
            
            // البحث عن جميع صفوف هذا الاختبار
            const container = document.getElementById(`${testName}_rows_container`);
            const rows = container.querySelectorAll('.test-row');
            
            rows.forEach(row => {
                const rowNumber = row.getAttribute('data-row');
                const resultField = row.querySelector(`input[name="${testName}_result_${rowNumber}"]`);
                const passRadio = row.querySelector(`input[name="${testName}_status_${rowNumber}"][value="pass"]`);
                const failRadio = row.querySelector(`input[name="${testName}_status_${rowNumber}"][value="fail"]`);
                
                const result = parseFloat(resultField?.value) || 0;
                
                // إضافة النتيجة للإجمالي إذا كانت موجودة
                if (result > 0) {
                    totalSum += result;
                    totalRowsCount++;
                    
                    if (passRadio && passRadio.checked) {
                        passedSum += result;
                        passedRowsCount++;
                    } else if (failRadio && failRadio.checked) {
                        failedSum += result;
                        failedRowsCount++;
                        
                        // إضافة سبب الرسوب
                        const testLabel = getTestLabel(testName);
                        failureReasons.push(`${testLabel} (القيمة #${rowNumber}): نتيجة ${result.toFixed(2)}`);
                    }
                }
            });
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
        
        // تسجيل القيم للتحقق
        console.log('Test Totals Updated:', {
            passedSum: passedSum.toFixed(2),
            failedSum: failedSum.toFixed(2),
            failureReasons: failureReasons
        });
    
    // حساب نسبة النجاح بناءً على الصفوف المكتملة
    let successPercentage = 0;
    if (totalRowsCount > 0) {
        successPercentage = (passedRowsCount / totalRowsCount) * 100;
    }
    document.getElementById('success_percentage').textContent = successPercentage.toFixed(1) + '%';
    
    // تحديث الحالة العامة
    updateOverallLabStatus(activeTestsCount, passedRowsCount, failedRowsCount, totalRowsCount);
    
    // تحديث أسباب الرسوب
    const failureReasonsTextarea = document.getElementById('test_failure_reasons');
    if (failureReasons.length > 0) {
        failureReasonsTextarea.value = 'أسباب الرسوب:\n' + failureReasons.join('\n');
    } else if (totalRowsCount > 0 && failedRowsCount === 0) {
        failureReasonsTextarea.value = 'جميع القيم ناجحة - لا توجد أسباب رسوب';
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
        'interlock_test': 'اختبار بلاط وانترلوك',
        'max_dry_density_pro_test': 'اختبار الكثافة الجافة القصوى (طريقة برو)',
        'asphalt_ratio_gradation_test': 'اختبار تعيين نسبة الاسفلت والتدرج الحبيبي',
        'marshall_test': 'اختبار تجربة مارشال',
        'concrete_molds_test': 'اختبار اعداد القوالب الخرسانية وصبها مع الكسر',
        'excavation_bottom_test': 'اختبار قاع الحفر',
        'protection_depth_test': 'اختبار تحديد الاعماق لمواد الحماية',
        'settlement_test': 'اختبار الهبوط',
        'concrete_temperature_test': 'اختبار درجة حرارة الخرسانة',
        'field_density_atomic_test': 'اختبار الكثافة الحقلية باستخدام الجهاز الذري',
        'moisture_content_test': 'اختبار محتوي الرطوبة',
        'soil_layer_flatness_test': 'اختبار استواء طبقة التربة',
        'concrete_sample_test': 'اختبار اخذ عينة من الخرسانة بالموقع',
        'asphalt_spray_rate_test': 'اختبار معدل الرش الاسفلتي MC1/RC2',
        'asphalt_temperature_test': 'اختبار قياس درجة حرارة الاسفلت',
        'concrete_cylinder_compression_test': 'اختبار تعيين مقاومة الضغط لخرسانة الاسطوانات',
        'soil_particle_analysis_test': 'اختبار التحليل الحبيبي للتربة',
        'liquid_plastic_limit_test': 'اختبار تحديد حد السيولة واللدونة ومؤشر اللدونة',
        'proctor_test': 'اختبار البروكتور',
        'asphalt_layer_flatness_test': 'اختبار استواء طبقة الاسفلت',
        'asphalt_compaction_atomic_test': 'اختبار قياس قوة دمك الاسفلت بالجهاز الذري',
        'bitumen_ratio_test': 'اختبار تعيين نسبة البترومين بالخلطة الاسفلتية',
        'asphalt_gradation_test': 'اختبار التدرج الحبيبي للاسفلت',
        'asphalt_mix_gmm_test': 'اختبار الوزن النوعي لخليط الاسفلت GMM',
        'marshall_density_test': 'اختبار كثافة مارشال',
        'aggregate_ratio_test': 'اختبار تحديد نسبة الحصمة الاجمالية بالخلطة',
        'stability_deficiency_test': 'اختبار تحديد النقص في درجة الثبات',
        'stability_degree_test': 'اختبار قياس درجة الثبات',
        'backup_test': 'اختبار احتياطي (إضافي)'
    };
    return labels[testName] || testName;
}

// دالة لتحديث الحالة العامة للمختبر
function updateOverallLabStatus(activeTests, passedRows, failedRows, totalRows) {
    const statusElement = document.getElementById('overall_lab_status');
    
    if (activeTests === 0) {
        statusElement.textContent = 'لم يتم تفعيل أي اختبارات';
        statusElement.className = 'badge fs-6 p-2 bg-secondary';
    } else if (totalRows === 0) {
        statusElement.textContent = 'لا توجد قيم مدخلة';
        statusElement.className = 'badge fs-6 p-2 bg-secondary';
    } else if (failedRows === 0 && passedRows > 0) {
        statusElement.textContent = `جميع القيم ناجحة (${passedRows} قيمة)`;
        statusElement.className = 'badge fs-6 p-2 bg-success';
    } else if (failedRows > 0 && passedRows > 0) {
        statusElement.textContent = `نجاح جزئي - ${passedRows} ناجح، ${failedRows} راسب`;
        statusElement.className = 'badge fs-6 p-2 bg-warning';
    } else if (failedRows > 0 && passedRows === 0) {
        statusElement.textContent = `جميع القيم راسبة (${failedRows} قيمة)`;
        statusElement.className = 'badge fs-6 p-2 bg-danger';
    } else {
        statusElement.textContent = 'قيد التقييم';
        statusElement.className = 'badge fs-6 p-2 bg-info';
    }
}

// دالة إظهار وإخفاء حقول التمديد
function toggleExtensionFields() {
    const extensionFields = document.getElementById('extensionFields');
    const toggleBtn = document.getElementById('extensionToggleBtn');
    
    if (extensionFields.style.display === 'none' || extensionFields.style.display === '') {
        extensionFields.style.display = 'block';
        toggleBtn.innerHTML = '<i class="fas fa-calendar-minus me-2"></i>إخفاء التمديد';
        toggleBtn.classList.remove('btn-outline-warning');
        toggleBtn.classList.add('btn-warning');
        
        // تأثير انيميشن
        extensionFields.style.opacity = '0';
        extensionFields.style.transform = 'translateY(-20px)';
        setTimeout(() => {
            extensionFields.style.transition = 'all 0.3s ease';
            extensionFields.style.opacity = '1';
            extensionFields.style.transform = 'translateY(0)';
        }, 50);
    } else {
        extensionFields.style.transition = 'all 0.3s ease';
        extensionFields.style.opacity = '0';
        extensionFields.style.transform = 'translateY(-20px)';
        
        setTimeout(() => {
            extensionFields.style.display = 'none';
            toggleBtn.innerHTML = '<i class="fas fa-calendar-plus me-2"></i>إظهار التمديد';
            toggleBtn.classList.remove('btn-warning');
            toggleBtn.classList.add('btn-outline-warning');
        }, 300);
    }
}

// دالة حساب أيام التمديد تلقائياً
function calculateExtensionDays() {
    try {
        const startDateInput = document.getElementById('extension_start_date');
        const endDateInput = document.getElementById('extension_end_date');
        const daysField = document.getElementById('extension_days');
        
        if (!startDateInput || !endDateInput || !daysField) {
            console.warn('Extension date inputs not found');
            return;
        }
        
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            
            if (end >= start) {
                const timeDifference = end.getTime() - start.getTime();
                const daysDifference = Math.ceil(timeDifference / (1000 * 3600 * 24));
                daysField.value = daysDifference;
            } else {
                daysField.value = '';
                toastr.warning('تاريخ نهاية التمديد يجب أن يكون بعد تاريخ البداية');
            }
        } else {
            if (daysField) daysField.value = '';
        }
    } catch (error) {
        console.error('Error calculating extension days:', error);
    }
}

// تهيئة النظام عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // تهيئة أزرار الحذف لجميع الاختبارات
    const testNames = [
        'depth_test', 'soil_compaction_test', 'rc1_mc1_test', 'asphalt_test', 'soil_test', 'interlock_test',
        'max_dry_density_pro_test', 'asphalt_ratio_gradation_test', 'marshall_test', 'concrete_molds_test',
        'excavation_bottom_test', 'protection_depth_test', 'settlement_test', 'concrete_temperature_test',
        'field_density_atomic_test', 'moisture_content_test', 'soil_layer_flatness_test', 'concrete_sample_test',
        'asphalt_spray_rate_test', 'asphalt_temperature_test', 'concrete_cylinder_compression_test',
        'soil_particle_analysis_test', 'liquid_plastic_limit_test', 'proctor_test', 'asphalt_layer_flatness_test',
        'asphalt_compaction_atomic_test', 'bitumen_ratio_test', 'asphalt_gradation_test', 'asphalt_mix_gmm_test',
        'marshall_density_test', 'aggregate_ratio_test', 'stability_deficiency_test', 'stability_degree_test',
        'backup_test'
    ];
    testNames.forEach(testName => {
        updateDeleteButtons(testName);
    });
    
    // تهيئة الإجماليات عند تحميل الصفحة
    setTimeout(() => {
        updateTestTotals();
    }, 500);
    
    // إضافة مستمعين لتواريخ التمديد
    const extensionStartDate = document.getElementById('extension_start_date');
    const extensionEndDate = document.getElementById('extension_end_date');
    
    if (extensionStartDate) {
        extensionStartDate.addEventListener('change', calculateExtensionDays);
    }
    
    if (extensionEndDate) {
        extensionEndDate.addEventListener('change', calculateExtensionDays);
    }
});

let extensionCounter = 0;

// دالة إضافة تمديد جديد
function addNewExtension() {
    extensionCounter++;
    const tbody = document.getElementById('extensions-table-body');
    const noExtensionsRow = document.getElementById('no-extensions-row');
    
    if (!tbody) {
        console.error('Extensions table body not found');
        toastr.error('خطأ: لم يتم العثور على جدول التمديدات');
        return;
    }
    
    if (noExtensionsRow) {
        noExtensionsRow.style.display = 'none';
    }

    const newRow = document.createElement('tr');
    newRow.id = `extension-row-${extensionCounter}`;
    newRow.innerHTML = `
        <td>${extensionCounter}</td>
        <td>
            <select class="form-select form-select-sm" name="extensions[${extensionCounter}][license_id]" required>
                <option value="">اختر الرخصة</option>
                <!-- سيتم تعبئتها من JavaScript -->
            </select>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <input type="number" class="form-control" name="extensions[${extensionCounter}][extension_value]" step="0.01" required>
                <span class="input-group-text">ريال</span>
            </div>
        </td>
        <td>
            <input type="date" class="form-control form-control-sm" name="extensions[${extensionCounter}][extension_start_date]" onchange="calculateExtensionDays(${extensionCounter})" required>
        </td>
        <td>
            <input type="date" class="form-control form-control-sm" name="extensions[${extensionCounter}][extension_end_date]" onchange="calculateExtensionDays(${extensionCounter})" required>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm bg-light" name="extensions[${extensionCounter}][extension_days]" id="extension-days-${extensionCounter}" readonly>
        </td>
        <td>
            <input type="file" class="form-control form-control-sm" name="extensions[${extensionCounter}][extension_attachment_1]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
        </td>
        <td>
            <input type="file" class="form-control form-control-sm" name="extensions[${extensionCounter}][extension_attachment_3]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
        </td>
        <td>
            <input type="file" class="form-control form-control-sm" name="extensions[${extensionCounter}][extension_attachment_4]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
        </td>
        <td>
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-success" onclick="saveExtension(${extensionCounter})" title="حفظ">
                    <i class="fas fa-save"></i>
                </button>
                <button type="button" class="btn btn-danger" onclick="removeExtensionRow(${extensionCounter})" title="حذف">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    `;

    tbody.appendChild(newRow);
    
    // تحديث قائمة الرخص في الصف الجديد
    loadLicensesForExtension(extensionCounter);
}

// دالة حذف صف التمديد
function removeExtensionRow(rowId) {
    if (confirm('هل أنت متأكد من حذف هذا التمديد؟')) {
        const row = document.getElementById(`extension-row-${rowId}`);
        if (!row) {
            console.error(`Row with ID extension-row-${rowId} not found`);
            toastr.error('خطأ: لم يتم العثور على الصف المطلوب حذفه');
            return;
        }
        
        row.remove();
        
        // إذا لم تعد هناك صفوف، أظهر رسالة "لا توجد تمديدات"
        const tbody = document.getElementById('extensions-table-body');
        const noExtensionsRow = document.getElementById('no-extensions-row');
        
        if (tbody && tbody.children.length === 1 && noExtensionsRow) {
            noExtensionsRow.style.display = '';
        }
        
        toastr.success('تم حذف التمديد بنجاح');
    }
}

// دالة حساب أيام التمديد
function calculateExtensionDays(extensionId) {
    try {
        const startDateInput = document.querySelector(`input[name="extensions[${extensionId}][extension_start_date]"]`);
        const endDateInput = document.querySelector(`input[name="extensions[${extensionId}][extension_end_date]"]`);
        const daysInput = document.getElementById(`extension-days-${extensionId}`);
        
        if (!startDateInput || !endDateInput || !daysInput) {
            console.warn(`Extension inputs not found for ID: ${extensionId}`);
            return;
        }
        
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            
            if (end >= start) {
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                daysInput.value = diffDays;
            } else {
                daysInput.value = '';
                toastr.warning('تاريخ نهاية التمديد يجب أن يكون بعد تاريخ البداية');
            }
        } else {
            daysInput.value = '';
        }
    } catch (error) {
        console.error('Error calculating extension days:', error);
        toastr.error('خطأ في حساب أيام التمديد');
    }
}

// دالة تحميل الرخص للتمديد
function loadLicensesForExtension(extensionId) {
    // هذه الدالة ستحمل قائمة الرخص المتاحة من الخادم
    // يمكن تنفيذها لاحقاً عند توفر API للرخص
    console.log(`Loading licenses for extension ${extensionId}`);
}

// دالة حفظ التمديد
function saveExtension(extensionId) {
    const formData = new FormData();
    const row = document.getElementById(`extension-row-${extensionId}`);
    
    // التحقق من وجود الصف
    if (!row) {
        console.error(`Row with ID extension-row-${extensionId} not found`);
        toastr.error('خطأ: لم يتم العثور على بيانات التمديد');
        return;
    }
    
    // جمع بيانات التمديد من الصف
    const inputs = row.querySelectorAll('input, select');
    if (inputs.length === 0) {
        toastr.warning('لا توجد بيانات للحفظ');
        return;
    }
    
    inputs.forEach(input => {
        if (input.name && input.value) {
            if (input.type === 'file') {
                if (input.files.length > 0) {
                    formData.append(input.name, input.files[0]);
                }
            } else {
                formData.append(input.name, input.value);
            }
        }
    });

    formData.append('work_order_id', '{{ $workOrder->id }}');
    formData.append('_token', '{{ csrf_token() }}');

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
            toastr.success('تم حفظ التمديد بنجاح');
        },
        error: function(xhr) {
            console.error('Error saving extension:', xhr);
            const errors = xhr.responseJSON?.errors || {};
            if (Object.keys(errors).length > 0) {
                Object.values(errors).forEach(error => {
                    toastr.error(error[0]);
                });
            } else {
                toastr.error('حدث خطأ في حفظ التمديد');
            }
        }
    });
}
</script>

@endsection 