@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 fs-4">
                            <i class="fas fa-eye me-2"></i>
                            تفاصيل الرخصة - {{ $license->license_number ?? 'غير محدد' }}
                        </h3>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.licenses.edit', $license) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit me-1"></i> تعديل
                            </a>
                            <a href="{{ route('admin.work-orders.license', $license->workOrder) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-right me-1"></i> عودة
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- معلومات أساسية -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                المعلومات الأساسية
                            </h5>
                        </div>
                        <div class="col-md-3">
                            <strong>أمر العمل:</strong>
                            <p class="text-muted">{{ $license->workOrder->order_number ?? 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>رقم الرخصة:</strong>
                            <p class="text-primary fw-bold">{{ $license->license_number ?? 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>تاريخ الرخصة:</strong>
                            <p class="text-muted">{{ $license->license_date ? $license->license_date->format('Y-m-d') : 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>نوع الرخصة:</strong>
                            <span class="badge bg-info">{{ $license->license_type ?? 'غير محدد' }}</span>
                        </div>
                    </div>

                    <!-- القيم المالية -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-success mb-3">
                                <i class="fas fa-money-bill-wave me-2"></i>
                                القيم المالية
                            </h5>
                        </div>
                        <div class="col-md-4">
                            <strong>قيمة الرخصة:</strong>
                            <p class="text-success fw-bold">{{ $license->license_value ? number_format($license->license_value, 2) . ' ر.س' : 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>قيمة التمديدات:</strong>
                            <p class="text-warning fw-bold">{{ $license->extension_value ? number_format($license->extension_value, 2) . ' ر.س' : 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>إجمالي القيمة:</strong>
                            <p class="text-primary fw-bold">{{ ($license->license_value + $license->extension_value) ? number_format($license->license_value + $license->extension_value, 2) . ' ر.س' : 'غير محدد' }}</p>
                        </div>
                    </div>

                    <!-- التواريخ -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-info mb-3">
                                <i class="fas fa-calendar-alt me-2"></i>
                                التواريخ المهمة
                            </h5>
                        </div>
                        <div class="col-md-3">
                            <strong>تاريخ البداية:</strong>
                            <p class="text-muted">{{ $license->license_start_date ? $license->license_start_date->format('Y-m-d') : 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>تاريخ النهاية:</strong>
                            <p class="text-muted">{{ $license->license_end_date ? $license->license_end_date->format('Y-m-d') : 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>تاريخ بداية التمديد:</strong>
                            <p class="text-muted">{{ $license->license_extension_start_date ? $license->license_extension_start_date->format('Y-m-d') : 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>تاريخ نهاية التمديد:</strong>
                            <p class="text-muted">{{ $license->license_extension_end_date ? $license->license_extension_end_date->format('Y-m-d') : 'غير محدد' }}</p>
                        </div>
                    </div>

                    <!-- أبعاد الحفر -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-warning mb-3">
                                <i class="fas fa-ruler-combined me-2"></i>
                                أبعاد الحفر
                            </h5>
                        </div>
                        <div class="col-md-4">
                            <strong>الطول:</strong>
                            <p class="text-muted">{{ $license->excavation_length ?? 'غير محدد' }} متر</p>
                        </div>
                        <div class="col-md-4">
                            <strong>العرض:</strong>
                            <p class="text-muted">{{ $license->excavation_width ?? 'غير محدد' }} متر</p>
                        </div>
                        <div class="col-md-4">
                            <strong>العمق:</strong>
                            <p class="text-muted">{{ $license->excavation_depth ?? 'غير محدد' }} متر</p>
                        </div>
                    </div>

                    <!-- حالة الحظر -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-danger mb-3">
                                <i class="fas fa-ban me-2"></i>
                                حالة الحظر
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <strong>يوجد حظر:</strong>
                            <span class="badge {{ $license->has_restriction ? 'bg-danger' : 'bg-success' }} ms-2">
                                {{ $license->has_restriction ? 'نعم' : 'لا' }}
                            </span>
                        </div>
                        @if($license->has_restriction)
                        <div class="col-md-6">
                            <strong>جهة الحظر:</strong>
                            <p class="text-muted">{{ $license->restriction_authority ?? 'غير محدد' }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- الاختبارات المطلوبة -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-success mb-3">
                                <i class="fas fa-flask me-2"></i>
                                الاختبارات المطلوبة
                            </h5>
                        </div>
                        <div class="col-md-2">
                            <strong>اختبار العمق:</strong><br>
                            <span class="badge {{ $license->has_depth_test ? 'bg-success' : 'bg-secondary' }}">
                                {{ $license->has_depth_test ? 'مطلوب' : 'غير مطلوب' }}
                            </span>
                        </div>
                        <div class="col-md-2">
                            <strong>اختبار التربة:</strong><br>
                            <span class="badge {{ $license->has_soil_test ? 'bg-success' : 'bg-secondary' }}">
                                {{ $license->has_soil_test ? 'مطلوب' : 'غير مطلوب' }}
                            </span>
                        </div>
                        <div class="col-md-2">
                            <strong>اختبار الأسفلت:</strong><br>
                            <span class="badge {{ $license->has_asphalt_test ? 'bg-success' : 'bg-secondary' }}">
                                {{ $license->has_asphalt_test ? 'مطلوب' : 'غير مطلوب' }}
                            </span>
                        </div>
                        <div class="col-md-2">
                            <strong>اختبار دك التربة:</strong><br>
                            <span class="badge {{ $license->has_soil_compaction_test ? 'bg-success' : 'bg-secondary' }}">
                                {{ $license->has_soil_compaction_test ? 'مطلوب' : 'غير مطلوب' }}
                            </span>
                        </div>
                        <div class="col-md-2">
                            <strong>اختبار RC1/MC1:</strong><br>
                            <span class="badge {{ $license->has_rc1_mc1_test ? 'bg-success' : 'bg-secondary' }}">
                                {{ $license->has_rc1_mc1_test ? 'مطلوب' : 'غير مطلوب' }}
                            </span>
                        </div>
                        <div class="col-md-2">
                            <strong>اختبار انترلوك:</strong><br>
                            <span class="badge {{ $license->has_interlock_test ? 'bg-success' : 'bg-secondary' }}">
                                {{ $license->has_interlock_test ? 'مطلوب' : 'غير مطلوب' }}
                            </span>
                        </div>
                    </div>

                    <!-- الإخلاءات -->
                    @if($license->is_evacuated)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-warning mb-3">
                                <i class="fas fa-truck-moving me-2"></i>
                                معلومات الإخلاءات
                            </h5>
                        </div>
                        <div class="col-md-3">
                            <strong>رقم رخصة الإخلاء:</strong>
                            <p class="text-muted">{{ $license->evac_license_number ?? 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>قيمة رخصة الإخلاء:</strong>
                            <p class="text-warning fw-bold">{{ $license->evac_license_value ? number_format($license->evac_license_value, 2) . ' ر.س' : 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>تاريخ الإخلاء:</strong>
                            <p class="text-muted">{{ $license->evac_date ? $license->evac_date->format('Y-m-d') : 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>مبلغ الإخلاء:</strong>
                            <p class="text-info fw-bold">{{ $license->evac_amount ? number_format($license->evac_amount, 2) . ' ر.س' : 'غير محدد' }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- المخالفات -->
                    @if($license->violation_number)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-danger mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                معلومات المخالفات
                            </h5>
                        </div>
                        <div class="col-md-4">
                            <strong>رقم المخالفة:</strong>
                            <p class="text-muted">{{ $license->violation_number }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>قيمة رخصة المخالفة:</strong>
                            <p class="text-danger fw-bold">{{ $license->violation_license_value ? number_format($license->violation_license_value, 2) . ' ر.س' : 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>آخر موعد سداد:</strong>
                            <p class="text-muted">{{ $license->violation_due_date ? $license->violation_due_date->format('Y-m-d') : 'غير محدد' }}</p>
                        </div>
                        @if($license->violation_cause)
                        <div class="col-12">
                            <strong>مسبب المخالفة:</strong>
                            <p class="text-muted">{{ $license->violation_cause }}</p>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- الملاحظات -->
                    @if($license->notes)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-secondary mb-3">
                                <i class="fas fa-sticky-note me-2"></i>
                                الملاحظات
                            </h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{{ $license->notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- معلومات النظام -->
                    <div class="row">
                        <div class="col-12">
                            <hr>
                            <h6 class="text-muted mb-3">معلومات النظام</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <strong>تاريخ الإنشاء:</strong> {{ $license->created_at->format('Y-m-d H:i:s') }}
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <strong>آخر تحديث:</strong> {{ $license->updated_at->format('Y-m-d H:i:s') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #1976D2, #2196F3, #42A5F5);
}

.card {
    border-radius: 1rem;
}

.card-header {
    border-radius: 1rem 1rem 0 0 !important;
}

h5 {
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 0.5rem;
}

.badge {
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
}

.fw-bold {
    font-weight: 600 !important;
}
</style> 