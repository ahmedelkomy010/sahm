@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-3">
        <div class="row">
            <div class="col-12">
                <!-- Header Section -->
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-header text-white py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-1">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    التقرير اوامر العمل التي عليها معوقات تنفيذ - الرياض
                                </h3>
                                <p class="mb-0 opacity-90">
                                    <i class="fas fa-city me-1"></i>
                                    نظرة شاملة على أوامر العمل والمعوقات في مشروع الرياض
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

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card shadow-sm border-0 h-100" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);">
                            <div class="card-body text-white text-center py-3">
                                <i class="fas fa-list fa-2x mb-2 opacity-75"></i>
                                <h3 class="mb-1">{{ $totalOrders }}</h3>
                                <p class="mb-0 small">إجمالي الأوامر</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card shadow-sm border-0 h-100" style="background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);">
                            <div class="card-body text-white text-center py-3">
                                <i class="fas fa-check-circle fa-2x mb-2 opacity-75"></i>
                                <h3 class="mb-1">{{ $completedCount }}</h3>
                                <p class="mb-0 small">أوامر مكتملة</p>
                            </div>
                        </div>
                    </div>
                   
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        
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
                        <form method="GET" action="{{ route('admin.time-management.detailed-report.riyadh') }}">
                            <div class="row g-3">
                                <!-- Search Field -->
                                <div class="col-md-3">
                                    <label for="search" class="form-label">بحث برقم أمر العمل</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="search" 
                                           name="search" 
                                           placeholder="ابحث برقم الأمر"
                                           value="{{ request('search') }}">
                                </div>

                                <!-- Start Date -->
                                <div class="col-md-2">
                                    <label for="start_date" class="form-label">تاريخ البداية</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="start_date" 
                                           name="start_date"
                                           value="{{ request('start_date') }}">
                                </div>

                                <!-- End Date -->
                                <div class="col-md-2">
                                    <label for="end_date" class="form-label">تاريخ النهاية</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="end_date" 
                                           name="end_date"
                                           value="{{ request('end_date') }}">
                                </div>

                                <!-- Per Page -->
                                <div class="col-md-2">
                                    <label for="per_page" class="form-label">عدد النتائج</label>
                                    <select class="form-select" id="per_page" name="per_page">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>

                                <!-- Buttons -->
                                <div class="col-md-3">
                                    <label class="form-label d-block">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search me-1"></i>
                                        بحث
                                    </button>
                                    <a href="{{ route('admin.time-management.detailed-report.riyadh') }}" 
                                       class="btn btn-secondary">
                                        <i class="fas fa-redo me-1"></i>
                                        إعادة تعيين
                                    </a>
                                </div>
                            </div>

                            <!-- Quick Date Filters -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <label class="form-label">فترات زمنية سريعة:</label>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary" onclick="setQuickDateRange(0)">اليوم</button>
                                        <button type="button" class="btn btn-outline-primary" onclick="setQuickDateRange(7)">أسبوع</button>
                                        <button type="button" class="btn btn-outline-primary" onclick="setQuickDateRange(30)">شهر</button>
                                        <button type="button" class="btn btn-outline-primary" onclick="setQuickDateRange(90)">ربع سنة</button>
                                        <button type="button" class="btn btn-outline-primary" onclick="setQuickDateRange(180)">نصف سنة</button>
                                        <button type="button" class="btn btn-outline-primary" onclick="setQuickDateRange(365)">سنة</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Orders with Obstacles Section -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                الأوامر التي عليها معوقات في التنفيذ
                            </h5>
                            <a href="{{ route('admin.time-management.export-obstacles', ['project' => 'riyadh']) }}" 
                               class="btn btn-light btn-sm">
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
                                        <th class="text-center">يوجد معوقات</th>
                                        <th class="text-center">تاريخ المسح</th>
                                        <th>ملاحظات المعوقات</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($workOrders as $index => $order)
                                    @php
                                        $survey = $order->survey;
                                        $hasObstacles = $survey && $survey->has_obstacles;
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
                                            @if($hasObstacles)
                                                <span class="badge bg-danger" style="font-size: 0.9rem;">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    يوجد معوقات
                                                </span>
                                            @else
                                                <span class="badge bg-success" style="font-size: 0.9rem;">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    لا يوجد معوقات
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($survey && $survey->survey_date)
                                                <span class="text-muted">{{ $survey->survey_date->format('Y-m-d') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($hasObstacles && $survey->obstacles_notes)
                                                <span class="text-muted">{{ Str::limit($survey->obstacles_notes, 50) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
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
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block text-secondary"></i>
                                            <h5>لا توجد أوامر عمل</h5>
                                            <p class="mb-0">لا يوجد أوامر عمل للعرض حالياً</p>
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

@push('scripts')
<script>
function setQuickDateRange(days) {
    const endDate = new Date();
    const startDate = new Date();
    startDate.setDate(startDate.getDate() - days);
    
    document.getElementById('start_date').value = startDate.toISOString().split('T')[0];
    document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
}
</script>
@endpush

