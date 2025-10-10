@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-3">
        <div class="row">
            <div class="col-12">
                <!-- Header Section -->
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-header text-white py-4" style="background: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-1">
                                    <i class="fas fa-clock me-2"></i>
                                    أوامر العمل المتأخرة (الرياض)
                                </h3>
                                <p class="mb-0 opacity-90">
                                    <i class="fas fa-city me-1"></i>
                                    عرض أوامر العمل التي تجاوزت المدة الزمنية المحددة في مشروع الرياض
                                </p>
                            </div>
                            <div>
                                <a href="/admin/work-orders/productivity/riyadh" class="btn btn-light">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    العودة إلى لوحة التحكم
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%);">
                            <div class="card-body text-white text-center">
                                <i class="fas fa-exclamation-circle fa-3x mb-3 opacity-75"></i>
                                <h2 class="mb-1">{{ $ordersCount }}</h2>
                                <p class="mb-0">عدد الأوامر المتأخرة</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #0e8c80 0%, #2ec96a 100%);">
                            <div class="card-body text-white text-center">
                                <i class="fas fa-city fa-3x mb-3 opacity-75"></i>
                                <h2 class="mb-1">الرياض</h2>
                                <p class="mb-0">مشروع الرياض</p>
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
                                قائمة الأوامر المتأخرة
                            </h5>
                            <a href="{{ route('admin.time-management.export-overdue', ['project' => 'riyadh']) }}" 
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
                                        <th class="text-center">تاريخ الاعتماد</th>
                                        <th class="text-center">الأيام المتأخرة</th>
                                        <th class="text-center">حالة التنفيذ</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($workOrders as $index => $order)
                                    @php
                                        $daysOverdue = $order->approval_date ? (int) now()->diffInDays($order->approval_date) : 0;
                                        $statusLabels = [
                                            1 => 'جاري العمل بالموقع',
                                            2 => 'تم التنفيذ بالموقع',
                                            3 => 'تم تسليم 155',
                                            4 => 'إعداد مستخلص أول',
                                            5 => 'تم صرف مستخلص أول',
                                            6 => 'إعداد مستخلص ثاني',
                                            8 => 'تم إصدار شهادة الإنجاز',
                                            10 => 'إعداد مستخلص كلي'
                                        ];
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
                                            @if($order->approval_date)
                                                <span class="text-muted">{{ $order->approval_date->format('Y-m-d') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger" style="font-size: 0.9rem;">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                {{ $daysOverdue }} يوم
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning">
                                                {{ $statusLabels[$order->execution_status] ?? 'غير محدد' }}
                                            </span>
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
                                        <td colspan="7" class="text-center py-5">
                                            <div class="alert alert-success mb-0">
                                                <i class="fas fa-check-circle fa-2x mb-3"></i>
                                                <p class="mb-0">لا توجد أوامر متأخرة حالياً - جميع الأوامر ضمن الجدول الزمني!</p>
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

