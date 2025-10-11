@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-3">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-header text-white py-4" style="background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-1">
                                    <i class="fas fa-truck-loading me-2"></i>
                                    المواد المطلوب صرفها - {{ $city }}
                                </h3>
                                <p class="mb-0 opacity-90">
                                    <i class="fas fa-dolly me-1"></i>
                                    المواد الناقصة التي يجب صرفها من المستودع
                                </p>
                            </div>
                            <div>
                                <a href="/admin/work-orders/productivity/{{ $project }}" class="btn btn-light">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    العودة إلى لوحة التحكم
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);">
                            <div class="card-body text-white text-center py-3">
                                <i class="fas fa-list fa-2x mb-2 opacity-75"></i>
                                <h3 class="mb-1">{{ $totalOrders }}</h3>
                                <p class="mb-0 small">إجمالي أوامر العمل</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100" style="background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);">
                            <div class="card-body text-white text-center py-3">
                                <i class="fas fa-truck-loading fa-2x mb-2 opacity-75"></i>
                                <h3 class="mb-1">{{ $totalMaterials }}</h3>
                                <p class="mb-0 small">إجمالي المواد</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-filter me-2"></i>
                            فلاتر البحث
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.materials.to-disburse.' . $project) }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">بحث برقم أمر العمل</label>
                                    <input type="text" class="form-control" name="search" placeholder="ابحث برقم الأمر" value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">تاريخ البداية</label>
                                    <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">تاريخ النهاية</label>
                                    <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">عدد النتائج</label>
                                    <select class="form-select" name="per_page">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label d-block">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search me-1"></i>
                                        بحث
                                    </button>
                                    <a href="{{ route('admin.materials.to-disburse.' . $project) }}" class="btn btn-secondary">
                                        <i class="fas fa-redo me-1"></i>
                                        إعادة تعيين
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-table me-2"></i>
                            قائمة المواد المطلوب صرفها
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>رقم أمر العمل</th>
                                        <th>نوع العمل</th>
                                        <th class="text-center">عدد المواد</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($workOrders as $index => $order)
                                    <tr>
                                        <td class="text-center">{{ $workOrders->firstItem() + $index }}</td>
                                        <td><strong>{{ $order->order_number ?? 'غير محدد' }}</strong></td>
                                        <td><span class="badge bg-info">{{ $order->work_type ?? 'غير محدد' }}</span></td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ $order->workOrderMaterials->count() }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.work-orders.show', $order->id) }}" 
                                               class="btn btn-sm btn-primary" 
                                               title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block text-secondary"></i>
                                            <h5>لا توجد بيانات</h5>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($workOrders->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $workOrders->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

