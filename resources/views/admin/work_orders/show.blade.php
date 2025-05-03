@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 fs-4">تفاصيل أمر العمل</h3>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.work-orders.materials', $workOrder) }}" class="btn btn-light btn-sm px-3" style="min-width: 100px;">
                            <i class="fas fa-boxes me-1"></i> المواد
                        </a>
                        <a href="{{ route('admin.work-orders.survey', $workOrder) }}" class="btn btn-light btn-sm px-3" style="min-width: 100px;">
                            <i class="fas fa-map-marked-alt me-1"></i> المسح
                        </a>
                        <a href="{{ route('admin.work-orders.license', $workOrder) }}" class="btn btn-light btn-sm px-3" style="min-width: 100px;">
                            <i class="fas fa-file-alt me-1"></i> رخص
                        </a>
                        <a href="{{ route('admin.work-orders.execution', $workOrder) }}" class="btn btn-light btn-sm px-3" style="min-width: 100px;">
                            <i class="fas fa-tools me-1"></i> تنفيذ
                        </a>
                        <a href="{{ route('admin.work-orders.post-execution', $workOrder) }}" class="btn btn-light btn-sm px-3" style="min-width: 120px;">
                            <i class="fas fa-clipboard-check me-1"></i> إجراءات ما بعد التنفيذ
                        </a>
                        <a href="{{ route('admin.work-orders.edit', $workOrder) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <a href="{{ route('admin.work-orders.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-right"></i> عودة
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="border-bottom pb-2 mb-3">معلومات أساسية</h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width: 40%"> رقم المسلسل</th>
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
                                                @case('1')
                                                    تمديد شبكات المياه
                                                    @break
                                                @case('2')
                                                    تمديد شبكات الصرف الصحي
                                                    @break
                                                @case('3')
                                                    صيانة شبكات المياه
                                                    @break
                                                @case('4')
                                                    صيانة شبكات الصرف الصحي
                                                    @break
                                                @case('5')
                                                    إنشاء محطات الضخ
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

                        <div class="col-md-6">
                            <h4 class="border-bottom pb-2 mb-3">المعلومات المالية والإجراءات</h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width: 40%">قيمة أمر العمل شامل الاستشاري</th>
                                        <td>{{ number_format($workOrder->order_value_with_consultant, 2) }} ₪</td>
                                    </tr>
                                    <tr>
                                        <th>قيمة أمر العمل بدون استشاري</th>
                                        <td>{{ number_format($workOrder->order_value_without_consultant, 2) }} ₪</td>
                                    </tr>
                                    <tr>
                                        <th>قيمة التنفيذ الفعلي</th>
                                        <td>{{ $workOrder->actual_execution_value ? number_format($workOrder->actual_execution_value, 2) . ' ₪' : 'غير متوفر' }}</td>
                                    </tr>
                                    <tr>
                                        <th>تاريخ تسليم إجراء 155</th>
                                        <td>{{ $workOrder->procedure_155_delivery_date ? $workOrder->procedure_155_delivery_date->format('Y-m-d') : 'غير متوفر' }}</td>
                                    </tr>
                                    <tr>
                                        <th>تاريخ إجراء 211</th>
                                        <td>{{ $workOrder->procedure_211_date ? $workOrder->procedure_211_date->format('Y-m-d') : 'غير متوفر' }}</td>
                                    </tr>
                                    <tr>
                                        <th>حذف جزئي</th>
                                        <td>{{ $workOrder->partial_deletion ? 'نعم' : 'لا' }}</td>
                                    </tr>
                                    <tr>
                                        <th>قيمة الدفعة الجزئية</th>
                                        <td>{{ $workOrder->partial_payment_value ? number_format($workOrder->partial_payment_value, 2) . ' ₪' : 'غير متوفر' }}</td>
                                    </tr>
                                    <tr>
                                        <th>رقم المستخلص</th>
                                        <td>{{ $workOrder->extract_number ?? 'غير متوفر' }}</td>
                                    </tr>
                                    <tr>
                                        <th>رقم الفاتورة</th>
                                        <td>{{ $workOrder->invoice_number ?? 'غير متوفر' }}</td>
                                    </tr>
                                    <tr>
                                        <th>رقم أمر الشراء</th>
                                        <td>{{ $workOrder->purchase_order_number ?? 'غير متوفر' }}</td>
                                    </tr>
                                    <tr>
                                        <th>قيمة الضريبة</th>
                                        <td>{{ $workOrder->tax_value ? number_format($workOrder->tax_value, 2) . ' ₪' : 'غير متوفر' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h4 class="border-bottom pb-2 mb-3">المرفقات</h4>
                            
                            @if($workOrder->files->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>اسم الملف</th>
                                                <th>نوع الملف</th>
                                                <th>حجم الملف</th>
                                                <th>تاريخ الرفع</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($workOrder->files as $index => $file)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $file->original_filename }}</td>
                                                    <td>{{ pathinfo($file->filename, PATHINFO_EXTENSION) }}</td>
                                                    <td>{{ round($file->file_size / 1024, 2) }} KB</td>
                                                    <td>{{ $file->created_at->format('Y-m-d H:i') }}</td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ asset('storage/' . $file->file_path) }}" class="btn btn-sm btn-info" target="_blank">عرض</a>
                                                            <a href="{{ asset('storage/' . $file->file_path) }}" class="btn btn-sm btn-success" download>تحميل</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    لا توجد ملفات مرفقة لهذا الأمر
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h4 class="border-bottom pb-2 mb-3">معلومات النظام</h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width: 30%">تاريخ الإنشاء</th>
                                        <td>{{ $workOrder->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>آخر تحديث</th>
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

<style>
    .table th, .table td {
        text-align: right;
    }
    
    .table th {
        background-color: rgba(0, 0, 0, 0.03);
    }
    
    .btn-group {
        display: flex;
    }
    
    .btn-group .btn {
        margin-left: 5px;
    }
</style>
@endsection 