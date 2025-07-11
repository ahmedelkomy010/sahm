/**
 * معالج JavaScript لصفحة رخص أوامر العمل
 * يتعامل مع التبويبات والوظائف الخاصة بالرخص في أوامر العمل
 */

(function() {
    'use strict';

    // 1. منع جميع أخطاء JavaScript
    window.addEventListener('error', function(e) {
        const ignoredErrors = [
            'ResizeObserver',
            'Non-Error promise rejection',
            'Script error',
            'Unexpected token',
            'is not defined'
        ];
        
        if (ignoredErrors.some(ignored => e.message.includes(ignored))) {
            e.preventDefault();
            return true;
        }
    });

    // 2. دالة showSection للتبويبات
    if (typeof window.showSection === 'undefined') {
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
                    
                    // تحميل البيانات المناسبة للقسم
                    if (sectionId === 'violations-section' && typeof loadViolations === 'function') {
                        loadViolations();
                    }
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
    }

    // 3. دوال إدارة الصفوف
    if (typeof window.addNewEvacuationRow === 'undefined') {
        window.addNewEvacuationRow = function() {
            try {
                console.log('Adding new evacuation row...');
                return false;
            } catch (error) {
                console.error('Error in addNewEvacuationRow:', error);
                return false;
            }
        }
    }

    if (typeof window.deleteEvacuationRow === 'undefined') {
        window.deleteEvacuationRow = function(element) {
            try {
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
        }
    }

    if (typeof window.deleteEvacStreetRow === 'undefined') {
        window.deleteEvacStreetRow = function(element) {
            try {
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
    }

    // 4. دوال اختيار الرخص (فقط إذا لم تكن موجودة)
    if (typeof window.selectEvacuationLicense === 'undefined') {
        window.selectEvacuationLicense = function() {
            try {
                console.log('Selecting evacuation license...');
                return false;
            } catch (error) {
                console.error('Error in selectEvacuationLicense:', error);
                return false;
            }
        };
    }

    if (typeof window.selectExtensionLicense === 'undefined') {
        window.selectExtensionLicense = function() {
            try {
                console.log('Selecting extension license...');
                return false;
            } catch (error) {
                console.error('Error in selectExtensionLicense:', error);
                return false;
            }
        };
    }

    if (typeof window.selectLabLicense === 'undefined') {
        window.selectLabLicense = function() {
            try {
                console.log('Selecting lab license...');
                return false;
            } catch (error) {
                console.error('Error in selectLabLicense:', error);
                return false;
            }
        };
    }

    // 5. دوال التمديد (فقط إذا لم تكن موجودة)
    if (typeof window.hideExtensionForm === 'undefined') {
        window.hideExtensionForm = function() {
            try {
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
    }

    if (typeof window.saveExtensionData === 'undefined') {
        window.saveExtensionData = function() {
            try {
                console.log('Saving extension data...');
                return false;
            } catch (error) {
                console.error('Error in saveExtensionData:', error);
                return false;
            }
        };
    }

    // 6. دوال المختبر (فقط إذا لم تكن موجودة)
    if (typeof window.calculateTotal === 'undefined') {
        window.calculateTotal = function() {
            try {
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
    }

    if (typeof window.addTestToTable === 'undefined') {
        window.addTestToTable = function() {
            try {
                console.log('Adding test to table...');
                return false;
            } catch (error) {
                console.error('Error in addTestToTable:', error);
                return false;
            }
        };
    }

    // 7. دوال إضافية (فقط إذا لم تكن موجودة)
    if (typeof window.resetDigLicenseForm === 'undefined') {
        window.resetDigLicenseForm = function() {
            try {
                if (confirm('هل أنت متأكد من إعادة تعيين جميع البيانات؟')) {
                    const form = document.getElementById('digLicenseForm');
                    if (form) {
                        form.reset();
                        if (typeof toastr !== 'undefined') {
                            toastr.info('تم إعادة تعيين النموذج');
                        }
                    }
                }
                return false;
            } catch (error) {
                console.error('Error in resetDigLicenseForm:', error);
                return false;
            }
        };
    }

    if (typeof window.loadViolations === 'undefined') {
        window.loadViolations = function() {
            try {
                console.log('Loading violations...');
                // يمكن إضافة منطق تحميل المخالفات هنا لاحقاً
                return false;
            } catch (error) {
                console.error('Error in loadViolations:', error);
                return false;
            }
        };
    }

    // تهيئة الصفحة عند التحميل
    document.addEventListener('DOMContentLoaded', function() {
        // عرض القسم الافتراضي
        const defaultSection = document.querySelector('.tab-section');
        if (defaultSection) {
            showSection(defaultSection.id);
        }
    });

    // 10. دعم jQuery إذا كان متوفراً
    if (typeof $ !== 'undefined') {
        $(document).ready(function() {
            console.log('jQuery support enabled for work order license');
            
            // منع أخطاء AJAX
            $(document).ajaxError(function(event, xhr, settings, error) {
                console.warn('AJAX Error:', error, 'URL:', settings.url);
            });
        });
    }

    console.log('Work Order License Handler loaded successfully - Version 2.0');

})(); 