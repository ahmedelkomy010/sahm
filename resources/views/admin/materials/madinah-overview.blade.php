@extends('layouts.admin')

@section('title', 'تفاصيل عامة للمواد - المدينة المنورة')

@push('head')
<style>
    /* تنسيق عام */
    .card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    /* تنسيق الهيدر */
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .page-header * {
        position: relative;
        z-index: 1;
    }

    /* تنسيق الإحصائيات */
    .stats-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        border: none;
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        transform: scale(1.05);
    }

    .stats-card .stat-value {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .stats-card .stat-label {
        font-size: 1rem;
        opacity: 0.9;
    }

    /* تنسيق الفلاتر */
    .filters-section {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    /* تنسيق الجدول */
    .table-container {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        padding: 1rem;
        text-align: center;
        font-weight: bold;
    }

    .table thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 1rem 0.75rem;
        text-align: center;
        font-weight: 600;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9ff;
        transform: scale(1.01);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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

    .table tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border-color: #e9ecef;
    }

    /* تنسيق الأزرار */
    .btn {
        border-radius: 10px;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-outline-light {
        border: 2px solid white;
        color: white;
        background: transparent;
    }

    .btn-outline-light:hover {
        background: white;
        color: #667eea;
        transform: translateY(-2px);
    }

    /* تنسيق البادجات */
    .badge {
        border-radius: 10px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge.bg-light {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
        color: #495057;
        border: 1px solid #dee2e6;
    }

    .badge.bg-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%) !important;
    }

    /* تحسينات الاستجابة */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }
        
        .stats-card .stat-value {
            font-size: 2rem;
        }
        
        .table-responsive {
            border-radius: 15px;
        }
    }

    /* تنسيق رسالة عدم وجود بيانات */
    .no-data-message {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }

    .no-data-message i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    /* تحسين عرض الجدول على الشاشات الصغيرة */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.85rem;
        }
    }

    /* تنسيق التصفح */
    .pagination {
        justify-content: center;
        margin-top: 2rem;
    }

    .pagination .page-link {
        border-radius: 10px;
        margin: 0 0.25rem;
        border: none;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        color: #667eea;
        transition: all 0.3s ease;
    }

    .pagination .page-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateY(-2px);
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Breadcrumb -->
   

    <!-- Header -->
    <div class="page-header text-center">
        <div class="row align-items-center">
            <div class="col-12">
                <h1 class="mb-2">
                    <i class="fas fa-boxes me-3"></i>
                     مستودعات المدينة المنورة
                </h1>
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
        <div class="col-md-4 mb-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stat-value">{{ $materials->total() }}</div>
                    <div class="stat-label">إجمالي المواد الفريدة</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stat-value">{{ $workOrders->count() }}</div>
                    <div class="stat-label">أوامر العمل</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stats-card">
                <div class="card-body">
                    @php
                        $totalFinalBalance = 0;
                        foreach($materials as $material) {
                            $totalFinalBalance += $material->final_balance ?? 0;
                        }
                    @endphp
                    <div class="stat-value {{ $totalFinalBalance >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $totalFinalBalance >= 0 ? '+' : '' }}{{ number_format($totalFinalBalance, 2) }}
                    </div>
                    <div class="stat-label">إجمالي الرصيد النهائي</div>
                </div>
            </div>
        </div>
    </div>

    <!-- فلاتر البحث -->
    <div class="filters-section">
        <form method="GET" action="{{ route('admin.materials.madinah-overview') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search_code" class="form-label">
                    <i class="fas fa-barcode text-primary me-1"></i>
                    كود المادة
                </label>
                <input type="text" class="form-control" id="search_code" name="search_code" 
                       value="{{ request('search_code') }}" placeholder="ابحث بالكود...">
            </div>
            
            <div class="col-md-3">
                <label for="work_order_number" class="form-label">
                    <i class="fas fa-file-contract text-primary me-1"></i>
                    رقم أمر العمل
                </label>
                <input type="text" class="form-control" id="work_order_number" name="work_order_number" 
                       value="{{ request('work_order_number') }}" placeholder="ابحث برقم أمر العمل...">
            </div>
            
            <!-- فلتر حالة المستودع -->
            <div class="col-md-3">
                <label class="form-label">
                    <i class="fas fa-warehouse text-primary me-1"></i>
                    حالة المستودع
                </label>
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check" name="balance_filter" value="positive" id="balance_positive" 
                           {{ request('balance_filter') == 'positive' ? 'checked' : '' }}>
                    <label class="btn btn-outline-success btn-sm" for="balance_positive">
                        <i class="fas fa-check-circle me-1"></i>متاحة
                    </label>

                    <input type="radio" class="btn-check" name="balance_filter" value="negative" id="balance_negative" 
                           {{ request('balance_filter') == 'negative' ? 'checked' : '' }}>
                    <label class="btn btn-outline-danger btn-sm" for="balance_negative">
                        <i class="fas fa-exclamation-triangle me-1"></i>ناقصة
                    </label>
                </div>
            </div>
            
            <!-- فلتر نوع العملية -->
            <div class="col-md-3">
                <label class="form-label">
                    <i class="fas fa-exchange-alt text-primary me-1"></i>
                    نوع العملية
                </label>
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check" name="operation_filter" value="recovery" id="operation_recovery" 
                           {{ request('operation_filter') == 'recovery' ? 'checked' : '' }}>
                    <label class="btn btn-outline-info btn-sm" for="operation_recovery">
                        <i class="fas fa-undo me-1"></i>ارتجاع
                    </label>

                    <input type="radio" class="btn-check" name="operation_filter" value="completion" id="operation_completion" 
                           {{ request('operation_filter') == 'completion' ? 'checked' : '' }}>
                    <label class="btn btn-outline-warning btn-sm" for="operation_completion">
                        <i class="fas fa-file-signature me-1"></i>أذن صرف
                    </label>
                </div>
            </div>
            
            <div class="col-12 mt-3 text-center">
                <div class="d-flex gap-2 justify-content-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> بحث
                    </button>
                    <a href="{{ route('admin.materials.madinah-overview') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> مسح
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- جدول المواد -->
    <div class="card table-container">
        <div class="table-header">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>
                قائمة المواد - مشروع المدينة المنورة
                <span class="badge bg-light text-primary ms-2">{{ $materials->total() }}</span>
            </h5>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="4%">
                                <i class="fas fa-list-ol me-1"></i>
                                #
                            </th>
                            <th width="12%">
                                <i class="fas fa-barcode me-1"></i>
                                كود المادة
                            </th>
                            <th width="28%">
                                <i class="fas fa-align-left me-1"></i>
                                الوصف
                            </th>
                            <th width="10%">
                                <i class="fas fa-ruler me-1"></i>
                                الوحدة
                            </th>
                            <th width="15%">
                                <i class="fas fa-undo text-info me-1"></i>
                                مواد الارتجاع
                            </th>
                            <th width="15%">
                                <i class="fas fa-file-signature text-warning me-1"></i>
                                أذن صرف
                            </th>
                            <th width="16%">
                                <i class="fas fa-balance-scale me-1"></i>
                                الرصيد النهائي
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($materials as $material)
                            <tr>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark">{{ $loop->iteration }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $material->code }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold">
                                        {{ $material->description ?: $material->name }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">
                                        {{ $material->unit ?: 'غير محدد' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="badge bg-info px-2 py-1 mb-1">
                                            <i class="fas fa-undo me-1"></i>
                                            {{ number_format($material->total_completion_quantity, 2) }}
                                        </span>
                                        <small class="text-muted">ارتجاع</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="badge bg-warning px-2 py-1 mb-1">
                                            <i class="fas fa-file-signature me-1"></i>
                                            {{ number_format($material->total_recovery_quantity, 2) }}
                                        </span>
                                        <small class="text-muted">أذن صرف</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        @if($material->final_balance > 0)
                                            <span class="badge bg-success fs-6 px-3 py-2 final-balance-badge" title="متاح في المستودع: {{ number_format($material->final_balance, 2) }}">
                                                <i class="fas fa-check-circle me-1"></i>
                                                {{ number_format($material->final_balance, 2) }}
                                            </span>
                                        @elseif($material->final_balance < 0)
                                            <span class="badge bg-danger fs-6 px-3 py-2 final-balance-badge" title="ناقص من المستودع: {{ number_format($material->final_balance, 2) }}">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                {{ number_format(abs($material->final_balance), 2) }}
                                            </span>
                                        @endif
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        {{ number_format($material->final_balance, 2) }}
                                    </small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
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
    </div>

    <!-- التصفح -->
    @if($materials->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $materials->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // تحسين تجربة المستخدم
    document.addEventListener('DOMContentLoaded', function() {
        // تفعيل tooltip للعناصر
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // تحسين البحث التلقائي
        const searchInputs = document.querySelectorAll('input[type="text"]');
        searchInputs.forEach(input => {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    this.closest('form').submit();
                }
            });
        });
        
        // تحسين تجربة المستخدم مع أزرار الفلاتر
        const filterButtons = document.querySelectorAll('input[name="balance_filter"], input[name="operation_filter"]');
        
        filterButtons.forEach(button => {
            button.addEventListener('change', function() {
                // إضافة تأثير بصري عند التغيير
                const label = document.querySelector(`label[for="${this.id}"]`);
                if (label) {
                    label.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        label.style.transform = '';
                    }, 150);
                }
            });
        });
        
        // إضافة tooltips للأزرار
        const tooltips = {
            'balance_positive': 'عرض المواد المتاحة في المستودع (رصيد إيجابي)',
            'balance_negative': 'عرض المواد الناقصة من المستودع (رصيد سالب)',
            'operation_recovery': 'عرض المواد التي تم ارتجاعها فقط',
            'operation_completion': 'عرض المواد التي لها أذون صرف فقط'
        };
        
        Object.keys(tooltips).forEach(id => {
            const label = document.querySelector(`label[for="${id}"]`);
            if (label) {
                label.setAttribute('title', tooltips[id]);
                label.setAttribute('data-bs-toggle', 'tooltip');
            }
        });
        
        // تفعيل Bootstrap tooltips الجديدة
        const newTooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const newTooltipList = newTooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
