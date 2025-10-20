@extends('layouts.app')

@push('styles')
<style>
    .project-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }
    
    .project-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        opacity: 0.3;
    }
    
    .info-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .info-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .info-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-value {
        font-size: 1.125rem;
        color: #111827;
        font-weight: 700;
    }
    
    .section-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    
    .section-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }
    
    .section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .section-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        opacity: 0.3;
    }
    
    .section-title {
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .section-body {
        padding: 2rem;
        background: white;
    }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.875rem 1.75rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        cursor: pointer;
        font-size: 1rem;
        text-decoration: none;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Project Header -->
    <div class="project-header rounded-3xl shadow-2xl mb-8 overflow-hidden">
        <div class="p-8 relative z-10">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-3">{{ $project->name }}</h1>
                    <p class="text-purple-100 text-lg font-mono bg-white/20 px-4 py-2 rounded-lg inline-block">
                        {{ $project->contract_number }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.special-projects.index') }}" class="px-6 py-3 bg-white/20 backdrop-blur-sm rounded-xl text-white font-bold text-lg border border-white/30 hover:bg-white/30 transition-all duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        العودة للقائمة
                    </a>
                    <a href="{{ route('admin.special-projects.revenues', $project) }}" class="px-6 py-3 bg-green-500/90 backdrop-blur-sm rounded-xl text-white font-bold text-lg border border-green-400/30 hover:bg-green-600 transition-all duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        عرض الإيرادات
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Location -->
        <div class="info-card">
            <div class="info-label">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                الموقع
            </div>
            <div class="info-value">{{ $project->location }}</div>
        </div>

        <!-- Amount -->
        <div class="info-card">
            <div class="info-label">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                قيمة المشروع
            </div>
            <div class="info-value">
                @if($project->amount)
                    {{ number_format($project->amount, 2) }} ريال سعودي
                @else
                    غير محدد
                @endif
            </div>
        </div>

        <!-- Status -->
        <div class="info-card">
            <div class="info-label">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                الحالة
            </div>
            <div class="info-value">
                @if($project->status == 'active')
                    <span class="text-green-600">نشط</span>
                @elseif($project->status == 'completed')
                    <span class="text-blue-600">مكتمل</span>
                @else
                    <span class="text-yellow-600">معلق</span>
                @endif
            </div>
        </div>

        <!-- Creation Date -->
        <div class="info-card">
            <div class="info-label">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                تاريخ الإنشاء
            </div>
            <div class="info-value">{{ $project->created_at->format('Y/m/d') }}</div>
        </div>
    </div>

    <!-- Description -->
    @if($project->description)
    <div class="info-card mb-8">
        <div class="info-label">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1 -2 -2V5a2 2 0 0 1 2 -2h5.586a1 1 0 0 1 .707 .293l5.414 5.414a1 1 0 0 1 .293 .707V19a2 2 0 0 1 -2 2z"></path>
            </svg>
            وصف المشروع
        </div>
        <div class="info-value text-gray-700 mt-2">{{ $project->description }}</div>
    </div>
    @endif

    <!-- Project Attachments -->
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-title">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                </svg>
                المرفقات
            </h2>
        </div>
        <div class="section-body">
            <!-- Upload Form -->
            <div class="mb-4">
                <form action="{{ route('admin.special-projects.attachments.upload', $project) }}" method="POST" enctype="multipart/form-data" id="attachmentForm">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <label for="attachments" class="form-label fw-bold">
                                <i class="fas fa-cloud-upload-alt me-2 text-primary"></i>
                                رفع ملفات جديدة
                            </label>
                            <input type="file" 
                                   class="form-control @error('attachments.*') is-invalid @enderror" 
                                   name="attachments[]" 
                                   id="attachments"
                                   multiple
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                يمكنك رفع عدة ملفات (PDF, Word, Excel, صور) - الحد الأقصى 20 ميجابايت لكل ملف
                            </small>
                            @error('attachments.*')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-upload me-2"></i>
                                رفع الملفات
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <hr class="my-4">

            <!-- Attachments List -->
            @if(isset($project->attachments) && count($project->attachments) > 0)
            <div class="row g-3">
                @foreach($project->attachments as $index => $attachment)
                    @php
                        $fileName = basename($attachment);
                        $extension = pathinfo($attachment, PATHINFO_EXTENSION);
                        $iconClass = match(strtolower($extension)) {
                            'pdf' => 'fa-file-pdf text-danger',
                            'doc', 'docx' => 'fa-file-word text-primary',
                            'xls', 'xlsx' => 'fa-file-excel text-success',
                            'jpg', 'jpeg', 'png', 'gif' => 'fa-file-image text-info',
                            default => 'fa-file text-secondary'
                        };
                    @endphp
                    <div class="col-md-4 col-sm-6">
                        <div class="card border h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas {{ $iconClass }} fs-2 me-3"></i>
                                    <div class="flex-grow-1 text-truncate">
                                        <h6 class="mb-1 text-truncate" title="{{ $fileName }}">{{ $fileName }}</h6>
                                        <small class="text-muted">{{ strtoupper($extension) }}</small>
                                    </div>
                                </div>
                                <div class="mt-auto d-flex gap-2">
                                    <a href="{{ Storage::url($attachment) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-primary flex-grow-1">
                                        <i class="fas fa-eye me-1"></i>
                                        عرض
                                    </a>
                                    <form action="{{ route('admin.special-projects.attachments.delete', ['project' => $project, 'index' => $index]) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا المرفق؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                <p class="text-muted mb-0">لا توجد مرفقات حالياً</p>
                <small class="text-muted">استخدم النموذج أعلاه لرفع الملفات</small>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Future: Add revenue management functionality here
    console.log('Special Project View Loaded:', @json($project->id));
</script>
@endpush

