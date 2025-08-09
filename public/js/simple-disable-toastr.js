// Simple toastr disabler for license pages
(function() {
    function disableToastr() {
        if (typeof window.toastr !== 'undefined') {
            window.toastr.success = function() { return false; };
            window.toastr.error = function() { return false; };
            window.toastr.warning = function() { return false; };
            window.toastr.info = function() { return false; };
            window.toastr.clear = function() { return false; };
            window.toastr.remove = function() { return false; };
        }
        
        // Remove existing toasts
        var toasts = document.querySelectorAll('#toast-container, .toast, .toastr');
        for (var i = 0; i < toasts.length; i++) {
            toasts[i].remove();
        }
    }
    
    // Check if we're on a license page
    if (window.location.pathname.indexOf('license') !== -1) {
        // Disable immediately
        disableToastr();
        
        // Disable after DOM is loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', disableToastr);
        }
        
        // Disable after a short delay
        setTimeout(disableToastr, 500);
        setTimeout(disableToastr, 1000);
        setTimeout(disableToastr, 2000);
    }
})();
