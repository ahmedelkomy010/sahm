@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
@endphp
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
                            <a href="{{ route('admin.licenses.pdf', $license) }}" class="btn btn-danger btn-sm" target="_blank">
                                <i class="fas fa-file-pdf me-1"></i> تصدير PDF
                            </a>
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
                        <!-- تواريخ الرخصة الأصلية -->
                        <div class="col-md-6">
                            <div class="card border-primary h-100">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-file-contract me-2"></i>
                                        الرخصة الأصلية
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>تاريخ البداية:</strong>
                                            <p class="text-success fw-bold">{{ $license->license_start_date ? $license->license_start_date->format('Y-m-d') : 'غير محدد' }}</p>
                                        </div>
                                        <div class="col-6">
                                            <strong>تاريخ النهاية:</strong>
                                            <p class="text-danger fw-bold">{{ $license->license_end_date ? $license->license_end_date->format('Y-m-d') : 'غير محدد' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- تواريخ التمديد -->
                        <div class="col-md-6">
                            <div class="card border-warning h-100">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0">
                                        <i class="fas fa-clock me-2"></i>
                                        فترة التمديد
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if($license->license_extension_start_date || $license->license_extension_end_date)
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>تاريخ بداية التمديد:</strong>
                                            <p class="text-success fw-bold">{{ $license->license_extension_start_date ? $license->license_extension_start_date->format('Y-m-d') : 'غير محدد' }}</p>
                                        </div>
                                        <div class="col-6">
                                            <strong>تاريخ نهاية التمديد:</strong>
                                            <p class="text-danger fw-bold">{{ $license->license_extension_end_date ? $license->license_extension_end_date->format('Y-m-d') : 'غير محدد' }}</p>
                                        </div>
                                    </div>
                                    @if($license->license_extension_start_date && $license->license_extension_end_date)
                                    @php
                                        $startDate = \Carbon\Carbon::parse($license->license_extension_start_date);
                                        $endDate = \Carbon\Carbon::parse($license->license_extension_end_date);
                                        $extensionDays = $endDate->diffInDays($startDate);
                                    @endphp
                                    <div class="row mt-3">
                                        <div class="col-12 text-center">
                                            <div class="alert alert-info mb-0">
                                                <i class="fas fa-calendar-check me-2"></i>
                                                <strong>عدد أيام التمديد: {{ $extensionDays }} يوم</strong>
                                                <br>
                                                <small class="text-muted">من {{ $startDate->format('Y-m-d') }} إلى {{ $endDate->format('Y-m-d') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @else
                                    <div class="text-center text-muted">
                                        <i class="fas fa-info-circle me-2"></i>
                                        لا يوجد تمديد لهذه الرخصة
                                    </div>
                                    @endif
                                </div>
                            </div>
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
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-warning mb-3">
                                <i class="fas fa-truck-moving me-2"></i>
                                معلومات الإخلاءات
                            </h5>
                        </div>
                        <div class="col-md-2">
                            <strong>حالة الإخلاء:</strong>
                            <span class="badge {{ $license->is_evacuated ? 'bg-warning' : 'bg-secondary' }} ms-2">
                                {{ $license->is_evacuated ? 'تم الإخلاء' : 'لم يتم الإخلاء' }}
                            </span>
                        </div>
                        @if($license->is_evacuated)
                        <div class="col-md-2">
                            <strong>رقم رخصة الإخلاء:</strong>
                            <p class="text-muted">{{ $license->evac_license_number ?? 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-2">
                            <strong>قيمة رخصة الإخلاء:</strong>
                            <p class="text-warning fw-bold">{{ $license->evac_license_value ? number_format($license->evac_license_value, 2) . ' ر.س' : 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-2">
                            <strong>رقم سداد الإخلاء:</strong>
                            <p class="text-muted">{{ $license->evac_payment_number ?? 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-2">
                            <strong>تاريخ الإخلاء:</strong>
                            <p class="text-muted">{{ $license->evac_date ? $license->evac_date->format('Y-m-d') : 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-2">
                            <strong>مبلغ الإخلاء:</strong>
                            <p class="text-info fw-bold">{{ $license->evac_amount ? number_format($license->evac_amount, 2) . ' ر.س' : 'غير محدد' }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- المخالفات -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-danger mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                معلومات المخالفات
                            </h5>
                        </div>
                        @if($license->violation_number || $license->violation_license_number)
                        <div class="col-md-3">
                            <strong>رقم رخصة المخالفة:</strong>
                            <p class="text-muted">{{ $license->violation_license_number ?? 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>قيمة رخصة المخالفة:</strong>
                            <p class="text-danger fw-bold">{{ $license->violation_license_value ? number_format($license->violation_license_value, 2) . ' ر.س' : 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>تاريخ رخصة المخالفة:</strong>
                            <p class="text-muted">{{ $license->violation_license_date ? $license->violation_license_date->format('Y-m-d') : 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>رقم المخالفة:</strong>
                            <p class="text-muted">{{ $license->violation_number ?? 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>رقم سداد المخالفة:</strong>
                            <p class="text-muted">{{ $license->violation_payment_number ?? 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>آخر موعد سداد:</strong>
                            <p class="text-muted">{{ $license->violation_due_date ? $license->violation_due_date->format('Y-m-d') : 'غير محدد' }}</p>
                        </div>
                        @if($license->violation_cause)
                        <div class="col-12">
                            <strong>مسبب المخالفة:</strong>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{{ $license->violation_cause }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        @else
                        <div class="col-12">
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                لا توجد مخالفات مسجلة لهذه الرخصة
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- جداول المختبر -->
                    @if($license->lab_table1_data || $license->lab_table2_data)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-success mb-3">
                                <i class="fas fa-table me-2"></i>
                                جداول المختبر
                            </h5>
                        </div>
                        
                        @if($license->lab_table1_data)
                        <div class="col-12 mb-3">
                            <h6 class="text-info mb-2">جدول الفسح ونوع الشارع:</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>رقم الفسح</th>
                                            <th>تاريخ الفسح</th>
                                            <th>ترابي</th>
                                            <th>أسفلت</th>
                                            <th>بلاط</th>
                                            <th>الطول</th>
                                            <th>تدقيق المختبر</th>
                                            <th>ملاحظات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(json_decode($license->lab_table1_data, true) ?? [] as $row)
                                        <tr>
                                            <td>{{ $row['clearance_number'] ?? '-' }}</td>
                                            <td>{{ $row['clearance_date'] ?? '-' }}</td>
                                            <td>{{ isset($row['is_dirt']) && $row['is_dirt'] ? '✓' : '-' }}</td>
                                            <td>{{ isset($row['is_asphalt']) && $row['is_asphalt'] ? '✓' : '-' }}</td>
                                            <td>{{ isset($row['is_tile']) && $row['is_tile'] ? '✓' : '-' }}</td>
                                            <td>{{ $row['length'] ?? '-' }} م</td>
                                            <td>{{ $row['lab_check'] ?? '-' }}</td>
                                            <td>{{ $row['notes'] ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                        
                        @if($license->lab_table2_data)
                        <div class="col-12">
                            <h6 class="text-info mb-2">جدول التفاصيل الفنية:</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th>السنة</th>
                                            <th>نوع العمل</th>
                                            <th>العمق</th>
                                            <th>دك التربة</th>
                                            <th>MC1-RC2</th>
                                            <th>الكثافة القصوى</th>
                                            <th>نسبة الأسفلت</th>
                                            <th>التدرج الحبيبي</th>
                                            <th>تجربة مارشال</th>
                                            <th>تقييم البلاط</th>
                                            <th>تصنيف التربة</th>
                                            <th>تجربة بروكتور</th>
                                            <th>الخرسانة</th>
                                            <th>ملاحظات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(json_decode($license->lab_table2_data, true) ?? [] as $row)
                                        <tr>
                                            <td>{{ $row['year'] ?? '-' }}</td>
                                            <td>{{ $row['work_type'] ?? '-' }}</td>
                                            <td>{{ $row['depth'] ?? '-' }}</td>
                                            <td>{{ isset($row['soil_compaction']) && $row['soil_compaction'] ? '✓' : '-' }}</td>
                                            <td>{{ isset($row['mc1rc2']) && $row['mc1rc2'] ? '✓' : '-' }}</td>
                                            <td>{{ $row['max_asphalt_density'] ?? '-' }}</td>
                                            <td>{{ $row['asphalt_percentage'] ?? '-' }}</td>
                                            <td>{{ $row['granular_gradient'] ?? '-' }}</td>
                                            <td>{{ $row['marshall_test'] ?? '-' }}</td>
                                            <td>{{ $row['tile_evaluation'] ?? '-' }}</td>
                                            <td>{{ $row['soil_classification'] ?? '-' }}</td>
                                            <td>{{ $row['proctor_test'] ?? '-' }}</td>
                                            <td>{{ $row['concrete'] ?? '-' }}</td>
                                            <td>{{ $row['notes'] ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- جداول الإخلاءات -->
                    @if($license->evac_table1_data || $license->evac_table2_data)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-warning mb-3">
                                <i class="fas fa-table me-2"></i>
                                جداول الإخلاءات
                            </h5>
                        </div>
                        
                        @if($license->evac_table1_data)
                        <div class="col-12 mb-3">
                            <h6 class="text-warning mb-2">جدول الفسح ونوع الشارع (الإخلاءات):</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="bg-warning text-dark">
                                        <tr>
                                            <th rowspan="2" class="align-middle">رقم الفسح</th>
                                            <th rowspan="2" class="align-middle">تاريخ الفسح</th>
                                            <th rowspan="2" class="align-middle">طول الفسح</th>
                                            <th rowspan="2" class="align-middle">طول المختبر</th>
                                            <th rowspan="2" class="align-middle">نوع الشارع</th>
                                            <th colspan="3" class="text-center">كمية المواد (متر مكعب)</th>
                                            <th colspan="3" class="text-center">تدقيق المختبر</th>
                                            <th rowspan="2" class="align-middle">ملاحظات</th>
                                        </tr>
                                        <tr>
                                            <th>ترابي</th>
                                            <th>أسفلت</th>
                                            <th>بلاط</th>
                                            <th>التربة</th>
                                            <th>MC1</th>
                                            <th>أسفلت</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(json_decode($license->evac_table1_data, true) ?? [] as $row)
                                        <tr>
                                            <td>{{ $row['clearance_number'] ?? '-' }}</td>
                                            <td>{{ $row['clearance_date'] ?? '-' }}</td>
                                            <td>{{ $row['length'] ?? '-' }} م</td>
                                            <td>{{ $row['clearance_lab_number'] ?? '-' }}</td>
                                            <td>{{ $row['street_type'] ?? '-' }}</td>
                                            <td>{{ $row['dirt_quantity'] ?? '-' }}</td>
                                            <td>{{ $row['asphalt_quantity'] ?? '-' }}</td>
                                            <td>{{ $row['tile_quantity'] ?? '-' }}</td>
                                            <td>{{ $row['soil_check'] ?? '-' }}</td>
                                            <td>{{ $row['mc1_check'] ?? '-' }}</td>
                                            <td>{{ $row['asphalt_check'] ?? '-' }}</td>
                                            <td>{{ $row['notes'] ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                        
                        @if($license->evac_table2_data)
                        <div class="col-12">
                            <h6 class="text-warning mb-2">جدول التفاصيل الفنية (الإخلاءات):</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="bg-secondary text-white">
                                        <tr>
                                            <th>السنة</th>
                                            <th>نوع العمل</th>
                                            <th>العمق</th>
                                            <th>دك التربة</th>
                                            <th>MC1-RC2</th>
                                            <th>دك أسفلت</th>
                                            <th>ترابي</th>
                                            <th>الكثافة القصوى للأسفلت</th>
                                            <th>نسبة الأسفلت / التدرج الحبيبي</th>
                                            <th>تجربة مارشال</th>
                                            <th>تقييم البلاط / البرودة</th>
                                            <th>تصنيف التربة</th>
                                            <th>تجربة بروكتور</th>
                                            <th>الخرسانة</th>
                                            <th>ملاحظات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(json_decode($license->evac_table2_data, true) ?? [] as $row)
                                        <tr>
                                            <td>{{ $row['year'] ?? '-' }}</td>
                                            <td>{{ $row['work_type'] ?? '-' }}</td>
                                            <td>{{ $row['depth'] ?? '-' }}</td>
                                            <td>{{ is_numeric($row['soil_compaction'] ?? '') ? $row['soil_compaction'] : (isset($row['soil_compaction']) && $row['soil_compaction'] ? '✓' : '-') }}</td>
                                            <td>{{ is_numeric($row['mc1rc2'] ?? '') ? $row['mc1rc2'] : (isset($row['mc1rc2']) && $row['mc1rc2'] ? '✓' : '-') }}</td>
                                            <td>{{ is_numeric($row['asphalt_compaction'] ?? '') ? $row['asphalt_compaction'] : (isset($row['asphalt_compaction']) && $row['asphalt_compaction'] ? '✓' : '-') }}</td>
                                            <td>{{ is_numeric($row['is_dirt'] ?? '') ? $row['is_dirt'] : (isset($row['is_dirt']) && $row['is_dirt'] ? '✓' : '-') }}</td>
                                            <td>{{ $row['max_asphalt_density'] ?? '-' }}</td>
                                            <td>{{ isset($row['asphalt_percentage_gradient']) ? $row['asphalt_percentage_gradient'] : (($row['asphalt_percentage'] ?? '') . (isset($row['granular_gradient']) && $row['granular_gradient'] ? ' / ' . $row['granular_gradient'] : '')) }}</td>
                                            <td>{{ $row['marshall_test'] ?? '-' }}</td>
                                            <td>{{ isset($row['tile_evaluation_coldness']) ? $row['tile_evaluation_coldness'] : (($row['tile_evaluation'] ?? '') . (isset($row['coldness']) && $row['coldness'] ? ' / ' . $row['coldness'] : '')) }}</td>
                                            <td>{{ $row['soil_classification'] ?? '-' }}</td>
                                            <td>{{ $row['proctor_test'] ?? '-' }}</td>
                                            <td>{{ $row['concrete'] ?? '-' }}</td>
                                            <td>{{ $row['notes'] ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- المرفقات والوثائق -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-info mb-3">
                                <i class="fas fa-paperclip me-2"></i>
                                المرفقات والوثائق
                            </h5>
                        </div>
                        
                        <!-- شهادة التنسيق -->
                        @if($license->coordination_certificate_path)
                        <div class="col-md-6 mb-3">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0">
                                        <i class="fas fa-certificate me-2"></i>
                                        شهادة التنسيق
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <a href="{{ Storage::url($license->coordination_certificate_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i> عرض الشهادة
                                    </a>
                                    <a href="{{ Storage::url($license->coordination_certificate_path) }}" download class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-download me-1"></i> تحميل
                                    </a>
                                    @if($license->coordination_certificate_notes)
                                    <div class="mt-2">
                                        <small class="text-muted"><strong>ملاحظات:</strong> {{ $license->coordination_certificate_notes }}</small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- الخطابات والتعهدات -->
                        @if($license->letters_commitments_file_path)
                        <div class="col-md-6 mb-3">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-file-contract me-2"></i>
                                        الخطابات والتعهدات
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $letterFiles = json_decode($license->letters_commitments_file_path, true) ?? [];
                                    @endphp
                                    @foreach($letterFiles as $index => $filePath)
                                    <div class="d-flex gap-2 mb-2">
                                        <a href="{{ Storage::url($filePath) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i> ملف {{ $index + 1 }}
                                        </a>
                                        <a href="{{ Storage::url($filePath) }}" download class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-download me-1"></i> تحميل
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- ملف الرخصة -->
                        @if($license->license_file_path)
                        <div class="col-md-6 mb-3">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-file-alt me-2"></i>
                                        ملف الرخصة
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <a href="{{ Storage::url($license->license_file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i> عرض الملف
                                    </a>
                                    <a href="{{ Storage::url($license->license_file_path) }}" download class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-download me-1"></i> تحميل
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- فواتير السداد -->
                        @if($license->payment_invoices_path)
                        <div class="col-md-6 mb-3">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-receipt me-2"></i>
                                        فواتير السداد
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $invoiceFiles = json_decode($license->payment_invoices_path, true) ?? [];
                                    @endphp
                                    @foreach($invoiceFiles as $index => $filePath)
                                    <div class="d-flex gap-2 mb-2">
                                        <a href="{{ Storage::url($filePath) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i> فاتورة {{ $index + 1 }}
                                        </a>
                                        <a href="{{ Storage::url($filePath) }}" download class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-download me-1"></i> تحميل
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- إثبات السداد -->
                        @if($license->payment_proof_path)
                        <div class="col-md-6 mb-3">
                            <div class="card border-secondary">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-check-circle me-2"></i>
                                        إثبات السداد
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $proofFiles = json_decode($license->payment_proof_path, true) ?? [];
                                    @endphp
                                    @foreach($proofFiles as $index => $filePath)
                                    <div class="d-flex gap-2 mb-2">
                                        <a href="{{ Storage::url($filePath) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i> إثبات {{ $index + 1 }}
                                        </a>
                                        <a href="{{ Storage::url($filePath) }}" download class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-download me-1"></i> تحميل
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- تفعيل الرخصة -->
                        @if($license->license_activation_path)
                        <div class="col-md-6 mb-3">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0">
                                        <i class="fas fa-toggle-on me-2"></i>
                                        تفعيل الرخصة
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $activationFiles = json_decode($license->license_activation_path, true) ?? [];
                                    @endphp
                                    @foreach($activationFiles as $index => $filePath)
                                    <div class="d-flex gap-2 mb-2">
                                        <a href="{{ Storage::url($filePath) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i> تفعيل {{ $index + 1 }}
                                        </a>
                                        <a href="{{ Storage::url($filePath) }}" download class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-download me-1"></i> تحميل
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- ملفات اختبارات المختبر -->
                        @if($license->depth_test_file_path || $license->soil_test_file_path || $license->asphalt_test_file_path || $license->soil_compaction_file_path || $license->rc1_mc1_file_path || $license->interlock_test_file_path)
                        <div class="col-12 mb-3">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-flask me-2"></i>
                                        ملفات اختبارات المختبر
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if($license->depth_test_file_path)
                                        <div class="col-md-4 mb-2">
                                            <strong>اختبار العمق:</strong><br>
                                            <a href="{{ Storage::url($license->depth_test_file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i> عرض
                                            </a>
                                            <a href="{{ Storage::url($license->depth_test_file_path) }}" download class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-download me-1"></i> تحميل
                                            </a>
                                        </div>
                                        @endif
                                        
                                        @if($license->soil_test_file_path)
                                        <div class="col-md-4 mb-2">
                                            <strong>اختبار التربة:</strong><br>
                                            <a href="{{ Storage::url($license->soil_test_file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i> عرض
                                            </a>
                                            <a href="{{ Storage::url($license->soil_test_file_path) }}" download class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-download me-1"></i> تحميل
                                            </a>
                                        </div>
                                        @endif
                                        
                                        @if($license->asphalt_test_file_path)
                                        <div class="col-md-4 mb-2">
                                            <strong>اختبار الأسفلت:</strong><br>
                                            <a href="{{ Storage::url($license->asphalt_test_file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i> عرض
                                            </a>
                                            <a href="{{ Storage::url($license->asphalt_test_file_path) }}" download class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-download me-1"></i> تحميل
                                            </a>
                                        </div>
                                        @endif
                                        
                                        @if($license->soil_compaction_file_path)
                                        <div class="col-md-4 mb-2">
                                            <strong>اختبار دك التربة:</strong><br>
                                            <a href="{{ Storage::url($license->soil_compaction_file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i> عرض
                                            </a>
                                            <a href="{{ Storage::url($license->soil_compaction_file_path) }}" download class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-download me-1"></i> تحميل
                                            </a>
                                        </div>
                                        @endif
                                        
                                        @if($license->rc1_mc1_file_path)
                                        <div class="col-md-4 mb-2">
                                            <strong>اختبار RC1/MC1:</strong><br>
                                            <a href="{{ Storage::url($license->rc1_mc1_file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i> عرض
                                            </a>
                                            <a href="{{ Storage::url($license->rc1_mc1_file_path) }}" download class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-download me-1"></i> تحميل
                                            </a>
                                        </div>
                                        @endif
                                        
                                        @if($license->interlock_test_file_path)
                                        <div class="col-md-4 mb-2">
                                            <strong>اختبار انترلوك:</strong><br>
                                            <a href="{{ Storage::url($license->interlock_test_file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i> عرض
                                            </a>
                                            <a href="{{ Storage::url($license->interlock_test_file_path) }}" download class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-download me-1"></i> تحميل
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- ملفات الإخلاءات -->
                        @if($license->evacuations_file_path)
                        <div class="col-md-6 mb-3">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0">
                                        <i class="fas fa-truck-moving me-2"></i>
                                        ملفات الإخلاءات
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $evacuationFiles = json_decode($license->evacuations_file_path, true) ?? [];
                                    @endphp
                                    @foreach($evacuationFiles as $index => $filePath)
                                    <div class="d-flex gap-2 mb-2">
                                        <a href="{{ Storage::url($filePath) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i> إخلاء {{ $index + 1 }}
                                        </a>
                                        <a href="{{ Storage::url($filePath) }}" download class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-download me-1"></i> تحميل
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- ملفات المخالفات -->
                        @if($license->violations_file_path)
                        <div class="col-md-6 mb-3">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        ملفات المخالفات
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $violationFiles = json_decode($license->violations_file_path, true) ?? [];
                                    @endphp
                                    @foreach($violationFiles as $index => $filePath)
                                    <div class="d-flex gap-2 mb-2">
                                        <a href="{{ Storage::url($filePath) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i> مخالفة {{ $index + 1 }}
                                        </a>
                                        <a href="{{ Storage::url($filePath) }}" download class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-download me-1"></i> تحميل
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- مرفقات الملاحظات -->
                        @if($license->notes_attachments_path)
                        <div class="col-md-6 mb-3">
                            <div class="card border-dark">
                                <div class="card-header bg-dark text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-sticky-note me-2"></i>
                                        مرفقات الملاحظات
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $notesFiles = json_decode($license->notes_attachments_path, true) ?? [];
                                    @endphp
                                    @foreach($notesFiles as $index => $filePath)
                                    <div class="d-flex gap-2 mb-2">
                                        <a href="{{ Storage::url($filePath) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i> مرفق {{ $index + 1 }}
                                        </a>
                                        <a href="{{ Storage::url($filePath) }}" download class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-download me-1"></i> تحميل
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if(!$license->coordination_certificate_path && !$license->letters_commitments_file_path && !$license->license_file_path && !$license->payment_invoices_path && !$license->payment_proof_path && !$license->license_activation_path && !$license->depth_test_file_path && !$license->soil_test_file_path && !$license->asphalt_test_file_path && !$license->soil_compaction_file_path && !$license->rc1_mc1_file_path && !$license->interlock_test_file_path && !$license->evacuations_file_path && !$license->violations_file_path && !$license->notes_attachments_path)
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                لا توجد مرفقات محفوظة لهذه الرخصة
                            </div>
                        </div>
                        @endif
                    </div>

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

/* تحسينات المرفقات */
.card.border-warning {
    border-color: #ffc107 !important;
}

.card.border-info {
    border-color: #0dcaf0 !important;
}

.card.border-primary {
    border-color: #0d6efd !important;
}

.card.border-success {
    border-color: #198754 !important;
}

.card.border-secondary {
    border-color: #6c757d !important;
}

.card.border-danger {
    border-color: #dc3545 !important;
}

.card.border-dark {
    border-color: #212529 !important;
}

.btn-outline-primary:hover {
    color: white;
}

.btn-outline-success:hover {
    color: white;
}

/* تحسين عرض المرفقات */
.d-flex.gap-2 {
    gap: 0.5rem !important;
}

.card h6 {
    font-weight: 600;
}

.table-responsive {
    max-height: 400px;
    overflow-y: auto;
}

.alert.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}
</style> 