// Electrical Works JavaScript Handler

// تهيئة المتغيرات العامة
let debounceTimer;
let isNewDay = false;

// دالة لتأخير تنفيذ الوظيفة
function debounce(func, wait) {
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(debounceTimer);
            func(...args);
        };
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(later, wait);
    };
}

function getCurrentDate() {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// تحديث التاريخ في الصفحة
function updateDateDisplay() {
    const currentDate = getCurrentDate();
    // تحديث التاريخ في الهيدر
    document.getElementById('header-date').textContent = currentDate;
    // تحديث التاريخ في الجدول
    document.querySelectorAll('.current-date').forEach(element => {
        element.textContent = currentDate;
    });
}

// بدء يوم جديد
function startNewDay() {
    if (!confirm('هل أنت متأكد من أنك تريد بدء يوم جديد؟')) {
        return;
    }
    
    // تحديث التاريخ
    updateDateDisplay();
    
    // مسح البيانات وإعادة تعيين النموذج
    document.querySelectorAll('.electrical-length, .electrical-price').forEach(input => {
        input.value = '';
    });
    document.querySelectorAll('.electrical-total').forEach(input => {
        input.value = '0.00';
    });
    
    // تحديث الإجماليات
    updateAll();
}

// حساب إجمالي الصف
function calculateRowTotal(row) {
    const length = parseFloat(row.querySelector('.electrical-length').value) || 0;
    const price = parseFloat(row.querySelector('.electrical-price').value) || 0;
    const total = length * price;
    row.querySelector('.electrical-total').value = total.toFixed(2);
    return { length, price, total };
}

// تحديث الإحصائيات والملخص
function updateSummaryAndStats() {
    let totalLength = 0;
    let totalCost = 0;
    let activeItems = 0;

    // جمع البيانات من جميع الصفوف
    document.querySelectorAll('tr').forEach(row => {
        const lengthInput = row.querySelector('.electrical-length');
        const priceInput = row.querySelector('.electrical-price');
        const totalInput = row.querySelector('.electrical-total');

        if (lengthInput && priceInput && totalInput) {
            const length = parseFloat(lengthInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const total = parseFloat(totalInput.value) || 0;

            if (length > 0 || price > 0) {
                totalLength += length;
                totalCost += total;
                activeItems++;
            }
        }
    });

    // تحديث الإحصائيات
    document.getElementById('total-length').textContent = totalLength.toFixed(2);
    document.getElementById('total-items').textContent = activeItems;
    document.getElementById('total-cost').textContent = totalCost.toFixed(2);
    document.getElementById('total-amount').textContent = totalCost.toFixed(2);

    // تحديث جدول الملخص
    updateSummaryTable();
}

// تحديث جدول الملخص
function updateSummaryTable() {
    const tbody = document.getElementById('summary-tbody');
    const currentDate = getCurrentDate();
    tbody.innerHTML = '';

    // تحديث التاريخ الحالي في الجدول
    document.getElementById('current-date').textContent = currentDate;

    let totalAmount = 0;
    Object.entries(electricalItems).forEach(([key, label]) => {
        const length = parseFloat(document.querySelector(`input[name="electrical_works[${key}][length]"]`).value) || 0;
        const price = parseFloat(document.querySelector(`input[name="electrical_works[${key}][price]"]`).value) || 0;
        const total = length * price;

        if (length > 0 || price > 0) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${label}</td>
                <td class="text-center">${length}</td>
                <td class="text-center">${price.toFixed(2)}</td>
                <td class="text-center">${total.toFixed(2)}</td>
                <td class="text-center">${currentDate}</td>
            `;
            tbody.appendChild(row);
            totalAmount += total;
        }
    });

    document.getElementById('total-amount').textContent = totalAmount.toFixed(2);
}

// إضافة مستمعي الأحداث
function addEventListeners() {
    const lengthInputs = document.querySelectorAll('.electrical-length');
    const priceInputs = document.querySelectorAll('.electrical-price');
    
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

// حفظ البيانات
function saveElectricalWorks() {
    return new Promise((resolve, reject) => {
    const form = document.getElementById('electrical-works-form');
    if (!form) {
            console.error('Form not found');
            reject('Form not found');
        return;
    }
    
    const formData = new FormData(form);
        if (isNewDay) {
            formData.append('is_new_day', '1');
            isNewDay = false;
    }
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAutoSaveIndicator();
                loadDailyData();
                resolve(data);
            } else {
                reject(data.message);
        }
    })
    .catch(error => {
        console.error('Error saving data:', error);
            reject(error);
        });
    });
}

function saveElectricalWorksData() {
    const currentDate = getCurrentDate();
    
    $.ajax({
        url: `/admin/work-orders/${workOrderId}/electrical-works`,
        method: 'POST',
        data: {
            _token: csrfToken,
            daily_electrical_works_data: workOrder.daily_electrical_works_data,
            daily_electrical_works_dates: workOrder.daily_electrical_works_dates,
            daily_electrical_works_last_update: new Date().toISOString()
        },
        success: function(response) {
            toastr.success('تم حفظ البيانات بنجاح');
            displayDailySummaries();
        },
        error: function(xhr) {
            toastr.error('حدث خطأ أثناء حفظ البيانات');
        }
    });
}

// إظهار مؤشر الحفظ التلقائي
function showAutoSaveIndicator() {
    const indicator = document.getElementById('auto-save-indicator');
    if (indicator) {
        indicator.style.display = 'block';
        setTimeout(() => {
            indicator.style.display = 'none';
        }, 2000);
    }
}

// الحفظ المؤجل
const debouncedSave = debounce(() => {
    saveElectricalWorks();
}, 1000);

// تحديث جميع الحسابات
function updateAll() {
    document.querySelectorAll('tr').forEach(row => {
        calculateRowTotal(row);
    });
    updateSummaryAndStats();
}

// تحميل البيانات اليومية
function loadDailyData() {
    const url = window.location.href.replace('/electrical-works', '/electrical-works/daily-data');
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateDailySummary(data.data);
            }
        })
        .catch(error => console.error('Error loading daily data:', error));
}

function displayDailySummaries() {
    const summariesContainer = $('#dailySummaries');
    summariesContainer.empty();

    const dates = workOrder.daily_electrical_works_dates || [];
    const dailyData = workOrder.daily_electrical_works_data || {};

    dates.sort().reverse().forEach(date => {
        const dayData = dailyData[date] || { items: [], notes: '', totals: { totalLength: 0, totalItems: 0, totalCost: 0 } };
        
        const summaryHtml = `
            <div class="daily-summary mb-4 border rounded p-4">
                <h3 class="text-lg font-bold mb-2">ملخص يوم ${date}</h3>
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div>
                        <span class="font-bold">إجمالي الطول:</span>
                        <span>${dayData.totals.totalLength} متر</span>
                    </div>
                    <div>
                        <span class="font-bold">إجمالي العناصر:</span>
                        <span>${dayData.totals.totalItems}</span>
                    </div>
                    <div>
                        <span class="font-bold">إجمالي التكلفة:</span>
                        <span>${dayData.totals.totalCost} ريال</span>
                    </div>
                </div>
                <div class="mb-4">
                    <h4 class="font-bold mb-2">العناصر:</h4>
                    <ul class="list-disc list-inside">
                        ${dayData.items.map(item => `
                            <li>${item.description} - ${item.length} متر - ${item.quantity} قطعة - ${item.price} ريال</li>
                        `).join('')}
                    </ul>
                </div>
                ${dayData.notes ? `
                    <div class="mb-4">
                        <h4 class="font-bold mb-2">ملاحظات:</h4>
                        <p>${dayData.notes}</p>
                    </div>
                ` : ''}
            </div>
        `;
        
        summariesContainer.append(summaryHtml);
    });
}

// تحديث عرض الملخص اليومي
function updateDailySummary(dailyData) {
    const summaryContainer = document.getElementById('daily-summary');
    summaryContainer.innerHTML = '';

    // ترتيب التواريخ تنازلياً
    const dates = Object.keys(dailyData).sort().reverse();

    dates.forEach((date, index) => {
        const dayData = dailyData[date];
        const items = dayData.items;
        
        let itemsHtml = '';
        let dayTotal = 0;
        
        for (const [key, value] of Object.entries(items)) {
            if (value.length > 0 || value.price > 0) {
                const itemLabel = document.querySelector(`[name="electrical_works[${key}][length]"]`)
                    ?.closest('tr')?.querySelector('td:first-child')?.textContent || key;
                
                const total = parseFloat(value.total) || 0;
                dayTotal += total;
                
                itemsHtml += `
                    <tr>
                        <td>${itemLabel}</td>
                        <td class="text-center">${value.length}</td>
                        <td class="text-center">${value.price}</td>
                        <td class="text-center">${value.total}</td>
                    </tr>
                `;
            }
        }

        const accordionItem = `
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button ${index === 0 ? '' : 'collapsed'}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${date}">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div>
                                <i class="fas fa-calendar-day me-2"></i>
                                ${new Date(date).toLocaleDateString('ar-SA')}
                                <small class="ms-2 text-muted">${new Date(dayData.created_at).toLocaleTimeString('ar-SA')}</small>
                            </div>
                            <div class="badge bg-success ms-2">
                                الإجمالي: ${dayTotal.toFixed(2)}
                            </div>
                        </div>
                    </button>
                </h2>
                <div id="collapse-${date}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>البند</th>
                                        <th>الطول</th>
                                        <th>السعر</th>
                                        <th>الإجمالي</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${itemsHtml}
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-start fw-bold">إجمالي اليوم:</td>
                                        <td class="text-center fw-bold">${dayTotal.toFixed(2)}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        ${dayData.notes ? `
                            <div class="mt-3">
                                <strong>ملاحظات:</strong>
                                <p class="mb-0">${dayData.notes}</p>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;

        summaryContainer.insertAdjacentHTML('beforeend', accordionItem);
    });

    if (dates.length === 0) {
        summaryContainer.innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="fas fa-info-circle mb-2"></i>
                <p class="mb-0">لا توجد بيانات يومية مسجلة</p>
            </div>
        `;
    }
}

// مسح البيانات اليومية
function clearDailyData() {
    if (!confirm('هل أنت متأكد من مسح جميع البيانات اليومية؟')) {
        return;
    }

    const url = window.location.href.replace('/electrical-works', '/electrical-works/clear-daily-data');
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadDailyData();
            toastr.success('تم مسح البيانات اليومية بنجاح');
        } else {
            toastr.error(data.message);
        }
    })
    .catch(error => {
        console.error('Error clearing daily data:', error);
        toastr.error('حدث خطأ أثناء مسح البيانات');
    });
}

function updateAllDates() {
    const currentDate = getCurrentDate();
    document.querySelectorAll('.current-date').forEach(cell => {
        cell.textContent = currentDate;
    });
}

// تحديث التاريخ عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    updateDateDisplay();
    // إضافة مستمعات الأحداث الأخرى
    addEventListeners();
    updateAll();
});

// تحديث التاريخ عند بدء يوم جديد
function startNewDay() {
    if (!confirm('هل أنت متأكد من أنك تريد بدء يوم جديد؟')) {
        return;
    }
    
    // تحديث التاريخ
    updateDateDisplay();
    
    // مسح البيانات وإعادة تعيين النموذج
    document.querySelectorAll('.electrical-length, .electrical-price').forEach(input => {
        input.value = '';
    });
    document.querySelectorAll('.electrical-total').forEach(input => {
        input.value = '0.00';
    });
    
    // تحديث الإجماليات
    updateAll();
}

// طباعة الملخص
function printSummary() {
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html dir="rtl">
        <head>
            <title>ملخص الأعمال الكهربائية</title>
            <style>
                body { font-family: Arial, sans-serif; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
                th { background-color: #f5f5f5; }
                .text-center { text-align: center; }
                .mt-3 { margin-top: 15px; }
            </style>
        </head>
        <body>
            <h2 class="text-center">ملخص الأعمال الكهربائية</h2>
            ${document.getElementById('summary-table').outerHTML}
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
} 