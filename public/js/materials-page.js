// ملف JavaScript لصفحة المواد - حل مشاكل الكونسول

// دالة حذف ملف مادة
function deleteMaterialFile(materialId, fileType, buttonElement) {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 غير محمل');
        if (confirm('هل أنت متأكد من حذف هذا الملف؟')) {
            performFileDelete(materialId, fileType, buttonElement);
        }
        return;
    }

    Swal.fire({
        title: 'تأكيد الحذف',
        text: 'هل أنت متأكد من حذف هذا الملف؟',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذف',
        cancelButtonText: 'إلغاء',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            performFileDelete(materialId, fileType, buttonElement);
        }
    });
}

// دالة تنفيذ حذف الملف
function performFileDelete(materialId, fileType, buttonElement) {
    try {
        // العثور على الصف وحذفه من الجدول
        const button = buttonElement || (typeof event !== 'undefined' ? event.target.closest('button') : null);
        if (!button) {
            console.error('لم يتم العثور على زر الحذف');
            return;
        }
        
        const row = button.closest('tr');
        
        // إنشاء فورم مخفي لحذف الملف
        const form = document.createElement('form');
        form.method = 'POST';
        
        // الحصول على work order ID من الصفحة
        const workOrderId = getWorkOrderId();
        form.action = `/admin/work-orders/${workOrderId}/materials/${materialId}/delete-file`;
        
        // إضافة CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = getCSRFToken();
        form.appendChild(csrfToken);
        
        // إضافة method DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        // إضافة نوع الملف
        const fileTypeInput = document.createElement('input');
        fileTypeInput.type = 'hidden';
        fileTypeInput.name = 'file_type';
        fileTypeInput.value = fileType;
        form.appendChild(fileTypeInput);
        
        // حذف الصف من الجدول فوراً
        if (row) {
            row.remove();
            showSuccessMessage('تم حذف الملف بنجاح');
        }
        
        // إضافة الفورم للصفحة وإرساله
        document.body.appendChild(form);
        form.submit();
    } catch (error) {
        console.error('خطأ في حذف الملف:', error);
        showErrorMessage('حدث خطأ أثناء حذف الملف');
    }
}

// دالة إضافة ملف إلى الجدول
function addFileToTable(fileData) {
    const tableBody = document.querySelector('#attachmentsTable tbody');
    if (!tableBody) {
        console.error('لم يتم العثور على جدول المرفقات');
        return;
    }
    
    const row = document.createElement('tr');
    
    const now = new Date();
    const dateString = now.getFullYear() + '-' + 
                      String(now.getMonth() + 1).padStart(2, '0') + '-' + 
                      String(now.getDate()).padStart(2, '0') + ' ' +
                      String(now.getHours()).padStart(2, '0') + ':' + 
                      String(now.getMinutes()).padStart(2, '0');
    
    row.innerHTML = `
        <td>
            <div class="d-flex flex-column">
                <div class="d-flex align-items-center mb-1">
                    <i class="${fileData.icon} ${fileData.color} me-2"></i>
                    <span class="fw-bold">${fileData.label}</span>
                </div>
                <div class="ms-3">
                    <a href="/storage/${fileData.file_path}" target="_blank" class="text-decoration-none text-muted small">
                        <i class="fas fa-file me-1"></i>
                        ${fileData.file_name}
                    </a>
                </div>
            </div>
        </td>
        <td>
            <small class="text-muted">${dateString}</small>
        </td>
        <td>
            <div class="btn-group" role="group">
                <a href="/storage/${fileData.file_path}" 
                   target="_blank" 
                   class="btn btn-sm btn-outline-primary"
                   data-bs-toggle="tooltip" 
                   title="عرض الملف">
                    <i class="fas fa-eye"></i>
                </a>
                <button type="button" 
                        class="btn btn-sm btn-outline-danger"
                        onclick="deleteMaterialFile(${fileData.material_id}, '${fileData.file_type}', this)"
                        data-bs-toggle="tooltip" 
                        title="حذف الملف">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    `;
    
    tableBody.appendChild(row);
}

// دوال مساعدة
function getWorkOrderId() {
    // محاولة الحصور على work order ID من الصفحة
    const urlParts = window.location.pathname.split('/');
    const workOrderIndex = urlParts.indexOf('work-orders');
    if (workOrderIndex !== -1 && urlParts[workOrderIndex + 1]) {
        return urlParts[workOrderIndex + 1];
    }
    
    // محاولة أخرى من meta tag أو data attribute
    const metaTag = document.querySelector('meta[name="work-order-id"]');
    if (metaTag) {
        return metaTag.getAttribute('content');
    }
    
    console.error('لم يتم العثور على work order ID');
    return null;
}

function getCSRFToken() {
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    if (metaTag) {
        return metaTag.getAttribute('content');
    }
    
    const tokenInput = document.querySelector('input[name="_token"]');
    if (tokenInput) {
        return tokenInput.value;
    }
    
    console.error('لم يتم العثور على CSRF token');
    return '';
}

function showSuccessMessage(message) {
    if (typeof toastr !== 'undefined') {
        toastr.success(message);
    } else {
        console.log('نجح: ' + message);
        // Fallback alert
        alert('نجح: ' + message);
    }
}

function showErrorMessage(message) {
    if (typeof toastr !== 'undefined') {
        toastr.error(message);
    } else {
        console.error('خطأ: ' + message);
        alert('خطأ: ' + message);
    }
}

function showWarningMessage(message) {
    if (typeof toastr !== 'undefined') {
        toastr.warning(message);
    } else {
        console.warn('تحذير: ' + message);
        alert('تحذير: ' + message);
    }
}

// تهيئة الصفحة عند التحميل
document.addEventListener('DOMContentLoaded', function() {
    // تهيئة فورم رفع الملفات
    initializeUploadForm();
    
    // تهيئة tooltips
    initializeTooltips();
    
    // إضافة CSS للتمييز
    addHighlightStyles();
});

// تهيئة فورم رفع الملفات
function initializeUploadForm() {
    const uploadForm = document.getElementById('uploadMaterialFilesForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            const fileInputs = this.querySelectorAll('input[type="file"]');
            let hasFiles = false;
            
            fileInputs.forEach(input => {
                if (input.files.length > 0) {
                    hasFiles = true;
                }
            });
            
            if (!hasFiles) {
                e.preventDefault();
                showWarningMessage('يرجى اختيار ملف واحد على الأقل للرفع');
                return false;
            }
            
            // تعطيل زر الإرسال لمنع الإرسال المتكرر
            const submitBtn = document.getElementById('uploadFilesBtn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الرفع...';
            }
        });
    }
}

// تهيئة tooltips
function initializeTooltips() {
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
}

// إضافة CSS للتمييز
function addHighlightStyles() {
    const style = document.createElement('style');
    style.textContent = `
        .highlight-text {
            background-color: #fff176;
            padding: 2px 0;
            border-radius: 2px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            animation: highlight-pulse 2s infinite;
        }
        
        @keyframes highlight-pulse {
            0% { background-color: #fff176; }
            50% { background-color: #ffeb3b; }
            100% { background-color: #fff176; }
        }
        
        .table-highlight {
            position: relative;
        }
        
        .table-highlight::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: #fbc02d;
            animation: highlight-border 1s infinite;
        }
        
        @keyframes highlight-border {
            0% { opacity: 0.4; }
            50% { opacity: 1; }
            100% { opacity: 0.4; }
        }
    `;
    document.head.appendChild(style);
}

// تصدير الدوال للاستخدام العام
window.deleteMaterialFile = deleteMaterialFile;
window.addFileToTable = addFileToTable; 