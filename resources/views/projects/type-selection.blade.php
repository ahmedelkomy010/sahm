@extends('layouts.app')

@push('styles')
<style>
    /* تنسيق LTR للصفحة */
    .ltr-page {
        direction: ltr;
        text-align: left;
    }
    
    .ltr-page * {
        text-align: left;
    }
    
    /* تعديل الـ margin والـ padding للتنسيق LTR */
    .ltr-page .mr-2 { margin-right: 0.5rem; margin-left: 0; }
    .ltr-page .mr-3 { margin-right: 0.75rem; margin-left: 0; }
    .ltr-page .mr-1 { margin-right: 0.25rem; margin-left: 0; }
    .ltr-page .ml-2 { margin-left: 0.5rem; margin-right: 0; }
    .ltr-page .ml-3 { margin-left: 0.75rem; margin-right: 0; }
    .ltr-page .ml-1 { margin-left: 0.25rem; margin-right: 0; }
    
    /* تعديل التوجه للعناصر */
    .ltr-page .flex-row-reverse { flex-direction: row; }
    .ltr-page .justify-end { justify-content: flex-start; }
    .ltr-page .items-end { align-items: flex-start; }
    .ltr-page .text-right { text-align: left; }
    
    /* تعديل مخصص للأزرار والعناصر */
    .ltr-page .action-button {
        justify-content: flex-start;
    }
    
    /* تحسينات إضافية للتنسيق LTR */
    .ltr-page .project-card {
        text-align: left;
    }
    
    .ltr-page .type-badge {
        text-align: left;
    }
    
    .ltr-page .primary-button,
    .ltr-page .secondary-button {
        text-align: left;
    }
    
    /* تعديل أيقونات الأسهم للتنسيق LTR */
    .ltr-page .back-arrow {
        transform: scaleX(-1);
    }
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
    
    .action-button.flex-1 {
        display: flex;
        align-items: center;
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
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-12 ltr-page">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="project-header rounded-3xl shadow-2xl overflow-hidden mb-12 relative">
            <div class="relative z-10 p-8">
                <div class="flex justify-between items-start w-full">
                    <div class="flex flex-col items-start">
                        <a href="{{ route('projects.create') }}" 
                           class="primary-button mb-4">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create New Project
                        </a>
                        <div class="text-left text-white">
                            <h1 class="text-4xl font-bold mb-2">Projects</h1>
                            <p class="text-blue-100 text-lg">Project Management & Overview</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 text-white rounded-xl font-semibold transition-all duration-300 hover:bg-white/30 hover:transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2 back-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <!-- Projects List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse(\App\Models\Project::latest()->get() as $project)
            <div class="project-card rounded-2xl overflow-hidden p-6" 
                 style="--project-color: {{ 
                    $project->project_type === 'OH33KV' ? '#3b82f6' : (
                        $project->project_type === 'UA33LW' ? '#10b981' : (
                            $project->project_type === 'SLS33KV' ? '#8b5cf6' : (
                                $project->project_type === 'UG132KV' ? '#f59e0b' : '#667eea'
                            )
                        )
                    )
                 }};">
                
                <!-- Project Header -->
                <div class="flex flex-col items-start mb-6">
                    <div class="mb-3">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $project->name }}</h3>
                        <p class="text-sm text-gray-600 font-mono bg-gray-100 px-3 py-1 rounded-lg inline-block">
                            {{ $project->contract_number }}
                        </p>
                    </div>
                    <div>
                        @switch($project->project_type)
                            @case('OH33KV')
                                <span class="type-badge bg-blue-100 text-blue-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    OH 33KV
                                </span>
                                @break
                            @case('UA33LW')
                                <span class="type-badge bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    UA 33LW
                                </span>
                                @break
                            @case('SLS33KV')
                                <span class="type-badge bg-purple-100 text-purple-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                    SLS 33KV
                                </span>
                                @break
                            @case('UG132KV')
                                <span class="type-badge bg-yellow-100 text-yellow-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                    </svg>
                                    UG 132 KV
                                </span>
                                @break
                        @endswitch
                    </div>
                </div>

                <!-- Project Details -->
                <div class="space-y-4 mb-6">
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">{{ $project->location }}</span>
                    </div>
                    
                    <!-- Project Amount -->
                    <div class="flex items-center text-gray-600 mb-2">
                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">
                            {{ $project->amount ? number_format($project->amount, 2) . ' ريال سعودي' : 'لم يتم تحديد المبلغ' }}
                        </span>
                    </div>

                    @if($project->description)
                    <div class="flex items-start text-gray-600">
                        <svg class="w-4 h-4 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-sm line-clamp-2">{{ $project->description }}</p>
                    </div>
                    @endif
                    
                </div>

                <!-- Project Timeline -->
                <div class="mb-6 p-4 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl border border-gray-200 text-left">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-start">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Project Timeline
                    </h4>
                    
                    <div class="grid grid-cols-5 gap-2 text-xs text-left">
                        <div class="flex flex-col items-start">
                            <span class="text-gray-500 uppercase tracking-wide">SIGN Date</span>
                            <span class="font-medium text-gray-700">
                                {{ $project->srgn_date ? $project->srgn_date->format('M d, Y') : 'Not Set' }}
                            </span>
                        </div>
                        <div class="flex flex-col items-start">
                            <span class="text-gray-500 uppercase tracking-wide text-[0.65rem]">KICK OFF MEETING Date</span>
                            <span class="font-medium text-gray-700">
                                {{ $project->kick_off_date ? $project->kick_off_date->format('M d, Y') : 'Not Set' }}
                            </span>
                        </div>
                        <div class="flex flex-col items-start">
                            <span class="text-gray-500 uppercase tracking-wide">TCC Date</span>
                            <span class="font-medium text-gray-700">
                                {{ $project->tcc_date ? $project->tcc_date->format('M d, Y') : 'Not Set' }}
                            </span>
                        </div>
                        <div class="flex flex-col items-start">
                            <span class="text-gray-500 uppercase tracking-wide">PAC Date</span>
                            <span class="font-medium text-gray-700">
                                {{ $project->pac_date ? $project->pac_date->format('M d, Y') : 'Not Set' }}
                            </span>
                        </div>
                        <div class="flex flex-col items-start">
                            <span class="text-gray-500 uppercase tracking-wide">FAC Date</span>
                            <span class="font-medium text-gray-700">
                                {{ $project->fat_date ? $project->fat_date->format('M d, Y') : 'Not Set' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <!-- Full Width View Project Button -->
                    <a href="{{ route('projects.show', $project) }}" 
                       class="w-full inline-flex items-center justify-start px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 hover:transform hover:scale-105 hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View Project
                    </a>
                    
                    <!-- Secondary Actions Row -->
                    <div class="flex justify-start space-x-3">
                        <a href="{{ route('projects.edit', $project) }}" 
                           class="action-button bg-yellow-100 text-yellow-700 hover:bg-yellow-200 justify-start"
                           title="Edit Project">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>
                        
                        <form action="{{ route('projects.destroy', $project) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this project?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="action-button bg-red-100 text-red-700 hover:bg-red-200 justify-start"
                                    title="Delete Project">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="empty-state">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No Projects Found</h3>
                    <p class="text-gray-600 mb-6">You haven't created any projects yet. Get started by creating your first project.</p>
                    <a href="{{ route('projects.create') }}" 
                       class="primary-button">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create New Project
                    </a>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection 