@extends('layouts.admin')

@section('title', 'تفاصيل عامة للمواد' . (request()->route('city') ? ' - ' . ucfirst(request()->route('city')) : ' - الرياض'))

@push('head')
<style>
    /* تنسيق عام */
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    /* تنسيق الهيدر */
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 15px;
    }
    
    /* تنسيق الفلاتر */
    .filters-card {
        background: linear-gradient(to right, #f8f9fa, #ffffff);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    /* تنسيق الرصيد النهائي */
    .final-balance-badge {
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .final-balance-badge:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    /* تنسيق أزرار فلتر الرصيد */
    .btn-group .btn-check:checked + .btn {
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }
    
    .btn-group .btn {
        transition: all 0.2s ease;
    }
    
    .btn-group .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }
    
    /* تنسيق الجدول */
    .table-responsive {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .table thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        padding: 15px 10px;
    }
    
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.1);
        transform: scale(1.01);
    }
    
    .table tbody td {
        vertical-align: middle;
        padding: 12px 10px;
        text-align: center;
    }
    
    /* تنسيق البادجز */
    .badge {
        font-size: 0.8rem;
        padding: 0.5rem 0.8rem;
        border-radius: 20px;
    }
    
    /* تنسيق الأزرار */
    .btn {
        border-radius: 20px;
        padding: 8px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    /* تنسيق الإحصائيات */
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        margin-bottom: 20px;
    }
    
    .stats-number {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .stats-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-home me-1"></i>الرئيسية
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.work-orders.index') }}">
                    <i class="fas fa-clipboard-list me-1"></i>أوامر العمل
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <i class="fas fa-boxes me-1"></i>تفاصيل عامة للمواد - الرياض
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="page-header text-center">
        <div class="row align-items-center">
            <div class="col-12">
                <h1 class="mb-2">
                    <i class="fas fa-boxes me-3"></i>
                    تفاصيل عامة للمواد{{ request()->route('city') ? ' - مشروع ' . ucfirst(request()->route('city')) : ' - مشروع الرياض' }}
                </h1>
                <p class="mb-0 fs-5">عرض شامل لجميع المواد المستخدمة في المشروع</p>
            </div>
            <div class="text-left">
                <a href="{{ route('admin.work-orders.index', ['project' => $project]) }}" class="btn btn-success btn-lg">
                    <i class="fas fa-arrow-right me-2"></i>
                    العودة لأوامر العمل
                </a>
            </div>
            
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ $materials->total() }}</div>
                <div class="stats-label">إجمالي المواد</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ $workOrders->count() }}</div>
                <div class="stats-label">أوامر العمل</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ $units->count() }}</div>
                <div class="stats-label">أنواع الوحدات</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                @php
                    $totalFinalBalance = 0;
                    foreach($materials as $material) {
                        $finalBalance = ($material->spent_quantity ?? 0) + ($material->recovery_quantity ?? 0) - ($material->executed_quantity ?? 0) - ($material->completion_quantity ?? 0);
                        $totalFinalBalance += $finalBalance;
                    }
                @endphp
                <div class="stats-number {{ $totalFinalBalance >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $totalFinalBalance >= 0 ? '+' : '' }}{{ number_format($totalFinalBalance, 2) }}
                </div>
                <div class="stats-label">إجمالي الرصيد النهائي</div>
            </div>
        </div>
    </div>

    <!-- الفلاتر -->
    <div class="card mb-4">
        <div class="card-body filters-card">
            <h5 class="mb-3">
                <i class="fas fa-filter me-2"></i>
                فلاتر البحث
            </h5>
            
            <form method="GET" action="{{ route('admin.materials.riyadh-overview') }}" class="row g-3">
                <!-- البحث بالكود -->
                <div class="col-md-3">
                    <label for="search_code" class="form-label fw-bold">
                        <i class="fas fa-barcode me-1"></i>كود المادة
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="search_code" 
                           name="search_code" 
                           value="{{ request('search_code') }}" 
                           placeholder="ابحث بالكود...">
                </div>

                <!-- البحث برقم أمر العمل -->
                <div class="col-md-3">
                    <label for="work_order_number" class="form-label fw-bold">
                        <i class="fas fa-file-contract me-1"></i>رقم أمر العمل
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="work_order_number" 
                           name="work_order_number" 
                           value="{{ request('work_order_number') }}" 
                           placeholder="ابحث برقم أمر العمل...">
                </div>

                <!-- فلتر الوحدة -->
                <div class="col-md-2">
                    <label for="unit_filter" class="form-label fw-bold">
                        <i class="fas fa-ruler me-1"></i>الوحدة
                    </label>
                    <select class="form-select" id="unit_filter" name="unit_filter">
                        <option value="">جميع الوحدات</option>
                        <option value="Ech" {{ request('unit_filter') == 'Ech' ? 'selected' : '' }}>
                            Ech
                        </option>
                        @foreach($units as $unit)
                            <option value="{{ $unit }}" {{ request('unit_filter') == $unit ? 'selected' : '' }}>
                                {{ $unit }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- فلتر أمر العمل -->
                <!-- فلتر الرصيد النهائي -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-balance-scale me-1"></i>فلتر الرصيد النهائي
                    </label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="balance_filter" value="" id="balance_all" 
                               {{ !request('balance_filter') ? 'checked' : '' }}>
                        <label class="btn btn-outline-secondary btn-sm" for="balance_all">
                            <i class="fas fa-list me-1"></i>الكل
                        </label>

                        <input type="radio" class="btn-check" name="balance_filter" value="positive" id="balance_positive" 
                               {{ request('balance_filter') == 'positive' ? 'checked' : '' }}>
                        <label class="btn btn-outline-success btn-sm" for="balance_positive">
                            <i class="fas fa-plus me-1"></i>موجب
                        </label>

                        <input type="radio" class="btn-check" name="balance_filter" value="negative" id="balance_negative" 
                               {{ request('balance_filter') == 'negative' ? 'checked' : '' }}>
                        <label class="btn btn-outline-danger btn-sm" for="balance_negative">
                            <i class="fas fa-minus me-1"></i>سالب
                        </label>

                        <input type="radio" class="btn-check" name="balance_filter" value="zero" id="balance_zero" 
                               {{ request('balance_filter') == 'zero' ? 'checked' : '' }}>
                        <label class="btn btn-outline-info btn-sm" for="balance_zero">
                            <i class="fas fa-balance-scale me-1"></i>متوازن
                        </label>
                    </div>
                </div>
                
                <!-- أزرار العمل -->
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>بحث
                    </button>
                    <a href="{{ route('admin.materials.riyadh-overview') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>مسح
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- جدول المواد -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-table me-2"></i>
                قائمة المواد - مشروع الرياض
                <span class="badge bg-light text-primary ms-2">{{ $materials->total() }} مادة</span>
            </h5>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 4%;">#</th>
                            <th style="width: 15%;">كود المادة</th>
                            <th style="width: 35%;">الوصف</th>
                            <th style="width: 10%;">الوحدة</th>
                            <th style="width: 15%;">الرصيد النهائي</th>
                            <th style="width: 21%;">رقم أمر العمل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($materials as $index => $material)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ ($materials->currentPage() - 1) * $materials->perPage() + $index + 1 }}
                                    </span>
                                </td>
                                <td>
                                    <code class="text-primary fw-bold">{{ $material->code }}</code>
                                </td>
                                <td class="text-start">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-cube text-muted me-2"></i>
                                        {{ $material->description }}
                                    </div>
                                </td>
                                <td>
                                    @if($material->unit)
                                        <span class="badge bg-info">{{ $material->unit }}</span>
                                    @else
                                        <span class="text-muted">غير محدد</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php
                                        // حساب الرصيد النهائي: الكمية المصروفة + أذن الصرف - الكمية المنفذة - أذن ارتجاع
                                        $finalBalance = ($material->spent_quantity ?? 0) + ($material->recovery_quantity ?? 0) - ($material->executed_quantity ?? 0) - ($material->completion_quantity ?? 0);
                                    @endphp
                                    <div class="d-flex align-items-center justify-content-center">
                                        @if($finalBalance > 0)
                                            <span class="badge bg-success fs-6 px-3 py-2 final-balance-badge" title="رصيد إيجابي: {{ number_format($finalBalance, 2) }}">
                                                <i class="fas fa-plus me-1"></i>
                                                {{ number_format($finalBalance, 2) }}
                                            </span>
                                        @elseif($finalBalance < 0)
                                            <span class="badge bg-danger fs-6 px-3 py-2 final-balance-badge" title="رصيد سالب: {{ number_format($finalBalance, 2) }}">
                                                <i class="fas fa-minus me-1"></i>
                                                {{ number_format(abs($finalBalance), 2) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary fs-6 px-3 py-2 final-balance-badge" title="رصيد متوازن: {{ number_format($finalBalance, 2) }}">
                                                <i class="fas fa-balance-scale me-1"></i>
                                                متوازن
                                            </span>
                                        @endif
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        {{ number_format($finalBalance, 2) }}
                                    </small>
                                </td>
                                <td>
                                    @if($material->workOrder)
                                        <a href="{{ route('admin.work-orders.show', $material->workOrder->id) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-external-link-alt me-1"></i>
                                            {{ $material->workOrder->order_number }}
                                        </a>
                                    @else
                                        <span class="text-muted">غير محدد</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-search fa-3x mb-3"></i>
                                        <h5>لا توجد مواد تطابق معايير البحث</h5>
                                        <p>جرب تعديل الفلاتر أو البحث بمعايير أخرى</p>
                                        @if(config('app.debug'))
                                            <div class="mt-3 p-3 bg-light border rounded">
                                                <small class="text-info">
                                                    <strong>معلومات التشخيص:</strong><br>
                                                    عدد أوامر العمل: {{ $workOrders->count() }}<br>
                                                    @if($workOrders->count() > 0)
                                                        أوامر العمل المتاحة:<br>
                                                        @foreach($workOrders->take(5) as $wo)
                                                            - {{ $wo->order_number }}: {{ $wo->project_name ?: $wo->project_description }}<br>
                                                        @endforeach
                                                    @endif
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($materials->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        عرض {{ $materials->firstItem() }} إلى {{ $materials->lastItem() }} 
                        من أصل {{ $materials->total() }} مادة
                    </div>
                    <div>
                        {{ $materials->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تحسين تجربة المستخدم مع أزرار فلتر الرصيد
    const balanceButtons = document.querySelectorAll('input[name="balance_filter"]');
    
    balanceButtons.forEach(button => {
        button.addEventListener('change', function() {
            // إضافة تأثير بصري عند التغيير
            const label = document.querySelector(`label[for="${this.id}"]`);
            if (label) {
                label.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    label.style.transform = '';
                }, 150);
            }
            
            // إرسال النموذج تلقائياً عند تغيير الفلتر (اختياري)
            // document.querySelector('form').submit();
        });
    });
    
    // إضافة tooltips للأزرار
    const tooltips = {
        'balance_all': 'عرض جميع المواد بغض النظر عن الرصيد',
        'balance_positive': 'عرض المواد ذات الرصيد الإيجابي فقط',
        'balance_negative': 'عرض المواد ذات الرصيد السالب فقط',
        'balance_zero': 'عرض المواد ذات الرصيد المتوازن فقط'
    };
    
    Object.keys(tooltips).forEach(id => {
        const label = document.querySelector(`label[for="${id}"]`);
        if (label) {
            label.setAttribute('title', tooltips[id]);
            label.setAttribute('data-bs-toggle', 'tooltip');
        }
    });
    
    // تفعيل Bootstrap tooltips
    if (typeof bootstrap !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});

// تحسين تجربة المستخدم
$(document).ready(function() {
        // إضافة تأثيرات على الأزرار
        $('.btn').hover(function() {
            $(this).addClass('shadow-sm');
        }, function() {
            $(this).removeClass('shadow-sm');
        });
        
        // تحسين عرض الجدول على الشاشات الصغيرة
        if ($(window).width() < 768) {
            $('.table-responsive').addClass('table-responsive-sm');
        }
    });
</script>
@endpush
