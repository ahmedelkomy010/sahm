@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header text-white py-4" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">
                                <i class="fas fa-hard-hat me-2"></i>
                                مخالفات السلامة (المدينة المنورة)
                            </h3>
                            <p class="mb-0 opacity-90">
                                <i class="fas fa-mosque me-1"></i>
                                عرض جميع مخالفات السلامة لأوامر العمل في مشروع المدينة المنورة
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.quality.violations.madinah') }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                المخالفات العامة
                            </a>
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
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-hard-hat fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ $violationsCount }}</h2>
                            <p class="mb-0">عدد مخالفات السلامة</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-money-bill-wave fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ number_format($totalAmount, 2) }}</h2>
                            <p class="mb-0">إجمالي قيمة المخالفات (ريال)</p>
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

            <!-- Safety Violations Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2 text-warning"></i>
                            قائمة مخالفات السلامة
                        </h5>
                        <a href="{{ route('admin.quality.safety-violations.export', ['city' => 'madinah']) }}" class="btn btn-success btn-sm">
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
                                    <th class="text-center">تاريخ المخالفة</th>
                                    <th>المخالف</th>
                                    <th>جهة المخالفة</th>
                                    <th class="text-center">قيمة المخالفة</th>
                                    <th>الوصف</th>
                                    <th class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($violations as $index => $violation)
                                <tr>
                                    <td class="text-center">{{ $violations->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $violation->workOrder->order_number ?? 'غير محدد' }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $violation->workOrder->work_type ?? 'غير محدد' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ $violation->violation_date ? $violation->violation_date->format('Y-m-d') : '-' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $violation->violator ?? 'غير محدد' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($violation->violation_source == 'internal')
                                            <span class="badge bg-warning">داخلية</span>
                                        @elseif($violation->violation_source == 'external')
                                            <span class="badge bg-info">خارجية</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $violation->violation_source ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <strong class="text-danger">
                                            {{ number_format($violation->violation_amount ?? 0, 2) }} ريال
                                        </strong>
                                    </td>
                                    <td>
                                        <small>{{ $violation->description ?? '-' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.work-orders.show', $violation->work_order_id) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                                            <p class="mb-0">لا توجد مخالفات سلامة مسجلة حالياً</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($violations->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $violations->links() }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

