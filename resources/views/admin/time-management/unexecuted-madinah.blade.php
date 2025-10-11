@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-3">
        <div class="row">
            <div class="col-12">
                <!-- Header Section -->
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-header text-white py-4" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-1">
                                    <i class="fas fa-ban me-2"></i>
                                    أوامر العمل الغير منفذة (المدينة المنورة)
                                </h3>
                                <p class="mb-0 opacity-90">
                                    <i class="fas fa-mosque me-1"></i>
                                    عرض أوامر العمل جاري العمل بالموقع في مشروع المدينة المنورة
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
                        <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                            <div class="card-body text-white text-center">
                                <i class="fas fa-exclamation-circle fa-3x mb-3 opacity-75"></i>
                                <h2 class="mb-1">{{ $ordersCount }}</h2>
                                <p class="mb-0">عدد الأوامر الغير منفذة</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);">
                            <div class="card-body text-white text-center">
                                <i class="fas fa-money-bill-wave fa-3x mb-3 opacity-75"></i>
                                <h2 class="mb-1">{{ number_format($totalValue, 2) }}</h2>
                                <p class="mb-0">إجمالي القيمة</p>
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

                <!-- Work Orders Table -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-2 text-danger"></i>
                                قائمة الأوامر الغير منفذة
                            </h5>
                            <a href="{{ route('admin.time-management.export-unexecuted', ['project' => 'madinah']) }}" 
                               class="btn btn-success btn-sm">
                                <i class="fas fa-file-excel me-2"></i>
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
                                        <th class="text-center">حالة التنفيذ من السجل</th>
                                        <th class="text-center">يوجد رخصة</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($workOrders as $index => $order)
                                    @php
                                        // التحقق من وجود سجلات تنفيذ
                                        $hasExecution = $order->dailyExecutionNotes()->exists();
                                        // التحقق من وجود رخصة
                                        $hasLicense = $order->licenses()->exists();
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $workOrders->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $order->order_number ?? 'غير محدد' }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $order->work_type ?? 'غير محدد' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($hasExecution)
                                                <span class="badge bg-success" style="font-size: 0.9rem;">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    تم التنفيذ
                                                </span>
                                            @else
                                                <span class="badge bg-danger" style="font-size: 0.9rem;">
                                                    <i class="fas fa-times-circle me-1"></i>
                                                    لم يتم التنفيذ
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($hasLicense)
                                                <span class="badge bg-success" style="font-size: 0.9rem;">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    يوجد
                                                </span>
                                            @else
                                                <span class="badge bg-danger" style="font-size: 0.9rem;">
                                                    <i class="fas fa-times-circle me-1"></i>
                                                    لا يوجد
                                                </span>
                                            @endif
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
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block text-secondary"></i>
                                            <h5>لا توجد أوامر عمل غير منفذة</h5>
                                            <p class="mb-0">جميع الأوامر قيد التنفيذ أو مكتملة</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

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
@endsection

