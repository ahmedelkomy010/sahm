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
    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card text-center">
                <div class="stat-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($totalWorkOrders) }}</h3>
                <p class="mb-0 opacity-90">أمر عمل منفذ</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card text-center">
                <div class="stat-icon">
                    <i class="fas fa-coins"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($totalExecutedValue ?? 0, 2) }}</h3>
                <p class="mb-0 opacity-90">إجمالي القيمة المنفذة</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card text-center">
                <div class="stat-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($totalWorkItems, 2) }}</h3>
                <p class="mb-0 opacity-90">إجمالي بنود العمل المنفذة</p>
            </div>
        </div>
    </div>

    <!-- فلاتر البحث -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.work-orders.execution-productivity') }}">
            <input type="hidden" name="project" value="{{ $project }}">
            
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="date_from" class="form-label fw-semibold">
                        <i class="fas fa-calendar-alt me-1 text-primary"></i>
                        من تاريخ
                    </label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                
                <div class="col-md-3">
                    <label for="date_to" class="form-label fw-semibold">
                        <i class="fas fa-calendar-alt me-1 text-primary"></i>
                        إلى تاريخ
                    </label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
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
                        <th width="5%">#</th>
                        <th width="10%">رقم أمر العمل</th>
                        <th width="8%">رقم البند</th>
                        <th width="25%">وصف البند</th>
                        <th width="8%">سعر الوحدة</th>
                        <th width="8%">الكمية المنفذة</th>
                        <th width="10%">تاريخ التنفيذ</th>
                        <th width="12%">السعر الإجمالي المنفذ</th>
                        <th width="9%">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($workOrderItems as $workOrderItem)
                        <tr>
                            <td class="text-center fw-semibold">
                                <span class="badge bg-light text-dark">{{ $loop->iteration }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $workOrderItem->workOrder->order_number }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info text-white">
                                    {{ $workOrderItem->workItem->code ?? $workOrderItem->work_item_id }}
                                </span>
                            </td>
                            <td>
                                <div class="text-wrap" style="max-width: 300px;">
                                    {{ $workOrderItem->workItem->description ?? 'وصف غير متوفر' }}
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">
                                    {{ number_format($workOrderItem->unit_price ?? 0, 2) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">
                                    {{ number_format($workOrderItem->executed_quantity, 2) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <small class="text-muted">
                                    {{ $workOrderItem->work_date ? $workOrderItem->work_date->format('Y-m-d') : 'غير محدد' }}
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark fw-bold">
                                    {{ number_format(($workOrderItem->executed_quantity * $workOrderItem->unit_price), 2) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.work-orders.show', $workOrderItem->workOrder) }}" class="btn btn-outline-primary" title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.work-orders.execution', $workOrderItem->workOrder) }}" class="btn btn-outline-success" title="عرض بنود العمل">
                                        <i class="fas fa-tasks"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">لا توجد بنود عمل منفذة</h5>
                                    <p class="text-muted">لم يتم العثور على أوامر عمل تحتوي على بنود عمل منفذة تطابق معايير البحث</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($workOrderItems->hasPages())
        <div class="d-flex justify-content-center py-3">
            {{ $workOrderItems->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // تفعيل tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endpush
