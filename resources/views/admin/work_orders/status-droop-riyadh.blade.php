@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">
                                <i class="fas fa-ban me-2"></i>
                                أوامر العمل - دروب (الرياض)
                            </h3>
                            <p class="mb-0 opacity-90">
                                <i class="fas fa-city me-1"></i>
                                عرض جميع أوامر العمل المقررة بالرياض
                            </p>
                        </div>
                        <div>
                            <a href="/admin/work-orders/productivity/riyadh" 
                               class="btn btn-light btn-lg shadow-sm">
                                <i class="fas fa-arrow-right me-2"></i>
                                العودة إلى لوحة التحكم - الرياض
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-clipboard-list fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ $workOrders->total() }}</h2>
                            <p class="mb-0">إجمالي أوامر العمل المقررة</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 bg-primary">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-city fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">الرياض</h2>
                            <p class="mb-0">مشروع الرياض</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-filter me-2"></i>
                        خيارات العرض والتصدير
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.work-orders.status.droop.riyadh') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">عدد النتائج في الصفحة</label>
                            <select class="form-select" name="per_page" onchange="this.form.submit()">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label d-block">&nbsp;</label>
                            <a href="{{ route('admin.work-orders.status.droop.export', ['city' => 'riyadh']) }}" 
                               class="btn btn-success w-100">
                                <i class="fas fa-file-excel me-2"></i>
                                تصدير إلى Excel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Work Orders Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2 text-danger"></i>
                        قائمة أوامر العمل المقررة (المتشابهة/المكررة)
                    </h5>
                </div>
                <div class="card-body">
                    @if($workOrders->count() > 0)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>تنبيه:</strong> هذه القائمة تعرض فقط أوامر العمل المتشابهة (المكررة) في رقم أمر العمل ونوع العمل
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">رقم أمر العمل</th>
                                    <th class="text-center">نوع أمر العمل</th>
                                    <th class="text-center">تاريخ الإنشاء</th>
                                    <th class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $previousOrderNumber = null;
                                    $previousWorkType = null;
                                    $rowColor = true;
                                @endphp
                                @foreach($workOrders as $index => $order)
                                    @php
                                        // تغيير اللون عند تغيير المجموعة
                                        if ($previousOrderNumber !== $order->work_order_number || $previousWorkType !== $order->work_type) {
                                            $rowColor = !$rowColor;
                                            $previousOrderNumber = $order->work_order_number;
                                            $previousWorkType = $order->work_type;
                                        }
                                        $bgClass = $rowColor ? 'table-warning' : 'table-danger';
                                    @endphp
                                <tr class="{{ $bgClass }} bg-opacity-25">
                                    <td class="text-center">{{ $workOrders->firstItem() + $index }}</td>
                                    <td class="text-center">
                                        <strong class="text-danger">{{ $order->work_order_number }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $order->work_type ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <small>{{ $order->created_at ? $order->created_at->format('Y-m-d') : '-' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.work-orders.show', $order->id) }}" 
                                           class="btn btn-sm btn-primary"
                                           title="عرض التفاصيل">
                                            <i class="fas fa-eye me-1"></i>
                                            عرض
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($workOrders->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $workOrders->links() }}
                    </div>
                    @endif

                    @else
                    <div class="alert alert-info text-center mb-0">
                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                        <p class="mb-0">لا توجد أوامر عمل مقررة حالياً</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

