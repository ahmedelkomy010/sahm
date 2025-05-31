@extends('layouts.app')

@push('styles')
<style>
    .custom-btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s;
        color: #fff;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin: 0 2px;
    }

    .custom-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        color: #fff;
    }

    .btn-materials { background-color: #4CAF50; }
    .btn-materials:hover { background-color: #43A047; }
    
    .btn-survey { background-color: #2196F3; }
    .btn-survey:hover { background-color: #1E88E5; }
    
    .btn-license { background-color: #9C27B0; }
    .btn-license:hover { background-color: #8E24AA; }
    
    .btn-execution { background-color: #FF9800; }
    .btn-execution:hover { background-color: #FB8C00; }
    
    .btn-actions { background-color: #607D8B; }
    .btn-actions:hover { background-color: #546E7A; }
    
    .btn-back { background-color: #795548; }
    .btn-back:hover { background-color: #6D4C41; }

    .custom-card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        background-color: #fff;
    }

    .custom-card .card-header {
        background: linear-gradient(45deg, #f8f9fa, #ffffff);
        border-bottom: 1px solid #eee;
        padding: 15px 20px;
        border-radius: 8px 8px 0 0;
    }

    .custom-card .card-header h4 {
        margin: 0;
        color: #333;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .custom-table {
        width: 100%;
        margin-bottom: 0;
    }

    .custom-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        padding: 12px 15px;
        border: 1px solid #e9ecef;
    }

    .custom-table td {
        padding: 12px 15px;
        border: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .header-gradient {
        background: linear-gradient(45deg, #1976D2, #2196F3);
        color: white;
        padding: 20px;
        border-radius: 8px 8px 0 0;
    }

    .badge {
        padding: 6px 10px;
        border-radius: 4px;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .custom-btn {
            padding: 6px 12px;
            font-size: 14px;
        }
        .custom-btn i {
            margin-right: 4px;
        }
        .custom-btn span {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="custom-card">
                <div class="header-gradient">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <h3 class="mb-0 fs-4">تفاصيل أمر العمل</h3>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <a href="{{ route('admin.work-orders.materials', $workOrder) }}" class="custom-btn btn-materials">
                                <i class="fas fa-boxes me-1"></i> المواد
                            </a>
                            <a href="{{ route('admin.work-orders.survey', $workOrder) }}" class="custom-btn btn-survey">
                                <i class="fas fa-map-marked-alt me-1"></i> المسح
                            </a>
                            <a href="{{ route('admin.work-orders.license', $workOrder) }}" class="custom-btn btn-license">
                                <i class="fas fa-file-alt me-1"></i> الجودة
                            </a>
                            <a href="{{ route('admin.work-orders.execution', $workOrder) }}" class="custom-btn btn-execution">
                                <i class="fas fa-tools me-1"></i> تنفيذ
                            </a>
                            <a href="{{ route('admin.work-orders.actions-execution', $workOrder) }}" class="custom-btn btn-actions">
                                <i class="fas fa-cogs me-1"></i> إجراءات ما بعد التنفيذ
                            </a>
                            <a href="{{ route('admin.work-orders.edit', $workOrder) }}" class="custom-btn btn-edit">
                                <i class="fas fa-edit"></i> تعديل
                            </a>
                            <a href="{{ route('admin.work-orders.index') }}" class="custom-btn btn-back">
                                <i class="fas fa-arrow-right me-1"></i> عودة
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- معلومات أساسية -->
                        <div class="col-12 col-lg-6">
                            <div class="custom-card">
                                <div class="card-header">
                                    <h4>
                                        <i class="fas fa-info-circle text-primary me-2"></i>
                                        معلومات أساسية
                                    </h4>
                                </div>
                                <div class="card-body p-0">
                                    <table class="custom-table">
                                        <tbody>
                                            <tr>
                                                <th style="width: 40%">رقم المسلسل</th>
                                                <td>{{ $workOrder->id }}</td>
                                            </tr>
                                            <tr>
                                                <th>رقم الطلب</th>
                                                <td>{{ $workOrder->order_number }}</td>
                                            </tr>
                                            <tr>
                                                <th>رقم نوع العمل</th>
                                                <td>{{ $workOrder->work_type }}</td>
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
                                                            <span class="badge bg-info">جاري العمل ...</span>
                                                            @break
                                                        @case('2')
                                                            <span class="badge bg-warning">تم تسليم 155 ولم تصدر شهادة انجاز</span>
                                                            @break
                                                        @case('3')
                                                            <span class="badge bg-primary">صدرت شهادة ولم تعتمد</span>
                                                            @break
                                                        @case('4')
                                                            <span class="badge bg-secondary">تم اعتماد شهادة الانجاز</span>
                                                            @break
                                                        @case('5')
                                                            <span class="badge bg-success">مؤكد ولم تدخل مستخلص</span>
                                                            @break
                                                        @case('6')
                                                            <span class="badge bg-dark">دخلت مستخلص ولم تصرف</span>
                                                            @break
                                                        @case('7')
                                                            <span class="badge bg-dark">منتهي تم الصرف</span>
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

                        <!-- المعلومات المالية -->
                        <div class="col-12 col-lg-6">
                            <div class="custom-card">
                                <div class="card-header">
                                    <h4>
                                        <i class="fas fa-money-bill-wave text-primary me-2"></i>
                                        المعلومات المالية
                                    </h4>
                                </div>
                                <div class="card-body p-0">
                                    <table class="custom-table">
                                        <tbody>
                                            <tr>
                                                <th style="width: 40%">قيمة أمر العمل المبدئية شامل الاستشاري</th>
                                                <td>{{ number_format($workOrder->order_value_with_consultant, 2) }} ريال</td>
                                            </tr>
                                            <tr>
                                                <th>قيمة أمر العمل المبدئية غير شامل الاستشاري</th>
                                                <td>{{ number_format($workOrder->order_value_without_consultant, 2) }} ريال</td>
                                            </tr>
                                            <tr>
                                                <th>قيمة التنفيذ الفعلي شامل الاستشاري</th>
                                                <td>{{ $workOrder->actual_execution_value_consultant ? number_format($workOrder->actual_execution_value_consultant, 2) . ' ريال' : 'غير متوفر' }}</td>
                                            </tr>
                                            <tr>
                                                <th>قيمة التنفيذ الفعلي غير شامل الاستشاري</th>
                                                <td>{{ $workOrder->actual_execution_value_without_consultant ? number_format($workOrder->actual_execution_value_without_consultant, 2) . ' ريال' : 'غير متوفر' }}</td>
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
                                            @if($workOrder->license)
                                            <tr>
                                                <th>قيمة رخصة الحفر</th>
                                                <td>{{ $workOrder->license->license_value ? number_format($workOrder->license->license_value, 2) . ' ريال' : 'غير متوفر' }}</td>
                                            </tr>
                                            <tr>
                                                <th>قيمة التمديدات</th>
                                                <td>{{ $workOrder->license->extension_value ? number_format($workOrder->license->extension_value, 2) . ' ريال' : 'غير متوفر' }}</td>
                                            </tr>
                                            @endif
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

                        <!-- المرفقات الأساسية -->
                        <div class="col-12">
                            <div class="custom-card">
                                <div class="card-header">
                                    <h4>
                                        <i class="fas fa-paperclip text-primary me-2"></i>
                                        المرفقات الأساسية
                                    </h4>
                                </div>
                                <div class="card-body p-0">
                                    @if($workOrder->files->count() > 0)
                                        <table class="custom-table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 60px">#</th>
                                                    <th>اسم الملف</th>
                                                    <th>نوع الملف</th>
                                                    <th>تاريخ الرفع</th>
                                                    <th class="text-center" style="width: 100px">الإجراءات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($workOrder->files as $file)
                                                    <tr>
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-file me-2 text-primary"></i>
                                                                <span class="text-truncate" style="max-width: 200px;" title="{{ $file->original_filename }}">
                                                                    {{ $file->original_filename }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td>{{ $file->file_type }}</td>
                                                        <td>{{ $file->created_at->format('Y-m-d H:i:s') }}</td>
                                                        <td class="text-center">
                                                            <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="custom-btn btn-info btn-sm">
                                                                <i class="fas fa-eye"></i>
                                                                <span class="d-none d-sm-inline">عرض</span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="alert alert-info m-3">لا توجد مرفقات أساسية</div>
                                    @endif
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