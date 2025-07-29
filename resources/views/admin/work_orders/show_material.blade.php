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
                    
                </div>
                <div>
                    <a href="{{ route('admin.work-orders.materials.edit', [$material->workOrder, $material]) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> تعديل المادة
                    </a>
                    <a href="{{ route('admin.work-orders.materials.index', $material->workOrder) }}" class="btn btn-info">
                        <i class="fas fa-file-upload"></i> إدارة الملفات
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
                        
                        
                    </div>
                </div>
        </div>
    </div>

    <!-- تنبيه حول الملفات -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-info-circle fa-2x text-info"></i>
                    </div>
                    <div>
                        <h6 class="alert-heading mb-1">إدارة الملفات المرفوعة</h6>
                        <p class="mb-0 small">لرفع أو عرض الملفات المرفقة بالمواد، يرجى الانتقال إلى صفحة إدارة المواد الرئيسية</p>
                    </div>
                    <div class="ms-auto">
                        <a href="{{ route('admin.work-orders.materials.index', $material->workOrder) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-file-upload me-1"></i>
                            إدارة الملفات
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- معلومات المادة الأساسية -->
        <div class="col-12">
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
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted small">الوحدة</label>
                            <div class="form-control-plaintext">
                                <span class="badge badge-light">{{ $material->unit ?: '-' }}</span>
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
                    <!-- الصف الأول: الكمية المخططة، المصروفة، والفرق بينهما -->
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small">الكمية المخططة</label>
                            <div class="text-center">
                                <div class="h4 mb-0">
                                    <span class="badge badge-primary badge-lg">{{ number_format($material->planned_quantity, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small">الكمية المصروفة</label>
                            <div class="text-center">
                                <div class="h4 mb-0">
                                    <span class="badge badge-info badge-lg">{{ number_format($material->spent_quantity, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small">الفرق (المخططة - المصروفة)</label>
                            <div class="text-center">
                                @php
                                    $plannedSpentDiff = ($material->planned_quantity ?? 0) - ($material->spent_quantity ?? 0);
                                @endphp
                                <div class="h4 mb-0">
                                    @if($plannedSpentDiff > 0)
                                        <span class="badge badge-warning badge-lg" title="يوجد كمية مخططة لم يتم صرفها">
                                            +{{ number_format($plannedSpentDiff, 2) }}
                                        </span>
                                        <small class="d-block text-warning">كمية لم يتم صرفها</small>
                                    @elseif($plannedSpentDiff < 0)
                                        <span class="badge badge-danger badge-lg" title="تم صرف كمية أكثر من المخطط">
                                            {{ number_format($plannedSpentDiff, 2) }}
                                        </span>
                                        <small class="d-block text-danger">صرف زائد</small>
                                    @else
                                        <span class="badge badge-success badge-lg" title="متطابقة">
                                            <i class="fas fa-check"></i> متطابقة
                                        </span>
                                        <small class="d-block text-success">مطابقة للمخطط</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الصف الثاني: الكمية المنفذة والفرق بين المنفذة والمصروفة -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small">الكمية المنفذة</label>
                            <div class="text-center">
                                <div class="h4 mb-0">
                                    <span class="badge badge-success badge-lg">{{ number_format($material->executed_quantity ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small">الفرق (المنفذة - المصروفة)</label>
                            <div class="text-center">
                                @php
                                    $executedSpentDiff = ($material->executed_quantity ?? 0) - ($material->spent_quantity ?? 0);
                                @endphp
                                <div class="h4 mb-0">
                                    @if($executedSpentDiff > 0)
                                        <span class="badge badge-warning badge-lg" title="تم تنفيذ كمية أكثر من المصروفة">
                                            +{{ number_format($executedSpentDiff, 2) }}
                                        </span>
                                        <small class="d-block text-warning">تنفيذ أكثر من المصروف</small>
                                    @elseif($executedSpentDiff < 0)
                                        <span class="badge badge-danger badge-lg" title="تم صرف كمية أكثر من المنفذة">
                                            {{ number_format($executedSpentDiff, 2) }}
                                        </span>
                                        <small class="d-block text-danger">صرف أكثر من المنفذ</small>
                                    @else
                                        <span class="badge badge-success badge-lg" title="متطابقة">
                                            <i class="fas fa-check"></i> متطابقة
                                        </span>
                                        <small class="d-block text-success">مطابقة للمنفذ</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <!-- عمود فارغ للتوازن -->
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
        

    </div>
</div>

@push('styles')
<style>
.badge-lg {
    font-size: 1.1rem;
    padding: 0.5rem 0.75rem;
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