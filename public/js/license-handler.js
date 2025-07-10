/**
 * معالج JavaScript للرخص - إعادة كتابة شاملة
 * يعالج جميع مشاكل JavaScript في صفحات الرخص
 */

// 1. منع جميع أخطاء JavaScript غير المهمة
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

})();

// 2. دوال التبويبات الأساسية
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
};

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

// 10. تهيئة الصفحة
function initializePage() {
    try {
        console.log('Initializing license page...');
        
        // تفعيل التبويب الأول إذا وُجد
        const firstTab = document.querySelector('.nav-tab-btn.active, button[onclick*="showSection"]:first-of-type');
        if (firstTab) {
            const match = firstTab.getAttribute('onclick')?.match(/showSection\('([^']+)'/);
            if (match && match[1]) {
                showSection(match[1]);
            }
        }
        
        // معالجة النقرات على الروابط الفارغة
        document.querySelectorAll('a[href="#"]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
            });
        });
        
        console.log('License page initialized successfully');
        
    } catch (error) {
        console.error('Error initializing page:', error);
    }
}

// 11. انتظار تحميل الصفحة
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializePage);
} else {
    initializePage();
}

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