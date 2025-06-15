@extends('layouts.admin')

@section('title', 'مواد أمر العمل رقم ' . $workOrder->order_number)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">مواد أمر العمل رقم {{ $workOrder->order_number }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.work-orders.index') }}">أوامر العمل</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.work-orders.show', $workOrder) }}">أمر العمل {{ $workOrder->order_number }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">المواد</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.work-orders.materials.create', $workOrder) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة مادة جديدة
                    </a>
                    @if($materials->count() > 0)
                        <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> العودة 
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Work Order Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>رقم أمر العمل:</strong> {{ $workOrder->order_number }}
                        </div>
                        <div class="col-md-3">
                            <strong>نوع العمل:</strong> {{ $workOrder->work_type }}
                        </div>
                        <div class="col-md-3">
                            <strong>اسم المشترك:</strong> {{ $workOrder->subscriber_name }}
                        </div>
                        <div class="col-md-3">
                            <strong>نوع العمل:</strong> {{ $workOrder->work_type }}
                        </div>
                        <div class="col-md-3">
                            <strong>عدد المواد:</strong> {{ $materials->total() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.work-orders.materials.index', $workOrder) }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search_code" class="form-label">البحث بالكود</label>
                            <input type="text" class="form-control" id="search_code" name="search_code" 
                                   value="{{ request('search_code') }}" placeholder="أدخل كود المادة">
                        </div>
                        <div class="col-md-4">
                            <label for="search_description" class="form-label">البحث بالوصف</label>
                            <input type="text" class="form-control" id="search_description" name="search_description" 
                                   value="{{ request('search_description') }}" placeholder="أدخل وصف المادة">
                        </div>
                        <div class="col-md-2">
                            <label for="unit_filter" class="form-label">الوحدة</label>
                            <select class="form-select" id="unit_filter" name="unit_filter">
                                <option value=""> الوحدات</option>
                                <option value="L.M" {{ request('unit_filter') == 'L.M' ? 'selected' : '' }}>L.M</option>
                                <option value="Ech" {{ request('unit_filter') == 'Ech' ? 'selected' : '' }}>Ech</option>
                                <option value="Kit" {{ request('unit_filter') == 'Kit' ? 'selected' : '' }}>Kit</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> بحث
                                </button>
                                <a href="{{ route('admin.work-orders.materials.index', $workOrder) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> مسح
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Materials Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">قائمة المواد</h6>
                    @if(request()->hasAny(['search_code', 'search_description', 'unit_filter']))
                        <span class="badge badge-info">
                            <i class="fas fa-filter"></i> 
                            نتائج البحث: {{ $materials->total() }} مادة
                        </span>
                    @endif
                </div>
                <div class="card-body">
                    @if($materials->count() > 0)
                        <div class="table-responsive materials-table-wrapper">
                            <table class="table table-bordered table-hover align-middle" id="materialsTable">
                                <thead>
                                    <tr class="bg-light">
                                        <th class="text-center" style="width: 50px">#</th>
                                        <th style="width: 120px">
                                            <i class="fas fa-barcode text-secondary me-1"></i>
                                            الكود
                                        </th>
                                        <th>
                                            <i class="fas fa-align-left text-primary me-1"></i>
                                            الوصف
                                        </th>
                                        <th style="width: 110px">
                                            <i class="fas fa-chart-line text-info me-1"></i>
                                            الكمية المخططة
                                        </th>
                                        <th style="width: 110px">
                                            <i class="fas fa-box text-danger me-1"></i>
                                            الكمية المصروفة
                                        </th>
                                        <th style="width: 100px">
                                            <i class="fas fa-calculator text-warning me-1"></i>
                                            الفرق (مخططة - مصروفة)
                                        </th>
                                        <th style="width: 110px">
                                            <i class="fas fa-tasks text-success me-1"></i>
                                            الكمية المنفذة
                                        </th>
                                        <th style="width: 100px">
                                            <i class="fas fa-calculator text-primary me-1"></i>
                                            الفرق (منفذة - مصروفة)
                                        </th>
                                        <th style="width: 80px">
                                            <i class="fas fa-ruler text-secondary me-1"></i>
                                            الوحدة
                                        </th>
                                        <th style="width: 80px">
                                            <i class="fas fa-hashtag text-muted me-1"></i>
                                            السطر
                                        </th>
                                        <th style="width: 160px" class="text-center">
                                            <i class="fas fa-cogs text-secondary me-1"></i>
                                            الإجراءات
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materials as $index => $material)
                                        <tr class="align-middle">
                                            <td class="text-center fw-bold">{{ $materials->firstItem() + $index }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-light text-dark border">{{ $material->code }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-wrap" style="max-width: 300px;">
                                                    {{ $material->description }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="quantity-badge planned">
                                                    {{ number_format($material->planned_quantity, 2) }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="quantity-badge spent">
                                                    {{ number_format($material->spent_quantity, 2) }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $plannedSpentDiff = ($material->planned_quantity ?? 0) - ($material->spent_quantity ?? 0);
                                                @endphp
                                                <div class="quantity-badge difference {{ $plannedSpentDiff > 0 ? 'warning' : ($plannedSpentDiff < 0 ? 'danger' : 'success') }}"
                                                     data-bs-toggle="tooltip" 
                                                     title="{{ $plannedSpentDiff > 0 ? 'يوجد كمية مخططة لم يتم صرفها' : ($plannedSpentDiff < 0 ? 'تم صرف كمية أكثر من المخطط' : 'متطابقة') }}">
                                                    @if($plannedSpentDiff == 0)
                                                        <i class="fas fa-check"></i>
                                                    @else
                                                        {{ $plannedSpentDiff > 0 ? '+' : '' }}{{ number_format($plannedSpentDiff, 2) }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="quantity-badge executed">
                                                    {{ number_format($material->executed_quantity ?? 0, 2) }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $executedSpentDiff = ($material->executed_quantity ?? 0) - ($material->spent_quantity ?? 0);
                                                @endphp
                                                <div class="quantity-badge difference {{ $executedSpentDiff > 0 ? 'warning' : ($executedSpentDiff < 0 ? 'danger' : 'success') }}"
                                                     data-bs-toggle="tooltip" 
                                                     title="{{ $executedSpentDiff > 0 ? 'تم تنفيذ كمية أكثر من المصروفة' : ($executedSpentDiff < 0 ? 'تم صرف كمية أكثر من المنفذة' : 'متطابقة') }}">
                                                    @if($executedSpentDiff == 0)
                                                        <i class="fas fa-check"></i>
                                                    @else
                                                        {{ $executedSpentDiff > 0 ? '+' : '' }}{{ number_format($executedSpentDiff, 2) }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border">{{ $material->unit }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border">{{ $material->line ?: '-' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="action-buttons">
                                                    <!-- View/Edit/Delete Group -->
                                                    <div class="btn-group">
                                                        <a href="{{ route('admin.work-orders.materials.show', [$workOrder, $material]) }}" 
                                                           class="btn btn-action btn-view"
                                                           data-bs-toggle="tooltip"
                                                           data-bs-placement="top"
                                                           title="عرض التفاصيل">
                                                            <i class="fas fa-eye"></i>
                                                            <span class="btn-text">عرض</span>
                                                        </a>
                                                        
                                                        <a href="{{ route('admin.work-orders.materials.edit', [$workOrder, $material]) }}" 
                                                           class="btn btn-action btn-edit"
                                                           data-bs-toggle="tooltip"
                                                           data-bs-placement="top"
                                                           title="تعديل المادة">
                                                            <i class="fas fa-edit"></i>
                                                            <span class="btn-text">تعديل</span>
                                                        </a>

                                                        <button type="button" 
                                                                class="btn btn-action btn-delete"
                                                                onclick="deleteMaterial({{ $material->id }}, '{{ $material->code }}')"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                title="حذف المادة">
                                                            <i class="fas fa-trash-alt"></i>
                                                            <span class="btn-text">حذف</span>
                                                        </button>
                                                    </div>
                                                    
                                                    <!-- Attachments Button -->
                                                    <div class="attachments-wrapper">
                                                        @if($material->hasAttachments())
                                                            <div class="dropdown">
                                                                <button type="button" 
                                                                        class="btn btn-action btn-attachments" 
                                                                        data-bs-toggle="dropdown" 
                                                                        data-bs-auto-close="outside"
                                                                        aria-expanded="false"
                                                                        title="المرفقات">
                                                                    <i class="fas fa-paperclip"></i>
                                                                    <span class="btn-text">المرفقات</span>
                                                                    <span class="attachments-count">{{ count($material->getAttachments()) }}</span>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm py-0" style="min-width: 300px;">
                                                                    <li class="p-2 bg-light border-bottom">
                                                                        <h6 class="mb-0">
                                                                            <i class="fas fa-folder-open text-primary me-1"></i>
                                                                            المرفقات المتاحة
                                                                        </h6>
                                                                    </li>
                                                                    @foreach($material->getAttachments() as $attachment)
                                                                        <li>
                                                                            <a class="dropdown-item py-2 px-3" href="{{ $attachment['url'] }}" target="_blank">
                                                                                @php
                                                                                    $extension = strtolower(pathinfo($attachment['file'], PATHINFO_EXTENSION));
                                                                                    $icon = 'fas fa-file';
                                                                                    $color = 'text-secondary';
                                                                                    
                                                                                    switch($extension) {
                                                                                        case 'pdf':
                                                                                            $icon = 'fas fa-file-pdf';
                                                                                            $color = 'text-danger';
                                                                                            break;
                                                                                        case 'doc':
                                                                                        case 'docx':
                                                                                            $icon = 'fas fa-file-word';
                                                                                            $color = 'text-primary';
                                                                                            break;
                                                                                        case 'xls':
                                                                                        case 'xlsx':
                                                                                            $icon = 'fas fa-file-excel';
                                                                                            $color = 'text-success';
                                                                                            break;
                                                                                        case 'jpg':
                                                                                        case 'jpeg':
                                                                                        case 'png':
                                                                                        case 'gif':
                                                                                            $icon = 'fas fa-file-image';
                                                                                            $color = 'text-info';
                                                                                            break;
                                                                                    }
                                                                                @endphp
                                                                                <div class="d-flex align-items-center">
                                                                                    <div class="file-icon me-2">
                                                                                        <i class="{{ $icon }} {{ $color }} fa-lg"></i>
                                                                                    </div>
                                                                                    <div class="flex-grow-1">
                                                                                        <div class="fw-bold text-truncate" style="max-width: 200px;">
                                                                                            {{ $attachment['name'] }}
                                                                                        </div>
                                                                                        <small class="text-muted">انقر للعرض</small>
                                                                                    </div>
                                                                                    <div class="ms-2">
                                                                                        <i class="fas fa-external-link-alt text-muted"></i>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </li>
                                                                        @if(!$loop->last)
                                                                            <li><hr class="dropdown-divider my-0"></li>
                                                                        @endif
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @else
                                                            <button type="button" 
                                                                    class="btn btn-action btn-attachments-empty" 
                                                                    disabled
                                                                    data-bs-toggle="tooltip"
                                                                    title="لا توجد مرفقات">
                                                                <i class="fas fa-paperclip"></i>
                                                                <span class="btn-text">لا توجد مرفقات</span>
                                                            </button>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Hidden Delete Form -->
                                                    <form id="delete-form-{{ $material->id }}" 
                                                          method="POST" 
                                                          action="{{ route('admin.work-orders.materials.destroy', [$workOrder, $material]) }}" 
                                                          style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $materials->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد مواد مضافة لهذا أمر العمل</h5>
                            <a href="{{ route('admin.work-orders.materials.create', $workOrder) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> إضافة أول مادة
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- قسم الملفات المرفقة -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-paperclip me-2"></i>
                        الملفات المرفقة للمواد
                    </h6>
                </div>
                <div class="card-body">
                    <!-- فورم رفع الملفات -->
                    <form id="uploadMaterialFilesForm" action="{{ route('admin.work-orders.materials.upload-files', $workOrder) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-upload me-2"></i>
                                    رفع ملفات جديدة
                                </h5>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="check_in_file" class="form-label">
                                    <i class="fas fa-list-check me-2 text-primary"></i>
                                    CHECK LIST
                                </label>
                                <input type="file" class="form-control @error('check_in_file') is-invalid @enderror" 
                                       id="check_in_file" name="check_in_file" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('check_in_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">PDF, JPG, PNG, DOC (حد أقصى 10MB)</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="gate_pass_file" class="form-label">
                                    <i class="fas fa-id-card me-2 text-success"></i>
                                    GATE PASS
                                </label>
                                <input type="file" class="form-control @error('gate_pass_file') is-invalid @enderror" 
                                       id="gate_pass_file" name="gate_pass_file" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('gate_pass_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">PDF, JPG, PNG, DOC (حد أقصى 10MB)</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="stock_in_file" class="form-label">
                                    <i class="fas fa-sign-in-alt me-2 text-info"></i>
                                    STORE IN
                                </label>
                                <input type="file" class="form-control @error('stock_in_file') is-invalid @enderror" 
                                       id="stock_in_file" name="stock_in_file" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('stock_in_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">PDF, JPG, PNG, DOC (حد أقصى 10MB)</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="stock_out_file" class="form-label">
                                    <i class="fas fa-sign-out-alt me-2 text-warning"></i>
                                    STORE OUT
                                </label>
                                <input type="file" class="form-control @error('stock_out_file') is-invalid @enderror" 
                                       id="stock_out_file" name="stock_out_file" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('stock_out_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">PDF, JPG, PNG, DOC (حد أقصى 10MB)</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ddo_file" class="form-label">
                                    <i class="fas fa-file-alt me-2 text-primary"></i>
                                    DDO FILE
                                </label>
                                <input type="file" class="form-control @error('ddo_file') is-invalid @enderror" 
                                       id="ddo_file" name="ddo_file" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('ddo_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">PDF, JPG, PNG, DOC (حد أقصى 10MB)</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="check_out_file" class="form-label">
                                    <i class="fas fa-check-circle me-2 text-success"></i>
                                    CHECK OUT FILE
                                </label>
                                <input type="file" class="form-control @error('check_out_file') is-invalid @enderror" 
                                       id="check_out_file" name="check_out_file" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('check_out_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">PDF, JPG, PNG, DOC (حد أقصى 10MB)</small>
                            </div>
                        </div>

                        <!-- أزرار الحفظ -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg" id="uploadFilesBtn">
                                        <i class="fas fa-upload me-2"></i>
                                        رفع الملفات
                                    </button>

                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- عرض الملفات المرفوعة -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-folder-open me-2"></i>
                                الملفات المرفوعة
                            </h5>
                                                         <div id="uploadedFilesContainer">
                                @php
                                    $fileTypes = [
                                        'check_in_file' => ['label' => 'CHECK LIST', 'icon' => 'fas fa-list-check', 'color' => 'text-primary'],
                                        'gate_pass_file' => ['label' => 'GATE PASS', 'icon' => 'fas fa-id-card', 'color' => 'text-success'],
                                        'stock_in_file' => ['label' => 'STORE IN', 'icon' => 'fas fa-sign-in-alt', 'color' => 'text-info'],
                                        'stock_out_file' => ['label' => 'STORE OUT', 'icon' => 'fas fa-sign-out-alt', 'color' => 'text-warning'],
                                        'ddo_file' => ['label' => 'DDO FILE', 'icon' => 'fas fa-file-alt', 'color' => 'text-primary'],
                                        'check_out_file' => ['label' => 'CHECK OUT FILE', 'icon' => 'fas fa-check-circle', 'color' => 'text-success']
                                    ];
                                    
                                    // جمع جميع الملفات من جميع المواد
                                    $allFiles = [];
                                    foreach($materials as $material) {
                                        foreach($fileTypes as $field => $info) {
                                            if($material->$field) {
                                                $allFiles[] = [
                                                    'material_id' => $material->id,
                                                    'material_code' => $material->code,
                                                    'file_type' => $field,
                                                    'file_path' => $material->$field,
                                                    'file_name' => basename($material->$field),
                                                    'file_info' => $info,
                                                    'created_at' => $material->created_at
                                                ];
                                            }
                                        }
                                    }
                                @endphp
                                
                                <div class="table-responsive">
                                    <table class="table table-hover" id="attachmentsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>نوع الملف</th>
                                                <th>تاريخ الرفع</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($allFiles) > 0)
                                                @foreach($allFiles as $file)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex flex-column">
                                                                <div class="d-flex align-items-center mb-1">
                                                                    <i class="{{ $file['file_info']['icon'] }} {{ $file['file_info']['color'] }} me-2"></i>
                                                                    <span class="fw-bold">{{ $file['file_info']['label'] }}</span>
                                                                </div>
                                                                <div class="ms-3">
                                                                    <a href="{{ Storage::url($file['file_path']) }}" target="_blank" class="text-decoration-none text-muted small">
                                                                        <i class="fas fa-file me-1"></i>
                                                                        {{ $file['file_name'] }}
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <small class="text-muted">{{ $file['created_at']->format('Y-m-d H:i') }}</small>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group" role="group">
                                                                <a href="{{ Storage::url($file['file_path']) }}" 
                                                                   target="_blank" 
                                                                   class="btn btn-sm btn-outline-primary"
                                                                   data-bs-toggle="tooltip" 
                                                                   title="عرض الملف">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-danger"
                                                                        onclick="deleteMaterialFile({{ $file['material_id'] }}, '{{ $file['file_type'] }}')"
                                                                        data-bs-toggle="tooltip" 
                                                                        title="حذف الملف">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="3" class="text-center py-4">
                                                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                                        <h6 class="text-muted">لا توجد ملفات مرفوعة حتى الآن</h6>
                                                        <p class="text-muted small">استخدم النموذج أعلاه لرفع الملفات</p>
                                                    </td>
                                                </tr>
                                            @endif
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

@push('styles')
<style>
/* تحسين مظهر الجدول */
.materials-table-wrapper {
    border-radius: 0.5rem;
    box-shadow: 0 0 20px rgba(0,0,0,.05);
}

#materialsTable {
    margin-bottom: 0;
}

#materialsTable thead tr {
    background: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

#materialsTable th {
    font-weight: 600;
    text-align: center;
    padding: 1rem 0.75rem;
    white-space: nowrap;
}

#materialsTable td {
    padding: 0.75rem;
    vertical-align: middle;
}

#materialsTable tbody tr {
    transition: all 0.3s ease;
}

#materialsTable tbody tr:hover {
    background-color: #f8f9fa;
}

/* تحسين عرض الكميات */
.quantity-badge {
    display: inline-block;
    padding: 0.4rem 0.8rem;
    border-radius: 50rem;
    font-weight: 600;
    font-size: 0.875rem;
    line-height: 1;
    white-space: nowrap;
    transition: all 0.3s ease;
}

.quantity-badge.planned {
    background-color: #e3f2fd;
    color: #0d6efd;
    border: 1px solid #90caf9;
}

.quantity-badge.executed {
    background-color: #e8f5e9;
    color: #198754;
    border: 1px solid #a5d6a7;
}

.quantity-badge.spent {
    background-color: #fff3e0;
    color: #fd7e14;
    border: 1px solid #ffcc80;
}

.quantity-badge.difference {
    padding: 0.35rem 0.7rem;
}

.quantity-badge.difference.warning {
    background-color: #fff3cd;
    color: #997404;
    border: 1px solid #ffe69c;
}

.quantity-badge.difference.danger {
    background-color: #f8d7da;
    color: #dc3545;
    border: 1px solid #f5c2c7;
}

.quantity-badge.difference.success {
    background-color: #d1e7dd;
    color: #146c43;
    border: 1px solid #a3cfbb;
}

/* تحسين الأزرار */
.action-buttons-wrapper {
    position: relative;
}

.action-buttons {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.3s ease;
    background: #fff;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    position: relative;
    overflow: hidden;
}

.btn-action i {
    margin-inline-end: 0.5rem;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.btn-action .btn-text {
    opacity: 0;
    width: 0;
    transition: all 0.3s ease;
}

.btn-action:hover .btn-text {
    opacity: 1;
    width: auto;
    margin-inline-start: 0.5rem;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-action:active {
    transform: translateY(0);
}

/* تنسيق الأزرار */
.btn-group {
    display: flex;
    gap: 0.5rem;
    margin-inline-end: 0.75rem;
}

.btn-group .btn-action {
    border-radius: 50px !important;
}

/* تنسيق زر العرض */
.btn-view {
    color: #0dcaf0;
    background: rgba(13, 202, 240, 0.1);
}

.btn-view:hover {
    color: #fff;
    background: linear-gradient(135deg, #0dcaf0, #0d6efd);
}

/* تنسيق زر التعديل */
.btn-edit {
    color: #ffc107;
    background: rgba(255, 193, 7, 0.1);
}

.btn-edit:hover {
    color: #fff;
    background: linear-gradient(135deg, #ffc107, #fd7e14);
}

/* تنسيق زر المرفقات */
.btn-attachments {
    color: #198754;
    background: rgba(25, 135, 84, 0.1);
}

.btn-attachments:hover {
    color: #fff;
    background: linear-gradient(135deg, #198754, #146c43);
}

.btn-attachments-empty {
    color: #6c757d;
    background: rgba(108, 117, 125, 0.1);
    cursor: not-allowed;
}

.btn-attachments-empty:hover {
    transform: none;
    box-shadow: none;
}

.attachments-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #198754;
    color: #fff;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* تنسيق زر الحذف */
.btn-delete {
    color: #dc3545;
    background: rgba(220, 53, 69, 0.1);
}

.btn-delete:hover {
    color: #fff;
    background: linear-gradient(135deg, #dc3545, #b02a37);
}

/* تأثيرات إضافية */
.btn-action::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: all 0.5s ease;
}

.btn-action:hover::before {
    width: 200%;
    height: 200%;
    opacity: 0;
}

/* تحسين قائمة المرفقات */
.dropdown-menu {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
}

.dropdown-item {
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.file-icon {
    width: 24px;
    text-align: center;
}

/* تحسين الشاشات الصغيرة */
@media (max-width: 768px) {
    .quantity-badge {
        padding: 0.3rem 0.6rem;
        font-size: 0.8125rem;
    }
    
    .action-buttons {
        flex-wrap: nowrap;
        gap: 0.35rem;
    }
    
    .btn-action {
        padding: 0.4rem;
        min-width: 32px;
        justify-content: center;
    }
    
    .btn-action i {
        margin: 0;
        font-size: 0.9rem;
    }
    
    .btn-action .btn-text {
        display: none;
    }
    
    .btn-action:hover .btn-text {
        display: none;
    }
    
    .attachments-count {
        width: 18px;
        height: 18px;
        font-size: 0.7rem;
        top: -5px;
        right: -5px;
    }
    
    .dropdown-menu {
        min-width: 280px !important;
        max-width: 95vw;
        margin-top: 0.5rem;
    }
}

/* تحسينات إضافية للشاشات الصغيرة جداً */
@media (max-width: 480px) {
    .action-buttons {
        gap: 0.25rem;
    }
    
    .btn-action {
        padding: 0.35rem;
        min-width: 28px;
    }
    
    .btn-action i {
        font-size: 0.8rem;
    }
    
    .attachments-count {
        width: 16px;
        height: 16px;
        font-size: 0.65rem;
        top: -4px;
        right: -4px;
    }
    
    .dropdown-menu {
        min-width: 250px !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // تفعيل tooltips بشكل محسن
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            trigger: 'hover',
            animation: true,
            delay: { show: 100, hide: 100 }
        });
    });
    
    // تحسين عرض الكميات
    $('.quantity-badge').each(function() {
        const $badge = $(this);
        $badge.hover(
            function() {
                $(this).css({
                    'transform': 'scale(1.05)',
                    'box-shadow': '0 2px 4px rgba(0,0,0,0.1)'
                });
            },
            function() {
                $(this).css({
                    'transform': 'scale(1)',
                    'box-shadow': 'none'
                });
            }
        );
    });
    
    // تحسين عرض المرفقات
    $('.dropdown-item').hover(
        function() {
            const $icon = $(this).find('.file-icon i');
            $icon.css({
                'transform': 'scale(1.2) rotate(5deg)',
                'transition': 'all 0.2s ease'
            });
        },
        function() {
            const $icon = $(this).find('.file-icon i');
            $icon.css({
                'transform': 'scale(1) rotate(0deg)',
                'transition': 'all 0.2s ease'
            });
        }
    );
    
    // تحسين تجربة البحث
    $('#search_code, #search_description').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            $(this).closest('form').submit();
        }
    });

    // البحث السريع المحسن
    let searchTimeout;
    $('#search_code, #search_description').on('input', function() {
        const $input = $(this);
        const value = $input.val();
        const type = $input.attr('id').replace('search_', '');
        
        clearTimeout(searchTimeout);
        
        if (value.length >= 2) {
            searchTimeout = setTimeout(() => {
                highlightTableRows(value, type);
                
                // تحريك الشاشة إلى أول نتيجة
                const $firstHighlight = $('.table-highlight').first();
                if ($firstHighlight.length) {
                    $('html, body').animate({
                        scrollTop: $firstHighlight.offset().top - 200
                    }, 300);
                }
            }, 300);
        } else {
            clearHighlights();
        }
    });

    // تحسين عرض الجدول
    if ($('#materialsTable tbody tr').length > 0) {
        $('#materialsTable tbody tr').each(function(index) {
            const $row = $(this);
            
            // إضافة تأثير ظهور متدرج
            $row.css({
                'opacity': 0,
                'transform': 'translateY(10px)'
            }).delay(index * 50).animate({
                'opacity': 1,
                'transform': 'translateY(0)'
            }, 300);
            
            // تحسين hover effect
            $row.hover(
                function() {
                    $(this).addClass('table-active').css({
                        'transform': 'translateY(-2px)',
                        'box-shadow': '0 2px 8px rgba(0,0,0,.1)',
                        'z-index': 1
                    });
                },
                function() {
                    $(this).removeClass('table-active').css({
                        'transform': 'translateY(0)',
                        'box-shadow': 'none',
                        'z-index': 'auto'
                    });
                }
            );
        });
    }

    // تحسين dropdown المرفقات
    $('.dropdown-toggle').on('click', function(e) {
        e.stopPropagation();
        
        const $button = $(this);
        const $menu = $button.next('.dropdown-menu');
        
        // تأثير ظهور القائمة
        if (!$menu.hasClass('show')) {
            $menu.css({
                'opacity': 0,
                'transform': 'translateY(-10px)'
            }).addClass('show').animate({
                'opacity': 1,
                'transform': 'translateY(0)'
            }, 200);
        }
    });
});

// دالة حذف المادة مع تأكيد محسن
function deleteMaterial(materialId, materialCode) {
    Swal.fire({
        title: 'تأكيد الحذف',
        text: `هل أنت متأكد من حذف المادة "${materialCode}"؟`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذف',
        cancelButtonText: 'إلغاء',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // إظهار loading
            Swal.fire({
                title: 'جاري الحذف...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // إرسال النموذج
            document.getElementById('delete-form-' + materialId).submit();
        }
    });
}

// دالة تمييز الصفوف في البحث مع تحسينات
function highlightTableRows(searchTerm, column) {
    clearHighlights();
    
    if (!searchTerm) return;
    
    const searchTermLower = searchTerm.toLowerCase();
    let matchCount = 0;
    
    $('#materialsTable tbody tr').each(function() {
        const $row = $(this);
        let $targetCell;
        
        switch(column) {
            case 'code':
                $targetCell = $row.find('td:eq(1) .badge'); // عمود الكود
                break;
            case 'description':
                $targetCell = $row.find('td:eq(2) .text-wrap'); // عمود الوصف
                break;
            default:
                return;
        }
        
        const cellText = $targetCell.text().toLowerCase();
        if (cellText.includes(searchTermLower)) {
            matchCount++;
            
            // إضافة تأثير التمييز
            $row.addClass('table-highlight').css({
                'background-color': '#fff9c4',
                'transition': 'background-color 0.3s ease'
            });
            
            // تمييز النص المطابق
            const originalText = $targetCell.text();
            const highlightedText = originalText.replace(
                new RegExp(searchTerm, 'gi'),
                match => `<mark class="highlight-text">${match}</mark>`
            );
            $targetCell.html(highlightedText);
        }
    });
    
    // عرض عدد النتائج
    if (matchCount > 0) {
        toastr.info(`تم العثور على ${matchCount} نتيجة`);
    } else {
        toastr.warning('لم يتم العثور على نتائج');
    }
    
    return matchCount;
}

// دالة مسح التمييز مع تحسينات
function clearHighlights() {
    const $rows = $('#materialsTable tbody tr');
    
    $rows.removeClass('table-highlight').css({
        'background-color': '',
        'transition': 'none'
    });
    
    // إعادة النص الأصلي
    $rows.find('.highlight-text').each(function() {
        const $mark = $(this);
        $mark.replaceWith($mark.text());
    });
}

// تحسين عرض الرسائل
@if(session('success'))
    toastr.success('{{ session('success') }}', '', {
        progressBar: true,
        closeButton: true,
        timeOut: 5000,
        extendedTimeOut: 2000,
        positionClass: 'toast-top-center',
        rtl: true
    });
@endif

@if(session('error'))
        toastr.error('{{ session('error') }}', '', {
        progressBar: true,
        closeButton: true,
        timeOut: 7000,
        extendedTimeOut: 3000,
        positionClass: 'toast-top-center',
        rtl: true
    });
@endif

// إضافة CSS للتمييز
const style = document.createElement('style');
style.textContent = `
    .highlight-text {
        background-color: #fff176;
        padding: 2px 0;
        border-radius: 2px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        animation: highlight-pulse 2s infinite;
    }
    
    @keyframes highlight-pulse {
        0% { background-color: #fff176; }
        50% { background-color: #ffeb3b; }
        100% { background-color: #fff176; }
    }
    
    .table-highlight {
        position: relative;
    }
    
    .table-highlight::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background-color: #fbc02d;
        animation: highlight-border 1s infinite;
    }
    
    @keyframes highlight-border {
        0% { opacity: 0.4; }
        50% { opacity: 1; }
        100% { opacity: 0.4; }
    }
`;
document.head.appendChild(style);
});



// دالة حذف ملف مادة
function deleteMaterialFile(materialId, fileType) {
    Swal.fire({
        title: 'تأكيد الحذف',
        text: 'هل أنت متأكد من حذف هذا الملف؟',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذف',
        cancelButtonText: 'إلغاء',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // العثور على الصف وحذفه من الجدول
            const button = event.target.closest('button');
            const row = button.closest('tr');
            
            // إنشاء فورم مخفي لحذف الملف
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/work-orders/{{ $workOrder->id }}/materials/${materialId}/delete-file`;
            
            // إضافة CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // إضافة method DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            // إضافة نوع الملف
            const fileTypeInput = document.createElement('input');
            fileTypeInput.type = 'hidden';
            fileTypeInput.name = 'file_type';
            fileTypeInput.value = fileType;
            form.appendChild(fileTypeInput);
            
            // حذف الصف من الجدول فوراً
            if (row) {
                row.remove();
                toastr.success('تم حذف الملف بنجاح');
            }
            
            // إضافة الفورم للصفحة وإرساله
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// تحسين فورم رفع الملفات
document.getElementById('uploadMaterialFilesForm').addEventListener('submit', function(e) {
    const fileInputs = this.querySelectorAll('input[type="file"]');
    let hasFiles = false;
    
    fileInputs.forEach(input => {
        if (input.files.length > 0) {
            hasFiles = true;
        }
    });
    
    if (!hasFiles) {
        e.preventDefault();
        toastr.warning('يرجى اختيار ملف واحد على الأقل للرفع');
        return false;
    }
    
    // تعطيل زر الإرسال لمنع الإرسال المتكرر
    const submitBtn = document.getElementById('uploadFilesBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الرفع...';
});

// دالة إضافة ملف إلى الجدول
function addFileToTable(fileData) {
    const tableBody = document.querySelector('#attachmentsTable tbody');
    const row = document.createElement('tr');
    
    const now = new Date();
    const dateString = now.getFullYear() + '-' + 
                      String(now.getMonth() + 1).padStart(2, '0') + '-' + 
                      String(now.getDate()).padStart(2, '0') + ' ' +
                      String(now.getHours()).padStart(2, '0') + ':' + 
                      String(now.getMinutes()).padStart(2, '0');
    
    row.innerHTML = `
        <td>
            <div class="d-flex flex-column">
                <div class="d-flex align-items-center mb-1">
                    <i class="${fileData.icon} ${fileData.color} me-2"></i>
                    <span class="fw-bold">${fileData.label}</span>
                </div>
                <div class="ms-3">
                    <a href="/storage/${fileData.file_path}" target="_blank" class="text-decoration-none text-muted small">
                        <i class="fas fa-file me-1"></i>
                        ${fileData.file_name}
                    </a>
                </div>
            </div>
        </td>
        <td>
            <small class="text-muted">${dateString}</small>
        </td>
        <td>
            <div class="btn-group" role="group">
                <a href="/storage/${fileData.file_path}" 
                   target="_blank" 
                   class="btn btn-sm btn-outline-primary"
                   data-bs-toggle="tooltip" 
                   title="عرض الملف">
                    <i class="fas fa-eye"></i>
                </a>
                <button type="button" 
                        class="btn btn-sm btn-outline-danger"
                        onclick="deleteMaterialFile(${fileData.material_id}, '${fileData.file_type}')"
                        data-bs-toggle="tooltip" 
                        title="حذف الملف">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    `;
    
    tableBody.appendChild(row);
}

// التحقق من وجود ملفات مرفوعة جديدة وإضافتها للجدول
@if(session('uploaded_files'))
    document.addEventListener('DOMContentLoaded', function() {
        const uploadedFiles = @json(session('uploaded_files'));
        uploadedFiles.forEach(file => {
            addFileToTable({
                material_id: file.material_id,
                file_type: file.file_type,
                file_path: file.file_path,
                file_name: file.file_name,
                icon: file.file_info.icon,
                color: file.file_info.color,
                label: file.file_info.label
            });
        });
        
        // مسح حقول الملفات بعد الرفع الناجح
        document.querySelectorAll('#uploadMaterialFilesForm input[type="file"]').forEach(input => {
            input.value = '';
        });
        
        // إعادة تمكين زر الرفع
        const submitBtn = document.getElementById('uploadFilesBtn');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-upload me-2"></i>رفع الملفات';
        }
    });
@endif
</script>

<!-- إضافة SweetAlert2 للتأكيدات المحسنة -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* تحسين مظهر الجدول */
#materialsTable {
    font-size: 0.9rem;
}

#materialsTable th {
    background-color: #f8f9fa;
    border-top: 2px solid #dee2e6;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
}

#materialsTable td {
    vertical-align: middle;
    text-align: center;
}

#materialsTable td:nth-child(3) { /* عمود الوصف */
    text-align: right;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* تحسين أزرار الإجراءات */
.btn-toolbar .btn-group .btn {
    margin: 0 1px;
    border-radius: 0.375rem !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
    transition: all 0.3s cubic-bezier(.25,.8,.25,1);
}

.btn-toolbar .btn-group .btn:hover {
    box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
    transform: translateY(-2px);
}

.btn-toolbar .btn-group {
    margin: 0 2px;
}

/* تحسين الألوان */
.badge {
    font-weight: 600;
    font-size: 0.7rem;
    padding: 0.35em 0.65em;
}

.table td .badge {
    font-size: 0.75rem;
}

/* تحسين dropdown المرفقات */
.dropdown-menu {
    max-height: 300px;
    overflow-y: auto;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

/* تحسين البحث */
.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* تحسين badges */
.badge {
    font-size: 0.75rem;
}

/* تحسين hover effects */
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.075);
}

.table-active {
    background-color: rgba(0, 123, 255, 0.1) !important;
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.2) !important;
}
</style>
@endpush
@endsection 