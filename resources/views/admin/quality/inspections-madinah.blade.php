@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header text-white py-4" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">
                                <i class="fas fa-clipboard-check me-2"></i>
                                اختبارات الجودة (المدينة المنورة)
                            </h3>
                            <p class="mb-0 opacity-90">
                                <i class="fas fa-mosque me-1"></i>
                                عرض جميع اختبارات الجودة لأوامر العمل في مشروع المدينة المنورة
                            </p>
                        </div>
                        <div>
                            <a href="/admin/work-orders/productivity/madinah" class="btn btn-light">
                                <i class="fas fa-arrow-right me-2"></i>
                                العودة إلى لوحة التحكم
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-clipboard-list fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ $totalOrders }}</h2>
                            <p class="mb-0">إجمالي أوامر العمل</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-tasks fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ $ordersWithTests }}</h2>
                            <p class="mb-0">أوامر تحتوي على اختبارات</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #0e8c80 0%, #2ec96a 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-mosque fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">المدينة المنورة</h2>
                            <p class="mb-0">مشروع المدينة المنورة</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inspections Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2 text-info"></i>
                            قائمة الاختبارات
                        </h5>
                        <a href="{{ route('admin.quality.inspections.export', ['city' => 'madinah']) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel me-1"></i>
                            تصدير Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>رقم أمر العمل</th>
                                    <th>نوع العمل</th>
                                    <th class="text-center">يوجد اختبارات</th>
                                    <th class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($workOrders as $index => $workOrder)
                                @php
                                    // فحص وجود اختبارات معملية
                                    $hasTests = false;
                                    foreach($workOrder->licenses as $license) {
                                        if ($license->total_tests_count > 0 || !empty($license->lab_tests_data)) {
                                            $hasTests = true;
                                            break;
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $workOrders->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $workOrder->order_number ?? 'غير محدد' }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $workOrder->work_type ?? 'غير محدد' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($hasTests)
                                            <span class="badge bg-success" style="font-size: 0.9rem;">
                                                <i class="fas fa-check-circle me-1"></i>
                                                نعم
                                            </span>
                                        @else
                                            <span class="badge bg-danger" style="font-size: 0.9rem;">
                                                <i class="fas fa-times-circle me-1"></i>
                                                لا
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.work-orders.show', $workOrder->id) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                                            <p class="mb-0">لا توجد أوامر عمل مسجلة حالياً</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination -->
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
</div>

@endsection

