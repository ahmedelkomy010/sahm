console.log('Simple Electrical Works Script Loaded');

// متغيرات عامة
let isInitialized = false;
let workOrderId = null;

// دالة للحصول على التاريخ بالتنسيق العربي
function getFormattedDate() {
    const today = new Date();
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        weekday: 'long',
        calendar: 'gregory'
    };
    return today.toLocaleDateString('ar', options);
}

// دالة للحصول على التاريخ الحالي
function getCurrentDate() {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// دالة تحديث الملخص اليومي
async function updateDailySummary(date) {
    try {
        const response = await fetch(`/admin/work-orders/${workOrderId}/electrical-works/daily-summary?date=${date}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (!response.ok) throw new Error('فشل في جلب البيانات');
        
        const data = await response.json();
        displayDailySummary(data.electrical_works || []);
        
    } catch (error) {
        console.error('Error fetching daily summary:', error);
        showMessage('حدث خطأ أثناء جلب البيانات اليومية', 'error');
    }
}

// دالة عرض الملخص اليومي
function displayDailySummary(electricalWorks) {
    const tbody = document.getElementById('summary-tbody');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    let totalAmount = 0;
    let totalItems = 0;
    let totalLength = 0;
    
    if (electricalWorks.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">لا توجد بيانات لهذا التاريخ</td></tr>';
        updateStatistics(0, 0, 0);
        return;
    }
    
    electricalWorks.forEach(work => {
        const row = document.createElement('tr');
        const length = parseFloat(work.length) || 0;
        const price = parseFloat(work.price) || 0;
        const total = parseFloat(work.total) || 0;
        
        row.innerHTML = `
            <td>${work.item_name}</td>
            <td class="text-center">${length.toFixed(2)} م</td>
            <td class="text-center">${price.toFixed(2)} ريال</td>
            <td class="text-center">${total.toFixed(2)} ريال</td>
            <td class="text-center">${work.work_date}</td>
        `;
        
        tbody.appendChild(row);
        
        if (length > 0 || price > 0) {
            totalLength += length;
            totalAmount += total;
            totalItems++;
        }
    });
    
    updateStatistics(totalLength, totalItems, totalAmount);
}

// دالة تحديث الإحصائيات
function updateStatistics(totalLength, totalItems, totalAmount) {
    const lengthElement = document.getElementById('total-length');
    const itemsElement = document.getElementById('total-items');
    const costElement = document.getElementById('total-cost');
    const amountElement = document.getElementById('total-amount');
    
    if (lengthElement) lengthElement.textContent = totalLength.toFixed(2);
    if (itemsElement) itemsElement.textContent = totalItems;
    if (costElement) costElement.textContent = totalAmount.toFixed(2);
    if (amountElement) amountElement.textContent = totalAmount.toFixed(2) + ' ريال';
}

// دالة حفظ البيانات اليومية
async function saveDailyElectricalWorks() {
    const date = document.getElementById('electrical_work_date').value;
    const electricalWorks = [];
    
    // جمع البيانات من النموذج
    const rows = document.querySelectorAll('#electrical-works-form tbody tr');
    
    rows.forEach(row => {
        const label = row.querySelector('td:first-child').textContent.trim();
        const lengthInput = row.querySelector('.electrical-length');
        const priceInput = row.querySelector('.electrical-price');
        const totalInput = row.querySelector('.electrical-total');
        
        if (lengthInput && priceInput && totalInput) {
            const length = parseFloat(lengthInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const total = parseFloat(totalInput.value) || 0;
            
            if (length > 0 || price > 0) {
                electricalWorks.push({
                    item_name: label,
                    length: length,
                    price: price,
                    total: total,
                    work_date: date
                });
            }
        }
    });
    
    if (electricalWorks.length === 0) {
        showMessage('لا توجد بيانات للحفظ', 'warning');
        return;
    }
    
    try {
        const response = await fetch(`/admin/work-orders/${workOrderId}/electrical-works/save-daily`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                electrical_works: electricalWorks,
                work_date: date
            })
        });
        
        if (!response.ok) throw new Error('فشل في حفظ البيانات');
        
        const result = await response.json();
        if (result.success) {
            showMessage('تم حفظ البيانات بنجاح', 'success');
            updateDailySummary(date);
        } else {
            throw new Error(result.message || 'فشل في حفظ البيانات');
        }
        
    } catch (error) {
        console.error('Error saving daily electrical works:', error);
        showMessage('حدث خطأ أثناء حفظ البيانات: ' + error.message, 'error');
    }
}

// دالة عرض الرسائل
function showMessage(message, type = 'info') {
    // إنشاء عنصر الرسالة
    const messageDiv = document.createElement('div');
    messageDiv.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : type === 'warning' ? 'warning' : 'info'} alert-dismissible fade show`;
    messageDiv.style.position = 'fixed';
    messageDiv.style.top = '20px';
    messageDiv.style.right = '20px';
    messageDiv.style.zIndex = '9999';
    messageDiv.style.minWidth = '300px';
    
    messageDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(messageDiv);
    
    // إزالة الرسالة بعد 5 ثوان
    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.parentNode.removeChild(messageDiv);
        }
    }, 5000);
}

// تهيئة مستمعي الأحداث عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // الحصول على معرف أمر العمل
    workOrderId = window.location.pathname.match(/work-orders\/(\d+)/)?.[1];
    
    // تحديث التاريخ الحالي
    document.getElementById('current-date').textContent = getCurrentDate();
    
    // تحديث تاريخ الملخص
    const summaryDateElement = document.getElementById('current-summary-date');
    if (summaryDateElement) {
        summaryDateElement.textContent = new Date().toLocaleDateString('ar', { calendar: 'gregory' });
    }
    
    // تحميل الملخص اليومي للتاريخ الحالي
    const currentDate = getCurrentDate();
    updateDailySummary(currentDate);
    
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
        window.saveTimeout = setTimeout(() => saveDailyElectricalWorks(), 2000);
    }
}

// تحديث الملخص
function updateSummary() {
    const tbody = document.getElementById('summary-tbody');
    if (!tbody) return;
    
    // سيتم تحديث الملخص من خلال updateDailySummary
}

// دالة حفظ الأعمال الكهربائية (للزر الرئيسي)
function saveElectricalWorks() {
    saveDailyElectricalWorks();
}

// دالة إعادة حساب الإجماليات
function updateAll() {
    const rows = document.querySelectorAll('#electrical-works-form tbody tr');
    
    rows.forEach(row => {
        const lengthInput = row.querySelector('.electrical-length');
        const priceInput = row.querySelector('.electrical-price');
        
        if (lengthInput && priceInput) {
            calculateRowTotal(lengthInput);
        }
    });
    
    showMessage('تم إعادة حساب جميع الإجماليات', 'success');
}

// دالة مسح جميع البيانات
function clearAllItems() {
    if (!confirm('هل أنت متأكد من مسح جميع البيانات؟')) {
        return;
    }
    
    const inputs = document.querySelectorAll('#electrical-works-form input[type="number"]');
    inputs.forEach(input => {
        input.value = '';
    });
    
    const tbody = document.getElementById('summary-tbody');
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">لا توجد بيانات</td></tr>';
    }
    
    updateStatistics(0, 0, 0);
    showMessage('تم مسح جميع البيانات', 'success');
}

// دالة طباعة الملخص
function printSummary() {
    const printWindow = window.open('', '_blank');
    const date = document.getElementById('electrical_work_date').value;
    const tbody = document.getElementById('summary-tbody');
    
    if (!tbody) return;
    
    printWindow.document.write(`
        <html>
        <head>
            <title>ملخص الأعمال الكهربائية</title>
            <style>
                body { font-family: Arial, sans-serif; direction: rtl; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
                th { background-color: #f2f2f2; }
                .header { text-align: center; margin-bottom: 20px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>ملخص الأعمال الكهربائية</h2>
                <p>التاريخ: ${date}</p>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>البند</th>
                        <th>الطول</th>
                        <th>السعر</th>
                        <th>الإجمالي</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    ${tbody.innerHTML}
                </tbody>
            </table>
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.print();
} 