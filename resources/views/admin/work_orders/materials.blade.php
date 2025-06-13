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

    <!-- Materials Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">قائمة المواد</h6>
                </div>
                <div class="card-body">
                    @if($materials->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="materialsTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="10%">الكود</th>
                                        <th width="25%">الوصف</th>
                                        <th width="10%">الكمية المخططة</th>
                                        <th width="10%">الكمية المستهلكة</th>
                                        <th width="8%">الوحدة</th>
                                        <th width="10%">الخط</th>
                                        <th width="12%">تاريخ تصريح المرور</th>
                                        <th width="15%">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materials as $index => $material)
                                        <tr>
                                            <td>{{ $materials->firstItem() + $index }}</td>
                                            <td>{{ $material->code }}</td>
                                            <td>{{ $material->description }}</td>
                                            <td>{{ number_format($material->planned_quantity, 2) }}</td>
                                            <td>{{ number_format($material->spent_quantity, 2) }}</td>
                                            <td>{{ $material->unit }}</td>
                                            <td>{{ $material->line }}</td>
                                            <td>{{ $material->date_gatepass ? $material->date_gatepass->format('Y-m-d') : '-' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <!-- View Button -->
                                                    <a href="{{ route('admin.work-orders.materials.show', [$workOrder, $material]) }}" 
                                                       class="btn btn-sm btn-info" title="عرض">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <!-- Edit Button -->
                                                    <a href="{{ route('admin.work-orders.materials.edit', [$workOrder, $material]) }}" 
                                                       class="btn btn-sm btn-warning" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <!-- Attachments Dropdown -->
                                                    @if($material->hasAttachments())
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" 
                                                                    data-toggle="dropdown" title="المرفقات">
                                                                <i class="fas fa-paperclip"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                @foreach($material->getAttachments() as $key => $attachment)
                                                                    <a class="dropdown-item" href="{{ $attachment['url'] }}" target="_blank">
                                                                        <i class="fas fa-file"></i> {{ $attachment['name'] }}
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-light" disabled title="لا توجد مرفقات">
                                                            <i class="fas fa-paperclip"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    <!-- Delete Button -->
                                                    <form method="POST" action="{{ route('admin.work-orders.materials.destroy', [$workOrder, $material]) }}" 
                                                          style="display: inline;" 
                                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه المادة؟')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
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
                            <p class="text-muted">يمكنك إضافة مواد جديدة باستخدام الزر أعلاه</p>
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
    // Initialize DataTable if needed
    if ($('#materialsTable tbody tr').length > 0) {
        $('#materialsTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json"
            },
            "pageLength": 15,
            "ordering": true,
            "searching": true,
            "paging": false, // We use Laravel pagination
            "info": false
        });
    }
});
</script>
@endpush
@endsection 