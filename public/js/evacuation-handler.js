// دوال التعامل مع بيانات الإخلاء
// متغير عام لتتبع حالة التحميل
let isLoadingEvacuationData = false;

function loadEvacuationDataForLicense(licenseId) {
    if (!licenseId || isLoadingEvacuationData) return;
    
    isLoadingEvacuationData = true;
    
    // إظهار مؤشر التحميل
    const tbody = document.getElementById('evacuationDataTable').getElementsByTagName('tbody')[0];
    tbody.innerHTML = `
        <tr>
            <td colspan="10" class="text-center py-5">
                <div class="d-flex flex-column align-items-center">
                    <div class="spinner-border text-success mb-3" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                    <p class="mb-0 text-success">جاري تحميل بيانات الإخلاءات...</p>
                </div>
            </td>
        </tr>
    `;

    fetch(`/admin/licenses/get-evacuation-data/${licenseId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            tbody.innerHTML = '';
            
            if (data.success && data.evacuation_data && data.evacuation_data.length > 0) {
                data.evacuation_data.forEach((item, index) => {
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td class="text-center align-middle">${index + 1}</td>
                        <td>
                            <select class="form-select form-select-sm" name="evacuation_data[${index + 1}][is_evacuated]" required>
                                <option value="">-- اختر --</option>
                                <option value="1" ${item.is_evacuated == '1' ? 'selected' : ''}>نعم</option>
                                <option value="0" ${item.is_evacuated == '0' ? 'selected' : ''}>لا</option>
                            </select>
                        </td>
                        <td>
                            <input type="date" class="form-control form-control-sm" 
                                   name="evacuation_data[${index + 1}][evacuation_date]" 
                                   value="${item.evacuation_date || ''}" required>
                        </td>
                        <td>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" class="form-control" 
                                       name="evacuation_data[${index + 1}][evacuation_amount]" 
                                       value="${item.evacuation_amount || ''}" 
                                       placeholder="0.00" required>
                                <span class="input-group-text">ريال</span>
                            </div>
                        </td>
                        <td>
                            <input type="datetime-local" class="form-control form-control-sm" 
                                   name="evacuation_data[${index + 1}][evacuation_datetime]" 
                                   value="${item.evacuation_datetime || ''}" required>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm" 
                                   name="evacuation_data[${index + 1}][payment_number]" 
                                   value="${item.payment_number || ''}" 
                                   placeholder="رقم سداد الإخلاء" required>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm" 
                                   name="evacuation_data[${index + 1}][clearance_number]" 
                                   value="${item.clearance_number || ''}" 
                                   placeholder="رقم الفسح">
                        </td>
                        <td>
                            <textarea class="form-control form-control-sm" 
                                      name="evacuation_data[${index + 1}][notes]" 
                                      rows="1" placeholder="ملاحظات الإخلاء">${item.notes || ''}</textarea>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                ${item.attachments && item.attachments.length > 0 ? `
                                    <div class="mb-1">
                                        <small class="text-success">
                                            <i class="fas fa-paperclip"></i>
                                            ${item.attachments.length} مرفق
                                        </small>
                                    </div>
                                ` : ''}
                                <input type="file" class="form-control form-control-sm" 
                                       name="evacuation_data[${index + 1}][attachments][]" 
                                       accept=".pdf,.jpg,.jpeg,.png" multiple>
                            </div>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm" 
                                    onclick="deleteEvacuationRow(this)" 
                                    title="حذف الصف">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(newRow);
                });
            } else {
                tbody.innerHTML = `
                    <tr id="no-evacuation-data-row">
                        <td colspan="10" class="text-center text-muted py-5">
                            <i class="fas fa-truck fa-2x mb-3"></i>
                            <p class="mb-1">لا توجد بيانات إخلاء مسجلة</p>
                            <small class="text-muted">اضغط على زر "إضافة بيانات إخلاء جديدة" لإضافة بيانات</small>
                        </td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading evacuation data:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="10" class="text-center text-danger py-5">
                        <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                        <p class="mb-1">حدث خطأ أثناء تحميل بيانات الإخلاءات</p>
                        <small class="text-muted">${error.message}</small>
                    </td>
                </tr>
            `;
        })
        .finally(() => {
            isLoadingEvacuationData = false;
        });
}

function loadSavedEvacuationAttachments(licenseId) {
    const container = document.getElementById('saved-evacuation-attachments');
    if (!container) return;
    
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
                                <button type="button" class="btn btn-outline-danger" onclick="deleteEvacuationAttachment(${index})" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
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
            if (container) {
                container.innerHTML = `
                    <div class="text-center text-danger">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <br>خطأ في تحميل المرفقات
                    </div>
                `;
            }
        });
}

function deleteEvacuationRow(button) {
    if (!confirm('هل أنت متأكد من حذف هذا السجل؟')) {
        return;
    }

    const row = button.closest('tr');
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

function deleteEvacuationAttachment(index) {
    const licenseIdField = document.getElementById('evacuation-license-id');
    if (!licenseIdField) {
        if (typeof toastr !== 'undefined') {
            toastr.error('لا توجد رخصة محددة');
        }
        return;
    }
    
    const licenseId = licenseIdField.value;
    if (!licenseId) {
        if (typeof toastr !== 'undefined') {
            toastr.error('لا توجد رخصة محددة');
        }
        return;
    }

    if (!confirm('هل أنت متأكد من حذف هذا المرفق؟')) {
        return;
    }

    fetch(`/admin/licenses/${licenseId}/evacuation-attachments/${index}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof toastr !== 'undefined') {
                toastr.success('تم حذف المرفق بنجاح');
            }
            loadSavedEvacuationAttachments(licenseId);
        } else {
            if (typeof toastr !== 'undefined') {
                toastr.error(data.message || 'حدث خطأ أثناء حذف المرفق');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof toastr !== 'undefined') {
            toastr.error('حدث خطأ أثناء حذف المرفق');
        }
    });
}

function addNewEvacuationRow() {
    const tbody = document.getElementById('evacuationDataTable').getElementsByTagName('tbody')[0];
    const noRowsMessage = document.getElementById('no-evacuation-data-row');
    
    if (noRowsMessage) {
        noRowsMessage.remove();
    }

    const rowCount = tbody.rows.length + 1;
    const newRow = document.createElement('tr');
    
    newRow.innerHTML = `
        <td class="text-center align-middle">${rowCount}</td>
        <td>
            <select class="form-select form-select-sm" name="evacuation_data[${rowCount}][is_evacuated]" required>
                <option value="">-- اختر --</option>
                <option value="1">نعم</option>
                <option value="0">لا</option>
            </select>
        </td>
        <td>
            <input type="date" class="form-control form-control-sm" 
                   name="evacuation_data[${rowCount}][evacuation_date]" required>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <input type="number" step="0.01" class="form-control" 
                       name="evacuation_data[${rowCount}][evacuation_amount]" 
                       placeholder="0.00" required>
                <span class="input-group-text">ريال</span>
            </div>
        </td>
        <td>
            <input type="datetime-local" class="form-control form-control-sm" 
                   name="evacuation_data[${rowCount}][evacuation_datetime]" required>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" 
                   name="evacuation_data[${rowCount}][payment_number]" 
                   placeholder="رقم سداد الإخلاء" required>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" 
                   name="evacuation_data[${rowCount}][clearance_number]" 
                   placeholder="رقم الفسح">
        </td>
        <td>
            <textarea class="form-control form-control-sm" 
                      name="evacuation_data[${rowCount}][notes]" 
                      rows="1" placeholder="ملاحظات الإخلاء"></textarea>
        </td>
        <td>
            <div class="d-flex flex-column gap-1">
                <input type="file" class="form-control form-control-sm" 
                       name="evacuation_data[${rowCount}][attachments][]" 
                       accept=".pdf,.jpg,.jpeg,.png" multiple>
            </div>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm" 
                    onclick="deleteEvacuationRow(this)" 
                    title="حذف الصف">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(newRow);
    
    // تحديث أرقام الصفوف
    updateEvacuationRowNumbers();
}

// تحديث أرقام الصفوف
function updateEvacuationRowNumbers() {
    const tbody = document.getElementById('evacuationDataTable').getElementsByTagName('tbody')[0];
    const rows = tbody.getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        if (rows[i].id !== 'no-evacuation-data-row') {
            rows[i].cells[0].textContent = i + 1;
        }
    }
}

// تفعيل tooltips
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// تهيئة معالجات الإخلاء عند تحميل الصفحة
function initializeEvacuationHandlers() {
    // معالج حقل اختيار مرفق الإخلاء
    const evacuationAttachment = document.getElementById('evacuation-attachment');
    if (evacuationAttachment && !evacuationAttachment.hasAttribute('data-handled')) {
        evacuationAttachment.setAttribute('data-handled', 'true');
        evacuationAttachment.addEventListener('change', function() {
            const preview = document.getElementById('evacuation-file-preview');
            const fileName = document.getElementById('evacuation-file-name');
            
            if (this.files.length > 0) {
                const file = this.files[0];
                if (fileName) fileName.textContent = file.name;
                if (preview) preview.style.display = 'block';
            } else {
                if (preview) preview.style.display = 'none';
            }
        });
    }

    // تحميل البيانات إذا كانت هناك رخصة محددة
    const licenseIdField = document.getElementById('evacuation-license-id');
    if (licenseIdField && licenseIdField.value) {
        const licenseId = licenseIdField.value;
        loadEvacuationDataForLicense(licenseId);
        loadSavedEvacuationAttachments(licenseId);
    }
}

// تشغيل التهيئة عند تحميل الصفحة
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeEvacuationHandlers);
} else {
    initializeEvacuationHandlers();
} 

function saveAllEvacuationData() {
    const licenseId = document.getElementById('evacuation-license-id').value;
    if (!licenseId) {
        toastr.error('يرجى اختيار رخصة أولاً');
        return;
    }

    // تجميع البيانات من الجدول
    const rows = document.getElementById('evacuationDataTable').getElementsByTagName('tbody')[0].rows;
    let hasValidData = false;
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
                clearance_number: row.querySelector('[name*="[clearance_number]"]').value,
                notes: row.querySelector('[name*="[notes]"]').value,
                attachments: Array.from(row.querySelector('[name*="[attachments]"]').files)
            };
            
            // التحقق من وجود البيانات المطلوبة
            if (rowData.is_evacuated && rowData.evacuation_date && rowData.evacuation_amount && 
                rowData.evacuation_datetime && rowData.payment_number) {
                evacuationDataArray.push(rowData);
                hasValidData = true;
            }
        }
    }

    if (!hasValidData) {
        toastr.warning('لا توجد بيانات صالحة للحفظ. تأكد من ملء جميع الحقول المطلوبة.');
        return;
    }

    // إنشاء FormData وإضافة البيانات
    const formData = new FormData();
    formData.append('license_id', licenseId);
    formData.append('work_order_id', document.querySelector('input[name="work_order_id"]').value);
    
    // إضافة بيانات الإخلاءات
    evacuationDataArray.forEach((data, index) => {
        Object.keys(data).forEach(key => {
            if (key === 'attachments') {
                // إضافة المرفقات
                data[key].forEach(file => {
                    formData.append(`evacuation_data[${index}][attachments][]`, file);
                });
            } else {
                formData.append(`evacuation_data[${index}][${key}]`, data[key]);
            }
        });
    });

    // إظهار مؤشر التحميل
    const saveButton = document.querySelector('button[onclick="saveAllEvacuationData()"]');
    const originalButtonHtml = saveButton.innerHTML;
    saveButton.disabled = true;
    saveButton.innerHTML = `
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        جاري الحفظ...
    `;

    // إرسال البيانات
    fetch('/admin/licenses/save-evacuation-data', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            toastr.success('تم حفظ بيانات الإخلاءات بنجاح');
            
            // إعادة تحميل البيانات
            loadEvacuationDataForLicense(licenseId);
        } else {
            toastr.error(data.message || 'حدث خطأ أثناء حفظ البيانات');
        }
    })
    .catch(error => {
        console.error('Error saving evacuation data:', error);
        toastr.error('حدث خطأ أثناء حفظ البيانات');
    })
    .finally(() => {
        // إعادة تفعيل زر الحفظ
        saveButton.disabled = false;
        saveButton.innerHTML = originalButtonHtml;
    });
} 

function viewAttachments(button) {
    const row = button.closest('tr');
    const fileInput = row.querySelector('input[type="file"]');
    
    // If no files are selected yet, open file selection
    if (!fileInput.files.length) {
        fileInput.click();
        return;
    }
    
    const rowIndex = Array.from(row.parentNode.children).indexOf(row);
    
    // Show files in a modal
    let filesList = '';
    for (let i = 0; i < fileInput.files.length; i++) {
        const file = fileInput.files[i];
        filesList += `
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-file me-2"></i>
                <span>${file.name}</span>
            </div>
        `;
    }
    
    // Create and show modal
    const modalHtml = `
        <div class="modal fade" id="attachmentsModal${rowIndex}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">المرفقات</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        ${filesList}
                        <div class="mt-3">
                            <button type="button" class="btn btn-primary btn-sm" onclick="document.querySelector('tr:nth-child(${rowIndex + 1}) input[type=file]').click()">
                                <i class="fas fa-plus me-1"></i>
                                إضافة مرفقات
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Add modal to document if it doesn't exist
    if (!document.getElementById(`attachmentsModal${rowIndex}`)) {
        document.body.insertAdjacentHTML('beforeend', modalHtml);
    }
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById(`attachmentsModal${rowIndex}`));
    modal.show();
}

// Update attachment count when files are selected
function updateAttachmentCount(input) {
    const row = input.closest('tr');
    const countSpan = row.querySelector('.attachment-count');
    if (countSpan) {
        countSpan.textContent = input.files.length;
    }
} 