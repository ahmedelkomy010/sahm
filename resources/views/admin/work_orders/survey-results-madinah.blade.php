@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">
                                <i class="fas fa-clipboard-list me-2"></i>
                                أوامر العمل التي تحتاج للمسح (المدينة المنورة)
                            </h3>
                            <p class="mb-0 opacity-90">
                                <i class="fas fa-mosque me-1"></i>
                                عرض جميع أوامر العمل بالمدينة المنورة التي لم يتم مسحها بعد
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.work-orders.survey.export', ['city' => 'madinah']) }}" 
                               class="btn btn-success btn-lg shadow-sm me-2">
                                <i class="fas fa-file-excel me-2"></i>
                                تصدير Excel
                            </a>
                            <a href="/admin/work-orders/productivity/madinah" 
                               class="btn btn-light btn-lg shadow-sm">
                                <i class="fas fa-arrow-right me-2"></i>
                                العودة إلى لوحة التحكم - المدينة المنورة
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-clipboard-list fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ $workOrders->total() }}</h2>
                            <p class="mb-0">أوامر العمل التي تحتاج للمسح</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-money-bill-wave fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ number_format($totalValue, 0) }}</h2>
                            <p class="mb-0">إجمالي القيمة المبدئية</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 bg-success">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-mosque fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">المدينة المنورة</h2>
                            <p class="mb-0">مشروع المدينة المنورة</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Work Orders Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2 text-primary"></i>
                        قائمة أوامر العمل التي تحتاج للمسح
                    </h5>
                </div>
                <div class="card-body">
                    @if($workOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>رقم أمر العمل</th>
                                    <th>نوع أمر العمل</th>
                                    <th class="text-center">تاريخ الاعتماد</th>
                                    <th class="text-center">نتائج المسح</th>
                                    <th class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workOrders as $index => $order)
                                <tr>
                                    <td class="text-center">{{ $workOrders->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $order->work_order_number }}</strong>
                                    </td>
                                    <td>
                                        <span class="text-muted small">{{ $order->work_type ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        {{ $order->approval_date ? $order->approval_date->format('Y-m-d') : '-' }}
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $surveysCount = $order->surveys()->count();
                                            $surveysWithFiles = $order->surveys()->has('files')->count();
                                            $totalFiles = $order->surveys()->withCount('files')->get()->sum('files_count');
                                        @endphp
                                        @if($surveysCount > 0)
                                            <span class="badge bg-info">
                                                <i class="fas fa-clipboard-list me-1"></i>
                                                {{ $surveysCount }} مسح
                                            </span>
                                            @if($totalFiles > 0)
                                                <span class="badge bg-success ms-1">
                                                    <i class="fas fa-file me-1"></i>
                                                    {{ $totalFiles }} ملف
                                                </span>
                                            @else
                                                <span class="badge bg-danger ms-1">
                                                    <i class="fas fa-times me-1"></i>
                                                    بدون ملفات
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-minus me-1"></i>
                                                لا يوجد
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.work-orders.show', $order->id) }}" 
                                           class="btn btn-sm btn-primary me-1" 
                                           title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $workOrders->links() }}
                    </div>
                    @else
                    <div class="alert alert-success text-center">
                        <i class="fas fa-check-circle fa-2x mb-3"></i>
                        <p class="mb-0">جميع أوامر العمل في المدينة المنورة تم مسحها ✓</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }
    
    .badge {
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
// No additional scripts needed
</script>
@endpush
@endsection

