@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0" style="font-size:2.1rem; font-weight:800; color:#2c3e50; letter-spacing:1px; display:flex; align-items:center;">
            <i class="fas fa-tasks me-2" style="color:#007bff;"></i>
            إجراءات ما بعد التنفيذ
        </h2>
        <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-primary">
                            <i class="fas fa-arrow-right"></i> عودة الي تفاصيل أمر العمل  
                        </a>    </div>

    <!-- معلومات أمر العمل -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-hashtag text-primary me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">رقم أمر العمل</small>
                                    <strong class="text-primary fs-6">{{ $workOrder->order_number }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-tools text-warning me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">نوع العمل</small>
                                    <strong class="fs-6">{{ $workOrder->work_type }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user text-info me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">اسم المشترك</small>
                                    <strong class="fs-6">{{ $workOrder->subscriber_name }}</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-flag-checkered text-success me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">حالة التنفيذ</small>
                                    <strong class="text-success fs-6">
                                        @switch($workOrder->execution_status)
                                            @case(2)
                                                تم تسليم 155
                                                @break
                                            @case(1)
                                                جاري العمل
                                                @break
                                            @case(3)
                                                صدرت شهادة
                                                @break
                                            @case(4)
                                                تم اعتماد الشهادة
                                                @break
                                            @case(5)
                                                مؤكد
                                                @break
                                            @case(6)
                                                دخل مستخلص
                                                @break
                                            @case(7)
                                                منتهي
                                                @break
                                            @default
                                                غير محدد
                                        @endswitch
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- رسائل التنبيه --}}
    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger text-center">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- جدول بنود العمل --}}
    @if($hasWorkItems && $workOrder->workOrderItems->count() > 0)
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list me-2"></i>بنود العمل</span>
            <span class="badge bg-light text-dark">{{ $workOrder->workOrderItems->count() }} بند</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 4%;">#</th>
                            <th class="text-center" style="width: 8%;">كود البند</th>
                            <th style="width: 25%;">اسم البند</th>
                            <th class="text-center" style="width: 8%;">الوحدة</th>
                            <th class="text-center" style="width: 10%;">الكمية المخططة</th>
                            <th class="text-center" style="width: 10%;">الكمية المنفذة</th>
                            <th class="text-center" style="width: 10%;">سعر الوحدة</th>
                            <th class="text-center" style="width: 12%;">القيمة المخططة</th>
                            <th class="text-center" style="width: 13%;">القيمة المنفذة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalPlannedValue = 0;
                            $totalExecutedValue = 0;
                        @endphp
                        @foreach($workOrder->workOrderItems as $index => $item)
                            @php
                                $plannedValue = $item->quantity * $item->unit_price;
                                $executedValue = ($item->executed_quantity ?? 0) * $item->unit_price;
                                $totalPlannedValue += $plannedValue;
                                $totalExecutedValue += $executedValue;
                            @endphp
                            <tr>
                                <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $item->workItem->code ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-primary">{{ $item->workItem->name ?? 'غير محدد' }}</div>
                                    @if($item->workItem->description)
                                        <small class="text-muted d-block">{{ $item->workItem->description }}</small>
                                    @endif
                                    @if($item->notes)
                                        <small class="text-info d-block"><i class="fas fa-sticky-note me-1"></i>{{ $item->notes }}</small>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->unit ?? $item->workItem->unit ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ number_format($item->quantity, 2) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="executed-quantity-container" data-item-id="{{ $item->id }}">
                                        <span class="executed-quantity-display" onclick="editExecutedQuantity({{ $item->id }})">
                                            @if($item->executed_quantity)
                                                <span class="badge bg-success" style="cursor: pointer;" title="انقر للتعديل">
                                                    {{ number_format($item->executed_quantity, 2) }}
                                                    <i class="fas fa-edit ms-1" style="font-size: 0.8em;"></i>
                                                </span>
                                            @else
                                                <span class="badge bg-secondary" style="cursor: pointer;" title="انقر للتعديل">
                                                    لم يتم التنفيذ
                                                    <i class="fas fa-edit ms-1" style="font-size: 0.8em;"></i>
                                                </span>
                                            @endif
                                        </span>
                                        <div class="executed-quantity-edit d-none">
                                            <div class="input-group input-group-sm">
                                                <input type="number" 
                                                       class="form-control form-control-sm" 
                                                       step="0.01" 
                                                       min="0"
                                                       value="{{ $item->executed_quantity ?? 0 }}"
                                                       id="executed_quantity_{{ $item->id }}">
                                                <button class="btn btn-success btn-sm" 
                                                        onclick="saveExecutedQuantity({{ $item->id }})"
                                                        title="حفظ">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-secondary btn-sm" 
                                                        onclick="cancelEditExecutedQuantity({{ $item->id }})"
                                                        title="إلغاء">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-success">{{ number_format($item->unit_price, 2) }} ر.س</span>
                                </td>
                                <td class="text-center">
                                    <div class="fw-bold text-primary">{{ number_format($plannedValue, 2) }} ر.س</div>
                                </td>
                                <td class="text-center">
                                    @if($item->executed_quantity)
                                        <div class="fw-bold text-success">{{ number_format($executedValue, 2) }} ر.س</div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="7" class="text-end">إجمالي القيمة المخططة:</th>
                            <th class="text-center">{{ number_format($totalPlannedValue, 2) }} ر.س</th>
                            <th class="text-center">-</th>
                        </tr>
                        <tr>
                            <th colspan="7" class="text-end">إجمالي القيمة المنفذة:</th>
                            <th class="text-center">-</th>
                            <th class="text-center">{{ number_format($totalExecutedValue, 2) }} ر.س</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif


    {{-- جدول المواد المرتبطة --}}
    @if($workOrder->materials->count() > 0)
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <span><i class="fas fa-boxes me-2"></i>المواد المرتبطة</span>
            <span class="badge bg-light text-dark">{{ $workOrder->materials->count() }} مادة</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 5%;">#</th>
                            <th class="text-center" style="width: 10%;">كود المادة</th>
                            <th style="width: 30%;">اسم المادة</th>
                            <th class="text-center" style="width: 10%;">الوحدة</th>
                            <th class="text-center" style="width: 12%;">الكمية المخططة</th>
                            <th class="text-center" style="width: 12%;">الكمية المنفذة</th>
                            <th class="text-center" style="width: 12%;">الكمية المصروفة</th>
                            <th class="text-center" style="width: 9%;">الفرق</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($workOrder->materials as $index => $material)
                            <tr>
                                <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $material->code ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-primary">{{ $material->name ?? 'غير محدد' }}</div>
                                    @if($material->description)
                                        <small class="text-muted d-block">{{ $material->description }}</small>
                                    @endif
                                </td>
                                <td class="text-center">{{ $material->unit ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ number_format($material->planned_quantity, 2) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="executed-quantity-container" data-material-id="{{ $material->id }}">
                                        <span class="executed-quantity-display">
                                            @if($material->executed_quantity)
                                                <span class="badge bg-success" style="cursor: pointer;" title="انقر للتعديل">
                                                    {{ number_format($material->executed_quantity, 2) }}
                                                    <i class="fas fa-edit ms-1" style="font-size: 0.8em;"></i>
                                                </span>
                                            @else
                                                <span class="badge bg-secondary" style="cursor: pointer;" title="انقر للتعديل">
                                                    لم يتم التنفيذ
                                                    <i class="fas fa-edit ms-1" style="font-size: 0.8em;"></i>
                                                </span>
                                            @endif
                                        </span>
                                        <div class="executed-quantity-edit d-none">
                                            <div class="input-group input-group-sm">
                                                <input type="number" 
                                                       class="form-control form-control-sm" 
                                                       step="0.01" 
                                                       min="0"
                                                       value="{{ $material->executed_quantity ?? 0 }}"
                                                       id="material_executed_quantity_{{ $material->id }}">
                                                <button class="btn btn-success btn-sm" 
                                                        onclick="saveMaterialExecutedQuantity({{ $material->id }})"
                                                        title="حفظ">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-secondary btn-sm" 
                                                        onclick="cancelEditMaterialExecutedQuantity({{ $material->id }})"
                                                        title="إلغاء">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="spent-quantity-container" data-material-id="{{ $material->id }}">
                                        <span class="spent-quantity-display">
                                            @if($material->spent_quantity)
                                                <span class="badge bg-warning text-dark" style="cursor: pointer;" title="انقر للتعديل">
                                                    {{ number_format($material->spent_quantity, 2) }}
                                                    <i class="fas fa-edit ms-1" style="font-size: 0.8em;"></i>
                                                </span>
                                            @else
                                                <span class="badge bg-secondary" style="cursor: pointer;" title="انقر للتعديل">
                                                    لم يتم الصرف
                                                    <i class="fas fa-edit ms-1" style="font-size: 0.8em;"></i>
                                                </span>
                                            @endif
                                        </span>
                                        <div class="spent-quantity-edit d-none">
                                            <div class="input-group input-group-sm">
                                                <input type="number" 
                                                       class="form-control form-control-sm" 
                                                       step="0.01" 
                                                       min="0"
                                                       value="{{ $material->spent_quantity ?? 0 }}"
                                                       id="material_spent_quantity_{{ $material->id }}">
                                                <button class="btn btn-success btn-sm" 
                                                        onclick="saveMaterialSpentQuantity({{ $material->id }})"
                                                        title="حفظ">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-secondary btn-sm" 
                                                        onclick="cancelEditMaterialSpentQuantity({{ $material->id }})"
                                                        title="إلغاء">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @php
                                        $difference = $material->quantity_difference;
                                    @endphp
                                    @if($difference > 0)
                                        <span class="badge bg-info">+{{ number_format($difference, 2) }}</span>
                                    @elseif($difference < 0)
                                        <span class="badge bg-danger">{{ number_format($difference, 2) }}</span>
                                    @else
                                        <span class="badge bg-success">متطابق</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- كارد الحقول النصية --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">بيانات أوامر الشراء والإدخال</div>
        <div class="card-body">
            <form action="{{ route('admin.work-orders.update', $workOrder->id) }}" method="POST" class="row g-2 align-items-center mb-3">
                @csrf
                @method('PUT')
                <input type="hidden" name="_section" value="extract_number_group">
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">رقم أمر الشراء:</label>
                    <input type="text" name="purchase_order_number" class="form-control" value="{{ old('purchase_order_number', $workOrder->purchase_order_number) }}">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">صحيفة الإدخال 1:</label>
                    <input type="text" name="entry_sheet_1" class="form-control" value="{{ $workOrder->entry_sheet_1 ?? '' }}" placeholder="رقم صحيفة الإدخال الأولى">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">صحيفة الإدخال 2:</label>
                    <input type="text" name="entry_sheet_2" class="form-control" value="{{ $workOrder->entry_sheet_2 ?? '' }}" placeholder="رقم صحيفة الإدخال الثانية">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">رقم المستخلص:</label>
                    <input type="text" name="extract_number" class="form-control" value="{{ old('extract_number', $workOrder->extract_number) }}">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">قيمة التنفيذ الفعلي للاستشاري:</label>
                    <input type="number" step="0.01" name="actual_execution_value_consultant" class="form-control" value="{{ old('actual_execution_value_consultant', $workOrder->actual_execution_value_consultant) }}">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">قيمة التنفيذ الفعلي غير شامل الاستشاري:</label>
                    <input type="number" step="0.01" name="actual_execution_value_without_consultant" class="form-control" value="{{ old('actual_execution_value_without_consultant', $workOrder->actual_execution_value_without_consultant) }}">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">قيمة الدفعة الجزئية الأولى غير شامل الضريبة:</label>
                    <input type="number" step="0.01" name="first_partial_payment_without_tax" class="form-control" value="{{ old('first_partial_payment_without_tax', $workOrder->first_partial_payment_without_tax) }}">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">قيمة الدفعة الجزئية الثانية غير شامل الضريبة:</label>
                    <input type="number" step="0.01" name="second_partial_payment_with_tax" class="form-control" value="{{ old('second_partial_payment_with_tax', $workOrder->second_partial_payment_with_tax) }}">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">قيمة الضريبة:</label>
                    <input type="number" step="0.01" name="tax_value" class="form-control" value="{{ old('tax_value', $workOrder->tax_value) }}">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">تاريخ تسليم إجراء 155:</label>
                    <input type="date" name="procedure_155_delivery_date" class="form-control" value="{{ old('procedure_155_delivery_date', $workOrder->procedure_155_delivery_date ? $workOrder->procedure_155_delivery_date->format('Y-m-d') : '') }}">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">القيمة الكلية النهائية غير شامل الضريبة:</label>
                    <input type="number" step="0.01" name="final_total_value" class="form-control" value="{{ old('final_total_value', $workOrder->final_total_value) }}">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">اختبارات ما قبل التشغيل:</label>
                    <input type="text" name="pre_operation_tests" class="form-control" value="{{ old('pre_operation_tests', $workOrder->pre_operation_tests) }}">
                </div>
                <div class="form-group mb-3">
                                    <label for="execution_status" class="form-label fw-bold">حالة تنفيذ أمر العمل</label>
                                    <select id="execution_status" class="form-select @error('execution_status') is-invalid @enderror" name="execution_status">
                                        <option value="1" {{ old('execution_status', $workOrder->execution_status) == '1' ? 'selected' : '' }}>جاري العمل ...</option>
                                        <option value="2" {{ old('execution_status', $workOrder->execution_status) == '2' ? 'selected' : '' }}> تم تسليم 155 ولم تصدر شهادة انجاز </option>
                                        <option value="3" {{ old('execution_status', $workOrder->execution_status) == '3' ? 'selected' : '' }}> صدرت شهادة ولم تعتمد</option>
                                        <option value="4" {{ old('execution_status', $workOrder->execution_status) == '4' ? 'selected' : '' }}> تم اعتماد شهادة الانجاز</option>
                                        <option value="5" {{ old('execution_status', $workOrder->execution_status) == '5' ? 'selected' : '' }}>مؤكد ولم تدخل مستخلص </option>
                                        <option value="6" {{ old('execution_status', $workOrder->execution_status) == '6' ? 'selected' : '' }}> دخلت مستخلص ولم تصرف </option>
                                        <option value="7" {{ old('execution_status', $workOrder->execution_status) == '7' ? 'selected' : '' }}> منتهي تم الصرف </option>
                                    </select>
                                    @error('execution_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                <div class="col-12 text-center mt-3">
                    <button type="submit" class="btn btn-primary px-4">حفظ البيانات</button>
                </div>
            </form>
        </div>
    </div>

    {{-- كارد المرفقات --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">المرفقات</div>
        <div class="card-body">
            <!-- فورم رفع الملفات -->
            <form id="uploadFilesForm" action="{{ route('admin.work-orders.upload-post-execution-file', $workOrder) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    @php
                        $fileTypes = [
                            'quantities_statement_file' => 'بيان كميات التنفيذ',
                            'final_materials_file' => 'كميات المواد النهائية',
                            'final_measurement_file' => 'ورقة القياس النهائية',
                            'soil_tests_file' => 'اختبارات التربة',
                            'site_drawing_file' => 'الرسم الهندسي للموقع',
                            'modified_estimate_file' => 'تعديل المقايسة',
                            'completion_certificate_file' => 'شهادة الانجاز',
                            'form_200_file' => 'نموذج 200',
                            'form_190_file' => 'نموذج 190',
                            'pre_operation_tests_file' => 'اختبارات ما قبل التشغيل 211',
                            'first_payment_extract_file' => 'مستخلص دفعة أولى',
                            'second_payment_extract_file' => 'مستخلص دفعة ثانية',
                            'total_extract_file' => 'مستخلص كلي',
                        ];
                    @endphp
                    @foreach($fileTypes as $field => $label)
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ $label }}</label>
                            <input type="file" name="{{ $field }}[]" class="form-control" accept=".pdf,.jpg,.jpeg,.png" multiple>
                            <small class="text-muted">يمكن اختيار أكثر من ملف</small>
                            @error($field)
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary px-4">رفع الملفات</button>
                </div>
            </form>

            <!-- جدول عرض المرفقات -->
            <div class="table-responsive mt-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>نوع المرفق</th>
                            <th>الحالة الحالية</th>
                            <th>عرض الملف</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fileTypes as $field => $label)
                            @php
                                $files = $workOrder->files->where('file_category', 'post_execution_files')
                                    ->where('attachment_type', $field);
                            @endphp
                            <tr>
                                <td>{{ $label }}</td>
                                <td>
                                    @if($files->count() > 0)
                                        <span class="badge bg-success">{{ $files->count() }} ملف</span>
                                        <small class="d-block text-muted mt-1">آخر رفع: {{ $files->sortByDesc('created_at')->first()->created_at->format('Y-m-d H:i') }}</small>
                                    @else
                                        <span class="badge bg-secondary">لم يتم الرفع</span>
                                    @endif
                                </td>
                                <td>
                                    @if($files->count() > 0)
                                        <div class="btn-group" role="group">
                                            @foreach($files->sortByDesc('created_at')->take(3) as $index => $file)
                                                <a href="{{ Storage::url($file->file_path) }}" target="_blank" 
                                                   class="btn btn-sm btn-info" title="{{ $file->original_filename ?? 'ملف ' . ($index + 1) }}">
                                                    <i class="fas fa-file-pdf"></i> {{ $index + 1 }}
                                                </a>
                                            @endforeach
                                            @if($files->count() > 3)
                                                <button class="btn btn-sm btn-outline-info" onclick="showAllFiles('{{ $field }}', '{{ $label }}')">
                                                    <i class="fas fa-ellipsis-h"></i> +{{ $files->count() - 3 }}
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- صور التنفيذ --}}
    @if(isset($executionImages) && $executionImages->count())
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <span>ملفات التنفيذ (الأعمال المدنية، التركيبات، الكهرباء)</span>
            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#viewAllImagesModal">
                <i class="fas fa-folder-open"></i> عرض جميع الملفات
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($executionImages as $img)
                    <div class="col-6 col-md-3 col-lg-2 mb-3">
                        <div class="card h-100">
                            @php
                                // تحديد مسار الصورة بناءً على نوع الملف
                                $imagePath = isset($img->file_category) && $img->file_category === 'installations_json' 
                                    ? asset('storage/' . $img->file_path) 
                                    : Storage::url($img->file_path);
                                $isPdf = strtolower(pathinfo($img->file_path, PATHINFO_EXTENSION)) === 'pdf';
                            @endphp
                            
                            @if($isPdf)
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 120px;">
                                    <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                </div>
                            @else
                                <img src="{{ $imagePath }}" 
                                     class="card-img-top" 
                                     style="height: 120px; object-fit: cover;"
                                     alt="صورة التنفيذ"
                                     data-bs-toggle="modal" 
                                     data-bs-target="#viewImageModal"
                                     data-image-url="{{ $imagePath }}"
                                     data-image-name="{{ $img->original_filename }}"
                                     data-image-date="{{ $img->created_at->format('Y-m-d H:i') }}"
                                     style="cursor: pointer;">
                            @endif
                            
                            <div class="card-body p-2">
                                <small class="text-muted d-block text-truncate">{{ $img->original_filename }}</small>
                                <small class="text-muted d-block">{{ $img->created_at->format('Y-m-d H:i') }}</small>
                                @if($isPdf)
                                    <a href="{{ $imagePath }}" target="_blank" class="btn btn-sm btn-primary w-100 mt-1">
                                        <i class="fas fa-eye me-1"></i> عرض
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Modal لعرض صورة واحدة --}}
    <div class="modal fade" id="viewImageModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">عرض الصورة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalImage" class="img-fluid" alt="صورة التنفيذ">
                    <div class="mt-2">
                        <small class="text-muted" id="modalImageName"></small><br>
                        <small class="text-muted" id="modalImageDate"></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal لعرض جميع الصور --}}
    <div class="modal fade" id="viewAllImagesModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">جميع ملفات التنفيذ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @foreach($executionImages as $img)
                            <div class="col-6 col-md-4 col-lg-3 mb-3">
                                <div class="card h-100">
                                    @php
                                        // تحديد مسار الصورة بناءً على نوع الملف
                                        $imagePath = isset($img->file_category) && $img->file_category === 'installations_json' 
                                            ? asset('storage/' . $img->file_path) 
                                            : Storage::url($img->file_path);
                                        $isPdf = strtolower(pathinfo($img->file_path, PATHINFO_EXTENSION)) === 'pdf';
                                    @endphp
                                    
                                    @if($isPdf)
                                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 150px;">
                                            <i class="fas fa-file-pdf fa-4x text-danger"></i>
                                        </div>
                                    @else
                                        <img src="{{ $imagePath }}" 
                                             class="card-img-top" 
                                             style="height: 150px; object-fit: cover; cursor: pointer;"
                                             onclick="openImageModal(this)"
                                             data-image-url="{{ $imagePath }}"
                                             data-image-name="{{ $img->original_filename }}"
                                             data-image-date="{{ $img->created_at->format('Y-m-d H:i') }}"
                                             alt="صورة التنفيذ">
                                    @endif
                                    
                                    <div class="card-body p-2">
                                        <small class="text-muted d-block text-truncate">{{ $img->original_filename }}</small>
                                        <small class="text-muted d-block">{{ $img->created_at->format('Y-m-d H:i') }}</small>
                                        @if($isPdf)
                                            <a href="{{ $imagePath }}" target="_blank" class="btn btn-sm btn-primary w-100 mt-1">
                                                <i class="fas fa-eye me-1"></i> عرض PDF
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- مرفقات الفاتورة --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span>مرفقات الفاتورة</span>
            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#uploadInvoiceAttachmentsModal">
                <i class="fas fa-upload"></i> رفع مرفقات جديدة
            </button>
        </div>
        <div class="card-body">
            @if($workOrder->invoiceAttachments->count() > 0)
                <div class="row">
                    @foreach($workOrder->invoiceAttachments as $attachment)
                        <div class="col-6 col-md-4 col-lg-3 mb-3">
                            <div class="card h-100">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-info">{{ $attachment->file_type ?? 'غير محدد' }}</span>
                                        <div class="btn-group">
                                            <a href="{{ Storage::url($attachment->file_path) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-info"
                                               title="عرض الملف">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
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
                <div class="text-center text-muted py-4">
                    <i class="fas fa-file-invoice fa-3x mb-3"></i>
                    <p class="mb-0">لا توجد مرفقات للفاتورة</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal رفع مرفقات جديدة --}}
    <div class="modal fade" id="uploadInvoiceAttachmentsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">رفع مرفقات الفاتورة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.work-orders.invoice-attachments.store', $workOrder) }}" 
                      method="POST" 
                      enctype="multipart/form-data"
                      id="uploadInvoiceAttachmentsForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اختر الملفات (يمكن اختيار أكثر من ملف)</label>
                            <input type="file" 
                                   name="attachments[]" 
                                   class="form-control" 
                                   multiple 
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   required>
                            <small class="text-muted">يمكن رفع حتى 20 ملف. الأنواع المدعومة: PDF, JPG, JPEG, PNG</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">نوع المرفقات</label>
                            <input type="text" 
                                   name="file_type" 
                                   class="form-control" 
                                   placeholder="مثال: فاتورة، إشعار، مستند..."
                                   required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">وصف المرفقات (اختياري)</label>
                            <textarea name="description" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="أدخل وصفاً مختصراً للمرفقات..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> رفع المرفقات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>





    @push('scripts')
    <script>
        // تحديث بيانات الصورة في المودال عند النقر على صورة
        document.querySelectorAll('[data-bs-target="#viewImageModal"]').forEach(img => {
            img.addEventListener('click', function() {
                const modal = document.getElementById('viewImageModal');
                modal.querySelector('#modalImage').src = this.dataset.imageUrl;
                modal.querySelector('#modalImageName').textContent = this.dataset.imageName;
                modal.querySelector('#modalImageDate').textContent = this.dataset.imageDate;
            });
        });



        // دالة لفتح مودال الصورة من عرض جميع الصور
        function openImageModal(img) {
            const modal = document.getElementById('viewImageModal');
            modal.querySelector('#modalImage').src = img.dataset.imageUrl;
            modal.querySelector('#modalImageName').textContent = img.dataset.imageName;
            modal.querySelector('#modalImageDate').textContent = img.dataset.imageDate;
            new bootstrap.Modal(modal).show();
        }



        // التحقق من عدد الملفات قبل الرفع
        document.getElementById('uploadInvoiceAttachmentsForm').addEventListener('submit', function(e) {
            const fileInput = this.querySelector('input[type="file"]');
            if (fileInput.files.length > 20) {
                e.preventDefault();
                alert('يمكن رفع 20 ملف كحد أقصى');
            }
        });

        document.getElementById('uploadFilesForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تم رفع الملفات بنجاح!');
                    location.reload();
                } else {
                    alert(data.message || 'حدث خطأ أثناء رفع الملفات');
                }
            })
            .catch(error => {
                alert('حدث خطأ أثناء رفع الملفات');
                console.error(error);
            });
        });

        // دوال تعديل الكمية المنفذة
        function editExecutedQuantity(itemId) {
            const container = document.querySelector(`[data-item-id="${itemId}"]`);
            const display = container.querySelector('.executed-quantity-display');
            const edit = container.querySelector('.executed-quantity-edit');
            
            display.classList.add('d-none');
            edit.classList.remove('d-none');
            
            const input = document.getElementById(`executed_quantity_${itemId}`);
            input.focus();
            input.select();
        }

        function cancelEditExecutedQuantity(itemId) {
            const container = document.querySelector(`[data-item-id="${itemId}"]`);
            const display = container.querySelector('.executed-quantity-display');
            const edit = container.querySelector('.executed-quantity-edit');
            
            edit.classList.add('d-none');
            display.classList.remove('d-none');
        }

        function saveExecutedQuantity(itemId) {
            const input = document.getElementById(`executed_quantity_${itemId}`);
            const newQuantity = parseFloat(input.value) || 0;
            
            // إظهار loading
            const saveBtn = document.querySelector(`button[onclick="saveExecutedQuantity(${itemId})"]`);
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            saveBtn.disabled = true;
            
            // إرسال طلب التحديث
            fetch(`{{ url('admin/work-orders/update-work-item') }}/${itemId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    executed_quantity: newQuantity,
                    work_date: new Date().toISOString().split('T')[0] // تاريخ اليوم
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // تحديث العرض
                    const container = document.querySelector(`[data-item-id="${itemId}"]`);
                    const display = container.querySelector('.executed-quantity-display');
                    const edit = container.querySelector('.executed-quantity-edit');
                    
                    // تحديث النص المعروض
                    if (newQuantity > 0) {
                        display.innerHTML = `<span class="badge bg-success" style="cursor: pointer;" title="انقر للتعديل">
                            ${newQuantity.toFixed(2)}
                            <i class="fas fa-edit ms-1" style="font-size: 0.8em;"></i>
                        </span>`;
                    } else {
                        display.innerHTML = `<span class="badge bg-secondary" style="cursor: pointer;" title="انقر للتعديل">
                            لم يتم التنفيذ
                            <i class="fas fa-edit ms-1" style="font-size: 0.8em;"></i>
                        </span>`;
                    }
                    
                    edit.classList.add('d-none');
                    display.classList.remove('d-none');
                    
                    // إعادة تعيين event listener للعنصر الجديد
                    display.onclick = () => editExecutedQuantity(itemId);
                    
                    // إعادة تحميل الصفحة لتحديث الإجماليات
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                    
                    // إظهار رسالة نجاح
                    showToast('تم تحديث الكمية المنفذة بنجاح', 'success');
                } else {
                    alert(data.message || 'حدث خطأ أثناء التحديث');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء التحديث');
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }

        // دالة لإظهار رسائل التنبيه
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            toast.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 3000);
        }

        // دوال تعديل الكمية المنفذة للمواد
        function editMaterialExecutedQuantity(materialId) {
            console.log('Editing executed quantity for material:', materialId);
            const container = document.querySelector(`.executed-quantity-container[data-material-id="${materialId}"]`);
            console.log('Container found:', container);
            
            if (!container) {
                console.error('Container not found for material:', materialId);
                return;
            }
            
            const display = container.querySelector('.executed-quantity-display');
            const edit = container.querySelector('.executed-quantity-edit');
            
            display.classList.add('d-none');
            edit.classList.remove('d-none');
            
            const input = document.getElementById(`material_executed_quantity_${materialId}`);
            input.focus();
            input.select();
        }

        function cancelEditMaterialExecutedQuantity(materialId) {
            const container = document.querySelector(`.executed-quantity-container[data-material-id="${materialId}"]`);
            const display = container.querySelector('.executed-quantity-display');
            const edit = container.querySelector('.executed-quantity-edit');
            
            edit.classList.add('d-none');
            display.classList.remove('d-none');
        }

        function saveMaterialExecutedQuantity(materialId) {
            const input = document.getElementById(`material_executed_quantity_${materialId}`);
            const newQuantity = parseFloat(input.value) || 0;
            
            // إظهار loading
            const saveBtn = document.querySelector(`button[onclick="saveMaterialExecutedQuantity(${materialId})"]`);
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            saveBtn.disabled = true;
            
            // إرسال طلب التحديث
            fetch(`{{ route('admin.work-orders.materials.update-quantity', $workOrder) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    material_id: materialId,
                    quantity_type: 'executed',
                    value: newQuantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // تحديث العرض
                    const container = document.querySelector(`.executed-quantity-container[data-material-id="${materialId}"]`);
                    const display = container.querySelector('.executed-quantity-display');
                    const edit = container.querySelector('.executed-quantity-edit');
                    
                    // تحديث النص المعروض
                    if (newQuantity > 0) {
                        display.innerHTML = `<span class="badge bg-success" style="cursor: pointer;" title="انقر للتعديل">
                            ${newQuantity.toFixed(2)}
                            <i class="fas fa-edit ms-1" style="font-size: 0.8em;"></i>
                        </span>`;
                    } else {
                        display.innerHTML = `<span class="badge bg-secondary" style="cursor: pointer;" title="انقر للتعديل">
                            لم يتم التنفيذ
                            <i class="fas fa-edit ms-1" style="font-size: 0.8em;"></i>
                        </span>`;
                    }
                    
                    edit.classList.add('d-none');
                    display.classList.remove('d-none');
                    
                    // إعادة تعيين event listener للعنصر الجديد
                    display.onclick = () => editMaterialExecutedQuantity(materialId);
                    
                    // إعادة تحميل الصفحة لتحديث الفرق
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                    
                    showToast('تم تحديث الكمية المنفذة بنجاح', 'success');
                } else {
                    alert(data.message || 'حدث خطأ أثناء التحديث');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء التحديث');
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }

        // دوال تعديل الكمية المصروفة للمواد
        function editMaterialSpentQuantity(materialId) {
            const container = document.querySelector(`.spent-quantity-container[data-material-id="${materialId}"]`);
            const display = container.querySelector('.spent-quantity-display');
            const edit = container.querySelector('.spent-quantity-edit');
            
            display.classList.add('d-none');
            edit.classList.remove('d-none');
            
            const input = document.getElementById(`material_spent_quantity_${materialId}`);
            input.focus();
            input.select();
        }

        function cancelEditMaterialSpentQuantity(materialId) {
            const container = document.querySelector(`.spent-quantity-container[data-material-id="${materialId}"]`);
            const display = container.querySelector('.spent-quantity-display');
            const edit = container.querySelector('.spent-quantity-edit');
            
            edit.classList.add('d-none');
            display.classList.remove('d-none');
        }

        function saveMaterialSpentQuantity(materialId) {
            const input = document.getElementById(`material_spent_quantity_${materialId}`);
            const newQuantity = parseFloat(input.value) || 0;
            
            // إظهار loading
            const saveBtn = document.querySelector(`button[onclick="saveMaterialSpentQuantity(${materialId})"]`);
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            saveBtn.disabled = true;
            
            // إرسال طلب التحديث
            fetch(`{{ route('admin.work-orders.materials.update-quantity', $workOrder) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    material_id: materialId,
                    quantity_type: 'spent',
                    value: newQuantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // تحديث العرض
                    const container = document.querySelector(`.spent-quantity-container[data-material-id="${materialId}"]`);
                    const display = container.querySelector('.spent-quantity-display');
                    const edit = container.querySelector('.spent-quantity-edit');
                    
                    // تحديث النص المعروض
                    if (newQuantity > 0) {
                        display.innerHTML = `<span class="badge bg-warning text-dark" style="cursor: pointer;" title="انقر للتعديل">
                            ${newQuantity.toFixed(2)}
                            <i class="fas fa-edit ms-1" style="font-size: 0.8em;"></i>
                        </span>`;
                    } else {
                        display.innerHTML = `<span class="badge bg-secondary" style="cursor: pointer;" title="انقر للتعديل">
                            لم يتم الصرف
                            <i class="fas fa-edit ms-1" style="font-size: 0.8em;"></i>
                        </span>`;
                    }
                    
                    edit.classList.add('d-none');
                    display.classList.remove('d-none');
                    
                    // إعادة تعيين event listener للعنصر الجديد
                    display.onclick = () => editMaterialSpentQuantity(materialId);
                    
                    // إعادة تحميل الصفحة لتحديث الفرق
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                    
                    showToast('تم تحديث الكمية المصروفة بنجاح', 'success');
                } else {
                    alert(data.message || 'حدث خطأ أثناء التحديث');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء التحديث');
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }

        // تفعيل event listeners عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, setting up event listeners');
            
            // تفعيل النقر على الكميات المنفذة للمواد
            const executedElements = document.querySelectorAll('.executed-quantity-display');
            console.log('Found executed quantity elements:', executedElements.length);
            
            executedElements.forEach(function(element) {
                element.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Executed quantity clicked');
                    const container = this.closest('.executed-quantity-container');
                    const materialId = container.getAttribute('data-material-id');
                    console.log('Material ID:', materialId);
                    if (materialId) {
                        editMaterialExecutedQuantity(materialId);
                    }
                });
            });

            // تفعيل النقر على الكميات المصروفة للمواد
            const spentElements = document.querySelectorAll('.spent-quantity-display');
            console.log('Found spent quantity elements:', spentElements.length);
            
            spentElements.forEach(function(element) {
                element.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Spent quantity clicked');
                    const container = this.closest('.spent-quantity-container');
                    const materialId = container.getAttribute('data-material-id');
                    console.log('Material ID:', materialId);
                    if (materialId) {
                        editMaterialSpentQuantity(materialId);
                    }
                });
            });

            // إضافة keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    // إلغاء جميع عمليات التعديل المفتوحة
                    document.querySelectorAll('.executed-quantity-edit:not(.d-none)').forEach(function(edit) {
                        const container = edit.closest('.executed-quantity-container');
                        const materialId = container.getAttribute('data-material-id');
                        if (materialId) {
                            cancelEditMaterialExecutedQuantity(materialId);
                        }
                    });
                    
                    document.querySelectorAll('.spent-quantity-edit:not(.d-none)').forEach(function(edit) {
                        const container = edit.closest('.spent-quantity-container');
                        const materialId = container.getAttribute('data-material-id');
                        if (materialId) {
                            cancelEditMaterialSpentQuantity(materialId);
                        }
                    });
                }
            });
        });

        // دالة لعرض جميع الملفات في modal
        function showAllFiles(fieldName, label) {
            const modal = new bootstrap.Modal(document.getElementById('allFilesModal'));
            document.getElementById('allFilesModalLabel').textContent = 'جميع ملفات: ' + label;
            
            // جلب الملفات من الخادم
            fetch(`{{ route('admin.work-orders.get-files', $workOrder) }}?field=${fieldName}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let filesHtml = '<div class="row">';
                        data.files.forEach((file, index) => {
                            filesHtml += `
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <h6 class="card-title text-truncate">${file.original_filename}</h6>
                                            <small class="text-muted d-block mb-2">${file.created_at}</small>
                                            <small class="text-muted d-block mb-2">حجم الملف: ${file.file_size_formatted}</small>
                                            <a href="${file.url}" target="_blank" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye me-1"></i>عرض الملف
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        filesHtml += '</div>';
                        document.getElementById('allFilesModalBody').innerHTML = filesHtml;
                    } else {
                        document.getElementById('allFilesModalBody').innerHTML = '<p class="text-center text-muted">لا توجد ملفات</p>';
                    }
                })
                .catch(error => {
                    document.getElementById('allFilesModalBody').innerHTML = '<p class="text-center text-danger">حدث خطأ في جلب الملفات</p>';
                });
            
            modal.show();
        }
    </script>
    @endpush

    {{-- Modal لعرض جميع الملفات --}}
    <div class="modal fade" id="allFilesModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="allFilesModalLabel">جميع الملفات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="allFilesModalBody">
                    <!-- سيتم ملء المحتوى بـ JavaScript -->
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function showImageModal(imageSrc, imageName) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModalLabel').textContent = imageName;
    var imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    imageModal.show();
}
</script>
@endpush

@endsection 