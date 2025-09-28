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
          overflow-x: auto;
          overflow-y: visible;
          max-width: 100%;
      }
      .table {
          margin-bottom: 0;
          min-width: 1600px; /* عرض أدنى للجدول */
      }
      
      /* تحسين حقول الملاحظات */
      .notes-field {
          min-width: 200px;
          max-width: 400px;
      }
      .notes-field input {
          min-width: 200px;
          max-width: 400px;
          resize: horizontal;
          overflow: visible;
          white-space: nowrap;
          text-overflow: ellipsis;
          transition: all 0.3s ease;
          border: 1px solid #ddd;
          border-radius: 4px;
          padding: 4px 8px;
      }
      
      /* عرض النص بالكامل عند التركيز */
      .notes-field input:focus {
          white-space: normal;
          text-overflow: clip;
          z-index: 10;
          position: relative;
          background: white;
          box-shadow: 0 2px 8px rgba(0,0,0,0.15);
          border-color: #4e73df;
          outline: none;
      }
      
      /* تحسين عرض النص الطويل */
      .notes-field input:not(:focus) {
          overflow: hidden;
          text-overflow: ellipsis;
      }
      
      /* إضافة مؤشر للنص المقطوع */
      .notes-field input:not(:focus)[title]:after {
          content: "...";
          color: #666;
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
        padding: 8px 12px;
        border-radius: 6px;
        font-weight: 600;
        background: #f8f9fa;
        display: inline-block;
        min-width: 60px;
        text-align: center;
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
    
    /* تنسيق جدول مقايسة المواد */
    .work-order-materials-table {
        font-size: 0.95rem;
    }
    .work-order-materials-table th {
        background: linear-gradient(45deg, #1cc88a, #17a2b8);
        color: white;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        border: none;
        padding: 15px 10px;
    }
    .work-order-materials-table td {
        vertical-align: middle;
        padding: 12px 10px;
        border-bottom: 1px solid #e3e6f0;
    }
    .work-order-materials-table .text-right {
        text-align: right;
        padding-right: 15px;
    }
    .work-order-materials-table .text-center {
        text-align: center;
    }
    .work-order-materials-table tr:hover {
        background-color: rgba(28, 200, 138, 0.05);
    }
    .work-order-materials-table .badge {
        font-size: 0.9rem;
        padding: 6px 10px;
        min-width: 50px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <span class="fs-5">المواد</span>
                        <span class="badge bg-light text-primary ms-2">{{ $materials->total() }} مادة</span>
                    </div>
                        <div class="d-flex gap-2">
                            
                            <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-right"></i> عودة الي تفاصيل أمر العمل
                            </a>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal إضافة مادة جديدة -->
    <div class="modal fade" id="addMaterialModal" tabindex="-1" aria-labelledby="addMaterialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.work-orders.materials.store', $workOrder) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addMaterialModalLabel">
                            <i class="fas fa-plus-circle me-2"></i>
                            إضافة مادة جديدة
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="code" class="form-label required">
                                        <i class="fas fa-barcode me-1"></i>
                                        كود المادة
                                    </label>
                                    <input type="text" class="form-control" id="code" name="code" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="line" class="form-label">
                                        <i class="fas fa-hashtag me-1"></i>
                                        السطر
                                    </label>
                                    <input type="text" class="form-control" id="line" name="line">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-1"></i>
                                وصف المادة
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="unit" class="form-label required">
                                        <i class="fas fa-ruler me-1"></i>
                                        الوحدة
                                    </label>
                                    <select class="form-select" id="unit" name="unit" required>
                                        <option value="L.M">L.M</option>
                                        <option value="Ech">Ech</option>
                                        <option value="Kit">Kit</option>                     
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="planned_quantity" class="form-label">
                                <i class="fas fa-chart-line me-1 text-info"></i>
                                الكمية المخططة
                            </label>
                            <input type="number" class="form-control" id="planned_quantity" name="planned_quantity" 
                                   step="0.01" min="0" value="0" placeholder="أدخل الكمية المخططة"
                                   oninput="updateModalPreview()">
                            <div class="form-text">الكمية المطلوبة حسب التخطيط الأولي للمشروع</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="spent_quantity" class="form-label">
                                        <i class="fas fa-box me-1"></i>
                                        الكمية المصروفة
                                    </label>
                                    <input type="number" class="form-control" id="spent_quantity" name="spent_quantity" 
                                           step="0.01" min="0" value="0" oninput="updateModalPreview()">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="executed_quantity" class="form-label">
                                        <i class="fas fa-tasks me-1"></i>
                                        الكمية المنفذة
                                    </label>
                                    <input type="number" class="form-control" id="executed_quantity" name="executed_quantity" 
                                           step="0.01" min="0" value="0" oninput="updateModalPreview()">
                                </div>
                            </div>
                        </div>
                        
                        <!-- معاينة الفروق -->
                        <div class="alert alert-info" id="modalPreview" style="display: none;">
                            <h6 class="mb-2">
                                <i class="fas fa-calculator me-1"></i>
                                معاينة الفروق
                            </h6>
                            <div class="row text-center">
                                <div class="col-md-6">
                                    <small class="text-muted d-block">الفرق (مصروفة - مخططة)</small>
                                    <span class="badge" id="plannedDiffPreview">0.00</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">الفرق (مصروفة - منفذة)</small>
                                    <span class="badge" id="executedDiffPreview">0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>
                            إلغاء
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            حفظ المادة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Work Order Info -->
    <div class="row mb-4">
        <div class="col-12">
        <div class="card-body py-3">
            <div class="card info-card">
                <div class="card-body">
                    <div class="row">
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
                               
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

   

    <!-- قسم عرض جميع مواد المقايسة -->
    @if($workOrderMaterials && $workOrderMaterials->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border-info">
                <div class="card-header bg-info text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="m-0 font-weight-bold">
                            <i class="fas fa-clipboard-list me-2"></i>
                            جدول مقايسة المواد الكاملة
                            <span class="badge bg-light text-info ms-2">{{ $workOrderMaterials->count() }} مادة</span>
                            <p class="text-white fs-10">ملحوظه : يتم اختيار الكمية المخططة اثناء الصرف من الكهرباء</p>
                        </h5>
                        <div class="d-flex gap-2">
                           
                            
                        </div>
                    </div>
                </div>
                <div class="card-body" id="workOrderMaterialsTable">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover work-order-materials-table">
                            <thead class="table-info">
                                <tr>
                                    <th width="8%" class="text-center">
                                        <i class="fas fa-list-ol me-1"></i>
                                        #
                                    </th>
                                    <th width="15%" class="text-center">
                                        <i class="fas fa-barcode me-1"></i>
                                        كود المادة
                                    </th>
                                    <th width="35%">
                                        <i class="fas fa-align-left me-1"></i>
                                        وصف المادة
                                    </th>
                                    <th width="12%" class="text-center">
                                        <i class="fas fa-ruler me-1"></i>
                                        الوحدة
                                    </th>
                                    <th width="12%" class="text-center">
                                        <i class="fas fa-calculator me-1"></i>
                                        الكمية
                                    </th>
                                    <th width="8%" class="text-center">
                                        <i class="fas fa-cogs me-1"></i>
                                        إجراء
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workOrderMaterials as $index => $workOrderMaterial)
                                @php
                                    $existsInSystem = $materials->where('code', $workOrderMaterial->material->code ?? '')->count() > 0;
                                @endphp
                                <tr class="{{ $existsInSystem ? 'table-success' : 'table-warning' }}">
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <span class="badge {{ $existsInSystem ? 'bg-success' : 'bg-warning' }} text-white">
                                                {{ $workOrderMaterial->material->code ?? 'غير محدد' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="material-description">
                                            {{ $workOrderMaterial->material->description ?? $workOrderMaterial->material->name ?? 'بدون وصف' }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">
                                            {{ $workOrderMaterial->material->unit ?? 'غير محدد' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary fs-6">
                                            {{ number_format($workOrderMaterial->quantity, 2) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            @if(!$existsInSystem)
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-success add-material-btn" 
                                                        title="إضافة للنظام"
                                                        data-material-code="{{ $workOrderMaterial->material->code ?? '' }}"
                                                        data-material-description="{{ $workOrderMaterial->material->description ?? '' }}"
                                                        data-material-name="{{ $workOrderMaterial->material->name ?? $workOrderMaterial->material->description ?? '' }}"
                                                        data-material-unit="{{ $workOrderMaterial->material->unit ?? '' }}"
                                                        data-planned-quantity="{{ $workOrderMaterial->quantity ?? 0 }}"
                                                        data-work-order-id="{{ $workOrder->id }}"
                                                        data-row-index="{{ $index }}">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            @else
                                                <span class="text-success me-2">
                                                    <i class="fas fa-check-circle"></i>
                                                </span>
                                            @endif
                                            
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="حذف من المقايسة"
                                                    onclick="deleteWorkOrderMaterial({{ $workOrderMaterial->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- معلومات إضافية -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            
                        </div>
                        <div class="col-md-6">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- قسم قائمة المواد -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <h5 class="m-0 font-weight-bold">
                            <i class="fas fa-boxes me-2"></i>
                            قائمة المواد
                            <span class="badge bg-light text-primary ms-2">{{ $materials->total() }} مادة</span>
                        </h5>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
                            <i class="fas fa-plus"></i> إضافة مادة جديدة
                        </button>
                        <a href="{{ route('admin.work-orders.materials.export', $workOrder) }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-file-excel me-1"></i> تصدير إكسل
                        </a>
                        @if(request()->hasAny(['search_code', 'search_description', 'unit_filter']))
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-filter"></i> 
                                نتائج البحث: {{ $materials->total() }} من أصل {{ $materials->total() }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($materials->count() > 0)
                        <div class="table-responsive materials-table-wrapper">
                            <table class="table table-bordered table-hover align-middle" id="materialsTable">
                                <thead>
                                    <!-- هيدر رئيسي للشركتين -->
                                    <tr class="bg-primary text-white">
                                        <th class="text-center" width="5%">
                                            <!-- عمود فارغ للترقيم -->
                                        </th>
                                        <th class="text-center fw-bold" colspan="8" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%); border: 2px solid #fff;">
                                            <i class="fas fa-building me-2"></i>
                                            الشركة السعودية للكهرباء
                                            <i class="fas fa-bolt ms-2"></i>
                                        </th>
                                        <th class="text-center fw-bold" colspan="5" style="background: linear-gradient(135deg, #198754 0%, #157347 100%); border: 2px solid #fff;">
                                            <i class="fas fa-city me-2"></i>
                                            شركة سهم بلدي
                                            <i class="fas fa-handshake ms-2"></i>
                                        </th>
                                    </tr>
                                    <tr class="bg-light">
                                        <th class="text-center" width="5%">
                                            <i class="fas fa-list-ol text-muted me-1"></i>
                                            #
                                        </th>
                                        <th class="text-center" width="5%">
                                            <i class="fas fa-hashtag text-muted me-1"></i>
                                            السطر
                                        </th>
                                        <th width="12%">
                                            <i class="fas fa-barcode text-secondary me-1"></i>
                                            الكود
                                        </th>
                                        <th width="20%">
                                            <i class="fas fa-align-left text-primary me-1"></i>
                                            الوصف
                                        </th>
                                        <th class="text-center" width="6%">
                                            <i class="fas fa-ruler text-secondary me-1"></i>
                                            الوحدة
                                        </th>
                                        <th class="text-center" width="8%">
                                            <i class="fas fa-chart-line text-info me-1"></i>
                                            الكمية<br>المخططة
                                        </th>
                                        <th class="text-center" width="8%">
                                            <i class="fas fa-box text-danger me-1"></i>
                                            الكمية<br>المصروفة
                                        </th>
                                        <th class="text-center" width="7%">
                                            <i class="fas fa-calculator text-warning me-1"></i>
                                            الفرق<br>(مصروفة - مخططة)
                                        </th>
                                        <th class="text-center" width="20%">
                                            <i class="fas fa-sticky-note text-info me-1"></i>
                                            ملاحظات<br>المصروفة
                                        </th>
                                        <th class="text-center" width="6%">
                                            <i class="fas fa-tasks text-success me-1"></i>
                                            الكمية<br>المنفذة
                                        </th>
                                        <th class="text-center" width="5%">
                                            <i class="fas fa-calculator text-primary me-1"></i>
                                            الفرق<br>(مصروفة - منفذة)
                                        </th>
                                        <th class="text-center" width="20%">
                                            <i class="fas fa-clipboard-check text-success me-1"></i>
                                            ملاحظات<br>المنفذة
                                        </th>
                                        <th class="text-center" width="6%">
                                            <i class="fas fa-plus-circle text-info me-1"></i>
                                            أذن ارتجاع
                                        </th>
                                        <th class="text-center" width="6%">
                                            <i class="fas fa-undo text-warning me-1"></i>
                                            أذن صرف
                                        </th>
                                        <th class="text-center" width="7%">
                                            <i class="fas fa-balance-scale text-success me-1"></i>
                                            الرصيد<br>النهائي
                                        </th>
                                        <th class="text-center" width="5%">
                                            <i class="fas fa-cogs text-secondary me-1"></i>
                                            إجراءات
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
                                                @php
                                                    $workOrderMaterial = $workOrderMaterials->where('material.code', $material->code)->first();
                                                @endphp
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-light text-dark border">
                                                        {{ $workOrderMaterial ? $workOrderMaterial->material->code : $material->code }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-wrap" style="max-width: 300px;">
                                                    {{ $workOrderMaterial ? ($workOrderMaterial->material->description ?? $workOrderMaterial->material->name) : $material->description }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border">
                                                    {{ $workOrderMaterial ? $workOrderMaterial->material->unit : $material->unit }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="quantity-badge planned">
                                                    {{ number_format($material->planned_quantity, 2) }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="quantity-badge">
                                                    <input type="number" 
                                                           class="quantity-input spent-quantity" 
                                                           value="{{ number_format($material->spent_quantity, 2) }}"
                                                           data-material-id="{{ $material->id }}"
                                                           data-original-value="{{ $material->spent_quantity }}"
                                                           data-planned-quantity="{{ $material->planned_quantity }}"
                                                           step="0.01"
                                                           min="0"
                                                           style="width: 60px; text-align: center; padding: 2px 5px; font-size: 0.9rem;">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $plannedSpentDiff = ($material->spent_quantity ?? 0) - ($material->planned_quantity ?? 0);
                                                @endphp
                                                <div class="quantity-badge difference {{ $plannedSpentDiff > 0 ? 'warning' : ($plannedSpentDiff < 0 ? 'danger' : 'success') }}"
                                                     id="planned-spent-diff-{{ $material->id }}"
                                                     data-bs-toggle="tooltip" 
                                                     title="{{ $plannedSpentDiff > 0 ? 'تم صرف كمية أكثر من المخطط' : ($plannedSpentDiff < 0 ? 'يوجد كمية مخططة لم يتم صرفها' : 'متطابقة') }}">
                                                    <span class="diff-value">
                                                        @if($plannedSpentDiff == 0)
                                                            <i class="fas fa-check"></i>
                                                        @else
                                                            {{ $plannedSpentDiff > 0 ? '+' : '' }}{{ number_format($plannedSpentDiff, 2) }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="notes-field">
                                                    <input type="text" 
                                                           class="form-control form-control-sm spent-notes-input" 
                                                           value="{{ $material->spent_notes ?? '' }}"
                                                           data-material-id="{{ $material->id }}"
                                                           placeholder="ملاحظات المصروفة..."
                                                           style="font-size: 0.85rem; text-align: right; padding: 4px 8px;">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="quantity-badge">
                                                    <input type="number" 
                                                           class="quantity-input executed-quantity" 
                                                           value="{{ number_format($material->executed_quantity ?? 0, 2) }}"
                                                           data-material-id="{{ $material->id }}"
                                                           data-original-value="{{ $material->executed_quantity }}"
                                                           data-spent-quantity="{{ $material->spent_quantity }}"
                                                           step="0.01"
                                                           min="0"
                                                           style="width: 60px; text-align: center; padding: 2px 5px; font-size: 0.9rem;">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $executedSpentDiff = ($material->spent_quantity ?? 0) - ($material->executed_quantity ?? 0);
                                                @endphp
                                                <div class="quantity-badge difference {{ $executedSpentDiff > 0 ? 'warning' : ($executedSpentDiff < 0 ? 'danger' : 'success') }}"
                                                     id="executed-spent-diff-{{ $material->id }}"
                                                     data-bs-toggle="tooltip" 
                                                     title="{{ $executedSpentDiff > 0 ? 'تم صرف كمية أكثر من المنفذة' : ($executedSpentDiff < 0 ? 'تم تنفيذ كمية أكثر من المصروفة' : 'متطابقة') }}">
                                                    <span class="diff-value">
                                                        @if($executedSpentDiff == 0)
                                                            <i class="fas fa-check"></i>
                                                        @else
                                                            {{ $executedSpentDiff > 0 ? '+' : '' }}{{ number_format($executedSpentDiff, 2) }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="notes-field">
                                                    <input type="text" 
                                                           class="form-control form-control-sm executed-notes-input" 
                                                           value="{{ $material->executed_notes ?? '' }}"
                                                           data-material-id="{{ $material->id }}"
                                                           placeholder="ملاحظات المنفذة..."
                                                           style="font-size: 0.85rem; text-align: right; padding: 4px 8px;">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="quantity-badge">
                                                    <input type="number" 
                                                           class="quantity-input completion-quantity" 
                                                           value="{{ number_format($material->completion_quantity ?? 0, 2) }}"
                                                           data-material-id="{{ $material->id }}"
                                                           data-original-value="{{ $material->completion_quantity }}"
                                                           step="0.01"
                                                           min="0"
                                                           placeholder="0.00"
                                                           style="width: 60px; text-align: center; padding: 2px 5px; font-size: 0.85rem;">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="quantity-badge">
                                                    <input type="number" 
                                                           class="quantity-input recovery-quantity" 
                                                           value="{{ number_format($material->recovery_quantity ?? 0, 2) }}"
                                                           data-material-id="{{ $material->id }}"
                                                           data-original-value="{{ $material->recovery_quantity }}"
                                                           step="0.01"
                                                           min="0"
                                                           placeholder="0.00"
                                                           style="width: 60px; text-align: center; padding: 2px 5px; font-size: 0.85rem;">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $finalBalance = ($material->spent_quantity ?? 0) + ($material->recovery_quantity ?? 0) - ($material->executed_quantity ?? 0) - ($material->completion_quantity ?? 0);
                                                @endphp
                                                <div class="quantity-badge final-balance {{ $finalBalance > 0 ? 'success' : ($finalBalance < 0 ? 'danger' : 'info') }}"
                                                     id="final-balance-{{ $material->id }}"
                                                     data-bs-toggle="tooltip" 
                                                     title="الرصيد النهائي: {{ $finalBalance > 0 ? 'يوجد رصيد متبقي' : ($finalBalance < 0 ? 'رصيد سالب (نقص)' : 'رصيد متوازن') }}">
                                                    <span class="balance-value">
                                                        @if($finalBalance == 0)
                                                            <i class="fas fa-balance-scale"></i>
                                                        @else
                                                            {{ $finalBalance > 0 ? '+' : '' }}{{ number_format($finalBalance, 2) }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="action-buttons">
                                                    <div class="btn-group">
                                                        <button type="button" 
                                                                class="btn btn-action btn-delete"
                                                                onclick="deleteMaterial({{ $material->id }})"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                title="حذف">
                                                            <i class="fas fa-trash"></i>
                                                            <span class="btn-text">حذف</span>
                                                        </button>

                                                    </div>
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

    <!-- قسم الإزالة والسكراب -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <h5 class="m-0 font-weight-bold">
                            <i class="fas fa-trash-alt me-2"></i>
                            في حالة وجود إزالة أو سكراب على أمر العمل
                        </h5>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#addRemovalScrapModal">
                            <i class="fas fa-plus"></i> إضافة مادة اسكراب
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="updateRemovalScrapTable()">
                            <i class="fas fa-sync"></i> عرض مواد الازالةوالسكراب المضافة
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" id="removalScrapTable">
                            <thead>
                                <tr class="bg-light">
                                    <th class="text-center" width="5%">
                                        <i class="fas fa-list-ol text-muted me-1"></i>
                                        #
                                    </th>
                                    <th width="15%">
                                        <i class="fas fa-barcode text-secondary me-1"></i>
                                        كود المادة
                                    </th>
                                    <th width="35%">
                                        <i class="fas fa-align-left text-primary me-1"></i>
                                        وصف المادة
                                    </th>
                                    <th class="text-center" width="10%">
                                        <i class="fas fa-ruler text-secondary me-1"></i>
                                        الوحدة
                                    </th>
                                    <th class="text-center" width="10%">
                                        <i class="fas fa-calculator text-danger me-1"></i>
                                        الكمية
                                    </th>
                                    <th width="20%">
                                        <i class="fas fa-sticky-note text-info me-1"></i>
                                        ملاحظات
                                    </th>
                                    <th class="text-center" width="5%">
                                        <i class="fas fa-cogs text-secondary me-1"></i>
                                        إجراءات
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="removalScrapTableBody">
                                <!-- سيتم إضافة البيانات هنا عبر JavaScript -->
                                <tr id="noRemovalScrapRow">
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle fs-3 text-info mb-2"></i>
                                        <br>لا توجد مواد مضافة للإزالة أو السكراب
                                        <br><small>استخدم زر "إضافة مادة للإزالة" لإضافة مواد جديدة</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal إضافة مادة للإزالة -->
    <div class="modal fade" id="addRemovalScrapModal" tabindex="-1" aria-labelledby="addRemovalScrapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="addRemovalScrapModalLabel">
                        <i class="fas fa-trash-alt me-2"></i>
                        إضافة مادة للإزالة أو السكراب
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addRemovalScrapForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="removal_material_code" class="form-label fw-bold">
                                    <i class="fas fa-barcode me-1 text-secondary"></i>
                                    كود المادة
                                </label>
                                <input type="text" class="form-control" id="removal_material_code" name="material_code" required placeholder="أدخل كود المادة">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="removal_material_unit" class="form-label fw-bold">
                                    <i class="fas fa-ruler me-1 text-secondary"></i>
                                    الوحدة
                                </label>
                                <input type="text" class="form-control" id="removal_material_unit" name="unit" required placeholder="مثال: متر، قطعة، كيلو">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="removal_material_description" class="form-label fw-bold">
                                    <i class="fas fa-align-left me-1 text-primary"></i>
                                    وصف المادة
                                </label>
                                <textarea class="form-control" id="removal_material_description" name="material_description" rows="2" required placeholder="أدخل وصف المادة"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="removal_quantity" class="form-label fw-bold">
                                    <i class="fas fa-calculator me-1 text-danger"></i>
                                    الكمية
                                </label>
                                <input type="number" class="form-control" id="removal_quantity" name="quantity" step="0.01" min="0" required placeholder="0.00">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="removal_notes" class="form-label fw-bold">
                                    <i class="fas fa-sticky-note me-1 text-info"></i>
                                    ملاحظات
                                </label>
                                <input type="text" class="form-control" id="removal_notes" name="notes" placeholder="ملاحظات إضافية (اختياري)">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>إلغاء
                    </button>
                    <button type="button" class="btn btn-warning" id="saveRemovalScrapBtn">
                        <i class="fas fa-plus me-1"></i>إضافة المادة
                    </button>
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
                        أرفاق ملفات  
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
                                                  أرفاق ملفات
                                            </h5>
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
                                       id="check_in_file" name="check_in_file[]" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                       multiple
                                       onchange="previewSelectedFiles(this, 'check_in_preview')">
                                @error('check_in_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">PDF, JPG, PNG, DOC (حد أقصى 10MB لكل ملف) - يمكن اختيار عدة ملفات</small>
                                <div id="check_in_preview" class="mt-2"></div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="gate_pass_file" class="form-label">
                                    <i class="fas fa-id-card me-2 text-success"></i>
                                    GATE PASS
                                </label>
                                <input type="file" class="form-control @error('gate_pass_file') is-invalid @enderror" 
                                       id="gate_pass_file" name="gate_pass_file[]" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                       multiple
                                       onchange="previewSelectedFiles(this, 'gate_pass_preview')">
                                @error('gate_pass_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">PDF, JPG, PNG, DOC (حد أقصى 10MB لكل ملف) - يمكن اختيار عدة ملفات</small>
                                <div id="gate_pass_preview" class="mt-2"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="stock_in_file" class="form-label">
                                    <i class="fas fa-sign-in-alt me-2 text-info"></i>
                                    STORE IN
                                </label>
                                <input type="file" class="form-control @error('stock_in_file') is-invalid @enderror" 
                                       id="stock_in_file" name="stock_in_file[]" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                       multiple
                                       onchange="previewSelectedFiles(this, 'stock_in_preview')">
                                @error('stock_in_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">PDF, JPG, PNG, DOC (حد أقصى 10MB لكل ملف) - يمكن اختيار عدة ملفات</small>
                                <div id="stock_in_preview" class="mt-2"></div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="stock_out_file" class="form-label">
                                    <i class="fas fa-sign-out-alt me-2 text-warning"></i>
                                    STORE OUT
                                </label>
                                <input type="file" class="form-control @error('stock_out_file') is-invalid @enderror" 
                                       id="stock_out_file" name="stock_out_file[]" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                       multiple
                                       onchange="previewSelectedFiles(this, 'stock_out_preview')">
                                @error('stock_out_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">PDF, JPG, PNG, DOC (حد أقصى 10MB لكل ملف) - يمكن اختيار عدة ملفات</small>
                                <div id="stock_out_preview" class="mt-2"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ddo_file" class="form-label">
                                    <i class="fas fa-file-alt me-2 text-primary"></i>
                                    DDO
                                </label>
                                <input type="file" class="form-control @error('ddo_file') is-invalid @enderror" 
                                       id="ddo_file" name="ddo_file[]" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                       multiple
                                       onchange="previewSelectedFiles(this, 'ddo_preview')">
                                @error('ddo_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">PDF, JPG, PNG, DOC (حد أقصى 10MB لكل ملف) - يمكن اختيار عدة ملفات</small>
                                <div id="ddo_preview" class="mt-2"></div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="check_out_file" class="form-label">
                                    <i class="fas fa-check-circle me-2 text-success"></i>
                                     Upload
                                </label>
                                <input type="file" class="form-control @error('check_out_file') is-invalid @enderror" 
                                       id="check_out_file" name="check_out_file[]" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                       multiple
                                       onchange="previewSelectedFiles(this, 'check_out_preview')">
                                @error('check_out_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">PDF, JPG, PNG, DOC (حد أقصى 10MB لكل ملف) - يمكن اختيار عدة ملفات</small>
                                <div id="check_out_preview" class="mt-2"></div>
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

                    <!-- عرض رسائل النجاح والخطأ -->
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                        <div class="d-flex align-items-center">
                            
                            <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                            <div>
                                <h5 class="alert-heading mb-1">تم بنجاح!</h5>
                                <p class="mb-0">{{ session('success') }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle fa-2x text-danger me-3"></i>
                            <div>
                                <h5 class="alert-heading mb-1">خطأ!</h5>
                                <p class="mb-0">{{ session('error') }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show mt-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning me-3"></i>
                            <div>
                                <h5 class="alert-heading mb-1">تنبيه!</h5>
                                <p class="mb-0">{{ session('warning') }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

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
                                                الملفات المرفوعة
                                            </h5>
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
                                            @php
                                                // استخدام الملفات من الـ controller
                                                $independentMaterials = $independentFiles ?? [];
                                            @endphp
                                            
                                            @if(count($independentMaterials) > 0)
                                                @foreach($independentMaterials as $fileData)
                                                    @php
                                                        // استخدام البيانات الجاهزة من الـ controller
                                                        $fileType = $fileData['file_info']['label'];
                                                        $icon = $fileData['file_info']['icon'];
                                                        $color = $fileData['file_info']['color'];
                                                        $filePath = $fileData['file_path'];
                                                        $fileName = $fileData['file_name'];
                                                        $materialId = $fileData['material_id'];
                                                        $createdAt = $fileData['created_at'];
                                                    @endphp
                                                    
                                                    @if($filePath)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex flex-column">
                                                                <div class="d-flex align-items-center mb-1">
                                                                    <i class="{{ $icon }} {{ $color }} me-2"></i>
                                                                    <span class="fw-bold">{{ $fileType }}</span>
                                                                </div>
                                                                <div class="ms-3">
                                                                    <span class="text-muted small">
                                                                        <i class="fas fa-file me-1"></i>
                                                                        {{ $fileName }}
                                                                    </span>
                                                                </div>
                                                                <div class="ms-3">
                                                                    <small class="text-info">ملف {{ $fileType }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <small class="text-muted">{{ $createdAt->format('Y-m-d H:i') }}</small>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="btn-group" role="group">
                                                                <a href="{{ Storage::url($filePath) }}" 
                                                                   target="_blank" 
                                                                   class="btn btn-sm btn-outline-primary"
                                                                   data-bs-toggle="tooltip" 
                                                                   title="عرض الملف">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-danger"
                                                                        onclick="deleteIndependentFile({{ $materialId }}, 'material', this)"
                                                                        data-bs-toggle="tooltip" 
                                                                        title="حذف الملف">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="3" class="text-center py-5">
                                                        <div class="empty-state">
                                                            <i class="fas fa-cloud-upload-alt fa-4x text-muted mb-3"></i>
                                                            <h5 class="text-muted mb-2">لا توجد ملفات مرفوعة</h5>
                                                            <p class="text-muted small mb-3">لم يتم رفع أي ملفات حتى الآن</p>
                                                            <div class="text-muted small">
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
                    <!-- قسم ملاحظات المواد -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-sticky-note me-2"></i>
                        ملاحظات المواد
                        <small class="ms-2 opacity-75">(يتم الحفظ تلقائياً)</small>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="materials_notes" class="form-label">الملاحظات:</label>
                        <textarea 
                            class="form-control" 
                            id="materials_notes" 
                            name="materials_notes" 
                            rows="4" 
                            placeholder="اكتب ملاحظاتك حول المواد هنا..."
                            data-work-order-id="{{ $workOrder->id }}"
                        >{{ $workOrder->materials_notes ?? '' }}</textarea>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            يتم حفظ الملاحظات تلقائياً عند التوقف عن الكتابة
                            <span id="save-status" class="ms-3"></span>
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
    background: linear-gradient(135deg, #dc3545, #c82333);
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

/* تحسين الشاشات الصغيرة جداً */
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

/* تحسين عرض مقايسة المواد */
.work-order-indicator {
    position: relative;
    display: inline-block;
}

.work-order-indicator .badge {
    position: relative;
    z-index: 1;
}

.work-order-indicator::after {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, #28a745, #20c997);
    border-radius: 50px;
    z-index: 0;
    opacity: 0.1;
}

.material-description-container {
    max-height: 80px;
    overflow-y: auto;
}

.material-description-container::-webkit-scrollbar {
    width: 4px;
}

.material-description-container::-webkit-scrollbar-thumb {
    background-color: #dee2e6;
    border-radius: 2px;
}

.material-description-container::-webkit-scrollbar-thumb:hover {
    background-color: #adb5bd;
}

/* تحسين عرض الوحدات المختلفة */
.unit-comparison {
    position: relative;
}

.unit-comparison .badge {
    transition: all 0.3s ease;
}

.unit-comparison:hover .badge {
    transform: scale(1.05);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* تحسين مظهر الملخص */
.summary-stat {
    transition: all 0.3s ease;
    cursor: pointer;
}

.summary-stat:hover {
    transform: translateY(-2px);
}

.summary-stat .display-6 {
    font-weight: 700;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}

/* تحسين جدول مقايسة المواد */
.work-order-materials-table {
    font-size: 0.9rem;
    margin-bottom: 0;
}

.work-order-materials-table th {
    background: linear-gradient(135deg, #17a2b8, #138496);
    color: white;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    border: none;
    padding: 12px 8px;
    white-space: nowrap;
}

.work-order-materials-table td {
    vertical-align: middle;
    padding: 10px 8px;
    border-bottom: 1px solid #e3e6f0;
}

.work-order-materials-table tbody tr {
    transition: all 0.3s ease;
}

.work-order-materials-table tbody tr:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.work-order-materials-table .table-success {
    background-color: rgba(25, 135, 84, 0.1) !important;
    border-left: 4px solid #198754;
}

.work-order-materials-table .table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
    border-left: 4px solid #ffc107;
}

.work-order-materials-table .material-description {
    max-width: 250px;
    word-wrap: break-word;
    line-height: 1.4;
}

.work-order-materials-table .badge {
    font-size: 0.8rem;
    padding: 0.4em 0.6em;
    font-weight: 500;
}

/* تحسين أزرار الإضافة */
.create-material-form .btn {
    transition: all 0.3s ease;
}

.create-material-form .btn:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
}

/* تحسين الـ alerts */
.alert {
    border: none;
    border-radius: 10px;
    padding: 1rem 1.25rem;
}

.alert-success {
    background: linear-gradient(135deg, rgba(25, 135, 84, 0.1), rgba(25, 135, 84, 0.05));
    border-left: 4px solid #198754;
}

.alert-warning {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 193, 7, 0.05));
    border-left: 4px solid #ffc107;
}

/* تحسين card الجدول */
.border-info {
    border-color: #17a2b8 !important;
    border-width: 2px !important;
}

.card-header.bg-info {
    background: linear-gradient(135deg, #17a2b8, #138496) !important;
}

/* تأثيرات الانتقال */
#workOrderMaterialsTable {
    transition: all 0.3s ease;
}

/* تحسين responsive */
@media (max-width: 768px) {
    .work-order-materials-table {
        font-size: 0.8rem;
    }
    
    .work-order-materials-table th,
    .work-order-materials-table td {
        padding: 8px 4px;
    }
    
    .work-order-materials-table .material-description {
        max-width: 150px;
        font-size: 0.75rem;
    }
    
    .work-order-materials-table .badge {
        font-size: 0.7rem;
        padding: 0.3em 0.5em;
    }
}

/* تنسيق حقول الإدخال */
.quantity-input {
    background: transparent;
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 4px;
    padding: 4px;
    color: inherit;
    font-weight: inherit;
    transition: all 0.3s ease;
    width: 80px;
    text-align: center;
}

.quantity-input:focus {
    outline: none;
    border-color: #4e73df;
    box-shadow: 0 0 0 2px rgba(78,115,223,0.25);
    background-color: rgba(78,115,223,0.05);
}

.quantity-input:hover {
    border-color: #4e73df;
}

.quantity-input::-webkit-inner-spin-button,
.quantity-input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.quantity-input[type=number] {
    -moz-appearance: textfield;
}

/* تنسيق مؤشر التحميل */
.quantity-badge {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 100px;
}

.spinner-border {
    position: absolute;
    right: -1.5rem;
}

/* تنسيق أيقونة النجاح */
.fa-check.text-success {
    position: absolute;
    right: -1.5rem;
    animation: fadeInOut 1s ease;
}

@keyframes fadeInOut {
    0% { opacity: 0; transform: scale(0.8); }
    20% { opacity: 1; transform: scale(1.2); }
    50% { opacity: 1; transform: scale(1); }
    100% { opacity: 0; transform: scale(0.8); }
}

/* تحسين مظهر الفروق */
.quantity-badge.difference {
    min-width: 60px;
    position: relative;
}

.quantity-badge.difference.success {
    background-color: rgba(40, 167, 69, 0.1);
    border: 1px solid #28a745;
    color: #28a745;
}

/* تنسيق الرصيد النهائي */
.quantity-badge.final-balance {
    min-width: 65px;
    font-weight: bold;
    border-radius: 8px;
    padding: 0.4rem 0.6rem;
}

.quantity-badge.final-balance.success {
    background-color: rgba(40, 167, 69, 0.15);
    border: 1px solid #28a745;
    color: #28a745;
}

.quantity-badge.final-balance.danger {
    background-color: rgba(220, 53, 69, 0.15);
    border: 1px solid #dc3545;
    color: #dc3545;
}

.quantity-badge.final-balance.info {
    background-color: rgba(23, 162, 184, 0.15);
    border: 1px solid #17a2b8;
    color: #17a2b8;
}

.quantity-badge.difference.warning {
    background-color: rgba(255, 193, 7, 0.1);
    border: 1px solid #ffc107;
    color: #ffc107;
}

.quantity-badge.difference.danger {
    background-color: rgba(220, 53, 69, 0.1);
    border: 1px solid #dc3545;
    color: #dc3545;
}

/* تحسين التفاعلية */
.quantity-badge {
    transition: all 0.3s ease;
}

.quantity-badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* تنسيق الخلية عند التحرير */
td.editing {
    background-color: rgba(78,115,223,0.05);
    box-shadow: inset 0 0 0 2px #4e73df;
    border-radius: 4px;
}

/* تحسين عرض الأرقام */
.quantity-input {
    font-family: 'Courier New', monospace;
    letter-spacing: 0.5px;
}

/* تنسيق النافذة المنبثقة */
.modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-header {
    border-radius: 15px 15px 0 0;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.modal-footer {
    border-top: 1px solid #dee2e6;
    background-color: #f8f9fa;
    border-radius: 0 0 15px 15px;
}

/* تنسيق الحقول */
.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e3e6f0;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

/* تنسيق الأيقونات */
.modal-body .fas {
    color: #4e73df;
    width: 20px;
    text-align: center;
}

/* تمييز الحقول المطلوبة */
.required::after {
    content: ' *';
    color: #e74a3b;
}

/* تحسين أزرار النافذة */
.modal .btn {
    padding: 0.5rem 1.5rem;
    font-weight: 500;
    border-radius: 8px;
}

.modal .btn-primary {
    background: linear-gradient(45deg, #4e73df, #2e59d9);
    border: none;
}

.modal .btn-primary:hover {
    background: linear-gradient(45deg, #2e59d9, #1d3db7);
    transform: translateY(-1px);
}

/* تحسين حقول الإدخال */
.quantity-input {
    width: 60px !important;
    text-align: center;
    padding: 2px 5px;
    font-size: 0.9rem;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    background-color: #fff;
    transition: all 0.2s ease;
}

.quantity-input:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    outline: none;
    background-color: #f8f9fa;
}

.quantity-input:hover {
    border-color: #4e73df;
}

/* إخفاء أسهم الزيادة والنقصان */
.quantity-input::-webkit-inner-spin-button,
.quantity-input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.quantity-input[type=number] {
    -moz-appearance: textfield;
}

/* تحسين مظهر الخلية */
.quantity-badge {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    min-height: 28px;
}

/* تحسين مظهر الفروق */
.difference {
    min-width: 50px;
    font-size: 0.85rem;
    padding: 2px 5px;
}

/* تحسين عرض الأرقام */
.quantity-input, .difference {
    font-family: 'Courier New', monospace;
    letter-spacing: 0.5px;
}

/* تحسين مؤشر التحميل */
.spinner-border {
    width: 1rem !important;
    height: 1rem !important;
    position: absolute;
    right: -1.2rem;
}

/* تحسين أيقونة النجاح */
.fa-check.text-success {
    position: absolute;
    right: -1.2rem;
    font-size: 0.8rem;
}

/* تحسين الخلية عند التحرير */
td.editing {
    background-color: rgba(78, 115, 223, 0.05);
}

/* تحسين عرض الأخطاء */
.invalid-feedback {
    position: absolute;
    bottom: -20px;
    left: 50%;
    transform: translateX(-50%);
    white-space: nowrap;
    font-size: 0.75rem;
    background-color: #fff;
    padding: 2px 5px;
    border: 1px solid #e74a3b;
    border-radius: 3px;
    z-index: 1;
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
// حذف ملف مستقل (حذف المادة التي تحتوي على الملف)
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
            // إظهار مؤشر التحميل
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            // إرسال طلب الحذف
            fetch(`{{ route('admin.work-orders.materials.destroy', [$workOrder, ':material_id']) }}`.replace(':material_id', materialId), {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // إزالة الصف من الجدول
                    const row = button.closest('tr');
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(-20px)';
                    
                    setTimeout(() => {
                        row.remove();
                        
                        // التحقق من وجود ملفات أخرى
                        const tbody = document.querySelector('#independentFilesTable tbody');
                        if (tbody.children.length === 0) {
                            tbody.innerHTML = `
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-cloud-upload-alt fa-4x text-muted mb-3"></i>
                                            <h5 class="text-muted mb-2">لا توجد ملفات مستقلة مرفوعة</h5>
                                            <p class="text-muted small mb-3">لم يتم رفع أي ملفات مستقلة حتى الآن</p>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحذف بنجاح',
                            text: 'تم حذف الملف نهائياً',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }, 300);
                } else {
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-trash"></i>';
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ في الحذف',
                        text: data.message || 'حدث خطأ أثناء حذف الملف'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-trash"></i>';
                
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ في الاتصال',
                    text: 'حدث خطأ أثناء الاتصال بالخادم'
                });
            });
        }
    });
}

// حذف مادة
function deleteMaterial(materialId) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: 'سيتم حذف هذه المادة نهائياً مع جميع ملفاتها المرفقة',
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
            form.action = `{{ route('admin.work-orders.materials.destroy', [$workOrder, ':material_id']) }}`.replace(':material_id', materialId);
            
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
            
            // إضافة الفورم للصفحة وإرساله
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// حذف مادة من مقايسة المواد
function deleteWorkOrderMaterial(workOrderMaterialId) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: 'سيتم حذف هذه المادة من المقايسة نهائياً',
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
            form.action = `/admin/work-orders/materials/work-order-material/${workOrderMaterialId}`;
            
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
            
            // إضافة الفورم للصفحة وإرساله
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// معالجة إضافة المواد من مقايسة المواد
document.addEventListener('DOMContentLoaded', function() {


    // إظهار رسالة خطأ إذا حدث خطأ
    @if(session('error'))
        Swal.fire({
            title: 'خطأ!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'حسناً'
        });
    @endif
    
    // التعامل مع نماذج إضافة المواد من مقايسة المواد
    const createMaterialForms = document.querySelectorAll('.create-material-form');
    
    createMaterialForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            this.submit();
        });
    });
    
    // معالجة زر إضافة جميع المواد المتبقية
    const addAllMaterialsBtn = document.getElementById('addAllMaterialsBtn');
    if (addAllMaterialsBtn) {
        addAllMaterialsBtn.addEventListener('click', function() {
            const notAddedForms = document.querySelectorAll('.create-material-form');
            const notAddedCount = notAddedForms.length;
            
            if (notAddedCount === 0) {
                Swal.fire({
                    title: 'لا توجد مواد',
                    text: 'تم إضافة جميع المواد من المقايسة بالفعل',
                    icon: 'info',
                    confirmButtonText: 'حسناً'
                });
                return;
            }
            
            Swal.fire({
                title: 'تأكيد الإضافة الجماعية',
                html: `
                    <div class="text-center">
                        <i class="fas fa-plus-circle text-success mb-3" style="font-size: 3rem;"></i>
                        <h5 class="mb-3">إضافة جميع المواد المتبقية</h5>
                        <p class="text-muted">سيتم إضافة <strong>${notAddedCount}</strong> مادة من مقايسة المواد إلى قائمة المواد</p>
                        <div class="alert alert-warning mt-3 text-start">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>ملاحظة:</strong> ستتم إضافة جميع المواد بالكميات المخططة من المقايسة، ويمكنك تعديل الكميات المصروفة والمنفذة لاحقاً.
                        </div>
                    </div>
                `,
                icon: null,
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-plus-circle me-1"></i> نعم، أضف جميع المواد',
                cancelButtonText: '<i class="fas fa-times me-1"></i> إلغاء',
                buttonsStyling: true,
                customClass: {
                    popup: 'swal2-large'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // إظهار progress bar
                    Swal.fire({
                        title: 'جاري الإضافة...',
                        html: `
                            <div class="progress mb-3" style="height: 20px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                                     role="progressbar" style="width: 100%">
                                    جاري المعالجة...
                                </div>
                            </div>
                            <p>إضافة جميع المواد من مقايسة المواد...</p>
                        `,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false
                    });
                    
                    // إرسال طلب AJAX لإضافة جميع المواد
                    fetch('{{ route('admin.work-orders.materials.add-all-from-work-order', $workOrder) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        
                                                                        if (data.success) {
                                                    location.reload(); // إعادة تحميل الصفحة لإظهار التحديثات
                        } else {
                            let errorMessage = data.message || 'حدث خطأ أثناء إضافة المواد';
                            
                            // إضافة تفاصيل الأخطاء إذا كانت متوفرة
                            if (data.errors && data.errors.length > 0) {
                                errorMessage += '\n\nتفاصيل الأخطاء:\n' + data.errors.join('\n');
                            }
                            
                            // إضافة إحصائيات العملية
                            if (data.added_count !== undefined && data.skipped_count !== undefined) {
                                errorMessage += `\n\nالإحصائيات:\n`;
                                errorMessage += `• تم إضافة: ${data.added_count} مادة\n`;
                                errorMessage += `• تم تخطي: ${data.skipped_count} مادة`;
                            }
                            
                            Swal.fire({
                                title: 'تنبيه!',
                                text: errorMessage,
                                icon: 'warning',
                                confirmButtonColor: '#ffc107',
                                confirmButtonText: 'حسناً'
                            }).then(() => {
                                // إعادة تحميل الصفحة إذا تم إضافة مواد حتى لو حدثت أخطاء
                                if (data.added_count && data.added_count > 0) {
                                    location.reload();
                                }
                            });
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'خطأ!',
                            html: `
                                <div class="text-center">
                                    <i class="fas fa-exclamation-triangle text-danger mb-3" style="font-size: 3rem;"></i>
                                    <h5 class="text-danger mb-3">حدث خطأ أثناء إضافة المواد</h5>
                                    <p class="text-muted">قد يكون السبب:</p>
                                    <ul class="text-start text-muted">
                                        <li>مشكلة في الاتصال بالخادم</li>
                                        <li>وجود مواد بأكواد مكررة</li>
                                        <li>خطأ في قاعدة البيانات</li>
                                    </ul>
                                    <div class="alert alert-warning mt-3">
                                        <i class="fas fa-lightbulb me-2"></i>
                                        <strong>نصيحة:</strong> جرب إضافة المواد واحدة تلو الأخرى لتحديد المشكلة
                                    </div>
                                </div>
                            `,
                            icon: 'error',
                            confirmButtonColor: '#dc3545',
                            confirmButtonText: 'حسناً',
                            customClass: {
                                popup: 'swal2-large'
                            }
                        });
                    });
                }
            });
        });
    }
});

// وظيفة إخفاء/إظهار جدول مقايسة المواد
function toggleWorkOrderTable() {
    const table = document.getElementById('workOrderMaterialsTable');
    const toggleText = document.getElementById('toggleText');
    const icon = document.querySelector('[onclick="toggleWorkOrderTable()"] i');
    
    if (table.style.display === 'none') {
        table.style.display = 'block';
        toggleText.textContent = 'إخفاء الجدول';
        icon.className = 'fas fa-eye me-1';
        
        // تأثير الظهور
        table.style.opacity = '0';
        table.style.transform = 'translateY(-10px)';
        setTimeout(() => {
            table.style.transition = 'all 0.3s ease';
            table.style.opacity = '1';
            table.style.transform = 'translateY(0)';
        }, 10);
    } else {
        table.style.transition = 'all 0.3s ease';
        table.style.opacity = '0';
        table.style.transform = 'translateY(-10px)';
        
        setTimeout(() => {
            table.style.display = 'none';
            toggleText.textContent = 'إظهار الجدول';
            icon.className = 'fas fa-eye-slash me-1';
        }, 300);
    }
}

// دالة تحديث الفروق
function updateDifferences(materialId, spentValue, executedValue) {
    // تحويل القيم إلى أرقام
    spentValue = parseFloat(spentValue) || 0;
    executedValue = parseFloat(executedValue) || 0;
    
    // الحصول على الكمية المخططة
    const plannedQuantity = parseFloat(document.querySelector(`.spent-quantity[data-material-id="${materialId}"]`).dataset.plannedQuantity) || 0;
    
    // حساب الفروق
    const plannedSpentDiff = spentValue - plannedQuantity;
    const executedSpentDiff = spentValue - executedValue;
    
    // تحديث فرق المخطط والمصروف
    const plannedDiffElement = document.querySelector(`#planned-spent-diff-${materialId} .diff-value`);
    if (plannedDiffElement) {
        plannedDiffElement.innerHTML = getDifferenceDisplay(plannedSpentDiff);
        plannedDiffElement.parentElement.className = `quantity-badge difference ${getDifferenceClass(plannedSpentDiff)}`;
        plannedDiffElement.parentElement.title = getDifferenceTitle(plannedSpentDiff, 'planned');
    }
    
    // تحديث فرق المنفذ والمصروف
    const executedDiffElement = document.querySelector(`#executed-spent-diff-${materialId} .diff-value`);
    if (executedDiffElement) {
        executedDiffElement.innerHTML = getDifferenceDisplay(executedSpentDiff);
        executedDiffElement.parentElement.className = `quantity-badge difference ${getDifferenceClass(executedSpentDiff)}`;
        executedDiffElement.parentElement.title = getDifferenceTitle(executedSpentDiff, 'executed');
    }
    
    // تحديث الرصيد النهائي
    updateFinalBalance(materialId);
}

// دالة إرجاع عرض الفرق
function getDifferenceDisplay(diff) {
    if (diff === 0) return '<i class="fas fa-check"></i>';
    return (diff > 0 ? '+' : '') + diff.toFixed(2);
}

// دالة إرجاع class الفرق
function getDifferenceClass(diff) {
    if (diff === 0) return 'success';
    return diff > 0 ? 'warning' : 'danger';
}

// دالة إرجاع عنوان الفرق
function getDifferenceTitle(diff, type) {
    if (diff === 0) return 'متطابقة';
    if (type === 'planned') {
        return diff > 0 ? 'تم صرف كمية أكثر من المخطط' : 'يوجد كمية مخططة لم يتم صرفها';
    }
    return diff > 0 ? 'تم صرف كمية أكثر من المنفذة' : 'تم تنفيذ كمية أكثر من المصروفة';
}

// دالة تحديث الرصيد النهائي
function updateFinalBalance(materialId) {
    // الحصول على جميع الكميات
    const spentQuantity = parseFloat(document.querySelector(`.spent-quantity[data-material-id="${materialId}"]`).value) || 0;
    const executedQuantity = parseFloat(document.querySelector(`.executed-quantity[data-material-id="${materialId}"]`).value) || 0;
    const recoveryQuantity = parseFloat(document.querySelector(`.recovery-quantity[data-material-id="${materialId}"]`).value) || 0;
    const completionQuantity = parseFloat(document.querySelector(`.completion-quantity[data-material-id="${materialId}"]`).value) || 0;
    
    // حساب الرصيد النهائي: المصروفة + أذن الصرف - المنفذة - أذن الارتجاع
    const finalBalance = spentQuantity + recoveryQuantity - executedQuantity - completionQuantity;
    
    // تحديث عرض الرصيد النهائي
    const balanceElement = document.querySelector(`#final-balance-${materialId} .balance-value`);
    if (balanceElement) {
        if (finalBalance === 0) {
            balanceElement.innerHTML = '<i class="fas fa-balance-scale"></i>';
        } else {
            balanceElement.innerHTML = (finalBalance > 0 ? '+' : '') + finalBalance.toFixed(2);
        }
        
        // تحديث الكلاسات والألوان
        const balanceContainer = balanceElement.parentElement;
        balanceContainer.className = `quantity-badge final-balance ${getFinalBalanceClass(finalBalance)}`;
        balanceContainer.title = getFinalBalanceTitle(finalBalance);
    }
}

// دالة إرجاع كلاس الرصيد النهائي
function getFinalBalanceClass(balance) {
    if (balance === 0) return 'info';
    return balance > 0 ? 'success' : 'danger';
}

// دالة إرجاع عنوان الرصيد النهائي
function getFinalBalanceTitle(balance) {
    if (balance === 0) return 'الرصيد النهائي: رصيد متوازن';
    return balance > 0 ? 'الرصيد النهائي: يوجد رصيد متبقي' : 'الرصيد النهائي: رصيد سالب (نقص)';
}

// معالجة تحديث الكميات
document.querySelectorAll('.quantity-input').forEach(input => {
    let updateTimeout;
    
    input.addEventListener('input', function() {
        const materialId = this.dataset.materialId;
        const newValue = parseFloat(this.value) || 0;
        
        // التحقق من القيمة
        if (newValue < 0) {
            this.value = this.dataset.originalValue;
            toastr.error('لا يمكن إدخال قيمة سالبة');
            return;
        }
        
        // الحصول على القيم الحالية
        const spentInput = document.querySelector(`.spent-quantity[data-material-id="${materialId}"]`);
        const executedInput = document.querySelector(`.executed-quantity[data-material-id="${materialId}"]`);
        const spentValue = this.classList.contains('spent-quantity') ? newValue : parseFloat(spentInput.value) || 0;
        const executedValue = this.classList.contains('executed-quantity') ? newValue : parseFloat(executedInput.value) || 0;
        
        // تحديث الفروق
        updateDifferences(materialId, spentValue, executedValue);
        
        // إلغاء التوقيت السابق
        if (updateTimeout) clearTimeout(updateTimeout);
        
        // إنشاء مؤشر التحميل
        const loadingSpinner = document.createElement('div');
        loadingSpinner.className = 'spinner-border spinner-border-sm text-primary ms-2';
        loadingSpinner.style.width = '1rem';
        loadingSpinner.style.height = '1rem';
        this.parentElement.appendChild(loadingSpinner);
        
        // تحديث القيمة في قاعدة البيانات
        updateTimeout = setTimeout(() => {
            fetch(`{{ route('admin.work-orders.materials.update-quantity', ['workOrder' => $workOrder->id]) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    material_id: materialId,
                    quantity_type: this.classList.contains('spent-quantity') ? 'spent' : 
                                  (this.classList.contains('executed-quantity') ? 'executed' : 
                                  (this.classList.contains('completion-quantity') ? 'completion' : 'recovery')),
                    value: newValue
                })
            })
            .then(response => response.json())
            .then(data => {
                loadingSpinner.remove();
                
                if (data.success) {
                    // تحديث القيم المخزنة
                    this.dataset.originalValue = newValue;
                    
                    // إظهار أيقونة النجاح
                    const successIcon = document.createElement('i');
                    successIcon.className = 'fas fa-check text-success ms-2';
                    this.parentElement.appendChild(successIcon);
                    setTimeout(() => successIcon.remove(), 1000);
                    
                    toastr.success('تم تحديث الكمية بنجاح');
                } else {
                    // إرجاع القيمة السابقة
                    this.value = this.dataset.originalValue;
                    updateDifferences(materialId, spentValue, executedValue);
                    toastr.error(data.message || 'حدث خطأ أثناء تحديث الكمية');
                }
            })
            .catch(error => {
                loadingSpinner.remove();
                console.error('Error:', error);
                this.value = this.dataset.originalValue;
                updateDifferences(materialId, spentValue, executedValue);
                toastr.error('حدث خطأ أثناء تحديث الكمية');
            });
        }, 500);
    });
});

// معالجة تحديث الملاحظات ديناميكياً
document.querySelectorAll('.spent-notes-input, .executed-notes-input').forEach(input => {
    let updateTimeout;
    
    input.addEventListener('input', function() {
        const materialId = this.dataset.materialId;
        const newValue = this.value;
        const notesType = this.classList.contains('spent-notes-input') ? 'spent_notes' : 'executed_notes';
        
        // إلغاء التوقيت السابق
        if (updateTimeout) clearTimeout(updateTimeout);
        
        // إنشاء مؤشر التحميل
        const loadingSpinner = document.createElement('div');
        loadingSpinner.className = 'spinner-border spinner-border-sm text-primary ms-2';
        loadingSpinner.style.width = '0.8rem';
        loadingSpinner.style.height = '0.8rem';
        this.parentElement.appendChild(loadingSpinner);
        
        // تحديث الملاحظات في قاعدة البيانات
        updateTimeout = setTimeout(() => {
            fetch(`{{ route('admin.work-orders.materials.update-notes', ['workOrder' => $workOrder->id]) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    material_id: materialId,
                    notes_type: notesType,
                    value: newValue
                })
            })
            .then(response => response.json())
            .then(data => {
                loadingSpinner.remove();
                
                if (data.success) {
                    // إظهار أيقونة النجاح
                    const successIcon = document.createElement('i');
                    successIcon.className = 'fas fa-check text-success ms-2';
                    successIcon.style.fontSize = '0.8rem';
                    this.parentElement.appendChild(successIcon);
                    setTimeout(() => successIcon.remove(), 1000);
                    
                    // عدم إظهار toastr للملاحظات لتجنب الإزعاج
                } else {
                    toastr.error(data.message || 'حدث خطأ أثناء حفظ الملاحظات');
                }
            })
            .catch(error => {
                loadingSpinner.remove();
                console.error('Error:', error);
                toastr.error('حدث خطأ أثناء حفظ الملاحظات');
            });
        }, 800); // مدة أطول للملاحظات عشان المستخدم يكمل كتابة
    });
});
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

/* تأثيرات حذف الملفات المستقلة */
#independentFilesTable tr {
    transition: all 0.3s ease;
}

#independentFilesTable tr.deleting {
    opacity: 0;
    transform: translateX(-20px);
}

/* تحسين أزرار الحذف */
.btn-outline-danger {
    transition: all 0.2s ease;
}

.btn-outline-danger:hover {
    transform: scale(1.05);
}

.btn-outline-danger:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* تحسين مظهر الجدول */
#independentFilesTable {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

#independentFilesTable th {
    background: linear-gradient(135deg, #343a40 0%, #495057 100%);
    border: none;
    font-weight: 600;
}

#independentFilesTable tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* تحسين حالة الفراغ */
.empty-state {
    padding: 2rem;
    text-align: center;
}

.empty-state i {
    opacity: 0.5;
}
</style>
@endpush



@push('scripts')
<script>
// إضافة تنسيق للحقول التي بها أخطاء
const validationStyle = document.createElement('style');
validationStyle.textContent = `
    .has-error .form-control {
        border-color: #e74a3b;
    }
    .has-error .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(231, 74, 59, 0.25);
    }
    .invalid-feedback {
        color: #e74a3b;
        font-size: 80%;
        margin-top: 0.25rem;
    }
`;
document.head.appendChild(validationStyle);

// دالة إضافة جميع المواد تلقائياً
function addAllMaterialsAutomatically() {
    // إظهار مؤشر التحميل
    Swal.fire({
        title: 'جاري إضافة المواد...',
        html: `
            <div class="progress mb-3" style="height: 20px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                     role="progressbar" style="width: 100%">
                    جاري المعالجة...
                </div>
            </div>
            <p>جاري إضافة جميع المواد من مقايسة المواد إلى القائمة...</p>
        `,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false
    });
    
    // إرسال طلب إضافة جميع المواد
    fetch('{{ route('admin.work-orders.materials.add-all-from-work-order', $workOrder) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        
        if (data.success) {
            Swal.fire({
                title: 'تم بنجاح!',
                text: data.message || 'تم إضافة جميع المواد بنجاح',
                icon: 'success',
                confirmButtonColor: '#28a745',
                confirmButtonText: 'تم'
            }).then(() => {
                location.reload(); // إعادة تحميل الصفحة لإظهار التحديثات
            });
        } else {
            let errorMessage = data.message || 'حدث خطأ أثناء إضافة المواد';
            
            if (data.notes && data.notes.length > 0) {
                errorMessage += '\n\nملاحظات:\n' + data.notes.join('\n');
            }
            
            Swal.fire({
                title: 'تنبيه!',
                text: errorMessage,
                icon: 'warning',
                confirmButtonColor: '#ffc107',
                confirmButtonText: 'حسناً'
            }).then(() => {
                if (data.added_count && data.added_count > 0) {
                    location.reload();
                }
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'خطأ!',
            text: 'حدث خطأ أثناء إضافة المواد',
            icon: 'error',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'حسناً'
        });
    });
}

// تم إلغاء الإضافة التلقائية للمواد - المواد ستتم إضافتها يدوياً فقط

// تحديث زر إضافة الجميع ليعمل يدوياً أيضاً
const addAllBtn = document.getElementById('addAllMaterialsBtn');
if (addAllBtn) {
    addAllBtn.addEventListener('click', addAllMaterialsAutomatically);
}

// معالجة أزرار إضافة المواد الفردية بـ AJAX
document.addEventListener('DOMContentLoaded', function() {
    // إضافة event listener لجميع أزرار الإضافة
    document.querySelectorAll('.add-material-btn').forEach(button => {
        button.addEventListener('click', function() {
            const btn = this;
            const materialCode = btn.dataset.materialCode;
            const materialDescription = btn.dataset.materialDescription;
            const materialName = btn.dataset.materialName;
            const materialUnit = btn.dataset.materialUnit;
            const plannedQuantity = btn.dataset.plannedQuantity;
            const workOrderId = btn.dataset.workOrderId;
            const rowIndex = btn.dataset.rowIndex;
            
            // تعطيل الزر وإظهار loading
            btn.disabled = true;
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            // إرسال AJAX request
            fetch(`{{ route('admin.work-orders.materials.store', $workOrder) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    code: materialCode,
                    description: materialDescription,
                    name: materialName,
                    unit: materialUnit,
                    planned_quantity: plannedQuantity,
                    spent_quantity: 0,
                    executed_quantity: 0
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // تغيير الزر إلى أيقونة النجاح
                    btn.outerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i></span>';
                    
                    // تحديث لون الصف إلى الأخضر
                    const row = btn.closest('tr');
                    row.className = 'table-success';
                    
                    // إظهار toast بسيط
                    toastr.success(`تم إضافة ${materialName} بنجاح`, '', {
                        timeOut: 2000,
                        positionClass: 'toast-top-left',
                        progressBar: true
                    });
                    
                    // تحديث عداد المواد في الصفحة
                    updateMaterialsCount();
                    
                } else {
                    // إرجاع الزر لحالته الأصلية
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                    
                    toastr.error(data.message || 'حدث خطأ أثناء إضافة المادة', '', {
                        timeOut: 3000,
                        positionClass: 'toast-top-left'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // إرجاع الزر لحالته الأصلية
                btn.disabled = false;
                btn.innerHTML = originalContent;
                
                toastr.error('حدث خطأ في الاتصال', '', {
                    timeOut: 3000,
                    positionClass: 'toast-top-left'
                });
            });
        });
    });
});

// دالة تحديث عداد المواد
function updateMaterialsCount() {
    // تحديث عداد المواد في header الصفحة
    const materialsCountBadges = document.querySelectorAll('.badge');
    materialsCountBadges.forEach(badge => {
        if (badge.textContent.includes('مادة')) {
            const currentCount = parseInt(badge.textContent.match(/\d+/)?.[0] || 0);
            badge.textContent = badge.textContent.replace(/\d+/, currentCount + 1);
        }
    });
}

// دالة معاينة الملفات المختارة
function previewSelectedFiles(input, previewId) {
    const previewContainer = document.getElementById(previewId);
    previewContainer.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        const fileList = document.createElement('div');
        fileList.className = 'selected-files-list mt-2';
        
        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            const fileItem = document.createElement('div');
            fileItem.className = 'selected-file-item d-flex align-items-center justify-content-between bg-light p-2 mb-1 rounded';
            
            // أيقونة حسب نوع الملف
            let fileIcon = 'fas fa-file';
            if (file.type.startsWith('image/')) {
                fileIcon = 'fas fa-image text-success';
            } else if (file.type === 'application/pdf') {
                fileIcon = 'fas fa-file-pdf text-danger';
            } else if (file.type.includes('word')) {
                fileIcon = 'fas fa-file-word text-primary';
            }
            
            fileItem.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="${fileIcon} me-2"></i>
                    <span class="file-name text-truncate" style="max-width: 200px;">${file.name}</span>
                    <small class="text-muted ms-2">(${(file.size / 1024 / 1024).toFixed(2)} MB)</small>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFileFromInput('${input.id}', ${i}, '${previewId}')">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            fileList.appendChild(fileItem);
        }
        
        // إضافة عداد الملفات
        const fileCounter = document.createElement('div');
        fileCounter.className = 'file-counter text-info mt-1';
        fileCounter.innerHTML = `<small><i class="fas fa-info-circle me-1"></i>تم اختيار ${input.files.length} ملف</small>`;
        
        previewContainer.appendChild(fileList);
        previewContainer.appendChild(fileCounter);
    }
}

// دالة إزالة ملف من القائمة
function removeFileFromInput(inputId, fileIndex, previewId) {
    const input = document.getElementById(inputId);
    const dt = new DataTransfer();
    
    for (let i = 0; i < input.files.length; i++) {
        if (i !== fileIndex) {
            dt.items.add(input.files[i]);
        }
    }
    
    input.files = dt.files;
    previewSelectedFiles(input, previewId);
}

// دالة توسيع حقول الملاحظات حسب المحتوى
function adjustNotesFieldWidth() {
    const notesInputs = document.querySelectorAll('.spent-notes-input, .executed-notes-input');
    
    notesInputs.forEach(input => {
        // إنشاء عنصر مؤقت لحساب العرض المطلوب
        const tempSpan = document.createElement('span');
        tempSpan.style.visibility = 'hidden';
        tempSpan.style.position = 'absolute';
        tempSpan.style.fontSize = window.getComputedStyle(input).fontSize;
        tempSpan.style.fontFamily = window.getComputedStyle(input).fontFamily;
        tempSpan.style.padding = '4px 8px';
        tempSpan.textContent = input.value || input.placeholder;
        
        document.body.appendChild(tempSpan);
        
        // حساب العرض المطلوب
        const requiredWidth = Math.max(200, Math.min(400, tempSpan.offsetWidth + 20));
        input.style.width = requiredWidth + 'px';
        
        document.body.removeChild(tempSpan);
    });
}

// إضافة معالجة لـ form submission
document.addEventListener('DOMContentLoaded', function() {
    // تطبيق التوسيع التلقائي عند تحميل الصفحة
    adjustNotesFieldWidth();
    
    // تطبيق التوسيع عند تغيير المحتوى
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('spent-notes-input') || e.target.classList.contains('executed-notes-input')) {
            // توسيع الحقل حسب المحتوى
            const tempSpan = document.createElement('span');
            tempSpan.style.visibility = 'hidden';
            tempSpan.style.position = 'absolute';
            tempSpan.style.fontSize = window.getComputedStyle(e.target).fontSize;
            tempSpan.style.fontFamily = window.getComputedStyle(e.target).fontFamily;
            tempSpan.style.padding = '4px 8px';
            tempSpan.textContent = e.target.value || e.target.placeholder;
            
            document.body.appendChild(tempSpan);
            
            const requiredWidth = Math.max(200, Math.min(400, tempSpan.offsetWidth + 20));
            e.target.style.width = requiredWidth + 'px';
            
            // إضافة tooltip إذا كان النص طويل
            if (e.target.value && e.target.value.length > 30) {
                e.target.setAttribute('title', e.target.value);
                e.target.setAttribute('data-bs-toggle', 'tooltip');
            } else {
                e.target.removeAttribute('title');
                e.target.removeAttribute('data-bs-toggle');
            }
            
            document.body.removeChild(tempSpan);
        }
    });
    const uploadForm = document.getElementById('uploadMaterialFilesForm');
    const uploadBtn = document.getElementById('uploadFilesBtn');
    
    if (uploadForm && uploadBtn) {
        uploadForm.addEventListener('submit', function(e) {
            // التحقق من وجود ملفات مختارة
            const fileInputs = uploadForm.querySelectorAll('input[type="file"]');
            let hasFiles = false;
            
            fileInputs.forEach(input => {
                if (input.files && input.files.length > 0) {
                    hasFiles = true;
                }
            });
            
            if (!hasFiles) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'تنبيه',
                    text: 'يرجى اختيار ملف واحد على الأقل للرفع',
                    confirmButtonText: 'موافق'
                });
                return false;
            }
            
            // إظهار مؤشر التحميل
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الرفع...';
            
            // إظهار رسالة تحميل
            Swal.fire({
                title: 'جاري رفع الملفات...',
                html: 'يرجى الانتظار حتى اكتمال رفع الملفات',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
    }

    // وظيفة الحفظ التلقائي للملاحظات
    const materialsNotes = document.getElementById('materials_notes');
    const saveStatus = document.getElementById('save-status');
    let saveTimeout;
    
    if (materialsNotes) {
        materialsNotes.addEventListener('input', function() {
            // إلغاء المهلة الزمنية السابقة
            clearTimeout(saveTimeout);
            
            // عرض حالة الحفظ
            saveStatus.innerHTML = '<i class="fas fa-clock text-warning"></i> جاري الحفظ...';
            
            // تعيين مهلة زمنية جديدة للحفظ
            saveTimeout = setTimeout(function() {
                saveMaterialsNotes();
            }, 1000); // حفظ بعد ثانية واحدة من التوقف عن الكتابة
        });
    }
    
    function saveMaterialsNotes() {
        const workOrderId = materialsNotes.dataset.workOrderId;
        const notes = materialsNotes.value;
        
        fetch(`/admin/work-orders/${workOrderId}/save-materials-notes`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                materials_notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                saveStatus.innerHTML = '<i class="fas fa-check text-success"></i> تم الحفظ';
                setTimeout(() => {
                    saveStatus.innerHTML = '';
                }, 3000);
            } else {
                saveStatus.innerHTML = '<i class="fas fa-exclamation-triangle text-danger"></i> خطأ في الحفظ';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            saveStatus.innerHTML = '<i class="fas fa-exclamation-triangle text-danger"></i> خطأ في الحفظ';
        });
    }

    // وظائف جدول الإزالة والسكراب
    let removalScrapData = @json($workOrder->removal_scrap_materials ?? []);
    let removalScrapCounter = 0;
    
    // تحميل البيانات الموجودة عند فتح الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Loaded removal scrap data:', removalScrapData);
        console.log('Data length:', removalScrapData.length);
        
        // تأخير صغير للتأكد من تحميل DOM كامل
        setTimeout(function() {
            console.log('Delayed update call');
            updateRemovalScrapTable();
        }, 100);
    });

    // إضافة مادة للإزالة
    document.getElementById('saveRemovalScrapBtn').addEventListener('click', function() {
        const form = document.getElementById('addRemovalScrapForm');
        const formData = new FormData(form);
        const submitBtn = this;
        const originalText = submitBtn.innerHTML;
        
        // التحقق من صحة البيانات
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // تعطيل الزر أثناء الإرسال
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>جاري الإضافة...';
        
        // إرسال البيانات للسيرفر
        fetch(`/admin/work-orders/{{ $workOrder->id }}/removal-scrap-materials`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // إظهار رسالة نجاح
                toastr.success(data.message, '', {
                    timeOut: 1500,
                    positionClass: 'toast-top-left',
                    progressBar: true
                });
                
                // إغلاق الـ modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addRemovalScrapModal'));
                modal.hide();
                
                // إعادة تحميل الصفحة بعد تأخير قصير
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                toastr.error(data.message || 'حدث خطأ أثناء إضافة المادة', '', {
                    timeOut: 3000,
                    positionClass: 'toast-top-left',
                    progressBar: true
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('حدث خطأ أثناء إضافة المادة', '', {
                timeOut: 3000,
                positionClass: 'toast-top-left',
                progressBar: true
            });
        })
        .finally(() => {
            // إعادة تفعيل الزر
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    // تحديث جدول الإزالة والسكراب
    function updateRemovalScrapTable() {
        console.log('updateRemovalScrapTable called');
        const tableBody = document.getElementById('removalScrapTableBody');
        const noDataRow = document.getElementById('noRemovalScrapRow');
        
        console.log('tableBody:', tableBody);
        console.log('noDataRow:', noDataRow);
        console.log('removalScrapData:', removalScrapData);
        
        if (removalScrapData.length === 0) {
            console.log('No data, showing no data row');
            if (noDataRow) noDataRow.style.display = '';
            return;
        }
        
        console.log('Has data, hiding no data row');
        if (noDataRow) noDataRow.style.display = 'none';
        
        if (!tableBody) {
            console.error('Table body not found!');
            return;
        }
        
        // إزالة الصفوف الموجودة (عدا صف "لا توجد بيانات")
        const existingRows = tableBody.querySelectorAll('tr:not(#noRemovalScrapRow)');
        console.log('Existing rows to remove:', existingRows.length);
        existingRows.forEach(row => row.remove());
        
        // إضافة الصفوف الجديدة
        console.log('Adding rows for data:', removalScrapData.length);
        removalScrapData.forEach((item, index) => {
            const row = document.createElement('tr');
            row.className = 'align-middle';
            row.innerHTML = `
                <td class="text-center">
                    <span class="badge bg-warning text-dark">${index + 1}</span>
                </td>
                <td>
                    <span class="badge bg-light text-dark border">${item.material_code}</span>
                </td>
                <td>
                    <div class="text-wrap" style="max-width: 300px;">
                        ${item.material_description}
                    </div>
                </td>
                <td class="text-center">
                    <span class="badge bg-light text-dark border">${item.material_unit}</span>
                </td>
                <td class="text-center">
                    <span class="badge bg-danger text-white">
                        ${parseFloat(item.quantity).toFixed(2)}
                    </span>
                </td>
                <td>
                    <div class="text-wrap" style="max-width: 200px;">
                        ${item.notes || '-'}
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger delete-removal-item" 
                            data-item-id="${item.id}"
                            title="حذف المادة">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            
            console.log('Adding row for item:', item);
            tableBody.appendChild(row);
        });
        
        console.log('Finished updating table. Total rows added:', removalScrapData.length);
        
        // إضافة event listeners لأزرار الحذف
        document.querySelectorAll('.delete-removal-item').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.dataset.itemId;
                console.log('Delete button clicked for item:', itemId);
                removeRemovalScrapItem(itemId);
            });
        });
    }

    // حذف مادة من جدول الإزالة
    function removeRemovalScrapItem(itemId) {
        console.log('removeRemovalScrapItem called with:', itemId, 'type:', typeof itemId);
        console.log('Current data:', removalScrapData);
        
        Swal.fire({
            title: 'تأكيد الحذف',
            text: 'هل أنت متأكد من حذف هذه المادة؟',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                // العثور على فهرس العنصر
                console.log('Looking for item with ID:', itemId);
                const itemIndex = removalScrapData.findIndex(item => {
                    console.log('Comparing:', item.id, '(type:', typeof item.id, ') with', itemId, '(type:', typeof itemId, ')');
                    // استخدام == للتعامل مع type differences
                    const match = item.id == itemId;
                    console.log('Match result:', match);
                    return match;
                });
                console.log('Found index:', itemIndex);
                
                if (itemIndex === -1) {
                    toastr.error('العنصر غير موجود', '', {
                        timeOut: 2000,
                        positionClass: 'toast-top-left',
                        progressBar: true
                    });
                    return;
                }
                
                // إرسال طلب الحذف للسيرفر
                fetch(`/admin/work-orders/{{ $workOrder->id }}/removal-scrap-materials/${itemIndex}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // إزالة العنصر من المصفوفة المحلية
                        console.log('Removing item with ID:', itemId, 'from local array');
                        const beforeLength = removalScrapData.length;
                        removalScrapData = removalScrapData.filter(item => item.id != itemId);
                        console.log('Array length before:', beforeLength, 'after:', removalScrapData.length);
                        
                        // تحديث الجدول
                        updateRemovalScrapTable();
                        
                        // إظهار رسالة نجاح
                        Swal.fire({
                            title: 'تم الحذف!',
                            text: data.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        toastr.error(data.message || 'حدث خطأ أثناء حذف المادة', '', {
                            timeOut: 3000,
                            positionClass: 'toast-top-left',
                            progressBar: true
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('حدث خطأ أثناء حذف المادة', '', {
                        timeOut: 3000,
                        positionClass: 'toast-top-left',
                        progressBar: true
                    });
                });
            }
        });
    }

    // جعل الدوال متاحة عالمياً
    window.removeRemovalScrapItem = removeRemovalScrapItem;
    window.updateRemovalScrapTable = updateRemovalScrapTable;
});

// حساب الرصيد النهائي لجميع المواد عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // تأخير صغير للتأكد من تحميل جميع العناصر
    setTimeout(function() {
        const processedMaterials = new Set();
        document.querySelectorAll('.quantity-input').forEach(input => {
            const materialId = input.dataset.materialId;
            if (materialId && !processedMaterials.has(materialId)) {
                updateFinalBalance(materialId);
                processedMaterials.add(materialId);
            }
        });
    }, 500);
});

// دالة معاينة الفروق في نافذة إضافة مادة جديدة
function updateModalPreview() {
    const plannedQuantity = parseFloat(document.getElementById('planned_quantity').value) || 0;
    const spentQuantity = parseFloat(document.getElementById('spent_quantity').value) || 0;
    const executedQuantity = parseFloat(document.getElementById('executed_quantity').value) || 0;
    
    // حساب الفروق
    const plannedDiff = spentQuantity - plannedQuantity;
    const executedDiff = spentQuantity - executedQuantity;
    
    // تحديث العرض
    const plannedDiffElement = document.getElementById('plannedDiffPreview');
    const executedDiffElement = document.getElementById('executedDiffPreview');
    const previewElement = document.getElementById('modalPreview');
    
    if (plannedQuantity > 0 || spentQuantity > 0 || executedQuantity > 0) {
        previewElement.style.display = 'block';
        
        // تحديث الفرق المخطط
        plannedDiffElement.textContent = (plannedDiff > 0 ? '+' : '') + plannedDiff.toFixed(2);
        plannedDiffElement.className = `badge ${plannedDiff > 0 ? 'bg-warning' : (plannedDiff < 0 ? 'bg-danger' : 'bg-success')}`;
        
        // تحديث الفرق المنفذ
        executedDiffElement.textContent = (executedDiff > 0 ? '+' : '') + executedDiff.toFixed(2);
        executedDiffElement.className = `badge ${executedDiff > 0 ? 'bg-warning' : (executedDiff < 0 ? 'bg-danger' : 'bg-success')}`;
    } else {
        previewElement.style.display = 'none';
    }
}

// إعادة تعيين النافذة عند إغلاقها
document.getElementById('addMaterialModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('modalPreview').style.display = 'none';
    document.getElementById('addMaterialModal').querySelector('form').reset();
});

</script>
@endpush
@endsection 