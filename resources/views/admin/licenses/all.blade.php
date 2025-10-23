@extends('layouts.app')

@section('title', 'جميع الرخص - ' . $projectName)

@push('styles')
<style>
    .table-responsive {
        max-height: 70vh;
        overflow-y: auto;
    }

    .table thead th {
        position: sticky;
        top: 0;
        background: #e8eef7;
        color: #495057;
        z-index: 10;
        font-size: 0.9rem;
        padding: 1rem;
        text-align: center;
        white-space: nowrap;
        border-bottom: 2px solid #d1dce8;
        font-weight: 600;
    }

    .table tbody td {
        vertical-align: middle;
        font-size: 0.95rem;
        padding: 1rem;
        text-align: center;
        border-bottom: 1px solid #e9ecef;
    }

    .table tbody tr:hover {
        background-color: #f8f9fc;
    }

    .stats-card {
        background: #f7f9fc;
        color: #495057;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid #e3e8ef;
    }

    .stats-card h5 {
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        color: #6c757d;
        font-weight: 500;
    }

    .stats-card h3 {
        font-size: 1.5rem;
        font-weight: bold;
        margin: 0;
        color: #2d3748;
    }

    .stats-card i {
        opacity: 0.6;
    }

    .filter-card {
        background: #fff;
        border-radius: 10px;
        padding: 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        margin-bottom: 1rem;
        border: 1px solid #e9ecef;
    }

    .badge-license {
        font-size: 0.8rem;
        padding: 0.4rem 0.75rem;
        font-weight: 500;
    }
    
    .table tbody tr {
        transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="card shadow-sm mb-4" style="border: 1px solid #e3e8ef;">
        <div class="card-header" style="background: #f8f9fc; border-bottom: 2px solid #e3e8ef;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1" style="color: #2d3748;">
                        <i class="fas fa-certificate me-2" style="color: #6c757d;"></i>
                        جميع الرخص - {{ $projectName }}
                    </h4>
                    <p class="mb-0" style="font-size: 0.9rem; color: #6c757d;">
                        عرض وإدارة جميع رخص أوامر العمل
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.work-orders.productivity.' . $project) }}" class="btn btn-sm" style="background: #e8eef7; color: #495057; border: 1px solid #d1dce8;">
                        <i class="fas fa-arrow-right me-1"></i>
                        عودة للوحة التحكم
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card">
                <h5><i class="fas fa-list me-2"></i>إجمالي عدد الرخص</h5>
                <h3>{{ number_format($stats['total_licenses']) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <h5><i class="fas fa-file-alt me-2"></i>عدد أوامر العمل</h5>
                <h3>{{ number_format($stats['total_work_orders']) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <h5><i class="fas fa-money-bill-wave me-2"></i>إجمالي قيمة الرخص</h5>
                <h3>{{ number_format($stats['total_value'], 2) }} ر.س</h3>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.licenses.all.' . $project) }}" class="row g-3">
            <!-- فترات زمنية سريعة -->
            <div class="col-12">
                <label class="form-label" style="color: #495057; font-weight: 500;">
                    <i class="fas fa-clock me-1" style="color: #6c757d;"></i>
                    فترات زمنية سريعة
                </label>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-sm" onclick="setQuickDateRange('today')" 
                            style="background: #e8eef7; color: #495057; border: 1px solid #d1dce8; padding: 0.4rem 0.8rem;">
                        <i class="fas fa-calendar-day me-1"></i>
                        اليوم
                    </button>
                    <button type="button" class="btn btn-sm" onclick="setQuickDateRange('week')" 
                            style="background: #e8eef7; color: #495057; border: 1px solid #d1dce8; padding: 0.4rem 0.8rem;">
                        <i class="fas fa-calendar-week me-1"></i>
                        أسبوع
                    </button>
                    <button type="button" class="btn btn-sm" onclick="setQuickDateRange('month')" 
                            style="background: #e8eef7; color: #495057; border: 1px solid #d1dce8; padding: 0.4rem 0.8rem;">
                        <i class="fas fa-calendar-alt me-1"></i>
                        شهر
                    </button>
                    <button type="button" class="btn btn-sm" onclick="setQuickDateRange('quarter')" 
                            style="background: #e8eef7; color: #495057; border: 1px solid #d1dce8; padding: 0.4rem 0.8rem;">
                        <i class="fas fa-calendar me-1"></i>
                        ربع سنة
                    </button>
                    <button type="button" class="btn btn-sm" onclick="setQuickDateRange('half')" 
                            style="background: #e8eef7; color: #495057; border: 1px solid #d1dce8; padding: 0.4rem 0.8rem;">
                        <i class="fas fa-calendar-check me-1"></i>
                        نصف سنة
                    </button>
                    <button type="button" class="btn btn-sm" onclick="setQuickDateRange('year')" 
                            style="background: #e8eef7; color: #495057; border: 1px solid #d1dce8; padding: 0.4rem 0.8rem;">
                        <i class="fas fa-calendar-plus me-1"></i>
                        سنة
                    </button>
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label" style="color: #495057; font-weight: 500;">
                    <i class="fas fa-search me-1" style="color: #6c757d;"></i>
                    بحث (رقم الرخصة أو رقم الأمر)
                </label>
                <input type="text" name="search" class="form-control" placeholder="ابحث..." value="{{ request('search') }}" 
                       style="border: 1px solid #d1dce8; padding: 0.6rem;">
            </div>
            <div class="col-md-2">
                <label class="form-label" style="color: #495057; font-weight: 500;">
                    <i class="fas fa-calendar-alt me-1" style="color: #6c757d;"></i>
                    تاريخ البداية
                </label>
                <input type="date" name="start_date" id="filter_start_date" class="form-control" value="{{ request('start_date') }}" 
                       style="border: 1px solid #d1dce8; padding: 0.6rem;">
            </div>
            <div class="col-md-2">
                <label class="form-label" style="color: #495057; font-weight: 500;">
                    <i class="fas fa-calendar-check me-1" style="color: #6c757d;"></i>
                    تاريخ النهاية
                </label>
                <input type="date" name="end_date" id="filter_end_date" class="form-control" value="{{ request('end_date') }}" 
                       style="border: 1px solid #d1dce8; padding: 0.6rem;">
            </div>
            <div class="col-md-5 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-sm" style="background: #5a67d8; color: white; border: none; padding: 0.5rem 1rem;">
                    <i class="fas fa-filter me-1"></i>
                    تطبيق الفلتر
                </button>
                <a href="{{ route('admin.licenses.all.' . $project) }}" class="btn btn-sm" style="background: #e8eef7; color: #495057; border: 1px solid #d1dce8; padding: 0.5rem 1rem;">
                    <i class="fas fa-undo me-1"></i>
                    مسح
                </a>
                <a href="{{ route('admin.licenses.all.export', $project) }}?{{ http_build_query(request()->except(['page'])) }}" class="btn btn-sm" style="background: #10b981; color: white; border: none; padding: 0.5rem 1rem;">
                    <i class="fas fa-file-excel me-1"></i>
                    تصدير Excel
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card shadow-sm" style="border: 1px solid #e9ecef;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>رقم الرخصة</th>
                            <th>رقم أمر العمل</th>
                            <th>نوع العمل</th>
                            <th>تاريخ الرخصة</th>
                            <th>قيمة الرخصة</th>
                            <th style="width: 120px;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($licenses as $index => $license)
                        <tr>
                            <td>
                                <span class="badge" style="background: #e8eef7; color: #495057; font-weight: 600;">
                                    {{ $licenses->firstItem() + $index }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-certificate me-2" style="color: #5a67d8;"></i>
                                    <strong style="color: #5a67d8; font-size: 1.05rem;">
                                        {{ $license->license_number ?? 'غير محدد' }}
                                    </strong>
                                </div>
                            </td>
                            <td>
                                @if($license->workOrder)
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-file-alt me-2" style="color: #6c757d;"></i>
                                        <span style="color: #4a5568; font-weight: 600; font-size: 1.05rem;">
                                            {{ $license->workOrder->order_number }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($license->workOrder)
                                    @php
                                        $workType = $license->workOrder->work_type ?? '';
                                        $bgColor = '#e8f5e9';
                                        $textColor = '#2e7d32';
                                        
                                        // تلوين حسب نوع العمل
                                        if(str_contains($workType, 'كهرباء') || str_contains($workType, 'كهربائ')) {
                                            $bgColor = '#fff3e0';
                                            $textColor = '#e65100';
                                        } elseif(str_contains($workType, 'مياه') || str_contains($workType, 'صرف')) {
                                            $bgColor = '#e1f5fe';
                                            $textColor = '#01579b';
                                        } elseif(str_contains($workType, 'اتصالات')) {
                                            $bgColor = '#f3e5f5';
                                            $textColor = '#6a1b9a';
                                        }
                                    @endphp
                                    <span class="badge badge-license" style="background: {{ $bgColor }}; color: {{ $textColor }};">
                                        <i class="fas fa-tag me-1"></i>
                                        {{ $workType ?: 'غير محدد' }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($license->license_start_date)
                                    <span style="color: #6c757d; font-size: 0.95rem;">
                                        <i class="fas fa-calendar-alt me-1" style="color: #4a5568;"></i>
                                        {{ \Carbon\Carbon::parse($license->license_start_date)->format('Y-m-d') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-money-bill-wave me-2" style="color: #10b981;"></i>
                                    <strong style="color: #10b981; font-size: 1.05rem;">
                                        {{ number_format($license->license_value ?? 0, 2) }} ر.س
                                    </strong>
                                </div>
                            </td>
                            <td>
                                @if($license->workOrder)
                                    <a href="{{ route('admin.work-orders.show', ['project' => $project, 'workOrder' => $license->workOrder->id]) }}" 
                                       class="btn btn-sm" target="_blank" title="عرض أمر العمل"
                                       style="background: #e8eef7; color: #495057; border: 1px solid #d1dce8;">
                                        <i class="fas fa-eye me-1"></i>
                                        عرض
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block" style="color: #cbd5e0;"></i>
                                    <h5 style="color: #6c757d;">لا توجد رخص</h5>
                                    <p style="color: #9ca3af;">لم يتم العثور على رخص لهذا المشروع</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($licenses->hasPages())
            <div class="card-footer" style="background: #f8f9fc; border-top: 1px solid #e9ecef;">
                <div class="d-flex justify-content-center">
                    {{ $licenses->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// دالة للفترات الزمنية السريعة
function setQuickDateRange(period) {
    const today = new Date();
    const endDate = today.toISOString().split('T')[0];
    let startDate;
    
    switch(period) {
        case 'today':
            startDate = endDate;
            break;
        case 'week':
            const weekAgo = new Date(today);
            weekAgo.setDate(today.getDate() - 7);
            startDate = weekAgo.toISOString().split('T')[0];
            break;
        case 'month':
            const monthAgo = new Date(today);
            monthAgo.setMonth(today.getMonth() - 1);
            startDate = monthAgo.toISOString().split('T')[0];
            break;
        case 'quarter':
            const quarterAgo = new Date(today);
            quarterAgo.setMonth(today.getMonth() - 3);
            startDate = quarterAgo.toISOString().split('T')[0];
            break;
        case 'half':
            const halfYearAgo = new Date(today);
            halfYearAgo.setMonth(today.getMonth() - 6);
            startDate = halfYearAgo.toISOString().split('T')[0];
            break;
        case 'year':
            const yearAgo = new Date(today);
            yearAgo.setFullYear(today.getFullYear() - 1);
            startDate = yearAgo.toISOString().split('T')[0];
            break;
    }
    
    document.getElementById('filter_start_date').value = startDate;
    document.getElementById('filter_end_date').value = endDate;
}
</script>
@endpush

@endsection

