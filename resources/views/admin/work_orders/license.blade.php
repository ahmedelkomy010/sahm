@extends('layouts.app')

@section('content')
<!-- إضافة CSRF token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- تضمين مكتبات JavaScript وCSS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- إعدادات toastr الآمنة -->
<script>
$(document).ready(function() {
    // إعدادات toastr الافتراضية الآمنة
    if (typeof toastr !== 'undefined') {
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    }
});

// حفظ مرجع للـ toastr الأصلي
let originalToastr = null;

// دالة آمنة لعرض رسائل toastr
function safeToastr(type, message, title = '', options = {}) {
    try {
        // استخدام الـ toastr الأصلي وليس الـ wrapper
        if (originalToastr && originalToastr[type]) {
            // دمج الإعدادات الافتراضية مع الإعدادات المخصصة
            const defaultOptions = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            
            const finalOptions = Object.assign({}, defaultOptions, options);
            return originalToastr[type](message, title, finalOptions);
        } else {
            // fallback to console if toastr unavailable
            console.log(`${type.toUpperCase()}: ${title ? title + ' - ' : ''}${message}`);
        }
    } catch (error) {
        console.error('Toastr error:', error);
        console.log(`${type.toUpperCase()}: ${title ? title + ' - ' : ''}${message}`);
    }
}

// إنشاء wrapper آمن لجميع دوال toastr
function createSafeToastrWrapper() {
    if (typeof toastr !== 'undefined') {
        // حفظ مرجع للـ toastr الأصلي
        originalToastr = {
            success: toastr.success.bind(toastr),
            error: toastr.error.bind(toastr),
            warning: toastr.warning.bind(toastr),
            info: toastr.info.bind(toastr),
            options: toastr.options,
            clear: toastr.clear,
            remove: toastr.remove
        };
        
        // إعادة تعريف toastr بنسخة آمنة
        window.toastr = {
            success: function(message, title, options) {
                return safeToastr('success', message, title, options);
            },
            error: function(message, title, options) {
                return safeToastr('error', message, title, options);
            },
            warning: function(message, title, options) {
                return safeToastr('warning', message, title, options);
            },
            info: function(message, title, options) {
                return safeToastr('info', message, title, options);
            },
            options: originalToastr.options,
            clear: originalToastr.clear,
            remove: originalToastr.remove
        };
    }
}

// Run wrapper after page load - temporarily disabled
$(document).ready(function() {
    // Disable all toastr notifications in license page
    if (typeof toastr !== 'undefined') {
        toastr.success = function() { console.log('toastr.success disabled'); return false; };
        toastr.error = function() { console.log('toastr.error disabled'); return false; };
        toastr.warning = function() { console.log('toastr.warning disabled'); return false; };
        toastr.info = function() { console.log('toastr.info disabled'); return false; };
    }
    
    // Disable wrapper temporarily
    // setTimeout(createSafeToastrWrapper, 100);
});
</script>

<!-- Custom Styles -->
<style>
    .license-header {
        background: linear-gradient(135deg,rgb(38, 42, 61) 0%,rgb(116, 78, 165) 100%);
        border-radius: 15px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    /* ضمان ظهور إجمالي الإخلاء */
    #evacuationDataTable tfoot {
        display: table-footer-group !important;
        background-color: #d1ecf1 !important;
    }
    
    #total-evacuation-amount {
        display: table-cell !important;
        visibility: visible !important;
        background-color: #b8daff !important;
        color: #004085 !important;
        font-weight: bold !important;
        font-size: 1.1em !important;
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
    
    /* تحسين مظهر رقم شهادة التنسيق */
    .text-info[title="رقم شهادة التنسيق"] {
        background: linear-gradient(135deg, #17a2b8 0%, #0056b3 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        padding: 2px 4px;
        border-radius: 4px;
        border: 1px solid rgba(23, 162, 184, 0.2);
        display: inline-block;
    }
    
    .text-info[title="رقم شهادة التنسيق"] i {
        color: #17a2b8;
        -webkit-text-fill-color: initial;
    }
    
    .text-info[title="رقم شهادة التنسيق"]:hover {
        background: linear-gradient(135deg, #0056b3 0%, #17a2b8 100%);
        -webkit-background-clip: text;
        border-color: rgba(23, 162, 184, 0.4);
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(23, 162, 184, 0.1);
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
    #extensionsTable {
        font-size: 0.9rem;
    }
    
    #extensionsTable .table-warning {
        background: linear-gradient(45deg, #fff3cd, #ffeaa7);
    }
    
    #extensionsTable th {
        vertical-align: middle;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    
    #extensionsTable td {
        vertical-align: middle;
        padding: 0.5rem 0.3rem;
    }

    /* تأثيرات المرور على صفوف التمديدات */
    #extensions-table-body tr:hover {
        background: linear-gradient(45deg, rgba(255,193,7,0.1), rgba(255,235,59,0.1));
        transform: scale(1.005);
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* تحسين عرض الأزرار في الجدول */
    #extensionsTable .btn-group-sm .btn {
        padding: 0.25rem 0.4rem;
        font-size: 0.8rem;
    }
    
    /* تحسين عرض التواريخ */
    #extensionsTable small {
        font-size: 0.85rem;
        color: #495057;
    }
    
    /* تحسين عرض البادج */
    #extensionsTable .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.5em;
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

    /* تنسيق قسم المرفقات */
    .min-height-100 {
        min-height: 100px;
    }
    
    #evacuation-attachments-list .card {
        transition: all 0.3s ease;
        cursor: default;
    }
    
    #evacuation-attachments-list .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    #evacuation-attachments-list .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
    
    /* تحسين مظهر حقل رفع الملفات */
    #evacuation-attachments {
        border: 2px dashed #17a2b8;
        background: rgba(23, 162, 184, 0.05);
        transition: all 0.3s ease;
    }
    
    #evacuation-attachments:hover {
        border-color: #138496;
        background: rgba(23, 162, 184, 0.1);
    }
    
    #evacuation-attachments:focus {
        border-color: #0c5460;
        box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
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
                
                // إعادة تفعيل الزر مع تأثير بصري للنجاح
                if (saveBtn) {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i>تم الحفظ بنجاح';
                    saveBtn.classList.remove('btn-success');
                    saveBtn.classList.add('btn-success');
                    saveBtn.style.background = 'linear-gradient(45deg, #28a745, #20c997)';
                    
                    // إرجاع الزر لحالته الطبيعية بعد 3 ثواني
                    setTimeout(() => {
                        saveBtn.innerHTML = '<i class="fas fa-plus-circle me-2"></i>حفظ شهادة التنسيق';
                        saveBtn.style.background = '';
                    }, 3000);
                }
                
                // رسالة نجاح مفصلة
                toastr.success(`تم حفظ شهادة التنسيق بنجاح! رقم الرخصة: ${response.license_id}`, 'نجح الحفظ', {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                });
                
                // تحديث جدول رخص الحفر فوراً
                setTimeout(() => {
                    loadDigLicenses();
                }, 500);
                
                // تحديث إضافي بعد ثانية للتأكد
                setTimeout(() => {
                    loadDigLicenses();
                }, 1500);
                
                // إعادة تعيين النموذج (اختياري - يمكن تعطيله إذا كان المستخدم يريد الاحتفاظ بالبيانات)
                // document.getElementById('coordinationForm').reset();
                
                // تحديث حالة النموذج لإظهار أنه تم الحفظ
                const form = document.getElementById('coordinationForm');
                const formFields = form.querySelectorAll('input, select, textarea');
                formFields.forEach(field => {
                    field.style.borderColor = '#28a745';
                    field.style.boxShadow = '0 0 0 0.2rem rgba(40, 167, 69, 0.25)';
                });
                
                // إزالة التأثير بعد 3 ثواني
                setTimeout(() => {
                    formFields.forEach(field => {
                        field.style.borderColor = '';
                        field.style.boxShadow = '';
                    });
                }, 3000);
                
                // إظهار رسالة نجاح بدون إعادة تحميل الصفحة
                setTimeout(() => {
                    toastr.success('تم حفظ شهادة التنسيق بنجاح وتحديث الجدول!', 'تم الحفظ بنجاح', {
                        "closeButton": true,
                        "timeOut": "3000"
                    });
                }, 1000);
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
            url: `/admin/licenses/all-by-work-order/{{ $workOrder->id }}`,
            type: 'GET',
            success: function(response) {
                console.log('loadDigLicenses response:', response);
                
                const tbody = document.getElementById('dig-licenses-table-body');
                const totalRow = document.getElementById('licenses-total-row');
                if (!tbody) {
                    console.error('dig-licenses-table-body not found');
                    return;
                }
                
                tbody.innerHTML = '';

                if (!response.success || !response.licenses || response.licenses.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="11" class="text-center">لا توجد رخص حفر</td>
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
                    
                    // حساب العد التنازلي مع التحقق من حالة التنفيذ
                    const countdown = calculateCountdown(license.license_end_date, license.work_order_execution_status, license.license_start_date);
                    
                    // إضافة قيمة الرخصة للإجمالي
                    if (license.license_value) {
                        totalValue += parseFloat(license.license_value);
                    }
                    
                    tbody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>
                                ${license.coordination_certificate_number ? 
                                    `<strong class="text-info" title="رقم شهادة التنسيق">
                                        <i class="fas fa-certificate me-1"></i>
                                        ${license.coordination_certificate_number}
                                    </strong>` : 
                                    `<span class="text-muted">-</span>`
                                }
                            </td>
                            <td><strong class="text-primary">${license.license_number || ''}</strong></td>
                            <td><small>${licenseDate}</small></td>
                            <td>${licenseTypeBadge}</td>
                            <td><strong class="text-success">${formatCurrency(license.license_value)}</strong></td>
                            <td><small>${dimensions}</small></td>
                            <td><small>${period}</small></td>
                            <td data-end-date="${license.license_end_date || ''}" data-start-date="${license.license_start_date || ''}" data-execution-status="${license.work_order_execution_status || ''}">${countdown}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    ${license.license_file_url ? 
                                        `<a href="${license.license_file_url}" target="_blank" class="btn btn-outline-primary" title="ملف الرخصة">
                                            <i class="fas fa-file-pdf me-1"></i>رخصة
                                        </a>` : 
                                        `<span class="btn btn-outline-secondary disabled" title="لا يوجد ملف رخصة">
                                            <i class="fas fa-file-pdf me-1"></i>رخصة
                                        </span>`
                                    }
                                    ${license.payment_proof_url ? 
                                        `<a href="${license.payment_proof_url}" target="_blank" class="btn btn-outline-success" title="إثبات السداد">
                                            <i class="fas fa-receipt me-1"></i>إثبات
                                        </a>` : 
                                        license.payment_proof_urls && license.payment_proof_urls.length > 0 ?
                                        `<button class="btn btn-outline-success" onclick="showPaymentProofs(${license.id})" title="إثباتات السداد (${license.payment_proof_urls.length})">
                                            <i class="fas fa-receipt me-1"></i>${license.payment_proof_urls.length}
                                        </button>` : ''
                                    }
                                    ${license.payment_invoices_urls && license.payment_invoices_urls.length > 0 ? 
                                        `<button class="btn btn-outline-info" onclick="showPaymentInvoices(${license.id})" title="فواتير السداد (${license.payment_invoices_urls.length})">
                                            <i class="fas fa-file-invoice me-1"></i>${license.payment_invoices_urls.length}
                                        </button>` : ''
                                    }
                                    ${license.license_activation_urls && license.license_activation_urls.length > 0 ? 
                                        `<button class="btn btn-outline-warning" onclick="showActivationFiles(${license.id})" title="ملفات اضافية (${license.license_activation_urls.length})">
                                            <i class="fas fa-power-off me-1"></i>${license.license_activation_urls.length}
                                        </button>` : ''
                                    }
                                </div>
                                <div class="mt-1">
                                    <small class="text-muted">
                                        ${getAttachmentsCount(license)} مرفق
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addLicenseModal" title="إضافة رخصة">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button class="btn btn-outline-info" onclick="viewLicense(${license.id})" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="deleteLicense(${license.id})" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    ${license.is_cancelled ? 
                                        `<button class="btn btn-outline-warning btn-sm" onclick="toggleLicenseStatus(${license.id}, false)" title="الرخصة ملغاة - اضغط لتفعيلها">
                                            <i class="fas fa-ban"></i>
                                            <small>ملغاة</small>
                                        </button>` : 
                                        `<button class="btn btn-outline-success btn-sm" onclick="toggleLicenseStatus(${license.id}, true)" title="الرخصة متاحة - اضغط لإلغائها">
                                            <i class="fas fa-check-circle"></i>
                                            <small>متاحة</small>
                                        </button>`
                                    }
                                    
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
                
                // حفظ بيانات الرخص في sessionStorage للوصول إليها لاحقاً
                sessionStorage.setItem('current_licenses', JSON.stringify(response.licenses));
                
                // تحديث قوائم الرخص في التبويبات الأخرى
                loadLicensesForSelectors();
                
                // بدء تحديث العد التنازلي كل دقيقة
                startCountdownUpdates();
                
                // تحديث مفتاح حالة الرخص
                updateLicenseToggle(response.licenses && response.licenses.length > 0);
            },
            error: function(xhr) {
                console.error('Error loading dig licenses:', xhr);
                toastr.error('حدث خطأ في تحميل رخص الحفر');
                
                // تحديث المفتاح في حالة الخطأ
                updateLicenseToggle(false);
            }
        });
    }

    // دالة تحديث مفتاح حالة الرخص
    function updateLicenseToggle(hasLicenses) {
        const toggleIcon = document.getElementById('toggleIcon');
        const toggleText = document.getElementById('toggleText');
        const toggleContainer = document.querySelector('.toggle-switch');
        
        if (hasLicenses) {
            // يوجد رخص - مفتوح
            toggleIcon.className = 'fas fa-unlock toggle-icon me-2 fs-5';
            toggleIcon.style.color = '#28a745';
            toggleText.textContent = 'مفتوح - يوجد رخص';
            toggleText.style.color = '#28a745';
            toggleContainer.style.background = 'rgba(40, 167, 69, 0.2)';
        } else {
            // لا توجد رخص - مقفل
            toggleIcon.className = 'fas fa-lock toggle-icon me-2 fs-5';
            toggleIcon.style.color = '#ff6b6b';
            toggleText.textContent = 'مقفل - لا توجد رخص';
            toggleText.style.color = '#ff6b6b';
            toggleContainer.style.background = 'rgba(255, 107, 107, 0.2)';
        }
    }

    // دالة حساب العد التنازلي والمدة الكاملة للرخصة
    function calculateCountdown(endDate, executionStatus, startDate = null) {
        // التحقق من حالة التنفيذ - إذا كانت "تم تسليم 155" (حالة 2)، توقف العد التنازلي
        if (executionStatus && parseInt(executionStatus) >= 2) {
            return '<span class="badge bg-info"><i class="fas fa-check me-1"></i>تم التسليم</span>';
        }
        
        if (!endDate) return '<span class="badge bg-secondary">غير محدد</span>';
        
        const now = new Date();
        const end = new Date(endDate);
        const diffTime = end.getTime() - now.getTime();
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        const diffHours = Math.ceil(diffTime / (1000 * 60 * 60));
        
        // حساب المدة الكاملة للرخصة إذا كان تاريخ البداية متوفر
        let totalDaysText = '';
        if (startDate) {
            const start = new Date(startDate);
            const totalTime = end.getTime() - start.getTime();
            const totalDays = Math.ceil(totalTime / (1000 * 60 * 60 * 24));
            if (totalDays > 0) {
                totalDaysText = ` من ${totalDays}`;
            }
        }
        
        if (diffDays < 0) {
            return `<span class="badge bg-danger"><i class="fas fa-exclamation-triangle me-1"></i>منتهية منذ ${Math.abs(diffDays)} يوم${totalDaysText}</span>`;
        } else if (diffDays === 0) {
            if (diffHours > 0) {
                return `<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>تنتهي خلال ${diffHours} ساعة${totalDaysText}</span>`;
            } else {
                return `<span class="badge bg-danger"><i class="fas fa-exclamation-triangle me-1"></i>منتهية${totalDaysText}</span>`;
            }
        } else if (diffDays === 1) {
            return `<span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i>تنتهي غداً${totalDaysText}</span>`;
        } else if (diffDays <= 7) {
            return `<span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i>باقي ${diffDays} أيام${totalDaysText}</span>`;
        } else if (diffDays <= 30) {
            return `<span class="badge bg-info"><i class="fas fa-calendar-check me-1"></i>باقي ${diffDays} يوم${totalDaysText}</span>`;
        } else {
            return `<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>باقي ${diffDays} يوم${totalDaysText}</span>`;
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
                const countdown = calculateCountdown(countdownCell.dataset.endDate, countdownCell.dataset.executionStatus, countdownCell.dataset.startDate);
                countdownCell.innerHTML = countdown;
            }
        });
        } catch (error) {
            console.error('Error in updateAllCountdowns:', error);
        }
    }

    // دالة حساب عدد المرفقات
    function getAttachmentsCount(license) {
        let count = 0;
        if (license.license_file_url) count++;
        if (license.payment_proof_url) count++;
        if (license.payment_proof_urls && license.payment_proof_urls.length > 0) count += license.payment_proof_urls.length;
        if (license.payment_invoices_urls && license.payment_invoices_urls.length > 0) count += license.payment_invoices_urls.length;
        if (license.license_activation_urls && license.license_activation_urls.length > 0) count += license.license_activation_urls.length;
        return count;
    }

    // دالة عرض إثباتات السداد
    function showPaymentProofs(licenseId) {
        const licenses = JSON.parse(sessionStorage.getItem('current_licenses') || '[]');
        const license = licenses.find(l => l.id == licenseId);
        
        if (!license || !license.payment_proof_urls) {
            toastr.error('لا توجد إثباتات سداد لهذه الرخصة');
            return;
        }

        let modalContent = '<div class="row">';
        license.payment_proof_urls.forEach((file, index) => {
            if (file.exists && file.url) {
                modalContent += `
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-receipt fa-3x text-success mb-3"></i>
                                <h6>إثبات سداد ${index + 1}</h6>
                                <a href="${file.url}" target="_blank" class="btn btn-success btn-sm">
                                    <i class="fas fa-external-link-alt me-1"></i>عرض الملف
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            }
        });
        modalContent += '</div>';

        showCustomModal('إثباتات السداد', modalContent);
    }

    // دالة عرض فواتير السداد
    function showPaymentInvoices(licenseId) {
        const licenses = JSON.parse(sessionStorage.getItem('current_licenses') || '[]');
        const license = licenses.find(l => l.id == licenseId);
        
        if (!license || !license.payment_invoices_urls) {
            toastr.error('لا توجد فواتير سداد لهذه الرخصة');
            return;
        }

        let modalContent = '<div class="row">';
        license.payment_invoices_urls.forEach((file, index) => {
            if (file.exists && file.url) {
                modalContent += `
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-file-invoice fa-3x text-info mb-3"></i>
                                <h6>فاتورة سداد ${index + 1}</h6>
                                <a href="${file.url}" target="_blank" class="btn btn-info btn-sm">
                                    <i class="fas fa-external-link-alt me-1"></i>عرض الملف
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            }
        });
        modalContent += '</div>';

        showCustomModal('فواتير السداد', modalContent);
    }

    // دالة عرض ملفات التفعيل
    function showActivationFiles(licenseId) {
        const licenses = JSON.parse(sessionStorage.getItem('current_licenses') || '[]');
        const license = licenses.find(l => l.id == licenseId);
        
        if (!license || !license.license_activation_urls) {
            toastr.error('لا توجد ملفات تفعيل لهذه الرخصة');
            return;
        }

        let modalContent = '<div class="row">';
        license.license_activation_urls.forEach((file, index) => {
            if (file.exists && file.url) {
                modalContent += `
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-power-off fa-3x text-warning mb-3"></i>
                                <h6>ملف تفعيل ${index + 1}</h6>
                                <a href="${file.url}" target="_blank" class="btn btn-warning btn-sm">
                                    <i class="fas fa-external-link-alt me-1"></i>عرض الملف
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            }
        });
        modalContent += '</div>';

        showCustomModal('ملفات التفعيل', modalContent);
    }

    // دالة مساعدة لعرض نافذة منبثقة مخصصة
    function showCustomModal(title, content) {
        const modalId = 'customModal_' + Date.now();
        const modalHtml = `
            <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">${title}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            ${content}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // إضافة النافذة إلى الصفحة
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // عرض النافذة
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
        
        // حذف النافذة بعد إغلاقها
        document.getElementById(modalId).addEventListener('hidden.bs.modal', function() {
            this.remove();
        });
    }

    // دالة مساعدة لتنظيف العدادات التنازلية عند مغادرة الصفحة
    window.addEventListener('beforeunload', function() {
        if (window.countdownInterval) {
            clearInterval(window.countdownInterval);
        }
    });

    // حساب أيام الرخصة تلقائياً
    function calculateLicenseDays() {
        const startDate = document.getElementById('license_start_date').value;
        const endDate = document.getElementById('license_end_date').value;
        const daysField = document.getElementById('license_days');
        
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const timeDiff = end.getTime() - start.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // +1 لتشمل اليوم الأخير
            
            if (daysDiff > 0) {
                daysField.value = daysDiff;
            } else {
                daysField.value = '';
                toastr.warning('تاريخ النهاية يجب أن يكون بعد تاريخ البداية');
            }
        } else {
            daysField.value = '';
        }
    }

    // تشغيل العد التنازلي عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        // تحميل رخص الحفر مع العد التنازلي
        loadDigLicenses();
        
        // إضافة مستمعي الأحداث لحساب أيام الرخصة
        const startDateInput = document.getElementById('license_start_date');
        const endDateInput = document.getElementById('license_end_date');
        
        if (startDateInput) {
            startDateInput.addEventListener('change', calculateLicenseDays);
        }
        
        if (endDateInput) {
            endDateInput.addEventListener('change', calculateLicenseDays);
        }
    });



      // دالة للحصول على badge نوع الرخصة
    function getLicenseTypeBadge(type) {
        switch(type) {
            case 'طوارئ':
                return '<span class="badge bg-danger">طوارئ</span>';
            case 'مشروع':
                return '<span class="badge bg-info">مشروع</span>';
            case 'عادي':
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

    // دالة لتغيير حالة الرخصة (متاحة/ملغاة)
    function toggleLicenseStatus(licenseId, isCancelled) {
        const statusText = isCancelled ? 'إلغاء' : 'تفعيل';
        const confirmMessage = `هل أنت متأكد من ${statusText} هذه الرخصة؟`;
        
        if (!confirm(confirmMessage)) {
            return;
        }

        $.ajax({
            url: `/admin/licenses/${licenseId}/toggle-status`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            },
            data: JSON.stringify({
                is_cancelled: isCancelled
            }),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    // إعادة تحميل قائمة الرخص
                    loadDigLicenses();
                } else {
                    toastr.error(response.message || 'حدث خطأ أثناء تحديث حالة الرخصة');
                }
            },
            error: function(xhr) {
                console.error('خطأ:', xhr);
                toastr.error('حدث خطأ أثناء تحديث حالة الرخصة');
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

        // التحقق من وجود اختبارات
        if (testsArray.length === 0) {
            toastr.warning('لا توجد اختبارات لحفظها. يرجى إضافة اختبار واحد على الأقل');
            return;
        }

        // الحصول على اسم الرخصة المختارة
        const selectedLicenseName = licenseSelector.options[licenseSelector.selectedIndex].text;
        
        // إظهار رسالة تأكيد
        if (!confirm(`هل أنت متأكد من حفظ ${testsArray.length} اختبار في ${selectedLicenseName}؟`)) {
            return;
        }
        
        // إعداد البيانات للإرسال
        const labData = {
            work_order_id: {{ $workOrder->id }},
            license_id: licenseId,
            section_type: 'lab_tests',
            tests: testsArray.map(test => ({
                name: test.name,
                points: test.points,
                price: test.price,
                total: test.total,
                result: test.result,
                file_url: test.fileUrl,
                file_name: test.fileName
            }))
        };
        
        // تسجيل البيانات المرسلة للتحقق
        console.log('=== Lab Tests Data Being Sent ===');
        console.log(labData);
        console.log('==================================');
        
        // إظهار مؤشر التحميل
        const saveBtn = document.getElementById('save-lab-btn');
        if (saveBtn) {
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';
        }
        
        $.ajax({
            url: '{{ route("admin.licenses.save-section") }}',
            type: 'POST',
            data: JSON.stringify(labData),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Lab tests data saved successfully:', response);
                
                // حساب الإحصائيات
                const totalTests = testsArray.length;
                const passedTests = testsArray.filter(test => test.result === 'pass').length;
                const failedTests = testsArray.filter(test => test.result === 'fail').length;
                const totalAmount = testsArray.reduce((sum, test) => sum + test.total, 0);
                
                // toastr.success(`تم حفظ ${totalTests} اختبار بنجاح في ${selectedLicenseName}`); // معطل
                
                // إعادة تفعيل الزر
                if (saveBtn) {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>حفظ جميع بيانات المختبر';
                }
                
                // رسالة تفصيلية عن النتائج
                setTimeout(() => {
                    let statusMessage = '';
                    if (failedTests === 0 && passedTests > 0) {
                        statusMessage = `جميع الاختبارات ناجحة (${passedTests} اختبار) ✅`;
                    } else if (passedTests === 0 && failedTests > 0) {
                        statusMessage = `جميع الاختبارات راسبة (${failedTests} اختبار) ❌`;
                    } else if (passedTests > 0 && failedTests > 0) {
                        statusMessage = `نتائج مختلطة: ${passedTests} ناجح، ${failedTests} راسب ⚠️`;
                    } else {
                        statusMessage = 'تم حفظ الاختبارات بدون تحديد النتائج';
                    }
                    
                    // toastr.info(`${statusMessage}<br>إجمالي المبلغ: ${totalAmount.toFixed(2)} ريال<br>البيانات متاحة في تفاصيل الرخصة`, 'ملخص الاختبارات', {
                    //     timeOut: 8000,
                    //     extendedTimeOut: 3000,
                    //     allowHtml: true
                    // }); // معطل
                }, 1000);
                
                // إضافة ملخص مرئي
                showNewLabTestsSummary(totalTests, passedTests, failedTests, totalAmount);
            },
            error: function(xhr) {
                // إعادة تفعيل الزر
                if (saveBtn) {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>حفظ جميع بيانات المختبر';
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

    // دالة عرض ملخص الاختبارات الجديدة المحفوظة
    function showNewLabTestsSummary(totalTests, passedTests, failedTests, totalAmount) {
        let summaryHtml = `
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <h6 class="alert-heading">
                    <i class="fas fa-check-circle me-2"></i>تم حفظ اختبارات المختبر بنجاح
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <strong>إحصائيات الاختبارات:</strong>
                        <ul class="mb-2">
                            <li>إجمالي الاختبارات: <span class="badge bg-primary">${totalTests}</span></li>
                            <li>الاختبارات الناجحة: <span class="badge bg-success">${passedTests}</span></li>
                            <li>الاختبارات الراسبة: <span class="badge bg-danger">${failedTests}</span></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <strong>التفاصيل المالية:</strong>
                        <ul class="mb-2">
                            <li>إجمالي المبلغ: <span class="text-success fw-bold">${totalAmount.toFixed(2)} ريال</span></li>
                        </ul>
                        <strong>أسماء الاختبارات:</strong>
                        <ul class="mb-0 small">`;
        
        testsArray.forEach(test => {
            const resultIcon = test.result === 'pass' ? '✅' : '❌';
            summaryHtml += `<li>${resultIcon} ${test.name} (${test.total.toFixed(2)} ريال)</li>`;
        });
        
        summaryHtml += `
                        </ul>
                    </div>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        البيانات محفوظة ومتاحة في تفاصيل الرخصة
                    </small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // إدراج الملخص بعد نموذج المختبر
        const labForm = document.getElementById('labForm');
        const existingSummary = document.querySelector('.lab-tests-summary-alert');
        if (existingSummary) {
            existingSummary.remove();
        }
        
        const summaryDiv = document.createElement('div');
        summaryDiv.className = 'lab-tests-summary-alert';
        summaryDiv.innerHTML = summaryHtml;
        labForm.parentNode.insertBefore(summaryDiv, labForm.nextSibling);
        
        // التمرير للملخص
        setTimeout(() => {
            summaryDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 500);
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
                        `<a href="/storage/${violation.attachment_path}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-file me-1"></i>عرض المرفق
                        </a>` : 
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
                            <td>${violation.notes || '-'}</td>
                            <td>${attachmentCell}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
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
            timeout: 5000, // 5 ثوان timeout
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
                         success: function(response) {
                 // رسالة نجاح محسنة
                 toastr.success(response.message || `تم حفظ المخالفة بنجاح للرخصة: ${licenseNumber}`, 'نجح الحفظ', {
                     "closeButton": true,
                     "debug": false,
                     "newestOnTop": false,
                     "progressBar": true,
                     "positionClass": "toast-top-right",
                     "preventDuplicates": false,
                     "onclick": null,
                     "showDuration": "300",
                     "hideDuration": "1000",
                     "timeOut": "5000",
                     "extendedTimeOut": "1000",
                     "showEasing": "swing",
                     "hideEasing": "linear",
                     "showMethod": "fadeIn",
                     "hideMethod": "fadeOut"
                 });
                 
                 // إضافة المخالفة للجدول مباشرة بدلاً من إعادة التحميل
                 const tbody = document.getElementById('violations-table-body');
                 const violation = response.violation;
                 
                 // إزالة رسالة "لا توجد مخالفات" إذا كانت موجودة
                 if (tbody.innerHTML.includes('لا توجد مخالفات')) {
                     tbody.innerHTML = '';
                 }
                 
                 const violationDate = violation.violation_date ? 
                     new Date(violation.violation_date).toLocaleDateString('ar-SA') : '';
                 const dueDate = violation.payment_due_date ? 
                     new Date(violation.payment_due_date).toLocaleDateString('ar-SA') : '';
                 
                 const paymentStatusBadge = violation.payment_status == 1 ? 
                     '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>مسددة</span>' : 
                     '<span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>غير مسددة</span>';

                 const attachmentCell = violation.attachment_path ? 
                     `<a href="/storage/${violation.attachment_path}" target="_blank" class="btn btn-sm btn-outline-primary">
                         <i class="fas fa-file me-1"></i>عرض المرفق
                     </a>` : 
                     '<span class="text-muted">لا يوجد</span>';

                 const newRow = document.createElement('tr');
                 newRow.setAttribute('data-violation-id', violation.id);
                 newRow.innerHTML = `
                     <td>${tbody.children.length + 1}</td>
                     <td><strong class="text-primary">${violation.violation_number || ''}</strong></td>
                     <td>${violationDate}</td>
                     <td><span class="badge bg-info">${violation.violation_type || ''}</span></td>
                     <td>${violation.responsible_party || ''}</td>
                     <td>${violation.violation_description || '-'}</td>
                     <td><strong class="text-success">${formatCurrency(violation.violation_amount)}</strong></td>
                     <td>${dueDate}</td>
                     <td>${violation.payment_invoice_number || '-'}</td>
                     <td>${paymentStatusBadge}</td>
                     <td>${violation.notes || '-'}</td>
                     <td>${attachmentCell}</td>
                     <td>
                         <div class="btn-group btn-group-sm">
                             <button class="btn btn-outline-danger" onclick="deleteViolation(${violation.id})" title="حذف">
                                 <i class="fas fa-trash"></i>
                             </button>
                         </div>
                     </td>
                 `;
                 
                 tbody.appendChild(newRow);
                 
                 // تحديث الإجمالي
                 const currentTotal = parseFloat(document.getElementById('total-violations-amount').textContent.replace(/[^\d.-]/g, '') || 0);
                 const newTotal = currentTotal + parseFloat(violation.violation_amount || 0);
                 updateTotalAmount(newTotal);

                 // إعادة تفعيل زر الحفظ مع تأثير بصري للنجاح
                 const saveBtn = document.querySelector('button[onclick="saveViolationSection()"]');
                 if (saveBtn) {
                     saveBtn.disabled = false;
                     saveBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i>تم الحفظ بنجاح';
                     saveBtn.classList.remove('btn-danger');
                     saveBtn.classList.add('btn-success');
                     saveBtn.style.background = 'linear-gradient(45deg, #28a745, #20c997)';
                     
                     // إرجاع الزر لحالته الطبيعية بعد 3 ثواني
                     setTimeout(() => {
                         saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>حفظ المخالفة';
                         saveBtn.classList.remove('btn-success');
                         saveBtn.classList.add('btn-danger');
                         saveBtn.style.background = '';
                     }, 3000);
                 }
                 
                 // تحديث حالة النموذج لإظهار أنه تم الحفظ (بدون إعادة تعيين)
                 const form = document.getElementById('violationForm');
                 const formFields = form.querySelectorAll('input, select, textarea');
                 formFields.forEach(field => {
                     if (field.type !== 'file') { // لا نغير حقول الملفات
                         field.style.borderColor = '#28a745';
                         field.style.boxShadow = '0 0 0 0.2rem rgba(40, 167, 69, 0.25)';
                     }
                 });
                 
                 // إزالة التأثير بعد 3 ثواني
                 setTimeout(() => {
                     formFields.forEach(field => {
                         field.style.borderColor = '';
                         field.style.boxShadow = '';
                     });
                 }, 3000);
                 
                 // إظهار رسالة إضافية
                 setTimeout(() => {
                     toastr.success('تم حفظ المخالفة بنجاح! يمكنك الآن إضافة مخالفة أخرى أو المتابعة للأقسام الأخرى.', 'تم الحفظ بنجاح');
                 }, 1000);
                 
                 // عدم مسح النموذج - الاحتفاظ بالبيانات للمستخدم
                 // resetViolationForm();
             },
            error: function(xhr, status, error) {
                console.error('Error saving violation:', xhr, status, error);
                
                // معالجة أخطاء مختلفة
                if (status === 'timeout') {
                    toastr.error('انتهت مهلة الاتصال. يرجى المحاولة مرة أخرى.');
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    Object.values(errors).forEach(error => {
                        toastr.error(Array.isArray(error) ? error[0] : error);
                    });
                } else {
                    toastr.error(xhr.responseJSON?.message || 'حدث خطأ أثناء حفظ المخالفة. يرجى المحاولة مرة أخرى.');
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
                // تجميع كل الرخص المتاحة
                const allSelectors = {
                    'lab-license-selector': 'المختبر',
                    'evacuation-license-selector': 'الإخلاءات',
                    'violation-license-selector': 'المخالفات',
                    'extension-license-selector': 'التمديدات'
                };
                
                // مسح وتحديث كل القوائم
                Object.keys(allSelectors).forEach(selectorId => {
                    const selector = document.getElementById(selectorId);
                    if (selector) {
                        // حفظ الخيار الافتراضي
                        const defaultOption = selector.querySelector('option[value=""]');
                        selector.innerHTML = '';
                        if (defaultOption) {
                            selector.appendChild(defaultOption.cloneNode(true));
                        }
                        
                        // إضافة الرخص
                        response.licenses.forEach(license => {
                            const option = document.createElement('option');
                            option.value = license.id;
                            
                            // تحسين عرض معلومات الرخصة
                            let licenseInfo = [
                                `رخصة #${license.license_number}`,
                                license.license_type || 'غير محدد',
                                license.work_order_execution_status >= 7 ? '- منتهية' : ''
                            ];
                            
                            // إضافة معلومات إضافية للتمديدات
                            if (selectorId === 'extension-license-selector') {
                                // إضافة تاريخ انتهاء الرخصة الأصلية
                                if (license.license_end_date) {
                                    const endDate = new Date(license.license_end_date);
                                    const today = new Date();
                                    const isExpired = endDate < today;
                                    licenseInfo.push(`تنتهي: ${endDate.toLocaleDateString()}${isExpired ? ' (منتهية)' : ''}`);
                                }
                                
                                // إضافة معلومات التمديد إذا وجد
                                if (license.extension_end_date) {
                                    const extEndDate = new Date(license.extension_end_date);
                                    licenseInfo.push(`تمديد حتى: ${extEndDate.toLocaleDateString()}`);
                                }
                                
                                // إضافة قيمة الرخصة
                                if (license.license_value) {
                                    licenseInfo.push(`${parseFloat(license.license_value).toLocaleString()} ريال`);
                                }
                            }
                            
                            option.textContent = licenseInfo.join(' - ');
                            selector.appendChild(option);
                        });
                    }
                });
                
                // إذا كان هناك رخصة واحدة فقط، اختارها تلقائياً
                if (response.licenses.length === 1) {
                    const licenseId = response.licenses[0].id;
                    const licenseText = `رخصة #${response.licenses[0].license_number}`;
                    
                    [labSelector, evacuationSelector, violationSelector, extensionSelector].forEach(selector => {
                        if (selector) {
                            selector.value = licenseId;
                            // تشغيل دالة التحديد
                            if (selector.id === 'lab-license-selector') {
                                selectLabLicense();
                                // تأكد من تعيين القيمة في الحقل المخفي
                                const labLicenseIdField = document.getElementById('lab-license-id');
                                if (labLicenseIdField) {
                                    labLicenseIdField.value = licenseId;
                                }
                                toastr.success(`تم اختيار الرخصة الوحيدة تلقائياً: ${licenseText}`);
                            }
                            else if (selector.id === 'evacuation-license-selector') selectEvacuationLicense();
                            else if (selector.id === 'violation-license-selector') selectViolationLicense();
                            else if (selector.id === 'extension-license-selector') selectExtensionLicense();
                        }
                    });
                }
                // إذا كان هناك أكثر من رخصة، اختار الأحدث في قسم المختبر فقط
                else if (response.licenses.length > 1 && labSelector) {
                    const latestLicense = response.licenses[0]; // الرخص مرتبة بالتاريخ desc
                    const licenseId = latestLicense.id;
                    const licenseText = `رخصة #${latestLicense.license_number}`;
                    
                    labSelector.value = licenseId;
                    selectLabLicense();
                    const labLicenseIdField = document.getElementById('lab-license-id');
                    if (labLicenseIdField) {
                        labLicenseIdField.value = licenseId;
                    }
                    toastr.info(`تم اختيار أحدث رخصة تلقائياً: ${licenseText}`, 'اختيار تلقائي');
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
    
    console.log('selectLabLicense called. Selector value:', selector ? selector.value : 'selector not found');
    
    if (selector && selector.value) {
        if (licenseIdField) {
            licenseIdField.value = selector.value;
            console.log('License ID set to:', licenseIdField.value);
        } else {
            console.error('lab-license-id field not found!');
        }
        
        const selectedText = selector.options[selector.selectedIndex].text;
        if (displayElement) {
            displayElement.textContent = selectedText;
        }
        if (infoDiv) {
            infoDiv.style.display = 'block';
        }
        
        // تفعيل أزرار الحفظ للمختبر
        enableLabButtons();
        
        // إعادة تعيين الاختبارات والتحميل من الخادم
        testsArray = [];
        testIdCounter = 1;
        updateTestsTable();
        updateTestsSummary();
        
        // تحميل البيانات المحفوظة من الخادم للرخصة المختارة
        loadLabTestsFromServer(selector.value);
        
        // إظهار رسالة نجاح
        toastr.success(`تم اختيار الرخصة: ${selectedText}`);
    } else {
        if (licenseIdField) {
            licenseIdField.value = '';
        }
        if (infoDiv) {
            infoDiv.style.display = 'none';
        }
        
        // تعطيل أزرار الحفظ للمختبر
        disableLabButtons();
        
        // مسح البيانات عند عدم اختيار رخصة
        testsArray = [];
        testIdCounter = 1;
        updateTestsTable();
        updateTestsSummary();
    }
}

// دالة تفعيل أزرار المختبر
function enableLabButtons() {
    const buttons = [
        'save-lab-btn',
        'add-test-btn'
    ];
    
    // تفعيل الأزرار باستخدام onclick attribute
    const saveLabDetailsBtn = document.querySelector('button[onclick="saveAllLabDetails()"]');
    if (saveLabDetailsBtn) {
        saveLabDetailsBtn.disabled = false;
        saveLabDetailsBtn.classList.remove('btn-secondary');
        saveLabDetailsBtn.classList.add('btn-primary');
    }
    
    buttons.forEach(buttonId => {
        const button = document.getElementById(buttonId);
        if (button) {
            button.disabled = false;
            button.classList.remove('btn-secondary');
        }
    });
}

// دالة تعطيل أزرار المختبر
function disableLabButtons() {
    const buttons = [
        'save-lab-btn',
        'add-test-btn'
    ];
    
    // تعطيل الأزرار باستخدام onclick attribute
    const saveLabDetailsBtn = document.querySelector('button[onclick="saveAllLabDetails()"]');
    if (saveLabDetailsBtn) {
        saveLabDetailsBtn.disabled = true;
        saveLabDetailsBtn.classList.remove('btn-primary');
        saveLabDetailsBtn.classList.add('btn-secondary');
    }
    
    buttons.forEach(buttonId => {
        const button = document.getElementById(buttonId);
        if (button) {
            button.disabled = true;
            button.classList.add('btn-secondary');
        }
    });
}

// دالة اختيار الرخصة للإخلاءات - محسنة مع معالجة الأخطاء
function selectEvacuationLicense() {
    try {
        // التحقق من وجود العناصر المطلوبة
        const selector = document.getElementById('evacuation-license-selector');
        if (!selector) {
            console.error('عنصر محدد الرخصة غير موجود');
            return;
        }

        const elements = {
            licenseIdField: document.getElementById('evacuation-license-id'),
            infoDiv: document.getElementById('selected-evacuation-license-info'),
            displayElement: document.getElementById('evacuation-license-display'),
            formContainer: document.getElementById('evacuation-form-container'),
            noLicenseWarning: document.getElementById('no-license-warning'),
            tbody: document.getElementById('evacuationDataTable')?.getElementsByTagName('tbody')[0]
        };

        // التحقق من وجود جميع العناصر المطلوبة
        if (!elements.licenseIdField || !elements.infoDiv || !elements.displayElement || 
            !elements.formContainer || !elements.noLicenseWarning || !elements.tbody) {
            console.error('بعض العناصر المطلوبة غير موجودة في الصفحة');
            return;
        }

        if (selector.value) {
            // تفعيل النموذج
            elements.licenseIdField.value = selector.value;
            const selectedText = selector.options[selector.selectedIndex].text;
            elements.displayElement.textContent = selectedText;
            elements.infoDiv.style.display = 'block';
            
            // تفعيل النموذج والأزرار
            elements.formContainer.style.opacity = '1';
            elements.formContainer.style.pointerEvents = 'auto';
            elements.noLicenseWarning.style.display = 'none';
            
            // تفعيل جميع الأزرار
            enableEvacuationButtons();
            
            // تفعيل جدول التفاصيل الفنية للمختبر
            const labDetailsCard = document.getElementById('lab-details-card');
            if (labDetailsCard) {
                labDetailsCard.style.opacity = '1';
                labDetailsCard.style.pointerEvents = 'auto';
                // إضافة تأثير بصري لإظهار أن الجدول أصبح متاحاً
                labDetailsCard.classList.add('border-success');
            }
            
            // إظهار قسم المرفقات وإخفاء الـ placeholder
            const attachmentsSection = document.getElementById('evacuation-attachments-section');
            const attachmentsPlaceholder = document.getElementById('evacuation-attachments-placeholder');
            if (attachmentsSection) {
                attachmentsSection.style.display = 'block';
            }
            if (attachmentsPlaceholder) {
                attachmentsPlaceholder.style.display = 'none';
            }
            
            // تحميل بيانات الإخلاءات للرخصة المختارة
            loadEvacuationDataForLicense(selector.value);
            
            // إظهار رسالة نجاح
            toastr.success(`تم اختيار الرخصة: ${selectedText}`);
        } else {
            // تعطيل النموذج
            elements.licenseIdField.value = '';
            elements.infoDiv.style.display = 'none';
            
            // تعطيل النموذج والأزرار
            elements.formContainer.style.opacity = '0.5';
            elements.formContainer.style.pointerEvents = 'none';
            elements.noLicenseWarning.style.display = 'block';
            
            // تعطيل جميع الأزرار
            disableEvacuationButtons();
            
            // تعطيل جدول التفاصيل الفنية للمختبر
            const labDetailsCard = document.getElementById('lab-details-card');
            if (labDetailsCard) {
                labDetailsCard.style.opacity = '0.5';
                labDetailsCard.style.pointerEvents = 'none';
                labDetailsCard.classList.remove('border-success');
            }
            
            // إخفاء قسم المرفقات وإظهار الـ placeholder
            const attachmentsSection = document.getElementById('evacuation-attachments-section');
            const attachmentsPlaceholder = document.getElementById('evacuation-attachments-placeholder');
            if (attachmentsSection) {
                attachmentsSection.style.display = 'none';
            }
            if (attachmentsPlaceholder) {
                attachmentsPlaceholder.style.display = 'block';
            }
            
            // مسح الجدول عند عدم اختيار رخصة
            elements.tbody.innerHTML = `
                <tr id="no-evacuation-data-row">
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2 text-warning"></i>
                        <br><strong>يجب اختيار رخصة أولاً</strong>
                        <br><small>اختر رخصة من القائمة أعلاه لبدء إدخال بيانات الإخلاءات</small>
                    </td>
                </tr>
            `;
        }
            } catch (error) {
            console.error('حدث خطأ أثناء تحديد الرخصة:', error);
            toastr.error('حدث خطأ أثناء تحديد الرخصة');
        }
}

// دالة تفعيل أزرار الإخلاءات
function enableEvacuationButtons() {
    const buttons = [
        'add-evacuation-btn',
        'save-evacuation-btn', 
        'add-evac-street-btn',
        'save-evac-streets-btn'
    ];
    
    buttons.forEach(buttonId => {
        const button = document.getElementById(buttonId);
        if (button) {
            button.disabled = false;
            button.classList.remove('btn-secondary');
        }
    });
}

// دالة تعطيل أزرار الإخلاءات
function disableEvacuationButtons() {
    const buttons = [
        'add-evacuation-btn',
        'save-evacuation-btn', 
        'add-evac-street-btn',
        'save-evac-streets-btn'
    ];
    
    buttons.forEach(buttonId => {
        const button = document.getElementById(buttonId);
        if (button) {
            button.disabled = true;
            button.classList.add('btn-secondary');
        }
    });
}

// دالة مسح اختيار الرخصة
function clearEvacuationLicenseSelection() {
    const selector = document.getElementById('evacuation-license-selector');
    selector.value = '';
    selectEvacuationLicense();
    toastr.info('تم مسح اختيار الرخصة');
}

// دالة فلترة خيارات الرخص
function filterLicenseOptions() {
    const searchInput = document.getElementById('license-search-input');
    const selector = document.getElementById('extension-license-selector');
    const searchTerm = searchInput.value.toLowerCase().trim();
    
    // إظهار جميع الخيارات إذا كان البحث فارغ
    if (!searchTerm) {
        Array.from(selector.options).forEach(option => {
            option.style.display = '';
        });
        return;
    }
    
    // فلترة الخيارات بناءً على النص المدخل
    Array.from(selector.options).forEach(option => {
        if (option.value === '') {
            option.style.display = '';
            return;
        }
        
        const optionText = option.textContent.toLowerCase();
        if (optionText.includes(searchTerm)) {
            option.style.display = '';
        } else {
            option.style.display = 'none';
        }
    });
}

// دالة اختيار أحدث رخصة
function selectLatestLicense() {
    const selector = document.getElementById('extension-license-selector');
    const visibleOptions = Array.from(selector.options).filter(option => 
        option.value !== '' && option.style.display !== 'none'
    );
    
    if (visibleOptions.length > 0) {
        // اختيار أول خيار مرئي (الأحدث)
        selector.value = visibleOptions[0].value;
        selectExtensionLicense();
        toastr.success('تم اختيار أحدث رخصة');
    } else {
        toastr.warning('لا توجد رخص متاحة');
    }
}

// دالة فلترة الرخص السارية فقط
function selectActiveOnlyLicenses() {
    const selector = document.getElementById('extension-license-selector');
    let hasActiveFound = false;
    
    Array.from(selector.options).forEach(option => {
        if (option.value === '') {
            option.style.display = '';
            return;
        }
        
        const optionText = option.textContent.toLowerCase();
        // البحث عن الرخص غير المنتهية
        if (optionText.includes('منتهية') || optionText.includes('منتهي')) {
            option.style.display = 'none';
        } else {
            option.style.display = '';
            hasActiveFound = true;
        }
    });
    
    if (hasActiveFound) {
        toastr.info('يتم عرض الرخص السارية فقط');
    } else {
        toastr.warning('لا توجد رخص سارية');
    }
}

// دالة مسح البحث
function clearLicenseSearch() {
    const searchInput = document.getElementById('license-search-input');
    const selector = document.getElementById('extension-license-selector');
    
    searchInput.value = '';
    
    // إظهار جميع الخيارات
    Array.from(selector.options).forEach(option => {
        option.style.display = '';
    });
    
    // مسح الاختيار
    selector.value = '';
    selectExtensionLicense();
    
    toastr.info('تم مسح البحث وإعادة تعيين القائمة');
}

// دالة اختيار الرخصة للتمديد
function selectExtensionLicense() {
    const selector = document.getElementById('extension-license-selector');
    const licenseIdField = document.getElementById('extension-license-id');
    const infoDiv = document.getElementById('selected-extension-license-info');
    const displayElement = document.getElementById('extension-license-display');
    const addBtn = document.getElementById('add-extension-btn');
    
    // تحديث حالة الزر والمعلومات
    function updateUI(enabled, text = '') {
        // تحديث الحقل المخفي
        licenseIdField.value = enabled ? selector.value : '';
        
        // تحديث عرض المعلومات
        if (enabled && text) {
            displayElement.textContent = text;
            infoDiv.style.display = 'block';
        } else {
            infoDiv.style.display = 'none';
        }
        
        // تحديث حالة الزر
        addBtn.disabled = !enabled;
        addBtn.classList.toggle('btn-success', enabled);
        addBtn.classList.toggle('btn-secondary', !enabled);
        
        // إخفاء نموذج التمديد إذا لم يتم اختيار رخصة
        if (!enabled) {
            hideExtensionForm();
        }
    }
    
    if (selector.value) {
        const selectedOption = selector.options[selector.selectedIndex];
        const selectedText = selectedOption.text;
        
        // تحديث الواجهة
        updateUI(true, selectedText);
        
        // إظهار رسالة نجاح
        toastr.success('تم اختيار الرخصة بنجاح');
    } else {
        // إعادة تعيين الواجهة
        updateUI(false);
        
        // إظهار رسالة تحذير
        toastr.warning('يجب اختيار رخصة قبل إضافة التمديد');
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

    // تنظيف النموذج بالكامل قبل الإظهار
    const form = document.getElementById('extensionForm');
    if (form) {
        form.reset();
        
        // تنظيف جميع الحقول يدوياً عدا work_order_id
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.name !== 'work_order_id') {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = false;
                } else if (input.type === 'file') {
                    input.value = '';
                } else {
                    input.value = '';
                }
            }
        });
        
        // تنظيف عرض الأيام
        const extensionDaysDisplay = document.getElementById('extension-days-display');
        if (extensionDaysDisplay) {
            extensionDaysDisplay.value = '';
        }
        
        console.log('Extension form cleared before showing');
    }

    formCard.style.display = 'block';
    formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
    
    // تعيين التاريخ الافتراضي (اليوم لتاريخ البداية)
    const today = new Date().toISOString().split('T')[0];
    const startDateInput = document.getElementById('extension-start-date');
    if (startDateInput) {
        startDateInput.value = today;
    }
    
    // إضافة مستمعي الأحداث لحساب الأيام
    const endDateInput = document.getElementById('extension-end-date');
    
    if (startDateInput) {
        startDateInput.removeEventListener('change', calculateExtensionDays);
        startDateInput.addEventListener('change', calculateExtensionDays);
    }
    if (endDateInput) {
        endDateInput.removeEventListener('change', calculateExtensionDays);
        endDateInput.addEventListener('change', calculateExtensionDays);
    }
}

// دالة إخفاء نموذج التمديد
function hideExtensionForm() {
    const formCard = document.getElementById('extension-form-card');
    const form = document.getElementById('extensionForm');
    
    formCard.style.display = 'none';
    
    // إعادة تعيين النموذج بالكامل
    if (form) {
        form.reset();
        
        // تنظيف جميع الحقول يدوياً
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.type === 'checkbox' || input.type === 'radio') {
                input.checked = false;
            } else if (input.type === 'file') {
                input.value = '';
            } else {
                input.value = '';
            }
        });
        
        // تنظيف الحقول المخفية
        const extensionLicenseId = document.getElementById('extension-license-id');
        if (extensionLicenseId) {
            extensionLicenseId.value = '';
        }
        
        // تنظيف عرض الأيام
        const extensionDaysDisplay = document.getElementById('extension-days-display');
        if (extensionDaysDisplay) {
            extensionDaysDisplay.value = '';
        }
        
        // إعادة تعيين اختيار الرخصة
        const licenseSelector = document.getElementById('extension-license-selector');
        if (licenseSelector) {
            licenseSelector.value = '';
        }
        
        // إخفاء معلومات الرخصة المختارة
        const licenseInfo = document.getElementById('selected-extension-license-info');
        if (licenseInfo) {
            licenseInfo.style.display = 'none';
        }
        
        // مسح حقل البحث
        const searchInput = document.getElementById('license-search-input');
        if (searchInput) {
            searchInput.value = '';
        }
        
        // إظهار جميع خيارات الرخص
        if (licenseSelector) {
            Array.from(licenseSelector.options).forEach(option => {
                option.style.display = '';
            });
        }
        
        // تعطيل زر الإضافة
        const addBtn = document.getElementById('add-extension-btn');
        if (addBtn) {
            addBtn.disabled = true;
        }
        
        console.log('Extension form has been completely reset');
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
            
            // إخفاء النموذج وإعادة تعيينه بالكامل
            hideExtensionForm();
            
            // تنظيف إضافي للتأكد من عدم بقاء أي بيانات
            setTimeout(() => {
                const form = document.getElementById('extensionForm');
                if (form) {
                    form.reset();
                    
                    // التأكد من تنظيف جميع الحقول
                    const allInputs = form.querySelectorAll('input, select, textarea');
                    allInputs.forEach(input => {
                        if (input.type !== 'hidden' || input.name !== 'work_order_id') {
                            input.value = '';
                        }
                    });
                    
                    console.log('Form completely cleared after successful save');
                }
            }, 100);
            
            // تحديث جدول التمديدات فوراً
            setTimeout(() => {
                loadExtensions();
            }, 500);
            
            // إشعار بنجاح العملية
            setTimeout(() => {
                toastr.info('يمكنك إضافة تمديد جديد الآن');
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
                    <td colspan="9" class="text-center text-muted">
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
                
                // معالجة المرفقات
                const attachments = [];
                if (extension.attachments && extension.attachments.length > 0) {
                    extension.attachments.forEach((attachment, attachIndex) => {
                        const fileName = attachment.path ? attachment.path.split('/').pop() : `مرفق ${attachIndex + 1}`;
                        attachments.push(`<a href="${attachment.url}" target="_blank" class="btn btn-outline-primary btn-sm me-1 mb-1" title="${fileName}"><i class="fas fa-file-pdf"></i></a>`);
                    });
                }
                
                // معالجة النص المحدود للملاحظات
                let reasonText = extension.reason || 'لا توجد ملاحظات';
                if (reasonText.length > 50) {
                    reasonText = reasonText.substring(0, 50) + '...';
                }
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="text-center">
                        <span class="badge bg-secondary">${index + 1}</span>
                    </td>
                    <td class="text-center">
                        <strong class="text-primary">رخصة #${extension.license_number || 'غير محدد'}</strong>
                    </td>
                    <td class="text-center">
                        <strong class="text-success">${formatCurrency(extension.extension_value || 0)}</strong>
                    </td>
                    <td class="text-center">
                        <small class="text-info">
                            <i class="fas fa-calendar-alt me-1"></i>${startDate || 'غير محدد'}
                        </small>
                    </td>
                    <td class="text-center">
                        <small class="text-danger">
                            <i class="fas fa-calendar-check me-1"></i>${endDate || 'غير محدد'}
                        </small>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-clock me-1"></i>${extensionDays} يوم
                        </span>
                    </td>
                    <td class="text-start">
                        <small class="text-muted" title="${extension.reason || 'لا توجد ملاحظات'}" style="cursor: help;">
                            <i class="fas fa-comment-dots me-1"></i>${reasonText}
                        </small>
                    </td>
                    <td class="text-center">
                        <div class="d-flex flex-column gap-1">
                            ${attachments.join('') || '<small class="text-muted">لا توجد مرفقات</small>'}
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-info" onclick="viewExtension(${extension.id})" title="عرض التفاصيل">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-danger" onclick="deleteExtension(${extension.id})" title="حذف التمديد">
                                <i class="fas fa-trash-alt"></i>
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
            reason: 'تمديد لاستكمال الأعمال المطلوبة',
            attachments: [
                { url: '/path/to/license.pdf', path: 'licenses/license.pdf' },
                { url: '/path/to/payment.pdf', path: 'payments/payment.pdf' }
            ]
        },
        {
            id: 2,
            license_number: 'L002',
            extension_value: 7500,
            extension_start_date: '2024-02-01',
            extension_end_date: '2024-03-15',
            extension_days: 43,
            reason: 'تمديد بسبب الظروف الجوية',
            attachments: [
                { url: '/path/to/bank.pdf', path: 'banks/bank.pdf' }
            ]
        }
    ];
    
    sampleExtensions.forEach((extension, index) => {
        const startDate = new Date(extension.extension_start_date).toLocaleDateString('en-GB');
        const endDate = new Date(extension.extension_end_date).toLocaleDateString('en-GB');
        
        // معالجة المرفقات
        const attachments = [];
        if (extension.attachments && extension.attachments.length > 0) {
            extension.attachments.forEach((attachment, attachIndex) => {
                const fileName = attachment.path ? attachment.path.split('/').pop() : `مرفق ${attachIndex + 1}`;
                attachments.push(`<a href="${attachment.url}" target="_blank" class="btn btn-outline-primary btn-sm me-1 mb-1" title="${fileName}"><i class="fas fa-file-pdf"></i></a>`);
            });
        }
        
        // معالجة النص المحدود للملاحظات
        let reasonText = extension.reason || 'لا توجد ملاحظات';
        if (reasonText.length > 50) {
            reasonText = reasonText.substring(0, 50) + '...';
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
            <td>
                <span class="text-muted" title="${extension.reason || 'لا توجد ملاحظات'}">
                    ${reasonText}
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
            
            // عرض رسالة خطأ مناسبة
            if (xhr.status === 404) {
                toastr.error('التمديد غير موجود');
            } else if (xhr.status === 403) {
                toastr.error('ليس لديك صلاحية لحذف هذا التمديد');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                toastr.error(xhr.responseJSON.message);
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
                        
                        إدارة الجودة والرخص
                    </h1>
                    
                </div>
                
                <div class="col-lg-4 text-end">
                    <!-- <a href="{{ route('admin.licenses.display', ['project' => $project ?? 'riyadh']) }}" class="btn btn-light btn-lg ms-1">
                        
                         تفاصيل الرخص 
                    </a> -->
                    <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-success">
                            <i class="fas fa-arrow-right"></i> عودة الي تفاصيل أمر العمل  
                        </a> 
                </div>
            </div>
        </div>
        <div class="row align-items-center">
                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-hashtag text-primary me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">رقم أمر العمل</small>
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
                                            @case(1)
                                                جاري العمل بالموقع
                                                @break
                                            @case(8)
                                                جاري تسليم 155
                                                @break
                                            @case(2)
                                                تم تسليم 155
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
                                            @case(9)
                                                إلغاء امر العمل
                                                @break
                                            @default
                                                غير محدد
                                        @endswitch
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- مفتاح حالة الرخص -->
                    <div class="row align-items-center mt-4">
                        <div class="col-12 text-center">
                            <div class="license-status-container p-3 rounded-3" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="license-status-indicator" id="licenseStatusIndicator">
                                        <div class="d-flex align-items-center">
                                            
                                            <div class="toggle-switch d-flex align-items-center p-2 rounded-pill" style="background: rgba(255,255,255,0.2);">
                                                <i class="fas fa-lock toggle-icon me-2 fs-5" id="toggleIcon" style="color: #ff6b6b;"></i>
                                                <span class="toggle-text fw-bold" id="toggleText" style="color: #ff6b6b;">مقفل - لا توجد رخص</span>
                                            </div>
                                        </div>
                                    </div>
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
                        <div class="col-md-3">
                            <label class="form-label fw-bold">سبب الحظر</label>
                            <input type="text" class="form-control" name="restriction_reason" id="restriction_reason" placeholder="اذكر سبب الحظر...">
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
                                            <th>رقم شهادة التنسيق</th>
                                            <th>رقم الرخصة</th>
                                            <th>تاريخ الإصدار</th>
                                            <th>نوع الرخصة</th>
                                            <th>قيمة الرخصة</th>
                                            <th>أبعاد الحفر</th>
                                            <th>فترة الرخصة</th>
                                            <th>العد التنازلي للرخصة</th>
                                            <th>المرفقات</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dig-licenses-table-body">
                                        <tr>
                                            <td colspan="11" class="text-center">لا توجد رخص حفر</td>
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
                                                <div class="col-md-12">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-search me-2"></i>اختر الرخصة المراد تمديدها:
                                                    </label>
                                                    
                                                    <!-- حقل البحث -->
                                                    <div class="mb-2">
                                                        <input type="text" 
                                                               class="form-control" 
                                                               id="license-search-input" 
                                                               placeholder="ابحث برقم الرخصة أو نوعها..."
                                                               onkeyup="filterLicenseOptions()">
                                                    </div>
                                                    
                                                    <!-- قائمة الرخص -->
                                                    <select class="form-select" id="extension-license-selector" onchange="selectExtensionLicense()" size="4">
                                                        <option value="">-- اختر الرخصة للتمديد --</option>
                                                    </select>
                                                    
                                                    <!-- أزرار سريعة -->
                                                    <div class="mt-2 d-flex gap-2 flex-wrap">
                                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectLatestLicense()">
                                                            <i class="fas fa-clock me-1"></i>الأحدث
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-info" onclick="selectActiveOnlyLicenses()">
                                                            <i class="fas fa-filter me-1"></i>السارية فقط
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearLicenseSearch()">
                                                            <i class="fas fa-times me-1"></i>مسح البحث
                                                        </button>
                                                    </div>
                                                    
                                                    <div class="mt-2">
                                                        <small class="text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            يجب اختيار رخصة قبل إدخال بيانات التمديد. استخدم البحث لسهولة الوصول للرخصة المطلوبة.
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
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
                                                    <i class="fas fa-sticky-note me-2"></i> الملاحظات
                                                </label>
                                                <textarea class="form-control" name="extension_reason" rows="3" placeholder="أذكر سبب التمديد أو أي ملاحظات إضافية..."></textarea>
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
                                        <table class="table table-bordered table-hover table-sm" id="extensionsTable">
                                            <thead class="table-warning">
                                                <tr>
                                                    <th class="text-center" style="width: 5%;">#</th>
                                                    <th class="text-center" style="width: 15%;">رقم الرخصة</th>
                                                    <th class="text-center" style="width: 12%;">قيمة التمديد</th>
                                                    <th class="text-center" style="width: 12%;">تاريخ البداية</th>
                                                    <th class="text-center" style="width: 12%;">تاريخ النهاية</th>
                                                    <th class="text-center" style="width: 10%;">المدة</th>
                                                    <th class="text-center" style="width: 20%;">الملاحظات</th>
                                                    <th class="text-center" style="width: 8%;">المرفقات</th>
                                                    <th class="text-center" style="width: 6%;">الإجراءات</th>
                                                </tr>
                                            </thead>
                                            <tbody id="extensions-table-body">
                                                <tr id="no-extensions-row">
                                                    <td colspan="9" class="text-center text-muted">
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
                        
                        <!-- تنبيه الحفظ التلقائي -->
                       
                        
                        <!-- نافذة إضافة اختبار جديد -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0 d-flex justify-content-between align-items-center">
                                            <span>
                                                <i class="fas fa-plus-circle me-2"></i>
                                                إضافة اختبار جديد
                                            </span>
                                            
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold">اسم الاختبار</label>
                                                <input type="text" class="form-control" id="test_name" placeholder="أدخل اسم الاختبار">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label fw-bold">عدد النقاط</label>
                                                <input type="number" class="form-control" id="test_points" step="0.01" placeholder="0.00" onchange="calculateTotal()">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label fw-bold">السعر (ريال)</label>
                                                <input type="number" class="form-control" id="test_price" step="0.01" placeholder="0.00" onchange="calculateTotal()">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label fw-bold">الإجمالي</label>
                                                <input type="number" class="form-control bg-light" id="test_total" readonly placeholder="0.00">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label fw-bold">النتيجة</label>
                                                <select class="form-select" id="test_result">
                                                    <option value="">-- اختر --</option>
                                                    <option value="pass">ناجح</option>
                                                    <option value="fail">راسب</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label fw-bold">المرفق</label>
                                                <input type="file" class="form-control" id="test_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12 text-center">
                                                <button type="button" class="btn btn-success" onclick="addTestToTable()">
                                                    <i class="fas fa-plus me-2"></i>إضافة الاختبار
                                                </button>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- جدول الاختبارات -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0 d-flex justify-content-between align-items-center">
                                            <span>
                                                <i class="fas fa-table me-2"></i>
                                                جدول الاختبارات المضافة
                                            </span>
                                            
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="testsTable">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="width: 5%">#</th>
                                                        <th style="width: 25%">اسم الاختبار</th>
                                                        <th style="width: 12%">عدد النقاط</th>
                                                        <th style="width: 12%">السعر (ريال)</th>
                                                        <th style="width: 12%">الإجمالي (ريال)</th>
                                                        <th style="width: 10%">النتيجة</th>
                                                        <th style="width: 12%">المرفق</th>
                                                        <th style="width: 12%">الإجراءات</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="testsTableBody">
                                                    <tr id="no-tests-row">
                                                        <td colspan="8" class="text-center text-muted py-4">
                                                            <i class="fas fa-flask fa-3x mb-3"></i>
                                                            <br>لا توجد اختبارات مضافة بعد
                                                            <br><small>استخدم النموذج أعلاه لإضافة اختبار جديد</small>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ملخص الاختبارات -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-chart-pie me-2"></i>
                                            ملخص الاختبارات
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-6">
                                                <div class="bg-success bg-gradient text-white p-4 rounded shadow-sm">
                                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                                        <i class="fas fa-check-circle fa-2x me-3"></i>
                                                        <h3 class="mb-0" id="passed_tests_amount">0.00</h3>
                                                        <span class="ms-2">ريال</span>
                                                    </div>
                                                    <strong>إجمالي الاختبارات الناجحة</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="bg-danger bg-gradient text-white p-4 rounded shadow-sm">
                                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                                        <i class="fas fa-times-circle fa-2x me-3"></i>
                                                        <h3 class="mb-0" id="failed_tests_amount">0.00</h3>
                                                        <span class="ms-2">ريال</span>
                                                    </div>
                                                    <strong>إجمالي الاختبارات الراسبة</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                    <!-- اختيار الرخصة للإخلاءات - محسن -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-danger shadow-lg">
                               
                                <div class="card-body bg-light">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                           
                                            <select class="form-select form-select-lg border-danger" id="evacuation-license-selector" onchange="selectEvacuationLicense()">
                                                <option value="">--  يجب اختيار رخصة أولاً قبل إدخال بيانات الاخلاء و الفسح --</option>
                                            </select>
                                            <div class="mt-2">
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="p-3 bg-white rounded border">
                                                <i class="fas fa-certificate fa-3x text-muted mb-2"></i>
                                                <p class="mb-0 text-muted">اختر الرخصة لبدء العمل</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- معلومات الرخصة المختارة -->
                                    <div id="selected-evacuation-license-info" class="mt-3" style="display: none;">
                                        <div class="alert alert-success border-success">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <h6 class="alert-heading mb-2">
                                                        <i class="fas fa-check-circle me-2"></i>
                                                        تم اختيار الرخصة بنجاح
                                                    </h6>
                                                    <p class="mb-0">
                                                        <strong>الرخصة المختارة:</strong> 
                                                        <span class="badge bg-success fs-6" id="evacuation-license-display"></span>
                                                    </p>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- نموذج الإخلاءات - معطل حتى يتم اختيار رخصة -->
                    <div id="evacuation-form-container" style="opacity: 0.5; pointer-events: none;">
                        <form id="evacuationForm">
                            @csrf
                            <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                            <input type="hidden" name="license_id" id="evacuation-license-id" value="">
                            
                            <!-- رسالة تنبيه عند عدم اختيار رخصة -->
                            <div id="no-license-warning" class="alert alert-warning mb-4">
                                <div class="text-center py-3">
                                    <h5 class="text-warning mb-2">يجب اختيار رخصة أولاً</h5>
                                </div>
                            </div>

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
                                <button type="button" class="btn btn-success btn-sm" onclick="addNewEvacuationRow()" id="add-evacuation-btn" disabled>
                                    <i class="fas fa-plus me-1"></i>
                                    إضافة بيانات إخلاء جديدة
                                </button>
                            </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="evacuationDataTable">
                                <thead class="table-success">
                                    <tr>
                                        <th class="text-center" style="min-width: 60px;">
                                            <i class="fas fa-hashtag"></i>
                                            #
                                        </th>
                                        <th class="text-center" style="min-width: 120px;">
                                            <i class="fas fa-check-circle"></i>
                                            تم الإخلاء؟
                                        </th>
                                        <th class="text-center" style="min-width: 120px;">
                                            <i class="fas fa-calendar-alt"></i>
                                            تاريخ الإخلاء
                                        </th>
                                        <th class="text-center" style="min-width: 120px;">
                                            <i class="fas fa-money-bill-wave"></i>
                                            مبلغ الإخلاء (ريال)
                                        </th>
                                        <th class="text-center" style="min-width: 150px;">
                                            <i class="fas fa-clock"></i>
                                            تاريخ ووقت الإخلاء
                                        </th>
                                        <th class="text-center" style="min-width: 130px;">
                                            <i class="fas fa-receipt"></i>
                                            رقم سداد الإخلاء
                                        </th>
                                        <th class="text-center" style="min-width: 200px;">
                                            <i class="fas fa-file"></i>
                                            رقم الفسح 
                                        </th>
                                        <th class="text-center" style="min-width: 200px;">
                                            <i class="fas fa-sticky-note"></i>
                                            ملاحظات
                                        </th>
                                        <th class="text-center" style="min-width: 120px;">
                                            <i class="fas fa-cogs me-1"></i>
                                            الإجراءات 
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="no-evacuation-data-row">
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="fas fa-truck fa-2x mb-2"></i>
                                            <br>لا توجد بيانات إخلاء مسجلة
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="table-success" style="display: table-footer-group !important;">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold" style="background-color: #d1ecf1; color: #0c5460;">
                                            <i class="fas fa-calculator me-2"></i>
                                            إجمالي مبلغ الإخلاء:
                                        </td>
                                        <td class="text-center fw-bold" id="total-evacuation-amount" style="background-color: #b8daff; color: #004085; font-size: 1.1em;">
                                            0.00 ريال
                                        </td>
                                        <td colspan="4" style="background-color: #d1ecf1;"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- حقل معرف الرخصة المخفي -->
                        <input type="hidden" name="license_id" id="evacuation-license-id" value="">
                        <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">

                            <!-- زر الحفظ الموحد -->
                            <div class="d-flex justify-content-center mb-3">
                                <button type="button" class="btn btn-primary btn-lg" onclick="saveAllEvacuationData()" id="save-evacuation-btn" disabled>
                                    <i class="fas fa-save me-2"></i>
                                    حفظ  بيانات الإخلاء
                                </button>
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
                            <button type="button" class="btn btn-success btn-sm" onclick="addNewEvacStreetRow()" id="add-evac-street-btn" disabled>
                                <i class="fas fa-plus me-1"></i>
                                إضافة صف جديد
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="saveAllEvacStreets()" id="save-evac-streets-btn" disabled>
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
                                                    <div class="card mb-4 shadow-lg border-0" id="lab-details-card" style="opacity: 0.5; pointer-events: none;">
                            <div class="card-header bg-gradient-success text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-light" onclick="addNewLabDetailsRow()">
                                            <i class="fas fa-plus me-1"></i>إضافة صف
                                        </button>
                                        <button type="button" class="btn btn-outline-light" onclick="saveAllLabDetails()">
                                            <i class="fas fa-save me-1"></i>حفظ جميع البيانات
                                        </button>
                                        <small class="text-white-50 ms-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            يجب اختيار رخصة من الأعلى أولاً
                                        </small>
                                        
                                        
                                        
                                    </div>
                                    <h5 class="mb-0 fw-bold">
                                        <i class="fas fa-flask me-2"></i>
                                        جدول التفاصيل الفنية للمختبر
                                    </h5>
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
                                                <td colspan="15" class="text-center text-muted py-3">
                                                    <br><strong>لا توجد بيانات اختبارات </strong>
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
                    </div> <!-- End evacuation-form-container -->
                    
                    <!-- رسالة عدم اختيار رخصة للمرفقات -->
                    <div class="row mt-5" id="evacuation-attachments-placeholder">
                        <div class="col-12">
                            <div class="card border-secondary">
                                <div class="card-body text-center py-5">
                                    <i class="fas fa-paperclip fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">مرفقات الإخلاءات</h5>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-info-circle me-1"></i>
                                        يجب اختيار رخصة أولاً لتتمكن من رفع المرفقات
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- قسم مرفقات الإخلاءات -->
                    <div class="row mt-5" id="evacuation-attachments-section" style="display: none;">
                        <div class="col-12">
                            <div class="card border-info shadow">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-paperclip me-2"></i>
                                        مرفقات الإخلاءات والتعهدات
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <!-- رفع المرفقات -->
                                    <div class="row mb-4">
                                        <div class="col-md-8">
                                            <label for="evacuation-attachments" class="form-label fw-bold">
                                                <i class="fas fa-upload me-1"></i>رفع مرفقات الإخلاءات
                                            </label>
                                            <input type="file" class="form-control" id="evacuation-attachments" 
                                                   multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" 
                                                   onchange="handleEvacuationAttachments(this.files)">
                                            <small class="text-muted">
                                                يمكن رفع ملفات PDF, Word, أو صور (حد أقصى 10 ملفات لكل مرة)
                                            </small>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-success w-100 mt-4" 
                                                    onclick="uploadEvacuationAttachments()" id="upload-evacuation-btn" disabled>
                                                <i class="fas fa-cloud-upload-alt me-2"></i>
                                                رفع المرفقات
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- عرض المرفقات المرفوعة -->
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="text-primary mb-3">
                                                <i class="fas fa-folder-open me-1"></i>
                                                المرفقات المرفوعة
                                            </h6>
                                            <div id="evacuation-attachments-list" class="border rounded p-3 bg-light min-height-100">
                                                <div class="text-center text-muted py-3" id="no-evacuation-attachments">
                                                    <i class="fas fa-file-upload fa-2x mb-2"></i>
                                                    <br>لا توجد مرفقات مرفوعة حتى الآن
                                                    <br><small>استخدم الحقل أعلاه لرفع المرفقات</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                                <th>الملاحظات</th>
                                                <th>المرفقات</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody id="violations-table-body">
                                            <tr>
                                                <td colspan="13" class="text-center">لا توجد مخالفات</td>
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
                                                <td colspan="6"></td>
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
    try {
        console.log('saveDigLicenseSection function called!');
        
        // منع الإرسال العادي للنموذج
        if (event) {
            event.preventDefault();
        }
        
        const form = document.getElementById('digLicenseForm');
        if (!form) {
            toastr.error('لم يتم العثور على نموذج رخصة الحفر');
            return false;
        }

    // التحقق من البيانات الأساسية
    const licenseNumber = form.querySelector('[name="license_number"]').value.trim();
    const licenseDate = form.querySelector('[name="license_date"]').value;
    const licenseType = form.querySelector('[name="license_type"]').value;
    
    if (!licenseNumber) {
        toastr.error('يرجى إدخال رقم الرخصة');
        form.querySelector('[name="license_number"]').focus();
        return false;
    }
    
    if (!licenseDate) {
        toastr.error('يرجى إدخال تاريخ إصدار الرخصة');
        form.querySelector('[name="license_date"]').focus();
        return false;
    }
    
    if (!licenseType) {
        toastr.error('يرجى اختيار نوع الرخصة');
        form.querySelector('[name="license_type"]').focus();
        return false;
    }

    const formData = new FormData(form);
    
    // إضافة معلومات القسم
    formData.set('section_type', 'dig_license');
    
    // إظهار loading على الزر
    const saveBtn = event && event.target ? event.target : document.querySelector('button[onclick="saveDigLicenseSection()"]');
    const originalText = saveBtn ? saveBtn.innerHTML : '';
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
            console.log('Save response:', response);
            
            // حساب عدد الملفات المرفوعة
            const fileInputs = form.querySelectorAll('input[type="file"]');
            let uploadedFiles = 0;
            fileInputs.forEach(input => {
                if (input.files && input.files.length > 0) {
                    uploadedFiles += input.files.length;
                }
            });
            
            let successMessage = 'تم حفظ رخصة الحفر بنجاح';
            if (uploadedFiles > 0) {
                successMessage += ` مع ${uploadedFiles} مرفق`;
            }
            
            // رسالة نجاح محسنة
            toastr.success(successMessage, 'نجح الحفظ', {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            });
            
            // إعادة تفعيل الزر مع تأثير بصري للنجاح
            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i>تم الحفظ بنجاح';
                saveBtn.classList.remove('btn-primary');
                saveBtn.classList.add('btn-success');
                saveBtn.style.background = 'linear-gradient(45deg, #28a745, #20c997)';
                
                // إرجاع الزر لحالته الطبيعية بعد 3 ثواني
                setTimeout(() => {
                    saveBtn.innerHTML = originalText;
                    saveBtn.classList.remove('btn-success');
                    saveBtn.classList.add('btn-primary');
                    saveBtn.style.background = '';
                }, 3000);
            }
            
            // تحديث حالة النموذج لإظهار أنه تم الحفظ (بدون إعادة تعيين)
            const formFields = form.querySelectorAll('input, select, textarea');
            formFields.forEach(field => {
                if (field.type !== 'file') { // لا نغير حقول الملفات
                    field.style.borderColor = '#28a745';
                    field.style.boxShadow = '0 0 0 0.2rem rgba(40, 167, 69, 0.25)';
                }
            });
            
            // إزالة التأثير بعد 3 ثواني
            setTimeout(() => {
                formFields.forEach(field => {
                    field.style.borderColor = '';
                    field.style.boxShadow = '';
                });
            }, 3000);
            
            // تحديث جدول رخص الحفر
            loadDigLicenses();
            
            // إظهار رسالة إضافية وإعادة تحميل الصفحة
            setTimeout(() => {
                toastr.success('تم حفظ رخصة الحفر بنجاح! سيتم تحديث الصفحة...', 'تم الحفظ بنجاح');
                
                // إعادة تحميل الصفحة بعد ثانية واحدة للتحديث السريع
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }, 1000);

            // عدم إغلاق النافذة - الاحتفاظ بها مفتوحة للمستخدم
            // const modal = bootstrap.Modal.getInstance(document.getElementById('addLicenseModal'));
            // if (modal) {
            //     modal.hide();
            // }
        },
        error: function(xhr) {
            console.error('Error saving dig license:', xhr);
            console.log('XHR Status:', xhr.status);
            console.log('XHR Response Text:', xhr.responseText);
            console.log('XHR Response JSON:', xhr.responseJSON);
            
            // إعادة تفعيل الزر
            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalText;
            }
            
            // عرض تفاصيل الخطأ بشكل أوضح
            let errorMessage = 'حدث خطأ في حفظ رخصة الحفر';
            
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                Object.values(errors).forEach(error => {
                    toastr.error(error[0]);
                });
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
                toastr.error(errorMessage);
            } else if (xhr.status === 500) {
                errorMessage = 'خطأ داخلي في الخادم (500) - يرجى مراجعة سجل الأخطاء';
                toastr.error(errorMessage);
            } else if (xhr.status === 422) {
                errorMessage = 'بيانات غير صحيحة (422) - يرجى التحقق من البيانات المدخلة';
                toastr.error(errorMessage);
            } else {
                errorMessage = `خطأ HTTP ${xhr.status}: ${xhr.statusText || 'خطأ غير محدد'}`;
                toastr.error(errorMessage);
            }
        }
    });
    
    return false;
    
    } catch (error) {
        console.error('Error in saveDigLicenseSection:', error);
        toastr.error('حدث خطأ في JavaScript: ' + error.message);
        return false;
    }
}

// دالة إعادة تعيين نموذج رخصة الحفر
function resetDigLicenseForm() {
    if (confirm('هل أنت متأكد من إعادة تعيين جميع البيانات؟ سيتم فقدان جميع البيانات المدخلة.')) {
        const form = document.getElementById('digLicenseForm');
        if (form) {
            form.reset();
            
            // إعادة تعيين حقل عدد الأيام
            document.getElementById('license_days').value = '';
            
            toastr.info('تم إعادة تعيين النموذج');
        } else {
            toastr.error('لم يتم العثور على النموذج');
        }
    }
}

    function saveEvacuationSection() {
        // استخدام الرخصة المرتبطة بأمر العمل
        const licenseId = @if($workOrder->license) {{ $workOrder->license->id }} @else null @endif;
        
        if (!licenseId) {
            toastr.warning('لا توجد رخصة مرتبطة بأمر العمل هذا');
            return;
        }

        // الحصول على اسم الرخصة المختارة
        const selectedLicenseName = '@if($workOrder->license) {{ $workOrder->license->license_number }} @else "غير محدد" @endif';
        
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
        <td><input type="number" class="form-control form-control-sm" name="lab_max_asphalt_density" step="0.01" placeholder="الكثافة القصوى"></td>
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
            return;
        }
        
        const labTable = document.getElementById('labDetailsTable');
        if (!labTable) {
            return;
        }
        
        const tbody = labTable.querySelector('tbody');
        if (!tbody) {
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
        }, 300);
    }
}

// دالة فحص حالة اختيار الرخصة
function checkLicenseSelection() {
    const licenseIdField = document.getElementById('lab-license-id');
    const licenseSelector = document.getElementById('lab-license-selector');
    
    let message = 'حالة اختيار الرخصة:\n\n';
    
    if (licenseIdField) {
        message += `• الحقل المخفي (lab-license-id): "${licenseIdField.value || 'فارغ'}"\n`;
    } else {
        message += '• الحقل المخفي (lab-license-id): غير موجود!\n';
    }
    
    if (licenseSelector) {
        message += `• القائمة المنسدلة (lab-license-selector): "${licenseSelector.value || 'فارغ'}"\n`;
        message += `• عدد الرخص في القائمة: ${licenseSelector.options.length - 1}\n`;
        if (licenseSelector.selectedIndex > 0) {
            message += `• النص المختار: "${licenseSelector.options[licenseSelector.selectedIndex].text}"\n`;
        }
    } else {
        message += '• القائمة المنسدلة (lab-license-selector): غير موجودة!\n';
    }
    
    // عرض المعلومات
    alert(message);
    console.log(message);
    
    // إصلاح تلقائي للمشكلة
    if (licenseSelector && licenseSelector.value && licenseIdField && !licenseIdField.value) {
        licenseIdField.value = licenseSelector.value;
        toastr.success('تم إصلاح مشكلة اختيار الرخصة تلقائياً');
    }
    // إذا كان كلاهما فارغ بس في رخص متاحة، اختار الأولى
    else if (licenseSelector && !licenseSelector.value && licenseSelector.options.length > 1) {
        // اختيار أول رخصة متاحة (تخطي الخيار الافتراضي)
        licenseSelector.selectedIndex = 1;
        selectLabLicense();
        toastr.info('تم اختيار أول رخصة متاحة تلقائياً');
    }
    // إذا مكانش في رخص متاحة خالص
    else if (licenseSelector && licenseSelector.options.length <= 1) {
        toastr.warning('لا توجد رخص متاحة لهذا أمر العمل. يجب إنشاء رخصة أولاً من تبويب الرخص.');
    }
}

// دالة إعادة تحميل الرخص
function reloadLicenses() {
    toastr.info('جاري تحديث قائمة الرخص...');
    loadLicensesForSelectors();
    
    // إعادة تحميل بعد ثانية للتأكد
    setTimeout(() => {
        const licenseSelector = document.getElementById('lab-license-selector');
        if (licenseSelector && licenseSelector.options.length > 1) {
            toastr.success(`تم تحديث قائمة الرخص - متاح ${licenseSelector.options.length - 1} رخصة`);
        } else {
            toastr.warning('لا توجد رخص متاحة لهذا أمر العمل');
        }
    }, 1000);
}

function saveAllLabDetails() {
    // استخدام الرخصة المختارة من قسم الإخلاءات (لأن الجدول موجود في قسم الإخلاءات)
    const evacuationLicenseIdField = document.getElementById('evacuation-license-id');
    const evacuationLicenseSelector = document.getElementById('evacuation-license-selector');
    
    // تحقق محسن من وجود الرخصة من قسم الإخلاءات
    let licenseId = null;
    let licenseSelector = null;
    
    if (evacuationLicenseIdField && evacuationLicenseIdField.value) {
        licenseId = evacuationLicenseIdField.value;
        licenseSelector = evacuationLicenseSelector;
    } else if (evacuationLicenseSelector && evacuationLicenseSelector.value) {
        // إذا كانت القيمة موجودة في القائمة المنسدلة بس مش في الحقل المخفي
        licenseId = evacuationLicenseSelector.value;
        licenseSelector = evacuationLicenseSelector;
        if (evacuationLicenseIdField) {
            evacuationLicenseIdField.value = licenseId; // تعيين القيمة
        }
    } else {
        // في حالة عدم وجود رخصة مختارة في قسم الإخلاءات، جرب قسم المختبر
        const labLicenseIdField = document.getElementById('lab-license-id');
        const labLicenseSelector = document.getElementById('lab-license-selector');
        
        if (labLicenseIdField && labLicenseIdField.value) {
            licenseId = labLicenseIdField.value;
            licenseSelector = labLicenseSelector;
        } else if (labLicenseSelector && labLicenseSelector.value) {
            licenseId = labLicenseSelector.value;
            licenseSelector = labLicenseSelector;
            if (labLicenseIdField) {
                labLicenseIdField.value = licenseId;
            }
        }
    }
    
    if (!licenseId) {
        // محاولة إصلاح المشكلة تلقائياً قبل الرفض
        if (licenseSelector && licenseSelector.value) {
            licenseId = licenseSelector.value;
            // تحديث الحقل المخفي المناسب
            if (evacuationLicenseIdField && licenseSelector === evacuationLicenseSelector) {
                evacuationLicenseIdField.value = licenseId;
            } else if (document.getElementById('lab-license-id') && licenseSelector === document.getElementById('lab-license-selector')) {
                document.getElementById('lab-license-id').value = licenseId;
            }
            toastr.success('تم إصلاح مشكلة اختيار الرخصة وسيتم المتابعة');
        } else if (licenseSelector && licenseSelector.options.length > 1) {
            // اختيار أول رخصة متاحة
            licenseSelector.selectedIndex = 1;
            licenseId = licenseSelector.value;
            // تحديث الحقل المخفي المناسب
            if (evacuationLicenseIdField && licenseSelector === evacuationLicenseSelector) {
                evacuationLicenseIdField.value = licenseId;
                selectEvacuationLicense(); // تشغيل دالة الاختيار للإخلاءات
            } else if (document.getElementById('lab-license-id') && licenseSelector === document.getElementById('lab-license-selector')) {
                document.getElementById('lab-license-id').value = licenseId;
                selectLabLicense(); // تشغيل دالة الاختيار للمختبر
            }
            toastr.success('تم اختيار أول رخصة متاحة تلقائياً وسيتم المتابعة');
        } else {
            toastr.error('لا توجد رخص متاحة لهذا أمر العمل. يجب إنشاء رخصة أولاً من تبويب الرخص.');
            console.log('Debug: evacuation license field value:', evacuationLicenseIdField ? evacuationLicenseIdField.value : 'field not found');
            console.log('Debug: evacuation license selector value:', evacuationLicenseSelector ? evacuationLicenseSelector.value : 'selector not found');
            console.log('Debug: licenseSelector value:', licenseSelector ? licenseSelector.value : 'selector not found');
            console.log('Debug: available options:', licenseSelector ? licenseSelector.options.length : 'selector not found');
            return;
        }
    }

    const labTable = document.getElementById('labDetailsTable');
    if (!labTable) {
        return;
    }

    const rows = labTable.querySelectorAll('tbody tr:not(#no-lab-details-row)');
    if (rows.length === 0) {
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
        
        // التحقق من وجود بيانات أساسية مطلوبة (على الأقل حقل واحد مملوء)
        const hasData = Object.keys(rowData).length > 0;
        if (hasData) {
            labDetailsData.push(rowData);
            hasValidData = true;
        }
    });
    
    if (!hasValidData) {
        toastr.warning('لا توجد بيانات صحيحة في جدول التفاصيل الفنية لحفظها');
        return;
    }
    
    // الحصول على اسم الرخصة المختارة
    const selectedLicenseName = licenseSelector.options[licenseSelector.selectedIndex].text;
    
    // إظهار رسالة تأكيد
    if (!confirm(`هل أنت متأكد من حفظ التفاصيل الفنية في الرخصة ${selectedLicenseName}؟`)) {
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
            section_type: 'lab_table2',
            data: labDetailsData
        },
        success: function(response) {
            if (response.success) {
                console.log('Lab Details saved successfully:', response);
                
                // رسالة نجاح مفصلة
                toastr.success(`تم حفظ ${labDetailsData.length} سجل من التفاصيل الفنية بنجاح في ${selectedLicenseName}`, 'نجح الحفظ', {
                    "closeButton": true,
                    "progressBar": true,
                    "timeOut": "5000"
                });
                
                // إعادة تفعيل الزر مع تأثير بصري للنجاح
                saveButton.disabled = false;
                saveButton.innerHTML = '<i class="fas fa-check-circle me-2"></i>تم الحفظ بنجاح';
                saveButton.classList.remove('btn-primary');
                saveButton.classList.add('btn-success');
                
                // إرجاع الزر لحالته الطبيعية بعد 3 ثواني
                setTimeout(() => {
                    saveButton.innerHTML = '<i class="fas fa-save me-2"></i>حفظ البيانات';
                    saveButton.classList.remove('btn-success');
                    saveButton.classList.add('btn-outline-light');
                }, 3000);
                
                // إظهار رسالة إضافية
                setTimeout(() => {
                    toastr.success('تم حفظ التفاصيل الفنية بنجاح! البيانات متاحة الآن في تفاصيل الرخصة.', 'تم الحفظ بنجاح');
                }, 1000);
                
            } else {
                console.error('Error saving Lab Details:', response.message || 'Unknown error');
                toastr.error(response.message || 'حدث خطأ غير معروف أثناء حفظ التفاصيل الفنية');
            }
        },
        error: function(xhr) {
            console.error('Error saving Lab Details:', xhr);
            const errors = xhr.responseJSON?.errors || {};
            if (Object.keys(errors).length > 0) {
                Object.values(errors).forEach(error => {
                    toastr.error(Array.isArray(error) ? error[0] : error);
                });
            } else {
                toastr.error('حدث خطأ أثناء حفظ التفاصيل الفنية. يرجى المحاولة مرة أخرى.');
            }
        }
    }).always(function() {
        // إعادة تعيين زر الحفظ فقط في حالة الخطأ
        // في حالة النجاح، الزر يتم تحديثه في success callback
        if (!saveButton.classList.contains('btn-success')) {
            // الزر ليس في حالة نجاح، إذن هناك خطأ
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;
        }
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
    // استخدام الرخصة المختارة من المستخدم في قسم الإخلاءات
    const licenseIdField = document.getElementById('evacuation-license-id');
    const licenseSelector = document.getElementById('evacuation-license-selector');
    
    if (!licenseIdField || !licenseIdField.value) {
        toastr.warning('يجب اختيار رخصة أولاً قبل حفظ جدول الفسح');
        return;
    }
    
    const licenseId = licenseIdField.value;

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
        toastr.warning('لا توجد بيانات في جدول الفسح لحفظها');
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
            console.log('Evac streets saved successfully:', response);
            
            // رسالة نجاح مفصلة
            toastr.success(`تم حفظ ${data.length} سجل من فسح الإخلاءات بنجاح في ${selectedLicenseName}`, 'نجح الحفظ', {
                "closeButton": true,
                "progressBar": true,
                "timeOut": "5000"
            });
            
            // إظهار رسالة إضافية
            setTimeout(() => {
                toastr.success('تم حفظ بيانات فسح الإخلاءات بنجاح! البيانات متاحة الآن في تفاصيل الرخصة.', 'تم الحفظ بنجاح');
            }, 1000);
        },
        error: function(xhr) {
            console.error('Error saving Evac streets:', xhr);
            const errors = xhr.responseJSON?.errors || {};
            if (Object.keys(errors).length > 0) {
                Object.values(errors).forEach(error => {
                    toastr.error(Array.isArray(error) ? error[0] : error);
                });
            } else {
                toastr.error('حدث خطأ أثناء حفظ فسح الإخلاءات. يرجى المحاولة مرة أخرى.');
            }
        }
    });
}

// ==================== دوال النظام الجديد للمختبر ====================

// متغير لتتبع الاختبارات المضافة
let testsArray = [];
let testIdCounter = 1;

// دالة حساب الإجمالي في النافذة
function calculateTotal() {
    const points = parseFloat(document.getElementById('test_points').value) || 0;
    const price = parseFloat(document.getElementById('test_price').value) || 0;
    const total = points * price;
    document.getElementById('test_total').value = total.toFixed(2);
}

// دالة إضافة اختبار إلى الجدول
function addTestToTable() {
    const testName = document.getElementById('test_name').value.trim();
    const testPoints = parseFloat(document.getElementById('test_points').value) || 0;
    const testPrice = parseFloat(document.getElementById('test_price').value) || 0;
    const testTotal = parseFloat(document.getElementById('test_total').value) || 0;
    const testResult = document.getElementById('test_result').value;
    const testFile = document.getElementById('test_file').files[0];
    
    // التحقق من البيانات المطلوبة
    if (!testName) {
        return;
    }
    
    if (testPoints <= 0) {
        return;
    }
    
    if (testPrice <= 0) {
        return;
    }
    
    if (!testResult) {
        return;
    }
    
    // إنشاء FormData لرفع الملف
    const formData = new FormData();
    let fileUrl = null;
    let fileName = null;
    
    if (testFile) {
        const licenseId = document.getElementById('lab-license-id').value;
        
        if (!licenseId) {
            return;
        }
        
        // إنشاء FormData لرفع الملف
        const formData = new FormData();
        formData.append('file', testFile);
        formData.append('license_id', licenseId);
        formData.append('test_field', 'general_test');
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        
        // عرض مؤشر التحميل
        const addButton = document.querySelector('button[onclick="addTestToTable()"]');
        const originalText = addButton.innerHTML;
        addButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري رفع الملف...';
        addButton.disabled = true;
        
        // رفع الملف أولاً
        $.ajax({
            url: '{{ route("admin.licenses.lab-test.upload-file") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    fileUrl = response.file_url;
                    fileName = response.file_name;
                    addTestWithFileData(testName, testPoints, testPrice, testTotal, testResult, fileUrl, fileName);
                } else {
                    addTestWithFileData(testName, testPoints, testPrice, testTotal, testResult, null, null);
                }
            },
            error: function(xhr) {
                // إضافة الاختبار بدون ملف في حالة فشل الرفع
                addTestWithFileData(testName, testPoints, testPrice, testTotal, testResult, null, null);
            }
        }).always(function() {
            // إعادة تعيين زر الإضافة
            addButton.innerHTML = originalText;
            addButton.disabled = false;
        });
    } else {
        addTestWithFileData(testName, testPoints, testPrice, testTotal, testResult, null, null);
    }
}

// دالة مساعدة لإضافة الاختبار مع بيانات الملف
function addTestWithFileData(testName, testPoints, testPrice, testTotal, testResult, fileUrl, fileName) {
    // إنشاء كائن الاختبار
    const testData = {
        id: testIdCounter++,
        name: testName,
        points: testPoints,
        price: testPrice,
        total: testTotal,
        result: testResult,
        fileUrl: fileUrl,
        fileName: fileName
    };
    
    // إضافة الاختبار للمصفوفة
    testsArray.push(testData);
    
    // تحديث الجدول
    updateTestsTable();
    
    // تحديث الملخص
    updateTestsSummary();
    
    // حفظ تلقائي على الخادم
    const licenseId = document.getElementById('lab-license-id').value;
    if (licenseId) {
        autoSaveTestsToServer(licenseId);
    }
    
    // مسح النموذج
    clearTestForm();
}

// دالة مسح النموذج
function clearTestForm() {
    document.getElementById('test_name').value = '';
    document.getElementById('test_points').value = '';
    document.getElementById('test_price').value = '';
    document.getElementById('test_total').value = '';
    document.getElementById('test_result').value = '';
    document.getElementById('test_file').value = '';
}

// دالة تحديث جدول الاختبارات
function updateTestsTable() {
    const tableBody = document.getElementById('testsTableBody');
    const noTestsRow = document.getElementById('no-tests-row');
    const licenseId = document.getElementById('lab-license-id').value;
    
    // التحقق من وجود رخصة مختارة
    if (!licenseId) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center text-muted py-4">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <br>الرجاء اختيار رخصة أولاً
                    <br><small>يجب اختيار رخصة قبل إضافة أي اختبارات</small>
                </td>
            </tr>
        `;
        return;
    }
    
    if (testsArray.length === 0) {
        // إظهار رسالة عدم وجود اختبارات
        if (noTestsRow) {
            noTestsRow.style.display = '';
        }
        // حذف جميع صفوف الاختبارات
        const testRows = tableBody.querySelectorAll('tr:not(#no-tests-row)');
        testRows.forEach(row => row.remove());
        return;
    }
    
    // إخفاء رسالة عدم وجود اختبارات
    if (noTestsRow) {
        noTestsRow.style.display = 'none';
    }
    
    // حذف جميع صفوف الاختبارات الموجودة
    const testRows = tableBody.querySelectorAll('tr:not(#no-tests-row)');
    testRows.forEach(row => row.remove());
    
    // إضافة صفوف الاختبارات الجديدة
    testsArray.forEach((test, index) => {
        const row = document.createElement('tr');
        
        // إنشاء أزرار المرفق
        let attachmentButtons = '';
        if (test.fileName && test.fileUrl) {
            attachmentButtons = `
                <div class="d-flex gap-1 flex-wrap">
                    <button type="button" class="btn btn-info btn-sm" onclick="viewAttachment('${test.fileUrl}', '${test.fileName}')" title="عرض المرفق">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button type="button" class="btn btn-success btn-sm" onclick="downloadAttachment('${test.fileUrl}', '${test.fileName}')" title="تحميل المرفق">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
                <small class="text-success d-block mt-1">
                    <i class="fas fa-check-circle me-1"></i>${test.fileName}
                </small>
            `;
        } else {
            attachmentButtons = '<span class="text-muted">لا توجد مرفقات</span>';
        }
        
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${test.name}</td>
            <td>${test.points}</td>
            <td>${test.price.toFixed(2)}</td>
            <td class="fw-bold">${test.total.toFixed(2)}</td>
            <td>
                <span class="badge ${test.result === 'pass' ? 'bg-success' : 'bg-danger'}">
                    <i class="fas ${test.result === 'pass' ? 'fa-check' : 'fa-times'} me-1"></i>
                    ${test.result === 'pass' ? 'ناجح' : 'راسب'}
                </span>
            </td>
            <td>
                ${attachmentButtons}
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeTest(${test.id})" title="حذف الاختبار">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

// دالة حذف اختبار
function removeTest(testId) {
    if (confirm('هل أنت متأكد من حذف هذا الاختبار؟')) {
        testsArray = testsArray.filter(test => test.id !== testId);
        
        updateTestsTable();
        updateTestsSummary();
        
        // حفظ تلقائي على الخادم
        const licenseId = document.getElementById('lab-license-id').value;
        if (licenseId) {
            autoSaveTestsToServer(licenseId);
        }
    }
}





// دالة عرض المرفق
function viewAttachment(fileUrl, fileName) {
    // إنشاء modal لعرض المرفق
    const fileExtension = fileName.split('.').pop().toLowerCase();
    const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
    const pdfExtensions = ['pdf'];
    
    let modalContent = '';
    
    if (imageExtensions.includes(fileExtension)) {
        modalContent = `<img src="${fileUrl}" class="img-fluid" alt="${fileName}" style="max-height: 500px;">`;
    } else if (pdfExtensions.includes(fileExtension)) {
        modalContent = `<iframe src="${fileUrl}" width="100%" height="600px" frameborder="0"></iframe>`;
    } else {
        modalContent = `
            <div class="text-center p-4">
                <i class="fas fa-file fa-5x text-muted mb-3"></i>
                <h5>${fileName}</h5>
                <p class="text-muted">تم رفع الملف بنجاح - لا يمكن معاينة هذا النوع من الملفات</p>
                <button type="button" class="btn btn-primary" onclick="downloadAttachment('${fileUrl}', '${fileName}')">
                    <i class="fas fa-download me-2"></i>تحميل الملف
                </button>
            </div>
        `;
    }
    
    // إنشاء modal
    const modalHtml = `
        <div class="modal fade" id="attachmentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-eye me-2"></i>عرض المرفق: ${fileName}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        ${modalContent}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" onclick="downloadAttachment('${fileUrl}', '${fileName}')">
                            <i class="fas fa-download me-2"></i>تحميل
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // إزالة modal السابق إن وجد
    const existingModal = document.getElementById('attachmentModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // إضافة modal الجديد
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // إظهار modal
    const modal = new bootstrap.Modal(document.getElementById('attachmentModal'));
    modal.show();
}

// دالة تحميل المرفق
function downloadAttachment(fileUrl, fileName) {
    // تحميل ملف من الخادم
    const link = document.createElement('a');
    link.href = fileUrl;
    link.download = fileName;
    link.target = '_blank';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// دالة تحديث ملخص الاختبارات
function updateTestsSummary() {
    // حساب مبالغ الاختبارات الناجحة والراسبة
    const passedAmount = testsArray
        .filter(test => test.result === 'pass')
        .reduce((sum, test) => sum + test.total, 0);
    
    const failedAmount = testsArray
        .filter(test => test.result === 'fail')
        .reduce((sum, test) => sum + test.total, 0);
    
    // تحديث العرض مع التنسيق المناسب
    document.getElementById('passed_tests_amount').textContent = 
        passedAmount === 0 ? '0.00' : passedAmount.toFixed(2);
    document.getElementById('failed_tests_amount').textContent = 
        failedAmount === 0 ? '0.00' : failedAmount.toFixed(2);
    
    // تحديث ألوان الصناديق حسب القيم
    const passedBox = document.getElementById('passed_tests_amount').closest('.bg-success');
    const failedBox = document.getElementById('failed_tests_amount').closest('.bg-danger');
    
    if (passedBox) {
        passedBox.style.opacity = passedAmount === 0 ? '0.6' : '1';
    }
    
    if (failedBox) {
        failedBox.style.opacity = failedAmount === 0 ? '0.6' : '1';
    }
}



// دالة تحميل بيانات الاختبارات المحفوظة من الخادم
function loadLabTestsFromServer(licenseId) {
    if (!licenseId || licenseId === '' || licenseId === 'undefined') {
        testsArray = [];
        updateTestsTable();
        updateTestsSummary();
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        return;
    }
    
    fetch(`/admin/licenses/${licenseId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error: ${response.status} - ${response.statusText}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.indexOf('application/json') !== -1) {
                return response.json();
            } else {
                throw new Error('Response is not valid JSON');
            }
        })
        .then(data => {
            if (data.success && data.license) {
                try {
                    // التحقق من وجود بيانات الاختبارات
                    if (data.license.lab_tests_data && data.license.lab_tests_data !== '[]' && data.license.lab_tests_data !== '') {
                        let savedTests;
                        if (typeof data.license.lab_tests_data === 'string') {
                            savedTests = JSON.parse(data.license.lab_tests_data);
                        } else {
                            savedTests = data.license.lab_tests_data;
                        }
                        
                        if (Array.isArray(savedTests) && savedTests.length > 0) {
                            // تحويل البيانات المحفوظة إلى تنسيق النظام
                            testsArray = savedTests.map((test, index) => ({
                                id: index + 1,
                                name: test.name || '',
                                points: parseFloat(test.points || 0),
                                price: parseFloat(test.price || 0),
                                total: parseFloat(test.total || 0),
                                result: test.result || '',
                                fileUrl: test.file_url || null,
                                fileName: test.file_name || null
                            }));
                            
                            // تحديث العداد
                            testIdCounter = testsArray.length + 1;
                            
                            // تحديث الجدول والملخص
                            updateTestsTable();
                            updateTestsSummary();
                        } else {
                            // لا توجد اختبارات صالحة
                            testsArray = [];
                            updateTestsTable();
                            updateTestsSummary();
                        }
                    } else {
                        // لا توجد بيانات محفوظة، جرب تحميل النسخة الاحتياطية
                        tryLoadLocalBackup(licenseId);
                    }
                } catch (parseError) {
                    testsArray = [];
                    updateTestsTable();
                    updateTestsSummary();
                }
            } else {
                tryLoadLocalBackup(licenseId);
            }
        })
        .catch(error => {
            // جرب تحميل النسخة الاحتياطية المحلية
            tryLoadLocalBackup(licenseId);
        });
}

// دالة تحميل النسخة الاحتياطية المحلية
function tryLoadLocalBackup(licenseId) {
    // تم تعطيل تحميل النسخة المحلية
    testsArray = [];
    updateTestsTable();
    updateTestsSummary();
}

// دالة الحفظ التلقائي على الخادم
function autoSaveTestsToServer(licenseId) {
    if (!licenseId || licenseId === '' || licenseId === 'undefined') {
        console.error('No valid license ID provided for auto-save:', licenseId);
        return;
    }
    
    // تحضير البيانات للإرسال
    const testsData = testsArray.map(test => ({
        name: test.name,
        points: parseFloat(test.points),
        price: parseFloat(test.price),
        total: parseFloat(test.total),
        result: test.result,
        file_url: test.fileUrl || null,
        file_name: test.fileName || null
    }));
    
    const passedCount = testsArray.filter(test => test.result === 'pass').length;
    const failedCount = testsArray.filter(test => test.result === 'fail').length;
    const passedAmount = testsArray.filter(test => test.result === 'pass').reduce((sum, test) => sum + test.total, 0);
    const failedAmount = testsArray.filter(test => test.result === 'fail').reduce((sum, test) => sum + test.total, 0);
    const totalAmount = passedAmount + failedAmount;
    
    console.log(`Auto-saving ${testsArray.length} tests for license ${licenseId}`);
    
    // إرسال البيانات للخادم
    fetch('{{ route("admin.licenses.save-section") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            license_id: licenseId,
            work_order_id: {{ $workOrder->id }},
            section: 'lab_tests',
            tests_data: testsData,
            totals: {
                total_tests: testsArray.length,
                passed_tests: passedCount,
                failed_tests: failedCount,
                passed_amount: passedAmount,
                failed_amount: failedAmount,
                total_amount: totalAmount
            }
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Auto-save successful:', data);
            
            
            // عرض رسالة نجاح صغيرة في الزاوية
            showAutoSaveSuccess();
        } else {
            console.error('Auto-save failed:', data);
        }
    })
    .catch(error => {
        console.error('Auto-save error:', error);
    });
}

// دالة عرض رسالة نجاح الحفظ التلقائي
function showAutoSaveSuccess() {
    // إنشاء عنصر إشعار صغير
    const notification = document.createElement('div');
    notification.className = 'auto-save-notification';
    notification.innerHTML = '<i class="fas fa-check-circle me-2"></i>تم الحفظ تلقائياً';
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 14px;
        z-index: 9999;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    // إزالة الإشعار بعد 2 ثانية
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-20px)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 2000);
}

// تهيئة النظام عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // تحميل الرخص والمخالفات
    loadLicenses();
    loadViolations();
    
    // تهيئة نموذج الإخلاءات في حالة معطلة
    disableEvacuationButtons();
    
    // تهيئة الجدول بدون اختبارات
    testsArray = [];
    updateTestsTable();
    updateTestsSummary();
    
    // تحميل البيانات المحفوظة من الخادم عند تحديد رخصة
    const licenseSelector = document.getElementById('lab-license-selector');
    if (licenseSelector) {
        licenseSelector.addEventListener('change', function() {
            const licenseId = this.value;
            if (licenseId) {
                loadLabTestsFromServer(licenseId);
            } else {
                // مسح البيانات عند عدم اختيار رخصة
                testsArray = [];
                updateTestsTable();
                updateTestsSummary();
            }
        });
    }
    
    // التنقل التلقائي إلى قسم المختبر إذا كان موجود في URL
    if (window.location.hash === '#lab-section') {
        // تفعيل تبويب المختبر
        showSection('lab');
        
        // التمرير إلى القسم
        setTimeout(() => {
            const labSection = document.getElementById('lab-section');
            if (labSection) {
                labSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 100);
    }

    // ملاحظة: تم نقل معالجات الإخلاء إلى ملف evacuation-handler.js
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
            <input type="number" step="0.01" class="form-control form-control-sm evacuation-amount" name="evacuation_data[${rowCount}][evacuation_amount]" placeholder="0.00" required onchange="calculateEvacuationTotal()" oninput="calculateEvacuationTotal()">
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
        <td class="text-center">
            <div class="d-flex align-items-center justify-content-center">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-info btn-sm me-1" onclick="viewAttachments(this)" title="عرض المرفقات">
                        <i class="fas fa-paperclip"></i>
                        <span class="attachment-count">0</span>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteEvacuationRow(this)" title="حذف الصف">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <input type="file" class="d-none" name="evacuation_data[${rowCount}][attachments][]" multiple onchange="updateAttachmentCount(this)">
            </div>
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

// تحميل بيانات الإخلاء المحفوظة
function loadEvacuationDataForLicense(licenseId) {
    if (!licenseId) return;
    
    fetch(`/admin/licenses/get-evacuation-data/${licenseId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.evacuation_data && data.evacuation_data.length > 0) {
                const tbody = document.getElementById('evacuationDataTable').getElementsByTagName('tbody')[0];
                
                // مسح البيانات الموجودة
                tbody.innerHTML = '';
                
                // إضافة البيانات المحفوظة
                data.evacuation_data.forEach((evacuation, index) => {
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td class="text-center fw-bold">${index + 1}</td>
                        <td>
                            <select class="form-select form-select-sm" name="evacuation_data[${index + 1}][is_evacuated]" required>
                                <option value="">-- اختر --</option>
                                <option value="1" ${evacuation.is_evacuated == 1 ? 'selected' : ''}>نعم</option>
                                <option value="0" ${evacuation.is_evacuated == 0 ? 'selected' : ''}>لا</option>
                            </select>
                        </td>
                        <td>
                            <input type="date" class="form-control form-control-sm" name="evacuation_data[${index + 1}][evacuation_date]" value="${evacuation.evacuation_date || ''}" required>
                        </td>
                        <td>
                            <input type="number" step="0.01" class="form-control form-control-sm evacuation-amount" name="evacuation_data[${index + 1}][evacuation_amount]" value="${evacuation.evacuation_amount || ''}" placeholder="0.00" required onchange="calculateEvacuationTotal()" oninput="calculateEvacuationTotal()">
                        </td>
                        <td>
                            <input type="datetime-local" class="form-control form-control-sm" name="evacuation_data[${index + 1}][evacuation_datetime]" value="${evacuation.evacuation_datetime || ''}" required>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm" name="evacuation_data[${index + 1}][payment_number]" value="${evacuation.payment_number || ''}" placeholder="رقم سداد الإخلاء" required>
                        </td>
                        <td>
                            <textarea class="form-control form-control-sm" name="evacuation_data[${index + 1}][notes]" rows="2" placeholder="ملاحظات الإخلاء">${evacuation.notes || ''}</textarea>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-info btn-sm me-1" onclick="viewAttachments(this)" title="عرض المرفقات">
                                    <i class="fas fa-paperclip"></i>
                                    <span class="attachment-count">1</span>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteEvacuationRow(this)" title="حذف الصف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(newRow);
                });
                
                updateEvacuationRowNumbers();
            }
        })
        .catch(error => {
            console.error('Error loading evacuation data:', error);
        });
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
                    <td colspan="8" class="text-center text-muted py-4">
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
    
    // إعادة حساب الإجمالي بعد تحديث الصفوف
    calculateEvacuationTotal();
}

// دالة حساب إجمالي مبلغ الإخلاء
function calculateEvacuationTotal() {
    console.log('=== calculateEvacuationTotal called ===');
    
    // البحث عن عنصر الإجمالي أولاً وفرض ظهوره
    const totalElement = document.getElementById('total-evacuation-amount');
    
    if (totalElement) {
        // فرض الظهور فوراً باستخدام setProperty
        totalElement.style.setProperty('display', 'table-cell', 'important');
        totalElement.style.setProperty('visibility', 'visible', 'important');
        totalElement.style.backgroundColor = '#b8daff';
        totalElement.style.color = '#004085';
        totalElement.style.fontWeight = 'bold';
        totalElement.style.fontSize = '1.1em';
        totalElement.style.border = '2px solid #007bff';
        
        // فرض ظهور الـ tfoot أيضاً
        const tfoot = totalElement.closest('tfoot');
        if (tfoot) {
            tfoot.style.setProperty('display', 'table-footer-group', 'important');
            tfoot.style.backgroundColor = '#d1ecf1';
            tfoot.style.border = '2px solid #28a745';
        }
    }
    
    // البحث عن الجدول
    const table = document.getElementById('evacuationDataTable');
    if (!table) {
        console.error('evacuationDataTable not found!');
        if (totalElement) totalElement.textContent = '0.00 ريال';
        return;
    }
    
    if (!totalElement) {
        console.error('total-evacuation-amount element not found!');
        return;
    }
    
    // البحث عن حقول المبلغ بطرق متعددة
    let amountInputs = document.querySelectorAll('#evacuationDataTable .evacuation-amount');
    
    // إذا لم توجد حقول بالطريقة الأولى، جرب طريقة أخرى
    if (amountInputs.length === 0) {
        amountInputs = document.querySelectorAll('#evacuationDataTable input[name*="evacuation_amount"]');
    }
    
    // إذا لم توجد بعد، جرب البحث عن جميع حقول الأرقام
    if (amountInputs.length === 0) {
        const allInputs = document.querySelectorAll('#evacuationDataTable input[type="number"]');
        // فلتر للحصول على حقول المبلغ فقط
        amountInputs = Array.from(allInputs).filter(input => 
            input.name && input.name.includes('evacuation_amount')
        );
    }
    
    console.log('Amount inputs found:', amountInputs.length);
    
    let total = 0;
    
    amountInputs.forEach((input, index) => {
        const value = parseFloat(input.value) || 0;
        total += value;
        console.log(`Input ${index}: value="${input.value}", parsed=${value}`);
    });
    
    console.log('Final total:', total);
    
    // تحديث النص
    const displayText = total.toFixed(2) + ' ريال';
    totalElement.textContent = displayText;
    
    console.log('Updated total element with:', displayText);
}

function saveAllEvacuationDataSimple() {
    const licenseId = @if($workOrder->license) {{ $workOrder->license->id }} @else null @endif;
    if (!licenseId) {
        return;
    }

    const formData = new FormData();
    formData.append('work_order_id', {{ $workOrder->id }});
    formData.append('license_id', licenseId);

    const rows = document.getElementById('evacuationDataTable').getElementsByTagName('tbody')[0].rows;
    let evacuationDataArray = [];
    
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
                evacuationDataArray.push(rowData);
            }
        }
    }

    if (evacuationDataArray.length === 0) {
        return;
    }

    // إضافة المرفق المنفصل إذا تم اختياره
    const evacuationAttachment = document.getElementById('evacuation-attachment');
    if (evacuationAttachment && evacuationAttachment.files.length > 0) {
        formData.append('evacuation_attachment', evacuationAttachment.files[0]);
    }

    formData.append('evacuation_data', JSON.stringify(evacuationDataArray));

    console.log('Simple evacuation data to be sent:', evacuationDataArray);

    fetch(`/admin/licenses/save-evacuation-data-simple`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // مسح حقل المرفق بعد الحفظ الناجح
            if (evacuationAttachment) {
                evacuationAttachment.value = '';
                clearEvacuationFile();
            }
            // تحديث قائمة المرفقات المحفوظة
            loadSavedEvacuationAttachments(licenseId);
            // إعادة تحميل البيانات
            setTimeout(() => {
                loadEvacuationDataForLicense(licenseId);
            }, 1000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// دوال التعامل مع المرفق المنفصل
function clearEvacuationFile() {
    const input = document.getElementById('evacuation-attachment');
    const preview = document.getElementById('evacuation-file-preview');
    
    if (input) input.value = '';
    if (preview) preview.style.display = 'none';
}

function loadSavedEvacuationAttachments(licenseId) {
    const container = document.getElementById('saved-evacuation-attachments');
    
    fetch(`/admin/licenses/get-evacuation-attachments/${licenseId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.attachments && data.attachments.length > 0) {
                let attachmentsHtml = '';
                data.attachments.forEach((attachment, index) => {
                    attachmentsHtml += `
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                <span class="fw-bold">${attachment.name}</span>
                                <small class="text-muted d-block">${attachment.date}</small>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <a href="${attachment.url}" target="_blank" class="btn btn-outline-primary" title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="${attachment.download_url}" class="btn btn-outline-success" title="تحميل">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = attachmentsHtml;
            } else {
                container.innerHTML = `
                    <div class="text-center text-muted">
                        <i class="fas fa-folder-open fa-2x mb-2"></i>
                        <br>لا توجد مرفقات محفوظة
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading attachments:', error);
            container.innerHTML = `
                <div class="text-center text-danger">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                    <br>خطأ في تحميل المرفقات
                </div>
            `;
        });
}

function saveAllEvacuationData() {
    // الحصول على معرف الرخصة المختارة
    const licenseSelector = document.getElementById('evacuation-license-selector');
    const licenseId = licenseSelector ? licenseSelector.value : null;
    
    if (!licenseId) {
        toastr.error('يجب اختيار رخصة أولاً');
        return;
    }

    console.log('Saving evacuation data for license:', licenseId);

    // تجميع بيانات الجدول
    const rows = document.getElementById('evacuationDataTable').getElementsByTagName('tbody')[0].rows;
    let hasValidData = false;
    let evacuationDataArray = [];
    let validRowIndex = 0;
    
    console.log('Reading data from table. Total rows:', rows.length);
    
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
            
            console.log('Row', i, 'data:', rowData);
            
            // التحقق من وجود بيانات مطلوبة
            if (rowData.is_evacuated && rowData.evacuation_date && rowData.evacuation_amount && rowData.evacuation_datetime && rowData.payment_number) {
                evacuationDataArray.push(rowData);
                hasValidData = true;
                validRowIndex++;
            } else {
                console.log('Row', i, 'has invalid data, skipping');
            }
        }
    }

    if (!hasValidData) {
        return;
    }
    
    // طباعة معلومات التشخيص
    console.log('=== EVACUATION DATA DEBUG ===');
    console.log('Total rows found:', rows.length);
    console.log('Valid evacuation data count:', evacuationDataArray.length);
    console.log('Evacuation data to be sent:', evacuationDataArray);
    console.log('=== DETAILED ROW ANALYSIS ===');
    
    // تحليل تفصيلي لكل صف
    for (let i = 0; i < rows.length; i++) {
        if (rows[i].id !== 'no-evacuation-data-row') {
            const row = rows[i];
            console.log(`Row ${i}:`, {
                is_evacuated: row.querySelector('[name*="[is_evacuated]"]')?.value || 'NOT FOUND',
                evacuation_date: row.querySelector('[name*="[evacuation_date]"]')?.value || 'NOT FOUND',
                evacuation_amount: row.querySelector('[name*="[evacuation_amount]"]')?.value || 'NOT FOUND',
                evacuation_datetime: row.querySelector('[name*="[evacuation_datetime]"]')?.value || 'NOT FOUND',
                payment_number: row.querySelector('[name*="[payment_number]"]')?.value || 'NOT FOUND',
                notes: row.querySelector('[name*="[notes]"]')?.value || 'NOT FOUND'
            });
        }
    }

    // عرض مؤشر التحميل
    const saveButton = document.querySelector('button[onclick="saveAllEvacuationData()"]');
    const originalText = saveButton.innerHTML;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';
    saveButton.disabled = true;

    // إرسال البيانات إلى الخادم (الطريقة البسيطة بدون مرفقات)
    fetch(`/admin/licenses/save-evacuation-data-simple`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            work_order_id: {{ $workOrder->id }},
            license_id: licenseId,
            evacuation_data: JSON.stringify(evacuationDataArray),
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Response error text:', text);
                throw new Error(`HTTP error! status: ${response.status} - ${text.substring(0, 200)}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            toastr.success('تم حفظ بيانات الإخلاء بنجاح');
            
            // إعادة تحميل البيانات لإظهار المرفقات المحفوظة
            loadEvacuationDataForLicense(licenseId);
            
            // حساب الإجمالي بعد الحفظ
            setTimeout(() => {
                calculateEvacuationTotal();
            }, 500);
        } else {
            toastr.error(data.message || 'حدث خطأ في حفظ البيانات');
            if (data.errors) {
                console.error('Validation errors:', data.errors);
                Object.keys(data.errors).forEach(field => {
                    console.error(`${field}: ${data.errors[field].join(', ')}`);
                });
            }
        }
    })
    .catch(error => {
        console.error('Full error:', error);
        toastr.error('حدث خطأ في الاتصال بالخادم');
    })
    .finally(() => {
        // إعادة تعيين زر الحفظ
        saveButton.innerHTML = originalText;
        saveButton.disabled = false;
    });
}

// دالة لتحميل بيانات الإخلاءات الموجودة للرخصة المختارة
function loadEvacuationDataForLicense(licenseId) {
    if (!licenseId) {
        licenseId = @if($workOrder->license) {{ $workOrder->license->id }} @else null @endif;
    }
    if (!licenseId) return;
    
    console.log('Loading evacuation data for license:', licenseId);
    
    fetch(`/admin/licenses/get-evacuation-data/${licenseId}`)
        .then(response => response.json())
        .then(data => {
            console.log('Loaded evacuation data:', data);
            const tbody = document.getElementById('evacuationDataTable').getElementsByTagName('tbody')[0];
            tbody.innerHTML = '';
            
            if (data.evacuation_data && data.evacuation_data.length > 0) {
                console.log('Adding', data.evacuation_data.length, 'existing rows to table');
                data.evacuation_data.forEach((item, index) => {
                    addEvacuationRowWithData(item, index + 1);
                });
                // حساب الإجمالي بعد تحميل البيانات
                setTimeout(() => calculateEvacuationTotal(), 100);
            } else {
                console.log('No existing evacuation data found');
                tbody.innerHTML = `
                    <tr id="no-evacuation-data-row">
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-truck fa-2x mb-2"></i>
                            <br>لا توجد بيانات إخلاء مسجلة لهذه الرخصة
                        </td>
                    </tr>
                `;
                // حساب الإجمالي حتى لو لم توجد بيانات (سيكون 0.00)
                calculateEvacuationTotal();
            }
        })
        .catch(error => {
            console.error('Error loading evacuation data:', error);
        });
}

function addEvacuationRowWithData(data, rowNumber) {
    console.log('Adding evacuation row with data:', data, 'row number:', rowNumber);
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
            <input type="number" step="0.01" class="form-control form-control-sm evacuation-amount" name="evacuation_data[${rowNumber}][evacuation_amount]" value="${data.evacuation_amount || ''}" placeholder="0.00" required onchange="calculateEvacuationTotal()" oninput="calculateEvacuationTotal()">
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
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm" onclick="deleteEvacuationRow(this)" title="حذف الصف">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(newRow);
}

// دالة إعادة تعيين نموذج الرخصة في النافذة المنبثقة
function resetDigLicenseForm() {
    const form = document.getElementById('digLicenseForm');
    if (form) {
        form.reset();
        // إعادة تعيين حقل عدد الأيام
        document.getElementById('modal_license_days').value = '';
    }
}

// حساب عدد أيام الرخصة في النافذة المنبثقة
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('modal_license_start_date');
    const endDateInput = document.getElementById('modal_license_end_date');
    const daysInput = document.getElementById('modal_license_days');
    
    function calculateDays() {
        if (startDateInput.value && endDateInput.value) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            const timeDiff = endDate.getTime() - startDate.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
            daysInput.value = daysDiff > 0 ? daysDiff : 0;
        }
    }
    
    if (startDateInput && endDateInput) {
        startDateInput.addEventListener('change', calculateDays);
        endDateInput.addEventListener('change', calculateDays);
    }
    
    // تحميل بيانات الإخلاءات تلقائياً عند تحميل الصفحة
    const licenseId = @if($workOrder->license) {{ $workOrder->license->id }} @else null @endif;
    if (licenseId) {
        loadEvacuationDataForLicense(licenseId);
    }
    
    // إعادة تعيين النموذج عند إغلاق النافذة المنبثقة
    const modal = document.getElementById('addLicenseModal');
    if (modal) {
        modal.addEventListener('hidden.bs.modal', function () {
            resetDigLicenseForm();
        });
    }
});

// ========== دوال مرفقات الإخلاءات ==========

// متغيرات عامة للمرفقات
let selectedEvacuationFiles = [];
let currentLicenseId = null;

// دالة معالجة اختيار المرفقات
function handleEvacuationAttachments(files) {
    const uploadBtn = document.getElementById('upload-evacuation-btn');
    
    if (files.length === 0) {
        uploadBtn.disabled = true;
        selectedEvacuationFiles = [];
        return;
    }
    
    // التحقق من عدد الملفات
    if (files.length > 10) {
        toastr.error('لا يمكن رفع أكثر من 10 ملفات في المرة الواحدة');
        document.getElementById('evacuation-attachments').value = '';
        return;
    }
    
    // التحقق من حجم الملفات
    let totalSize = 0;
    for (let file of files) {
        totalSize += file.size;
        if (file.size > 10 * 1024 * 1024) { // 10MB
            toastr.error(`الملف ${file.name} كبير جداً. الحد الأقصى 10 ميجابايت`);
            document.getElementById('evacuation-attachments').value = '';
            return;
        }
    }
    
    if (totalSize > 50 * 1024 * 1024) { // 50MB إجمالي
        toastr.error('حجم الملفات الإجمالي كبير جداً. الحد الأقصى 50 ميجابايت');
        document.getElementById('evacuation-attachments').value = '';
        return;
    }
    
    selectedEvacuationFiles = Array.from(files);
    uploadBtn.disabled = false;
    
    toastr.info(`تم اختيار ${files.length} ملف للرفع`);
}

// دالة رفع المرفقات
function uploadEvacuationAttachments() {
    const licenseSelector = document.getElementById('evacuation-license-selector');
    
    if (!licenseSelector.value) {
        toastr.error('يجب اختيار رخصة أولاً');
        return;
    }
    
    if (selectedEvacuationFiles.length === 0) {
        toastr.error('يجب اختيار ملفات للرفع');
        return;
    }
    
    const formData = new FormData();
    formData.append('license_id', licenseSelector.value);
    formData.append('attachment_type', 'evacuation');
    
    selectedEvacuationFiles.forEach((file, index) => {
        formData.append(`attachments[${index}]`, file);
    });
    
    const uploadBtn = document.getElementById('upload-evacuation-btn');
    const originalText = uploadBtn.innerHTML;
    
    // إظهار مؤشر التحميل
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الرفع...';
    
    $.ajax({
        url: '/admin/licenses/evacuation-attachments/upload',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                toastr.success(`تم رفع ${selectedEvacuationFiles.length} ملف بنجاح`);
                
                // مسح اختيار الملفات
                document.getElementById('evacuation-attachments').value = '';
                selectedEvacuationFiles = [];
                
                // تحديث قائمة المرفقات
                loadEvacuationAttachments(licenseSelector.value);
            } else {
                toastr.error(response.message || 'حدث خطأ في رفع المرفقات');
            }
        },
        error: function(xhr) {
            console.error('Error uploading attachments:', xhr);
            const errorMsg = xhr.responseJSON?.message || 'حدث خطأ في رفع المرفقات';
            toastr.error(errorMsg);
        },
        complete: function() {
            // إعادة تفعيل الزر
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = originalText;
        }
    });
}

// دالة تحميل المرفقات المرفوعة
function loadEvacuationAttachments(licenseId) {
    if (!licenseId) return;
    
    $.ajax({
        url: `/admin/licenses/${licenseId}/evacuation-attachments`,
        type: 'GET',
        success: function(response) {
            const attachmentsList = document.getElementById('evacuation-attachments-list');
            const noAttachmentsMsg = document.getElementById('no-evacuation-attachments');
            
            if (response.attachments && response.attachments.length > 0) {
                // إخفاء رسالة عدم وجود مرفقات
                if (noAttachmentsMsg) {
                    noAttachmentsMsg.style.display = 'none';
                }
                
                // عرض المرفقات
                let attachmentsHtml = '<div class="row g-3">';
                response.attachments.forEach((attachment, index) => {
                    const fileName = attachment.path ? attachment.path.split('/').pop() : `مرفق ${index + 1}`;
                    const fileExtension = fileName.split('.').pop().toLowerCase();
                    let iconClass = 'fas fa-file';
                    
                    if (['pdf'].includes(fileExtension)) {
                        iconClass = 'fas fa-file-pdf text-danger';
                    } else if (['doc', 'docx'].includes(fileExtension)) {
                        iconClass = 'fas fa-file-word text-primary';
                    } else if (['jpg', 'jpeg', 'png'].includes(fileExtension)) {
                        iconClass = 'fas fa-file-image text-success';
                    }
                    
                    attachmentsHtml += `
                        <div class="col-md-4 col-sm-6">
                            <div class="card h-100 border-secondary">
                                <div class="card-body text-center p-3">
                                    <i class="${iconClass} fa-2x mb-2"></i>
                                    <h6 class="card-title text-truncate" title="${fileName}">${fileName}</h6>
                                    <div class="btn-group btn-group-sm w-100">
                                        <a href="${attachment.url}" target="_blank" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i> عرض
                                        </a>
                                        <button class="btn btn-outline-danger" onclick="deleteEvacuationAttachment(${index})">
                                            <i class="fas fa-trash"></i> حذف
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                attachmentsHtml += '</div>';
                
                attachmentsList.innerHTML = attachmentsHtml;
            } else {
                // عرض رسالة عدم وجود مرفقات
                if (noAttachmentsMsg) {
                    noAttachmentsMsg.style.display = 'block';
                }
                attachmentsList.innerHTML = `
                    <div class="text-center text-muted py-3" id="no-evacuation-attachments">
                        <i class="fas fa-file-upload fa-2x mb-2"></i>
                        <br>لا توجد مرفقات مرفوعة حتى الآن
                        <br><small>استخدم الحقل أعلاه لرفع المرفقات</small>
                    </div>
                `;
            }
        },
        error: function(xhr) {
            console.error('Error loading attachments:', xhr);
        }
    });
}

// دالة حذف مرفق
function deleteEvacuationAttachment(attachmentIndex) {
    const licenseSelector = document.getElementById('evacuation-license-selector');
    
    if (!licenseSelector.value) {
        toastr.error('خطأ في تحديد الرخصة');
        return;
    }
    
    if (!confirm('هل أنت متأكد من حذف هذا المرفق؟')) {
        return;
    }
    
    $.ajax({
        url: `/admin/licenses/${licenseSelector.value}/evacuation-attachments/${attachmentIndex}`,
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                toastr.success('تم حذف المرفق بنجاح');
                loadEvacuationAttachments(licenseSelector.value);
            } else {
                toastr.error(response.message || 'حدث خطأ في حذف المرفق');
            }
        },
        error: function(xhr) {
            console.error('Error deleting attachment:', xhr);
            toastr.error('حدث خطأ في حذف المرفق');
        }
    });
}

// استدعاء دالة حساب الإجمالي عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - initializing evacuation total');
    
    // دالة للتأكد من ظهور الإجمالي
    function ensureTotalVisible() {
        const totalElement = document.getElementById('total-evacuation-amount');
        const tfoot = document.querySelector('#evacuationDataTable tfoot');
        
        console.log('Ensuring total visibility:', {
            totalElement: !!totalElement,
            tfoot: !!tfoot
        });
        
        if (totalElement) {
            // استخدام setProperty لضمان تطبيق الـ CSS
            totalElement.style.setProperty('display', 'table-cell', 'important');
            totalElement.style.setProperty('visibility', 'visible', 'important');
            totalElement.style.backgroundColor = '#b8daff';
            totalElement.style.color = '#004085';
            totalElement.style.fontWeight = 'bold';
            totalElement.style.fontSize = '1.1em';
            
            // إضافة حد واضح للتأكد من الظهور
            totalElement.style.border = '2px solid #007bff';
            
            if (totalElement.textContent === '' || totalElement.textContent === '0.00 ريال') {
                totalElement.textContent = '0.00 ريال';
            }
        }
        
        if (tfoot) {
            tfoot.style.setProperty('display', 'table-footer-group', 'important');
            tfoot.style.backgroundColor = '#d1ecf1';
            tfoot.style.border = '2px solid #28a745';
        }
        
        calculateEvacuationTotal();
    }
    
    // تشغيل الدالة عدة مرات للتأكد
    setTimeout(ensureTotalVisible, 500);
    setTimeout(ensureTotalVisible, 1000);
    setTimeout(ensureTotalVisible, 2000);
    setTimeout(ensureTotalVisible, 5000); // إضافة تأخير أطول
    
    // إضافة مراقب للنقر لفرض ظهور الإجمالي
    document.addEventListener('click', function(e) {
        setTimeout(ensureTotalVisible, 50);
    });
    
    // إضافة مراقب للتغيير في أي input
    document.addEventListener('input', function(e) {
        if (e.target.type === 'number' || e.target.name.includes('evacuation')) {
            setTimeout(ensureTotalVisible, 50);
        }
    });
    
    // إضافة مراقب للتمرير
    document.addEventListener('scroll', function(e) {
        setTimeout(ensureTotalVisible, 100);
    });
    
    // إضافة مراقب لتغيير الحجم
    window.addEventListener('resize', function(e) {
        setTimeout(ensureTotalVisible, 100);
    });
    
    // تشغيل دوري كل 3 ثوان
    setInterval(ensureTotalVisible, 3000);
});

// دالة تشخيص مشاكل الإجمالي
function debugEvacuationTotal() {
    console.log('=== DEBUG EVACUATION TOTAL ===');
    
    const table = document.getElementById('evacuationDataTable');
    const totalElement = document.getElementById('total-evacuation-amount');
    const tfoot = document.querySelector('#evacuationDataTable tfoot');
    
    console.log('Elements check:', {
        table: !!table,
        totalElement: !!totalElement,
        tfoot: !!tfoot
    });
    
    if (table) {
        console.log('Table HTML:', table.outerHTML.substring(0, 500));
    }
    
    if (totalElement) {
        console.log('Total element:', {
            id: totalElement.id,
            textContent: totalElement.textContent,
            innerHTML: totalElement.innerHTML,
            display: window.getComputedStyle(totalElement).display,
            visibility: window.getComputedStyle(totalElement).visibility,
            backgroundColor: window.getComputedStyle(totalElement).backgroundColor,
            position: totalElement.getBoundingClientRect()
        });
        
        // فرض الظهور
        totalElement.style.display = 'table-cell !important';
        totalElement.style.visibility = 'visible !important';
        totalElement.style.backgroundColor = '#ff0000'; // أحمر للتأكد من الظهور
        totalElement.textContent = 'TEST 123.45 ريال';
        
        console.log('After forced styling:', {
            display: totalElement.style.display,
            visibility: totalElement.style.visibility,
            backgroundColor: totalElement.style.backgroundColor,
            textContent: totalElement.textContent
        });
    }
    
    if (tfoot) {
        console.log('Tfoot element:', {
            display: window.getComputedStyle(tfoot).display,
            visibility: window.getComputedStyle(tfoot).visibility
        });
        
        tfoot.style.display = 'table-footer-group !important';
        tfoot.style.backgroundColor = '#00ff00'; // أخضر للتأكد من الظهور
    }
    
    // البحث عن جميع حقول المبلغ
    const amountInputs = document.querySelectorAll('#evacuationDataTable input[type="number"]');
    console.log('Number inputs:', amountInputs.length);
    
    amountInputs.forEach((input, index) => {
        console.log(`Input ${index}:`, {
            name: input.name,
            value: input.value,
            className: input.className,
            type: input.type
        });
    });
    
    alert('تم إجراء التشخيص - تحقق من الكونسول للتفاصيل');
}

// تحديث دالة اختيار رخصة الإخلاءات لتحميل المرفقات
const originalSelectEvacuationLicense = selectEvacuationLicense;
selectEvacuationLicense = function() {
    originalSelectEvacuationLicense();
    
    const selector = document.getElementById('evacuation-license-selector');
    if (selector && selector.value) {
        // تحميل المرفقات للرخصة المختارة
        setTimeout(() => {
            loadEvacuationAttachments(selector.value);
            // حساب إجمالي مبلغ الإخلاء بعد تحميل البيانات
            calculateEvacuationTotal();
        }, 500);
    } else {
        // إعادة تعيين الإجمالي إلى 0.00 عند عدم اختيار رخصة
        calculateEvacuationTotal();
    }
};

</script>

<!-- Modal إضافة رخصة حفر جديدة -->
<div class="modal fade" id="addLicenseModal" tabindex="-1" aria-labelledby="addLicenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addLicenseModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>
                    إضافة رخصة حفر جديدة
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="digLicenseForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                    
                    <!-- معلومات الرخصة الأساسية -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">رقم الرخصة</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                <input type="text" class="form-control" name="license_number" required>
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
                            <select class="form-select" name="license_type" required>
                                <option value="">اختر نوع الرخصة</option>
                                <option value="طوارئ">طوارئ</option>
                                <option value="مشروع">مشروع</option>
                                <option value="عادي">عادي</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">قيمة الرخصة</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-money-bill"></i></span>
                                <input type="number" step="0.01" class="form-control" name="license_value" required>
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
                                <input type="number" step="0.01" class="form-control" name="excavation_length" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">عرض الحفر (متر)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-arrows-alt-h"></i></span>
                                <input type="number" step="0.01" class="form-control" name="excavation_width" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">عمق الحفر (متر)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-ruler-vertical"></i></span>
                                <input type="number" step="0.01" class="form-control" name="excavation_depth" required>
                            </div>
                        </div>
                    </div>

                    <!-- تواريخ الرخصة -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">تاريخ تفعيل الرخصة</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-play"></i></span>
                                <input type="date" class="form-control" name="license_start_date" id="modal_license_start_date">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">تاريخ نهاية الرخصة</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-stop"></i></span>
                                <input type="date" class="form-control" name="license_end_date" id="modal_license_end_date">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">عدد أيام الرخصة</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                <input type="number" class="form-control bg-light" name="license_alert_days" id="modal_license_days" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- المرفقات -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">ملف الرخصة</label>
                            <input type="file" class="form-control" name="license_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                ملف PDF أو صورة للرخصة
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">إثبات سداد البنك</label>
                            <input type="file" class="form-control" name="payment_proof[]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                يمكن رفع ملفات متعددة
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">فواتير السداد</label>
                            <input type="file" class="form-control" name="payment_invoices[]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                يمكن رفع ملفات متعددة
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">ملفات التفعيل</label>
                            <input type="file" class="form-control" name="license_activation[]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                ملفات تفعيل الرخصة (اختياري)
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    إلغاء
                </button>
                
                <button type="button" class="btn btn-primary" onclick="saveDigLicenseSection()">
                    <i class="fas fa-save me-2"></i>
                    حفظ رخصة الحفر
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- تحميل معالج JavaScript الآمن للرخص -->
<script src="{{ asset('js/work-order-license.js') }}?v={{ time() }}"></script>
<!-- تحميل معالج الإخلاءات -->
<script src="{{ asset('js/evacuation-handler.js') }}?v={{ time() }}"></script>
@endpush 