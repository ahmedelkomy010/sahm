@extends('layouts.app')

@section('title', 'انتاجية التنفيذ - ' . $projectName)

@push('styles')
<style>
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        color: white;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .stats-card .stat-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    
    .productivity-table {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    
    .table thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        font-weight: 600;
        text-align: center;
        padding: 1rem 0.75rem;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9ff;
        transform: scale(1.01);
        transition: all 0.2s ease;
    }
    
    .filter-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    
    .btn-filter {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        padding: 0.75rem 2rem;
        transition: all 0.3s ease;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .badge-status {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 0 0 30px 30px;
    }
    
    .quick-date-btn {
        transition: all 0.3s ease;
        border-radius: 25px;
        font-weight: 500;
        padding: 0.5rem 1rem;
    }
    
    .quick-date-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .quick-date-btn.active {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    
    .quick-date-btn.active:hover {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-0">
                    <i class="fas fa-chart-line me-3"></i>
                    انتاجية التنفيذ
                </h1>
                <p class="lead mb-0 mt-2 opacity-90">{{ $projectName }}</p>
            </div>
            <div class="col-lg-4 text-end">
                <a href="{{ route('admin.work-orders.index', ['project' => $project]) }}" class="btn btn-success btn-lg">
                    <i class="fas fa-arrow-right me-2"></i>
                    العودة لأوامر العمل
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- رسالة تفسيرية -->
    <div class="alert alert-info shadow-sm mb-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-info-circle fa-2x me-3 text-info"></i>
            <div>
                <h6 class="mb-1">
                    <strong>سجل التنفيذ اليومي</strong>
                </h6>
                <p class="mb-0">
                    هذه الصفحة تعرض سجل التنفيذ اليومي التفصيلي لجميع بنود العمل. كل سجل يمثل تنفيذ بند واحد في يوم محدد مع إمكانية تتبع المنفذ والملاحظات.
                </p>
            </div>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card text-center">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($uniqueWorkOrdersWithExecution ?? 0) }}</h3>
                <p class="mb-0 opacity-90">أمر عمل منفذ</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card text-center">
                <div class="stat-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($totalDailyExecutions ?? 0) }}</h3>
                <p class="mb-0 opacity-90">سجل تنفيذ يومي</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card text-center">
                <div class="stat-icon">
                    <i class="fas fa-coins"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($totalExecutedValue ?? 0, 2) }} ر.س</h3>
                <p class="mb-0 opacity-90">إجمالي القيمة المنفذة</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card text-center">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                @php
                    $avgDailyValue = $totalDailyExecutions > 0 ? $totalExecutedValue / $totalDailyExecutions : 0;
                @endphp
                <h3 class="fw-bold mb-1">{{ number_format($avgDailyValue, 2) }} ر.س</h3>
                <p class="mb-0 opacity-90">متوسط القيمة اليومية</p>
            </div>
        </div>
    </div>

    <!-- فلاتر البحث -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.work-orders.execution-productivity') }}" id="filterForm">
            <input type="hidden" name="project" value="{{ $project }}">
            
            <!-- أزرار الفترات الزمنية السريعة -->
            <div class="row mb-4">
                <div class="col-12">
                    <label class="form-label fw-semibold mb-3">
                        <i class="fas fa-clock me-1 text-primary"></i>
                        فترات زمنية سريعة
                    </label>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm quick-date-btn" data-period="today">
                            <i class="fas fa-calendar-day me-1"></i>
                            اليوم
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm quick-date-btn" data-period="week">
                            <i class="fas fa-calendar-week me-1"></i>
                            الأسبوع
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm quick-date-btn" data-period="month">
                            <i class="fas fa-calendar-alt me-1"></i>
                            الشهر
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm quick-date-btn" data-period="half-year">
                            <i class="fas fa-calendar me-1"></i>
                            نصف سنوي
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm quick-date-btn" data-period="year">
                            <i class="fas fa-calendar-check me-1"></i>
                            سنوي
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="clearDates">
                            <i class="fas fa-times me-1"></i>
                            مسح التواريخ
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="date_from" class="form-label fw-semibold">
                        <i class="fas fa-calendar-alt me-1 text-primary"></i>
                        من تاريخ التنفيذ
                    </label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}" placeholder="تاريخ التنفيذ من">
                </div>
                
                <div class="col-md-3">
                    <label for="date_to" class="form-label fw-semibold">
                        <i class="fas fa-calendar-alt me-1 text-primary"></i>
                        إلى تاريخ التنفيذ
                    </label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}" placeholder="تاريخ التنفيذ إلى">
                </div>
                
                <div class="col-md-3">
                    <label for="order_number" class="form-label fw-semibold">
                        <i class="fas fa-file-alt me-1 text-primary"></i>
                        رقم أمر العمل
                    </label>
                    <input type="text" class="form-control" id="order_number" name="order_number" 
                           value="{{ request('order_number') }}" placeholder="ابحث برقم أمر العمل...">
                </div>
                
                <div class="col-md-3">
                    <label for="work_item_code" class="form-label fw-semibold">
                        <i class="fas fa-hashtag me-1 text-primary"></i>
                        رقم البند
                    </label>
                    <input type="text" class="form-control" id="work_item_code" name="work_item_code" 
                           value="{{ request('work_item_code') }}" placeholder="ابحث برقم البند...">
                </div>
            </div>
            
            <div class="row g-3 mt-2">
                <div class="col-md-4">
                    <label for="executed_by" class="form-label fw-semibold">
                        <i class="fas fa-user me-1 text-primary"></i>
                        المنفذ بواسطة
                    </label>
                    <input type="text" class="form-control" id="executed_by" name="executed_by" 
                           value="{{ request('executed_by') }}" placeholder="اسم المستخدم المنفذ...">
                </div>
                
                <div class="col-md-4">
                    <label for="min_quantity" class="form-label fw-semibold">
                        <i class="fas fa-sort-amount-up me-1 text-primary"></i>
                        الحد الأدنى للكمية
                    </label>
                    <input type="number" class="form-control" id="min_quantity" name="min_quantity" 
                           value="{{ request('min_quantity') }}" placeholder="0.00" step="0.01" min="0">
                </div>
                
                <div class="col-md-4">
                    <label for="max_quantity" class="form-label fw-semibold">
                        <i class="fas fa-sort-amount-down me-1 text-primary"></i>
                        الحد الأقصى للكمية
                    </label>
                    <input type="number" class="form-control" id="max_quantity" name="max_quantity" 
                           value="{{ request('max_quantity') }}" placeholder="9999.99" step="0.01" min="0">
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-filter me-2">
                        <i class="fas fa-search me-2"></i>
                        بحث وفلترة
                    </button>
                    <a href="{{ route('admin.work-orders.execution-productivity', ['project' => $project]) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-2"></i>
                        إعادة تعيين
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- جدول النتائج -->
    <div class="productivity-table">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="4%">#</th>
                        <th width="9%">رقم أمر العمل</th>
                        <th width="7%">رقم البند</th>
                        <th width="20%">وصف البند</th>
                        <th width="7%">سعر الوحدة</th>
                        <th width="8%">الكمية المنفذة</th>
                        <th width="9%">تاريخ التنفيذ</th>
                        <th width="10%">السعر الإجمالي</th>
                        <th width="8%">المنفذ بواسطة</th>
                        <th width="10%">ملاحظات</th>
                        <th width="8%">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dailyExecutions as $execution)
                        <tr>
                            <td class="text-center fw-semibold">
                                <span class="badge bg-light text-dark">{{ $loop->iteration }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $execution->workOrder->order_number }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info text-white">
                                    {{ $execution->workOrderItem->workItem->code ?? $execution->workOrderItem->work_item_id }}
                                </span>
                            </td>
                            <td>
                                <div class="text-wrap" style="max-width: 250px;">
                                    <strong>{{ $execution->workOrderItem->workItem->name ?? 'اسم غير متوفر' }}</strong>
                                    @if($execution->workOrderItem->workItem->description)
                                        <br><small class="text-muted">{{ Str::limit($execution->workOrderItem->workItem->description, 80) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">
                                    {{ number_format($execution->workOrderItem->unit_price ?? 0, 2) }} ر.س
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">
                                    {{ number_format($execution->executed_quantity, 2) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark">
                                    {{ $execution->work_date->format('Y-m-d') }}
                                </span>
                                <br>
                                <small class="text-muted">
                                    {{ $execution->work_date->format('l') }}
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger text-white fw-bold">
                                    {{ number_format(($execution->executed_quantity * $execution->workOrderItem->unit_price), 2) }} ر.س
                                </span>
                            </td>
                            <td class="text-center">
                                @if($execution->createdBy)
                                    <span class="badge bg-info">
                                        {{ $execution->createdBy->name }}
                                    </span>
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($execution->notes)
                                    <span class="badge bg-secondary" title="{{ $execution->notes }}">
                                        <i class="fas fa-sticky-note"></i>
                                        {{ Str::limit($execution->notes, 20) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.work-orders.show', $execution->workOrder) }}" class="btn btn-outline-primary" title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.work-orders.execution', $execution->workOrder) }}" class="btn btn-outline-success" title="صفحة التنفيذ">
                                        <i class="fas fa-tasks"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">لا توجد سجلات تنفيذ يومية</h5>
                                    <p class="text-muted">لم يتم العثور على سجلات تنفيذ يومية تطابق معايير البحث المحددة</p>
                                    <small class="text-muted">تأكد من وجود تنفيذ يومي في الفترة المحددة</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($dailyExecutions->hasPages())
        <div class="d-flex justify-content-center py-3">
            {{ $dailyExecutions->withQueryString()->links() }}
        </div>
        @endif
    </div>

    <!-- إحصائيات تفصيلية -->
    @if($dailyExecutions->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        إحصائيات تفصيلية للنتائج المعروضة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ $dailyExecutions->count() }}</h4>
                                <small class="text-muted">سجل في هذه الصفحة</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                @php
                                    $currentPageTotal = $dailyExecutions->sum(function($execution) {
                                        return $execution->executed_quantity * $execution->workOrderItem->unit_price;
                                    });
                                @endphp
                                <h4 class="text-success mb-1">{{ number_format($currentPageTotal, 2) }} ر.س</h4>
                                <small class="text-muted">قيمة هذه الصفحة</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                @php
                                    $avgQuantity = $dailyExecutions->avg('executed_quantity');
                                @endphp
                                <h4 class="text-info mb-1">{{ number_format($avgQuantity, 2) }}</h4>
                                <small class="text-muted">متوسط الكمية المنفذة</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            @php
                                $uniqueUsers = $dailyExecutions->whereNotNull('created_by')->groupBy('created_by')->count();
                            @endphp
                            <h4 class="text-warning mb-1">{{ $uniqueUsers }}</h4>
                            <small class="text-muted">مستخدم منفذ</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // تفعيل tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // إضافة تأثيرات hover للجدول
    document.querySelectorAll('.table tbody tr').forEach(function(row) {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9ff';
            this.style.transform = 'scale(1.01)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
            this.style.transform = 'scale(1)';
        });
    });

    // إضافة تأثيرات للبطاقات الإحصائية
    document.querySelectorAll('.stats-card').forEach(function(card) {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 15px 35px rgba(0,0,0,0.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 10px 25px rgba(0,0,0,0.1)';
        });
    });

    // وظائف الفترات الزمنية السريعة
    document.addEventListener('DOMContentLoaded', function() {
        const dateFromInput = document.getElementById('date_from');
        const dateToInput = document.getElementById('date_to');
        const quickDateBtns = document.querySelectorAll('.quick-date-btn');
        const clearDatesBtn = document.getElementById('clearDates');
        const filterForm = document.getElementById('filterForm');

        // دالة لتنسيق التاريخ بصيغة YYYY-MM-DD
        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // دالة لحساب التواريخ حسب الفترة
        function calculateDateRange(period) {
            const today = new Date();
            let fromDate, toDate;

            switch(period) {
                case 'today':
                    fromDate = new Date(today);
                    toDate = new Date(today);
                    break;
                
                case 'week':
                    // من بداية الأسبوع (الأحد) إلى نهاية الأسبوع (السبت)
                    const dayOfWeek = today.getDay();
                    fromDate = new Date(today);
                    fromDate.setDate(today.getDate() - dayOfWeek);
                    toDate = new Date(fromDate);
                    toDate.setDate(fromDate.getDate() + 6);
                    break;
                
                case 'month':
                    // من بداية الشهر إلى نهاية الشهر
                    fromDate = new Date(today.getFullYear(), today.getMonth(), 1);
                    toDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                    break;
                
                case 'half-year':
                    // آخر 6 شهور
                    fromDate = new Date(today);
                    fromDate.setMonth(today.getMonth() - 6);
                    toDate = new Date(today);
                    break;
                
                case 'year':
                    // من بداية السنة إلى نهاية السنة
                    fromDate = new Date(today.getFullYear(), 0, 1);
                    toDate = new Date(today.getFullYear(), 11, 31);
                    break;
                
                default:
                    return null;
            }

            return {
                from: formatDate(fromDate),
                to: formatDate(toDate)
            };
        }

        // إضافة event listeners للأزرار
        quickDateBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const period = this.getAttribute('data-period');
                const dateRange = calculateDateRange(period);
                
                if (dateRange) {
                    dateFromInput.value = dateRange.from;
                    dateToInput.value = dateRange.to;
                    
                    // إزالة التحديد من جميع الأزرار وإضافة التحديد للزر الحالي
                    quickDateBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    // تقديم النموذج تلقائياً
                    filterForm.submit();
                }
            });
        });

        // زر مسح التواريخ
        clearDatesBtn.addEventListener('click', function() {
            dateFromInput.value = '';
            dateToInput.value = '';
            quickDateBtns.forEach(btn => btn.classList.remove('active'));
        });

        // تحديد الزر النشط بناءً على التواريخ المحددة حالياً
        const currentFromDate = dateFromInput.value;
        const currentToDate = dateToInput.value;
        
        if (currentFromDate && currentToDate) {
            quickDateBtns.forEach(btn => {
                const period = btn.getAttribute('data-period');
                const dateRange = calculateDateRange(period);
                
                if (dateRange && dateRange.from === currentFromDate && dateRange.to === currentToDate) {
                    btn.classList.add('active');
                }
            });
        }
    });
</script>
@endpush
