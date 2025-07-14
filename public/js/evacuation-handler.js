// دوال التعامل مع بيانات الإخلاء
function loadEvacuationDataForLicense(licenseId) {
    if (!licenseId) return;
    
    fetch(`/admin/licenses/get-evacuation-data/${licenseId}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('evacuationDataTable').getElementsByTagName('tbody')[0];
            tbody.innerHTML = '';
            
            if (data.success && data.evacuation_data && data.evacuation_data.length > 0) {
                data.evacuation_data.forEach((item, index) => {
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td class="text-center fw-bold">${index + 1}</td>
                        <td>
                            <select class="form-select form-select-sm" name="evacuation_data[${index + 1}][is_evacuated]" required>
                                <option value="">-- اختر --</option>
                                <option value="1" ${item.is_evacuated == '1' ? 'selected' : ''}>نعم</option>
                                <option value="0" ${item.is_evacuated == '0' ? 'selected' : ''}>لا</option>
                            </select>
                        </td>
                        <td>
                            <input type="date" class="form-control form-control-sm" name="evacuation_data[${index + 1}][evacuation_date]" value="${item.evacuation_date || ''}" required>
                        </td>
                        <td>
                            <input type="number" step="0.01" class="form-control form-control-sm" name="evacuation_data[${index + 1}][evacuation_amount]" value="${item.evacuation_amount || ''}" placeholder="0.00" required>
                        </td>
                        <td>
                            <input type="datetime-local" class="form-control form-control-sm" name="evacuation_data[${index + 1}][evacuation_datetime]" value="${item.evacuation_datetime || ''}" required>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm" name="evacuation_data[${index + 1}][payment_number]" value="${item.payment_number || ''}" placeholder="رقم سداد الإخلاء" required>
                        </td>
                        <td>
                            <textarea class="form-control form-control-sm" name="evacuation_data[${index + 1}][notes]" rows="2" placeholder="ملاحظات الإخلاء">${item.notes || ''}</textarea>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteEvacuationRow(this)" title="حذف الصف">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(newRow);
                });
            } else {
                tbody.innerHTML = `
                    <tr id="no-evacuation-data-row">
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-truck fa-2x mb-2"></i>
                            <br>لا توجد بيانات إخلاء مسجلة لهذه الرخصة
                        </td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading evacuation data:', error);
            if (typeof toastr !== 'undefined') {
                toastr.error('حدث خطأ في تحميل بيانات الإخلاء');
            }
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

function updateEvacuationRowNumbers() {
    const tbody = document.getElementById('evacuationDataTable');
    if (!tbody) return;
    
    const rows = tbody.querySelectorAll('tr:not(#no-evacuation-data-row)');
    
    rows.forEach((row, index) => {
        const firstCell = row.cells[0];
        if (firstCell) {
            firstCell.textContent = index + 1;
        }
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