/**
 * نظام الأعمال المدنية المبسط - الإصدار 8.1
 * Civil Works System - Version 8.1
 */



/**
 * تحميل البيانات المحفوظة
 */
function loadSavedData() {
    try {
        // الحصول على معرف أمر العمل
        const match = window.location.href.match(/work-orders\/(\d+)/);
        const workOrderId = match ? match[1] : null;

        if (!workOrderId) {
            console.error('لم يتم العثور على رقم أمر العمل');
            return;
        }

        // تهيئة المتغير العام
        window.savedDailyData = [];

        // الحصول على رمز CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            console.error('لم يتم العثور على رمز الحماية');
            return;
        }

        // طلب البيانات من الخادم
        fetch(`/admin/work-orders/${workOrderId}/get-daily-civil-works`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(result => {
            if (result.success && result.data && result.data.daily_data) {
                try {
                    const savedData = JSON.parse(result.data.daily_data);
                    if (Array.isArray(savedData) && savedData.length > 0) {
                        window.savedDailyData = savedData;
                        updateSummaryTable(savedData);
                        fillFormWithSavedData(savedData);
                    }
                } catch (parseError) {
                    console.error('خطأ في تحليل البيانات المحفوظة:', parseError);
                }
            }
        })
        .catch(error => {
            console.error('خطأ في تحميل البيانات:', error);
        });
    } catch (error) {
        console.error('خطأ في تحميل البيانات:', error);
    }
}

/**
 * ملء النموذج بالبيانات المحفوظة
 */
function fillFormWithSavedData(savedData) {
    try {
        savedData.forEach((item, index) => {
            // البحث عن الصف المناسب
            const rows = document.querySelectorAll('tr');
            const row = rows[index + 1]; // +1 لتجاوز صف العناوين
            if (!row) return;

            // ملء حقول الأبعاد
            const inputs = row.querySelectorAll('input[type="number"]');
            let dimensionIndex = 0;
            
            inputs.forEach(input => {
                if (input.name.includes('price') || input.name.includes('سعر')) {
                    input.value = item.price.toFixed(2);
                } else if (dimensionIndex < item.dimensions.length) {
                    input.value = item.dimensions[dimensionIndex].toFixed(2);
                    dimensionIndex++;
                }
            });

            // تحديث حسابات الصف
            calculateRowTotal(row);
        });
    } catch (error) {
        console.error('خطأ في ملء النموذج:', error);
    }
}

/**
 * إعداد الحسابات
 */
function setupCalculations() {
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', () => {
            const row = input.closest('tr');
            if (row) {
                calculateRowTotal(row);
            }
        });
    });
}

/**
 * حساب إجمالي الصف
 */
function calculateRowTotal(row) {
    const inputs = row.querySelectorAll('input[type="number"]');
    let values = [];
    let price = 0;

    inputs.forEach(input => {
        const value = parseFloat(input.value) || 0;
        if (value > 0) {
            if (input.name.includes('price') || input.name.includes('سعر')) {
                price = value;
            } else {
                values.push(value);
            }
        }
    });

    // حساب الإجمالي
    let total = 0;
    if (values.length > 0 && price > 0) {
        total = values.reduce((acc, val) => acc * val, 1) * price;
    }

    // تحديث عرض الإجمالي في الصف
    const totalElement = row.querySelector('.total-display, .total-calc, [class*="total"]');
    if (totalElement) {
        if (totalElement.tagName === 'INPUT') {
            totalElement.value = total.toFixed(2);
        } else {
            totalElement.textContent = total.toFixed(2) + ' ريال';
        }
    }
}

/**
 * جمع البيانات من الجدول
 */
function collectData() {
    const data = [];

    document.querySelectorAll('tr').forEach((row, index) => {
        // تجاهل صفوف العناوين
        if (row.querySelector('th')) return;

        const inputs = row.querySelectorAll('input[type="number"]');
        let values = [];
        let price = 0;

        inputs.forEach(input => {
            const value = parseFloat(input.value) || 0;
            if (value > 0) {
                if (input.name.includes('price') || input.name.includes('سعر')) {
                    price = value;
                } else {
                    values.push(value);
                }
            }
        });

        if (values.length > 0 && price > 0) {
            // تحديد نوع الحفرية والكابل
            let excavationType = '';
            let cableType = '';
            const typeCell = row.querySelector('td');
            if (typeCell) {
                const excavationSelect = typeCell.querySelector('select[name="excavation_type"]');
                const cableSelect = typeCell.querySelector('select[name="cable_type"]');
                excavationType = excavationSelect ? excavationSelect.value : 'حفرية عامة';
                cableType = cableSelect ? cableSelect.value : 'كابل عام';
            }

            // حساب الإجمالي
            const total = values.reduce((acc, val) => acc * val, 1) * price;

            data.push({
                excavation_type: excavationType,
                cable_type: cableType,
                dimensions: values,
                price: price,
                total: total,
                timestamp: new Date().toLocaleString('en-US', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                })
            });
        }
    });

    return data;
}

/**
 * حفظ البيانات
 */
function saveData() {
    try {
        // جمع البيانات الجديدة
        const newData = collectData();
        if (newData.length === 0) {
            alert('لا توجد بيانات للحفظ');
            return;
        }

        // الحصول على معرف أمر العمل
        const match = window.location.href.match(/work-orders\/(\d+)/);
        const workOrderId = match ? match[1] : null;

        if (!workOrderId) {
            alert('خطأ: لم يتم العثور على رقم أمر العمل');
            return;
        }

        // الحصول على رمز CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            alert('خطأ: لم يتم العثور على رمز الحماية');
            return;
        }

        // دمج البيانات الجديدة مع البيانات المحفوظة
        const existingData = window.savedDailyData || [];
        const mergedData = [...existingData, ...newData];

        // إرسال البيانات المدمجة
        fetch('/admin/work-orders/save-daily-civil-works', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                work_order_id: workOrderId,
                daily_data: JSON.stringify(mergedData),
                _token: csrfToken
            })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('تم حفظ البيانات بنجاح');
                window.savedDailyData = mergedData; // تحديث البيانات المحفوظة
                updateSummaryTable(mergedData);
            } else {
                alert(result.message || 'فشل في حفظ البيانات');
            }
        })
        .catch(error => {
            console.error('خطأ في الحفظ:', error);
            alert('حدث خطأ في حفظ البيانات');
        });

    } catch (error) {
        console.error('خطأ:', error);
        alert('حدث خطأ غير متوقع');
    }
}

/**
 * تحديث جدول الملخص
 */
function updateSummaryTable(data) {
    const tbody = document.getElementById('daily-excavation-tbody');
    if (!tbody) return;

    tbody.innerHTML = '';

    if (!data || data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="text-center">لا توجد بيانات</td></tr>';
        return;
    }

    let totalAmount = 0;

    data.forEach((item, index) => {
        const row = document.createElement('tr');
        row.className = 'daily-excavation-row';
        
        // تنسيق نوع الحفرية
        const excavationType = item.excavation_type || 'غير محدد';
        const excavationTypeFormatted = `<span class="excavation-type">${excavationType}</span>`;
        
        // تنسيق نوع الكابل
        let cableTypeDisplay = '';
        // تنسيق نوع الكابل بالشكل المطلوب
        if (item.cable_type.includes('منخفض') && !item.cable_type.includes('2') && !item.cable_type.includes('3') && !item.cable_type.includes('4')) {
            cableTypeDisplay = 'كابل منخفض 4×70';
        } else if (item.cable_type.includes('متوسط') && !item.cable_type.includes('2') && !item.cable_type.includes('3') && !item.cable_type.includes('4')) {
            cableTypeDisplay = 'كابل متوسط (20×80)';
        } else if (item.cable_type.includes('2 كابل منخفض')) {
            cableTypeDisplay = '2 كابل منخفض';
        } else if (item.cable_type.includes('3 كابل منخفض')) {
            cableTypeDisplay = '3 كابل منخفض';
        } else if (item.cable_type.includes('4 كابل منخفض')) {
            cableTypeDisplay = '4 كابل منخفض';
        } else if (item.cable_type.includes('2 كابل متوسط')) {
            cableTypeDisplay = '2 كابل متوسط';
        } else if (item.cable_type.includes('3 كابل متوسط')) {
            cableTypeDisplay = '3 كابل متوسط';
        } else if (item.cable_type.includes('4 كابل متوسط')) {
            cableTypeDisplay = '4 كابل متوسط';
        } else if (item.cable_type.includes('+4 كابل عالي')) {
            cableTypeDisplay = '+4 كابل عالي';
        } else {
            cableTypeDisplay = 'كابل عام';
        }
        
        // تنسيق الأبعاد والأسعار
        const length = parseFloat(item.length || 0).toFixed(2);
        const width = parseFloat(item.width || 0).toFixed(2);
        const depth = parseFloat(item.depth || 0).toFixed(2);
        const price = parseFloat(item.price || 0).toFixed(2);
        const total = parseFloat(item.total || 0).toFixed(2);
        
        // تنسيق التاريخ والوقت
        let timestamp = '-';
        if (item.updated_at || item.created_at) {
            const date = new Date(item.updated_at || item.created_at);
            timestamp = date.toLocaleString('ar-SA', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
        }
        
        totalAmount += parseFloat(total);
        
        row.innerHTML = `
            <td class="text-center border px-2">${index + 1}</td>
            <td class="text-center border px-2">${excavationTypeFormatted}</td>
            <td class="text-center border px-2"><span class="cable-type-badge">${cableTypeDisplay}</span></td>
            <td class="text-center border px-2 number-cell">${length} م</td>
            <td class="text-center border px-2 number-cell">${price} ريال</td>
            <td class="text-center border px-2 number-cell">${total} ريال</td>
            <td class="text-center border px-2">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        <i class="fas fa-clock ms-1"></i>${timestamp}
                    </span>
                    <button class="btn btn-sm btn-danger delete-row" type="button" data-row-id="${index}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </td>
        `;

        // إضافة الصف للجدول
        tbody.appendChild(row);
    });

    // تحديث إجمالي المبلغ الكلي
    const totalAmountElement = document.getElementById('total-amount');
    if (totalAmountElement) {
        totalAmountElement.textContent = totalAmount.toFixed(2);
    }

    // تحديث وقت آخر تحديث
    const timeElement = document.getElementById('last-update-time');
    if (timeElement) {
        timeElement.textContent = new Date().toLocaleString('en-US', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    }
}

// تهيئة النظام عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', () => {
    // إعداد زر الحفظ
    const saveButton = document.getElementById('save-daily-summary-btn');
    if (saveButton) {
        saveButton.addEventListener('click', saveData);
    }

    // إعداد مستمعي الأحداث للحقول
    setupCalculations();

    // تحميل البيانات المحفوظة
    loadSavedData();
}); 

// إضافة مستمع أحداث لأزرار الحذف
document.addEventListener('click', function(e) {
    if (e.target.closest('.delete-row')) {
        const button = e.target.closest('.delete-row');
        const rowId = button.dataset.rowId;
        const row = button.closest('tr');
        
        if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
            // حذف الصف من الجدول
            row.remove();
            
            // تحديث الترقيم
            updateRowNumbers();
            
            // تحديث الإحصائيات
            updateStatistics();
        }
    }
});

// تحديث أرقام الصفوف
function updateRowNumbers() {
    const tbody = document.getElementById('daily-excavation-tbody');
    if (!tbody) return;
    
    const rows = tbody.querySelectorAll('tr');
    rows.forEach((row, index) => {
        const numberCell = row.querySelector('.row-number');
        if (numberCell) {
            numberCell.textContent = index + 1;
        }
    });
} 