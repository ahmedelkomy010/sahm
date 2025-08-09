/**
 * ملف تعطيل الاشعارات في صفحات الرخص
 * يمنع ظهور أي اشعارات toastr غير مرغوب فيها
 */

(function() {
    'use strict';
    
    // تعطيل الاشعارات عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        // التحقق من أننا في صفحة رخص
        if (window.location.pathname.includes('licenses') || 
            window.location.pathname.includes('license') ||
            document.querySelector('.license-header') ||
            document.querySelector('[data-license-id]')) {
            
            console.log('تم تعطيل اشعارات toastr في صفحة الرخص');
            
            // تعطيل toastr بالكامل
            if (typeof window.toastr !== 'undefined') {
                window.toastr.success = function() { return false; };
                window.toastr.error = function() { return false; };
                window.toastr.warning = function() { return false; };
                window.toastr.info = function() { return false; };
                window.toastr.clear = function() { return false; };
                window.toastr.remove = function() { return false; };
            }
            
            // منع أي محاولة لإعادة تعريف toastr
            Object.defineProperty(window, 'toastr', {
                writable: false,
                configurable: false,
                value: {
                    success: function() { return false; },
                    error: function() { return false; },
                    warning: function() { return false; },
                    info: function() { return false; },
                    clear: function() { return false; },
                    remove: function() { return false; },
                    options: {}
                }
            });
            
            // إزالة أي اشعارات موجودة
            const existingToasts = document.querySelectorAll('#toast-container, .toast, .toastr');
            existingToasts.forEach(function(toast) {
                toast.remove();
            });
            
            // منع إضافة اشعارات جديدة
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList') {
                        mutation.addedNodes.forEach(function(node) {
                            if (node.nodeType === 1) { // Element node
                                if (node.id === 'toast-container' || 
                                    node.classList.contains('toast') || 
                                    node.classList.contains('toastr')) {
                                    node.remove();
                                }
                            }
                        });
                    }
                });
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    });
    
    // تعطيل إضافي للاشعارات المتأخرة
    setTimeout(function() {
        if (window.location.pathname.includes('licenses') || window.location.pathname.includes('license')) {
            const toasts = document.querySelectorAll('#toast-container, .toast, .toastr');
            toasts.forEach(function(toast) {
                toast.remove();
            });
        }
    }, 1000);
    
})();
