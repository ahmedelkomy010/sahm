{{-- استخدام المتغيرات من الكلاس --}}

<header class="main-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center px-3 py-2">
            <!-- Logo and App Name -->
            <div class="d-flex align-items-center">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                    <div class="logo-container bg-white rounded-circle p-1 me-2 shadow-sm">
                        <img class="logo-img" src="{{ asset('images/sahm.png') }}" alt="Sahm Logo">
                    </div>
                    <span class="fs-5 fw-bold text-white d-none d-md-inline text-shadow">شركة سهم بلدي للمقاولات</span>
                </a>
            </div>
            
            <!-- Main Navigation -->
            <div class="d-none d-lg-flex flex-grow-1 justify-content-center">
                <nav class="navbar-nav d-flex flex-row">
                    <a href="{{ route('dashboard') }}" 
                       class="nav-item nav-link px-3 py-2 rounded {{ $isHomePage ? 'active' : '' }}">
                        <i class="fas fa-home me-1"></i>
                        الرئيسية
                    </a>

                    @auth
                        <!-- إيرادات المشاريع -->
                        @if(auth()->user()->is_admin || (is_array(auth()->user()->permissions) && in_array('access_total_project_revenues', auth()->user()->permissions)))
                        <a href="{{ route('admin.all-projects-revenues') }}" 
                           class="nav-item nav-link px-3 py-2 rounded {{ request()->routeIs('admin.all-projects-revenues') ? 'active' : '' }}">
                            <i class="fas fa-coins me-1"></i>
                            إيرادات المشاريع
                        </a>
                        @endif

                        @if(auth()->user()->is_admin)
                        <!-- التقارير -->
                        <a href="{{ route('admin.reports.unified') }}" 
                           class="nav-item nav-link px-3 py-2 rounded {{ request()->routeIs('admin.reports.unified') ? 'active' : '' }}">
                            <i class="fas fa-file-alt me-1"></i>
                            التقارير
                        </a>

                        <!-- إدارة أعضاء فريق العمل -->
                        <a href="{{ route('admin.users.index') }}" 
                           class="nav-item nav-link px-3 py-2 rounded {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-users-cog me-1"></i>
                            إدارة أعضاء فريق العمل
                        </a>
                        @endif

                        <!-- الإشعارات -->
                        <div class="dropdown" style="display: inline-block;">
                            <a class="nav-item nav-link px-3 py-2 rounded position-relative" 
                               href="#"
                               id="notificationsNavButton"
                               role="button"
                               aria-expanded="false"
                               style="cursor: pointer;">
                                <i class="fas fa-bell me-1"></i>
                                الإشعارات
                                <span id="notificationBadgeNav" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none; font-size: 0.6rem;">
                                    0
                                </span>
                            </a>
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationsNavButton" style="min-width: 350px; max-height: 500px; overflow-y: auto;">
                            <li>
                                <div class="d-flex justify-content-between align-items-center px-3 py-2 bg-light border-bottom">
                                    <h6 class="mb-0 fw-bold">
                                        <i class="fas fa-bell me-2 text-primary"></i>
                                        الإشعارات
                                    </h6>
                                    <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()" title="تعليم الكل كمقروء">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                </div>
                            </li>
                            <li id="notificationsLoaderNav" class="text-center py-4">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">جاري التحميل...</span>
                                </div>
                                <p class="text-muted small mt-2 mb-0">جاري تحميل الإشعارات...</p>
                            </li>
                            <li id="notificationsEmptyNav" style="display: none;" class="text-center py-4 text-muted">
                                <i class="fas fa-bell-slash fa-2x mb-2 text-muted"></i>
                                <p class="mb-0">لا توجد إشعارات</p>
                            </li>
                            <div id="notificationsListNav"></div>
                        </ul>
                        </div>
                    @endauth
                </nav>
            </div>
            
            <!-- User Menu -->
            @auth
            <div class="d-flex align-items-center">
                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-link text-white d-flex align-items-center text-decoration-none dropdown-toggle" 
                            type="button" 
                            id="userDropdown"
                            aria-expanded="false"
                            style="cursor: pointer;">
                        <i class="fas fa-user-circle fs-5 me-1"></i>
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <div class="dropdown-header">
                                <div class="fw-bold">{{ Auth::user()->name }}</div>
                                <div class="text-muted small">{{ Auth::user()->email }}</div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-edit me-2"></i>
                                حسابي الشخصي
                            </a>
                        </li>
                        
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    تسجيل الخروج
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            @endauth
            
            <!-- Mobile menu button -->
            <div class="d-lg-none d-flex align-items-center gap-2">
                <!-- Mobile Notifications Button -->
                <div class="dropdown">
                    <a class="btn btn-link text-white position-relative p-2" 
                       href="#"
                       id="mobileNotificationsButton"
                       role="button"
                       aria-expanded="false"
                       style="cursor: pointer;">
                        <i class="fas fa-bell fs-5"></i>
                        <span id="mobileNotificationBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none; font-size: 0.6rem;">
                            0
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="mobileNotificationsButton" style="min-width: 320px; max-width: 95vw; max-height: 70vh; overflow-y: auto;">
                        <li>
                            <div class="d-flex justify-content-between align-items-center px-3 py-2 bg-light border-bottom">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-bell me-2 text-primary"></i>
                                    الإشعارات
                                </h6>
                                <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()" title="تعليم الكل كمقروء">
                                    <i class="fas fa-check-double"></i>
                                </button>
                            </div>
                        </li>
                        <li id="mobileNotificationsList">
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-bell-slash fa-2x mb-2"></i>
                                <p class="mb-0">لا توجد إشعارات جديدة</p>
                            </div>
                        </li>
                    </ul>
                </div>
                
                <!-- Hamburger Menu -->
                <button class="btn btn-link text-white p-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                    <i class="fas fa-bars fs-4"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Project Context Info -->
    @if(session('project'))
    <div class="bg-primary-light border-top border-light border-opacity-25">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between px-3 py-2 text-white-50">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle me-2"></i>
                    <span class="small">المشروع الحالي: <strong class="text-white">{{ session('project') == 'riyadh' ? 'الرياض' : 'المدينة المنورة' }}</strong></span>
                </div>
                <a href="{{ route('project.selection') }}" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-exchange-alt me-1"></i>
                    تغيير المشروع
                </a>
            </div>
        </div>
    </div>
    @endif
</header>

<!-- Mobile Offcanvas Menu -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu">
    <div class="offcanvas-header bg-primary text-white">
        <h5 class="offcanvas-title">القائمة الرئيسية</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <nav class="nav flex-column">
            <a href="{{ route('dashboard') }}" 
               class="nav-link {{ $isHomePage ? 'active fw-bold text-primary' : 'text-dark' }}">
                <i class="fas fa-home me-2"></i>
                الرئيسية
            </a>

            @auth
                @if(auth()->user()->is_admin || (is_array(auth()->user()->permissions) && in_array('access_total_project_revenues', auth()->user()->permissions)))
                <a href="{{ route('admin.all-projects-revenues') }}" class="nav-link text-dark">
                    <i class="fas fa-coins me-2"></i>
                    إيرادات المشاريع
                </a>
                @endif

                @if(auth()->user()->is_admin)
                <a href="{{ route('admin.reports.unified') }}" class="nav-link text-dark">
                    <i class="fas fa-file-alt me-2"></i>
                    التقارير
                </a>

                <a href="{{ route('admin.users.index') }}" class="nav-link text-dark">
                    <i class="fas fa-users-cog me-2"></i>
                    إدارة أعضاء فريق العمل
                </a>
                @endif

                <a href="{{ route('profile.edit') }}" class="nav-link text-dark">
                    <i class="fas fa-user-edit me-2"></i>
                    حسابي الشخصي
                </a>

                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        تسجيل الخروج
                    </button>
                </form>
            @endauth
        </nav>
    </div>
</div>

<style>
.main-header {
    background: linear-gradient(135deg, #1a56db 0%, #1e429f 100%);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 1030;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.text-shadow {
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
}

.logo-container {
    transition: transform 0.2s ease;
}

.logo-container:hover {
    transform: scale(1.05);
}

.logo-img {
    height: 40px;
    width: auto;
}

.nav-link {
    transition: all 0.2s ease;
    position: relative;
}

.nav-link:after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: 0;
    left: 50%;
    background-color: white;
    transition: all 0.2s ease;
    transform: translateX(-50%);
    pointer-events: none;
}

.nav-link:hover:after {
    width: 100%;
}

.nav-link.active {
    background-color: rgba(255, 255, 255, 0.1) !important;
    border-radius: 6px;
}

.nav-item.nav-link {
    color: rgba(255, 255, 255, 0.9) !important;
    transition: all 0.3s ease;
    border-radius: 0.375rem;
    font-weight: 500;
}

.nav-item.nav-link:hover {
    color: white !important;
    background-color: rgba(255, 255, 255, 0.15);
    transform: translateY(-1px);
}

.nav-item.nav-link.active {
    color: white !important;
    background-color: rgba(255, 255, 255, 0.2);
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.dropdown-menu {
    border: none;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    border-radius: 0.5rem;
    margin-top: 0.5rem;
    z-index: 1050 !important;
}

.dropdown {
    position: relative;
}

.dropdown-toggle {
    cursor: pointer !important;
}

/* تنسيق الأزرار في الـ navbar */
.nav-link.btn {
    border: none !important;
    padding: 0.5rem 0.75rem !important;
    text-decoration: none !important;
}

.nav-link.btn:hover {
    background-color: rgba(255, 255, 255, 0.15) !important;
}

.nav-link.btn:focus {
    box-shadow: none !important;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

.bg-primary-light {
    background-color: rgba(59, 130, 246, 0.1) !important;
}

/* إخفاء زرار القائمة على الشاشات الكبيرة */
@media (min-width: 992px) {
    .d-lg-none {
        display: none !important;
    }
    
    /* إخفاء زرار الموبايل تحديداً على الشاشات الكبيرة */
    .main-header .d-lg-none,
    .main-header button[data-bs-target="#mobileMenu"],
    .offcanvas-end#mobileMenu {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }
}

@media (max-width: 991.98px) {
    .main-header .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .accordion-button {
        background: none !important;
        box-shadow: none !important;
        padding: 0.5rem 0;
    }

    .accordion-button::after {
        margin-right: auto;
        margin-left: 0;
    }

    .accordion-body {
        background-color: rgba(0, 0, 0, 0.02);
        border-radius: 0.5rem;
    }

    .accordion-body .nav-link {
        transition: all 0.3s ease;
    }

    .accordion-body .nav-link:hover {
        background-color: rgba(0, 0, 0, 0.03);
        transform: translateX(-5px);
    }
}

/* تحسين شكل الإشعارات */
.btn-link:focus {
    box-shadow: none;
}

.badge {
    font-size: 0.6rem;
}

/* تحسين القائمة المنسدلة للمستخدم */
.dropdown-header {
    padding: 0.5rem 1rem;
    margin-bottom: 0;
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

/* تنسيق الإشعارات */
.notification-dropdown {
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
}

.notification-item {
    padding: 12px 16px;
    border-bottom: 1px solid #e9ecef;
    transition: all 0.2s ease;
    cursor: pointer;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-item.unread {
    background-color: #e3f2fd;
    border-right: 3px solid #2196f3;
}

.notification-item.unread:hover {
    background-color: #bbdefb;
}

.notification-title {
    font-weight: 600;
    color: #1e3a8a;
    font-size: 0.9rem;
    margin-bottom: 4px;
}

.notification-message {
    color: #6b7280;
    font-size: 0.85rem;
    margin-bottom: 4px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.notification-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 4px;
}

.notification-time {
    color: #9ca3af;
    font-size: 0.75rem;
}

.notification-from {
    color: #6366f1;
    font-size: 0.75rem;
    font-weight: 500;
}

.notification-actions {
    padding-top: 8px;
    border-top: 1px solid #e9ecef;
}

.notification-actions .btn {
    font-size: 0.75rem;
    padding: 4px 12px;
    transition: all 0.2s ease;
}

.notification-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

/* تنسيق Modal الرد */
#replyNotificationModal {
    z-index: 99999 !important;
}

.modal-backdrop {
    z-index: 99998 !important;
}

#replyNotificationModal .modal-content {
    position: relative;
    z-index: 100000 !important;
}

#replyNotificationModal .modal-body {
    position: relative;
    z-index: 100001 !important;
}

#replyNotificationModal textarea {
    pointer-events: auto !important;
    user-select: text !important;
    -webkit-user-select: text !important;
    -moz-user-select: text !important;
    -ms-user-select: text !important;
    cursor: text !important;
}

#replyNotificationModal textarea:focus {
    outline: none !important;
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
}

#replyNotificationModal .modal-dialog {
    pointer-events: auto !important;
}

#notificationBadgeNav {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}
</style>

<script>
let notificationsLoaded = false;

// تحميل الإشعارات عند فتح القائمة
function loadNotifications() {
    console.log('loadNotifications called');
    if (notificationsLoaded) {
        console.log('Notifications already loaded');
        return;
    }
    
    console.log('Fetching notifications...');
    fetch('/admin/notifications')
        .then(response => response.json())
        .then(data => {
            console.log('Notifications received:', data);
            notificationsLoaded = true;
            updateNotificationUINav(data);
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            const loader = document.getElementById('notificationsLoaderNav');
            const empty = document.getElementById('notificationsEmptyNav');
            
            if(loader) loader.style.display = 'none';
            if(empty) empty.style.display = 'block';
        });
}

// تحديث واجهة الإشعارات (في الـ navbar)
function updateNotificationUINav(data) {
    const loader = document.getElementById('notificationsLoaderNav');
    const empty = document.getElementById('notificationsEmptyNav');
    const list = document.getElementById('notificationsListNav');
    const badge = document.getElementById('notificationBadgeNav');
    
    // Mobile elements
    const mobileList = document.getElementById('mobileNotificationsList');
    const mobileBadge = document.getElementById('mobileNotificationBadge');
    
    if(loader) loader.style.display = 'none';
    
    if (data.notifications && data.notifications.length > 0) {
        if(list) {
            list.innerHTML = data.notifications.map(notification => {
                // إذا كان الإشعار فيه link، نستخدم onclick للرابط
                const clickAction = notification.link 
                    ? `onclick="window.location.href='${notification.link}'" style="cursor: pointer; flex: 1;"`
                    : `onclick="viewNotification(${notification.id}, ${notification.work_order ? notification.work_order.id : 'null'})" style="cursor: pointer; flex: 1;"`;
                
                return `
                <li class="notification-item ${notification.is_read ? '' : 'unread'}">
                    <div ${clickAction}>
                    <div class="notification-title">
                            <i class="fas ${notification.type === 'daily_program' ? 'fa-calendar-day' : 'fa-comment-dots'} me-2 text-primary"></i>
                            ${notification.title || 'إشعار جديد'}
                    </div>
                    <div class="notification-message">
                        ${notification.message}
                    </div>
                    <div class="notification-footer">
                        <span class="notification-from">
                            <i class="fas fa-user me-1"></i>
                                ${notification.from_user || 'النظام'}
                        </span>
                        <span class="notification-time">
                            <i class="fas fa-clock me-1"></i>
                            ${notification.created_at}
                        </span>
                    </div>
                    </div>
                    ${notification.work_order ? `
                    <div class="notification-actions mt-2" style="display: flex; gap: 6px; justify-content: flex-end;">
                        <button class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation(); replyToNotification(${notification.id}, '${(notification.title || '').replace(/'/g, "\\'")}', '${(notification.from_user || '').replace(/'/g, "\\'")}', ${notification.work_order.id})" title="الرد">
                            <i class="fas fa-reply me-1"></i>
                            رد
                        </button>
                    </div>
                    ` : ''}
                </li>
                `;
            }).join('');
        }
        
        // تحديث الـ badge للـ desktop
        if (badge && data.unread_count > 0) {
            badge.textContent = data.unread_count;
            badge.style.display = 'block';
        } else if(badge) {
            badge.style.display = 'none';
        }
        
        // تحديث الـ badge للـ mobile
        if (mobileBadge && data.unread_count > 0) {
            mobileBadge.textContent = data.unread_count;
            mobileBadge.style.display = 'block';
        } else if(mobileBadge) {
            mobileBadge.style.display = 'none';
        }
        
        // تحديث القائمة للـ mobile
        if(mobileList) {
            mobileList.innerHTML = list ? list.innerHTML : '';
        }
    } else {
        if(empty) empty.style.display = 'block';
        if(badge) badge.style.display = 'none';
        if(mobileBadge) mobileBadge.style.display = 'none';
        if(mobileList) {
            mobileList.innerHTML = `
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-bell-slash fa-2x mb-2"></i>
                    <p class="mb-0">لا توجد إشعارات جديدة</p>
                </div>
            `;
        }
    }
}

// عرض الإشعار والانتقال لأمر العمل
function viewNotification(notificationId, workOrderId) {
    // تعليم الإشعار كمقروء
    fetch(`/admin/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // تحديث العداد
            notificationsLoaded = false;
            loadNotifications();
            
            // الانتقال لصفحة أمر العمل
            if (workOrderId) {
                window.location.href = `/admin/work-orders/${workOrderId}`;
            }
        }
    })
    .catch(error => console.error('Error marking notification as read:', error));
}

// تعليم جميع الإشعارات كمقروءة
function markAllAsRead() {
    fetch('/admin/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            notificationsLoaded = false;
            loadNotifications();
        }
    })
    .catch(error => console.error('Error marking all as read:', error));
}

// فتح نافذة الرد على الإشعار
function replyToNotification(notificationId, title, fromUser, workOrderId) {
    console.log('Opening reply modal for notification:', notificationId);
    
    // ملء بيانات الإشعار الأصلي
    document.getElementById('originalNotificationTitle').textContent = title;
    document.getElementById('originalNotificationFrom').textContent = fromUser;
    document.getElementById('replyNotificationId').value = notificationId;
    document.getElementById('replyWorkOrderId').value = workOrderId || '';
    
    // التأكد من تفريغ الـ textarea
    const textarea = document.getElementById('replyMessage');
    if (textarea) {
        textarea.value = '';
        textarea.disabled = false;
        textarea.readOnly = false;
        console.log('Textarea found and cleared');
    } else {
        console.error('Textarea not found!');
    }
    
    // فتح الـ modal
    const modalElement = document.getElementById('replyNotificationModal');
    if (!modalElement) {
        console.error('Modal element not found!');
        return;
    }
    
    const modal = new bootstrap.Modal(modalElement, {
        backdrop: true,
        keyboard: true,
        focus: true
    });
    
    // Focus على الـ textarea بعد فتح الـ modal
    modalElement.addEventListener('shown.bs.modal', function () {
        console.log('Modal shown event triggered');
        const textarea = document.getElementById('replyMessage');
        if (textarea) {
            setTimeout(() => {
                textarea.focus();
                textarea.click();
                console.log('Textarea focused and clicked');
            }, 300);
        }
    }, { once: true });
    
    modal.show();
    console.log('Modal show() called');
}

// إرسال الرد على الإشعار
function sendReply() {
    const notificationId = document.getElementById('replyNotificationId').value;
    const workOrderId = document.getElementById('replyWorkOrderId').value;
    const message = document.getElementById('replyMessage').value.trim();
    
    if (!message) {
        alert('يرجى كتابة رسالة الرد');
        return;
    }
    
    // إظهار مؤشر التحميل
    const sendButton = event.target;
    const originalText = sendButton.innerHTML;
    sendButton.disabled = true;
    sendButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري الإرسال...';
    
    // إرسال الرد
    fetch('/admin/notifications/reply', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            notification_id: notificationId,
            work_order_id: workOrderId,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        sendButton.disabled = false;
        sendButton.innerHTML = originalText;
        
        if (data.success) {
            // إغلاق الـ modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('replyNotificationModal'));
            modal.hide();
            
            // إظهار رسالة نجاح
            const successToast = document.createElement('div');
            successToast.innerHTML = `
                <div style="position: fixed; top: 20px; right: 20px; background: #28a745; color: white; padding: 15px 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 10000; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-check-circle"></i>
                    <span>تم إرسال الرد بنجاح!</span>
                </div>
            `;
            document.body.appendChild(successToast);
            
            setTimeout(() => {
                document.body.removeChild(successToast);
            }, 3000);
            
            // إعادة تحميل الإشعارات
            notificationsLoaded = false;
            loadNotifications();
        } else {
            alert('حدث خطأ: ' + (data.message || 'فشل إرسال الرد'));
        }
    })
    .catch(error => {
        sendButton.disabled = false;
        sendButton.innerHTML = originalText;
        console.error('Error sending reply:', error);
        alert('حدث خطأ أثناء إرسال الرد');
    });
}

// تفعيل تحميل الإشعارات عند فتح القائمة
document.addEventListener('DOMContentLoaded', function() {
    // للزر في الـ navbar
    const notifNavButton = document.getElementById('notificationsNavButton');
    if (notifNavButton) {
        const dropdownMenu = notifNavButton.nextElementSibling;
        
        // إنشاء dropdown instance
        let dropdownInstance = null;
        
        // عند الضغط على الزر
        notifNavButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Notifications button clicked');
            
            // إنشاء أو الحصول على dropdown instance
            if (!dropdownInstance) {
                dropdownInstance = new bootstrap.Dropdown(notifNavButton, {
                    autoClose: true,
                    boundary: 'viewport'
                });
            }
            
            // التبديل بين الفتح والإغلاق
            if (dropdownMenu.classList.contains('show')) {
                dropdownInstance.hide();
            } else {
                // تحميل الإشعارات قبل الفتح
                loadNotifications();
                dropdownInstance.show();
            }
        });
        
        // عند فتح الـ dropdown
        notifNavButton.addEventListener('show.bs.dropdown', function () {
            console.log('Dropdown showing...');
            loadNotifications();
        });
        
        // عند إغلاق الـ dropdown من الخارج
        notifNavButton.addEventListener('hide.bs.dropdown', function () {
            console.log('Dropdown hiding...');
        });
    }
    
    // للزر الخاص بـ Profile (ahmedelkomy)
    const userDropdownButton = document.getElementById('userDropdown');
    if (userDropdownButton) {
        const userDropdownMenu = userDropdownButton.nextElementSibling;
        let userDropdownInstance = null;
        
        userDropdownButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('User dropdown button clicked');
            
            // إنشاء أو الحصول على dropdown instance
            if (!userDropdownInstance) {
                userDropdownInstance = new bootstrap.Dropdown(userDropdownButton, {
                    autoClose: true,
                    boundary: 'viewport'
                });
            }
            
            // التبديل بين الفتح والإغلاق
            if (userDropdownMenu.classList.contains('show')) {
                userDropdownInstance.hide();
            } else {
                userDropdownInstance.show();
            }
        });
        
        // عند فتح الـ dropdown
        userDropdownButton.addEventListener('show.bs.dropdown', function () {
            console.log('User dropdown showing...');
        });
        
        // عند إغلاق الـ dropdown
        userDropdownButton.addEventListener('hide.bs.dropdown', function () {
            console.log('User dropdown hiding...');
        });
    }
    
    // للزر الخاص بالإشعارات على الموبايل
    const mobileNotifButton = document.getElementById('mobileNotificationsButton');
    if (mobileNotifButton) {
        const mobileDropdownMenu = mobileNotifButton.nextElementSibling;
        let mobileDropdownInstance = null;
        
        mobileNotifButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Mobile notifications button clicked');
            
            if (!mobileDropdownInstance) {
                mobileDropdownInstance = new bootstrap.Dropdown(mobileNotifButton, {
                    autoClose: true,
                    boundary: 'viewport'
                });
            }
            
            if (mobileDropdownMenu.classList.contains('show')) {
                mobileDropdownInstance.hide();
            } else {
                loadNotifications();
                mobileDropdownInstance.show();
            }
        });
        
        mobileNotifButton.addEventListener('show.bs.dropdown', function() {
            console.log('Mobile dropdown showing...');
            loadNotifications();
        });
    }
});
</script> 

<!-- Modal للرد على الإشعار - خارج الـ header -->
<div class="modal fade" id="replyNotificationModal" tabindex="-1" aria-labelledby="replyNotificationModalLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true" style="z-index: 99999;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="replyNotificationModalLabel">
                    <i class="fas fa-reply me-2"></i>
                    الرد على الإشعار
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">الإشعار الأصلي:</label>
                    <div class="alert alert-light" id="originalNotificationContent">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-bold text-primary" id="originalNotificationTitle"></span>
                            <span class="badge bg-info text-white" id="originalNotificationFrom"></span>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="replyMessage" class="form-label fw-bold">
                        <i class="fas fa-comment-dots me-1"></i>
                        رسالة الرد:
                    </label>
                    <textarea 
                        class="form-control" 
                        id="replyMessage" 
                        rows="4" 
                        placeholder="اكتب ردك هنا..." 
                        required 
                        autocomplete="off"
                        style="resize: vertical; min-height: 100px; position: relative; z-index: 100000;"></textarea>
                    <div class="form-text">سيتم إرسال الرد كإشعار للمستخدم</div>
                </div>
                <input type="hidden" id="replyNotificationId">
                <input type="hidden" id="replyWorkOrderId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    إلغاء
                </button>
                <button type="button" class="btn btn-primary" onclick="sendReply()">
                    <i class="fas fa-paper-plane me-1"></i>
                    إرسال الرد
                </button>
            </div>
        </div>
    </div>
</div> 