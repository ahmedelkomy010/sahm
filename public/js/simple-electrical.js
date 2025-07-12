console.log('Simple Electrical Works Script Loaded');

// متغيرات عامة
let isInitialized = false;

// دالة للحصول على التاريخ الحالي
function getCurrentDate() {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// دالة للحصول على التاريخ بالتنسيق العربي
function getFormattedDate() {
    const today = new Date();
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        weekday: 'long'
    };
    return today.toLocaleDateString('ar-SA', options);
}

// تهيئة مستمعي الأحداث عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // تحديث التاريخ الحالي
    document.getElementById('current-date').textContent = getCurrentDate();
    
    // تحديث تاريخ الملخص
    const summaryDateElement = document.getElementById('current-summary-date');
    if (summaryDateElement) {
        summaryDateElement.textContent = new Date().toLocaleDateString('ar-SA');
    }
    
    // إضافة مستمعي الأحداث لحقول الطول والسعر
    const lengthInputs = document.querySelectorAll('.electrical-length');
    const priceInputs = document.querySelectorAll('.electrical-price');
    
    lengthInputs.forEach(input => {
        input.addEventListener('input', function() {
            calculateRowTotal(this);
        });
    });
    
    priceInputs.forEach(input => {
        input.addEventListener('input', function() {
            calculateRowTotal(this);
        });
    });
    
    // تحديث الملخص الأولي
    updateSummary();
});

// حساب إجمالي الصف
function calculateRowTotal(input) {
    const row = input.closest('tr');
    const lengthInput = row.querySelector('.electrical-length');
    const priceInput = row.querySelector('.electrical-price');
    const totalInput = row.querySelector('.electrical-total');
    
    if (lengthInput && priceInput && totalInput) {
        const length = parseFloat(lengthInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const total = length * price;
        
        totalInput.value = total.toFixed(2);
        
        console.log('Calculated:', length, '×', price, '=', total);
        
        // تحديث الملخص
        updateSummary();
        
        // الحفظ التلقائي مع debounce
        clearTimeout(window.saveTimeout);
        window.saveTimeout = setTimeout(saveData, 2000);
    }
}

// تحديث الملخص
function updateSummary() {
    const tbody = document.getElementById('summary-tbody');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    let totalAmount = 0;
    let totalItems = 0;
    let totalLength = 0;
    
    // البحث في الجدول الرئيسي
    const rows = document.querySelectorAll('#electrical-works-form tbody tr');
    
    rows.forEach(row => {
        const label = row.querySelector('td:first-child');
        const lengthInput = row.querySelector('.electrical-length');
        const priceInput = row.querySelector('.electrical-price');
        const totalInput = row.querySelector('.electrical-total');
        
        if (label && lengthInput && priceInput && totalInput) {
            const labelText = label.textContent.trim();
            const length = parseFloat(lengthInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const total = parseFloat(totalInput.value) || 0;
            
            if (length > 0 && price > 0) {
                const newRow = tbody.insertRow();
                newRow.className = 'table-row-item';
                newRow.innerHTML = `
                    <td class="fw-bold">${labelText}</td>
                    <td class="text-center">${length.toFixed(0)} م</td>
                    <td class="text-center">${price.toFixed(2)} ريال</td>
                    <td class="text-center fw-bold text-primary">${total.toFixed(2)} ريال</td>
                    <td class="text-center text-muted">${getCurrentDate()}</td>
                `;
                
                totalAmount += total;
                totalItems++;
                totalLength += length;
            }
        }
    });
    
    // تحديث الإحصائيات
    const elements = {
        'total-length': totalLength.toFixed(0),
        'total-items': totalItems,
        'total-cost': totalAmount.toFixed(2),
        'total-amount': totalAmount.toFixed(2) + ' ريال'
    };
    
    Object.keys(elements).forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = elements[id];
        }
    });
    
    // تحديث التاريخ في التذييل
    const currentDateElement = document.getElementById('current-date');
    if (currentDateElement) {
        const now = new Date();
        const dateStr = now.toLocaleDateString('ar-SA');
        const timeStr = now.toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit' });
        currentDateElement.textContent = `${dateStr} - ${timeStr}`;
    }
    
    // إضافة تأثير بصري للصفوف
    const newRows = tbody.querySelectorAll('.table-row-item');
    newRows.forEach(row => {
        row.classList.add('highlight');
        setTimeout(() => {
            row.classList.remove('highlight');
        }, 1000);
    });
    
    console.log('Summary updated:', totalItems, 'items, total:', totalAmount);
}

// دالة عرض التنبيهات
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    const alertClass = type === 'error' ? 'alert-danger' : 'alert-success';
    alertDiv.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'check-circle'} me-2"></i>
            ${message}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    document.body.appendChild(alertDiv);

    // إخفاء التنبيه تلقائياً بعد 4 ثواني
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 4000);
}

// حفظ البيانات
function saveData() {
    const form = document.getElementById('electrical-works-form');
    if (!form) {
        console.error('Form not found');
        showAlert('خطأ: لم يتم العثور على النموذج', 'error');
        return;
    }
    
    console.log('Saving data...');
    
    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    
    if (!csrfToken) {
        console.error('CSRF token not found');
        showAlert('خطأ: لم يتم العثور على رمز الحماية', 'error');
        return;
    }
    
    // إضافة مؤشر الحفظ
    const indicator = document.getElementById('auto-save-indicator');
    if (indicator) {
        indicator.style.display = 'block';
        indicator.innerHTML = '<small class="text-info"><i class="fas fa-spinner fa-spin me-1"></i>جاري الحفظ...</small>';
    }
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken.content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Save response status:', response.status);
        console.log('Save response headers:', response.headers);
        
        if (response.ok) {
            return response.json();
        } else {
            return response.text().then(text => {
                console.error('Save response text:', text);
                throw new Error(`Save failed with status ${response.status}: ${text}`);
            });
        }
    })
    .then(data => {
        console.log('Save successful:', data);
        
        // إخفاء مؤشر الحفظ وإظهار رسالة النجاح
        if (indicator) {
            indicator.innerHTML = '<small class="text-success"><i class="fas fa-check me-1"></i>تم الحفظ بنجاح</small>';
            setTimeout(() => {
                indicator.style.display = 'none';
            }, 2000);
        }
        
        showAlert('تم حفظ البيانات بنجاح', 'success');
    })
    .catch(error => {
        console.error('Save error:', error);
        
        // إخفاء مؤشر الحفظ وإظهار رسالة الخطأ
        if (indicator) {
            indicator.innerHTML = '<small class="text-danger"><i class="fas fa-times me-1"></i>فشل الحفظ</small>';
            setTimeout(() => {
                indicator.style.display = 'none';
            }, 3000);
        }
        
        showAlert('حدث خطأ في حفظ البيانات: ' + error.message, 'error');
    });
}

// دوال للأزرار
function saveElectricalWorks() {
    saveData();
}

function updateAll() {
    const inputs = document.querySelectorAll('.electrical-length, .electrical-price');
    inputs.forEach(input => {
        if (input.value) {
            calculateRowTotal(input);
        }
    });
}

function printSummary() {
    window.print();
}

function clearAllItems() {
    if (confirm('هل أنت متأكد من مسح جميع البيانات؟ لا يمكن التراجع عن هذا الإجراء.')) {
        // مسح جميع القيم في الجدول
        const inputs = document.querySelectorAll('.electrical-length, .electrical-price, .electrical-total');
        inputs.forEach(input => {
            input.value = '';
        });
        
        // تحديث الملخص
        updateSummary();
        
        showAlert('تم مسح جميع البيانات بنجاح', 'success');
    }
} 