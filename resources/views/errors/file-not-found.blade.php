@extends('layouts.app')

@section('title', 'الملف غير موجود')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        الملف غير موجود
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-file-times" style="font-size: 4rem; color: #dc3545;"></i>
                    </div>
                    
                    <h4 class="text-danger mb-3">عذراً، الملف المطلوب غير موجود</h4>
                    
                    <p class="text-muted mb-4">
                        {{ $message ?? 'الملف الذي تحاول الوصول إليه قد يكون محذوف أو منقول أو غير متاح حالياً.' }}
                    </p>
                    
                    @if(isset($file_path))
                    <div class="alert alert-info">
                        <small><strong>مسار الملف:</strong> {{ $file_path }}</small>
                    </div>
                    @endif
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="javascript:history.back()" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            العودة للخلف
                        </a>
                        
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home me-1"></i>
                            الصفحة الرئيسية
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 