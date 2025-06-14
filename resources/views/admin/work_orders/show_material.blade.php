@extends('layouts.admin')

@section('title', 'تفاصيل المادة - ' . $material->code)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">تفاصيل المادة: {{ $material->code }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.work-orders.index') }}">أوامر العمل</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.work-orders.show', $material->workOrder) }}">أمر العمل {{ $material->work_order_number }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.work-orders.materials.index', $material->workOrder) }}">المواد</a></li>
                            <li class="breadcrumb-item active" aria-current="page">تفاصيل المادة</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.work-orders.materials.edit', [$material->workOrder, $material]) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> تعديل المادة
                    </a>
                    <a href="{{ route('admin.work-orders.materials.index', $material->workOrder) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> العودة للقائمة
                    </a>
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
                            <strong>رقم أمر العمل:</strong> {{ $material->work_order_number }}
                        </div>
                        <div class="col-md-3">
                            <strong>اسم المشترك:</strong> {{ $material->subscriber_name }}
                        </div>
                        <div class="col-md-3">
                            <strong>نوع العمل:</strong> {{ $material->workOrder->work_type ?? '-' }}
                        </div>
                        <div class="col-md-3">
                            <strong>حالة أمر العمل:</strong> 
                            <span class="badge badge-primary">{{ $material->workOrder->status ?? 'نشط' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- معلومات المادة الأساسية -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-box me-2"></i>
                        معلومات المادة الأساسية
                    </h6>
                    <span class="badge badge-secondary">{{ $material->code }}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">كود المادة</label>
                            <div class="form-control-plaintext fw-bold">{{ $material->code }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">السطر</label>
                            <div class="form-control-plaintext">{{ $material->line ?: '-' }}</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted small">وصف المادة</label>
                            <div class="form-control-plaintext">{{ $material->description }}</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">الوحدة</label>
                            <div class="form-control-plaintext">
                                <span class="badge badge-light">{{ $material->unit ?: '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">DATE GATEPASS</label>
                            <div class="form-control-plaintext">
                                @if($material->date_gatepass)
                                    <i class="fas fa-calendar me-2 text-info"></i>
                                    {{ $material->date_gatepass->format('Y-m-d') }}
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- بيانات الكميات -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-calculator me-2"></i>
                        بيانات الكميات
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted small">الكمية المخططة</label>
                            <div class="text-center">
                                <div class="h4 mb-0">
                                    <span class="badge badge-primary badge-lg">{{ number_format($material->planned_quantity, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted small">الكمية المنفذة</label>
                            <div class="text-center">
                                <div class="h4 mb-0">
                                    <span class="badge badge-success badge-lg">{{ number_format($material->executed_quantity ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted small">الفرق</label>
                            <div class="text-center">
                                @php
                                    $difference = ($material->planned_quantity ?? 0) - ($material->executed_quantity ?? 0);
                                @endphp
                                <div class="h4 mb-0">
                                    @if($difference > 0)
                                        <span class="badge badge-warning badge-lg" title="نقص في التنفيذ">
                                            +{{ number_format($difference, 2) }}
                                        </span>
                                        <small class="d-block text-warning">نقص في التنفيذ</small>
                                    @elseif($difference < 0)
                                        <span class="badge badge-danger badge-lg" title="تنفيذ زائد">
                                            {{ number_format($difference, 2) }}
                                        </span>
                                        <small class="d-block text-danger">تنفيذ زائد</small>
                                    @else
                                        <span class="badge badge-success badge-lg" title="متطابقة">
                                            <i class="fas fa-check"></i> متطابقة
                                        </span>
                                        <small class="d-block text-success">مطابقة للمخطط</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted small">الكمية المصروفة</label>
                            <div class="text-center">
                                <div class="h4 mb-0">
                                    <span class="badge badge-info badge-lg">{{ number_format($material->spent_quantity, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- معلومات إضافية -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-secondary">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات إضافية
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">تاريخ الإنشاء</label>
                            <div class="form-control-plaintext">
                                <i class="fas fa-plus-circle me-2 text-success"></i>
                                {{ $material->created_at->format('Y-m-d H:i:s') }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">آخر تحديث</label>
                            <div class="form-control-plaintext">
                                <i class="fas fa-edit me-2 text-warning"></i>
                                {{ $material->updated_at->format('Y-m-d H:i:s') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- المرفقات -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-paperclip me-2"></i>
                        الملفات المرفقة
                    </h6>
                    @if($material->hasAttachments())
                        <span class="badge badge-info">{{ count($material->getAttachments()) }} ملف</span>
                    @else
                        <span class="badge badge-secondary">لا توجد ملفات</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($material->hasAttachments())
                        <div class="list-group list-group-flush">
                            @if($material->check_in_file)
                                <a href="{{ asset('storage/' . $material->check_in_file) }}" target="_blank" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-list-check me-2 text-primary"></i>
                                        <strong>CHECK LIST</strong>
                                        <small class="d-block text-muted">{{ basename($material->check_in_file) }}</small>
                                    </div>
                                    <i class="fas fa-external-link-alt text-muted"></i>
                                </a>
                            @endif
                            
                            @if($material->check_out_file)
                                <a href="{{ asset('storage/' . $material->check_out_file) }}" target="_blank" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-list-ul me-2 text-secondary"></i>
                                        <strong>CHECK OUT</strong>
                                        <small class="d-block text-muted">{{ basename($material->check_out_file) }}</small>
                                    </div>
                                    <i class="fas fa-external-link-alt text-muted"></i>
                                </a>
                            @endif
                            
                            @if($material->gate_pass_file)
                                <a href="{{ asset('storage/' . $material->gate_pass_file) }}" target="_blank" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-id-card me-2 text-success"></i>
                                        <strong>GATE PASS</strong>
                                        <small class="d-block text-muted">{{ basename($material->gate_pass_file) }}</small>
                                    </div>
                                    <i class="fas fa-external-link-alt text-muted"></i>
                                </a>
                            @endif
                            
                            @if($material->stock_in_file)
                                <a href="{{ asset('storage/' . $material->stock_in_file) }}" target="_blank" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-sign-in-alt me-2 text-info"></i>
                                        <strong>STORE IN</strong>
                                        <small class="d-block text-muted">{{ basename($material->stock_in_file) }}</small>
                                    </div>
                                    <i class="fas fa-external-link-alt text-muted"></i>
                                </a>
                            @endif
                            
                            @if($material->stock_out_file)
                                <a href="{{ asset('storage/' . $material->stock_out_file) }}" target="_blank" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-sign-out-alt me-2 text-warning"></i>
                                        <strong>STORE OUT</strong>
                                        <small class="d-block text-muted">{{ basename($material->stock_out_file) }}</small>
                                    </div>
                                    <i class="fas fa-external-link-alt text-muted"></i>
                                </a>
                            @endif
                            
                            @if($material->store_in_file)
                                <a href="{{ asset('storage/' . $material->store_in_file) }}" target="_blank" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-warehouse me-2 text-success"></i>
                                        <strong>STORE IN FILE</strong>
                                        <small class="d-block text-muted">{{ basename($material->store_in_file) }}</small>
                                    </div>
                                    <i class="fas fa-external-link-alt text-muted"></i>
                                </a>
                            @endif
                            
                            @if($material->store_out_file)
                                <a href="{{ asset('storage/' . $material->store_out_file) }}" target="_blank" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-dolly me-2 text-danger"></i>
                                        <strong>STORE OUT FILE</strong>
                                        <small class="d-block text-muted">{{ basename($material->store_out_file) }}</small>
                                    </div>
                                    <i class="fas fa-external-link-alt text-muted"></i>
                                </a>
                            @endif
                            
                            @if($material->ddo_file)
                                <a href="{{ asset('storage/' . $material->ddo_file) }}" target="_blank" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file-alt me-2 text-primary"></i>
                                        <strong>DDO FILE</strong>
                                        <small class="d-block text-muted">{{ basename($material->ddo_file) }}</small>
                                    </div>
                                    <i class="fas fa-external-link-alt text-muted"></i>
                                </a>
                            @endif
                        </div>
                        
                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                انقر على أي ملف لعرضه في نافذة جديدة
                            </small>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-file-slash fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">لا توجد ملفات مرفقة</h6>
                            <p class="text-muted small">لا توجد ملفات مرفقة لهذه المادة</p>
                            <a href="{{ route('admin.work-orders.materials.edit', [$material->workOrder, $material]) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus"></i> إضافة ملفات
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.badge-lg {
    font-size: 1.1rem;
    padding: 0.5rem 0.75rem;
}

.list-group-item-action:hover {
    background-color: #f8f9fa;
    transform: translateX(-2px);
    transition: all 0.3s ease;
}

.card {
    border: none;
    border-radius: 0.75rem;
}

.card-header {
    border-radius: 0.75rem 0.75rem 0 0 !important;
    border-bottom: 1px solid rgba(0,0,0,.125);
}

.form-control-plaintext {
    font-size: 1rem;
    line-height: 1.5;
}

.text-muted.small {
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

@media (max-width: 768px) {
    .badge-lg {
        font-size: 1rem;
        padding: 0.4rem 0.6rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // تحسين عرض الملفات
    $('.list-group-item-action').hover(
        function() {
            $(this).find('.fa-external-link-alt').addClass('text-primary');
        },
        function() {
            $(this).find('.fa-external-link-alt').removeClass('text-primary').addClass('text-muted');
        }
    );
    
    // إضافة tooltips
    $('[title]').tooltip();
    
    // تحسين عرض الرسائل
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif
    
    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif
});
</script>
@endpush
@endsection 