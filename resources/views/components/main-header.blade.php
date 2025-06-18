{{-- استخدام المتغيرات من الكلاس --}}

<header class="main-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center px-3 py-2">
            <!-- Logo and App Name -->
            <div class="d-flex align-items-center">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                    <div class="logo-container bg-white rounded-circle p-1 me-2">
                        <img class="logo-img" src="{{ asset('images/logo-sahm.svg') }}" alt="Sahm Logo">
                    </div>
                    <span class="fs-5 fw-bold text-white d-none d-md-inline">شركة سهم بلدي للمقاولات</span>
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
                        <a href="{{ route('admin.users.index') }}" 
                           class="nav-item nav-link px-3 py-2 rounded {{ $isUsersPage ? 'active' : '' }}">
                            <i class="fas fa-users-cog me-1"></i>
                            إدارة المستخدمين
                        </a>
                        @endif

                        <a href="{{ route('admin.work-orders.index') }}" 
                           class="nav-item nav-link px-3 py-2 rounded {{ $isWorkOrdersPage ? 'active' : '' }}">
                            <i class="fas fa-clipboard-list me-1"></i>
                            أوامر العمل
                        </a>

                        @if(auth()->user()->is_admin)
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle px-3 py-2 rounded" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cog me-1"></i>
                                الإعدادات
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">إعدادات النظام</a></li>
                                <li><a class="dropdown-item" href="#">النسخ الاحتياطي</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">التقارير</a></li>
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
                <div class="dropdown me-3">
                    <button class="btn btn-link text-white position-relative" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bell fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                            <span class="visually-hidden">إشعارات غير مقروءة</span>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">الإشعارات</h6></li>
                        <li><a class="dropdown-item" href="#">إشعار جديد 1</a></li>
                        <li><a class="dropdown-item" href="#">إشعار جديد 2</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="#">عرض جميع الإشعارات</a></li>
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
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cog me-2"></i>
                                الإعدادات
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
                @if(auth()->user()->is_admin)
                <a href="{{ route('admin.users.index') }}" 
                   class="nav-link {{ $isUsersPage ? 'active fw-bold text-primary' : 'text-dark' }}">
                    <i class="fas fa-users-cog me-2"></i>
                    إدارة المستخدمين
                </a>
                @endif

                <a href="{{ route('admin.work-orders.index') }}" 
                   class="nav-link {{ $isWorkOrdersPage ? 'active fw-bold text-primary' : 'text-dark' }}">
                    <i class="fas fa-clipboard-list me-2"></i>
                    أوامر العمل
                </a>

                <hr class="my-3">

                <a href="{{ route('profile.edit') }}" class="nav-link text-dark">
                    <i class="fas fa-user-edit me-2"></i>
                    حسابي الشخصي
                </a>

                @if(auth()->user()->is_admin)
                <a href="#" class="nav-link text-dark">
                    <i class="fas fa-cog me-2"></i>
                    الإعدادات
                </a>
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
    background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 1030;
}

.logo-img {
    height: 40px;
    width: auto;
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
</style> 