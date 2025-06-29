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
    }
    
    /* تحسينات جدول التفاصيل الفنية للمختبر */
    .bg-gradient-success {
        background: linear-gradient(45deg, #198754, #25a865);
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.075);
        transition: background-color 0.15s ease-in-out;
    }
    
    .form-control-sm, .form-select-sm {
        font-size: 0.85rem;
    }
    
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .btn-group .btn {
        transition: all 0.2s ease;
    }
    
    .btn-group .btn:hover {
        transform: translateY(-1px);
    }
    
    .table th {
        background-color: #495057;
        color: white;
        font-weight: 600;
        border: 1px solid #495057;
    }
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

    /* تحسينات جدول رخص الحفر */
    #licenses-total-row {
        background: linear-gradient(45deg, #f8f9fa, #e9ecef) !important;
        border-top: 3px solid #28a745 !important;
    }

    #licenses-total-row td {
        font-weight: bold !important;
        font-size: 1.1rem !important;
        padding: 15px 8px !important;
    }

    /* تحسينات العد التنازلي */
    .badge {
        font-size: 0.85em;
        padding: 0.5em 0.75em;
        border-radius: 0.5rem;
    }

    .badge.bg-danger {
        animation: pulse-danger 2s infinite;
    }

    .badge.bg-warning {
        animation: pulse-warning 2s infinite;
    }

    @keyframes pulse-danger {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }

    @keyframes pulse-warning {
        0% { opacity: 1; }
        50% { opacity: 0.8; }
        100% { opacity: 1; }
    }

    /* تحسين مظهر الجدول */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0,0,0,.02);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0,123,255,.1);
        transition: background-color 0.3s ease;
    }

    /* تنسيق التاريخ الميلادي */
    .table td small {
        color: #6c757d;
        font-weight: 500;
    }

    /* تحسينات قسم التمديدات */
    #extension-form-card {
        border: 2px solid #007bff !important;
        box-shadow: 0 4px 12px rgba(0,123,255,0.15);
        transition: all 0.3s ease;
    }

    #extension-form-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,123,255,0.2);
    }

    #add-extension-btn:disabled {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        cursor: not-allowed;
    }

    #add-extension-btn:not(:disabled) {
        background: linear-gradient(45deg, #28a745, #20c997);
        border: none;
        transform: scale(1);
        transition: all 0.3s ease;
    }

    #add-extension-btn:not(:disabled):hover {
        background: linear-gradient(45deg, #20c997, #17a2b8);
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(40,167,69,0.3);
    }

    .card.border-warning {
        border-width: 2px !important;
    }

    .card.border-warning:hover {
        box-shadow: 0 4px 12px rgba(255,193,7,0.15);
        transition: box-shadow 0.3s ease;
    }

    /* تحسين مظهر الجدول للتمديدات */
    #extensionsTable .table-warning {
        background: linear-gradient(45deg, #fff3cd, #ffeaa7);
    }

    /* تأثيرات المرور على صفوف التمديدات */
    #extensions-table-body tr:hover {
        background: linear-gradient(45deg, rgba(255,193,7,0.1), rgba(255,235,59,0.1));
        transform: scale(1.01);
        transition: all 0.3s ease;
    }

    /* تحسين مظهر badges عدد الأيام */
    .badge.bg-warning {
        background: linear-gradient(45deg, #ffc107, #ffdd54) !important;
        color: #212529 !important;
        font-weight: bold;
        padding: 0.6em 1em;
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(255,193,7,0.3);
        animation: pulse-warning 3s infinite;
    }

    @keyframes pulse-warning {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.9; }
    }

    @keyframes pulse-success {
        0%, 100% { transform: scale(1); box-shadow: 0 2px 8px rgba(40,167,69,0.3); }
        50% { transform: scale(1.05); box-shadow: 0 4px 16px rgba(40,167,69,0.6); }
    }

    /* تحسين مظهر إجمالي التمديدات */
    #extensions-total-badge {
        transition: all 0.3s ease;
        border-radius: 12px !important;
        font-weight: bold;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    }

    #extensions-total-english {
        font-family: 'Arial', sans-serif;
        letter-spacing: 0.5px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    }

    /* تحسين modal التمديدات */
    #extensionModal .modal-content {
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    #extensionModal .card {
        border-radius: 10px;
        transition: transform 0.3s ease;
    }

    #extensionModal .card:hover {
        transform: translateY(-5px);
    }

    /* تحسين أزرار الإجراءات في جدول التمديدات */
    #extensions-table-body .btn-group .btn {
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 2px;
        transition: all 0.3s ease;
    }

    #extensions-table-body .btn-outline-info:hover {
        background: linear-gradient(45deg, #17a2b8, #20c997);
        border-color: transparent;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(23,162,184,0.4);
    }

    #extensions-table-body .btn-outline-danger:hover {
        background: linear-gradient(45deg, #dc3545, #e74c3c);
        border-color: transparent;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(220,53,69,0.4);
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
        
        // تحميل التمديدات عند تحميل الصفحة
        loadExtensions();
        
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



    // دالة تحميل جدول رخص الحفر
    function loadDigLicenses() {
        $.ajax({
            url: `/admin/licenses/by-work-order/{{ $workOrder->id }}`,
            type: 'GET',
            success: function(response) {
                const tbody = document.getElementById('dig-licenses-table-body');
                const totalRow = document.getElementById('licenses-total-row');
                if (!tbody) return;
                
                tbody.innerHTML = '';

                if (!response.licenses || response.licenses.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="10" class="text-center">لا توجد رخص حفر</td>
                        </tr>
                    `;
                    
                    // إخفاء صف الإجمالي
                    if (totalRow) totalRow.style.display = 'none';
                    return;
                }

                let totalValue = 0; // متغير لحساب إجمالي قيمة الرخص

                response.licenses.forEach((license, index) => {
                    // تنسيق التاريخ الميلادي
                    const licenseDate = license.license_date ? 
                        new Date(license.license_date).toLocaleDateString('en-GB') : '';
                    const startDate = license.license_start_date ? 
                        new Date(license.license_start_date).toLocaleDateString('en-GB') : '';
                    const endDate = license.license_end_date ? 
                        new Date(license.license_end_date).toLocaleDateString('en-GB') : '';
                    
                    const licenseTypeBadge = getLicenseTypeBadge(license.license_type);
                    const dimensions = `${license.excavation_length || 0} × ${license.excavation_width || 0} × ${license.excavation_depth || 0} م`;
                    const period = startDate && endDate ? `${startDate} - ${endDate}` : '-';
                    
                    // حساب العد التنازلي
                    const countdown = calculateCountdown(license.license_end_date);
                    
                    // إضافة قيمة الرخصة للإجمالي
                    if (license.license_value) {
                        totalValue += parseFloat(license.license_value);
                    }
                    
                    tbody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td><strong class="text-primary">${license.license_number || ''}</strong></td>
                            <td><small>${licenseDate}</small></td>
                            <td>${licenseTypeBadge}</td>
                            <td><strong class="text-success">${formatCurrency(license.license_value)}</strong></td>
                            <td><small>${dimensions}</small></td>
                            <td><small>${period}</small></td>
                            <td data-end-date="${license.license_end_date || ''}">${countdown}</td>
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
                
                // إضافة صف الإجمالي
                if (totalRow) {
                    totalRow.style.display = 'table-row';
                    totalRow.innerHTML = `
                        <td colspan="4" class="text-end fw-bold bg-light">إجمالي قيمة الرخص:</td>
                        <td class="fw-bold bg-light"><strong class="text-success fs-5">${formatCurrency(totalValue)}</strong></td>
                        <td colspan="5" class="bg-light"></td>
                    `;
                } else {
                    // إضافة صف الإجمالي إذا لم يكن موجوداً
                    tbody.innerHTML += `
                        <tr id="licenses-total-row" class="border-top border-3">
                            <td colspan="4" class="text-end fw-bold bg-light">إجمالي قيمة الرخص:</td>
                            <td class="fw-bold bg-light"><strong class="text-success fs-5">${formatCurrency(totalValue)}</strong></td>
                            <td colspan="5" class="bg-light"></td>
                        </tr>
                    `;
                }
                
                // تحديث قوائم الرخص في التبويبات الأخرى
                loadLicensesForSelectors();
                
                // بدء تحديث العد التنازلي كل دقيقة
                startCountdownUpdates();
            },
            error: function(xhr) {
                console.error('Error loading dig licenses:', xhr);
                toastr.error('حدث خطأ في تحميل رخص الحفر');
            }
        });
    }

    // دالة حساب العد التنازلي
    function calculateCountdown(endDate) {
        if (!endDate) return '<span class="badge bg-secondary">غير محدد</span>';
        
        const now = new Date();
        const end = new Date(endDate);
        const diffTime = end.getTime() - now.getTime();
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        const diffHours = Math.ceil(diffTime / (1000 * 60 * 60));
        
        if (diffDays < 0) {
            return `<span class="badge bg-danger"><i class="fas fa-exclamation-triangle me-1"></i>منتهية منذ ${Math.abs(diffDays)} يوم</span>`;
        } else if (diffDays === 0) {
            if (diffHours > 0) {
                return `<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>تنتهي خلال ${diffHours} ساعة</span>`;
            } else {
                return '<span class="badge bg-danger"><i class="fas fa-exclamation-triangle me-1"></i>منتهية</span>';
            }
        } else if (diffDays === 1) {
            return '<span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i>تنتهي غداً</span>';
        } else if (diffDays <= 7) {
            return `<span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i>باقي ${diffDays} أيام</span>`;
        } else if (diffDays <= 30) {
            return `<span class="badge bg-info"><i class="fas fa-calendar-check me-1"></i>باقي ${diffDays} يوم</span>`;
        } else {
            return `<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>باقي ${diffDays} يوم</span>`;
        }
    }

    // دالة تحديث العد التنازلي كل دقيقة
    function startCountdownUpdates() {
        // مسح أي interval سابق
        if (window.countdownInterval) {
            clearInterval(window.countdownInterval);
        }
        
        // بدء interval جديد
        window.countdownInterval = setInterval(function() {
            // إعادة حساب العد التنازلي لجميع الرخص
            updateAllCountdowns();
        }, 60000); // كل دقيقة
    }

    // دالة تحديث جميع العدادات التنازلية
    function updateAllCountdowns() {
        const tbody = document.getElementById('dig-licenses-table-body');
        if (!tbody) return;
        
        try {
            // البحث عن جميع الصفوف ما عدا الصف الذي يحتوي على "لا توجد رخص"
            const rows = tbody.querySelectorAll('tr');
            if (!rows || rows.length === 0) return;
            
            rows.forEach((row, index) => {
            // تجاهل الصف الفارغ
            if (row.cells.length === 1 && row.cells[0].getAttribute('colspan')) return;
            
            const countdownCell = row.cells[7]; // عمود العد التنازلي
            if (countdownCell && countdownCell.dataset.endDate) {
                const countdown = calculateCountdown(countdownCell.dataset.endDate);
                countdownCell.innerHTML = countdown;
            }
        });
        } catch (error) {
            console.error('Error in updateAllCountdowns:', error);
        }
    }

    // دالة مساعدة لتنظيف العدادات التنازلية عند مغادرة الصفحة
    window.addEventListener('beforeunload', function() {
        if (window.countdownInterval) {
            clearInterval(window.countdownInterval);
        }
    });

    // تشغيل العد التنازلي عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        // تحميل رخص الحفر مع العد التنازلي
        loadDigLicenses();
    });



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
                
                // رسالة توضيحية مع رابط لعرض التفاصيل
                setTimeout(() => {
                    toastr.info(`البيانات محفوظة وتظهر الآن في تبويب المختبر في تفاصيل الرخصة ${selectedLicenseName}`, 'تم الحفظ بنجاح', {
                        timeOut: 5000,
                        extendedTimeOut: 2000
                    });
                }, 1000);
                
                // إضافة رسالة نجاح مع تأكيد عرض النتائج
                setTimeout(() => {
                    const activeTests = document.querySelectorAll('input[type="checkbox"]:checked').length;
                    const successValue = document.getElementById('successful_tests_value').value || 0;
                    const failedValue = document.getElementById('failed_tests_value').value || 0;
                    
                    let statusMessage = '';
                    if (parseFloat(successValue) > 0 && parseFloat(failedValue) == 0) {
                        statusMessage = 'جميع الاختبارات ناجحة ✅';
                    } else if (parseFloat(failedValue) > 0 && parseFloat(successValue) == 0) {
                        statusMessage = 'جميع الاختبارات راسبة ❌';
                    } else if (parseFloat(successValue) > 0 && parseFloat(failedValue) > 0) {
                        statusMessage = 'نتائج مختلطة - ناجح وراسب ⚠️';
                    } else {
                        statusMessage = 'تم حفظ بيانات الاختبارات';
                    }
                    
                    toastr.success(statusMessage + ` - تظهر النتائج في صفحة تفاصيل الرخصة`, 'نتيجة الاختبارات', {
                        timeOut: 7000
                    });
                    
                    // إضافة ملخص مرئي للاختبارات المحفوظة
                    showLabTestsSummary();
                }, 2000);
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

    // دالة عرض ملخص الاختبارات المحفوظة
    function showLabTestsSummary() {
        const activeTests = [];
        const checkboxes = document.querySelectorAll('#labForm input[type="checkbox"]:checked');
        
        checkboxes.forEach(checkbox => {
            const testName = getTestLabel(checkbox.name);
            activeTests.push(testName);
        });
        
        const successValue = document.getElementById('successful_tests_value').value || 0;
        const failedValue = document.getElementById('failed_tests_value').value || 0;
        
        // فحص المرفقات المرفوعة
        const uploadedFiles = [];
        const fileInputs = document.querySelectorAll('#labForm input[type="file"]');
        fileInputs.forEach(input => {
            if (input.files && input.files.length > 0) {
                const testName = getFileTestLabel(input.name);
                uploadedFiles.push(testName);
            }
        });
        
        if (activeTests.length > 0) {
            let summaryHtml = `
                <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
                    <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>ملخص الاختبارات المحفوظة:</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>الاختبارات المفعلة:</strong>
                            <ul class="mb-2">`;
            
            activeTests.forEach(test => {
                summaryHtml += `<li>${test}</li>`;
            });
            
            summaryHtml += `</ul>
                        </div>
                        <div class="col-md-6">
                            <strong>النتائج المالية:</strong>
                            <ul class="mb-2">
                                <li>قيمة الاختبارات الناجحة: <span class="text-success">${successValue} ريال</span></li>
                                <li>قيمة الاختبارات الراسبة: <span class="text-danger">${failedValue} ريال</span></li>
                            </ul>`;
            
            if (uploadedFiles.length > 0) {
                summaryHtml += `
                            <strong>المرفقات المرفوعة:</strong>
                            <ul class="mb-0">`;
                uploadedFiles.forEach(file => {
                    summaryHtml += `<li><i class="fas fa-file-alt me-1"></i>${file}</li>`;
                });
                summaryHtml += `</ul>`;
            } else {
                summaryHtml += `<small class="text-muted"><i class="fas fa-info-circle me-1"></i>لم يتم رفع مرفقات</small>`;
            }
            
            summaryHtml += `
                        </div>
                    </div>
                                         <hr class="my-2">
                     <div class="d-flex justify-content-between align-items-center">
                         <small class="text-muted">
                             <i class="fas fa-eye me-1"></i>
                             هذه البيانات تظهر الآن في <strong>تبويب المختبر</strong> في صفحة تفاصيل الرخصة
                         </small>
                         <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewLicenseDetails()">
                             <i class="fas fa-external-link-alt me-1"></i>
                             عرض تفاصيل الرخصة
                         </button>
                     </div>
                     <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            // إدراج الملخص بعد نموذج المختبر
            const labForm = document.getElementById('labForm');
            const existingSummary = document.querySelector('.lab-summary-alert');
            if (existingSummary) {
                existingSummary.remove();
            }
            
            const summaryDiv = document.createElement('div');
            summaryDiv.className = 'lab-summary-alert';
            summaryDiv.innerHTML = summaryHtml;
            labForm.parentNode.insertBefore(summaryDiv, labForm.nextSibling);
            
            // إزالة الملخص تلقائياً بعد 10 ثوان
            setTimeout(() => {
                const alertElement = document.querySelector('.lab-summary-alert .alert');
                if (alertElement) {
                    alertElement.classList.remove('show');
                    setTimeout(() => {
                        const summaryElement = document.querySelector('.lab-summary-alert');
                        if (summaryElement) {
                            summaryElement.remove();
                        }
                    }, 500);
                }
            }, 10000);
        }
    }
    
         // دالة للحصول على اسم الاختبار باللغة العربية
     function getTestLabel(testName) {
         const testLabels = {
             'has_depth_test': 'اختبار العمق',
             'has_soil_compaction_test': 'اختبار دك التربة', 
             'has_rc1_mc1_test': 'اختبار RC1-MC1',
             'has_asphalt_test': 'اختبار الأسفلت',
             'has_soil_test': 'اختبار التربة',
             'has_interlock_test': 'اختبار البلاط المتداخل'
         };
         return testLabels[testName] || testName;
     }
     
          // دالة للحصول على اسم الاختبار من اسم الملف
     function getFileTestLabel(fileName) {
         const fileTestLabels = {
             'depth_test_file_path': 'مرفق اختبار العمق',
             'soil_compaction_file_path': 'مرفق اختبار دك التربة',
             'rc1_mc1_file_path': 'مرفق اختبار RC1-MC1',
             'asphalt_test_file_path': 'مرفق اختبار الأسفلت',
             'soil_test_file_path': 'مرفق اختبار التربة',
             'interlock_test_file_path': 'مرفق اختبار البلاط المتداخل'
         };
         return fileTestLabels[fileName] || fileName;
     }
     
     // دالة إشعار عند رفع الملفات
     function onFileUpload(input, testName) {
         if (input.files && input.files.length > 0) {
             const fileName = input.files[0].name;
             toastr.success(`تم اختيار مرفق ${testName}: ${fileName}`, 'تم رفع الملف', {
                 timeOut: 3000,
                 progressBar: true
             });
         }
     }
     
     // دالة عرض تفاصيل الرخصة المحددة
     function viewLicenseDetails() {
         const licenseId = document.getElementById('lab-license-id').value;
         if (licenseId) {
             const url = `{{ url('/admin/licenses') }}/${licenseId}`;
             window.open(url, '_blank');
             toastr.info('تم فتح صفحة تفاصيل الرخصة في نافذة جديدة');
         } else {
             toastr.warning('يرجى اختيار رخصة أولاً');
         }
     }

     // تحميل الرخص المتاحة لهذا أمر العمل
     function loadLicenses() {
        $.ajax({
            url: `/admin/licenses-list/by-work-order/{{ $workOrder->id }}`,
            type: 'GET',
            success: function(response) {
                const select = document.getElementById('license-select');
                if (!select) return;
                
                // تنظيف القائمة المنسدلة
                select.innerHTML = '<option value="" selected disabled>-- اختر رقم الرخصة --</option>';
                
                if (!response.licenses || response.licenses.length === 0) {
                    select.innerHTML += '<option value="" disabled>لا توجد رخص متاحة</option>';
                    return;
                }

                response.licenses.forEach(license => {
                    const option = document.createElement('option');
                    option.value = license.id;
                    option.setAttribute('data-license-number', license.license_number);
                    option.textContent = `${license.license_number} - ${license.license_type || 'غير محدد'}`;
                    select.appendChild(option);
                });
            },
            error: function(xhr) {
                console.error('Error loading licenses:', xhr);
                toastr.error('حدث خطأ في تحميل الرخص');
            }
        });
    }

    // اختيار الرخصة
    function selectLicense(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const licenseId = selectedOption.value;
        const licenseNumber = selectedOption.getAttribute('data-license-number');
        
        // تحديث الحقول المخفية والمرئية
        document.getElementById('violation-license-id').value = licenseId;
        document.getElementById('selected-license-number').value = licenseNumber;
        
        toastr.success(`تم اختيار الرخصة: ${licenseNumber}`);
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
                            <td colspan="12" class="text-center">لا توجد مخالفات</td>
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

                    // إعداد المرفقات
                    const attachmentCell = violation.attachment_path ? 
                        `<button class="btn btn-sm btn-outline-primary" onclick="viewAttachment('${violation.attachment_path}')" title="عرض المرفق">
                            <i class="fas fa-paperclip"></i>
                        </button>` : 
                        '<span class="text-muted">لا يوجد</span>';

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
                            <td>${attachmentCell}</td>
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
        const licenseNumber = document.getElementById('selected-license-number').value;
        
        if (!licenseId || !licenseNumber) {
            toastr.warning('يجب اختيار رخصة محددة قبل حفظ المخالفة');
            return;
        }
        
        const form = document.getElementById('violationForm');
        if (!form) {
            toastr.error('لم يتم العثور على نموذج المخالفة');
            return;
        }

        // التحقق من البيانات المطلوبة
        const violationNumber = form.querySelector('[name="violation_number"]').value;
        const violationType = form.querySelector('[name="violation_type"]').value;
        const violationAmount = form.querySelector('[name="violation_amount"]').value;
        const responsibleParty = form.querySelector('[name="responsible_party"]').value;
        
        if (!violationNumber || !violationType || !violationAmount || !responsibleParty) {
            toastr.error('يجب ملء جميع الحقول المطلوبة');
            return;
        }

        // عرض مؤشر التحميل
        const saveButton = document.querySelector('button[onclick="saveViolationSection()"]');
        const originalText = saveButton.innerHTML;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';
        saveButton.disabled = true;

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
                toastr.success(response.message || `تم حفظ المخالفة بنجاح للرخصة: ${licenseNumber}`);
                resetViolationForm();
                loadViolations(); // سيقوم بتحديث الجدول والإجمالي
            },
            error: function(xhr) {
                console.error('Error saving violation:', xhr);
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    Object.values(errors).forEach(error => {
                        toastr.error(Array.isArray(error) ? error[0] : error);
                    });
                } else {
                    toastr.error(xhr.responseJSON?.message || 'حدث خطأ أثناء حفظ المخالفة');
                }
            },
            complete: function() {
                // إعادة تعيين زر الحفظ
                saveButton.innerHTML = originalText;
                saveButton.disabled = false;
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
            
            // إعادة تعيين اختيار الرخصة
            document.getElementById('license-select').selectedIndex = 0;
            document.getElementById('violation-license-id').value = '';
            document.getElementById('selected-license-number').value = '';
            
            // إعادة تعيين القيم الافتراضية
            document.querySelector('[name="violation_date"]').value = '{{ date("Y-m-d") }}';
            document.querySelector('[name="payment_due_date"]').value = '{{ date("Y-m-d", strtotime("+30 days")) }}';
            document.querySelector('[name="payment_status"]').value = '0';
            
            // إخفاء معاينة الملف
            clearViolationFile();
            
            toastr.info('تم إعادة تعيين النموذج');
        }
    }

    // دالة التحقق من صحة ملف المخالفة
    function validateViolationFile(input) {
        const file = input.files[0];
        if (!file) {
            clearViolationFile();
            return;
        }

        // التحقق من حجم الملف (10 MB)
        const maxSize = 10 * 1024 * 1024; // 10 MB
        if (file.size > maxSize) {
            toastr.error('حجم الملف يجب أن يكون أقل من 10 ميجابايت');
            input.value = '';
            clearViolationFile();
            return;
        }

        // التحقق من نوع الملف
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!allowedTypes.includes(file.type)) {
            toastr.error('نوع الملف غير مدعوم. يرجى اختيار ملف صورة، PDF، أو مستند Word');
            input.value = '';
            clearViolationFile();
            return;
        }

        // عرض معاينة الملف
        const preview = document.getElementById('violation-file-preview');
        const fileName = document.getElementById('violation-file-name');
        
        fileName.textContent = file.name;
        preview.style.display = 'block';
        
        toastr.success('تم اختيار الملف بنجاح');
    }

    // دالة إزالة ملف المخالفة
    function clearViolationFile() {
        const fileInput = document.querySelector('[name="violation_attachment"]');
        const preview = document.getElementById('violation-file-preview');
        
        if (fileInput) {
            fileInput.value = '';
        }
        
        if (preview) {
            preview.style.display = 'none';
        }
        
        toastr.info('تم إزالة الملف');
    }

    // دالة مسح الملف المختار
    function clearViolationFile() {
        const input = document.querySelector('[name="violation_attachment"]');
        const preview = document.getElementById('violation-file-preview');
        
        if (input) input.value = '';
        if (preview) preview.style.display = 'none';
    }

    // دالة عرض المرفق
    function viewAttachment(attachmentPath) {
        if (!attachmentPath) {
            toastr.error('لا يوجد مرفق للعرض');
            return;
        }

        // فتح المرفق في نافذة جديدة
        const url = `/storage/${attachmentPath}`;
        window.open(url, '_blank');
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
                
                // إضافة الرخص لقائمة التمديدات أيضاً
                const extensionSelector = document.getElementById('extension-license-selector');
                if (extensionSelector) {
                    // حفظ الخيار الافتراضي
                    const defaultOption = extensionSelector.querySelector('option[value=""]');
                    extensionSelector.innerHTML = '';
                    if (defaultOption) {
                        extensionSelector.appendChild(defaultOption.cloneNode(true));
                    }
                    
                    // إضافة الرخص
                    response.licenses.forEach(license => {
                        const optionText = `رخصة #${license.license_number} - ${license.license_type || 'غير محدد'}`;
                        const option = document.createElement('option');
                        option.value = license.id;
                        option.textContent = optionText;
                        extensionSelector.appendChild(option);
                    });
                }
                
                // إذا كان هناك رخصة واحدة فقط، اختارها تلقائياً
                if (response.licenses.length === 1) {
                    const licenseId = response.licenses[0].id;
                    const licenseText = `رخصة #${response.licenses[0].license_number}`;
                    
                    [labSelector, evacuationSelector, violationSelector, extensionSelector].forEach(selector => {
                        if (selector) {
                            selector.value = licenseId;
                            // تشغيل دالة التحديد
                            if (selector.id === 'lab-license-selector') selectLabLicense();
                            else if (selector.id === 'evacuation-license-selector') selectEvacuationLicense();
                            else if (selector.id === 'violation-license-selector') selectViolationLicense();
                            else if (selector.id === 'extension-license-selector') selectExtensionLicense();
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
        
        // تحميل بيانات الإخلاءات للرخصة المختارة
        loadEvacuationDataForLicense(selector.value);
    } else {
        licenseIdField.value = '';
        infoDiv.style.display = 'none';
        
        // مسح الجدول عند عدم اختيار رخصة
        const tbody = document.getElementById('evacuationDataTable').getElementsByTagName('tbody')[0];
        tbody.innerHTML = `
            <tr id="no-evacuation-data-row">
                <td colspan="9" class="text-center text-muted py-4">
                    <i class="fas fa-truck fa-2x mb-2"></i>
                    <br>لا توجد بيانات إخلاء مسجلة
                </td>
            </tr>
        `;
    }
}

// دالة اختيار الرخصة للتمديد
function selectExtensionLicense() {
    const selector = document.getElementById('extension-license-selector');
    const licenseIdField = document.getElementById('extension-license-id');
    const infoDiv = document.getElementById('selected-extension-license-info');
    const displayElement = document.getElementById('extension-license-display');
    const addBtn = document.getElementById('add-extension-btn');
    
    if (selector.value) {
        licenseIdField.value = selector.value;
        const selectedText = selector.options[selector.selectedIndex].text;
        displayElement.textContent = selectedText;
        infoDiv.style.display = 'block';
        addBtn.disabled = false;
        addBtn.classList.remove('btn-secondary');
        addBtn.classList.add('btn-success');
    } else {
        licenseIdField.value = '';
        infoDiv.style.display = 'none';
        addBtn.disabled = true;
        addBtn.classList.remove('btn-success');
        addBtn.classList.add('btn-secondary');
        hideExtensionForm(); // إخفاء النموذج إذا لم يتم اختيار رخصة
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

// ========== دوال إدارة التمديدات ==========

// دالة إظهار نموذج التمديد
function showExtensionForm() {
    const formCard = document.getElementById('extension-form-card');
    const licenseId = document.getElementById('extension-license-id').value;
    
    if (!licenseId) {
        toastr.warning('يجب اختيار رخصة أولاً');
        return;
    }
    
    formCard.style.display = 'block';
    formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
    
    // تعيين التاريخ الافتراضي (اليوم لتاريخ البداية)
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('extension-start-date').value = today;
    
    // إضافة مستمعي الأحداث لحساب الأيام
    const startDateInput = document.getElementById('extension-start-date');
    const endDateInput = document.getElementById('extension-end-date');
    
    startDateInput.addEventListener('change', calculateExtensionDays);
    endDateInput.addEventListener('change', calculateExtensionDays);
}

// دالة إخفاء نموذج التمديد
function hideExtensionForm() {
    const formCard = document.getElementById('extension-form-card');
    const form = document.getElementById('extensionForm');
    
    formCard.style.display = 'none';
    
    // إعادة تعيين النموذج
    if (form) {
        form.reset();
        document.getElementById('extension-days-display').value = '';
    }
}

// دالة حساب أيام التمديد
function calculateExtensionDays() {
    const startDate = document.getElementById('extension-start-date').value;
    const endDate = document.getElementById('extension-end-date').value;
    const daysDisplay = document.getElementById('extension-days-display');
    
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        if (end >= start) {
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            daysDisplay.value = diffDays;
        } else {
            daysDisplay.value = '';
            toastr.warning('تاريخ النهاية يجب أن يكون بعد تاريخ البداية');
        }
    } else {
        daysDisplay.value = '';
    }
}

// دالة حفظ التمديد
function saveExtensionData() {
    const licenseId = document.getElementById('extension-license-id').value;
    const licenseSelector = document.getElementById('extension-license-selector');
    
    if (!licenseId) {
        toastr.warning('يجب اختيار رخصة قبل حفظ التمديد');
        return;
    }

    // الحصول على اسم الرخصة المختارة
    const selectedLicenseName = licenseSelector.options[licenseSelector.selectedIndex].text;
    
    // إظهار رسالة تأكيد
    if (!confirm(`هل أنت متأكد من حفظ التمديد في ${selectedLicenseName}؟`)) {
        return;
    }
    
    const formData = new FormData(document.getElementById('extensionForm'));
    
    // إضافة معلومات القسم والرخصة
    formData.set('section_type', 'extension');
    formData.set('license_id', licenseId);
    
    // التأكد من إضافة عدد الأيام المحسوب
    const extensionDays = document.getElementById('extension-days-display').value;
    if (extensionDays) {
        formData.set('extension_days', extensionDays);
    }
    
    // إظهار مؤشر التحميل
    const saveBtn = document.getElementById('save-extension-btn');
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
            console.log('Extension saved successfully:', response);
            toastr.success(`تم حفظ التمديد بنجاح في ${selectedLicenseName}`);
            
            // إعادة تفعيل الزر
            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>حفظ التمديد';
            }
            
            // إخفاء النموذج وإعادة تعيينه
            hideExtensionForm();
            
            // تحديث جدول التمديدات فوراً
            setTimeout(() => {
                loadExtensions();
            }, 500);
            
            // إشعار بنجاح العملية
            setTimeout(() => {
                toastr.info('يمكنك عرض التمديد في صفحة تفاصيل الرخصة');
            }, 1000);
        },
        error: function(xhr) {
            console.error('Error saving extension:', xhr);
            
            // إعادة تفعيل الزر
            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>حفظ التمديد';
            }
            
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

// دالة تنسيق العملة بالعربية
function formatCurrency(amount) {
    if (!amount || amount === 0) return '0 ريال';
    
    // تحويل الرقم إلى نص وإضافة الفاصلات
    const formatted = parseFloat(amount).toLocaleString('ar-SA', {
        style: 'currency',
        currency: 'SAR',
        currencyDisplay: 'name'
    });
    
    return formatted.replace('ريال سعودي', 'ريال');
}

// دالة تنسيق العملة بالإنجليزية
function formatCurrencyEnglish(amount) {
    if (!amount || amount === 0) return '0 SAR';
    
    // تحويل الرقم إلى نص وإضافة الفاصلات بالإنجليزية
    const formatted = parseFloat(amount).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    return formatted + ' SAR';
}

// دالة تحديث إجمالي التمديدات
function updateExtensionsTotal(extensions) {
    let total = 0;
    
    if (extensions && extensions.length > 0) {
        total = extensions.reduce((sum, extension) => {
            return sum + (parseFloat(extension.extension_value) || 0);
        }, 0);
    }
    
    // تحديث النص العربي
    const arabicElement = document.getElementById('extensions-total-arabic');
    if (arabicElement) {
        arabicElement.textContent = `إجمالي: ${formatCurrency(total)}`;
    }
    
    // تحديث النص الإنجليزي
    const englishElement = document.getElementById('extensions-total-english');
    if (englishElement) {
        englishElement.textContent = `Total: ${formatCurrencyEnglish(total)}`;
    }
    
    // إضافة تأثير بصري للإجمالي
    const badgeElement = document.getElementById('extensions-total-badge');
    if (badgeElement) {
        if (total > 0) {
            badgeElement.classList.remove('bg-secondary');
            badgeElement.classList.add('bg-success');
            badgeElement.style.animation = 'pulse-success 2s infinite';
        } else {
            badgeElement.classList.remove('bg-success');
            badgeElement.classList.add('bg-secondary');
            badgeElement.style.animation = 'none';
        }
    }
}

// دالة تحميل التمديدات المحفوظة
function loadExtensions() {
    $.ajax({
        url: `/admin/licenses/extensions/by-work-order/{{ $workOrder->id }}`,
        type: 'GET',
        success: function(response) {
            const tbody = document.getElementById('extensions-table-body');
            const noExtensionsRow = document.getElementById('no-extensions-row');
            
            if (!tbody) return;
            
            // مسح الجدول
            tbody.innerHTML = '';

            if (!response.extensions || response.extensions.length === 0) {
                const noDataRow = document.createElement('tr');
                noDataRow.id = 'no-extensions-row';
                noDataRow.innerHTML = `
                    <td colspan="8" class="text-center text-muted">
                        <i class="fas fa-calendar-times fa-2x mb-3 d-block"></i>
                        لا توجد تمديدات محفوظة
                    </td>
                `;
                tbody.appendChild(noDataRow);
                
                // تحديث الإجمالي إلى صفر
                updateExtensionsTotal([]);
                return;
            }

            response.extensions.forEach((extension, index) => {
                const startDate = extension.extension_start_date ? 
                    new Date(extension.extension_start_date).toLocaleDateString('en-GB') : '';
                const endDate = extension.extension_end_date ? 
                    new Date(extension.extension_end_date).toLocaleDateString('en-GB') : '';
                
                // حساب عدد الأيام إذا لم تكن محفوظة
                let extensionDays = extension.extension_days || 0;
                if (!extensionDays && extension.extension_start_date && extension.extension_end_date) {
                    const start = new Date(extension.extension_start_date);
                    const end = new Date(extension.extension_end_date);
                    const diffTime = Math.abs(end - start);
                    extensionDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                }
                
                const attachments = [];
                if (extension.extension_license_file) {
                    attachments.push(`<a href="${extension.extension_license_file}" target="_blank" class="btn btn-outline-primary btn-sm me-1" title="ملف الرخصة"><i class="fas fa-file-pdf"></i></a>`);
                }
                if (extension.extension_payment_proof) {
                    attachments.push(`<a href="${extension.extension_payment_proof}" target="_blank" class="btn btn-outline-success btn-sm me-1" title="إثبات السداد"><i class="fas fa-receipt"></i></a>`);
                }
                if (extension.extension_bank_proof) {
                    attachments.push(`<a href="${extension.extension_bank_proof}" target="_blank" class="btn btn-outline-info btn-sm" title="إثبات البنك"><i class="fas fa-university"></i></a>`);
                }
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td><strong class="text-primary">رخصة #${extension.license_number || 'غير محدد'}</strong></td>
                    <td><strong class="text-success">${formatCurrency(extension.extension_value)}</strong></td>
                    <td><small>${startDate}</small></td>
                    <td><small>${endDate}</small></td>
                    <td>
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-clock me-1"></i>${extensionDays} يوم
                        </span>
                    </td>
                    <td>${attachments.join('') || '<span class="text-muted">لا توجد مرفقات</span>'}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-info" onclick="viewExtension(${extension.id})" title="عرض">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-danger" onclick="deleteExtension(${extension.id})" title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
            
            // تحديث إجمالي التمديدات
            updateExtensionsTotal(response.extensions);
        },
        error: function(xhr) {
            console.error('Error loading extensions:', xhr);
            
            // في حالة عدم وجود endpoint، عرض بيانات تجريبية
            if (xhr.status === 404) {
                loadSampleExtensions();
            } else {
                toastr.error('حدث خطأ في تحميل التمديدات');
            }
        }
    });
}

// دالة عرض بيانات تجريبية للتمديدات (للاختبار)
function loadSampleExtensions() {
    const tbody = document.getElementById('extensions-table-body');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    // بيانات تجريبية
    const sampleExtensions = [
        {
            id: 1,
            license_number: 'L001',
            extension_value: 5000,
            extension_start_date: '2024-01-15',
            extension_end_date: '2024-02-14',
            extension_days: 30,
            extension_license_file: '/path/to/license.pdf',
            extension_payment_proof: '/path/to/payment.pdf'
        },
        {
            id: 2,
            license_number: 'L002',
            extension_value: 7500,
            extension_start_date: '2024-02-01',
            extension_end_date: '2024-03-15',
            extension_days: 43,
            extension_bank_proof: '/path/to/bank.pdf'
        }
    ];
    
    sampleExtensions.forEach((extension, index) => {
        const startDate = new Date(extension.extension_start_date).toLocaleDateString('en-GB');
        const endDate = new Date(extension.extension_end_date).toLocaleDateString('en-GB');
        
        const attachments = [];
        if (extension.extension_license_file) {
            attachments.push(`<a href="${extension.extension_license_file}" target="_blank" class="btn btn-outline-primary btn-sm me-1" title="ملف الرخصة"><i class="fas fa-file-pdf"></i></a>`);
        }
        if (extension.extension_payment_proof) {
            attachments.push(`<a href="${extension.extension_payment_proof}" target="_blank" class="btn btn-outline-success btn-sm me-1" title="إثبات السداد"><i class="fas fa-receipt"></i></a>`);
        }
        if (extension.extension_bank_proof) {
            attachments.push(`<a href="${extension.extension_bank_proof}" target="_blank" class="btn btn-outline-info btn-sm me-1" title="إثبات البنك"><i class="fas fa-university"></i></a>`);
        }
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td><strong class="text-primary">رخصة #${extension.license_number}</strong></td>
            <td><strong class="text-success">${formatCurrency(extension.extension_value)}</strong></td>
            <td><small>${startDate}</small></td>
            <td><small>${endDate}</small></td>
            <td>
                <span class="badge bg-warning text-dark">
                    <i class="fas fa-clock me-1"></i>${extension.extension_days} يوم
                </span>
            </td>
            <td>${attachments.join('') || '<span class="text-muted">لا توجد مرفقات</span>'}</td>
            <td>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-info" onclick="viewExtension(${extension.id})" title="عرض">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-outline-danger" onclick="deleteExtension(${extension.id})" title="حذف">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
    
    // تحديث إجمالي التمديدات للبيانات التجريبية
    updateExtensionsTotal(sampleExtensions);
    
    toastr.info('تم عرض بيانات تجريبية للتمديدات', 'ملاحظة');
}

// دالة عرض تفاصيل التمديد
function viewExtension(extensionId) {
    if (!extensionId) {
        toastr.error('معرف التمديد غير صحيح');
        return;
    }
    
    // البحث عن التمديد في الجدول للحصول على التفاصيل
    const row = document.querySelector(`#extensions-table-body tr td button[onclick="viewExtension(${extensionId})"]`).closest('tr');
    if (!row) {
        toastr.error('لم يتم العثور على بيانات التمديد');
        return;
    }
    
    const cells = row.cells;
    const extensionDetails = {
        number: cells[0].textContent,
        licenseNumber: cells[1].textContent,
        value: cells[2].textContent,
        startDate: cells[3].textContent,
        endDate: cells[4].textContent,
        days: cells[5].textContent
    };
    
    // إنشاء modal لعرض التفاصيل
    const modalHtml = `
        <div class="modal fade" id="extensionModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">
                            <i class="fas fa-calendar-plus me-2"></i>
                            تفاصيل التمديد #${extensionDetails.number}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="fas fa-certificate me-2"></i>بيانات الرخصة</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>رقم الرخصة:</strong> ${extensionDetails.licenseNumber}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class="fas fa-money-bill me-2"></i>بيانات التمديد</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>قيمة التمديد:</strong> ${extensionDetails.value}</p>
                                        <p><strong>عدد الأيام:</strong> ${extensionDetails.days}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0"><i class="fas fa-calendar me-2"></i>فترة التمديد</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>تاريخ البداية:</strong> ${extensionDetails.startDate}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>تاريخ النهاية:</strong> ${extensionDetails.endDate}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>إغلاق
                        </button>
                        <button type="button" class="btn btn-primary" onclick="window.location.href='/admin/licenses/${extensionId}'">
                            <i class="fas fa-eye me-2"></i>عرض في صفحة الرخصة
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // إزالة أي modal موجود مسبقاً
    const existingModal = document.getElementById('extensionModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // إضافة modal جديد
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // إظهار modal
    const modal = new bootstrap.Modal(document.getElementById('extensionModal'));
    modal.show();
}

// دالة حذف التمديد
function deleteExtension(extensionId) {
    if (!extensionId) {
        toastr.error('معرف التمديد غير صحيح');
        return;
    }

    if (!confirm('هل أنت متأكد من حذف هذا التمديد؟ سيتم حذفه نهائياً من الرخصة.')) {
        return;
    }

    // إظهار مؤشر التحميل
    const deleteBtn = document.querySelector(`button[onclick="deleteExtension(${extensionId})"]`);
    if (deleteBtn) {
        deleteBtn.disabled = true;
        deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    }

    $.ajax({
        url: `/admin/license-extensions/${extensionId}`,
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            toastr.success('تم حذف التمديد بنجاح');
            
            // إعادة تحميل التمديدات
            loadExtensions();
        },
        error: function(xhr) {
            console.error('Error deleting extension:', xhr);
            
            // إعادة تفعيل الزر
            if (deleteBtn) {
                deleteBtn.disabled = false;
                deleteBtn.innerHTML = '<i class="fas fa-trash"></i>';
            }
            
            // في حالة عدم وجود endpoint، محاكاة الحذف
            if (xhr.status === 404) {
                // حذف الصف من الجدول مباشرة (للاختبار)
                const row = deleteBtn.closest('tr');
                if (row) {
                    row.remove();
                    toastr.success('تم حذف التمديد بنجاح (محاكاة)');
                    
                    // التحقق من وجود صفوف أخرى وتحديث الإجمالي
                    const tbody = document.getElementById('extensions-table-body');
                    if (tbody && tbody.children.length === 0) {
                        const noDataRow = document.createElement('tr');
                        noDataRow.id = 'no-extensions-row';
                        noDataRow.innerHTML = `
                            <td colspan="8" class="text-center text-muted">
                                <i class="fas fa-calendar-times fa-2x mb-3 d-block"></i>
                                لا توجد تمديدات محفوظة
                            </td>
                        `;
                        tbody.appendChild(noDataRow);
                        
                        // تحديث الإجمالي إلى صفر
                        updateExtensionsTotal([]);
                    } else {
                        // إعادة حساب الإجمالي من الصفوف المتبقية
                        const remainingExtensions = [];
                        if (tbody) {
                            const rows = tbody.querySelectorAll('tr:not(#no-extensions-row)');
                            rows.forEach(row => {
                            const valueCell = row.cells[2]; // عمود قيمة التمديد
                            if (valueCell) {
                                const valueText = valueCell.textContent.trim();
                                const value = parseFloat(valueText.replace(/[^\d.]/g, '')) || 0;
                                remainingExtensions.push({ extension_value: value });
                            }
                                                    });
                        }
                        updateExtensionsTotal(remainingExtensions);
                    }
                }
            } else {
                toastr.error('حدث خطأ في حذف التمديد');
            }
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
                                            <th>العد التنازلي للرخصة
                                            <th>المرفقات</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dig-licenses-table-body">
                                        <tr>
                                            <td colspan="10" class="text-center">لا توجد رخص حفر</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr id="licenses-total-row" class="border-top border-3" style="display: none;">
                                            <td colspan="4" class="text-end fw-bold bg-light">إجمالي قيمة الرخص:</td>
                                            <td class="fw-bold bg-light">-</td>
                                            <td colspan="5" class="bg-light"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- قسم التمديدات -->
                    <div class="card mt-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-plus me-2"></i>
                                تمديدات الرخص
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- اختيار الرخصة للتمديد -->
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <div class="card border-warning">
                                        <div class="card-header bg-warning text-dark">
                                            <h6 class="mb-0">
                                                <i class="fas fa-clipboard-list me-2"></i>
                                                اختيار الرخصة للتمديد
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-search me-2"></i>اختر الرخصة المراد تمديدها:
                                                    </label>
                                                    <select class="form-select" id="extension-license-selector" onchange="selectExtensionLicense()">
                                                        <option value="">-- اختر الرخصة للتمديد --</option>
                                                    </select>
                                                    <div class="mt-2">
                                                        <small class="text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            يجب اختيار رخصة قبل إدخال بيانات التمديد
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold">إجراءات:</label>
                                                    <div class="d-grid gap-2">
                                                        <button type="button" class="btn btn-success" id="add-extension-btn" onclick="showExtensionForm()" disabled>
                                                            <i class="fas fa-plus me-1"></i>
                                                            إضافة تمديد
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div id="selected-extension-license-info" class="mt-3" style="display: none;">
                                                <div class="alert alert-warning mb-0">
                                                    <i class="fas fa-certificate me-2"></i>
                                                    الرخصة المختارة: <strong id="extension-license-display"></strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- نموذج إضافة التمديد -->
                            <div id="extension-form-card" class="card border-primary mb-4" style="display: none;">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-plus-circle me-2"></i>
                                        إضافة تمديد جديد
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form id="extensionForm" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                                        <input type="hidden" name="license_id" id="extension-license-id" value="">
                                        
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-money-bill me-2"></i>قيمة التمديد (ريال)
                                                </label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="extension_value" step="0.01" required>
                                                    <span class="input-group-text">ريال</span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-calendar me-2"></i>تاريخ البداية
                                                </label>
                                                <input type="date" class="form-control" name="extension_start_date" id="extension-start-date" required>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-calendar-check me-2"></i>تاريخ النهاية
                                                </label>
                                                <input type="date" class="form-control" name="extension_end_date" id="extension-end-date" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-clock me-2"></i>عدد أيام التمديد
                                                </label>
                                                <input type="number" class="form-control bg-light" name="extension_days" id="extension-days-display" readonly>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-file-pdf me-2"></i>ملف الرخصة
                                                </label>
                                                <input type="file" class="form-control" name="extension_license_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-receipt me-2"></i>إثبات السداد
                                                </label>
                                                <input type="file" class="form-control" name="extension_payment_proof" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-university me-2"></i>إثبات سداد البنك
                                                </label>
                                                <input type="file" class="form-control" name="extension_bank_proof" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-sticky-note me-2"></i>ملاحظات (اختياري)
                                                </label>
                                                <textarea class="form-control" name="extension_notes" rows="3" placeholder="أي ملاحظات إضافية عن التمديد..."></textarea>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end gap-2 mt-4">
                                            <button type="button" class="btn btn-secondary" onclick="hideExtensionForm()">
                                                <i class="fas fa-times me-2"></i>إلغاء
                                            </button>
                                            <button type="button" class="btn btn-success" id="save-extension-btn" onclick="saveExtensionData()">
                                                <i class="fas fa-save me-2"></i>حفظ التمديد
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- جدول التمديدات المحفوظة -->
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="fas fa-list me-2"></i>
                                            سجل التمديدات المحفوظة
                                        </h6>
                                        <div class="text-end">
                                            <div class="badge bg-secondary fs-6 px-3 py-2" id="extensions-total-badge">
                                                <i class="fas fa-calculator me-2"></i>
                                                <span id="extensions-total-arabic">إجمالي: 0 ريال</span>
                                            </div>
                                            <div class="mt-1">
                                                <small class="text-light fw-bold" id="extensions-total-english" style="font-family: 'Arial', sans-serif;">
                                                    Total: 0.00 SAR
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover" id="extensionsTable">
                                            <thead class="table-warning">
                                                <tr>
                                                    <th>#</th>
                                                    <th>رقم الرخصة</th>
                                                    <th>قيمة التمديد</th>
                                                    <th>تاريخ البداية</th>
                                                    <th>تاريخ النهاية</th>
                                                    <th>عدد أيام التمديد</th>
                                                    <th>المرفقات</th>
                                                    <th>الإجراءات</th>
                                                </tr>
                                            </thead>
                                            <tbody id="extensions-table-body">
                                                <tr id="no-extensions-row">
                                                    <td colspan="8" class="text-center text-muted">
                                                        <i class="fas fa-calendar-times fa-2x mb-3 d-block"></i>
                                                        لا توجد تمديدات محفوظة
                                                    </td>
                                                </tr>
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
                                <input type="file" class="form-control form-control-sm" name="depth_test_file_path" onchange="onFileUpload(this, 'اختبار العمق')">
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
                                <input type="file" class="form-control form-control-sm" name="soil_compaction_file_path" onchange="onFileUpload(this, 'اختبار دك التربة')">
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
                                <input type="file" class="form-control form-control-sm" name="rc1_mc1_file_path" onchange="onFileUpload(this, 'اختبار RC1-MC1')">
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
                                <input type="file" class="form-control form-control-sm" name="asphalt_test_file_path" onchange="onFileUpload(this, 'اختبار الأسفلت')">
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
                                <input type="file" class="form-control form-control-sm" name="soil_test_file_path" onchange="onFileUpload(this, 'اختبار التربة')">
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
                                <input type="file" class="form-control form-control-sm" name="interlock_test_file_path" onchange="onFileUpload(this, 'اختبار البلاط المتداخل')">
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
                        
                        

                        <!-- جدول بيانات الإخلاءات التفصيلي -->
                        <div class="row mb-4 mt-4">
                            <div class="col-12">
                                <h5 class="text-success mb-3">
                                    <i class="fas fa-clipboard-list me-2"></i>
                                    جدول بيانات الإخلاءات التفصيلي
                                </h5>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <button type="button" class="btn btn-success btn-sm" onclick="addNewEvacuationRow()">
                                <i class="fas fa-plus me-1"></i>
                                إضافة بيانات إخلاء جديدة
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="saveAllEvacuationData()">
                                <i class="fas fa-save me-1"></i>
                                حفظ جميع بيانات الإخلاء
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="evacuationDataTable">
                                <thead class="table-success">
                                    <tr>
                                        <th style="min-width: 60px;">#</th>
                                        <th style="min-width: 120px;">تم الإخلاء؟</th>
                                        <th style="min-width: 120px;">تاريخ الإخلاء</th>
                                        <th style="min-width: 120px;">مبلغ الإخلاء (ريال)</th>
                                        <th style="min-width: 150px;">تاريخ ووقت الإخلاء</th>
                                        <th style="min-width: 130px;">رقم سداد الإخلاء</th>
                                        <th style="min-width: 200px;">ملاحظات</th>
                                        <th style="min-width: 120px;">المرفقات</th>
                                        <th style="min-width: 100px;">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="no-evacuation-data-row">
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="fas fa-truck fa-2x mb-2"></i>
                                            <br>لا توجد بيانات إخلاء مسجلة
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- جدول الفسح للإخلاء -->
                        <div class="row mb-4 mt-5">
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
                        <div class="card mb-4 shadow-lg border-0">
                            <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-flask me-2"></i>
                                    جدول التفاصيل الفنية للمختبر
                                </h5>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-light" onclick="addNewLabDetailsRow()">
                                        <i class="fas fa-plus me-1"></i>إضافة صف
                                    </button>
                                    <button type="button" class="btn btn-outline-light" onclick="saveAllLabDetails()">
                                        <i class="fas fa-save me-1"></i>حفظ البيانات
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped mb-0" id="labDetailsTable">
                                        <thead class="table-dark">
                                            <tr>
                                                <th style="width: 80px;">السنة</th>
                                                <th style="width: 120px;">نوع العمل</th>
                                                <th style="width: 80px;">العمق</th>
                                                <th style="width: 100px;">دك التربة</th>
                                                <th style="width: 100px;">MC1-RC2</th>
                                                <th style="width: 100px;">دك أسفلت</th>
                                                <th style="width: 80px;">ترابي</th>
                                                <th style="width: 120px;">الكثافة القصوى للأسفلت</th>
                                                <th style="width: 120px;">نسبة الأسفلت</th>
                                                <th style="width: 120px;">تجربة مارشال</th>
                                                <th style="width: 120px;">تقييم البلاط</th>
                                                <th style="width: 120px;">تصنيف التربة</th>
                                                <th style="width: 120px;">تجربة بروكتور</th>
                                                <th style="width: 100px;">الخرسانة</th>
                                                <th style="width: 80px;">حذف</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="no-lab-details-row">
                                                <td colspan="15" class="text-center text-muted py-5">
                                                    <i class="fas fa-flask fa-3x mb-3 text-muted"></i>
                                                    <br><strong>لا توجد بيانات اختبارات مختبرية</strong>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                           
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
                                <!-- اختيار الرخصة -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-license me-2"></i>اختر الرخصة
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                            <select class="form-select" name="license_select" id="license-select" required onchange="selectLicense(this)">
                                                <option value="" selected disabled>-- اختر رقم الرخصة --</option>
                                            </select>
                                        </div>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            سيتم ربط المخالفة بالرخصة المحددة
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">رقم الرخصة المحدد</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                            <input type="text" class="form-control" name="license_number" id="selected-license-number" readonly 
                                                   placeholder="سيتم تعبئته تلقائياً عند اختيار الرخصة">
                                        </div>
                                    </div>
                                </div>

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
                                <div class="row g-3 mb-4">
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

                                <!-- ملاحظات إضافية -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold">ملاحظات إضافية</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
                                            <textarea class="form-control" name="notes" rows="3" placeholder="أي ملاحظات إضافية حول المخالفة"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- مرفقات المخالفة -->
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-paperclip me-2"></i>مرفقات المخالفة
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-upload"></i></span>
                                            <input type="file" class="form-control" name="violation_attachment" 
                                                   accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" 
                                                   onchange="validateViolationFile(this)">
                                        </div>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            يمكن رفع الملفات التالية: الصور (JPG, PNG)، PDF، مستندات Word - الحد الأقصى: 10 MB
                                        </div>
                                        <div id="violation-file-preview" class="mt-2" style="display: none;">
                                            <div class="alert alert-info mb-0">
                                                <i class="fas fa-file me-2"></i>
                                                <span id="violation-file-name"></span>
                                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="clearViolationFile()">
                                                    <i class="fas fa-times"></i> إزالة
                                                </button>
                                            </div>
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
                                                <th>المرفقات</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody id="violations-table-body">
                                            <tr>
                                                <td colspan="12" class="text-center">لا توجد مخالفات</td>
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
                                                <td colspan="5"></td>
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
    const labTable = document.getElementById('labDetailsTable');
    if (!labTable) {
        toastr.error('لم يتم العثور على جدول التفاصيل الفنية');
        return;
    }
    
    const tbody = labTable.querySelector('tbody');
    if (!tbody) {
        toastr.error('لم يتم العثور على body الجدول');
        return;
    }
    
    const noLabDetailsRow = document.getElementById('no-lab-details-row');
    if (noLabDetailsRow) {
        noLabDetailsRow.remove();
    }
    
    const newRow = document.createElement('tr');
    // إضافة تأثير بصري
    newRow.style.opacity = '0';
    newRow.style.transform = 'translateY(-20px)';
    newRow.style.transition = 'all 0.3s ease';
    
    const currentYear = new Date().getFullYear();
    newRow.innerHTML = `
        <td><input type="number" class="form-control form-control-sm" name="lab_year" value="${currentYear}" min="2020" max="2030"></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_work_type" placeholder="نوع العمل"></td>
        <td><input type="number" class="form-control form-control-sm" name="lab_depth" step="0.01" placeholder="العمق"></td>
        <td><input type="number" class="form-control form-control-sm" name="lab_soil_compaction" step="0.01" placeholder="دك التربة "></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_mc1rc2" placeholder="MC1-RC2"></td>
        <td><input type="number" class="form-control form-control-sm" name="lab_asphalt_compaction" step="0.01" placeholder="دك أسفلت "></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_soil_type" placeholder="ترابي"></td>
        <td><input type="number" class="form-control form-control-sm" name="lab_max_asphalt_density" step="0.01" placeholder=""></td>
        <td><input type="number" class="form-control form-control-sm" name="lab_asphalt_percentage" step="0.01" placeholder="نسبة الأسفلت "></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_marshall_test" placeholder="نتيجة مارشال"></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_tile_evaluation" placeholder="تقييم البلاط"></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_soil_classification" placeholder="تصنيف التربة"></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_proctor_test" placeholder="نتيجة بروكتور"></td>
        <td><input type="text" class="form-control form-control-sm" name="lab_concrete" placeholder="مقاومة الخرسانة"></td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="deleteLabDetailsRow(this)" title="حذف الصف">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(newRow);
    
    // تأثير بصري للإدراج
    setTimeout(() => {
        newRow.style.opacity = '1';
        newRow.style.transform = 'translateY(0)';
    }, 50);
}

function deleteLabDetailsRow(button) {
    if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
        const row = button.closest('tr');
        if (!row) {
            toastr.error('خطأ في العثور على الصف');
            return;
        }
        
        const labTable = document.getElementById('labDetailsTable');
        if (!labTable) {
            toastr.error('لم يتم العثور على جدول التفاصيل الفنية');
            return;
        }
        
        const tbody = labTable.querySelector('tbody');
        if (!tbody) {
            toastr.error('لم يتم العثور على body الجدول');
            return;
        }
        
        // تأثير بصري للحذف
        row.style.transition = 'all 0.3s ease';
        row.style.opacity = '0';
        row.style.transform = 'translateX(100px)';
        
        setTimeout(() => {
            row.remove();
            
            // إذا لم تعد هناك صفوف، أضف صف "لا توجد بيانات"
            if (tbody.children.length === 0) {
                const noDataRow = document.createElement('tr');
                noDataRow.id = 'no-lab-details-row';
                noDataRow.innerHTML = `
                    <td colspan="15" class="text-center text-muted py-5">
                        <i class="fas fa-flask fa-3x mb-3 text-muted"></i>
                        <br><strong>لا توجد بيانات اختبارات مختبرية</strong>
                        <br><small>اضغط "إضافة صف" لبدء إدخال نتائج الاختبارات</small>
                    </td>
                `;
                tbody.appendChild(noDataRow);
            }
            
            toastr.success('تم حذف السجل بنجاح');
        }, 300);
    }
}

function saveAllLabDetails() {
    // التحقق من اختيار الرخصة
    const licenseIdElement = document.getElementById('evacuation-license-id');
    const licenseSelectorElement = document.getElementById('evacuation-license-selector');
    
    if (!licenseIdElement || !licenseSelectorElement) {
        toastr.error('خطأ في العثور على عناصر اختيار الرخصة');
        return;
    }
    
    const licenseId = licenseIdElement.value;
    
    if (!licenseId) {
        toastr.warning('يجب اختيار رخصة قبل حفظ التفاصيل الفنية');
        return;
    }

    const labTable = document.getElementById('labDetailsTable');
    if (!labTable) {
        toastr.error('لم يتم العثور على جدول التفاصيل الفنية');
        return;
    }

    const rows = labTable.querySelectorAll('tbody tr:not(#no-lab-details-row)');
    if (rows.length === 0) {
        toastr.warning('لا توجد بيانات للحفظ');
        return;
    }
    
    const labDetailsData = [];
    let hasValidData = false;
    
    rows.forEach(row => {
        const inputs = row.querySelectorAll('input, select, textarea');
        const rowData = {};
        
        inputs.forEach(input => {
            if (input.name && input.value.trim()) {
                // تحويل أسماء الحقول لتتطابق مع قاعدة البيانات
                const fieldName = input.name.replace('lab_', '');
                rowData[fieldName] = input.value.trim();
            }
        });
        
        // التحقق من وجود بيانات أساسية مطلوبة
        if (rowData.year || rowData.work_type || rowData.depth || rowData.soil_compaction) {
            labDetailsData.push(rowData);
            hasValidData = true;
        }
    });
    
    if (!hasValidData) {
        toastr.warning('لا توجد بيانات صالحة للحفظ. تأكد من ملء البيانات الأساسية.');
        return;
    }
    
    // الحصول على اسم الرخصة المختارة
    const selectedLicenseName = licenseSelectorElement.options[licenseSelectorElement.selectedIndex].text;
    
    // إظهار رسالة تأكيد
    if (!confirm(`هل أنت متأكد من حفظ التفاصيل الفنية في ${selectedLicenseName}؟`)) {
        return;
    }
    
    // عرض مؤشر التحميل
    const saveButton = document.querySelector('button[onclick="saveAllLabDetails()"]');
    const originalText = saveButton.innerHTML;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';
    saveButton.disabled = true;
    
    // إرسال البيانات للخادم
    $.ajax({
        url: '{{ route("admin.licenses.save-section") }}',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            license_id: licenseId,
            section: 'lab_table2',
            data: labDetailsData
        },
        success: function(response) {
            if (response.success) {
                toastr.success(`تم حفظ التفاصيل الفنية للمختبر بنجاح في ${selectedLicenseName}`);
                console.log('Lab Details saved successfully:', response);
            } else {
                toastr.error(response.message || 'حدث خطأ أثناء حفظ البيانات');
            }
        },
        error: function(xhr) {
            console.error('خطأ في حفظ التفاصيل الفنية:', xhr);
            const errors = xhr.responseJSON?.errors || {};
            if (Object.keys(errors).length > 0) {
                Object.values(errors).forEach(error => {
                    toastr.error(Array.isArray(error) ? error[0] : error);
                });
            } else {
                toastr.error('حدث خطأ أثناء حفظ التفاصيل الفنية للمختبر');
            }
        }
    }).always(function() {
        // إعادة تعيين زر الحفظ
        saveButton.innerHTML = originalText;
        saveButton.disabled = false;
    });
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
    // التحقق من اختيار الرخصة
    const licenseId = document.getElementById('evacuation-license-id').value;
    const licenseSelector = document.getElementById('evacuation-license-selector');
    
    if (!licenseId) {
        toastr.warning('يجب اختيار رخصة قبل حفظ جميع الفسح');
        return;
    }

    // تجميع بيانات الجدول
    const rows = document.getElementById('evacStreetTable').getElementsByTagName('tbody')[0].rows;
    const data = [];
    
    for (let i = 0; i < rows.length; i++) {
        if (rows[i].id !== 'no-evac-streets-row') {
            const row = rows[i];
            const rowData = {
                clearance_number: row.querySelector('[name$="[clearance_number]"]')?.value || '',
                clearance_date: row.querySelector('[name$="[clearance_date]"]')?.value || '',
                length: row.querySelector('[name$="[length]"]')?.value || '',
                lab_length: row.querySelector('[name$="[lab_length]"]')?.value || '',
                street_type: row.querySelector('[name$="[street_type]"]')?.value || '',
                soil_quantity: row.querySelector('[name$="[soil_quantity]"]')?.value || '',
                asphalt_quantity: row.querySelector('[name$="[asphalt_quantity]"]')?.value || '',
                tile_quantity: row.querySelector('[name$="[tile_quantity]"]')?.value || '',
                soil_test: row.querySelector('[name$="[soil_test]"]')?.value || '',
                mc1_test: row.querySelector('[name$="[mc1_test]"]')?.value || '',
                asphalt_test: row.querySelector('[name$="[asphalt_test]"]')?.value || '',
                notes: row.querySelector('[name$="[notes]"]')?.value || ''
            };
            
            // إضافة الصف فقط إذا كان يحتوي على بيانات
            if (Object.values(rowData).some(value => value !== '')) {
                data.push(rowData);
            }
        }
    }

    if (data.length === 0) {
        toastr.warning('لا توجد بيانات فسح للحفظ');
        return;
    }

    // الحصول على اسم الرخصة المختارة
    const selectedLicenseName = licenseSelector.options[licenseSelector.selectedIndex].text;
    
    // إظهار رسالة تأكيد
    if (!confirm(`هل أنت متأكد من حفظ جميع الفسح في ${selectedLicenseName}؟`)) {
        return;
    }

    // إرسال البيانات للخادم
    $.ajax({
        url: '{{ route("admin.licenses.save-section") }}',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            license_id: licenseId,
            section_type: 'evac_table1',
            data: data
        },
        success: function(response) {
            toastr.success(`تم حفظ جميع الفسح بنجاح في ${selectedLicenseName}`);
            console.log('Evac streets saved successfully:', response);
        },
        error: function(xhr) {
            console.error('خطأ في حفظ بيانات الفسح:', xhr);
            const errors = xhr.responseJSON?.errors || {};
            if (Object.keys(errors).length > 0) {
                Object.values(errors).forEach(error => {
                    toastr.error(error[0]);
                });
            } else {
                toastr.error('حدث خطأ أثناء حفظ بيانات الفسح');
            }
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
    
    // التحقق من وجود العنصر قبل المتابعة
    if (!container) {
        console.warn(`Container not found for test: ${testName}`);
        return;
    }
    
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
    
    // التحقق من وجود العنصر قبل المتابعة
    if (!container) {
        console.warn(`Container not found for test: ${testName}`);
        return;
    }
    
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
        
        // التحقق من وجود الحاوية
        if (!container) {
            console.warn(`Container not found for test: ${testName}`);
            return;
        }
        
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
            
            // التحقق من وجود الحاوية قبل المتابعة
            if (!container) {
                console.warn(`Container not found for test: ${testName}`);
                return;
            }
            
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
    const totalTestsSumEl = document.getElementById('total_tests_sum');
    const passedTestsSumEl = document.getElementById('passed_tests_sum');
    const failedTestsSumEl = document.getElementById('failed_tests_sum');
    const activeTestsCountEl = document.getElementById('active_tests_count');
    
    if (totalTestsSumEl) totalTestsSumEl.textContent = totalSum.toFixed(2);
    if (passedTestsSumEl) passedTestsSumEl.textContent = passedSum.toFixed(2);
    if (failedTestsSumEl) failedTestsSumEl.textContent = failedSum.toFixed(2);
    if (activeTestsCountEl) activeTestsCountEl.value = activeTestsCount;
    
    // تحديث الحقول المخفية
    const totalTestsValueEl = document.getElementById('total_tests_value');
    const successfulTestsValueEl = document.getElementById('successful_tests_value');
    const failedTestsValueEl = document.getElementById('failed_tests_value');
    
    if (totalTestsValueEl) totalTestsValueEl.value = totalSum.toFixed(2);
    if (successfulTestsValueEl) successfulTestsValueEl.value = passedSum.toFixed(2);
    if (failedTestsValueEl) failedTestsValueEl.value = failedSum.toFixed(2);
        
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
    
    const successPercentageEl = document.getElementById('success_percentage');
    if (successPercentageEl) {
        successPercentageEl.textContent = successPercentage.toFixed(1) + '%';
    }
    
    // تحديث الحالة العامة
    updateOverallLabStatus(activeTestsCount, passedRowsCount, failedRowsCount, totalRowsCount);
    
    // تحديث أسباب الرسوب
    const failureReasonsTextarea = document.getElementById('test_failure_reasons');
    if (failureReasonsTextarea) {
        if (failureReasons.length > 0) {
            failureReasonsTextarea.value = 'أسباب الرسوب:\n' + failureReasons.join('\n');
        } else if (totalRowsCount > 0 && failedRowsCount === 0) {
            failureReasonsTextarea.value = 'جميع القيم ناجحة - لا توجد أسباب رسوب';
        } else {
            failureReasonsTextarea.value = '';
        }
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
    
    // التحقق من وجود العنصر
    if (!statusElement) {
        console.warn('Overall lab status element not found');
        return;
    }
    
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
    // تحميل الرخص والمخالفات
    loadLicenses();
    loadViolations();
    
    // تهيئة أزرار الحذف لجميع الاختبارات الموجودة
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
    
    // تحديث أزرار الحذف فقط للاختبارات الموجودة
    testNames.forEach(testName => {
        const container = document.getElementById(`${testName}_rows_container`);
        if (container) {
            updateDeleteButtons(testName);
        }
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

// ========== دوال التمديدات تم نقلها للأعلى ==========

// ========== دوال جدول بيانات الإخلاءات التفصيلي ==========

function addNewEvacuationRow() {
    const tbody = document.getElementById('evacuationDataTable').getElementsByTagName('tbody')[0];
    const noRowsMessage = document.getElementById('no-evacuation-data-row');
    
    if (noRowsMessage) {
        noRowsMessage.remove();
    }

    const rowCount = tbody.rows.length + 1;
    const newRow = document.createElement('tr');
    
    newRow.innerHTML = `
        <td class="text-center fw-bold">${rowCount}</td>
        <td>
            <select class="form-select form-select-sm" name="evacuation_data[${rowCount}][is_evacuated]" required>
                <option value="">-- اختر --</option>
                <option value="1">نعم</option>
                <option value="0">لا</option>
            </select>
        </td>
        <td>
            <input type="date" class="form-control form-control-sm" name="evacuation_data[${rowCount}][evacuation_date]" required>
        </td>
        <td>
            <input type="number" step="0.01" class="form-control form-control-sm" name="evacuation_data[${rowCount}][evacuation_amount]" placeholder="0.00" required>
        </td>
        <td>
            <input type="datetime-local" class="form-control form-control-sm" name="evacuation_data[${rowCount}][evacuation_datetime]" required>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="evacuation_data[${rowCount}][payment_number]" placeholder="رقم سداد الإخلاء" required>
        </td>
        <td>
            <textarea class="form-control form-control-sm" name="evacuation_data[${rowCount}][notes]" rows="2" placeholder="ملاحظات الإخلاء"></textarea>
        </td>
        <td>
            <input type="file" class="form-control form-control-sm" name="evacuation_data[${rowCount}][attachments][]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm" onclick="deleteEvacuationRow(this)" title="حذف الصف">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(newRow);
    
    // تطبيق تأثير بصري للصف الجديد
    newRow.style.opacity = '0';
    newRow.style.transform = 'translateY(20px)';
    setTimeout(() => {
        newRow.style.transition = 'all 0.5s ease';
        newRow.style.opacity = '1';
        newRow.style.transform = 'translateY(0)';
    }, 50);
    
    // تحديث أرقام الصفوف
    updateEvacuationRowNumbers();
}

function deleteEvacuationRow(button) {
    const row = button.closest('tr');
    
    // تأثير بصري للحذف
    row.style.transition = 'all 0.3s ease';
    row.style.opacity = '0';
    row.style.transform = 'translateX(100px)';
    
    setTimeout(() => {
        row.remove();
        updateEvacuationRowNumbers();
        
        const tbody = document.getElementById('evacuationDataTable').getElementsByTagName('tbody')[0];
        if (tbody.rows.length === 0) {
            tbody.innerHTML = `
                <tr id="no-evacuation-data-row">
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="fas fa-truck fa-2x mb-2"></i>
                        <br>لا توجد بيانات إخلاء مسجلة
                    </td>
                </tr>
            `;
        }
    }, 300);
}

function updateEvacuationRowNumbers() {
    const tbody = document.getElementById('evacuationDataTable').getElementsByTagName('tbody')[0];
    const rows = tbody.querySelectorAll('tr:not(#no-evacuation-data-row)');
    
    rows.forEach((row, index) => {
        const firstCell = row.cells[0];
        firstCell.textContent = index + 1;
    });
}

function saveAllEvacuationData() {
    // التحقق من اختيار الرخصة
    const licenseId = document.getElementById('evacuation-license-id').value;
    if (!licenseId) {
        toastr.error('يجب اختيار الرخصة أولاً');
        return;
    }

    // تجميع بيانات الجدول
    const rows = document.getElementById('evacuationDataTable').getElementsByTagName('tbody')[0].rows;
    const data = [];
    let hasValidData = false;
    
    for (let i = 0; i < rows.length; i++) {
        if (rows[i].id !== 'no-evacuation-data-row') {
            const row = rows[i];
            const rowData = {
                is_evacuated: row.querySelector('[name*="[is_evacuated]"]').value,
                evacuation_date: row.querySelector('[name*="[evacuation_date]"]').value,
                evacuation_amount: row.querySelector('[name*="[evacuation_amount]"]').value,
                evacuation_datetime: row.querySelector('[name*="[evacuation_datetime]"]').value,
                payment_number: row.querySelector('[name*="[payment_number]"]').value,
                notes: row.querySelector('[name*="[notes]"]').value
            };
            
            // التحقق من وجود بيانات مطلوبة
            if (rowData.is_evacuated && rowData.evacuation_date && rowData.evacuation_amount && rowData.evacuation_datetime && rowData.payment_number) {
                data.push(rowData);
                hasValidData = true;
            }
        }
    }

    if (!hasValidData) {
        toastr.warning('لا توجد بيانات صالحة للحفظ. تأكد من ملء الحقول المطلوبة.');
        return;
    }

    // عرض مؤشر التحميل
    const saveButton = document.querySelector('button[onclick="saveAllEvacuationData()"]');
    const originalText = saveButton.innerHTML;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';
    saveButton.disabled = true;

    // إرسال البيانات إلى الخادم
    fetch(`/admin/licenses/save-evacuation-data`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            work_order_id: {{ $workOrder->id }},
            license_id: licenseId,
            evacuation_data: data
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success(`تم حفظ بيانات الإخلاءات بنجاح للرخصة: ${data.license_name || licenseId}`);
        } else {
            toastr.error(data.message || 'حدث خطأ أثناء حفظ البيانات');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('حدث خطأ أثناء حفظ بيانات الإخلاءات');
    })
    .finally(() => {
        // إعادة تعيين زر الحفظ
        saveButton.innerHTML = originalText;
        saveButton.disabled = false;
    });
}

// دالة لتحميل بيانات الإخلاءات الموجودة للرخصة المختارة
function loadEvacuationDataForLicense(licenseId) {
    if (!licenseId) return;
    
    fetch(`/admin/licenses/get-evacuation-data/${licenseId}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('evacuationDataTable').getElementsByTagName('tbody')[0];
            tbody.innerHTML = '';
            
            if (data.evacuation_data && data.evacuation_data.length > 0) {
                data.evacuation_data.forEach((item, index) => {
                    addEvacuationRowWithData(item, index + 1);
                });
            } else {
                tbody.innerHTML = `
                    <tr id="no-evacuation-data-row">
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-truck fa-2x mb-2"></i>
                            <br>لا توجد بيانات إخلاء مسجلة لهذه الرخصة
                        </td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading evacuation data:', error);
        });
}

function addEvacuationRowWithData(data, rowNumber) {
    const tbody = document.getElementById('evacuationDataTable').getElementsByTagName('tbody')[0];
    const noRowsMessage = document.getElementById('no-evacuation-data-row');
    
    if (noRowsMessage) {
        noRowsMessage.remove();
    }

    const newRow = document.createElement('tr');
    
    newRow.innerHTML = `
        <td class="text-center fw-bold">${rowNumber}</td>
        <td>
            <select class="form-select form-select-sm" name="evacuation_data[${rowNumber}][is_evacuated]" required>
                <option value="">-- اختر --</option>
                <option value="1" ${data.is_evacuated == '1' ? 'selected' : ''}>نعم</option>
                <option value="0" ${data.is_evacuated == '0' ? 'selected' : ''}>لا</option>
            </select>
        </td>
        <td>
            <input type="date" class="form-control form-control-sm" name="evacuation_data[${rowNumber}][evacuation_date]" value="${data.evacuation_date || ''}" required>
        </td>
        <td>
            <input type="number" step="0.01" class="form-control form-control-sm" name="evacuation_data[${rowNumber}][evacuation_amount]" value="${data.evacuation_amount || ''}" placeholder="0.00" required>
        </td>
        <td>
            <input type="datetime-local" class="form-control form-control-sm" name="evacuation_data[${rowNumber}][evacuation_datetime]" value="${data.evacuation_datetime || ''}" required>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="evacuation_data[${rowNumber}][payment_number]" value="${data.payment_number || ''}" placeholder="رقم سداد الإخلاء" required>
        </td>
        <td>
            <textarea class="form-control form-control-sm" name="evacuation_data[${rowNumber}][notes]" rows="2" placeholder="ملاحظات الإخلاء">${data.notes || ''}</textarea>
        </td>
        <td>
            <input type="file" class="form-control form-control-sm" name="evacuation_data[${rowNumber}][attachments][]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
            ${data.attachments ? `<small class="text-muted">ملفات موجودة: ${data.attachments}</small>` : ''}
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm" onclick="deleteEvacuationRow(this)" title="حذف الصف">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(newRow);
}

</script>

@endsection 