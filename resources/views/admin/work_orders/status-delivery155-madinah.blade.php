@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #6f42c1 0%, #9561e2 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">
                                <i class="fas fa-file-signature me-2"></i>
                                تم تسليم 155 - جاري إصدار شهادة الإنجاز (المدينة المنورة)
                            </h3>
                            <p class="mb-0 opacity-90">
                                <i class="fas fa-mosque me-1"></i>
                                عرض جميع أوامر العمل بالمدينة المنورة التي حالتها: تم تسليم 155 جاري إصدار شهادة الإنجاز
                            </p>
                        </div>
                        <div>
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
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #6f42c1 0%, #9561e2 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-clipboard-check fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ $workOrders->total() }}</h2>
                            <p class="mb-0">إجمالي أوامر العمل</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 bg-info">
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
                        <i class="fas fa-list me-2 text-purple"></i>
                        قائمة أوامر العمل
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
                                    <th>المكتب</th>
                                    <th>الموقع</th>
                                    <th>المقاول</th>
                                    <th class="text-center">القيمة المبدئية</th>
                                    <th class="text-center">الحالة</th>
                                    <th class="text-center">تاريخ الإنشاء</th>
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
                                    <td>{{ $order->office ?? '-' }}</td>
                                    <td>{{ $order->location ?? '-' }}</td>
                                    <td>{{ $order->contractor_name ?? '-' }}</td>
                                    <td class="text-center">
                                        <strong class="text-primary">
                                            {{ number_format($order->order_value_without_consultant ?? 0, 2) }}
                                        </strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge" style="background-color: #6f42c1;">
                                            <i class="fas fa-file-signature me-1"></i>
                                            تم تسليم 155 - جاري إصدار شهادة الإنجاز
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ $order->created_at ? $order->created_at->format('Y-m-d') : '-' }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.work-orders.show', $order->id) }}" 
                                           class="btn btn-sm btn-primary" 
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
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                        <p class="mb-0">لا توجد أوامر عمل بحالة "تم تسليم 155 - جاري إصدار شهادة الإنجاز" في المدينة المنورة حالياً</p>
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
    
    .text-purple {
        color: #6f42c1 !important;
    }
</style>
@endpush
@endsection

