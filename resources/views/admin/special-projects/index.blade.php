@extends('layouts.app')

@push('styles')
<style>
    /* تنسيق RTL للصفحة */
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
    
    .project-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .project-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--project-color, #667eea);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }
    
    .project-card:hover::before {
        transform: scaleX(1);
    }
    
    .project-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }
    
    .action-button {
        display: inline-flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .action-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .primary-button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .primary-button:hover {
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .secondary-button {
        background: linear-gradient(145deg, #f1f5f9 0%, #e2e8f0 100%);
        color: #64748b;
        border: 1px solid #cbd5e1;
    }
    
    .secondary-button:hover {
        background: linear-gradient(145deg, #e2e8f0 0%, #cbd5e1 100%);
        color: #475569;
    }
    
    .type-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .empty-state {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border: 2px dashed #cbd5e1;
        border-radius: 20px;
        padding: 3rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .empty-state:hover {
        border-color: #667eea;
        background: linear-gradient(145deg, #f8fafc 0%, #f1f5f9 100%);
    }
    
    @media (max-width: 768px) {
        .project-card {
            margin: 0 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="alert alert-success mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-right">
            <i class="fas fa-check-circle text-green-600 me-2"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-right">
            <i class="fas fa-exclamation-circle text-red-600 me-2"></i>
            {{ session('error') }}
        </div>
        @endif
        
        <!-- Header -->
        <div class="project-header rounded-3xl shadow-2xl overflow-hidden mb-12 relative">
            <div class="relative z-10 p-8">
                <div class="flex justify-between items-start w-full">
                    <div class="flex flex-col items-end">
                        <a href="{{ route('admin.special-projects.create') }}" 
                           class="primary-button mb-4">
                            <i class="fas fa-plus me-2"></i>
                            إنشاء مشروع جديد
                        </a>
                        <div class="text-right text-white">
                            <h1 class="text-4xl font-bold mb-2">المشاريع الخاصة</h1>
                            <p class="text-purple-100 text-lg">إدارة ومتابعة المشاريع الخاصة</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 text-white rounded-xl font-semibold transition-all duration-300 hover:bg-white/30 hover:transform hover:scale-105">
                            <i class="fas fa-arrow-right me-2"></i>
                            العودة للوحة التحكم
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse(\App\Models\Project::where('project_type', 'special')->latest()->get() as $project)
            <div class="project-card rounded-2xl overflow-hidden p-6" 
                 style="--project-color: #8b5cf6;">
                
                <!-- Project Header -->
                <div class="flex flex-col items-end mb-6">
                    <div class="mb-3 text-right">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $project->name }}</h3>
                        <p class="text-sm text-gray-600 font-mono bg-gray-100 px-3 py-1 rounded-lg inline-block">
                            {{ $project->contract_number }}
                        </p>
                    </div>
                    <div>
                        <span class="type-badge bg-purple-100 text-purple-800">
                            <i class="fas fa-briefcase me-1"></i>
                            مشروع خاص
                        </span>
                    </div>
                </div>

                <!-- Project Details -->
                <div class="space-y-4 mb-6 text-right">
                    <div class="flex items-center text-gray-600 justify-end">
                        <span class="text-sm font-medium">{{ $project->location }}</span>
                        <i class="fas fa-map-marker-alt ms-3 flex-shrink-0"></i>
                    </div>
                    
                    <!-- Project Amount -->
                    <div class="flex items-center text-gray-600 mb-2 justify-end">
                        <span class="text-sm font-medium">
                            {{ $project->amount ? number_format($project->amount, 2) . ' ريال سعودي' : 'لم يتم تحديد المبلغ' }}
                        </span>
                        <i class="fas fa-money-bill-wave ms-3 flex-shrink-0"></i>
                    </div>

                    @if($project->description)
                    <div class="flex items-start text-gray-600 justify-end">
                        <p class="text-sm line-clamp-2 text-right">{{ $project->description }}</p>
                        <i class="fas fa-file-alt ms-3 flex-shrink-0 mt-0.5"></i>
                    </div>
                    @endif
                </div>

                <!-- Project Timeline -->
                <div class="mb-6 p-4 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl border border-purple-200 text-right">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center justify-end">
                        الجدول الزمني للمشروع
                        <i class="fas fa-clock ms-2"></i>
                    </h4>
                    
                    <div class="grid grid-cols-2 gap-3 text-xs text-right">
                        <div class="flex flex-col items-end">
                            <span class="text-gray-500 uppercase tracking-wide text-[0.65rem]">تاريخ التوقيع</span>
                            <span class="font-medium text-gray-700">
                                {{ $project->srgn_date ? $project->srgn_date->format('Y/m/d') : 'غير محدد' }}
                            </span>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-gray-500 uppercase tracking-wide text-[0.65rem]">تاريخ بدء العمل</span>
                            <span class="font-medium text-gray-700">
                                {{ $project->kick_off_date ? $project->kick_off_date->format('Y/m/d') : 'غير محدد' }}
                            </span>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-gray-500 uppercase tracking-wide text-[0.65rem]">TCC</span>
                            <span class="font-medium text-gray-700">
                                {{ $project->tcc_date ? $project->tcc_date->format('Y/m/d') : 'غير محدد' }}
                            </span>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-gray-500 uppercase tracking-wide text-[0.65rem]">PAC</span>
                            <span class="font-medium text-gray-700">
                                {{ $project->pac_date ? $project->pac_date->format('Y/m/d') : 'غير محدد' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <!-- Full Width View Project Button -->
                    <a href="{{ route('admin.special-projects.show', $project) }}" 
                       class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-300 hover:transform hover:scale-105 hover:shadow-xl">
                        <i class="fas fa-eye me-2"></i>
                        عرض المشروع
                    </a>
                    
                    <!-- Secondary Actions Row -->
                    <div class="flex justify-center gap-3">
                        <a href="{{ route('admin.special-projects.edit', $project) }}" 
                           class="action-button bg-yellow-100 text-yellow-700 hover:bg-yellow-200"
                           title="تعديل المشروع">
                            <i class="fas fa-edit me-1"></i>
                            تعديل
                        </a>
                        
                        <form action="{{ route('admin.special-projects.destroy', $project) }}" 
                              method="POST" 
                              onsubmit="return confirm('هل أنت متأكد من حذف هذا المشروع؟');">
                            @csrf
                            @method('DELETE')
                            
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="empty-state">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-briefcase text-4xl text-purple-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">لا توجد مشاريع خاصة</h3>
                    <p class="text-gray-600 mb-6">لم يتم إنشاء أي مشاريع خاصة حتى الآن. ابدأ بإنشاء أول مشروع.</p>
                    <a href="{{ route('admin.special-projects.create') }}" 
                       class="primary-button">
                        <i class="fas fa-plus me-2"></i>
                        إنشاء مشروع جديد
                    </a>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

