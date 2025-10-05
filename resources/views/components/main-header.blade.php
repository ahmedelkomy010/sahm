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
                        @if(auth()->user()->is_admin)
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle px-3 py-2 rounded text-white {{ request()->is('admin/users*') || request()->is('admin/settings*') || request()->is('admin/reports*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cog me-1"></i>
                                الإعدادات
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                        <i class="fas fa-users-cog me-2"></i>
                                        إدارة المستخدمين
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.reports.unified') }}">
                                        <i class="fas fa-file-alt me-2"></i>
                                        التقارير العامة للعقد الموحد
                                    </a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-sliders-h me-2"></i>إعدادات النظام</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-database me-2"></i>النسخ الاحتياطي</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-chart-bar me-2"></i>التقارير</a></li>
                            </ul>
                        </div>
                        @endif
                    @endauth
                </nav>
            </div>
            
            <!-- User Menu -->
            @auth
            <div class="d-flex align-items-center">
                <!-- Notifications -->
                <div class="dropdown me-3" id="notificationsDropdown">
                    <button class="btn btn-link text-white position-relative" 
                            type="button" 
                            data-bs-toggle="dropdown" 
                            id="notificationsButton"
                            onclick="loadNotifications()">
                        <i class="fas fa-bell fs-5"></i>
                        <span id="notificationBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">
                            0
                            <span class="visually-hidden">إشعارات غير مقروءة</span>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" style="min-width: 350px; max-height: 500px; overflow-y: auto;">
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
                        <li id="notificationsLoader" class="text-center py-4">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">جاري التحميل...</span>
                            </div>
                            <p class="text-muted small mt-2 mb-0">جاري تحميل الإشعارات...</p>
                        </li>
                        <li id="notificationsEmpty" style="display: none;" class="text-center py-4 text-muted">
                            <i class="fas fa-bell-slash fa-2x mb-2 text-muted"></i>
                            <p class="mb-0">لا توجد إشعارات</p>
                        </li>
                        <div id="notificationsList"></div>
                    </ul>
                </div>

                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-link text-white d-flex align-items-center text-decoration-none dropdown-toggle" 
                            type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle fs-5 me-1"></i>
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
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
            <div class="d-lg-none">
                <button class="btn btn-link text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
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
                <a href="{{ route('profile.edit') }}" class="nav-link text-dark">
                    <i class="fas fa-user-edit me-2"></i>
                    حسابي الشخصي
                </a>

                @if(auth()->user()->is_admin)
                <!-- Settings Dropdown for Mobile -->
                <div class="accordion" id="settingsAccordion">
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed nav-link text-dark {{ request()->is('admin/users*') || request()->is('admin/settings*') || request()->is('admin/reports*') ? 'active fw-bold text-primary' : '' }}" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#settingsCollapse">
                                <i class="fas fa-cog me-2"></i>
                                الإعدادات
                            </button>
                        </h2>
                        <div id="settingsCollapse" class="accordion-collapse collapse" data-bs-parent="#settingsAccordion">
                            <div class="accordion-body p-0">
                                <a class="nav-link py-2 ps-4 text-dark" href="{{ route('admin.users.index') }}">
                                    <i class="fas fa-users-cog me-2"></i>
                                    إدارة المستخدمين
                                </a>
                                
                                <a class="nav-link py-2 ps-4 text-dark" href="#">
                                    <i class="fas fa-sliders-h me-2"></i>
                                    إعدادات النظام
                                </a>
                                <a class="nav-link py-2 ps-4 text-dark" href="#">
                                    <i class="fas fa-database me-2"></i>
                                    النسخ الاحتياطي
                                </a>
                                <hr class="my-2">
                                <a class="nav-link py-2 ps-4 text-dark" href="{{ route('admin.reports.unified') }}">
                                    <i class="fas fa-file-alt me-2"></i>
                                    التقارير العامة للعقد الموحد
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

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
}

.nav-link:hover:after {
    width: 100%;
}

.nav-link.active {
    background-color: rgba(255, 255, 255, 0.1) !important;
    border-radius: 6px;
}
    width: auto;


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

#notificationBadge {
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
    if (notificationsLoaded) return;
    
    fetch('/admin/notifications')
        .then(response => response.json())
        .then(data => {
            notificationsLoaded = true;
            updateNotificationUI(data);
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            document.getElementById('notificationsLoader').style.display = 'none';
            document.getElementById('notificationsEmpty').style.display = 'block';
        });
}

// تحديث واجهة الإشعارات
function updateNotificationUI(data) {
    const loader = document.getElementById('notificationsLoader');
    const empty = document.getElementById('notificationsEmpty');
    const list = document.getElementById('notificationsList');
    const badge = document.getElementById('notificationBadge');
    
    loader.style.display = 'none';
    
    if (data.notifications && data.notifications.length > 0) {
        list.innerHTML = data.notifications.map(notification => `
            <li class="notification-item ${notification.is_read ? '' : 'unread'}" 
                onclick="viewNotification(${notification.id}, ${notification.work_order ? notification.work_order.id : 'null'})">
                <div class="notification-title">
                    <i class="fas fa-comment-dots me-2 text-primary"></i>
                    ${notification.title}
                </div>
                <div class="notification-message">
                    ${notification.message}
                </div>
                <div class="notification-footer">
                    <span class="notification-from">
                        <i class="fas fa-user me-1"></i>
                        ${notification.from_user}
                    </span>
                    <span class="notification-time">
                        <i class="fas fa-clock me-1"></i>
                        ${notification.created_at}
                    </span>
                </div>
            </li>
        `).join('');
        
        // تحديث الـ badge
        if (data.unread_count > 0) {
            badge.textContent = data.unread_count;
            badge.style.display = 'block';
        } else {
            badge.style.display = 'none';
        }
    } else {
        empty.style.display = 'block';
        badge.style.display = 'none';
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

// تحديث عداد الإشعارات تلقائياً كل دقيقة
setInterval(() => {
    fetch('/admin/notifications?limit=1')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('notificationBadge');
            if (data.unread_count > 0) {
                badge.textContent = data.unread_count;
                badge.style.display = 'block';
            } else {
                badge.style.display = 'none';
            }
        })
        .catch(error => console.error('Error updating notification count:', error));
}, 60000); // كل دقيقة

// تحميل عداد الإشعارات عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', () => {
    fetch('/admin/notifications?limit=1')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('notificationBadge');
            if (data.unread_count > 0) {
                badge.textContent = data.unread_count;
                badge.style.display = 'block';
            }
        })
        .catch(error => console.error('Error loading notification count:', error));
});
</script> 