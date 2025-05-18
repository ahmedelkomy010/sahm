@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <h3 class="mb-0 fs-4 text-center text-md-start">تفاصيل أمر العمل</h3>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <a href="{{ route('admin.work-orders.materials') }}" class="btn btn-materials btn-sm">
                                <i class="fas fa-boxes me-1"></i> المواد
                            </a>
                            <a href="{{ route('admin.work-orders.survey', $workOrder) }}" class="btn btn-survey btn-sm">
                                <i class="fas fa-map-marked-alt me-1"></i> المسح
                            </a>
                            <a href="{{ route('admin.work-orders.license', $workOrder) }}" class="btn btn-license btn-sm">
                                <i class="fas fa-file-alt me-1"></i> رخصة العمل
                            </a>
                            <a href="{{ route('admin.work-orders.execution', $workOrder) }}" class="btn btn-execution btn-sm">
                                <i class="fas fa-tools me-1"></i> تنفيذ
                            </a>
                            <a href="{{ route('admin.work-orders.actions-execution', $workOrder) }}" class="btn btn-actions btn-sm">
                                <i class="fas fa-cogs me-1"></i> إجراءات ما بعد التنفيذ
                            </a>
                            <a href="{{ route('admin.work-orders.edit', $workOrder) }}" class="btn btn-edit btn-sm">
                                <i class="fas fa-edit"></i> تعديل
                            </a>
                            <a href="{{ route('admin.work-orders.index') }}" class="btn btn-back btn-sm">
                                <i class="fas fa-arrow-right"></i> عودة
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3 p-md-4">
                    <div class="row g-4">
                        <!-- معلومات أساسية -->
                        <div class="col-12 col-lg-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h4 class="mb-0 fs-5">
                                        <i class="fas fa-info-circle text-primary me-2"></i>
                                        معلومات أساسية
                                    </h4>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover mb-0">
                                            <tbody>
                                                <tr>
                                                    <th class="bg-light" style="width: 40%">رقم المسلسل</th>
                                                    <td>{{ $workOrder->id }}</td>
                                                </tr>
                                                <tr>
                                                    <th>رقم الطلب</th>
                                                    <td>{{ $workOrder->order_number }}</td>
                                                </tr>
                                                <tr>
                                                    <th>نوع العمل</th>
                                                    <td>
                                                        @switch($workOrder->work_type)
                                                            @case('409')
                                                                ازالة-نقل شبكة على المشترك
                                                                @break
                                                            @case('408')
                                                                ازاله عداد على المشترك
                                                                @break
                                                            @case('460')
                                                                استبدال شبكات
                                                                @break
                                                            @case('901')
                                                                اضافة عداد طاقة شمسية
                                                                @break
                                                            @case('440')
                                                                الرفع المساحي
                                                                @break
                                                            @case('410')
                                                                انشاء محطة/محول لمشترك/مشتركين
                                                                @break
                                                            @case('801')
                                                                تركيب عداد ايصال سريع
                                                                @break
                                                            @case('804')
                                                                تركيب محطة ش ارضية VM ايصال سريع
                                                                @break
                                                            @case('805')
                                                                تركيب محطة ش هوائية VM ايصال سريع
                                                                @break
                                                            @case('480')
                                                                (تسليم مفتاح) تمويل خارجي
                                                                @break
                                                            @case('441')
                                                                تعزيز شبكة أرضية ومحطات
                                                                @break
                                                            @case('442')
                                                                تعزيز شبكة هوائية ومحطات
                                                                @break
                                                            @case('802')
                                                                شبكة ارضية VL ايصال سريع
                                                                @break
                                                            @case('803')
                                                                شبكة هوائية VL ايصال سريع
                                                                @break
                                                            @case('402')
                                                                توصيل عداد بحفرية شبكة ارضيه
                                                                @break
                                                            @case('401')
                                                                (عداد بدون حفرية) أرضي/هوائي
                                                                @break
                                                            @case('404')
                                                                عداد بمحطة شبكة ارضية VM
                                                                @break
                                                            @case('405')
                                                                توصيل عداد بمحطة شبكة هوائية VM
                                                                @break
                                                            @case('430')
                                                                مخططات منح وزارة البلدية
                                                                @break
                                                            @case('450')
                                                                مشاريع ربط محطات التحويل
                                                                @break
                                                            @case('403')
                                                                توصيل عداد شبكة هوائية VL
                                                                @break
                                                            @default
                                                                {{ $workOrder->work_type }}
                                                        @endswitch
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>وصف العمل والتعليق</th>
                                                    <td>{{ $workOrder->work_description ?? 'غير متوفر' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>حالة التنفيذ</th>
                                                    <td>
                                                        @switch($workOrder->execution_status)
                                                            @case('1')
                                                                <span class="badge bg-info">تم الاستلام من المقاول ولم تصدر شهادة الانجاز</span>
                                                                @break
                                                            @case('2')
                                                                <span class="badge bg-warning">تم تسليم المقاول ولم يتم الاستلام</span>
                                                                @break
                                                            @case('3')
                                                                <span class="badge bg-primary">دخلت مستخلص ولم تصرف</span>
                                                                @break
                                                            @case('4')
                                                                <span class="badge bg-secondary">صدرت شهادة الانجاز ولم تعتمد</span>
                                                                @break
                                                            @case('5')
                                                                <span class="badge bg-success">منتهي</span>
                                                                @break
                                                            @case('6')
                                                                <span class="badge bg-dark">مؤكد ولم تدخل مستخلص</span>
                                                                @break
                                                            @default
                                                                <span class="badge bg-secondary">{{ $workOrder->execution_status }}</span>
                                                        @endswitch
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>تاريخ الاعتماد</th>
                                                    <td>{{ date('Y-m-d', strtotime($workOrder->approval_date)) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>اسم المشترك</th>
                                                    <td>{{ $workOrder->subscriber_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>الحي</th>
                                                    <td>{{ $workOrder->district }}</td>
                                                </tr>
                                                <tr>
                                                    <th>البلدية</th>
                                                    <td>{{ $workOrder->municipality ?? 'غير متوفر' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>المكتب</th>
                                                    <td>
                                                        @if($workOrder->office)
                                                            @switch($workOrder->office)
                                                                @case('خريص')
                                                                    <span class="badge bg-primary">خريص</span>
                                                                    @break
                                                                @case('الشرق')
                                                                    <span class="badge bg-success">الشرق</span>
                                                                    @break
                                                                @case('الشمال')
                                                                    <span class="badge bg-info">الشمال</span>
                                                                    @break
                                                                @case('الجنوب')
                                                                    <span class="badge bg-warning">الجنوب</span>
                                                                    @break
                                                                @case('الدرعية')
                                                                    <span class="badge bg-secondary">الدرعية</span>
                                                                    @break
                                                                @default
                                                                    <span class="badge bg-light text-dark">{{ $workOrder->office }}</span>
                                                            @endswitch
                                                        @else
                                                            <span class="badge bg-light text-dark">غير محدد</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>رقم المحطة</th>
                                                    <td>{{ $workOrder->station_number ?? 'غير متوفر' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>اسم الاستشاري</th>
                                                    <td>{{ $workOrder->consultant_name ?? 'غير متوفر' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- المعلومات المالية والإجراءات -->
                        <div class="col-12 col-lg-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h4 class="mb-0 fs-5">
                                        <i class="fas fa-money-bill-wave text-primary me-2"></i>
                                        المعلومات المالية والإجراءات
                                    </h4>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover mb-0">
                                            <tbody>
                                                <tr>
                                                    <th style="width: 40%">قيمة أمر العمل المبدئية شامل الاستشاري 
                                                    <td>{{ number_format($workOrder->order_value_with_consultant, 2, '.', '') }} ₪</td>
                                                </tr>
                                                <tr>
                                                    <th>قيمة أمر العمل المبدئية غير شامل الاستشاري
                                                    <td>{{ number_format($workOrder->order_value_without_consultant, 2, '.', '') }} ₪</td>
                                                </tr>
                                                <tr>
                                                    <th>قيمة التنفيذ الفعلي شامل الاستشاري</th>
                                                    <td>{{ $workOrder->actual_execution_value_consultant ? number_format($workOrder->actual_execution_value_consultant, 2, '.', '') . ' ₪' : 'غير متوفر' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>قيمة التنفيذ الفعلي غير شامل الاستشاري</th>
                                                    <td>{{ $workOrder->actual_execution_value_without_consultant ? number_format($workOrder->actual_execution_value_without_consultant, 2, '.', '') . ' ₪' : 'غير متوفر' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>قيمة الدفعة الجزئية الأولى غير شامل الضريبة</th>
                                                    <td>{{ $workOrder->first_partial_payment_without_tax ? number_format($workOrder->first_partial_payment_without_tax, 2, '.', '') . ' ₪' : 'غير متوفر' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>قيمة الدفعة الجزئية الثانية شامل الضريبة الدفعتين</th>
                                                    <td>{{ $workOrder->second_partial_payment_with_tax ? number_format($workOrder->second_partial_payment_with_tax, 2, '.', '') . ' ₪' : 'غير متوفر' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>القيمة الكلية النهائية</th>
                                                    <td>{{ $workOrder->final_total_value ? number_format($workOrder->final_total_value, 2, '.', '') . ' ₪' : 'غير متوفر' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>تاريخ تسليم إجراء 155</th>
                                                    <td>{{ $workOrder->procedure_155_delivery_date ? $workOrder->procedure_155_delivery_date->format('Y-m-d') : 'غير متوفر' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>مرفق اختبارات ما قبل التشغيل 211</th>
                                                    <td>
                                                        @if($workOrder->pre_operation_tests_file)
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                                                <a href="{{ Storage::url($workOrder->pre_operation_tests_file) }}" 
                                                                   target="_blank" 
                                                                   class="text-decoration-none">
                                                                    عرض الملف
                                                                </a>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">لا يوجد مرفق</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>قيمة الضريبة</th>
                                                    <td>{{ $workOrder->tax_value ? number_format($workOrder->tax_value, 2) . ' ₪' : 'غير متوفر' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>رقم أمر الشراء</th>
                                                    <td>{{ $workOrder->purchase_order_number ?? 'غير متوفر' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>رقم المستخلص</th>
                                                    <td>{{ $workOrder->extract_number ?? 'غير متوفر' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>مرفقات الفاتورة</th>
                                                    <td>
                                                        @if($workOrder->invoiceAttachments->count() > 0)
                                                            <div class="row g-2">
                                                                @foreach($workOrder->invoiceAttachments as $attachment)
                                                                    <div class="col-12 col-md-6">
                                                                        <div class="card h-100 border-0 shadow-sm">
                                                                            <div class="card-body p-2">
                                                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                                                    <span class="badge bg-info">{{ $attachment->file_type ?? 'غير محدد' }}</span>
                                                                                    <a href="{{ Storage::url($attachment->file_path) }}" 
                                                                                       target="_blank" 
                                                                                       class="btn btn-sm btn-info"
                                                                                       title="عرض الملف">
                                                                                        <i class="fas fa-eye"></i>
                                                                                    </a>
                                                                                </div>
                                                                                <h6 class="card-title text-truncate mb-1" title="{{ $attachment->original_filename }}">
                                                                                    {{ $attachment->original_filename }}
                                                                                </h6>
                                                                                @if($attachment->description)
                                                                                    <p class="card-text small text-muted mb-1">{{ $attachment->description }}</p>
                                                                                @endif
                                                                                <small class="text-muted d-block">
                                                                                    {{ $attachment->created_at->format('Y-m-d H:i') }}
                                                                                </small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
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
                        </div>

                        <!-- المرفقات الأساسية -->
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h4 class="mb-0 fs-5">
                                        <i class="fas fa-paperclip text-primary me-2"></i>
                                        المرفقات الأساسية
                                    </h4>
                                </div>
                                <div class="card-body p-0">
                                    @if($workOrder->files->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="text-center" style="width: 60px">#</th>
                                                        <th>اسم الملف</th>
                                                        <th class="d-none d-md-table-cell">نوع الملف</th>
                                                        <th class="d-none d-md-table-cell">حجم الملف</th>
                                                        <th class="d-none d-lg-table-cell">تاريخ الرفع</th>
                                                        <th class="text-center" style="width: 100px">الإجراءات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $fileTypes = [
                                                            'license_estimate' => 'مقايسة الأعمال',
                                                            'daily_measurement' => 'مقايسة المواد',
                                                            'backup_1' => 'مرفق احتياطي '
                                                        ];
                                                    @endphp
                                                    @foreach($workOrder->files as $file)
                                                        @if(isset($fileTypes[$file->file_type]))
                                                            <tr>
                                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <i class="fas fa-file me-2 text-primary"></i>
                                                                        <span class="text-truncate" style="max-width: 200px;" title="{{ $file->original_filename }}">
                                                                            {{ $file->original_filename }}
                                                                        </span>
                                                                    </div>
                                                                    <div class="d-md-none small text-muted mt-1">
                                                                        {{ $fileTypes[$file->file_type] }} | {{ number_format($file->file_size / 1024 / 1024, 2) }} MB
                                                                    </div>
                                                                </td>
                                                                <td class="d-none d-md-table-cell">{{ $fileTypes[$file->file_type] }}</td>
                                                                <td class="d-none d-md-table-cell">{{ number_format($file->file_size / 1024 / 1024, 2) }} MB</td>
                                                                <td class="d-none d-lg-table-cell">{{ $file->created_at->format('Y-m-d H:i:s') }}</td>
                                                                <td class="text-center">
                                                                    <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                                                        <i class="fas fa-eye"></i>
                                                                        <span class="d-none d-sm-inline">عرض</span>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-info m-3">لا توجد مرفقات أساسية</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- معلومات النظام -->
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h4 class="mb-0 fs-5">
                                        <i class="fas fa-history text-primary me-2"></i>
                                        معلومات النظام
                                    </h4>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover mb-0">
                                            <tbody>
                                                <tr>
                                                    <th class="bg-light" style="width: 30%">تاريخ الإنشاء</th>
                                                    <td>{{ $workOrder->created_at->format('Y-m-d H:i:s') }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">آخر تحديث</th>
                                                    <td>{{ $workOrder->updated_at->format('Y-m-d H:i:s') }}</td>
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
    </div>
</div>

<style>
/* تحسينات عامة */
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

/* تنسيق الجداول */
.table {
    margin-bottom: 0;
}

.table th {
    font-weight: 600;
    white-space: nowrap;
}

.table td {
    vertical-align: middle;
}

/* تنسيق البطاقات */
.card-header {
    border-bottom: 1px solid rgba(0,0,0,.125);
}

.card-header h4 {
    font-weight: 600;
}

/* تنسيق الأزرار الجديدة */
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

/* ألوان الأزرار */
.btn-materials {
    background-color: #4CAF50;
    color: white;
}

.btn-materials:hover {
    background-color: #43A047;
    color: white;
}

.btn-survey {
    background-color: #2196F3;
    color: white;
}

.btn-survey:hover {
    background-color: #1E88E5;
    color: white;
}

.btn-license {
    background-color: #9C27B0;
    color: white;
}

.btn-license:hover {
    background-color: #8E24AA;
    color: white;
}

.btn-execution {
    background-color: #FF9800;
    color: white;
}

.btn-execution:hover {
    background-color: #FB8C00;
    color: white;
}

.btn-actions {
    background-color: #607D8B;
    color: white;
}

.btn-actions:hover {
    background-color: #546E7A;
    color: white;
}

.btn-edit {
    background-color: #00BCD4;
    color: white;
}

.btn-edit:hover {
    background-color: #00ACC1;
    color: white;
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

/* تحسين الأيقونات في الأزرار */
.btn i {
    transition: transform 0.3s ease;
}

.btn:hover i {
    transform: scale(1.1);
}

/* تحسين عرض الأزرار على الشاشات الصغيرة */
@media (max-width: 768px) {
    .btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
    }
    
    .btn i {
        margin-right: 0.25rem;
    }
    
    .btn span {
        display: none;
    }
}

@media (min-width: 769px) {
    .btn {
        min-width: 120px;
    }
}

/* تحسينات عامة */
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

/* تنسيق الجداول */
.table {
    margin-bottom: 0;
}

.table th {
    font-weight: 600;
    white-space: nowrap;
}

.table td {
    vertical-align: middle;
}

/* تنسيق البطاقات */
.card-header {
    border-bottom: 1px solid rgba(0,0,0,.125);
}

.card-header h4 {
    font-weight: 600;
}

/* تنسيق الأزرار */
.btn {
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
}

.btn-light {
    background-color: #f8f9fa;
    border-color: #f8f9fa;
}

.btn-light:hover {
    background-color: #e9ecef;
    border-color: #e9ecef;
}

/* تنسيق الشاشات الصغيرة */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .table-responsive {
        margin: 0 -1rem;
    }
    
    .btn-group {
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .btn {
        padding: 0.375rem 0.75rem;
    }
}

/* تنسيق البطاقات على الشاشات الصغيرة */
@media (max-width: 576px) {
    .card-header {
        padding: 0.75rem;
    }
    
    .card-body {
        padding: 0.75rem;
    }
    
    .table th, .table td {
        padding: 0.5rem;
    }
}

/* تحسين عرض البطاقات */
.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}

/* تحسين عرض الأيقونات */
.fas {
    width: 1.25em;
    text-align: center;
}

/* تحسين عرض النصوص الطويلة */
.text-truncate {
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
@endsection 