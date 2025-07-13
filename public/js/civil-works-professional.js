/**
 * نظام الأعمال المدنية المبسط - الإصدار 8.1
 * Civil Works System - Version 8.1
 */

// تعريف أنواع الحفريات
const EXCAVATION_TYPES = {
    UNSURFACED_SOIL: 'unsurfaced_soil',
    SURFACED_SOIL: 'surfaced_soil',
    SURFACED_ROCK: 'surfaced_rock',
    UNSURFACED_ROCK: 'unsurfaced_rock'
};

// تعريف أنواع الحفريات المفتوحة
const OPEN_EXCAVATION_TYPES = {
    UNSURFACED_SOIL: 'unsurfaced_soil_open',
    SURFACED_SOIL: 'surfaced_soil_open',
    SURFACED_ROCK: 'surfaced_rock_open',
    UNSURFACED_ROCK: 'unsurfaced_rock_open'
};

/**
 * تحديث الإحصائيات
 */
function updateStatistics() {
    try {
        let totalLength = 0;
        let totalAmount = 0;
        let itemsCount = 0;

        // حساب إجماليات الحفريات العادية
        Object.values(EXCAVATION_TYPES).forEach(type => {
            document.querySelectorAll(`[data-table="${type}"]`).forEach(input => {
                if (input.classList.contains('calc-length')) {
                    const length = parseFloat(input.value) || 0;
                    if (length > 0) {
                        totalLength += length;
                        itemsCount++;
                    }
                }
            });
        });

        // حساب إجماليات الحفر المفتوح
        Object.values(OPEN_EXCAVATION_TYPES).forEach(type => {
            const volumeInput = document.querySelector(`[id^="total_"][id$="${type}"]`);
            const totalInput = document.querySelector(`[id^="final_total_"][id$="${type}"]`);
            
            if (volumeInput && totalInput) {
                const volume = parseFloat(volumeInput.value) || 0;
                const total = parseFloat(totalInput.value) || 0;
                
                if (volume > 0) {
                    totalLength += volume;
                    totalAmount += total;
                    itemsCount++;
                }
            }
        });

        // تحديث عناصر الإحصائيات في الواجهة
        const statsElements = {
            totalLength: document.getElementById('total-length'),
            totalAmount: document.getElementById('total-amount'),
            itemsCount: document.getElementById('items-count')
        };

        if (statsElements.totalLength) {
            statsElements.totalLength.textContent = totalLength.toFixed(2);
        }
        if (statsElements.totalAmount) {
            statsElements.totalAmount.textContent = totalAmount.toFixed(2);
        }
        if (statsElements.itemsCount) {
            statsElements.itemsCount.textContent = itemsCount;
        }

    } catch (error) {
        console.error('خطأ في تحديث الإحصائيات:', error);
    }
}

/**
 * إعداد الحسابات
 */
function setupCalculations() {
    // إعداد حسابات الطول والسعر
    document.querySelectorAll('.calc-length, .calc-price').forEach(input => {
        input.addEventListener('input', () => {
            const row = input.closest('tr');
            if (row) calculateRowTotal(row);
        });
    });

    // إعداد حسابات الحجم
    document.querySelectorAll('.calc-volume-length, .calc-volume-width, .calc-volume-depth, .calc-volume-price').forEach(input => {
        input.addEventListener('input', () => {
            const row = input.closest('tr');
            if (row) calculateVolumeTotal(row);
        });
    });
}

/**
 * حساب إجمالي الصف للحفريات العادية
 */
function calculateRowTotal(row) {
    const lengthInput = row.querySelector('.calc-length');
    const priceInput = row.querySelector('.calc-price');
    const totalInput = row.querySelector('.total-calc');
    
    if (lengthInput && priceInput && totalInput) {
        const length = parseFloat(lengthInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const total = length * price;
        
        totalInput.value = total.toFixed(2);
        updateTotalSummary();
    }
}

/**
 * حساب إجمالي الصف للحفر المفتوح
 */
function calculateVolumeTotal(row) {
    const inputs = {
        length: row.querySelector('.calc-volume-length'),
        width: row.querySelector('.calc-volume-width'),
        depth: row.querySelector('.calc-volume-depth'),
        price: row.querySelector('.calc-volume-price')
    };
    
    const outputs = {
        volume: row.querySelector('[id^="total_"][id$="_open"]'),
        total: row.querySelector('[id^="final_total_"][id$="_open"]')
    };
    
    if (Object.values(inputs).every(Boolean) && Object.values(outputs).every(Boolean)) {
        const values = {
            length: parseFloat(inputs.length.value) || 0,
            width: parseFloat(inputs.width.value) || 0,
            depth: parseFloat(inputs.depth.value) || 0,
            price: parseFloat(inputs.price.value) || 0
        };
        
        const volume = values.length * values.width * values.depth;
        const total = volume * values.price;
        
        outputs.volume.value = volume.toFixed(2);
        outputs.total.value = total.toFixed(2);
        updateTotalSummary();
    }
}

/**
 * تحديث المجموع الكلي
 */
function updateTotalSummary() {
    let grandTotal = 0;
    
    // جمع إجماليات الحفريات العادية
    document.querySelectorAll('.total-calc').forEach(input => {
        grandTotal += parseFloat(input.value) || 0;
    });
    
    // جمع إجماليات الحفر المفتوح
    document.querySelectorAll('.volume-total-calc').forEach(input => {
        grandTotal += parseFloat(input.value) || 0;
    });
    
    // تحديث المجموع الكلي
    const grandTotalElement = document.getElementById('excavation_grand_total');
    if (grandTotalElement) {
        grandTotalElement.value = grandTotal.toFixed(2);
    }
}

/**
 * تحميل البيانات المحفوظة من قاعدة البيانات
 */
async function loadSavedDailyWork() {
    try {
        // الحصول على معرف أمر العمل من الرابط
        const workOrderId = window.location.href.match(/work-orders\/(\d+)/)?.[1];
        if (!workOrderId) {
            console.log('لم يتم العثور على رقم أمر العمل');
            return;
        }

        // الحصول على رمز CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            console.log('لم يتم العثور على رمز الحماية');
            return;
        }

        // جلب البيانات من الخادم
        const response = await fetch(`/admin/work-orders/${workOrderId}/civil-works/get-daily-data`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (!response.ok) {
            console.log(`خطأ في جلب البيانات: ${response.status}`);
            return;
        }

        const result = await response.json();
        
        if (result.success && result.data && result.data.length > 0) {
            // عرض البيانات في جدول الملخص
            displaySavedData(result.data);
            console.log(`تم تحميل ${result.data.length} عنصر من البيانات المحفوظة`);
        } else {
            console.log('لا توجد بيانات محفوظة');
        }

    } catch (error) {
        console.error('خطأ في تحميل البيانات:', error);
    }
}

/**
 * عرض البيانات المحفوظة في جدول الملخص
 */
function displaySavedData(savedData) {
    const tbody = document.getElementById('daily-excavation-tbody');
    if (!tbody) return;

    // مسح الجدول الحالي
    tbody.innerHTML = '';

    if (savedData.length === 0) {
        tbody.innerHTML = `
            <tr id="no-data-row">
                <td colspan="7">
                    <div class="empty-state-content">
                        <i class="fas fa-clipboard-list fa-3x"></i>
                        <h5>لا توجد بيانات حفريات</h5>
                        <p>سيتم إضافة البيانات تلقائياً عند إدخال القياسات</p>
                    </div>
                </td>
            </tr>`;
        return;
    }

    // إضافة البيانات للجدول
    savedData.forEach((item, index) => {
        const row = document.createElement('tr');
        row.className = 'daily-excavation-row';
        
        // تنسيق التاريخ بالميلادي
        let dateStr = item.work_date || '';
        let timeStr = item.work_time || '';
        
        // إذا كان التاريخ بتنسيق مختلف، نحوله إلى التنسيق الميلادي
        if (dateStr) {
            try {
                const date = new Date(dateStr);
                if (!isNaN(date)) {
                    dateStr = date.getFullYear() + '-' + 
                             String(date.getMonth() + 1).padStart(2, '0') + '-' + 
                             String(date.getDate()).padStart(2, '0');
                }
            } catch (e) {
                console.error('خطأ في تنسيق التاريخ:', e);
            }
        }
        
        row.innerHTML = `
            <td class="text-center">${index + 1}</td>
            <td class="text-center">
                <span class="badge bg-info">${item.excavation_type || 'غير محدد'}</span>
            </td>
            <td class="text-center">
                <span class="badge bg-secondary">${item.cable_name || 'غير محدد'}</span>
            </td>
            <td class="text-center">${parseFloat(item.length || 0).toFixed(2)} م</td>
            <td class="text-center">${parseFloat(item.price || 0).toFixed(2)} ريال</td>
            <td class="text-center">${parseFloat(item.total || 0).toFixed(2)} ريال</td>
            <td class="text-center">
                <small class="text-muted">${dateStr} ${timeStr}</small>
            </td>
        `;

        tbody.appendChild(row);
    });
}

/**
 * جمع البيانات التي تم العمل عليها اليوم فقط
 */
function collectTodayWorkData() {
    const todayWork = [];
    const date = new Date();
    const today = date.getFullYear() + '-' + 
                 String(date.getMonth() + 1).padStart(2, '0') + '-' + 
                 String(date.getDate()).padStart(2, '0');
    const currentTime = date.toLocaleTimeString('ar');

    // جمع بيانات الحفريات العادية
    const excavationTypes = {
        'unsurfaced_soil': 'حفرية ترابية غير مسفلتة',
        'surfaced_soil': 'حفرية ترابية مسفلتة', 
        'surfaced_rock': 'حفرية صخرية مسفلتة',
        'unsurfaced_rock': 'حفرية صخرية غير مسفلتة'
    };

    Object.entries(excavationTypes).forEach(([type, typeName]) => {
        document.querySelectorAll(`[data-table="${type}"]`).forEach((input, index) => {
            if (input.classList.contains('calc-length')) {
                const row = input.closest('tr');
                const lengthInput = input;
                const priceInput = row.querySelector('.calc-price');
                const totalInput = row.querySelector('.total-calc');
                const cableNameCell = row.querySelector('td:first-child');
                
                const length = parseFloat(lengthInput.value) || 0;
                const price = parseFloat(priceInput?.value) || 0;
                const total = parseFloat(totalInput?.value) || 0;
                
                // حفظ فقط البيانات التي تم إدخالها (الطول أو السعر أكبر من صفر)
                if (length > 0 || price > 0) {
                    const cableName = cableNameCell ? cableNameCell.textContent.trim().split(' ')[0] + ' ' + cableNameCell.textContent.trim().split(' ')[1] : `كابل ${index + 1}`;
                    
                    todayWork.push({
                        excavation_type: typeName,
                        cable_name: cableName,
                        length: length,
                        price: price,
                        total: total,
                        work_date: today,
                        work_time: currentTime
                    });
                }
            }
        });
    });

    return todayWork;
}

/**
 * حفظ بيانات العمل اليومي
 */
async function saveTodayWork() {
    try {
        const workData = collectTodayWorkData();
        
        if (workData.length === 0) {
            alert('لا توجد بيانات للحفظ. يرجى إدخال الطول أو السعر لأحد الكابلات.');
            return;
        }

        // الحصول على معرف أمر العمل من الرابط
        const workOrderId = window.location.href.match(/work-orders\/(\d+)/)?.[1];
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

        // إرسال البيانات للخادم
        const response = await fetch(`/admin/work-orders/${workOrderId}/civil-works/save-daily-data`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                daily_work: workData
            })
        });

        if (!response.ok) {
            throw new Error(`خطأ في الخادم: ${response.status}`);
        }

        const result = await response.json();
        
        if (result.success) {
            alert(`تم حفظ ${workData.length} عنصر بنجاح`);
            
            // تحديث جدول الملخص
            updateDailySummaryTable(workData);
        } else {
            throw new Error(result.message || 'فشل في حفظ البيانات');
        }

    } catch (error) {
        console.error('خطأ في حفظ البيانات:', error);
        alert('حدث خطأ أثناء حفظ البيانات: ' + error.message);
    }
}

/**
 * تحديث جدول الملخص اليومي
 */
function updateDailySummaryTable(workData) {
    const tbody = document.getElementById('daily-excavation-tbody');
    if (!tbody) return;

    // إضافة البيانات الجديدة للجدول (بدون مسح البيانات الموجودة)
    workData.forEach((item, index) => {
        const row = document.createElement('tr');
        row.className = 'daily-excavation-row';
        
        const currentRowCount = tbody.querySelectorAll('tr').length;
        const rowNumber = currentRowCount > 0 && tbody.querySelector('#no-data-row') ? 1 : currentRowCount + 1;
        
        row.innerHTML = `
            <td class="text-center">${rowNumber + index}</td>
            <td class="text-center">
                <span class="badge bg-info">${item.excavation_type}</span>
            </td>
            <td class="text-center">
                <span class="badge bg-secondary">${item.cable_name}</span>
            </td>
            <td class="text-center">${item.length.toFixed(2)} م</td>
            <td class="text-center">${item.price.toFixed(2)} ريال</td>
            <td class="text-center">${item.total.toFixed(2)} ريال</td>
            <td class="text-center">
                <small class="text-muted">${item.work_date} ${item.work_time}</small>
            </td>
        `;

        // إزالة صف "لا توجد بيانات" إذا كان موجوداً
        const noDataRow = tbody.querySelector('#no-data-row');
        if (noDataRow) {
            noDataRow.remove();
        }

        tbody.appendChild(row);
    });
}

/**
 * تحويل نوع الحفرية إلى نص عربي
 */
function getExcavationTypeDisplay(type) {
    const types = {
        'unsurfaced_soil': 'حفرية ترابية غير مسفلتة',
        'surfaced_soil': 'حفرية ترابية مسفلتة',
        'surfaced_rock': 'حفرية صخرية مسفلتة',
        'unsurfaced_rock': 'حفرية صخرية غير مسفلتة',
        'unsurfaced_soil_open': 'حفرية ترابية غير مسفلتة',
        'surfaced_soil_open': 'حفرية ترابية مسفلتة',
        'surfaced_rock_open': 'حفرية صخرية مسفلتة',
        'unsurfaced_rock_open': 'حفرية صخرية غير مسفلتة'
    };
    return types[type] || type;
}

/**
 * تحديد لون الشارة حسب نوع الحفرية
 */
function getExcavationTypeBadgeClass(excavationType) {
    if (excavationType.includes('ترابية')) {
        return excavationType.includes('مسفلتة') ? 'bg-warning text-dark' : 'bg-success text-white';
    } else if (excavationType.includes('صخرية')) {
        return excavationType.includes('مسفلتة') ? 'bg-danger text-white' : 'bg-primary text-white';
    }
    return 'bg-secondary text-white';
}

// تهيئة الصفحة
document.addEventListener('DOMContentLoaded', () => {
    setupCalculations();
    
    // تحميل البيانات المحفوظة عند تحميل الصفحة
    loadSavedDailyWork();
    
    // إضافة مستمع لزر الحفظ
    const saveButton = document.getElementById('save-daily-summary-btn');
    if (saveButton) {
        saveButton.addEventListener('click', function(e) {
            e.preventDefault();
            saveTodayWork();
        });
    }
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