// عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // التأكد من وجود المتغير العام أو تهيئته
    if (typeof window.workItemRowIndex === 'undefined') {
        window.workItemRowIndex = document.querySelectorAll('#workItemsBody tr').length;
    }

    // تهيئة نموذج استيراد Excel
    initializeExcelImport();
});

// تهيئة وظائف استيراد Excel
function initializeExcelImport() {
    const form = document.getElementById('excelImportForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const progressBar = document.getElementById('uploadProgress');
        const progressBarInner = progressBar.querySelector('.progress-bar');

        // التحقق من وجود ملف
        const fileInput = this.querySelector('input[type="file"]');
        if (!fileInput.files.length) {
            toastr.error('الرجاء اختيار ملف');
            return;
        }

        // التحقق من نوع الملف
        const file = fileInput.files[0];
        const allowedTypes = [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/csv'
        ];
        if (!allowedTypes.includes(file.type)) {
            toastr.error('نوع الملف غير مدعوم. الرجاء استخدام ملف Excel (.xlsx, .xls) أو CSV');
            return;
        }

        // التحقق من حجم الملف (10MB كحد أقصى)
        if (file.size > 10 * 1024 * 1024) {
            toastr.error('حجم الملف كبير جداً. الحد الأقصى هو 10 ميجابايت');
            return;
        }

        // إظهار شريط التقدم
        progressBar.classList.remove('d-none');
        progressBarInner.style.width = '0%';
        progressBarInner.setAttribute('aria-valuenow', '0');

        // تعطيل زر الرفع
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;

        // إرسال الملف
        fetch('/admin/work-orders/import-work-items', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(response => {
                // تحديث شريط التقدم إلى 100%
                progressBarInner.style.width = '100%';
                progressBarInner.setAttribute('aria-valuenow', '100');

                if (response.success) {
                    toastr.success(response.message);

                    // إضافة البنود المستوردة للجدول
                    if (response.imported_items && response.imported_items.length > 0) {
                        response.imported_items.forEach(function(item) {
                            addWorkItemToTable(item);
                        });
                    }

                    // إغلاق المودال
                    const modal = bootstrap.Modal.getInstance(document.getElementById('excelImportModal'));
                    modal.hide();

                    // إعادة تعيين النموذج
                    form.reset();
                } else {
                    toastr.error(response.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('حدث خطأ أثناء رفع الملف');
            })
            .finally(() => {
                // إعادة تمكين زر الرفع
                submitButton.disabled = false;

                // إخفاء شريط التقدم بعد ثانيتين
                setTimeout(() => {
                    progressBar.classList.add('d-none');
                    progressBarInner.style.width = '0%';
                    progressBarInner.setAttribute('aria-valuenow', '0');
                }, 2000);
            });
    });
}

// دالة إضافة بند عمل للجدول
function addWorkItemToTable(item) {
    const tbody = document.getElementById('workItemsBody');
    if (!tbody) return;

    const row = document.createElement('tr');

    row.innerHTML = `
        <td>
            <input type="hidden" name="work_items[${window.workItemRowIndex}][work_item_id]" value="${item.id}">
            <div class="d-flex align-items-center">
                <span class="badge bg-primary me-2">${item.code}</span>
                ${item.description}
            </div>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm" 
                   name="work_items[${window.workItemRowIndex}][planned_quantity]" 
                   value="1" min="0" step="0.01" required>
        </td>
        <td>
            <span class="badge bg-secondary">${item.unit}</span>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" 
                   name="work_items[${window.workItemRowIndex}][notes]" 
                   placeholder="ملاحظات">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;

    tbody.appendChild(row);
    window.workItemRowIndex++;
}

// دالة إضافة صف جديد
function addNewRow() {
    const tbody = document.getElementById('workItemsBody');
    if (!tbody) return;

    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select name="work_items[${window.workItemRowIndex}][work_item_id]" class="form-select form-select-sm" required>
                <option value="">اختر بند العمل</option>
                ${getWorkItemsOptions()}
            </select>
        </td>
        <td>
            <input type="number" name="work_items[${window.workItemRowIndex}][planned_quantity]" 
                   class="form-control form-control-sm" step="0.01" min="0" placeholder="الكمية" required>
        </td>
        <td>
            <span class="text-muted unit-display"></span>
        </td>
        <td>
            <input type="text" name="work_items[${window.workItemRowIndex}][notes]" 
                   class="form-control form-control-sm" placeholder="ملاحظات">
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;

    tbody.appendChild(row);

    // تحديث عرض الوحدة عند تغيير البند
    const select = row.querySelector('select');
    select.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const unit = selectedOption.getAttribute('data-unit');
        row.querySelector('.unit-display').textContent = unit || '';
    });

    window.workItemRowIndex++;
}

// دالة حذف صف
function removeRow(button) {
    button.closest('tr').remove();
}

// دالة مساعدة للحصول على خيارات بنود العمل
function getWorkItemsOptions() {
    const workItems = window.workItems || [];
    return workItems.map(item => `
        <option value="${item.id}" data-unit="${item.unit}">
            ${item.code} - ${item.description}
        </option>
    `).join('');
}