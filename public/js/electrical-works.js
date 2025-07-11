// Electrical Works JavaScript Handler

document.addEventListener('DOMContentLoaded', function() {
    console.log('Electrical Works JS loaded');
    
    // إضافة مستمعي الأحداث لحقول الطول والسعر
    addEventListeners();
    
    // التحديث الأولي
    updateAll();
});

// إضافة مستمعي الأحداث
function addEventListeners() {
    const lengthInputs = document.querySelectorAll('.electrical-length');
    const priceInputs = document.querySelectorAll('.electrical-price');
    
    console.log('Length inputs found:', lengthInputs.length);
    console.log('Price inputs found:', priceInputs.length);
    
    // إضافة مستمعي الأحداث لحقول الطول
    lengthInputs.forEach(input => {
        input.addEventListener('input', debounce(() => {
            const row = input.closest('tr');
            calculateRowTotal(row);
            updateSummaryAndStats();
            debouncedSave();
        }, 300));
        
        input.addEventListener('blur', function() {
            const row = this.closest('tr');
            calculateRowTotal(row);
            updateSummaryAndStats();
            saveElectricalWorks();
        });
    });
    
    // إضافة مستمعي الأحداث لحقول السعر
    priceInputs.forEach(input => {
        input.addEventListener('input', debounce(() => {
            const row = input.closest('tr');
            calculateRowTotal(row);
            updateSummaryAndStats();
            debouncedSave();
        }, 300));
        
        input.addEventListener('blur', function() {
            const row = this.closest('tr');
            calculateRowTotal(row);
            updateSummaryAndStats();
            saveElectricalWorks();
        });
    });
}

// حساب إجمالي الصف
function calculateRowTotal(row) {
    const lengthInput = row.querySelector('.electrical-length');
    const priceInput = row.querySelector('.electrical-price');
    const totalInput = row.querySelector('.electrical-total');
    
    if (lengthInput && priceInput && totalInput) {
        const length = parseFloat(lengthInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const total = length * price;
        
        totalInput.value = total.toFixed(2);
        
        console.log('Row calculation:', {
            length: length,
            price: price,
            total: total
        });
        
        // إضافة تأثير بصري
        totalInput.style.backgroundColor = '#fff3cd';
        setTimeout(() => {
            totalInput.style.backgroundColor = '';
        }, 500);
    }
}

// تحديث جميع الحسابات
function updateAll() {
    console.log('Updating all calculations');
    
    // حساب جميع إجماليات الصفوف
    document.querySelectorAll('tbody tr').forEach(row => {
        calculateRowTotal(row);
    });
    
    // تحديث الملخص والإحصائيات
    updateSummaryAndStats();
}

// تحديث الملخص والإحصائيات
function updateSummaryAndStats() {
    updateSummaryTable();
    updateStatistics();
}

// تحديث جدول الملخص
function updateSummaryTable() {
    const tbody = document.getElementById('summary-tbody');
    if (!tbody) {
        console.log('Summary tbody not found');
        return;
    }
    
    tbody.innerHTML = '';
    let totalAmount = 0;
    
    console.log('Updating summary table');
    
    // البحث في جميع صفوف البيانات
    document.querySelectorAll('tbody tr').forEach(row => {
        const labelCell = row.querySelector('td:first-child');
        const lengthInput = row.querySelector('.electrical-length');
        const priceInput = row.querySelector('.electrical-price');
        const totalInput = row.querySelector('.electrical-total');
        
        if (labelCell && lengthInput && priceInput && totalInput) {
            const label = labelCell.textContent.trim();
            const length = parseFloat(lengthInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const total = parseFloat(totalInput.value) || 0;
            
            // إضافة الصف للملخص إذا كان هناك قيم
            if (length > 0 || price > 0) {
                const newRow = tbody.insertRow();
                newRow.innerHTML = `
                    <td>${label}</td>
                    <td class="text-center">${length.toFixed(0)}</td>
                    <td class="text-center">${price.toFixed(2)}</td>
                    <td class="text-center">${total.toFixed(2)}</td>
                `;
                totalAmount += total;
                
                console.log('Added row to summary:', {
                    label: label,
                    length: length,
                    price: price,
                    total: total
                });
            }
        }
    });
    
    // تحديث الإجمالي الكلي في الجدول
    const totalAmountElement = document.getElementById('total-amount');
    if (totalAmountElement) {
        totalAmountElement.textContent = totalAmount.toFixed(2);
    }
    
    // إضافة رسالة إذا لم تكن هناك بيانات
    if (tbody.children.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center text-muted py-4">
                    <i class="fas fa-info-circle fa-2x mb-2"></i><br>
                    لم يتم إدخال أي بيانات بعد
                </td>
            </tr>
        `;
    }
    
    console.log('Summary updated, total amount:', totalAmount.toFixed(2));
}

// تحديث الإحصائيات
function updateStatistics() {
    let totalLength = 0;
    let totalItems = 0;
    let totalCost = 0;
    
    console.log('Updating statistics');
    
    document.querySelectorAll('tbody tr').forEach(row => {
        const lengthInput = row.querySelector('.electrical-length');
        const totalInput = row.querySelector('.electrical-total');
        
        if (lengthInput && totalInput) {
            const length = parseFloat(lengthInput.value) || 0;
            const total = parseFloat(totalInput.value) || 0;
            
            if (length > 0) {
                totalLength += length;
                totalItems++;
                totalCost += total;
            }
        }
    });
    
    // تحديث العناصر في الواجهة
    const totalLengthElement = document.getElementById('total-length');
    const totalItemsElement = document.getElementById('total-items');
    const totalCostElement = document.getElementById('total-cost');
    
    if (totalLengthElement) {
        totalLengthElement.textContent = totalLength.toFixed(0);
    }
    
    if (totalItemsElement) {
        totalItemsElement.textContent = totalItems;
    }
    
    if (totalCostElement) {
        totalCostElement.textContent = totalCost.toFixed(2);
    }
    
    console.log('Statistics updated:', {
        totalLength: totalLength,
        totalItems: totalItems,
        totalCost: totalCost
    });
}

// حفظ البيانات
function saveElectricalWorks() {
    const form = document.getElementById('electrical-works-form');
    if (!form) {
        console.log('Form not found');
        return;
    }
    
    console.log('Saving electrical works data');
    console.log('Form action:', form.action);
    console.log('Form method:', form.method);
    
    const formData = new FormData(form);
    
    // طباعة البيانات المرسلة
    console.log('FormData contents:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response URL:', response.url);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            return response.text().then(text => {
                console.log('Response text:', text);
                throw new Error('Response is not JSON');
            });
        }
    })
    .then(data => {
        console.log('Save response:', data);
        if (data.success) {
            showAutoSaveIndicator();
        }
    })
    .catch(error => {
        console.error('Error saving data:', error);
        console.error('Error details:', error.message);
    });
}

// عرض مؤشر الحفظ التلقائي
function showAutoSaveIndicator() {
    const indicator = document.getElementById('auto-save-indicator');
    if (indicator) {
        indicator.style.display = 'block';
        setTimeout(() => {
            indicator.style.display = 'none';
        }, 2000);
    }
}

// دالة للحفظ التلقائي مع تأخير
const debouncedSave = debounce(saveElectricalWorks, 1000);

// دالة لتأخير التنفيذ
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// طباعة الملخص
function printSummary() {
    const printWindow = window.open('', '_blank');
    const summaryTable = document.getElementById('summary-table').cloneNode(true);
    
    const printContent = `
        <html dir="rtl">
        <head>
            <title>ملخص الأعمال الكهربائية</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body { font-family: Arial, sans-serif; }
                .table th, .table td { padding: 8px; }
                @media print {
                    .table { width: 100%; border-collapse: collapse; }
                    .table th, .table td { border: 1px solid #ddd; }
                }
            </style>
        </head>
        <body class="p-4">
            <h3 class="text-center mb-4">ملخص الأعمال الكهربائية</h3>
            ${summaryTable.outerHTML}
        </body>
        </html>
    `;
    
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.print();
} 