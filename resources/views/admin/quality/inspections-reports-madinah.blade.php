@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header text-white py-4" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">
                                <i class="fas fa-chart-bar me-2"></i>
                                تقارير الاختبارات (المدينة المنورة)
                            </h3>
                            <p class="mb-0 opacity-90">
                                <i class="fas fa-mosque me-1"></i>
                                تقارير الاختبارات الناجحة والراسبة لمشروع المدينة المنورة
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.quality.inspections.madinah') }}" class="btn btn-light">
                                <i class="fas fa-arrow-right me-2"></i>
                                العودة إلى الاختبارات
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-clipboard-check fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ $totalTests }}</h2>
                            <p class="mb-0">إجمالي الاختبارات</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-check-circle fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ $successfulTests }}</h2>
                            <p class="mb-0">اختبارات ناجحة</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-times-circle fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ $failedTests }}</h2>
                            <p class="mb-0">اختبارات راسبة</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-percentage fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ $totalTests > 0 ? number_format(($successfulTests / $totalTests) * 100, 1) : 0 }}%</h2>
                            <p class="mb-0">نسبة النجاح</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2 text-primary"></i>
                            تفاصيل الاختبارات
                        </h5>
                        <a href="{{ route('admin.quality.inspections-reports.export-madinah') }}" class="btn btn-success btn-sm">
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
                                    <th>رقم الرخصة</th>
                                    <th class="text-center">إجمالي الاختبارات</th>
                                    <th class="text-center">ناجحة</th>
                                    <th class="text-center">راسبة</th>
                                    <th class="text-center">النسبة</th>
                                    <th class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($licenses as $index => $license)
                                @php
                                    $successRate = 0;
                                    if ($license->total_tests_count > 0) {
                                        $successRate = ($license->successful_tests_count / $license->total_tests_count) * 100;
                                    }
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $license->workOrder->order_number ?? 'غير محدد' }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $license->workOrder->work_type ?? 'غير محدد' }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-primary">{{ $license->license_number ?? '-' }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary" style="font-size: 0.95rem;">
                                            {{ $license->total_tests_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success" style="font-size: 0.95rem;">
                                            <i class="fas fa-check me-1"></i>
                                            {{ $license->successful_tests_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger" style="font-size: 0.95rem;">
                                            <i class="fas fa-times me-1"></i>
                                            {{ $license->failed_tests_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($successRate >= 80)
                                            <span class="badge bg-success" style="font-size: 0.9rem;">{{ number_format($successRate, 1) }}%</span>
                                        @elseif($successRate >= 50)
                                            <span class="badge bg-warning" style="font-size: 0.9rem;">{{ number_format($successRate, 1) }}%</span>
                                        @else
                                            <span class="badge bg-danger" style="font-size: 0.9rem;">{{ number_format($successRate, 1) }}%</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.work-orders.show', $license->work_order_id) }}" 
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
                                            <p class="mb-0">لا توجد اختبارات مسجلة حالياً</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

