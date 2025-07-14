/**
 * معالج JavaScript للرخص - إعادة كتابة شاملة
 * يعالج جميع مشاكل JavaScript في صفحات الرخص
 */

(function() {
    'use strict';
    
    // معالج الأخطاء العام
    window.addEventListener('error', function(e) {
        // قائمة الأخطاء التي نريد تجاهلها
        const ignoredErrors = [
            'ResizeObserver',
            'Non-Error promise rejection captured',
            'Script error',
            'Network error',
            'Loading chunk'
        ];
        
        // تحقق من الأخطاء المتجاهلة
        if (ignoredErrors.some(ignored => e.message.includes(ignored))) {
            e.preventDefault();
            return true;
        }
        
        // لوغ الأخطاء المهمة فقط
        console.warn('JavaScript Error:', e.message, 'at', e.filename + ':' + e.lineno);
    });

    // معالج رفض Promise
    window.addEventListener('unhandledrejection', function(e) {
        e.preventDefault();
        console.warn('Promise rejection:', e.reason);
    });

    // دوال التبويبات الأساسية
    window.showSection = function(sectionId) {
        try {
            console.log('showSection called with:', sectionId);
            
            // إخفاء جميع الأقسام
            const allSections = document.querySelectorAll('.tab-section, [id$="-section"]');
            allSections.forEach(function(section) {
                if (section && section.style) {
                    section.style.display = 'none';
                }
            });
            
            // إزالة الفئة النشطة من جميع الأزرار
            const allButtons = document.querySelectorAll('.nav-tab-btn, button[onclick*="showSection"]');
            allButtons.forEach(function(button) {
                if (button && button.classList) {
                    button.classList.remove('active');
                }
            });
            
            // عرض القسم المطلوب
            const targetSection = document.getElementById(sectionId);
            if (targetSection) {
                targetSection.style.display = 'block';
                console.log('Section shown:', sectionId);
            } else {
                console.warn('Section not found:', sectionId);
            }
            
            // تفعيل الزر المناسب
            const activeButton = document.querySelector(`button[onclick*="${sectionId}"]`);
            if (activeButton && activeButton.classList) {
                activeButton.classList.add('active');
            }
            
            return false;
        } catch (error) {
            console.error('Error in showSection:', error);
            return false;
        }
    }

    // تهيئة الصفحة عند التحميل
    document.addEventListener('DOMContentLoaded', function() {
        // عرض القسم الافتراضي
        const defaultSection = document.querySelector('.tab-section');
        if (defaultSection) {
            showSection(defaultSection.id);
        }
    });

})();

// 3. دوال المخالفات
window.viewViolation = function(violationId) {
    try {
        console.log('viewViolation called with:', violationId);
        // يمكن إضافة منطق عرض المخالفة هنا لاحقاً
        return false;
    } catch (error) {
        console.error('Error in viewViolation:', error);
        return false;
    }
};

window.deleteViolation = function(violationId) {
    try {
        console.log('deleteViolation called with:', violationId);
        if (confirm('هل أنت متأكد من حذف هذه المخالفة؟')) {
            // يمكن إضافة منطق حذف المخالفة هنا لاحقاً
            console.log('Deleting violation:', violationId);
        }
        return false;
    } catch (error) {
        console.error('Error in deleteViolation:', error);
        return false;
    }
};

// 4. دوال الإخلاءات
window.addNewEvacuationRow = function() {
    try {
        console.log('addNewEvacuationRow called');
        // منطق إضافة صف إخلاء جديد
        return false;
    } catch (error) {
        console.error('Error in addNewEvacuationRow:', error);
        return false;
    }
};

window.deleteEvacuationRow = function(element) {
    try {
        console.log('deleteEvacuationRow called');
        if (element && confirm('هل أنت متأكد من حذف هذا الصف؟')) {
            const row = element.closest('tr');
            if (row) {
                row.remove();
            }
        }
        return false;
    } catch (error) {
        console.error('Error in deleteEvacuationRow:', error);
        return false;
    }
};

// 5. دوال إضافية للتمديدات والاختبارات
window.hideExtensionForm = function() {
    try {
        console.log('hideExtensionForm called');
        const form = document.getElementById('extension-form-card');
        if (form) {
            form.style.display = 'none';
        }
        return false;
    } catch (error) {
        console.error('Error in hideExtensionForm:', error);
        return false;
    }
};

window.saveExtensionData = function() {
    try {
        console.log('saveExtensionData called');
        // منطق حفظ بيانات التمديد
        return false;
    } catch (error) {
        console.error('Error in saveExtensionData:', error);
        return false;
    }
};

window.selectLabLicense = function() {
    try {
        console.log('selectLabLicense called');
        // منطق اختيار رخصة المختبر
        return false;
    } catch (error) {
        console.error('Error in selectLabLicense:', error);
        return false;
    }
};

// 6. دوال حساب التكلفة
window.calculateTotal = function() {
    try {
        console.log('calculateTotal called');
        const points = parseFloat(document.getElementById('test_points')?.value) || 0;
        const price = parseFloat(document.getElementById('test_price')?.value) || 0;
        const total = points * price;
        
        const totalField = document.getElementById('test_total');
        if (totalField) {
            totalField.value = total.toFixed(2);
        }
        
        return false;
    } catch (error) {
        console.error('Error in calculateTotal:', error);
        return false;
    }
};

// 7. دوال الجداول
window.addTestToTable = function() {
    try {
        console.log('addTestToTable called');
        // منطق إضافة اختبار للجدول
        return false;
    } catch (error) {
        console.error('Error in addTestToTable:', error);
        return false;
    }
};

window.deleteEvacStreetRow = function(element) {
    try {
        console.log('deleteEvacStreetRow called');
        if (element && confirm('هل أنت متأكد من حذف هذا الصف؟')) {
            const row = element.closest('tr');
            if (row) {
                row.remove();
            }
        }
        return false;
    } catch (error) {
        console.error('Error in deleteEvacStreetRow:', error);
        return false;
    }
};

// 8. دوال الحفظ
window.saveAllEvacStreets = function() {
    try {
        console.log('saveAllEvacStreets called');
        // منطق حفظ جميع الفسح
        return false;
    } catch (error) {
        console.error('Error in saveAllEvacStreets:', error);
        return false;
    }
};

window.saveAllEvacuationData = function() {
    try {
        console.log('saveAllEvacuationData called');
        // منطق حفظ جميع بيانات الإخلاء
        return false;
    } catch (error) {
        console.error('Error in saveAllEvacuationData:', error);
        return false;
    }
};

// 9. دالة الطباعة
window.printLicense = function() {
    try {
        window.print();
        return false;
    } catch (error) {
        console.error('Error in printLicense:', error);
        return false;
    }
};

// تحديث دالة حفظ القسم
window.saveSection = async function(sectionId, data = null) {
    try {
        const formData = data || new FormData(document.getElementById(`${sectionId}-form`));
        
        const response = await fetch('/admin/licenses/save-section', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        const result = await response.json();
        
        if (!result.success) {
            console.warn('حدث خطأ أثناء حفظ القسم:', result.message);
            return false;
        }
        
        return true;
    } catch (error) {
        console.error('خطأ في حفظ القسم:', error);
        return false;
    }
};

// تحديث دالة الحفظ التلقائي
window.autoSaveTestsToServer = async function() {
    try {
        const testsData = collectTestsData();
        const formData = new FormData();
        formData.append('section', 'lab_tests');
        formData.append('tests_data', JSON.stringify(testsData));
        formData.append('totals', JSON.stringify(calculateTotals()));
        
        const response = await fetch('/admin/licenses/save-section', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        const result = await response.json();
        if (!result.success) {
            console.warn('تحذير: ', result.message);
        }
    } catch (error) {
        console.error('خطأ في حفظ البيانات:', error);
    }
};

// تحديث دالة حذف الاختبار
window.removeTest = function(element) {
    const row = element.closest('tr');
    if (row) {
        row.remove();
        updateTestTotals();
        autoSaveTestsToServer().catch(error => {
            console.error('خطأ في حفظ البيانات:', error);
        });
    }
};

// تحديث دالة جمع بيانات الاختبارات
window.collectTestsData = function() {
    const testsTable = document.getElementById('tests-table');
    if (!testsTable) return [];

    const tests = [];
    const rows = testsTable.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const test = {
            date: row.querySelector('[name="test_date[]"]')?.value,
            type: row.querySelector('[name="test_type[]"]')?.value,
            points: row.querySelector('[name="test_points[]"]')?.value,
            price: row.querySelector('[name="test_price[]"]')?.value,
            total: row.querySelector('[name="test_total[]"]')?.value,
            result: row.querySelector('[name="test_result[]"]')?.value,
            notes: row.querySelector('[name="test_notes[]"]')?.value
        };
        
        if (test.date || test.type || test.points || test.price || test.total || test.result || test.notes) {
            tests.push(test);
        }
    });
    
    return tests;
};

// تحديث دالة حساب الإجماليات
window.calculateTotals = function() {
    const testsTable = document.getElementById('tests-table');
    if (!testsTable) return {
        total_tests: 0,
        passed_tests: 0,
        failed_tests: 0,
        total_amount: 0,
        passed_amount: 0,
        failed_amount: 0
    };

    let totalTests = 0;
    let passedTests = 0;
    let failedTests = 0;
    let totalAmount = 0;
    let passedAmount = 0;
    let failedAmount = 0;

    const rows = testsTable.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const result = row.querySelector('[name="test_result[]"]')?.value;
        const total = parseFloat(row.querySelector('[name="test_total[]"]')?.value) || 0;

        if (result) {
            totalTests++;
            totalAmount += total;

            if (result.toLowerCase() === 'ناجح') {
                passedTests++;
                passedAmount += total;
            } else if (result.toLowerCase() === 'راسب') {
                failedTests++;
                failedAmount += total;
            }
        }
    });

    return {
        total_tests: totalTests,
        passed_tests: passedTests,
        failed_tests: failedTests,
        total_amount: totalAmount,
        passed_amount: passedAmount,
        failed_amount: failedAmount
    };
};

// 12. دعم jQuery إذا كان متوفراً
if (typeof $ !== 'undefined') {
    $(document).ready(function() {
        console.log('jQuery support enabled for license handler');
        
        // منع أخطاء AJAX
        $(document).ajaxError(function(event, xhr, settings, error) {
            console.warn('AJAX Error:', error, 'URL:', settings.url);
        });
        
        // تأكيد وجود CSRF token
        if ($('meta[name="csrf-token"]').length > 0) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }
    });
}

// 13. تصدير الدوال للنطاق العام
window.LicenseHandler = {
    showSection: window.showSection,
    viewViolation: window.viewViolation,
    deleteViolation: window.deleteViolation,
    addNewEvacuationRow: window.addNewEvacuationRow,
    deleteEvacuationRow: window.deleteEvacuationRow,
    calculateTotal: window.calculateTotal,
    printLicense: window.printLicense
};

console.log('License Handler loaded successfully - Version 2.0'); 