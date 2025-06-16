@extends('layouts.admin')

@section('title', 'مواد أمر العمل رقم ' . $workOrder->order_number)

@push('head')
<meta name="work-order-id" content="{{ $workOrder->id }}">
<style>
    /* تنسيق عام */
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-2px);
    }
    
    /* تنسيق معلومات أمر العمل */
    .info-card {
        background: linear-gradient(to right, #f8f9fa, #ffffff);
        border-radius: 15px;
        padding: 20px;
    }
    .info-item {
        padding: 10px;
        border-radius: 8px;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }
    .info-item:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .info-item strong {
        color: #4e73df;
        font-size: 0.9rem;
    }
    
    /* تنسيق الجدول */
    .materials-table-wrapper {
        border-radius: 10px;
        overflow: hidden;
    }
    .table {
        margin-bottom: 0;
    }
    .table thead th {
        background: linear-gradient(45deg, #4e73df, #36b9cc);
        color: white;
        font-weight: 500;
        border: none;
        padding: 15px;
        white-space: nowrap;
    }
    .table tbody tr {
        transition: all 0.3s ease;
    }
    .table tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05);
    }
    .table td {
        padding: 15px;
        vertical-align: middle;
    }
    
    /* تنسيق الأزرار */
    .btn {
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-primary {
        background: linear-gradient(45deg, #4e73df, #2e59d9);
        border: none;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(46, 89, 217, 0.2);
    }
    
    /* تنسيق البحث والفلتر */
    .search-filter-card {
        background: linear-gradient(to right, #ffffff, #f8f9fa);
        border-radius: 15px;
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e3e6f0;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    
    /* تنسيق الكميات */
    .quantity-badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 500;
        background: #f8f9fa;
    }
    .quantity-badge.planned {
        background: rgba(78, 115, 223, 0.1);
        color: #4e73df;
    }
    .quantity-badge.spent {
        background: rgba(231, 74, 59, 0.1);
        color: #e74a3b;
    }
    .quantity-badge.executed {
        background: rgba(28, 200, 138, 0.1);
        color: #1cc88a;
    }
    
    /* تنسيق الفروق */
    .difference-positive {
        color: #1cc88a;
        font-weight: 500;
    }
    .difference-negative {
        color: #e74a3b;
        font-weight: 500;
    }
    .difference-zero {
        color: #858796;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    
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
            <div class="card info-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-item">
                                <strong><i class="fas fa-hashtag me-2"></i>رقم أمر العمل:</strong>
                                <span class="ms-2">{{ $workOrder->order_number }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-item">
                                <strong><i class="fas fa-tasks me-2"></i>نوع العمل:</strong>
                                <span class="ms-2">{{ $workOrder->work_type }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-item">
                                <strong><i class="fas fa-user me-2"></i>اسم المشترك:</strong>
                                <span class="ms-2">{{ $workOrder->subscriber_name }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-item">
                                <strong><i class="fas fa-boxes me-2"></i>عدد المواد:</strong>
                                <span class="badge bg-primary ms-2">{{ $materials->total() }}</span>
                            </div>
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

    <!-- قسم قائمة المواد -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center bg-primary text-white">
                    <h5 class="m-0 font-weight-bold">
                        <i class="fas fa-boxes me-2"></i>
                        قائمة المواد
                        <span class="badge bg-light text-primary ms-2">{{ $materials->total() }} مادة</span>
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        @if(request()->hasAny(['search_code', 'search_description', 'unit_filter']))
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-filter"></i> 
                                نتائج البحث: {{ $materials->total() }} من أصل {{ $materials->total() }}
                            </span>
                        @endif
                        <small class="text-light">
                            <i class="fas fa-info-circle me-1"></i>
                            نظام إدارة الملفات المستقل متاح في القسم السفلي
                        </small>
                    </div>
                </div>
                <div class="card-body">
                    @if($materials->count() > 0)
                        <div class="table-responsive materials-table-wrapper">
                            <table class="table table-bordered table-hover align-middle" id="materialsTable">
                                <thead>
                                    <tr class="bg-light">
                                        <th class="text-center" style="width: 50px">
                                            <i class="fas fa-list-ol text-muted me-1"></i>
                                            رقم المسلسل
                                        </th>
                                        <th class="text-center" style="width: 50px">
                                            <i class="fas fa-hashtag text-muted me-1"></i>
                                            السطر
                                        </th>
                                        <th style="width: 120px">
                                            <i class="fas fa-barcode text-secondary me-1"></i>
                                            الكود
                                        </th>
                                        <th>
                                            <i class="fas fa-align-left text-primary me-1"></i>
                                            الوصف
                                        </th>
                                        <th style="width: 80px">
                                            <i class="fas fa-ruler text-secondary me-1"></i>
                                            الوحدة
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
                                        <th style="width: 120px" class="text-center">
                                            <i class="fas fa-cogs text-secondary me-1"></i>
                                            الإجراءات
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materials as $index => $material)
                                        <tr class="align-middle">
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border">{{ $loop->iteration }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border">{{ $material->line ?: '-' }}</span>
                                            </td>
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
                                                <span class="badge bg-light text-dark border">{{ $material->unit }}</span>
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

    <!-- فاصل بصري بين الأقسام -->
    <hr class="section-divider">
    
    <!-- قسم إدارة الملفات المرفوعة -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="m-0 font-weight-bold">
                        <i class="fas fa-file-upload me-2"></i>
                        إدارة الملفات المستقلة
                        <span class="badge bg-light text-success ms-2">{{ isset($independentFiles) ? count($independentFiles) : 0 }} ملف</span>
                    </h5>
                </div>
                <div class="card-body">
                    <!-- فورم رفع الملفات -->
                    <form id="uploadMaterialFilesForm" action="{{ route('admin.work-orders.materials.upload-files', $workOrder) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- قسم رفع الملفات الجديدة -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info border-0 shadow-sm">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-cloud-upload-alt fa-2x text-info"></i>
                                        </div>
                                        <div>
                                            <h5 class="alert-heading text-info mb-2">
                                                رفع ملفات مستقلة
                                            </h5>
                                            <p class="mb-0 small">يمكنك رفع الملفات المطلوبة (مستقلة عن بيانات المواد) من خلال النموذج أدناه</p>
                                        </div>
                                    </div>
                                </div>
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

                    <!-- فاصل بصري -->
                    <hr class="my-5">
                    
                    <!-- قسم عرض الملفات المرفوعة -->
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-success border-0 shadow-sm mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-folder-open fa-2x text-success"></i>
                                    </div>
                                    <div>
                                                                                    <h5 class="alert-heading text-success mb-2">
                                                الملفات المستقلة المرفوعة
                                            </h5>
                                            <p class="mb-0 small">جميع الملفات المرفوعة (مستقلة عن المواد) في هذا أمر العمل</p>
                                    </div>
                                </div>
                            </div>
                                                         <div id="uploadedFilesContainer">
                                <div class="table-responsive shadow-sm">
                                    <table class="table table-hover table-striped mb-0" id="independentFilesTable">
                                        <thead class="table-dark">
                                            <tr>
                                                <th class="text-center">
                                                    <i class="fas fa-file-alt me-2"></i>
                                                    نوع الملف
                                                </th>
                                                <th class="text-center">
                                                    <i class="fas fa-calendar me-2"></i>
                                                    تاريخ الرفع
                                                </th>
                                                <th class="text-center">
                                                    <i class="fas fa-cogs me-2"></i>
                                                    الإجراءات
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($independentFiles) && count($independentFiles) > 0)
                                                @foreach($independentFiles as $file)
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
                                                        <td class="text-center">
                                                            <small class="text-muted">{{ $file['created_at']->format('Y-m-d H:i') }}</small>
                                                        </td>
                                                        <td class="text-center">
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
                                                                        onclick="deleteIndependentFile({{ $file['material_id'] }}, '{{ $file['file_type'] }}', this)"
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
                                                    <td colspan="3" class="text-center py-5">
                                                        <div class="empty-state">
                                                            <i class="fas fa-cloud-upload-alt fa-4x text-muted mb-3"></i>
                                                            <h5 class="text-muted mb-2">لا توجد ملفات مستقلة مرفوعة</h5>
                                                            <p class="text-muted small mb-3">لم يتم رفع أي ملفات مستقلة حتى الآن</p>
                                                            <div class="text-muted small">
                                                                <i class="fas fa-arrow-up me-1"></i>
                                                                استخدم النموذج أعلاه لرفع الملفات المطلوبة
                                                            </div>
                                                        </div>
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
    justify-content: center;
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



/* تحسين الشاشات الصغيرة */
@media (max-width: 768px) {
    .quantity-badge {
        padding: 0.3rem 0.6rem;
        font-size: 0.8125rem;
    }
    
    .action-buttons {
        flex-wrap: nowrap;
        gap: 0.35rem;
        justify-content: center;
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

// معالجة الملفات المرفوعة الجديدة
@if(session('uploaded_files'))
    document.addEventListener('DOMContentLoaded', function() {
        try {
            const uploadedFiles = @json(session('uploaded_files'));
            if (uploadedFiles && Array.isArray(uploadedFiles)) {
                uploadedFiles.forEach(file => {
                    if (file && file.file_info) {
                        addFileToTable({
                            material_id: file.material_id,
                            file_type: file.file_type,
                            file_path: file.file_path,
                            file_name: file.file_name,
                            icon: file.file_info.icon,
                            color: file.file_info.color,
                            label: file.file_info.label
                        });
                    }
                });
            }
            
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
        } catch (error) {
            console.error('خطأ في معالجة الملفات المرفوعة:', error);
        }
    });
@endif
</script>

<!-- إضافة SweetAlert2 للتأكيدات المحسنة -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- إضافة FontAwesome من CDN محلي أو مختلف -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

<!-- إضافة ملف JavaScript المنفصل -->
<script src="{{ asset('js/materials-page.js') }}"></script>

<script>
// حذف ملف مستقل
function deleteIndependentFile(materialId, fileType, button) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: 'سيتم حذف هذا الملف نهائياً',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذف',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            // إنشاء form مخفي للحذف
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('admin.work-orders.materials.delete-file', [$workOrder, ':material_id']) }}`.replace(':material_id', materialId);
            
            // إضافة CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // إضافة نوع الملف
            const fileTypeInput = document.createElement('input');
            fileTypeInput.type = 'hidden';
            fileTypeInput.name = 'file_type';
            fileTypeInput.value = fileType;
            form.appendChild(fileTypeInput);
            
            // إضافة الفورم للصفحة وإرساله
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

<style>
/* تحسين الفصل بين الأقسام */
.section-divider {
    border: none;
    height: 3px;
    background: linear-gradient(to right, #007bff, #28a745);
    margin: 3rem 0;
    border-radius: 2px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* تحسين مظهر الأقسام */
.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

/* تحسين حالة الفراغ */
.empty-state {
    padding: 2rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
    border: 2px dashed #dee2e6;
}

/* تحسين الـ alerts */
.alert {
    border-radius: 10px;
    border: none;
}

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