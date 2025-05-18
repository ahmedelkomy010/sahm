@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <h3 class="mb-0 fs-4 text-center text-md-start">تفاصيل الرخصة</h3>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <a href="{{ route('admin.work-orders.show', $license->workOrder) }}" class="btn btn-back btn-sm">
                                <i class="fas fa-arrow-right"></i> عودة للتفاصيل
                            </a>
                            <a href="{{ route('admin.licenses.edit', $license) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i> تعديل الرخصة
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3 p-md-4">
                    <!-- معلومات الرخصة الأساسية -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h4 class="mb-0 fs-5">
                                <i class="fas fa-file-alt text-primary me-2"></i>
                                معلومات الرخصة الأساسية
                            </h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="bg-light" style="width: 200px">رقم أمر العمل</th>
                                            <td>{{ $license->workOrder->order_number ?? 'غير محدد' }}</td>
                                            <th class="bg-light" style="width: 200px">رقم الرخصة</th>
                                            <td>{{ $license->license_number ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">تاريخ الرخصة</th>
                                            <td>{{ $license->license_date ? \Carbon\Carbon::parse($license->license_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                            <th class="bg-light">نوع الرخصة</th>
                                            <td>{{ $license->license_type ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">تاريخ بداية الرخصة</th>
                                            <td>{{ $license->license_start_date ? \Carbon\Carbon::parse($license->license_start_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                            <th class="bg-light">تاريخ نهاية الرخصة</th>
                                            <td>{{ $license->license_end_date ? \Carbon\Carbon::parse($license->license_end_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">طول الرخصة</th>
                                            <td>{{ $license->license_length ? $license->license_length . ' متر' : 'غير محدد' }}</td>
                                            <th class="bg-light">حالة الرخصة</th>
                                            <td>
                                                @php
                                                    $endDate = $license->license_extension_end_date ?? $license->license_end_date;
                                                    $daysLeft = \Carbon\Carbon::parse($endDate)->diffInDays(now(), false);
                                                    $statusClass = $daysLeft > 0 ? 'danger' : 'success';
                                                    $statusText = $daysLeft > 0 ? 'منتهية' : 'سارية';
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                                @if($daysLeft !== null)
                                                    <small class="text-muted ms-2">
                                                        {{ $daysLeft > 0 ? 'انتهت منذ ' . abs($daysLeft) . ' يوم' : 'متبقي ' . abs($daysLeft) . ' يوم' }}
                                                    </small>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- المرفقات -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h4 class="mb-0 fs-5">
                                <i class="fas fa-paperclip text-primary me-2"></i>
                                المرفقات
                            </h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="bg-light" style="width: 200px">شهادة التنسيق</th>
                                            <td>
                                                @if($license->coordination_certificate_path)
                                                    <a href="{{ Storage::url($license->coordination_certificate_path) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fas fa-file me-1"></i> عرض الملف
                                                    </a>
                                                @else
                                                    <span class="text-muted">لا يوجد ملف</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">الخطابات والتعهدات</th>
                                            <td>
                                                @if($license->letters_and_commitments_path)
                                                    <a href="{{ Storage::url($license->letters_and_commitments_path) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fas fa-file me-1"></i> عرض الملف
                                                    </a>
                                                @else
                                                    <span class="text-muted">لا يوجد ملف</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">الرخصة</th>
                                            <td>
                                                @if($license->license_1_path)
                                                    <a href="{{ Storage::url($license->license_1_path) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fas fa-file me-1"></i> عرض الملف
                                                    </a>
                                                @else
                                                    <span class="text-muted">لا يوجد ملف</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">فواتير السداد</th>
                                            <td>
                                                @if($license->payment_invoices_path)
                                                    <a href="{{ Storage::url($license->payment_invoices_path) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fas fa-file me-1"></i> عرض الملف
                                                    </a>
                                                @else
                                                    <span class="text-muted">لا يوجد ملف</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">إثبات السداد</th>
                                            <td>
                                                @if($license->payment_proof_path)
                                                    <a href="{{ Storage::url($license->payment_proof_path) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fas fa-file me-1"></i> عرض الملف
                                                    </a>
                                                @else
                                                    <span class="text-muted">لا يوجد ملف</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- الاختبارات -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h4 class="mb-0 fs-5">
                                <i class="fas fa-vial text-primary me-2"></i>
                                الاختبارات
                            </h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="bg-light" style="width: 200px">اختبار العمق</th>
                                            <td>
                                                <span class="badge bg-{{ $license->has_depth_test ? 'success' : 'secondary' }}">
                                                    {{ $license->has_depth_test ? 'نعم' : 'لا' }}
                                                </span>
                                                @if($license->has_depth_test && $license->test_results_file_path)
                                                    <a href="{{ Storage::url($license->test_results_file_path) }}" target="_blank" class="btn btn-sm btn-info ms-2">
                                                        <i class="fas fa-file me-1"></i> عرض النتائج
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">اختبار دك التربة</th>
                                            <td>
                                                <span class="badge bg-{{ $license->has_soil_compaction_test ? 'success' : 'secondary' }}">
                                                    {{ $license->has_soil_compaction_test ? 'نعم' : 'لا' }}
                                                </span>
                                                @if($license->has_soil_compaction_test && $license->test_results_file_path)
                                                    <a href="{{ Storage::url($license->test_results_file_path) }}" target="_blank" class="btn btn-sm btn-info ms-2">
                                                        <i class="fas fa-file me-1"></i> عرض النتائج
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">اختبار RC1/MC1</th>
                                            <td>
                                                <span class="badge bg-{{ $license->has_rc1_mc1_test ? 'success' : 'secondary' }}">
                                                    {{ $license->has_rc1_mc1_test ? 'نعم' : 'لا' }}
                                                </span>
                                                @if($license->has_rc1_mc1_test && $license->test_results_file_path)
                                                    <a href="{{ Storage::url($license->test_results_file_path) }}" target="_blank" class="btn btn-sm btn-info ms-2">
                                                        <i class="fas fa-file me-1"></i> عرض النتائج
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">اختبار الأسفلت</th>
                                            <td>
                                                <span class="badge bg-{{ $license->has_asphalt_test ? 'success' : 'secondary' }}">
                                                    {{ $license->has_asphalt_test ? 'نعم' : 'لا' }}
                                                </span>
                                                @if($license->has_asphalt_test && $license->test_results_file_path)
                                                    <a href="{{ Storage::url($license->test_results_file_path) }}" target="_blank" class="btn btn-sm btn-info ms-2">
                                                        <i class="fas fa-file me-1"></i> عرض النتائج
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">اختبار التربة</th>
                                            <td>
                                                <span class="badge bg-{{ $license->has_soil_test ? 'success' : 'secondary' }}">
                                                    {{ $license->has_soil_test ? 'نعم' : 'لا' }}
                                                </span>
                                                @if($license->has_soil_test)
                                                    @if($license->test_results_file_path)
                                                        <a href="{{ Storage::url($license->test_results_file_path) }}" target="_blank" class="btn btn-sm btn-info ms-2">
                                                            <i class="fas fa-file me-1"></i> عرض النتائج
                                                        </a>
                                                    @endif
                                                    @if($license->soil_test_images_path)
                                                        <div class="mt-2">
                                                            @php
                                                                $soilTestImages = json_decode($license->soil_test_images_path);
                                                            @endphp
                                                            @if(is_array($soilTestImages) && !empty($soilTestImages))
                                                                <div class="row g-2">
                                                                    @foreach($soilTestImages as $image)
                                                                        <div class="col-md-3 col-sm-4 col-6">
                                                                            <div class="card h-100">
                                                                                <a href="{{ Storage::url($image) }}" target="_blank" class="soil-test-image-link">
                                                                                    <img src="{{ Storage::url($image) }}" 
                                                                                         class="card-img-top soil-test-image" 
                                                                                         alt="صورة اختبار التربة {{ $loop->iteration }}"
                                                                                         data-bs-toggle="tooltip"
                                                                                         title="انقر لعرض الصورة بالحجم الكامل">
                                                                                </a>
                                                                                <div class="card-footer bg-transparent border-0 text-center py-2">
                                                                                    <small class="text-muted">صورة {{ $loop->iteration }}</small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <span class="text-muted">لا توجد صور متاحة</span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">اختبار البلاط والانترلوك</th>
                                            <td>
                                                <span class="badge bg-{{ $license->has_interlock_test ? 'success' : 'secondary' }}">
                                                    {{ $license->has_interlock_test ? 'نعم' : 'لا' }}
                                                </span>
                                                @if($license->has_interlock_test && $license->test_results_file_path)
                                                    <a href="{{ Storage::url($license->test_results_file_path) }}" target="_blank" class="btn btn-sm btn-info ms-2">
                                                        <i class="fas fa-file me-1"></i> عرض النتائج
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- التمديدات -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h4 class="mb-0 fs-5">
                                <i class="fas fa-calendar-plus text-primary me-2"></i>
                                التمديدات
                            </h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="bg-light" style="width: 200px">تمديد الرخصة</th>
                                            <td>
                                                @if($license->license_extension_file_path)
                                                    <div class="d-flex flex-column">
                                                        <a href="{{ Storage::url($license->license_extension_file_path) }}" target="_blank" class="btn btn-sm btn-info mb-2">
                                                            <i class="fas fa-file me-1"></i> عرض الملف
                                                        </a>
                                                        @if($license->license_extension_start_date && $license->license_extension_end_date)
                                                            <small class="text-muted">
                                                                من {{ \Carbon\Carbon::parse($license->license_extension_start_date)->format('Y-m-d') }}
                                                                إلى {{ \Carbon\Carbon::parse($license->license_extension_end_date)->format('Y-m-d') }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">لا يوجد تمديد</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">فاتورة التمديد</th>
                                            <td>
                                                @if($license->invoice_extension_file_path)
                                                    <div class="d-flex flex-column">
                                                        <a href="{{ Storage::url($license->invoice_extension_file_path) }}" target="_blank" class="btn btn-sm btn-info mb-2">
                                                            <i class="fas fa-file me-1"></i> عرض الملف
                                                        </a>
                                                        @if($license->invoice_extension_start_date && $license->invoice_extension_end_date)
                                                            <small class="text-muted">
                                                                من {{ \Carbon\Carbon::parse($license->invoice_extension_start_date)->format('Y-m-d') }}
                                                                إلى {{ \Carbon\Carbon::parse($license->invoice_extension_end_date)->format('Y-m-d') }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">لا يوجد فاتورة</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- معلومات إضافية -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h4 class="mb-0 fs-5">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                معلومات إضافية
                            </h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0">
                        <tbody>
                                        <tr>
                                            <th class="bg-light" style="width: 200px">يوجد حظر</th>
                                            <td>
                                                <span class="badge bg-{{ $license->has_restriction ? 'danger' : 'success' }}">
                                                    {{ $license->has_restriction ? 'نعم' : 'لا' }}
                                                </span>
                                            </td>
                                            @if($license->has_restriction)
                                                <th class="bg-light">جهة الحظر</th>
                                                <td>{{ $license->restriction_authority ?? 'غير محدد' }}</td>
                                            @endif
                                        </tr>
                                        @if($license->has_restriction)
                                            <tr>
                                                <th class="bg-light">سبب الحظر</th>
                                                <td colspan="3">{{ $license->restriction_reason ?? 'غير محدد' }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th class="bg-light">ملاحظات</th>
                                            <td colspan="3">{{ $license->notes ?? 'لا توجد ملاحظات' }}</td>
                                        </tr>
                        </tbody>
                    </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* تنسيق الأزرار */
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

.btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-back {
    background-color: #795548;
    color: white;
}

.btn-back:hover {
    background-color: #6D4C41;
    color: white;
}

/* تحسين الهيدر */
.bg-gradient-primary {
    background: linear-gradient(45deg, #1976D2, #2196F3);
}

/* تحسين الجداول */
.table {
    margin-bottom: 0;
}

.table th {
    font-weight: 600;
    white-space: nowrap;
    background-color: #f8f9fa;
}

.table td {
    vertical-align: middle;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}

/* تحسين البطاقات */
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    margin-bottom: 1.5rem;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.card-header {
    border-bottom: 1px solid rgba(0,0,0,.125);
    background-color: #f8f9fa;
}

.card-header h4 {
    font-weight: 600;
    margin: 0;
}

/* تحسين الشارات */
.badge {
    padding: 0.5em 1em;
    font-weight: 500;
    border-radius: 0.5rem;
}

/* تحسين الأيقونات */
.fas {
    margin-right: 0.5rem;
}

/* تحسين الروابط */
a {
    text-decoration: none;
}

/* تحسين التجاوب */
@media (max-width: 768px) {
    .table-responsive {
        border: 0;
    }
    
    .table th,
    .table td {
        white-space: normal;
    }
    
    .btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
    }
}

/* تنسيق صور اختبار التربة */
.soil-test-image-link {
    display: block;
    overflow: hidden;
    border-radius: 0.5rem 0.5rem 0 0;
}

.soil-test-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.soil-test-image:hover {
    transform: scale(1.05);
}

.card {
    border: 1px solid rgba(0,0,0,.125);
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
}

.card-footer {
    padding: 0.5rem;
}

/* تحسين التجاوب للشاشات الصغيرة */
@media (max-width: 768px) {
    .soil-test-image {
        height: 150px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // تفعيل tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection 