@extends('layouts.admin')

@section('title', 'تفاصيل الرخصة رقم ' . $license->license_number)

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0 bg-primary text-white">
                <div class="card-body text-center py-4">
                    <h1 class="display-5 fw-bold mb-2">تفاصيل الرخصة</h1>
                    <h2 class="h3 mb-3">رقم الرخصة: {{ $license->license_number }}</h2>
                    <p class="mb-1">أمر العمل رقم: {{ $license->workOrder->order_number ?? 'غير محدد' }}</p>
                    <p class="mb-3">اسم المشترك: {{ $license->workOrder->subscriber_name ?? 'غير محدد' }}</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.work-orders.license', $license->workOrder) }}" class="btn btn-light">
                            <i class="fas fa-arrow-right me-2"></i>رجوع
                        </a>
                        <a href="{{ route('admin.licenses.edit', $license) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>تعديل
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-pills nav-justified" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#coordination" type="button" role="tab">
                        <i class="fas fa-handshake me-2"></i>شهادة التنسيق
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#laboratory" type="button" role="tab">
                            <i class="fas fa-flask me-2"></i>المختبر 
                            <span class="badge bg-success ms-2">{{ $license->license_number }}</span>
                        </button>
                </li>
                <li class="nav-item" role="presentation">
                                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#evacuations" type="button" role="tab">
                            <i class="fas fa-truck me-2"></i>الإخلاءات 
                            <span class="badge bg-warning text-dark ms-2">{{ $license->license_number }}</span>
                        </button>
                </li>
                <li class="nav-item" role="presentation">
                                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#violations" type="button" role="tab">
                            <i class="fas fa-exclamation-triangle me-2"></i>المخالفات 
                            <span class="badge bg-danger ms-2">{{ $license->license_number }}</span>
                        </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- شهادة التنسيق -->
        <div class="tab-pane fade show active" id="coordination" role="tabpanel">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-handshake me-2"></i>شهادة التنسيق 
                        <span class="badge bg-light text-primary ms-2 fs-6">رخصة {{ $license->license_number }}</span>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>معلومات أساسية</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <td><strong>رقم الرخصة</strong></td>
                                    <td>{{ $license->license_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>تاريخ الإصدار</strong></td>
                                    <td>{{ $license->license_date ? $license->license_date->format('Y-m-d') : 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>نوع الرخصة</strong></td>
                                    <td>
                                        @if($license->license_type == 'emergency')
                                            <span class="badge bg-danger">طوارئ</span>
                                        @elseif($license->license_type == 'project')
                                            <span class="badge bg-info">مشروع</span>
                                        @elseif($license->license_type == 'normal')
                                            <span class="badge bg-success">عادي</span>
                                        @else
                                            <span class="badge bg-secondary">غير محدد</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>قيمة الرخصة</strong></td>
                                    <td>{{ number_format($license->license_value ?? 0, 2) }} ريال</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>أبعاد الحفر</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <td><strong>الطول</strong></td>
                                    <td>{{ $license->excavation_length ?? 0 }} متر</td>
                                </tr>
                                <tr>
                                    <td><strong>العرض</strong></td>
                                    <td>{{ $license->excavation_width ?? 0 }} متر</td>
                                </tr>
                                <tr>
                                    <td><strong>العمق</strong></td>
                                    <td>{{ $license->excavation_depth ?? 0 }} متر</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- المختبر -->
        <div class="tab-pane fade" id="laboratory" role="tabpanel">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-flask me-2"></i>الاختبارات المعملية 
                        <span class="badge bg-light text-success ms-2 fs-6">رخصة {{ $license->license_number }}</span>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-ruler-vertical fa-3x text-primary mb-3"></i>
                                    <h6>اختبار العمق</h6>
                                    @if($license->has_depth_test)
                                        <span class="badge bg-success">مفعل</span>
                                    @else
                                        <span class="badge bg-secondary">غير مفعل</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-seedling fa-3x text-success mb-3"></i>
                                    <h6>اختبار التربة</h6>
                                    @if($license->has_soil_test)
                                        <span class="badge bg-success">مفعل</span>
                                    @else
                                        <span class="badge bg-secondary">غير مفعل</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-road fa-3x text-warning mb-3"></i>
                                    <h6>اختبار الأسفلت</h6>
                                    @if($license->has_asphalt_test)
                                        <span class="badge bg-success">مفعل</span>
                                    @else
                                        <span class="badge bg-secondary">غير مفعل</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الإخلاءات -->
        <div class="tab-pane fade" id="evacuations" role="tabpanel">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-truck me-2"></i>الإخلاءات 
                        <span class="badge bg-dark text-warning ms-2 fs-6">رخصة {{ $license->license_number }}</span>
                    </h4>
                </div>
                <div class="card-body">
                    @if($license->is_evacuated)
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle"></i> تم الإخلاء</h5>
                            <table class="table">
                                <tr>
                                    <td><strong>رقم رخصة الإخلاء:</strong></td>
                                    <td>{{ $license->evac_license_number ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>قيمة رخصة الإخلاء:</strong></td>
                                    <td>{{ number_format($license->evac_license_value ?? 0, 2) }} ريال</td>
                                </tr>
                                <tr>
                                    <td><strong>تاريخ الإخلاء:</strong></td>
                                    <td>{{ $license->evac_date ? $license->evac_date->format('Y-m-d') : 'غير محدد' }}</td>
                                </tr>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-truck-loading fa-5x text-muted mb-3"></i>
                            <h4 class="text-muted">لم يتم الإخلاء بعد</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- المخالفات -->
        <div class="tab-pane fade" id="violations" role="tabpanel">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>المخالفات 
                        <span class="badge bg-light text-danger ms-2 fs-6">رخصة {{ $license->license_number }}</span>
                    </h4>
                </div>
                <div class="card-body">
                    @if($license->violation_number)
                        <div class="alert alert-danger">
                            <h5><i class="fas fa-exclamation-triangle"></i> يوجد مخالفة</h5>
                            <table class="table">
                                <tr>
                                    <td><strong>رقم المخالفة:</strong></td>
                                    <td>{{ $license->violation_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>قيمة المخالفة:</strong></td>
                                    <td>{{ number_format($license->violation_license_value ?? 0, 2) }} ريال</td>
                                </tr>
                                <tr>
                                    <td><strong>تاريخ المخالفة:</strong></td>
                                    <td>{{ $license->violation_license_date ? $license->violation_license_date->format('Y-m-d') : 'غير محدد' }}</td>
                                </tr>
                                @if($license->violation_cause)
                                <tr>
                                    <td><strong>سبب المخالفة:</strong></td>
                                    <td>{{ $license->violation_cause }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shield-alt fa-5x text-success mb-3"></i>
                            <h4 class="text-success">لا توجد مخالفات</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- أزرار الإجراءات -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <div class="btn-group">
                <a href="{{ route('admin.work-orders.license', $license->workOrder) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>رجوع
                </a>
                <a href="{{ route('admin.licenses.edit', $license) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>تعديل
                </a>
                <button onclick="window.print()" class="btn btn-success">
                    <i class="fas fa-print me-2"></i>طباعة
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.bg-primary {
    background: linear-gradient(45deg, #007bff, #0056b3) !important;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تفعيل Bootstrap tabs
    var triggerTabList = [].slice.call(document.querySelectorAll('[data-bs-toggle="pill"]'));
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl);
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault();
            tabTrigger.show();
        });
    });
});
</script>
@endsection 