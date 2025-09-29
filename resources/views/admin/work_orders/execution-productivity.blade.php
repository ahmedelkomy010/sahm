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
    
    /* تحسين عرض معلومات الصفحة */
    .pagination-info {
        background: rgba(102, 126, 234, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        border: 1px solid rgba(102, 126, 234, 0.2);
    }
    
    .pagination-links .pagination {
        margin-bottom: 0;
    }
    
    .pagination-links .page-link {
        border-radius: 8px;
        margin: 0 2px;
        border: none;
        color: #667eea;
        font-weight: 500;
    }
    
    .pagination-links .page-link:hover {
        background-color: #667eea;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
    }
    
    .pagination-links .page-item.active .page-link {
        background-color: #667eea;
        border-color: #667eea;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
    }
    
    /* تحسين مظهر اختيار عدد العناصر */
    #per_page {
        background: linear-gradient(145deg, #ffffff, #f8f9ff);
        border: 2px solid #e2e8f0;
        font-weight: 600;
        color: #495057;
    }
    
    #per_page:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background: white;
    }
    
    /* تحسين عرض معلومات العدد */
    .badge.fs-6 {
        font-size: 0.9rem !important;
        padding: 0.5rem 1rem;
        border-radius: 15px;
    }
    
    /* تنسيق عمود تاريخ التنفيذ */
    .execution-date-cell {
        min-width: 120px;
    }
    
    .execution-date-cell .badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
    }
    
    /* تحسين عرض الأيقونات في الجدول */
    .table .badge .fas {
        font-size: 0.8em;
        margin-right: 0.25rem;
    }
    
    /* تحسين مظهر صفوف الجدول */
    .productivity-table .table tbody tr:nth-child(even) {
        background-color: rgba(102, 126, 234, 0.02);
    }
    
    .productivity-table .table tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.08) !important;
        transform: scale(1.005);
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
    }
    
    /* تحسين عرض القيمة المنفذة */
    .table .badge.bg-success.fs-6 {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
    }
    
    .table .badge.bg-secondary.fs-6 {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%) !important;
        border: 1px solid rgba(255, 255, 255, 0.2);
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
        <div class="col-md-4">
            <div class="stats-card text-center">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($uniqueWorkOrdersWithExecution ?? 0) }}</h3>
                <p class="mb-0 opacity-90">أمر عمل منفذ</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card text-center">
                <div class="stat-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($totalDailyExecutions ?? 0) }}</h3>
                <p class="mb-0 opacity-90">سجل تنفيذ يومي</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card text-center">
                <div class="stat-icon">
                    <i class="fas fa-coins"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($totalExecutedValue ?? 0, 2) }} ر.س</h3>
                <p class="mb-0 opacity-90">إجمالي القيمة المنفذة</p>
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
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-clock me-1 text-primary"></i>
                        فترات زمنية سريعة
                    </label>
                    <p class="text-muted small mb-3">
                        <i class="fas fa-info-circle me-1"></i>
                        الفلترة تتم حسب تاريخ التنفيذ اليومي الفعلي للبنود
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm quick-date-btn" data-period="today">
                            <i class="fas fa-calendar-day me-1"></i>
                            اليوم
                        </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm quick-date-btn" data-period="yesterday">
                            <i class="fas fa-calendar-minus me-1"></i>
                            أمس
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
                        من تاريخ التنفيذ اليومي
                    </label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}" placeholder="تاريخ التنفيذ من">
                </div>
                
                <div class="col-md-3">
                    <label for="date_to" class="form-label fw-semibold">
                        <i class="fas fa-calendar-alt me-1 text-primary"></i>
                        إلى تاريخ التنفيذ اليومي
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
                        المنفذ\المدخل بواسطة
                    </label>
                    <input type="text" class="form-control" id="executed_by" name="executed_by" 
                           value="{{ request('executed_by') }}" placeholder="اسم المستخدم المنفذ...">
                </div>
                
            </div>
            
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-filter me-2">
                        <i class="fas fa-search me-2"></i>
                        بحث 
                    </button>
                    <a href="{{ route('admin.work-orders.execution-productivity', ['project' => $project]) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-2"></i>
                        إعادة تعيين
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- معلومات العرض -->
    @if($dailyExecutions->count() > 0)
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle text-primary me-2"></i>
                <span class="fw-semibold">
                    عرض {{ $dailyExecutions->firstItem() }} - {{ $dailyExecutions->lastItem() }} 
                    من أصل {{ number_format($dailyExecutions->total()) }} سجل تنفيذ
                </span>
            </div>
        </div>
        
        <div class="col-md-6 text-end">
            <div class="d-flex align-items-center justify-content-end">
                <i class="fas fa-list-ol text-success me-2"></i>
                <span class="badge bg-success fs-6" id="items-display-counter">
                    عدد العناصر المعروضة: {{ request('per_page', 20) }}
                </span>
            </div>
        </div>
        <div class="col-md-1">
                    
                    <select class="form-select" id="per_page" name="per_page">
                        <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20 </option>
                        <option value="70" {{ request('per_page', 20) == 70 ? 'selected' : '' }}>70 </option>
                        <option value="200" {{ request('per_page', 20) == 200 ? 'selected' : '' }}>200 </option>
                        <option value="300" {{ request('per_page', 20) == 300 ? 'selected' : '' }}>300 </option>
                        <option value="700" {{ request('per_page', 20) == 700 ? 'selected' : '' }}>700 </option>
                    </select>
                </div>
    </div>
    @endif

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
                        <th width="6%">الوحدة</th>
                        <th width="10%">السعر الإجمالي</th>
                        <th width="9%">تاريخ التنفيذ</th>
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
                                    @if($usingFallbackData ?? false)
                                        {{ $execution->workItem->code ?? $execution->work_item_id }}
                                    @else
                                        {{ $execution->workOrderItem?->workItem?->code ?? $execution->workOrderItem?->work_item_id ?? 'غير محدد' }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <div class="text-wrap" style="max-width: 250px;">
                                    @if($usingFallbackData ?? false)
                                        <strong>{{ $execution->workItem->name ?? 'اسم غير متوفر' }}</strong>
                                        @if($execution->workItem->description)
                                            <br><small class="text-muted">{{ Str::limit($execution->workItem->description, 80) }}</small>
                                        @endif
                                    @else
                                        <strong>{{ $execution->workOrderItem?->workItem?->name ?? 'اسم غير متوفر' }}</strong>
                                        @if($execution->workOrderItem?->workItem?->description)
                                            <br><small class="text-muted">{{ Str::limit($execution->workOrderItem->workItem->description, 80) }}</small>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">
                                    @if($usingFallbackData ?? false)
                                        {{ number_format($execution->unit_price ?? 0, 2) }} ر.س
                                    @else
                                        {{ number_format($execution->workOrderItem?->unit_price ?? 0, 2) }} ر.س
                                    @endif
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">
                                    {{ number_format($execution->executed_quantity, 2) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">
                                    @if($usingFallbackData ?? false)
                                        {{ $execution->workItem->unit ?? 'عدد' }}
                                    @else
                                        {{ $execution->workOrderItem?->unit ?? ($execution->workOrderItem?->workItem?->unit ?? 'عدد') }}
                                    @endif
                                </span>
                            </td>
                            <td class="text-center">
                                @php
                                    $executedValue = $usingFallbackData ?? false 
                                        ? ($execution->executed_quantity * $execution->unit_price)
                                        : ($execution->executed_quantity * ($execution->workOrderItem?->unit_price ?? 0));
                                @endphp
                                @if($executedValue > 0)
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-money-bill me-1"></i>
                                        {{ number_format($executedValue, 2) }} ريال
                                    </span>
                                @else
                                    <span class="badge bg-secondary fs-6">
                                        <i class="fas fa-money-bill me-1"></i>
                                        {{ number_format($executedValue, 2) }} ريال
                                    </span>
                                @endif
                            </td>
                            <td class="text-center execution-date-cell">
                                @if($usingFallbackData ?? false)
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="badge bg-warning text-dark mb-1">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $execution->updated_at->format('Y-m-d') }}
                                        </span>
                                        <small class="text-muted">آخر تحديث</small>
                                    </div>
                                @else
                                    @if($execution->work_date)
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge bg-info mb-1">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $execution->work_date->format('Y-m-d') }}
                                            </span>
                                            <small class="text-muted">
                                                {{ $execution->work_date->format('l') }}
                                            </small>
                                        </div>
                                    @else
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge bg-warning mb-1">
                                                <i class="fas fa-question me-1"></i>
                                                غير محدد
                                            </span>
                                            <small class="text-muted">لا يوجد تاريخ</small>
                                        </div>
                                    @endif
                                @endif
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
                                @php
                                    $workOrderNotes = $dailyNotes->get($execution->work_order_id, collect());
                                    $executionNotes = $execution->notes;
                                @endphp
                                
                                @if($executionNotes || $workOrderNotes->count() > 0)
                                    <div class="d-flex flex-column gap-1">
                                        {{-- ملاحظات التنفيذ الخاصة بهذا السجل --}}
                                        @if($executionNotes)
                                            <span class="badge bg-primary" title="ملاحظة التنفيذ: {{ $executionNotes }}">
                                                <i class="fas fa-sticky-note me-1"></i>
                                                {{ Str::limit($executionNotes, 15) }}
                                            </span>
                                        @endif
                                        
                                        {{-- ملاحظات التنفيذ اليومي لنفس أمر العمل --}}
                                        @if($workOrderNotes->count() > 0)
                                            @foreach($workOrderNotes->take(2) as $note)
                                                <span class="badge bg-info" title="ملاحظة يومية ({{ $note->execution_date->format('Y-m-d') }}): {{ $note->notes }}">
                                                    <i class="fas fa-calendar-day me-1"></i>
                                                    {{ Str::limit($note->notes, 12) }}
                                                </span>
                                            @endforeach
                                            
                                            @if($workOrderNotes->count() > 2)
                                                <span class="badge bg-secondary" title="يوجد {{ $workOrderNotes->count() - 2 }} ملاحظات إضافية">
                                                    +{{ $workOrderNotes->count() - 2 }}
                                                </span>
                                            @endif
                                        @endif
                                    </div>
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
                            <td colspan="12" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">لا توجد سجلات تنفيذ يومية</h5>
                                    <p class="text-muted">لم يتم العثور على سجلات تنفيذ يومية تطابق معايير البحث المحددة</p>
                                    <small class="text-muted">تأكد من وجود تنفيذ يومي في الفترة المحددة أو قم بإزالة الفلاتر</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($dailyExecutions->hasPages())
        <div class="d-flex justify-content-between align-items-center py-3 px-3">
            <div class="pagination-info">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    صفحة {{ $dailyExecutions->currentPage() }} من {{ $dailyExecutions->lastPage() }}
                    ({{ number_format($dailyExecutions->total()) }} سجل إجمالي)
                </small>
            </div>
            <div class="pagination-links">
                {{ $dailyExecutions->withQueryString()->links() }}
            </div>
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
                        <div class="col-md-4">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ $dailyExecutions->count() }}</h4>
                                <small class="text-muted">سجل في هذه الصفحة</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-end">
                                @php
                                    $currentPageTotal = $dailyExecutions->sum(function($execution) {
                                        if (isset($execution->workOrderItem) && $execution->workOrderItem) {
                                            // من daily_work_executions
                                            return $execution->executed_quantity * ($execution->workOrderItem->unit_price ?? 0);
                                        } else {
                                            // من work_order_items مباشرة
                                            return $execution->executed_quantity * ($execution->unit_price ?? 0);
                                        }
                                    });
                                @endphp
                                <h4 class="text-success mb-1">{{ number_format($currentPageTotal, 2) }} ر.س</h4>
                                <small class="text-muted">قيمة هذه الصفحة</small>
                            </div>
                        </div>
                        <div class="col-md-4">
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
                
                case 'yesterday':
                    fromDate = new Date(today);
                    fromDate.setDate(today.getDate() - 1);
                    toDate = new Date(fromDate);
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

    // تحديث الصفحة تلقائياً عند تغيير عدد العناصر المعروضة
    document.getElementById('per_page').addEventListener('change', function() {
        // تحديث العداد فوراً
        const counter = document.getElementById('items-display-counter');
        if (counter) {
            counter.innerHTML = 'عدد العناصر المعروضة: ' + this.value;
            counter.className = 'badge bg-info fs-6';
            
            // إضافة مؤشر التحميل
            setTimeout(() => {
                counter.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>جاري التحديث...';
                counter.className = 'badge bg-warning fs-6';
            }, 100);
        }
        
        const form = document.getElementById('filterForm');
        form.submit();
    });

    // تحديث العداد عند الضغط على زر البحث والفلترة
    document.getElementById('filterForm').addEventListener('submit', function() {
        const counter = document.getElementById('items-display-counter');
        if (counter) {
            counter.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>جاري البحث...';
            counter.className = 'badge bg-warning fs-6';
        }
    });

    // تحسين تأثيرات الجدول
    document.addEventListener('DOMContentLoaded', function() {
        // إضافة تأثير highlight للصفوف عند التمرير
        const tableRows = document.querySelectorAll('.productivity-table tbody tr');
        
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                // إضافة تأثير للأزرار
                const buttons = this.querySelectorAll('.btn');
                buttons.forEach(btn => {
                    btn.style.transform = 'scale(1.05)';
                    btn.style.transition = 'all 0.2s ease';
                });
            });
            
            row.addEventListener('mouseleave', function() {
                // إزالة التأثير
                const buttons = this.querySelectorAll('.btn');
                buttons.forEach(btn => {
                    btn.style.transform = 'scale(1)';
                });
            });
        });

        // تحسين عرض الـ badges
        const badges = document.querySelectorAll('.badge');
        badges.forEach(badge => {
            badge.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-1px)';
                this.style.transition = 'all 0.2s ease';
            });
            
            badge.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // إضافة tooltip للملاحظات الطويلة
        const notesBadges = document.querySelectorAll('[title]');
        notesBadges.forEach(badge => {
            if (badge.title && badge.title.length > 30) {
                badge.style.cursor = 'help';
            }
        });
    });
</script>
@endpush
