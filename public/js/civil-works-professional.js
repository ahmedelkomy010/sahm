/**
 * نظام الأعمال المدنية المبسط - الإصدار 8.1
 * Civil Works System - Version 8.1
 */

// تعريف أنواع الحفريات
const EXCAVATION_TYPES = {
    UNSURFACED_SOIL: 'unsurfaced_soil',
    SURFACED_SOIL: 'surfaced_soil',
    SURFACED_ROCK: 'surfaced_rock',
    UNSURFACED_ROCK: 'unsurfaced_rock',
    PRECISE: 'precise',                   // حفريات دقيقة
    FIRST_LAYER: 'first_layer',           // أسفلت طبقة أولى
    SCRAPE_RESURFACE: 'scrape_resurface'  // كشط واعادة السفلتة
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

        // حساب إجماليات الحفريات الدقيقة
        document.querySelectorAll('.calc-precise-length').forEach(input => {
            const length = parseFloat(input.value) || 0;
            if (length > 0) {
                totalLength += length;
                itemsCount++;
            }
        });

        document.querySelectorAll('.precise-total-calc').forEach(input => {
            const total = parseFloat(input.value) || 0;
            if (total > 0) {
                totalAmount += total;
            }
        });

        // حساب إجماليات أسفلت طبقة أولى
        document.querySelectorAll('.calc-area-length').forEach(input => {
            const length = parseFloat(input.value) || 0;
            if (length > 0) {
                totalLength += length;
                itemsCount++;
            }
        });

        document.querySelectorAll('.area-total-calc').forEach(input => {
            const total = parseFloat(input.value) || 0;
            if (total > 0) {
                totalAmount += total;
            }
        });

        // حساب إجماليات عناصر التمديد الكهربائية
        document.querySelectorAll('.calc-electrical-length').forEach(input => {
            const length = parseFloat(input.value) || 0;
            if (length > 0) {
                totalLength += length;
                itemsCount++;
            }
        });

        document.querySelectorAll('.electrical-total-calc').forEach(input => {
            const total = parseFloat(input.value) || 0;
            if (total > 0) {
                totalAmount += total;
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
        
        console.log(`تحديث الإحصائيات: الطول الكلي=${totalLength.toFixed(2)}, المبلغ الكلي=${totalAmount.toFixed(2)}, عدد العناصر=${itemsCount}`);

    } catch (error) {
        console.error('خطأ في تحديث الإحصائيات:', error);
    }
}

/**
 * إعداد الحسابات
 */
function setupCalculations() {
    // إعداد حسابات الطول والسعر للحفريات العادية
    document.querySelectorAll('.calc-length, .calc-price').forEach(input => {
        input.addEventListener('input', () => {
            const row = input.closest('tr');
            if (row) calculateRowTotal(row);
        });
        
        input.addEventListener('change', () => {
            const row = input.closest('tr');
            if (row) calculateRowTotal(row);
        });
    });

    // إعداد حسابات الحجم للحفر المفتوح
    document.querySelectorAll('.calc-volume-length, .calc-volume-width, .calc-volume-depth, .calc-volume-price').forEach(input => {
        input.addEventListener('input', () => {
            const row = input.closest('tr');
            if (row) {
                // تحقق من وجود حقول الحفر المفتوح في نفس الصف
                const hasOpenExcavationFields = row.querySelector('[id^="total_"][id$="_open"]') && row.querySelector('[id^="final_total_"][id$="_open"]');
                if (hasOpenExcavationFields) {
                    console.log('تم العثور على حقول الحفر المفتوح في نفس الصف، استخدام calculateVolumeTotal');
                    calculateVolumeTotal(row);
                } else {
                    console.log('لم يتم العثور على حقول في نفس الصف، استخدام calculateOpenExcavation');
                    calculateOpenExcavation();
                }
            }
        });
        
        // إضافة مستمع للحدث change أيضاً
        input.addEventListener('change', () => {
            const row = input.closest('tr');
            if (row) {
                const hasOpenExcavationFields = row.querySelector('[id^="total_"][id$="_open"]') && row.querySelector('[id^="final_total_"][id$="_open"]');
                if (hasOpenExcavationFields) {
                    calculateVolumeTotal(row);
                } else {
                    calculateOpenExcavation();
                }
            }
        });
    });

    // إعداد حسابات الحفريات الدقيقة
    document.querySelectorAll('.calc-precise-length, .calc-precise-price').forEach(input => {
        input.addEventListener('input', () => {
            const row = input.closest('tr');
            if (row) calculatePreciseTotal(row);
        });
        
        input.addEventListener('change', () => {
            const row = input.closest('tr');
            if (row) calculatePreciseTotal(row);
        });
    });

    // إعداد حسابات أسفلت طبقة أولى
    document.querySelectorAll('.calc-area-length, .calc-area-price').forEach(input => {
        input.addEventListener('input', () => {
            const row = input.closest('tr');
            if (row) calculateAreaTotal(row);
        });
        
        input.addEventListener('change', () => {
            const row = input.closest('tr');
            if (row) calculateAreaTotal(row);
        });
    });

    // إعداد حسابات عناصر التمديد الكهربائية
    document.querySelectorAll('.calc-electrical-length, .calc-electrical-price').forEach(input => {
        input.addEventListener('input', () => {
            const row = input.closest('tr');
            if (row) calculateElectricalTotal(row);
        });
        
        input.addEventListener('change', () => {
            const row = input.closest('tr');
            if (row) calculateElectricalTotal(row);
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
        let total = length * price;
        
        // تطبيق معاملات خاصة للأنواع الجديدة
        const excavationType = row.querySelector('[data-table]')?.getAttribute('data-table');
        if (excavationType) {
            switch (excavationType) {
                case EXCAVATION_TYPES.PRECISE:
                    // حفريات دقيقة: سعر مضاعف × 1.5
                    total = length * (price * 1.5);
                    break;
                case EXCAVATION_TYPES.FIRST_LAYER:
                    // أسفلت طبقة أولى: السعر × الطول × العرض القياسي (0.5 متر)
                    total = length * price * 0.5;
                    break;
                case EXCAVATION_TYPES.SCRAPE_RESURFACE:
                    // كشط واعادة السفلتة: السعر × الطول × العرض القياسي (1 متر)
                    total = length * price * 1.0;
                    break;
            }
        }
        
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
    
    // البحث عن حقول الإخراج بطريقة أكثر دقة
    const volumeOutput = row.querySelector('[id^="total_"][id$="_open"]');
    const totalOutput = row.querySelector('[id^="final_total_"][id$="_open"]');
    
    if (Object.values(inputs).every(Boolean) && volumeOutput && totalOutput) {
        const values = {
            length: parseFloat(inputs.length.value) || 0,
            width: parseFloat(inputs.width.value) || 0,
            depth: parseFloat(inputs.depth.value) || 0,
            price: parseFloat(inputs.price.value) || 0
        };
        
        const volume = values.length * values.width * values.depth;
        const total = volume * values.price;
        
        volumeOutput.value = volume.toFixed(2);
        totalOutput.value = total.toFixed(2);
        
        // تحديث الإحصائيات
        updateTotalSummary();
        
        // طباعة للتأكد من أن الحساب يعمل (للتطوير فقط)
        console.log(`حساب الحفر المفتوح: الطول=${values.length}, العرض=${values.width}, العمق=${values.depth}, السعر=${values.price}, الحجم=${volume.toFixed(2)}, الإجمالي=${total.toFixed(2)}`);
    } else {
        console.log('لم يتم العثور على جميع حقول الحفر المفتوح المطلوبة');
    }
}

/**
 * حساب إجمالي الحفر المفتوح (دالة محسنة)
 */
function calculateOpenExcavation() {
    // أنواع الحفر المفتوح
    const openTypes = [
        'unsurfaced_soil_open',
        'surfaced_soil_open',
        'surfaced_rock_open',
        'unsurfaced_rock_open'
    ];

    openTypes.forEach(type => {
        const lengthInput = document.querySelector(`[name="excavation_${type}[length]"]`);
        const widthInput = document.querySelector(`[name="excavation_${type}[width]"]`);
        const depthInput = document.querySelector(`[name="excavation_${type}[depth]"]`);
        const priceInput = document.querySelector(`[name="excavation_${type}_price"]`);
        const volumeOutput = document.getElementById(`total_${type}`);
        const totalOutput = document.getElementById(`final_total_${type}`);

        if (lengthInput && widthInput && depthInput && priceInput && volumeOutput && totalOutput) {
            const length = parseFloat(lengthInput.value) || 0;
            const width = parseFloat(widthInput.value) || 0;
            const depth = parseFloat(depthInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;

            const volume = length * width * depth;
            const total = volume * price;

            volumeOutput.value = volume.toFixed(2);
            totalOutput.value = total.toFixed(2);
            
            // طباعة للتأكد من أن الحساب يعمل (للتطوير فقط)
            if (length > 0 || width > 0 || depth > 0 || price > 0) {
                console.log(`حساب ${type}: الطول=${length}, العرض=${width}, العمق=${depth}, السعر=${price}, الحجم=${volume.toFixed(2)}, الإجمالي=${total.toFixed(2)}`);
            }
        } else {
            console.log(`لم يتم العثور على جميع حقول ${type}`);
        }
    });

    updateTotalSummary();
}

/**
 * حساب إجمالي الحفريات الدقيقة
 */
function calculatePreciseTotal(row) {
    const lengthInput = row.querySelector('.calc-precise-length');
    const priceInput = row.querySelector('.calc-precise-price');
    const totalOutput = row.querySelector('.precise-total-calc');
    
    if (lengthInput && priceInput && totalOutput) {
        const length = parseFloat(lengthInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const dataType = lengthInput.getAttribute('data-type') || 'unknown';
        
        // الحفريات الدقيقة: الطول × السعر (كما هو مطلوب من المستخدم)
        const total = length * price;
        
        totalOutput.value = total.toFixed(2);
        
        console.log(`حساب الحفريات الدقيقة (${dataType}): الطول=${length}, السعر=${price}, الإجمالي=${total.toFixed(2)}`);
        
        updateTotalSummary();
    } else {
        console.log('لم يتم العثور على جميع حقول الحفريات الدقيقة المطلوبة');
    }
}

/**
 * حساب إجمالي أسفلت طبقة أولى
 */
function calculateAreaTotal(row) {
    const lengthInput = row.querySelector('.calc-area-length');
    const priceInput = row.querySelector('.calc-area-price');
    const totalOutput = row.querySelector('.area-total-calc');
    
    if (lengthInput && priceInput && totalOutput) {
        const length = parseFloat(lengthInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        
        // أسفلت طبقة أولى: السعر × الطول × العرض القياسي (0.5 متر)
        const standardWidth = 0.5;
        const total = length * standardWidth * price;
        
        totalOutput.value = total.toFixed(2);
        
        console.log(`حساب أسفلت طبقة أولى: الطول=${length}, العرض=${standardWidth}, السعر=${price}, الإجمالي=${total.toFixed(2)}`);
        
        updateTotalSummary();
    } else {
        console.log('لم يتم العثور على جميع حقول أسفلت طبقة أولى المطلوبة');
    }
}

/**
 * حساب إجمالي عناصر التمديد الكهربائية
 */
function calculateElectricalTotal(row) {
    const lengthInput = row.querySelector('.calc-electrical-length');
    const priceInput = row.querySelector('.calc-electrical-price');
    const totalOutput = row.querySelector('.electrical-total-calc');
    
    if (lengthInput && priceInput && totalOutput) {
        const length = parseFloat(lengthInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const dataType = lengthInput.getAttribute('data-type') || 'unknown';
        
        // عناصر التمديد: الطول × السعر
        const total = length * price;
        
        totalOutput.value = total.toFixed(2);
        
        console.log(`حساب عناصر التمديد (${dataType}): الطول=${length}, السعر=${price}, الإجمالي=${total.toFixed(2)}`);
        
        updateTotalSummary();
    } else {
        console.log('لم يتم العثور على جميع حقول التمديد الكهربائية المطلوبة');
    }
}

/**
 * تحديث المجموع الكلي
 */
function updateTotalSummary() {
    let grandTotal = 0;
    
    // جمع إجماليات الحفريات العادية
    document.querySelectorAll('.total-calc').forEach(input => {
        const row = input.closest('tr');
        const excavationType = row.querySelector('[data-table]')?.getAttribute('data-table');
        let total = parseFloat(input.value) || 0;
        
        // إضافة معاملات إضافية للأنواع الجديدة في المجموع الكلي
        if (excavationType) {
            switch (excavationType) {
                case EXCAVATION_TYPES.PRECISE:
                case EXCAVATION_TYPES.FIRST_LAYER:
                case EXCAVATION_TYPES.SCRAPE_RESURFACE:
                    // تم تطبيق المعاملات في حساب الصف
                    break;
                default:
                    // الحفريات العادية
                    break;
            }
        }
        
        grandTotal += total;
    });
    
    // جمع إجماليات الحفر المفتوح
    document.querySelectorAll('[id^="final_total_"][id$="_open"]').forEach(input => {
        grandTotal += parseFloat(input.value) || 0;
    });
    
    // جمع إجماليات الحفريات الدقيقة
    document.querySelectorAll('.precise-total-calc').forEach(input => {
        grandTotal += parseFloat(input.value) || 0;
    });
    
    // جمع إجماليات أسفلت طبقة أولى
    document.querySelectorAll('.area-total-calc').forEach(input => {
        grandTotal += parseFloat(input.value) || 0;
    });
    
    // جمع إجماليات عناصر التمديد الكهربائية
    document.querySelectorAll('.electrical-total-calc').forEach(input => {
        grandTotal += parseFloat(input.value) || 0;
    });
    
    // تحديث المجموع الكلي
    const grandTotalElement = document.getElementById('excavation_grand_total');
    if (grandTotalElement) {
        grandTotalElement.value = grandTotal.toFixed(2);
        console.log(`تحديث المجموع الكلي: ${grandTotal.toFixed(2)}`);
    }
    
    // تحديث الإحصائيات
    updateStatistics();
}

/**
 * إعداد مستمعي الأحداث للحفر المفتوح
 */
function setupOpenExcavationListeners() {
    // إضافة مستمعي الأحداث لجميع حقول الحفر المفتوح
    // سنضيف مستمعين إضافيين للتأكد من أن الحسابات تعمل بشكل صحيح
    const openExcavationInputs = document.querySelectorAll(`
        [name^="excavation_"][name$="[length]"],
        [name^="excavation_"][name$="[width]"],
        [name^="excavation_"][name$="[depth]"],
        [name$="_open_price"]
    `);

    openExcavationInputs.forEach(input => {
        // إضافة مستمع للحدث input
        input.addEventListener('input', function() {
            calculateOpenExcavation();
        });
        
                 // إضافة مستمع للحدث change للتأكد من التحديث
         input.addEventListener('change', function() {
             calculateOpenExcavation();
         });
     });

     // إضافة مستمعي أحداث للحفريات الدقيقة
     const preciseInputs = document.querySelectorAll(`
         [name="excavation_precise[medium]"],
         [name="excavation_precise[medium_price]"],
         [name="excavation_precise[low]"],
         [name="excavation_precise[low_price]"]
     `);

     if (preciseInputs.length === 0) {
         console.log('لم يتم العثور على حقول الحفريات الدقيقة في الصفحة');
     } else {
         console.log(`تم العثور على ${preciseInputs.length} حقل للحفريات الدقيقة`);
     }

     preciseInputs.forEach(input => {
         input.addEventListener('input', function() {
             const row = input.closest('tr');
             if (row) calculatePreciseTotal(row);
         });
         
         input.addEventListener('change', function() {
             const row = input.closest('tr');
             if (row) calculatePreciseTotal(row);
         });
     });

     // إضافة مستمعي أحداث لأسفلت طبقة أولى
     const areaInputs = document.querySelectorAll(`
         [name="open_excavation[first_asphalt][length]"],
         [name="open_excavation[first_asphalt][price]"]
     `);

     if (areaInputs.length === 0) {
         console.log('لم يتم العثور على حقول أسفلت طبقة أولى في الصفحة');
     } else {
         console.log(`تم العثور على ${areaInputs.length} حقل لأسفلت طبقة أولى`);
     }

     areaInputs.forEach(input => {
         input.addEventListener('input', function() {
             const row = input.closest('tr');
             if (row) calculateAreaTotal(row);
         });
         
         input.addEventListener('change', function() {
             const row = input.closest('tr');
             if (row) calculateAreaTotal(row);
         });
     });

     // إضافة مستمعي أحداث لعناصر التمديد الكهربائية
     const electricalInputs = document.querySelectorAll(`
         [name*="electrical_items"][name*="[meters]"],
         [name*="electrical_items"][name*="[price]"]
     `);

     if (electricalInputs.length === 0) {
         console.log('لم يتم العثور على حقول التمديد الكهربائية في الصفحة');
     } else {
         console.log(`تم العثور على ${electricalInputs.length} حقل للتمديد الكهربائية`);
     }

     electricalInputs.forEach(input => {
         input.addEventListener('input', function() {
             const row = input.closest('tr');
             if (row) calculateElectricalTotal(row);
         });
         
         input.addEventListener('change', function() {
             const row = input.closest('tr');
             if (row) calculateElectricalTotal(row);
         });
     });
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
            console.log('البيانات المحملة:', result.data);
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
        console.log('لا توجد بيانات محفوظة للعرض');
        return;
    }

    console.log(`عرض ${savedData.length} عنصر من البيانات المحفوظة`);

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

        // تحديد محتوى الخلايا بناءً على نوع الحفر
        let lengthDisplay, badgeClass;
        if (item.is_open_excavation) {
            lengthDisplay = `
                <div>الطول: ${parseFloat(item.length || 0).toFixed(2)} م</div>
                <div>العرض: ${parseFloat(item.width || 0).toFixed(2)} م</div>
                <div>العمق: ${parseFloat(item.depth || 0).toFixed(2)} م</div>
                <div class="mt-1 fw-bold">الحجم: ${parseFloat(item.volume || 0).toFixed(2)} م³</div>
            `;
            badgeClass = 'bg-warning text-dark';
        } else if (item.is_precise_excavation) {
            lengthDisplay = `${parseFloat(item.length || 0).toFixed(2)} م`;
            badgeClass = 'bg-purple text-white';
        } else if (item.is_area_excavation) {
            lengthDisplay = `${parseFloat(item.length || 0).toFixed(2)} م`;
            badgeClass = 'bg-success text-white';
        } else if (item.is_electrical_item) {
            lengthDisplay = `${parseFloat(item.length || 0).toFixed(2)} م`;
            badgeClass = 'bg-primary text-white';
        } else {
            lengthDisplay = `${parseFloat(item.length || 0).toFixed(2)} م`;
            badgeClass = 'bg-info';
        }
        
        row.innerHTML = `
            <td class="text-center">${index + 1}</td>
            <td class="text-center">
                <span class="badge ${badgeClass}">${item.excavation_type || 'غير محدد'}</span>
            </td>
            <td class="text-center">
                <span class="badge bg-secondary">${item.cable_name || 'غير محدد'}</span>
            </td>
            <td class="text-center">${lengthDisplay}</td>
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
        'unsurfaced_rock': 'حفرية صخرية غير مسفلتة',
        'precise': 'حفريات دقيقة',
        'first_layer': 'أسفلت طبقة أولى',
        'scrape_resurface': 'كشط واعادة السفلتة'
    };

    // جمع بيانات الحفريات العادية
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

    // جمع بيانات الحفر المفتوح
    const openExcavationTypes = {
        'unsurfaced_soil_open': 'حفر مفتوح - ترابية غير مسفلتة',
        'surfaced_soil_open': 'حفر مفتوح - ترابية مسفلتة',
        'surfaced_rock_open': 'حفر مفتوح - صخرية مسفلتة',
        'unsurfaced_rock_open': 'حفر مفتوح - صخرية غير مسفلتة'
    };

    Object.entries(openExcavationTypes).forEach(([type, typeName]) => {
        const lengthInput = document.querySelector(`[name="excavation_${type}[length]"]`);
        const widthInput = document.querySelector(`[name="excavation_${type}[width]"]`);
        const depthInput = document.querySelector(`[name="excavation_${type}[depth]"]`);
        const priceInput = document.querySelector(`[name="excavation_${type}_price"]`);
        const volumeOutput = document.getElementById(`total_${type}`);
        const totalOutput = document.getElementById(`final_total_${type}`);

        if (lengthInput && widthInput && depthInput && priceInput && volumeOutput && totalOutput) {
            const length = parseFloat(lengthInput.value) || 0;
            const width = parseFloat(widthInput.value) || 0;
            const depth = parseFloat(depthInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const volume = parseFloat(volumeOutput.value) || 0;
            const total = parseFloat(totalOutput.value) || 0;

            // إضافة شرط أكثر مرونة للحفظ
            if ((length > 0 && width > 0 && depth > 0) || (volume > 0 && price > 0) || total > 0) {
                console.log(`إضافة بيانات الحفر المفتوح: ${typeName} - الطول=${length}, العرض=${width}, العمق=${depth}, الحجم=${volume}, السعر=${price}, الإجمالي=${total}`);
                todayWork.push({
                    excavation_type: typeName,
                    cable_name: 'حفر مفتوح',
                    length: length,
                    width: width,
                    depth: depth,
                    volume: volume,
                    price: price,
                    total: total,
                    work_date: today,
                    work_time: currentTime,
                    is_open_excavation: true
                });
            } else {
                console.log(`تخطي ${typeName} - لا توجد بيانات صالحة للحفظ`);
            }
        }
    });

    // جمع بيانات الحفريات الدقيقة
    const preciseTypes = {
        'medium': 'حفريات دقيقة - متوسط (20×80)',
        'low': 'حفريات دقيقة - منخفض (20×56)'
    };

    Object.entries(preciseTypes).forEach(([type, typeName]) => {
        const lengthInput = document.querySelector(`[name="excavation_precise[${type}]"]`);
        const priceInput = document.querySelector(`[name="excavation_precise[${type}_price]"]`);
        const totalOutput = document.querySelector(`#final_total_precise_${type}`);

        if (lengthInput && priceInput && totalOutput) {
            const length = parseFloat(lengthInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const total = parseFloat(totalOutput.value) || 0;

            if (length > 0 || price > 0 || total > 0) {
                console.log(`إضافة بيانات الحفريات الدقيقة: ${typeName} - الطول=${length}, السعر=${price}, الإجمالي=${total}`);
                todayWork.push({
                    excavation_type: typeName,
                    cable_name: 'حفريات دقيقة',
                    length: length,
                    price: price,
                    total: total,
                    work_date: today,
                    work_time: currentTime,
                    is_precise_excavation: true
                });
            } else {
                console.log(`تخطي ${typeName} - لا توجد بيانات صالحة للحفظ`);
            }
        }
    });

    // جمع بيانات أسفلت طبقة أولى
    const lengthInput = document.querySelector('[name="open_excavation[first_asphalt][length]"]');
    const priceInput = document.querySelector('[name="open_excavation[first_asphalt][price]"]');
    const totalOutput = document.querySelector('#final_total_first_asphalt');

    if (lengthInput && priceInput && totalOutput) {
        const length = parseFloat(lengthInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const total = parseFloat(totalOutput.value) || 0;

        if (length > 0 || price > 0 || total > 0) {
            console.log(`إضافة بيانات أسفلت طبقة أولى: الطول=${length}, السعر=${price}, الإجمالي=${total}`);
            todayWork.push({
                excavation_type: 'أسفلت طبقة أولى',
                cable_name: 'أسفلت طبقة أولى',
                length: length,
                price: price,
                total: total,
                work_date: today,
                work_time: currentTime,
                is_area_excavation: true
            });
        } else {
            console.log('تخطي أسفلت طبقة أولى - لا توجد بيانات صالحة للحفظ');
        }
    }

    // جمع بيانات عناصر التمديد الكهربائية
    const electricalTypes = {
        'cable_4x70_low': 'تمديد كيبل 4x70 منخفض',
        'cable_4x185_low': 'تمديد كيبل 4x185 منخفض',
        'cable_4x300_low': 'تمديد كيبل 4x300 منخفض',
        'cable_3x500_med': 'تمديد كيبل 3x500 متوسط',
        'cable_3x400_med': 'تمديد كيبل 3x400 متوسط'
    };

    Object.entries(electricalTypes).forEach(([type, typeName]) => {
        const lengthInput = document.querySelector(`[name="electrical_items[${type}][meters]"]`);
        const priceInput = document.querySelector(`[name="electrical_items[${type}][price]"]`);
        const totalOutput = document.querySelector(`#final_total_${type}`);

        if (lengthInput && priceInput && totalOutput) {
            const length = parseFloat(lengthInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const total = parseFloat(totalOutput.value) || 0;

            if (length > 0 || price > 0 || total > 0) {
                console.log(`إضافة بيانات التمديد: ${typeName} - الطول=${length}, السعر=${price}, الإجمالي=${total}`);
                todayWork.push({
                    excavation_type: typeName,
                    cable_name: 'تمديد كهربائي',
                    length: length,
                    price: price,
                    total: total,
                    work_date: today,
                    work_time: currentTime,
                    is_electrical_item: true
                });
            } else {
                console.log(`تخطي ${typeName} - لا توجد بيانات صالحة للحفظ`);
            }
        }
    });

    return todayWork;
}

/**
 * حفظ بيانات العمل اليومي
 */
async function saveTodayWork() {
    try {
        const workData = collectTodayWorkData();
        
        console.log(`محاولة حفظ ${workData.length} عنصر من البيانات`);
        console.log('البيانات المراد حفظها:', workData);
        
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
        
        // تحديد محتوى الخلايا بناءً على نوع الحفر
        let lengthDisplay, badgeClass;
        if (item.is_open_excavation) {
            lengthDisplay = `
                <div>الطول: ${parseFloat(item.length || 0).toFixed(2)} م</div>
                <div>العرض: ${parseFloat(item.width || 0).toFixed(2)} م</div>
                <div>العمق: ${parseFloat(item.depth || 0).toFixed(2)} م</div>
                <div class="mt-1 fw-bold">الحجم: ${parseFloat(item.volume || 0).toFixed(2)} م³</div>
            `;
            badgeClass = 'bg-warning text-dark';
        } else if (item.is_precise_excavation) {
            lengthDisplay = `${parseFloat(item.length || 0).toFixed(2)} م`;
            badgeClass = 'bg-purple text-white';
        } else if (item.is_area_excavation) {
            lengthDisplay = `${parseFloat(item.length || 0).toFixed(2)} م`;
            badgeClass = 'bg-success text-white';
        } else if (item.is_electrical_item) {
            lengthDisplay = `${parseFloat(item.length || 0).toFixed(2)} م`;
            badgeClass = 'bg-primary text-white';
        } else {
            lengthDisplay = `${parseFloat(item.length || 0).toFixed(2)} م`;
            badgeClass = 'bg-info';
        }

        row.innerHTML = `
            <td class="text-center">${rowNumber + index}</td>
            <td class="text-center">
                <span class="badge ${badgeClass}">${item.excavation_type}</span>
            </td>
            <td class="text-center">
                <span class="badge bg-secondary">${item.cable_name}</span>
            </td>
            <td class="text-center">${lengthDisplay}</td>
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
        'precise': 'حفريات دقيقة',
        'first_layer': 'أسفلت طبقة أولى',
        'scrape_resurface': 'كشط واعادة السفلتة',
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
    if (excavationType.includes('دقيقة')) {
        return 'bg-purple text-white';
    } else if (excavationType.includes('طبقة أولى')) {
        return 'bg-success text-white';
    } else if (excavationType.includes('تمديد')) {
        return 'bg-primary text-white';
    } else if (excavationType.includes('كشط')) {
        return 'bg-orange text-white';
    } else if (excavationType.includes('حفر مفتوح')) {
        return 'bg-warning text-dark';
    } else if (excavationType.includes('ترابية')) {
        return excavationType.includes('مسفلتة') ? 'bg-warning text-dark' : 'bg-success text-white';
    } else if (excavationType.includes('صخرية')) {
        return excavationType.includes('مسفلتة') ? 'bg-danger text-white' : 'bg-primary text-white';
    }
    return 'bg-secondary text-white';
}

// تهيئة الصفحة
document.addEventListener('DOMContentLoaded', () => {
    setupCalculations();
    setupOpenExcavationListeners();
    
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
    
    // تحديث الحسابات عند تحميل الصفحة لأول مرة
    setTimeout(() => {
        console.log('تحديث جميع الحسابات عند تحميل الصفحة...');
        
        // تحديث حسابات الحفر المفتوح
        calculateOpenExcavation();
        
        // تحديث حسابات الحفريات الدقيقة
        document.querySelectorAll('.calc-precise-length').forEach(input => {
            const row = input.closest('tr');
            if (row) calculatePreciseTotal(row);
        });
        
        // تحديث حسابات أسفلت طبقة أولى
        document.querySelectorAll('.calc-area-length').forEach(input => {
            const row = input.closest('tr');
            if (row) calculateAreaTotal(row);
        });
        
        // تحديث حسابات الحفريات العادية
        document.querySelectorAll('.calc-length').forEach(input => {
            const row = input.closest('tr');
            if (row) calculateRowTotal(row);
        });
        
        // تحديث حسابات التمديد الكهربائية
        document.querySelectorAll('.calc-electrical-length').forEach(input => {
            const row = input.closest('tr');
            if (row) calculateElectricalTotal(row);
        });
        
        updateTotalSummary();
        
        // طباعة إحصائيات التحديث
        const counts = {
            openExcavation: document.querySelectorAll('[name^="excavation_"][name$="[length]"]').length,
            preciseExcavation: document.querySelectorAll('.calc-precise-length').length,
            areaExcavation: document.querySelectorAll('.calc-area-length').length,
            normalExcavation: document.querySelectorAll('.calc-length').length,
            electricalItems: document.querySelectorAll('.calc-electrical-length').length
        };
        
        console.log('إحصائيات الحقول:', counts);
        console.log('تم تحديث جميع الحسابات بنجاح');
    }, 100);
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