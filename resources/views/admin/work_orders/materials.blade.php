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
                        <a href="{{ route('admin.work-orders.materials.export.excel', $workOrder) }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> تصدير Excel
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
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="materialsTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="4%">#</th>
                                        <th width="8%">الكود</th>
                                        <th width="20%">الوصف</th>
                                        <th width="8%">الكمية المخططة</th>
                                        <th width="8%">الكمية المنفذة</th>
                                        <th width="6%">الفرق</th>
                                        <th width="8%">الكمية المصروفة</th>
                                        <th width="6%">الوحدة</th>
                                        <th width="8%">السطر</th>
                                        <th width="10%">DATE GATEPASS</th>
                                        <th width="8%">GATEPASS</th>
                                        <th width="18%">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materials as $index => $material)
                                        <tr>
                                            <td>{{ $materials->firstItem() + $index }}</td>
                                            <td><span class="badge bg-secondary">{{ $material->code }}</span></td>
                                            <td class="text-start">{{ $material->description }}</td>
                                            <td><span class="badge bg-primary">{{ number_format($material->planned_quantity, 2) }}</span></td>
                                            <td><span class="badge bg-success">{{ number_format($material->executed_quantity ?? 0, 2) }}</span></td>
                                            <td>
                                                @php
                                                    $difference = ($material->planned_quantity ?? 0) - ($material->executed_quantity ?? 0);
                                                @endphp
                                                @if($difference > 0)
                                                    <span class="badge bg-warning text-dark" title="نقص في التنفيذ">
                                                        +{{ number_format($difference, 2) }}
                                                    </span>
                                                @elseif($difference < 0)
                                                    <span class="badge bg-danger" title="تنفيذ زائد">
                                                        {{ number_format($difference, 2) }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-success" title="متطابقة">
                                                        <i class="fas fa-check"></i>
                                                    </span>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-info">{{ number_format($material->spent_quantity, 2) }}</span></td>
                                            <td><span class="badge bg-light text-dark">{{ $material->unit }}</span></td>
                                            <td>{{ $material->line ?: '-' }}</td>
                                            <td>
                                                @if($material->date_gatepass)
                                                    <small class="text-muted">{{ $material->date_gatepass->format('Y-m-d') }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($material->gate_pass_file)
                                                    <a href="{{ asset('storage/' . $material->gate_pass_file) }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-outline-success" 
                                                       title="عرض ملف GATEPASS">
                                                        <i class="fas fa-file-alt"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted" title="لا يوجد ملف GATEPASS">
                                                        <i class="fas fa-file-slash"></i>
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-toolbar justify-content-center" role="toolbar">
                                                    <!-- Actions Group -->
                                                    <div class="btn-group me-2" role="group">
                                                        <!-- View Button -->
                                                        <a href="{{ route('admin.work-orders.materials.show', [$workOrder, $material]) }}" 
                                                           class="btn btn-sm btn-info" title="عرض التفاصيل">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        
                                                        <!-- Edit Button -->
                                                        <a href="{{ route('admin.work-orders.materials.edit', [$workOrder, $material]) }}" 
                                                           class="btn btn-sm btn-warning" title="تعديل المادة">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                    
                                                    <!-- Attachments Group -->
                                                    <div class="btn-group me-2" role="group">
                                                        @if($material->hasAttachments())
                                                            <button type="button" class="btn btn-sm btn-success dropdown-toggle" 
                                                                    data-bs-toggle="dropdown" aria-expanded="false" title="عرض المرفقات ({{ count($material->getAttachments()) }})">
                                                                <i class="fas fa-paperclip"></i>
                                                                <span class="badge bg-light text-success ms-1">{{ count($material->getAttachments()) }}</span>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><h6 class="dropdown-header">المرفقات المتاحة</h6></li>
                                                                @foreach($material->getAttachments() as $key => $attachment)
                                                                    <li>
                                                                        <a class="dropdown-item d-flex align-items-center" href="{{ $attachment['url'] }}" target="_blank">
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
                                                                            <i class="{{ $icon }} {{ $color }} me-2"></i>
                                                                            <div>
                                                                                <div class="fw-bold">{{ $attachment['name'] }}</div>
                                                                                <small class="text-muted">انقر للعرض</small>
                                                                            </div>
                                                                        </a>
                                                                    </li>
                                                                    @if(!$loop->last)
                                                                        <li><hr class="dropdown-divider"></li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="لا توجد مرفقات">
                                                                <i class="fas fa-paperclip"></i>
                                                                <span class="badge bg-secondary ms-1">0</span>
                                                            </button>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Delete Group -->
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                onclick="deleteMaterial({{ $material->id }}, '{{ $material->code }}')" 
                                                                title="حذف المادة">
                                                            <i class="fas fa-trash"></i>
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
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // تحسين تجربة البحث
    $('#search_code, #search_description').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            $(this).closest('form').submit();
        }
    });

    // البحث السريع بالكود
    $('#search_code').on('input', function() {
        const value = $(this).val();
        if (value.length >= 3) {
            // يمكن إضافة AJAX search هنا للبحث السريع
            highlightTableRows(value, 'code');
        } else {
            clearHighlights();
        }
    });

    // تحسين عرض الجدول
    if ($('#materialsTable tbody tr').length > 0) {
        // إضافة hover effects
        $('#materialsTable tbody tr').hover(
            function() {
                $(this).addClass('table-active');
            },
            function() {
                $(this).removeClass('table-active');
            }
        );
    }

    // تحسين dropdown المرفقات
    $('.dropdown-toggle').on('click', function(e) {
        e.stopPropagation();
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

// دالة تمييز الصفوف في البحث
function highlightTableRows(searchTerm, column) {
    clearHighlights();
    
    $('#materialsTable tbody tr').each(function() {
        const row = $(this);
        let cellIndex;
        
        switch(column) {
            case 'code':
                cellIndex = 1; // عمود الكود
                break;
            case 'description':
                cellIndex = 2; // عمود الوصف
                break;
            default:
                return;
        }
        
        const cellText = row.find('td').eq(cellIndex).text().toLowerCase();
        if (cellText.includes(searchTerm.toLowerCase())) {
            row.addClass('table-warning');
        }
    });
}

// دالة مسح التمييز
function clearHighlights() {
    $('#materialsTable tbody tr').removeClass('table-warning');
}

// تحسين عرض الرسائل
@if(session('success'))
    toastr.success('{{ session('success') }}');
@endif

@if(session('error'))
    toastr.error('{{ session('error') }}');
@endif

// إضافة tooltips للأزرار
$(function () {
    $('[title]').tooltip();
});
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