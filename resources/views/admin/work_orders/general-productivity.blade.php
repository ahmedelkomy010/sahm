@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-primary text-white py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 fs-3">
                                <i class="fas fa-tachometer-alt me-3"></i>
                                لوحة التحكم التحليلية (Dashboard)
                            </h2>
                            <p class="mb-0 text-white-75">
                                @if(isset($project))
                                    @if($project == 'riyadh')
                                        <i class="fas fa-city me-2"></i><strong>مشروع الرياض</strong> - بيانات مخصصة لمدينة الرياض
                                    @elseif($project == 'madinah')
                                        <i class="fas fa-mosque me-2"></i><strong>مشروع المدينة المنورة</strong> - بيانات مخصصة للمدينة المنورة
                                    @else
                                        <i class="fas fa-chart-line me-2"></i>نظام التحليلات والتقارير المتقدمة
                                    @endif
                                @else
                                    <i class="fas fa-chart-line me-2"></i>نظام التحليلات والتقارير المتقدمة
                                @endif
                            </p>
                        </div>
                        <div class="text-end">
                            <div class="d-flex flex-column align-items-end gap-2">
                                <div class="badge bg-light text-dark fs-6 px-3 py-2">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    {{ now()->format('Y/m/d') }}
                                </div>
                                
                                <!-- Project Switcher -->
                                <div class="btn-group" role="group">
                                    <a href="{{ url('/admin/work-orders/productivity/riyadh') }}" 
                                       class="btn btn-sm {{ (isset($project) && $project == 'riyadh') ? 'btn-light' : 'btn-outline-light' }}">
                                        <i class="fas fa-city me-1"></i>الرياض
                                    </a>
                                    <a href="{{ url('/admin/work-orders/productivity/madinah') }}" 
                                       class="btn btn-sm {{ (isset($project) && $project == 'madinah') ? 'btn-light' : 'btn-outline-light' }}">
                                        <i class="fas fa-mosque me-1"></i>المدينة المنورة
                                    </a>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

            <!-- Date Filter Section -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="filter_start_date" class="form-label">
                                <i class="fas fa-calendar-alt me-1"></i>
                                تاريخ البداية
                            </label>
                            <input type="date" class="form-control" id="filter_start_date" name="start_date">
                        </div>
                        
                        <div class="col-md-3">
                            <label for="filter_end_date" class="form-label">
                                <i class="fas fa-calendar-alt me-1"></i>
                                تاريخ النهاية
                            </label>
                            <input type="date" class="form-control" id="filter_end_date" name="end_date">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-clock me-1"></i>
                                فترات زمنية سريعة
                            </label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setQuickDateRange('day')">
                                        <i class="fas fa-calendar-day me-1"></i>
                                        يوم
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setQuickDateRange('quarter')">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        ربع سنة
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setQuickDateRange('half')">
                                        <i class="fas fa-calendar me-1"></i>
                                        نصف سنة
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setQuickDateRange('year')">
                                        <i class="fas fa-calendar-check me-1"></i>
                                        سنة
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary w-100 mb-2" onclick="applyDateFilter()">
                                <i class="fas fa-filter me-1"></i>
                                تطبيق
                            </button>
                            <button type="button" class="btn btn-outline-secondary w-100" onclick="clearDateFilter()">
                                <i class="fas fa-times me-1"></i>
                                مسح
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BI Dashboard Sections -->
            <div class="row g-3 mb-3">
                
                <!-- القسم الأول: الاستلامات -->
                <div class="col-lg-4">
                    <div class="card h-100 shadow-sm border-0 dashboard-card receipts-card">
                        <div class="card-header bg-success text-white text-center py-4">
                            <div class="dashboard-icon mb-3">
                                <i class="fas fa-inbox fa-3x"></i>
                                                        </div>
                            <h4 class="mb-1">الاستلامات</h4>
                            <p class="mb-0 small">
                                @if(isset($project))
                                    @if($project == 'riyadh')
                                        أوامر العمل المستلمة - الرياض
                                    @elseif($project == 'madinah')
                                        أوامر العمل المستلمة - المدينة المنورة
                                    @else
                                        أوامر العمل المستلمة والقيم المبدئية
                                    @endif
                                @else
                                    أوامر العمل المستلمة والقيم المبدئية
                                @endif
                            </p>
                                                    </div>
                        <div class="card-body d-flex flex-column">
                            <div class="mb-3 flex-grow-1">
                                <div class="row text-center mb-3">
                                    <div class="col-6">
                                        <div class="stat-item">
                                            <i class="fas fa-clipboard-check text-success mb-2"></i>
                                            <h6 class="stat-number" id="workOrdersCount">0</h6>
                                            <small class="text-muted">أوامر مستلمة</small>
                                                        </div>
                                                    </div>
                                    <div class="col-6">
                                        <div class="stat-item">
                                            <i class="fas fa-money-bill-wave text-info mb-2"></i>
                                            <h6 class="stat-number" id="totalValue">0</h6>
                                            <small class="text-muted">القيمة المبدئية (بدون استشاري)</small>
                                                </div>
                                            </div>
                                                    </div>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-success" id="receiptsProgressBar" style="width: 0%"></div>
                                                </div>
                                <small class="text-muted" id="receiptsProgressText">معدل الاستلام الشهري</small>
                                            </div>
                            <a href="#" class="btn btn-success btn-lg w-100 dashboard-btn" onclick="navigateToSection('receipts')">
                                <i class="fas fa-arrow-left me-2"></i>
                                دخول القسم
                            </a>
                                            </div>
                                        </div>
                                </div>

                <!-- القسم الثاني: التنفيذ والإنجاز -->
                <div class="col-lg-4">
                    <div class="card h-100 shadow-sm border-0 dashboard-card execution-card">
                        <div class="card-header bg-warning text-white text-center py-4">
                            <div class="dashboard-icon mb-3">
                                <i class="fas fa-tasks fa-3x"></i>
                            </div>
                            <h4 class="mb-1">التنفيذ والإنجاز</h4>
                            <p class="mb-0 small">أوامر العمل المنفذة والسعر الإجمالي</p>
                                                    </div>
                        <div class="card-body d-flex flex-column">
                            <div class="mb-3 flex-grow-1">
                                <div class="row text-center mb-3">
                                    <div class="col-6">
                                        <div class="stat-item">
                                            <i class="fas fa-clipboard-check text-warning mb-2"></i>
                                            <h6 class="stat-number" id="executedOrdersCount">0</h6>
                                            <small class="text-muted">أوامر منفذة</small>
                                                        </div>
                                                    </div>
                                    <div class="col-6">
                                        <div class="stat-item">
                                            <i class="fas fa-money-check-alt text-success mb-2"></i>
                                            <h6 class="stat-number" id="totalExecutedValue">0</h6>
                                            <small class="text-muted">السعر الإجمالي المنفذ</small>
                                                </div>
                                            </div>
                                                    </div>
                                
                                <!-- حالات التنفيذ -->
                                <div class="execution-status-list mb-3" id="executionStatusList">
                                    <div class="status-item-small" style="background-color: rgba(112, 68, 2, 0.1); border-right: 3px solid rgb(112, 68, 2);">
                                        <span class="status-count" id="status2Count">0</span>
                                        <small class="text-muted">تم التنفيذ بالموقع</small>
                                    </div>
                                    <div class="status-item-small" style="background-color: rgba(165, 0, 52, 0.1); border-right: 3px solid rgb(165, 0, 52);">
                                        <span class="status-count" id="status3Count">0</span>
                                        <small class="text-muted">تم تسليم 155</small>
                                    </div>
                                    <div class="status-item-small" style="background-color: rgba(0, 149, 54, 0.1); border-right: 3px solid rgb(0, 149, 54);">
                                        <span class="status-count" id="status4Count">0</span>
                                        <small class="text-muted">مستخلص دفعة أولى</small>
                                    </div>
                                    <div class="status-item-small" style="background-color: rgba(41, 128, 185, 0.1); border-right: 3px solid rgb(41, 128, 185);">
                                        <span class="status-count" id="status5Count">0</span>
                                        <small class="text-muted">صرف دفعة أولى</small>
                                    </div>
                                    <div class="status-item-small" style="background-color: rgba(155, 89, 182, 0.1); border-right: 3px solid rgb(155, 89, 182);">
                                        <span class="status-count" id="status6Count">0</span>
                                        <small class="text-muted">مستخلص دفعة ثانية</small>
                                    </div>
                                    <div class="status-item-small" style="background-color: rgba(0, 149, 54, 0.1); border-right: 3px solid rgb(0, 149, 54);">
                                        <span class="status-count" id="status8Count">0</span>
                                        <small class="text-muted">تم اصدار شهادة الانجاز</small>
                                    </div>
                                    <div class="status-item-small" style="background-color: rgba(52, 152, 219, 0.1); border-right: 3px solid rgb(52, 152, 219);">
                                        <span class="status-count" id="status10Count">0</span>
                                        <small class="text-muted">مستخلص كلي</small>
                                    </div>
                                </div>
                                
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-warning" id="executionProgressBar" style="width: 0%"></div>
                                                </div>
                                <small class="text-muted" id="executionProgressText">معدل الإنجاز</small>
                                            </div>
                            <a href="#" class="btn btn-warning btn-lg w-100 dashboard-btn" onclick="navigateToSection('execution')">
                                <i class="fas fa-arrow-left me-2"></i>
                                دخول القسم
                            </a>
                                            </div>
                        </div>
                    </div>

                <!-- القسم الثالث: جاري العمل -->
                <div class="col-lg-4">
                    <div class="card h-100 shadow-sm border-0 dashboard-card inprogress-card">
                        <div class="card-header bg-info text-white text-center py-4">
                            <div class="dashboard-icon mb-3">
                                <i class="fas fa-cogs fa-3x"></i>
                                                </div>
                            <h4 class="mb-1">جاري العمل</h4>
                            <p class="mb-0 small">متابعة المشاريع وأوامر العمل</p>
                                            </div>
                        <div class="card-body d-flex flex-column">
                            <div class="mb-3 flex-grow-1">
                                <div class="row text-center mb-3">
                                    <div class="col-6">
                                        <div class="stat-item">
                                            <i class="fas fa-play-circle text-info mb-2"></i>
                                            <h6 class="stat-number" id="inProgressOrdersCount">0</h6>
                                            <small class="text-muted">أوامر جاري العمل</small>
                                        </div>
                                                </div>
                                    <div class="col-6">
                                        <div class="stat-item">
                                            <i class="fas fa-money-bill text-primary mb-2"></i>
                                            <h6 class="stat-number" id="inProgressTotalValue">0</h6>
                                            <small class="text-muted">القيمة المبدئية (بدون استشاري)</small>
                                            </div>
                                        </div>
                                                </div>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-info" id="inProgressProgressBar" style="width: 0%"></div>
                                            </div>
                                <small class="text-muted" id="inProgressProgressText">نسبة أوامر جاري العمل</small>
                                        </div>
                            <a href="#" class="btn btn-info btn-lg w-100 dashboard-btn" onclick="navigateToSection('inprogress')">
                                <i class="fas fa-arrow-left me-2"></i>
                                دخول القسم
                            </a>
                                                </div>
                                            </div>
                                        </div>

            </div>
            
            <div class="row g-3">
                <!-- القسم الرابع: المستخلص -->
                <div class="col-lg-6">
                    <div class="card h-100 shadow-sm border-0 dashboard-card extract-card">
                        <div class="card-header bg-danger text-white text-center py-4">
                            <div class="dashboard-icon mb-3">
                                <i class="fas fa-file-invoice-dollar fa-3x"></i>
                                    </div>
                            <h4 class="mb-1">المستخلص</h4>
                            <p class="mb-0 small">عدد المستخلص وأوامر العمل</p>
                                                </div>
                        <div class="card-body d-flex flex-column">
                            <div class="mb-3 flex-grow-1">
                                <div class="row text-center mb-3">
                                    <div class="col-6">
                                        <div class="stat-item">
                                            <i class="fas fa-file-alt text-danger mb-2"></i>
                                            <h6 class="stat-number" id="extractsValue">0</h6>
                                            <small class="text-muted">إجمالي قيمة المستخلص</small>
                                            </div>
                                        </div>
                                    <div class="col-6">
                                        <div class="stat-item">
                                            <i class="fas fa-list-ol text-warning mb-2"></i>
                                            <h6 class="stat-number" id="extractOrdersCount">0</h6>
                                            <small class="text-muted">أوامر العمل</small>
                                                </div>
                                            </div>
                                        </div>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-danger" id="extractProgressBar" style="width: 0%"></div>
                                    </div>
                                <small class="text-muted" id="extractProgressText">نسبة المستخلص</small>
                                </div>
                            <a href="#" class="btn btn-danger btn-lg w-100 dashboard-btn" onclick="navigateToSection('extracts')">
                                <i class="fas fa-arrow-left me-2"></i>
                                دخول القسم
                            </a>
                            </div>
                        </div>
                    </div>

                <!-- القسم الخامس: منتهي تم الصرف -->
                <div class="col-lg-6">
                    <div class="card h-100 shadow-sm border-0 dashboard-card completed-card">
                        <div class="card-header bg-secondary text-white text-center py-4">
                            <div class="dashboard-icon mb-3">
                                <i class="fas fa-check-circle fa-3x"></i>
                                    </div>
                            <h4 class="mb-1">منتهي تم الصرف</h4>
                            <p class="mb-0 small">أوامر العمل المكتملة والمصروفة</p>
                                                </div>
                        <div class="card-body d-flex flex-column">
                            <div class="mb-3 flex-grow-1">
                                <div class="row text-center mb-3">
                                    <div class="col-6">
                                        <div class="stat-item">
                                            <i class="fas fa-clipboard-check text-secondary mb-2"></i>
                                            <h6 class="stat-number" id="completedOrdersCount">0</h6>
                                            <small class="text-muted">أوامر مكتملة</small>
                                            </div>
                                        </div>
                                    <div class="col-6">
                                        <div class="stat-item">
                                            <i class="fas fa-money-check-alt text-success mb-2"></i>
                                            <h6 class="stat-number" id="completedTotalValue">0</h6>
                                            <small class="text-muted">القيمة الكلية النهائية</small>
                                                </div>
                                            </div>
                                        </div>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-secondary" id="completedProgressBar" style="width: 0%"></div>
                                    </div>
                                <small class="text-muted" id="completedProgressText">نسبة الإنجاز الكامل</small>
                                </div>
                            <a href="#" class="btn btn-secondary btn-lg w-100 dashboard-btn" onclick="navigateToSection('completed')">
                                <i class="fas fa-arrow-left me-2"></i>
                                دخول القسم
                            </a>
                            </div>
                        </div>
                    </div>

                <!-- Quick Stats Overview -->
                <div class="col-12">
                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-header bg-light">
                                <h5 class="mb-0">
                                <i class="fas fa-chart-bar me-2 text-primary"></i>
                                نظرة عامة سريعة - اختصارات تحليلية
                                </h5>
                            </div>
                            <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                                            <i class="fas fa-inbox text-success fs-4"></i>
                                </div>
                                        <h4 class="mb-1" id="quickReceiptsCount">0</h4>
                                        <small class="text-muted">أوامر مستلمة</small>
                            </div>
                        </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                                            <i class="fas fa-tasks text-warning fs-4"></i>
                    </div>
                                        <h4 class="mb-1" id="quickExecutionCount">0</h4>
                                        <small class="text-muted">أوامر منفذة</small>
                    </div>
                        </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                                            <i class="fas fa-cogs text-info fs-4"></i>
                    </div>
                                        <h4 class="mb-1" id="quickInProgressCount">0</h4>
                                        <small class="text-muted">جاري العمل</small>
                </div>
            </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="bg-secondary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                                            <i class="fas fa-check-circle text-secondary fs-4"></i>
                                        </div>
                                        <h4 class="mb-1" id="quickCompletedCount">0</h4>
                                        <small class="text-muted">منتهي تم الصرف</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* BI Dashboard Styles */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .bg-gradient-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .bg-gradient-danger {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    
    .bg-gradient-secondary {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    }

    .dashboard-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border-radius: 12px;
        overflow: hidden;
        height: 100%;
    }
    
    .dashboard-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important;
    }
    
    .dashboard-card .card-header {
        padding: 1.5rem 1rem;
    }
    
    .dashboard-card .card-body {
        padding: 1.25rem;
    }
    
    .dashboard-icon {
        opacity: 0.9;
    }
    
    .stat-item {
        padding: 8px;
    }
    
    .stat-number {
        font-size: 1.4rem;
        font-weight: bold;
        margin-bottom: 0;
    }
    
    .stat-item small {
        font-size: 0.8rem;
        line-height: 1.3;
    }
    
    .dashboard-btn {
        border-radius: 8px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        transition: all 0.3s ease;
        padding: 0.65rem 1rem;
        font-size: 0.9rem;
    }
    
    .dashboard-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .text-purple {
        color: #6f42c1 !important;
    }
    
    .progress {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .progress-bar {
        border-radius: 10px;
        transition: width 0.6s ease;
    }

    /* Project Switcher Styles */
    .btn-group .btn {
        font-weight: 600;
        border-radius: 8px !important;
        transition: all 0.3s ease;
    }
    
    .btn-group .btn:first-child {
        border-top-left-radius: 8px !important;
        border-bottom-left-radius: 8px !important;
    }
    
    .btn-group .btn:last-child {
        border-top-right-radius: 8px !important;
        border-bottom-right-radius: 8px !important;
    }
    
    .btn-group .btn-light {
        background: rgba(255, 255, 255, 0.9);
        color: #333;
        border-color: rgba(255, 255, 255, 0.5);
    }
    
    .btn-group .btn-outline-light {
        background: rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.8);
        border-color: rgba(255, 255, 255, 0.3);
    }
    
    .btn-group .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border-color: rgba(255, 255, 255, 0.5);
    }

    /* Execution Status List */
    .execution-status-list {
        display: flex;
        flex-direction: column;
        gap: 5px;
        max-height: 280px;
        overflow-y: auto;
    }
    
    .status-item-small {
        padding: 5px 8px;
        border-radius: 4px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s ease;
    }
    
    .status-item-small:hover {
        transform: translateX(-3px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .status-item-small .status-count {
        font-weight: bold;
        font-size: 0.95rem;
        min-width: 25px;
    }
    
    .status-item-small small {
        font-size: 0.7rem;
        flex: 1;
        text-align: right;
        margin-right: 6px;
    }

    /* RTL Support */
    body {
        direction: rtl;
        text-align: right;
    }
    
    .card-header h4, .card-header p {
        text-align: center;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .col-xl-4 {
            margin-bottom: 2rem;
        }
        
        .dashboard-card {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click events to cards
    document.querySelectorAll('.dashboard-card').forEach(card => {
        card.addEventListener('click', function() {
            const button = this.querySelector('.dashboard-btn');
            if (button) {
                button.click();
            }
        });
    });
    
    // Add hover effects
    document.querySelectorAll('.dashboard-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
    });
});

    // Calculate and display work orders statistics immediately
    updateWorkOrdersStats();
    
    // Start observing table changes
    observeTableChanges();
    
    // Force update after a short delay to ensure DOM is ready
    setTimeout(() => {
        // Update all stats
        updateWorkOrdersStats();
        updateExecutionStats();
        updateInProgressStats();
        updateExtractStats();
        updateCompletedStats();
    }, 500);
    
    // Additional update after 2 seconds to ensure all APIs have responded
    setTimeout(() => {
        updateWorkOrdersStats();
        updateExecutionStats();
        updateInProgressStats();
        updateExtractStats();
        updateCompletedStats();
        updateQuickStats();
    }, 2000);
    
    // Update stats every 10 seconds to keep data fresh
    setInterval(() => {
        updateWorkOrdersStats();
        updateExecutionStats();
        updateInProgressStats();
        updateExtractStats();
        updateCompletedStats();
        updateQuickStats();
    }, 10000);
});

// Update quick stats overview
function updateQuickStats() {
    const quickReceiptsElement = document.getElementById('quickReceiptsCount');
    const quickExecutionElement = document.getElementById('quickExecutionCount');
    const quickInProgressElement = document.getElementById('quickInProgressCount');
    const quickCompletedElement = document.getElementById('quickCompletedCount');
    
    // Get values from main cards
    const receiptsCount = document.getElementById('workOrdersCount')?.textContent || '0';
    const executionCount = document.getElementById('executedOrdersCount')?.textContent || '0';
    const inProgressCount = document.getElementById('inProgressOrdersCount')?.textContent || '0';
    const completedCount = document.getElementById('completedOrdersCount')?.textContent || '0';
    
    if (quickReceiptsElement) quickReceiptsElement.textContent = receiptsCount;
    if (quickExecutionElement) quickExecutionElement.textContent = executionCount;
    if (quickInProgressElement) quickInProgressElement.textContent = inProgressCount;
    if (quickCompletedElement) quickCompletedElement.textContent = completedCount;
}

// Set quick date range
function setQuickDateRange(period) {
    const today = new Date();
    const endDate = today.toISOString().split('T')[0];
    let startDate;
    
    switch(period) {
        case 'day':
            // آخر يوم (24 ساعة)
            const dayAgo = new Date(today);
            dayAgo.setDate(today.getDate() - 1);
            startDate = dayAgo.toISOString().split('T')[0];
            break;
        case 'quarter':
            // ربع سنة (3 أشهر)
            const quarterAgo = new Date(today);
            quarterAgo.setMonth(today.getMonth() - 3);
            startDate = quarterAgo.toISOString().split('T')[0];
            break;
        case 'half':
            // نصف سنة (6 أشهر)
            const halfYearAgo = new Date(today);
            halfYearAgo.setMonth(today.getMonth() - 6);
            startDate = halfYearAgo.toISOString().split('T')[0];
            break;
        case 'year':
            // سنة كاملة (12 شهر)
            const yearAgo = new Date(today);
            yearAgo.setFullYear(today.getFullYear() - 1);
            startDate = yearAgo.toISOString().split('T')[0];
            break;
    }
    
    document.getElementById('filter_start_date').value = startDate;
    document.getElementById('filter_end_date').value = endDate;
}

// Clear date filter
function clearDateFilter() {
    document.getElementById('filter_start_date').value = '';
    document.getElementById('filter_end_date').value = '';
}

// Apply date filter - reload all stats with date filters
function applyDateFilter() {
    const startDate = document.getElementById('filter_start_date').value;
    const endDate = document.getElementById('filter_end_date').value;
    
    console.log('Applying date filter:', startDate, 'to', endDate);
    
    // Show loading indicator
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>جاري التحميل...';
    btn.disabled = true;
    
    // Update all stats with date filters
    setTimeout(() => {
        updateWorkOrdersStats();
        updateExecutionStats();
        updateInProgressStats();
        updateExtractStats();
        updateCompletedStats();
        updateQuickStats();
        
        // Restore button
        btn.innerHTML = originalText;
        btn.disabled = false;
    }, 100);
}

// Function to update work orders statistics from the API
function updateWorkOrdersStats() {
    console.log('Fetching receipts stats from API...');
    
    // Get project from URL or default to 'riyadh'
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    
    // Get date filters
    const startDate = document.getElementById('filter_start_date')?.value || '';
    const endDate = document.getElementById('filter_end_date')?.value || '';
    
    // Build URL with parameters
    let url = `/api/work-orders/receipts?project=${project}`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    console.log('Receipts API URL:', url);
    
    // Call the receipts API
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.summary) {
            console.log('Received receipts stats:', data.summary);
            // Update the display with the summary data
            updateStatsDisplay(
                data.summary.total_orders || 0,
                data.summary.total_value || 0,
                data.summary.percentage || 0
            );
        } else {
            console.error('Failed to fetch receipts data:', data.message || 'No summary data');
            // Use fallback data
            const stats = getWorkOrdersStats();
            updateStatsDisplay(stats.count, stats.total, 0);
        }
    })
    .catch(error => {
        console.error('Error fetching receipts data:', error);
        // Use fallback data
        const stats = getWorkOrdersStats();
        updateStatsDisplay(stats.count, stats.total, 0);
    });
}

// Function to update the statistics display
function updateStatsDisplay(count, value, percentage) {
    const countElement = document.getElementById('workOrdersCount');
    const valueElement = document.getElementById('totalValue');
    const progressBar = document.getElementById('receiptsProgressBar');
    const progressText = document.getElementById('receiptsProgressText');
    
    if (countElement) {
        countElement.textContent = count.toLocaleString('en-US');
    }
    
    if (valueElement) {
        if (value >= 1000000) {
            valueElement.textContent = (value / 1000000).toFixed(1) + 'M';
        } else if (value >= 1000) {
            valueElement.textContent = (value / 1000).toFixed(1) + 'K';
        } else {
            valueElement.textContent = value.toLocaleString('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }
    }
    
    // Update progress bar if percentage is provided
    if (progressBar && percentage !== undefined) {
        progressBar.style.width = percentage + '%';
    }
    
    if (progressText && percentage !== undefined) {
        progressText.textContent = `معدل الاستلام ${percentage}%`;
    }
    
    console.log('Updated stats display - Count:', count, 'Value:', value, 'Percentage:', percentage);
}

// Function to fetch work orders data from API
function fetchWorkOrdersData() {
    // Try to get data from the current page context first
    try {
        // Check if there's Laravel data available
        if (typeof Laravel !== 'undefined' && Laravel.workOrdersData) {
            updateStatsDisplay(Laravel.workOrdersData.count, Laravel.workOrdersData.total_value);
        return;
        }
    } catch (e) {
        console.log('No Laravel data available');
    }
    
    // Fallback: Use sample data or make API call
    // For now, we'll use sample data based on the visible table
    const sampleData = calculateFromVisibleData();
    updateStatsDisplay(sampleData.count, sampleData.total, 0);
}

// Function to calculate data from visible elements
function calculateFromVisibleData() {
    // Try multiple selectors to find work order data
    let count = 0;
    let total = 0;
    
    // Method 1: Look for work order rows with specific class
    const workOrderRows = document.querySelectorAll('.work-order-row');
    if (workOrderRows.length > 0) {
        count = workOrderRows.length;
        workOrderRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length > 12) {
                const valueCell = cells[12];
                if (valueCell) {
                    const valueText = valueCell.textContent.trim();
                    console.log('Processing value text:', valueText);
                    
                    // More precise regex to extract numbers
                    const valueMatch = valueText.match(/([\d,]+\.?\d*)/);
                    if (valueMatch) {
                        const numericValue = parseFloat(valueMatch[1].replace(/,/g, ''));
                        if (!isNaN(numericValue) && numericValue > 0) {
                            total += numericValue;
                            console.log('Added value:', numericValue);
                        }
                    }
                }
            }
        });
        console.log('Method 1 - Count:', count, 'Total:', total);
        return { count: count, total: total };
    }
    
    // Method 2: Look for any table rows in tbody
    const tableRows = document.querySelectorAll('table tbody tr');
    if (tableRows.length > 0) {
        count = tableRows.length;
        tableRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length > 12) {
                const valueCell = cells[12];
                if (valueCell) {
                    const valueText = valueCell.textContent.trim();
                    const valueMatch = valueText.match(/[\d,]+\.?\d*/);
                    if (valueMatch) {
                        const numericValue = parseFloat(valueMatch[0].replace(/,/g, ''));
                        if (!isNaN(numericValue)) {
                            total += numericValue;
                        }
                    }
                }
            }
        });
        return { count: count, total: total };
    }
    
    // Method 3: Use empty data if nothing found
    console.log('No table data found, using empty data');
    return { count: 0, total: 0 };
}

// Add observer to watch for table changes
function observeTableChanges() {
    // Try multiple possible table containers
    const possibleTargets = ['#workOrdersTableBody', 'tbody', '#resultsTable tbody'];
    
    possibleTargets.forEach(selector => {
        const targetNode = document.querySelector(selector);
        if (targetNode) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList') {
                        // Table content changed, update stats
                        setTimeout(updateWorkOrdersStats, 100);
                    }
                });
            });
            
            observer.observe(targetNode, { 
                childList: true, 
                subtree: true 
            });
            console.log('Observing changes in:', selector);
        }
    });
}

// Force update stats with debugging
function forceUpdateStats() {
    console.log('Force updating stats...');
    
    // Try to get stats from current page
    const sampleData = calculateFromVisibleData();
    console.log('Calculated data:', sampleData);
    
    // Always show at least the sample data
    updateStatsDisplay(sampleData.count, sampleData.total, 0);
    
    // Also try the main update function
    updateWorkOrdersStats();
}

// Alternative method to calculate total from Laravel data if available
function calculateFromLaravelData() {
    // Check if we have access to Laravel data
    if (typeof workOrders !== 'undefined' && Array.isArray(workOrders)) {
        let total = 0;
        workOrders.forEach(order => {
            if (order.order_value_without_consultant) {
                total += parseFloat(order.order_value_without_consultant) || 0;
            }
        });
        return { count: workOrders.length, total: total };
    }
    return null;
}

// Enhanced calculation with multiple fallbacks
function getWorkOrdersStats() {
    console.log('Getting work orders stats...');
    
    // Method 1: Try Laravel data
    const laravelData = calculateFromLaravelData();
    if (laravelData && laravelData.count > 0) {
        console.log('Using Laravel data:', laravelData);
        return laravelData;
    }
    
    // Method 2: Try from visible table
    const visibleData = calculateFromVisibleData();
    if (visibleData && visibleData.count > 0) {
        console.log('Using visible table data:', visibleData);
        return visibleData;
    }
    
    // Method 3: Manual calculation from specific selectors
    const manualData = calculateManually();
    if (manualData && manualData.count > 0) {
        console.log('Using manual calculation:', manualData);
        return manualData;
    }
    
    // Fallback: Use empty data
    console.log('No data found, using empty fallback');
    return { count: 0, total: 0 };
}

// Manual calculation method
function calculateManually() {
    const rows = document.querySelectorAll('tr.work-order-row, tbody tr');
    let count = 0;
    let total = 0;
    
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        // Look for the value column (usually contains "ريال")
        for (let i = 0; i < cells.length; i++) {
            const cellText = cells[i].textContent.trim();
            if (cellText.includes('ريال') && cellText.match(/[\d,]+\.?\d*/)) {
                count++;
                const valueMatch = cellText.match(/([\d,]+\.?\d*)/);
                if (valueMatch) {
                    const numericValue = parseFloat(valueMatch[1].replace(/,/g, ''));
                    if (!isNaN(numericValue) && numericValue > 0) {
                        total += numericValue;
                        console.log('Manual calculation - added:', numericValue, 'from:', cellText);
                    }
                }
                break; // Found the value column, move to next row
            }
        }
    });
    
    return { count: count, total: total };
}

// Calculate execution statistics
// Calculate execution statistics from API
function calculateExecutionStats() {
    console.log('Fetching execution stats from API...');
    
    // Get project from URL or default to 'riyadh'
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    
    // Call the execution API
    return fetch(`/api/work-orders/execution?project=${project}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
        .then(response => response.json())
        .then(data => {
        if (data.success) {
            console.log('Received execution stats:', data.summary);
            return {
                executedCount: data.summary.total_orders || 0,
                totalExecutedValue: data.summary.total_executed_value || 0,
                executionRate: data.summary.execution_percentage || 0,
                totalOrders: data.summary.total_orders || 0
            };
        } else {
            console.error('Failed to fetch execution data:', data.message);
            return {
                executedCount: 0,
                totalExecutedValue: 0,
                executionRate: 0,
                totalOrders: 0
            };
        }
    })
    .catch(error => {
        console.error('Error fetching execution data:', error);
        return {
            executedCount: 0,
            totalExecutedValue: 0,
            executionRate: 0,
            totalOrders: 0
        };
    });
}

// Update execution statistics display
function updateExecutionStats() {
    console.log('Fetching execution stats with status counts from API...');
    
    // Get project from URL or default to 'riyadh'
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    
    // Get date filters
    const startDate = document.getElementById('filter_start_date')?.value || '';
    const endDate = document.getElementById('filter_end_date')?.value || '';
    
    // Build URL with parameters
    let url = `/api/work-orders/execution?project=${project}`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    console.log('Execution API URL:', url);
    
    // Call the execution API to get full data including status counts
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.summary) {
            console.log('Received execution stats with status counts:', data.summary);
            const stats = data.summary;
            
            // Update main stats
            const countElement = document.getElementById('executedOrdersCount');
            const valueElement = document.getElementById('totalExecutedValue');
            const progressBar = document.getElementById('executionProgressBar');
            const progressText = document.getElementById('executionProgressText');
            
            if (countElement) {
                countElement.textContent = (stats.total_orders || 0).toLocaleString('en-US');
            }
            
            if (valueElement) {
                const value = stats.total_executed_value || 0;
                if (value >= 1000000) {
                    valueElement.textContent = (value / 1000000).toFixed(1) + 'M';
                } else if (value >= 1000) {
                    valueElement.textContent = (value / 1000).toFixed(1) + 'K';
                } else {
                    valueElement.textContent = value.toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                }
            }
            
            if (progressBar) {
                progressBar.style.width = (stats.execution_percentage || 0) + '%';
            }
            
            if (progressText) {
                progressText.textContent = `معدل الإنجاز ${stats.execution_percentage || 0}%`;
            }
            
            // Update status counts
            if (stats.status_counts) {
                const statusCounts = stats.status_counts;
                
                const status2Element = document.getElementById('status2Count');
                const status3Element = document.getElementById('status3Count');
                const status4Element = document.getElementById('status4Count');
                const status5Element = document.getElementById('status5Count');
                const status6Element = document.getElementById('status6Count');
                const status8Element = document.getElementById('status8Count');
                const status10Element = document.getElementById('status10Count');
                
                if (status2Element) status2Element.textContent = statusCounts.status_2 || 0;
                if (status3Element) status3Element.textContent = statusCounts.status_3 || 0;
                if (status4Element) status4Element.textContent = statusCounts.status_4 || 0;
                if (status5Element) status5Element.textContent = statusCounts.status_5 || 0;
                if (status6Element) status6Element.textContent = statusCounts.status_6 || 0;
                if (status8Element) status8Element.textContent = statusCounts.status_8 || 0;
                if (status10Element) status10Element.textContent = statusCounts.status_10 || 0;
            }
        } else {
            console.error('Failed to fetch execution data:', data.message);
        }
    })
    .catch(error => {
        console.error('Error fetching execution data:', error);
    });
}

// Calculate in-progress work orders statistics from API
function calculateInProgressStats() {
    console.log('Fetching in-progress stats from API...');
    
    // Get project from URL or default to 'riyadh'
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    
    // Get date filters
    const startDate = document.getElementById('filter_start_date')?.value || '';
    const endDate = document.getElementById('filter_end_date')?.value || '';
    
    // Build URL with parameters
    let url = `/api/work-orders/inprogress?project=${project}`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    console.log('InProgress API URL:', url);
    
    // Call the in-progress API
    return fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
        .then(response => response.json())
        .then(data => {
        if (data.success) {
            console.log('Received in-progress stats:', data.summary);
            return {
                inProgressCount: data.summary.total_orders || 0,
                inProgressTotalValue: data.summary.total_value || 0,
                inProgressRate: data.summary.percentage || 0,
                totalOrders: data.summary.total_orders || 0
            };
            } else {
            console.error('Failed to fetch in-progress data:', data.message);
            return {
                inProgressCount: 0,
                inProgressTotalValue: 0,
                inProgressRate: 0,
                totalOrders: 0
            };
            }
        })
        .catch(error => {
        console.error('Error fetching in-progress data:', error);
        return {
            inProgressCount: 0,
            inProgressTotalValue: 0,
            inProgressRate: 0,
            totalOrders: 0
        };
    });
}

// Calculate extract statistics from API
function calculateExtractStats() {
    console.log('Fetching extract stats from API...');
    
    // Get project from URL or default to 'riyadh'
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    
    // Get date filters
    const startDate = document.getElementById('filter_start_date')?.value || '';
    const endDate = document.getElementById('filter_end_date')?.value || '';
    
    // Build URL with parameters
    let url = `/api/work-orders/extracts?project=${project}`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    console.log('Extracts API URL:', url);
    
    // Call the extracts API
    return fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Received extract stats:', data.summary);
            return {
                extractsValue: data.summary.total_extract_value || 0,
                extractOrdersCount: data.summary.total_orders || 0,
                extractPercentage: data.summary.extract_percentage || 0
            };
        } else {
            console.error('Failed to fetch extract data:', data.message);
            return {
                extractsValue: 0,
                extractOrdersCount: 0,
                extractPercentage: 0
            };
        }
    })
    .catch(error => {
        console.error('Error fetching extract data:', error);
        return {
            extractsValue: 0,
            extractOrdersCount: 0,
            extractPercentage: 0
        };
    });
}

// Calculate completed (منتهي تم الصرف) statistics from API
function calculateCompletedStats() {
    console.log('Fetching completed stats from API...');
    
    // Get project from URL or default to 'riyadh'
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    
    // Get date filters
    const startDate = document.getElementById('filter_start_date')?.value || '';
    const endDate = document.getElementById('filter_end_date')?.value || '';
    
    // Build URL with parameters
    let url = `/api/work-orders/completed?project=${project}`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    console.log('Completed API URL:', url);
    
    // Call the completed orders API
    return fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Received completed stats:', data.summary);
            return {
                completedCount: data.summary.total_orders || 0,
                completedTotalValue: data.summary.total_final_value || 0,
                completionPercentage: data.summary.completion_percentage || 0,
                averageFinalValue: data.summary.average_final_value || 0
            };
        } else {
            console.error('Failed to fetch completed data:', data.message);
            return {
                completedCount: 0,
                completedTotalValue: 0,
                completionPercentage: 0,
                averageFinalValue: 0
            };
        }
    })
    .catch(error => {
        console.error('Error fetching completed data:', error);
        return {
            completedCount: 0,
            completedTotalValue: 0,
            completionPercentage: 0,
            averageFinalValue: 0
        };
    });
}

// Update in-progress statistics display
function updateInProgressStats() {
    calculateInProgressStats().then(stats => {
        const countElement = document.getElementById('inProgressOrdersCount');
        const valueElement = document.getElementById('inProgressTotalValue');
        const progressBar = document.getElementById('inProgressProgressBar');
        const progressText = document.getElementById('inProgressProgressText');
        
        if (countElement) {
            countElement.textContent = stats.inProgressCount.toLocaleString('en-US');
        }
        
        if (valueElement) {
            if (stats.inProgressTotalValue >= 1000000) {
                valueElement.textContent = (stats.inProgressTotalValue / 1000000).toFixed(1) + 'M';
            } else if (stats.inProgressTotalValue >= 1000) {
                valueElement.textContent = (stats.inProgressTotalValue / 1000).toFixed(1) + 'K';
            } else {
                valueElement.textContent = stats.inProgressTotalValue.toLocaleString('en-US', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }
        }
        
        if (progressBar) {
            progressBar.style.width = stats.inProgressRate + '%';
        }
        
        if (progressText) {
            progressText.textContent = `نسبة أوامر جاري العمل ${stats.inProgressRate}%`;
        }
    });
}

// Update extract statistics display
function updateExtractStats() {
    calculateExtractStats().then(stats => {
        const extractsValueElement = document.getElementById('extractsValue');
        const extractOrdersCountElement = document.getElementById('extractOrdersCount');
        const progressBar = document.getElementById('extractProgressBar');
        const progressText = document.getElementById('extractProgressText');
        
        if (extractsValueElement) {
            // Format the value with K/M notation
            const value = stats.extractsValue;
            if (value >= 1000000) {
                extractsValueElement.textContent = (value / 1000000).toFixed(1) + 'M';
            } else if (value >= 1000) {
                extractsValueElement.textContent = (value / 1000).toFixed(1) + 'K';
            } else {
                extractsValueElement.textContent = value.toLocaleString('en-US');
            }
        }
        
        if (extractOrdersCountElement) {
            extractOrdersCountElement.textContent = stats.extractOrdersCount.toLocaleString('en-US');
        }
        
        if (progressBar) {
            progressBar.style.width = stats.extractPercentage + '%';
        }
        
        if (progressText) {
            progressText.textContent = `نسبة المستخلص ${stats.extractPercentage}%`;
        }
    }).catch(error => {
        console.error('Error updating extract stats:', error);
        // Set default values on error
        const extractsValueElement = document.getElementById('extractsValue');
        const extractOrdersCountElement = document.getElementById('extractOrdersCount');
        
        if (extractsValueElement) {
            extractsValueElement.textContent = '0 ر.س';
        }
        
        if (extractOrdersCountElement) {
            extractOrdersCountElement.textContent = '0';
        }
    });
}

// Update completed statistics display
function updateCompletedStats() {
    calculateCompletedStats().then(stats => {
        const completedCountElement = document.getElementById('completedOrdersCount');
        const completedValueElement = document.getElementById('completedTotalValue');
        const progressBar = document.getElementById('completedProgressBar');
        const progressText = document.getElementById('completedProgressText');
        
        if (completedCountElement) {
            completedCountElement.textContent = stats.completedCount.toLocaleString('en-US');
        }
        
        if (completedValueElement) {
            // Format the value with K/M notation
            const value = stats.completedTotalValue;
            if (value >= 1000000) {
                completedValueElement.textContent = (value / 1000000).toFixed(1) + 'M';
            } else if (value >= 1000) {
                completedValueElement.textContent = (value / 1000).toFixed(1) + 'K';
            } else {
                completedValueElement.textContent = value.toLocaleString('en-US');
            }
        }
        
        if (progressBar) {
            progressBar.style.width = stats.completionPercentage + '%';
        }
        
        if (progressText) {
            progressText.textContent = `نسبة الإنجاز الكامل ${stats.completionPercentage}%`;
        }
    }).catch(error => {
        console.error('Error updating completed stats:', error);
        // Set default values on error
        const completedCountElement = document.getElementById('completedOrdersCount');
        const completedValueElement = document.getElementById('completedTotalValue');
        
        if (completedCountElement) {
            completedCountElement.textContent = '0';
        }
        
        if (completedValueElement) {
            completedValueElement.textContent = '0 ر.س';
        }
    });
}

function navigateToSection(section) {
    // Show loading state
    const btn = event.target.closest('.dashboard-btn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري التحميل...';
    btn.disabled = true;
    
    // Get current project from URL or default
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    
    // Simulate navigation delay
    setTimeout(() => {
        switch(section) {
            case 'receipts':
                window.location.href = `/admin/bi-dashboard/${project}/receipts`;
                break;
            case 'execution':
                window.location.href = `/admin/bi-dashboard/${project}/execution`;
                break;
            case 'inprogress':
                window.location.href = `/admin/bi-dashboard/${project}/inprogress`;
                break;
            case 'extracts':
                window.location.href = `/admin/bi-dashboard/${project}/extracts`;
                break;
            case 'completed':
                window.location.href = `/admin/bi-dashboard/${project}/completed`;
                break;
            case 'analytics':
                window.location.href = '/admin/bi-dashboard/analytics';
                break;
            case 'operations':
                window.location.href = '/admin/bi-dashboard/operations';
                break;
            default:
                console.log('Unknown section:', section);
                btn.innerHTML = originalText;
                btn.disabled = false;
        }
    }, 1000);
}

// Add some animation to progress bars
function animateProgressBars() {
    document.querySelectorAll('.progress-bar').forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
}

// Call animation on page load
setTimeout(animateProgressBars, 300);
</script>
@endpush

@endsection 
