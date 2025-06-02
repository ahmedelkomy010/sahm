@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <h3 class="mb-0 fs-4 text-center text-md-start">
                            <i class="fas fa-certificate me-2"></i>
                            تفاصيل الرخصة - {{ $license->license_number ?? 'غير محدد' }}
                        </h3>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            @if($license->workOrder)
                                <a href="{{ route('admin.work-orders.show', $license->workOrder) }}" class="btn btn-back btn-sm">
                                    <i class="fas fa-arrow-right"></i> عودة لأمر العمل
                                </a>
                                <a href="{{ route('admin.work-orders.license', $license->workOrder) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-flask"></i> إدارة الجودة
                                </a>
                            @endif
                            <a href="{{ route('admin.licenses.data') }}" class="btn btn-info btn-sm">
                                <i class="fas fa-database"></i> بيانات الرخص
                            </a>
                            <a href="{{ route('admin.licenses.edit', $license) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-edit"></i> تعديل الرخصة
                            </a>
                            <button onclick="printLicense()" class="btn btn-light btn-sm">
                                <i class="fas fa-print"></i> طباعة
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3 p-md-4">
                    <!-- معلومات الرخصة الأساسية -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0 fs-5">
                                <i class="fas fa-info-circle me-2"></i>
                                المعلومات الأساسية
                            </h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="table-light" style="width: 200px">أمر العمل</th>
                                            <td>
                                                @if($license->workOrder)
                                                    <a href="{{ route('admin.work-orders.show', $license->workOrder) }}" class="text-decoration-none">
                                                        <i class="fas fa-external-link-alt me-1"></i>
                                                        {{ $license->workOrder->order_number }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">غير مرتبط</span>
                                                @endif
                                            </td>
                                            <th class="table-light">رقم الرخصة</th>
                                            <td class="fw-bold text-primary">{{ $license->license_number ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="table-light">تاريخ الرخصة</th>
                                            <td>{{ $license->license_date ? $license->license_date->format('Y-m-d') : 'غير محدد' }}</td>
                                            <th class="table-light">نوع الرخصة</th>
                                            <td>
                                                @if($license->license_type)
                                                    <span class="badge bg-info">{{ $license->license_type }}</span>
                                                @else
                                                    <span class="text-muted">غير محدد</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="table-light">قيمة الرخصة</th>
                                            <td class="fw-bold text-success">{{ number_format($license->license_value ?? 0, 2) }} ر.س</td>
                                            <th class="table-light">قيمة التمديدات</th>
                                            <td class="fw-bold text-warning">{{ number_format($license->extension_value ?? 0, 2) }} ر.س</td>
                                        </tr>
                                        <tr>
                                            <th class="table-light">تاريخ البداية</th>
                                            <td>{{ $license->license_start_date ? $license->license_start_date->format('Y-m-d') : 'غير محدد' }}</td>
                                            <th class="table-light">تاريخ النهاية</th>
                                            <td>{{ $license->license_end_date ? $license->license_end_date->format('Y-m-d') : 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="table-light">مدة الرخصة</th>
                                            <td colspan="3">
                                                @if($license->license_start_date && $license->license_end_date)
                                                    @php
                                                        $days = $license->license_start_date->diffInDays($license->license_end_date);
                                                        $today = now();
                                                        $endDate = $license->license_extension_end_date ?? $license->license_end_date;
                                                        $isExpired = $today->gt($endDate);
                                                        $daysRemaining = $isExpired ? $today->diffInDays($endDate) : $endDate->diffInDays($today);
                                                    @endphp
                                                    <div class="d-flex gap-3 flex-wrap">
                                                        <span class="badge bg-primary fs-6">{{ $days }} يوماً إجمالي</span>
                                                        @if($isExpired)
                                                            <span class="badge bg-danger fs-6">انتهت منذ {{ $daysRemaining }} يوم</span>
                                                        @else
                                                            <span class="badge bg-success fs-6">متبقي {{ $daysRemaining }} يوم</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">غير محدد</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- حالة الحظر -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header {{ $license->has_restriction ? 'bg-danger text-white' : 'bg-success text-white' }}">
                            <h4 class="mb-0 fs-5">
                                <i class="fas fa-shield-alt me-2"></i>
                                حالة الحظر والقيود
                            </h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="table-light" style="width: 200px">حالة الحظر</th>
                                            <td colspan="3">
                                                <span class="badge {{ $license->has_restriction ? 'bg-danger' : 'bg-success' }} fs-6">
                                                    <i class="fas {{ $license->has_restriction ? 'fa-ban' : 'fa-check' }} me-1"></i>
                                                    {{ $license->has_restriction ? 'يوجد حظر' : 'لا يوجد حظر' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @if($license->has_restriction)
                                            <tr>
                                                <th class="table-light">جهة الحظر</th>
                                                <td class="fw-bold text-danger">{{ $license->restriction_authority ?? 'غير محدد' }}</td>
                                                <th class="table-light">سبب الحظر</th>
                                                <td>{{ $license->restriction_reason ?? 'غير محدد' }}</td>
                                            </tr>
                                            @if($license->restriction_notes)
                                                <tr>
                                                    <th class="table-light">ملاحظات الحظر</th>
                                                    <td colspan="3" class="text-danger">{{ $license->restriction_notes }}</td>
                                                </tr>
                                            @endif
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- شهادة التنسيق والمرفقات -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h4 class="mb-0 fs-5">
                                <i class="fas fa-certificate me-2"></i>
                                شهادة التنسيق والمرفقات
                            </h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="table-light" style="width: 200px">شهادة التنسيق</th>
                                            <td>
                                                @if($license->coordination_certificate_path)
                                                    <a href="{{ Storage::url($license->coordination_certificate_path) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-file-pdf me-1"></i> عرض الشهادة
                                                    </a>
                                                @else
                                                    <span class="text-muted">لا يوجد ملف</span>
                                                @endif
                                            </td>
                                            <th class="table-light">ملاحظات الشهادة</th>
                                            <td>{{ $license->coordination_certificate_notes ?? 'لا توجد ملاحظات' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="table-light">الخطابات والتعهدات</th>
                                            <td colspan="3">
                                                @if($license->letters_commitments_file_path)
                                                    @php
                                                        $files = json_decode($license->letters_commitments_file_path, true);
                                                        if (!is_array($files)) $files = [$license->letters_commitments_file_path];
                                                    @endphp
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach($files as $index => $file)
                                                            <a href="{{ Storage::url($file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-file me-1"></i> مرفق {{ $index + 1 }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-muted">لا توجد مرفقات</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- جداول المختبر -->
                    @if($license->lab_table1_data || $license->lab_table2_data)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-success text-white">
                                <h4 class="mb-0 fs-5">
                                    <i class="fas fa-flask me-2"></i>
                                    جداول المختبر والاختبارات
                                </h4>
                            </div>
                            <div class="card-body">
                                <!-- الجدول الأول -->
                                @if($license->lab_table1_data)
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-table me-2"></i>
                                        جدول الفسح والاختبارات الأول
                                    </h5>
                                    <div class="table-responsive mb-4">
                                        <table class="table table-bordered table-hover table-sm">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>رقم الفسح</th>
                                                    <th>تاريخ الفسح</th>
                                                    <th>الطول</th>
                                                    <th>نوع الشارع</th>
                                                    <th>ترابي</th>
                                                    <th>أسفلت</th>
                                                    <th>بلاط</th>
                                                    <th>تربة</th>
                                                    <th>MC1</th>
                                                    <th>أسفلت</th>
                                                    <th>ملاحظات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($license->lab_table1_data as $row)
                                                    <tr>
                                                        <td>{{ $row['permit_number'] ?? '-' }}</td>
                                                        <td>{{ $row['permit_date'] ?? '-' }}</td>
                                                        <td>{{ $row['length'] ?? '-' }}</td>
                                                        <td>{{ $row['street_type'] ?? '-' }}</td>
                                                        <td>{{ $row['soil'] ?? '-' }}</td>
                                                        <td>{{ $row['asphalt'] ?? '-' }}</td>
                                                        <td>{{ $row['tiles'] ?? '-' }}</td>
                                                        <td>{{ $row['soil_test'] ?? '-' }}</td>
                                                        <td>{{ $row['mc1_test'] ?? '-' }}</td>
                                                        <td>{{ $row['asphalt_test'] ?? '-' }}</td>
                                                        <td>{{ $row['notes'] ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                <!-- الجدول الثاني -->
                                @if($license->lab_table2_data)
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-table me-2"></i>
                                        جدول الاختبارات التفصيلي الثاني
                                    </h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-sm">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>السنة</th>
                                                    <th>نوع العمل</th>
                                                    <th>العمق</th>
                                                    <th>دك التربة</th>
                                                    <th>MC1RC2</th>
                                                    <th>دك أسفلت</th>
                                                    <th>ترابي</th>
                                                    <th>الكثافة القصوى</th>
                                                    <th>نسبة الأسفلت</th>
                                                    <th>التدرج الحبيبي</th>
                                                    <th>تجربة مارشال</th>
                                                    <th>تقييم البلاط</th>
                                                    <th>البرودة</th>
                                                    <th>تصنيف التربة</th>
                                                    <th>تجربة بروكتور</th>
                                                    <th>الخرسانة</th>
                                                    <th>ملاحظات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($license->lab_table2_data as $row)
                                                    <tr>
                                                        <td>{{ $row['year'] ?? '-' }}</td>
                                                        <td>{{ $row['work_type'] ?? '-' }}</td>
                                                        <td>{{ $row['depth'] ?? '-' }}</td>
                                                        <td>{{ $row['soil_compaction'] ?? '-' }}</td>
                                                        <td>{{ $row['mc1rc2'] ?? '-' }}</td>
                                                        <td>{{ $row['asphalt_compaction'] ?? '-' }}</td>
                                                        <td>{{ $row['soil'] ?? '-' }}</td>
                                                        <td>{{ $row['max_asphalt_density'] ?? '-' }}</td>
                                                        <td>{{ $row['asphalt_ratio'] ?? '-' }}</td>
                                                        <td>{{ $row['gradation'] ?? '-' }}</td>
                                                        <td>{{ $row['marshall_test'] ?? '-' }}</td>
                                                        <td>{{ $row['tiles_evaluation'] ?? '-' }}</td>
                                                        <td>{{ $row['cooling'] ?? '-' }}</td>
                                                        <td>{{ $row['soil_classification'] ?? '-' }}</td>
                                                        <td>{{ $row['proctor_test'] ?? '-' }}</td>
                                                        <td>{{ $row['concrete'] ?? '-' }}</td>
                                                        <td>{{ $row['notes'] ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- الاختبارات المطلوبة -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h4 class="mb-0 fs-5">
                                <i class="fas fa-vial me-2"></i>
                                الاختبارات المطلوبة
                            </h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="table-light" style="width: 200px">اختبار العمق</th>
                                            <td>
                                                <span class="badge {{ $license->has_depth_test ? 'bg-success' : 'bg-secondary' }}">
                                                    <i class="fas {{ $license->has_depth_test ? 'fa-check' : 'fa-times' }} me-1"></i>
                                                    {{ $license->has_depth_test ? 'مطلوب' : 'غير مطلوب' }}
                                                </span>
                                            </td>
                                            <th class="table-light">اختبار دك التربة</th>
                                            <td>
                                                <span class="badge {{ $license->has_soil_compaction_test ? 'bg-success' : 'bg-secondary' }}">
                                                    <i class="fas {{ $license->has_soil_compaction_test ? 'fa-check' : 'fa-times' }} me-1"></i>
                                                    {{ $license->has_soil_compaction_test ? 'مطلوب' : 'غير مطلوب' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="table-light">اختبار MC1/RC2</th>
                                            <td>
                                                <span class="badge {{ $license->has_rc1_mc1_test ? 'bg-success' : 'bg-secondary' }}">
                                                    <i class="fas {{ $license->has_rc1_mc1_test ? 'fa-check' : 'fa-times' }} me-1"></i>
                                                    {{ $license->has_rc1_mc1_test ? 'مطلوب' : 'غير مطلوب' }}
                                                </span>
                                            </td>
                                            <th class="table-light">اختبار الأسفلت</th>
                                            <td>
                                                <span class="badge {{ $license->has_asphalt_test ? 'bg-success' : 'bg-secondary' }}">
                                                    <i class="fas {{ $license->has_asphalt_test ? 'fa-check' : 'fa-times' }} me-1"></i>
                                                    {{ $license->has_asphalt_test ? 'مطلوب' : 'غير مطلوب' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="table-light">اختبار التربة</th>
                                            <td>
                                                <span class="badge {{ $license->has_soil_test ? 'bg-success' : 'bg-secondary' }}">
                                                    <i class="fas {{ $license->has_soil_test ? 'fa-check' : 'fa-times' }} me-1"></i>
                                                    {{ $license->has_soil_test ? 'مطلوب' : 'غير مطلوب' }}
                                                </span>
                                            </td>
                                            <th class="table-light">اختبار البلاط والانترلوك</th>
                                            <td>
                                                <span class="badge {{ $license->has_interlock_test ? 'bg-success' : 'bg-secondary' }}">
                                                    <i class="fas {{ $license->has_interlock_test ? 'fa-check' : 'fa-times' }} me-1"></i>
                                                    {{ $license->has_interlock_test ? 'مطلوب' : 'غير مطلوب' }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- بيانات الإخلاءات -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header {{ $license->is_evacuated ? 'bg-warning text-dark' : 'bg-secondary text-white' }}">
                            <h4 class="mb-0 fs-5">
                                <i class="fas fa-truck-moving me-2"></i>
                                بيانات الإخلاءات
                            </h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="table-light" style="width: 200px">حالة الإخلاء</th>
                                            <td colspan="3">
                                                <span class="badge {{ $license->is_evacuated ? 'bg-warning text-dark' : 'bg-secondary' }} fs-6">
                                                    <i class="fas {{ $license->is_evacuated ? 'fa-check' : 'fa-times' }} me-1"></i>
                                                    {{ $license->is_evacuated ? 'تم الإخلاء' : 'لم يتم الإخلاء' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @if($license->is_evacuated)
                                            <tr>
                                                <th class="table-light">رقم رخصة الإخلاء</th>
                                                <td>{{ $license->evac_license_number ?? 'غير محدد' }}</td>
                                                <th class="table-light">قيمة رخصة الإخلاء</th>
                                                <td class="fw-bold text-warning">{{ number_format($license->evac_license_value ?? 0, 2) }} ر.س</td>
                                            </tr>
                                            <tr>
                                                <th class="table-light">رقم سداد الإخلاء</th>
                                                <td>{{ $license->evac_payment_number ?? 'غير محدد' }}</td>
                                                <th class="table-light">تاريخ الإخلاء</th>
                                                <td>{{ $license->evac_date ? $license->evac_date->format('Y-m-d') : 'غير محدد' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="table-light">مبلغ الإخلاء</th>
                                                <td class="fw-bold text-success">{{ number_format($license->evac_amount ?? 0, 2) }} ر.س</td>
                                                <th class="table-light">مرفقات الإخلاء</th>
                                                <td>
                                                    @if($license->evac_files_path)
                                                        @php
                                                            $files = json_decode($license->evac_files_path, true);
                                                            if (!is_array($files)) $files = [$license->evac_files_path];
                                                        @endphp
                                                        <div class="d-flex flex-wrap gap-2">
                                                            @foreach($files as $index => $file)
                                                                <a href="{{ Storage::url($file) }}" target="_blank" class="btn btn-sm btn-outline-warning">
                                                                    <i class="fas fa-file me-1"></i> مرفق {{ $index + 1 }}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="text-muted">لا توجد مرفقات</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- بيانات المخالفات -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-danger text-white">
                            <h4 class="mb-0 fs-5">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                بيانات المخالفات
                            </h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <tbody>
                                        @if($license->violation_license_number || $license->violation_number || $license->violation_cause)
                                            <tr>
                                                <th class="table-light" style="width: 200px">رقم الرخصة</th>
                                                <td>{{ $license->violation_license_number ?? 'غير محدد' }}</td>
                                                <th class="table-light">قيمة الرخصة</th>
                                                <td class="fw-bold text-danger">{{ number_format($license->violation_license_value ?? 0, 2) }} ر.س</td>
                                            </tr>
                                            <tr>
                                                <th class="table-light">تاريخ الرخصة</th>
                                                <td>{{ $license->violation_license_date ? $license->violation_license_date->format('Y-m-d') : 'غير محدد' }}</td>
                                                <th class="table-light">موعد السداد</th>
                                                <td>
                                                    @if($license->violation_due_date)
                                                        {{ $license->violation_due_date->format('Y-m-d') }}
                                                        @php
                                                            $isOverdue = now()->gt($license->violation_due_date);
                                                        @endphp
                                                        <span class="badge {{ $isOverdue ? 'bg-danger' : 'bg-warning' }} ms-2">
                                                            {{ $isOverdue ? 'متأخر' : 'لم ينته' }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">غير محدد</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-light">رقم المخالفة</th>
                                                <td>{{ $license->violation_number ?? 'غير محدد' }}</td>
                                                <th class="table-light">رقم السداد</th>
                                                <td>{{ $license->violation_payment_number ?? 'غير محدد' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="table-light">مسبب المخالفة</th>
                                                <td colspan="3" class="text-danger fw-bold">{{ $license->violation_cause ?? 'غير محدد' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="table-light">مرفقات المخالفة</th>
                                                <td colspan="3">
                                                    @if($license->violation_files_path)
                                                        @php
                                                            $files = json_decode($license->violation_files_path, true);
                                                            if (!is_array($files)) $files = [$license->violation_files_path];
                                                        @endphp
                                                        <div class="d-flex flex-wrap gap-2">
                                                            @foreach($files as $index => $file)
                                                                <a href="{{ Storage::url($file) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                                                    <i class="fas fa-file me-1"></i> مرفق {{ $index + 1 }}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="text-muted">لا توجد مرفقات</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">
                                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                                    <p class="mb-0">لا توجد مخالفات مسجلة</p>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- الملاحظات الإضافية -->
                    @if($license->notes)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-dark text-white">
                                <h4 class="mb-0 fs-5">
                                    <i class="fas fa-sticky-note me-2"></i>
                                    الملاحظات الإضافية
                                </h4>
                            </div>
                            <div class="card-body">
                                <p class="mb-0 text-muted">{{ $license->notes }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- إحصائيات سريعة -->
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-primary text-white border-0">
                                <div class="card-body text-center py-4">
                                    <i class="fas fa-coins fa-2x mb-2"></i>
                                    <h5 class="card-title mb-1">{{ number_format(($license->license_value ?? 0) + ($license->extension_value ?? 0), 0) }}</h5>
                                    <p class="card-text mb-0">إجمالي القيمة (ر.س)</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card {{ $license->has_restriction ? 'bg-danger' : 'bg-success' }} text-white border-0">
                                <div class="card-body text-center py-4">
                                    <i class="fas {{ $license->has_restriction ? 'fa-ban' : 'fa-check-circle' }} fa-2x mb-2"></i>
                                    <h5 class="card-title mb-1">{{ $license->has_restriction ? 'محظور' : 'مسموح' }}</h5>
                                    <p class="card-text mb-0">حالة الحظر</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card {{ $license->is_evacuated ? 'bg-warning text-dark' : 'bg-secondary text-white' }} border-0">
                                <div class="card-body text-center py-4">
                                    <i class="fas fa-truck-moving fa-2x mb-2"></i>
                                    <h5 class="card-title mb-1">{{ $license->is_evacuated ? 'مُخلى' : 'غير مُخلى' }}</h5>
                                    <p class="card-text mb-0">حالة الإخلاء</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-info text-white border-0">
                                <div class="card-body text-center py-4">
                                    <i class="fas fa-vial fa-2x mb-2"></i>
                                    <h5 class="card-title mb-1">
                                        {{ collect([$license->has_depth_test, $license->has_soil_compaction_test, $license->has_rc1_mc1_test, $license->has_asphalt_test, $license->has_soil_test, $license->has_interlock_test])->filter()->count() }}
                                    </h5>
                                    <p class="card-text mb-0">الاختبارات المطلوبة</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-primary text-white border-0">
                                <div class="card-body text-center py-4">
                                    <i class="fas fa-ruler-combined fa-2x mb-2"></i>
                                    <h5 class="card-title mb-1">
                                        {{ $license->excavation_length ?? 0 }} × {{ $license->excavation_width ?? 0 }} × {{ $license->excavation_depth ?? 0 }}
                                    </h5>
                                    <p class="card-text mb-0">أبعاد الحفر (متر)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// طباعة تفاصيل الرخصة
function printLicense() {
    const printContent = document.querySelector('.container-fluid').innerHTML;
    const originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <div style="direction: rtl; font-family: Arial, sans-serif; padding: 20px;">
            <style>
                body { font-size: 12px; }
                .btn, .d-flex.gap-2, .card-header .d-flex > div:last-child { display: none !important; }
                .card { margin-bottom: 15px; box-shadow: none !important; border: 1px solid #ccc; }
                .table { font-size: 11px; }
                .table th, .table td { padding: 8px 4px; border: 1px solid #000; }
                .badge { font-size: 10px; }
                @page { margin: 1cm; }
            </style>
            <h2 style="text-align: center; margin-bottom: 20px;">تفاصيل الرخصة - ${document.querySelector('h3').textContent}</h2>
            <p style="text-align: center; margin-bottom: 20px;">تاريخ الطباعة: ${new Date().toLocaleDateString('ar-SA')}</p>
            ${printContent}
        </div>
    `;
    
    window.print();
    document.body.innerHTML = originalContent;
    location.reload();
}

// تفعيل tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush

<style>
/* تنسيق احترافي للصفحة */
.bg-gradient-primary {
    background: linear-gradient(135deg, #1976D2, #2196F3, #42A5F5);
}

.btn {
    transition: all 0.3s ease;
    border: none;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-back {
    background: linear-gradient(45deg, #795548, #8D6E63);
    color: white;
}

.btn-back:hover {
    background: linear-gradient(45deg, #6D4C41, #795548);
    color: white;
}

.card {
    transition: all 0.3s ease;
    border-radius: 1rem;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15) !important;
}

.card-header {
    border-radius: 1rem 1rem 0 0 !important;
    border-bottom: none;
    font-weight: 600;
}

.table {
    margin-bottom: 0;
    font-size: 0.9rem;
}

.table th {
    font-weight: 600;
    white-space: nowrap;
    vertical-align: middle;
}

.table td {
    vertical-align: middle;
}

.table-light {
    background-color: #f8f9fa !important;
}

.badge {
    padding: 0.5em 1em;
    font-weight: 500;
    border-radius: 0.5rem;
}

.text-decoration-none:hover {
    text-decoration: underline !important;
}

/* تحسين الجداول المختبرية */
.table-sm th,
.table-sm td {
    padding: 0.5rem 0.25rem;
    font-size: 0.8rem;
    text-align: center;
}

/* تحسين الاستجابة */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
    }
    
    .badge {
        font-size: 0.75rem;
    }
}

/* تحسين الطباعة */
@media print {
    .btn, .d-flex.gap-2, .card-header .d-flex > div:last-child {
        display: none !important;
    }
    
    .card {
        margin-bottom: 15px;
        box-shadow: none !important;
        border: 1px solid #000;
    }
    
    .table {
        font-size: 10px;
    }
    
    .table th, .table td {
        padding: 4px;
        border: 1px solid #000;
    }
    
    .badge {
        color: #000 !important;
        background: transparent !important;
        border: 1px solid #000;
    }
}

/* تحسين الألوان */
.bg-primary { background: linear-gradient(45deg, #1976D2, #2196F3) !important; }
.bg-success { background: linear-gradient(45deg, #388E3C, #4CAF50) !important; }
.bg-info { background: linear-gradient(45deg, #0097A7, #00BCD4) !important; }
.bg-warning { background: linear-gradient(45deg, #F57C00, #FF9800) !important; }
.bg-danger { background: linear-gradient(45deg, #D32F2F, #F44336) !important; }
.bg-secondary { background: linear-gradient(45deg, #424242, #616161) !important; }
.bg-dark { background: linear-gradient(45deg, #212121, #424242) !important; }
</style>
@endsection 