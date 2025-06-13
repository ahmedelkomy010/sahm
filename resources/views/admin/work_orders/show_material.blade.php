@extends('layouts.app')

@section('title', 'عرض المادة')
@section('header', 'تفاصيل المادة')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('admin.work-orders.materials.index', $material->workOrder) }}" class="btn btn-outline-dark">
            <i class="fas fa-arrow-right ml-1"></i>
            العودة إلى قائمة المواد
        </a>
        <a href="{{ route('admin.work-orders.materials.edit', [$material->workOrder, $material]) }}" class="btn btn-primary ms-2">
            <i class="fas fa-edit me-2"></i>
            تعديل المادة
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="h4 m-0">
                        <i class="fas fa-box me-2"></i>
                        تفاصيل المادة
                    </h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">كود المادة:</label>
                            <p class="form-control-plaintext">{{ $material->code }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">السطر:</label>
                            <p class="form-control-plaintext">{{ $material->line ?? '-' }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">وصف المادة:</label>
                            <p class="form-control-plaintext">{{ $material->description }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">الكمية المخططة:</label>
                            <p class="form-control-plaintext">{{ number_format($material->planned_quantity, 2) }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">الكمية المستهلكة:</label>
                            <p class="form-control-plaintext">{{ number_format($material->spent_quantity, 2) }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">الوحدة:</label>
                            <p class="form-control-plaintext">{{ $material->unit ?? '-' }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">تاريخ تصريح المرور:</label>
                            <p class="form-control-plaintext">{{ $material->date_gatepass ? $material->date_gatepass->format('Y-m-d') : '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">دخول المخزن:</label>
                            <p class="form-control-plaintext">{{ $material->stock_in ?? '-' }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">خروج المخزن:</label>
                            <p class="form-control-plaintext">{{ $material->stock_out ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h3 class="h5 m-0">
                        <i class="fas fa-paperclip me-2"></i>
                        الملفات المرفقة
                    </h3>
                </div>
                <div class="card-body">
                    @if($material->hasAttachments())
                        <div class="list-group list-group-flush">
                            @if($material->check_in_file)
                                <a href="{{ asset('storage/' . $material->check_in_file) }}" target="_blank" 
                                   class="list-group-item list-group-item-action">
                                    <i class="fas fa-list-check me-2"></i>
                                    ملف دخول المواد
                                </a>
                            @endif
                            
                            @if($material->gate_pass_file)
                                <a href="{{ asset('storage/' . $material->gate_pass_file) }}" target="_blank" 
                                   class="list-group-item list-group-item-action">
                                    <i class="fas fa-id-card me-2"></i>
                                    ملف تصريح البوابة
                                </a>
                            @endif
                            
                            @if($material->store_in_file)
                                <a href="{{ asset('storage/' . $material->store_in_file) }}" target="_blank" 
                                   class="list-group-item list-group-item-action">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    ملف إدخال المخزن
                                </a>
                            @endif
                            
                            @if($material->store_out_file)
                                <a href="{{ asset('storage/' . $material->store_out_file) }}" target="_blank" 
                                   class="list-group-item list-group-item-action">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    ملف إخراج المخزن
                                </a>
                            @endif
                            
                            @if($material->ddo_file)
                                <a href="{{ asset('storage/' . $material->ddo_file) }}" target="_blank" 
                                   class="list-group-item list-group-item-action">
                                    <i class="fas fa-file-alt me-2"></i>
                                    ملف DDO
                                </a>
                            @endif
                        </div>
                    @else
                        <p class="text-muted text-center">
                            <i class="fas fa-file-slash fa-2x mb-2"></i>
                            <br>
                            لا توجد ملفات مرفقة
                        </p>
                    @endif
                </div>
            </div>
            
            <div class="card shadow mt-3">
                <div class="card-header bg-secondary text-white">
                    <h3 class="h5 m-0">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات إضافية
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">تاريخ الإنشاء:</small>
                        <br>
                        <strong>{{ $material->created_at->format('Y-m-d H:i:s') }}</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">آخر تحديث:</small>
                        <br>
                        <strong>{{ $material->updated_at->format('Y-m-d H:i:s') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 