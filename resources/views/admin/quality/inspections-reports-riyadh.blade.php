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
                                تقارير الاختبارات (الرياض)
                            </h3>
                            <p class="mb-0 opacity-90">
                                <i class="fas fa-city me-1"></i>
                                تقارير الاختبارات الناجحة والراسبة لمشروع الرياض
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.quality.inspections.riyadh') }}" class="btn btn-light">
                                <i class="fas fa-arrow-right me-2"></i>
                                العودة إلى الاختبارات
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-clipboard-check fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ $totalTests }}</h2>
                            <p class="mb-0">إجمالي الاختبارات</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-check-circle fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ $successfulTests }}</h2>
                            <p class="mb-0">اختبارات ناجحة</p>
                            @php
                                $successRate = $totalTests > 0 ? ($successfulTests / $totalTests) * 100 : 0;
                            @endphp
                            <div class="mt-2">
                                <h4 class="mb-0">{{ number_format($successRate, 1) }}%</h4>
                                <small class="opacity-75">النسبة</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-times-circle fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ $failedTests }}</h2>
                            <p class="mb-0">اختبارات راسبة</p>
                            @php
                                $failureRate = $totalTests > 0 ? ($failedTests / $totalTests) * 100 : 0;
                            @endphp
                            <div class="mt-2">
                                <h4 class="mb-0">{{ number_format($failureRate, 1) }}%</h4>
                                <small class="opacity-75">النسبة</small>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Filters Section -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-filter me-2"></i>
                        فلاتر البحث
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.quality.inspections-reports.riyadh') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">تاريخ البداية</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">تاريخ النهاية</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-6 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i>
                                تطبيق الفلتر
                            </button>
                            <a href="{{ route('admin.quality.inspections-reports.riyadh') }}" class="btn btn-secondary">
                                <i class="fas fa-redo me-1"></i>
                                إعادة تعيين
                            </a>
                            <a href="{{ route('admin.quality.inspections-reports.export', ['project' => 'riyadh']) }}?{{ http_build_query(request()->except(['page'])) }}" 
                               class="btn btn-success">
                                <i class="fas fa-file-excel me-1"></i>
                                تصدير Excel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2 text-primary"></i>
                            قائمة التقارير
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

