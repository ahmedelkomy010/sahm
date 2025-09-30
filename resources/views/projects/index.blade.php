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
    .ltr-page .ml-1 { margin-left: 0.25rem; margin-right: 0; }
    .ltr-page .ml-2 { margin-left: 0.5rem; margin-right: 0; }
    .ltr-page .ml-3 { margin-left: 0.75rem; margin-right: 0; }
    .ltr-page .mr-1 { margin-right: 0.25rem; margin-left: 0; }
    .ltr-page .mr-2 { margin-right: 0.5rem; margin-left: 0; }
    .ltr-page .mr-3 { margin-right: 0.75rem; margin-left: 0; }
    
    .ltr-page .pl-10 { padding-left: 2.5rem; padding-right: 1rem; }
    .ltr-page .pr-4 { padding-right: 1rem; padding-left: 1rem; }
    .ltr-page .right-3 { right: auto; left: 0.75rem; }
    
    /* تعديل التوجه للعناصر */
    .ltr-page .flex-row-reverse { flex-direction: row; }
    .ltr-page .justify-between { justify-content: space-between; }
    .ltr-page .justify-end { justify-content: flex-start; }
    .ltr-page .items-end { align-items: flex-start; }
    .ltr-page .text-right { text-align: left; }
    
    /* تعديل الـ space للعناصر */
    .ltr-page .space-x-2 > :not([hidden]) ~ :not([hidden]) {
        --tw-space-x-reverse: 0;
        margin-right: calc(0.5rem * var(--tw-space-x-reverse));
        margin-left: calc(0.5rem * calc(1 - var(--tw-space-x-reverse)));
    }
    
    .ltr-page .space-x-reverse > :not([hidden]) ~ :not([hidden]) {
        --tw-space-x-reverse: 0;
        margin-right: calc(0.5rem * var(--tw-space-x-reverse));
        margin-left: calc(0.5rem * calc(1 - var(--tw-space-x-reverse)));
    }
    
    /* تعديل مخصص للبطاقات */
    .ltr-page .project-card {
        text-align: left;
    }
    
    .ltr-page .project-card .badge {
        text-align: left;
    }
    
    /* تعديل أيقونات البحث */
    .ltr-page .search-icon {
        right: auto;
        left: 0.75rem;
    }
    
    /* تعديل الأيقونات في الوصف */
    .ltr-page .fas.fa-align-right:before {
        content: "\f036"; /* fa-align-left */
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50 py-8 ltr-page">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- رأس الصفحة -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">Projects</h1>
                <a href="{{ route('projects.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-lg transform transition hover:scale-105 duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    New Project
                </a>
            </div>
            <p class="mt-2 text-sm text-gray-600">Manage and view all projects</p>
        </div>

        <!-- قسم البحث والفلترة -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex flex-wrap gap-4">
                <!-- البحث -->
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <input type="text" 
                               id="search" 
                               placeholder="Search for project..." 
                               class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <i class="fas fa-search absolute right-3 top-3 text-gray-400 search-icon"></i>
                    </div>
                </div>
                
                <!-- فلتر نوع المشروع -->
                <div class="w-48">
                    <select id="project_type_filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Types</option>
                        <option value="OH33KV">OH 33KV</option>
                        <option value="UA33LW">UA 33LW</option>
                        <option value="SLS33KV">SLS 33KV</option>
                        <option value="UG132KV">UG 132 KV</option>
                    </select>
                </div>

                <!-- فلتر الحالة -->
                <div class="w-48">
                    <select id="status_filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                        <option value="on_hold">On Hold</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- عرض المشاريع -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($projects as $project)
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                <div class="p-6">
                    <!-- رأس البطاقة -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $project->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $project->contract_number }}</p>
                        </div>
                        <div class="flex items-center">
                            @switch($project->project_type)
                                @case('OH33KV')
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        <i class="fas fa-bolt mr-1"></i>
                                        OH 33KV
                                    </span>
                                    @break
                                @case('UA33LW')
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        <i class="fas fa-chart-bar mr-1"></i>
                                        UA 33LW
                                    </span>
                                    @break
                                @case('SLS33KV')
                                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        <i class="fas fa-star mr-1"></i>
                                        SLS 33KV
                                    </span>
                                    @break
                                @case('UG132KV')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        <i class="fas fa-flask mr-1"></i>
                                        UG 132 KV
                                    </span>
                                    @break
                            @endswitch
                        </div>
                    </div>

                    <!-- تفاصيل المشروع -->
                    <div class="space-y-3">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-map-marker-alt w-5"></i>
                            <span class="text-sm">{{ $project->location }}</span>
                        </div>
                        @if($project->description)
                        <div class="flex items-start text-gray-600">
                            <i class="fas fa-align-right w-5 mt-1"></i>
                            <p class="text-sm line-clamp-2">{{ $project->description }}</p>
                        </div>
                        @endif
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-calendar-alt w-5"></i>
                            <span class="text-sm">{{ $project->created_at->format('Y/m/d') }}</span>
                        </div>
                    </div>

                    <!-- الإجراءات -->
                    <div class="mt-6 flex justify-between items-center">
                        <div class="flex space-x-2">
                            <a href="{{ route('projects.show', $project) }}" 
                               class="text-blue-600 hover:text-blue-800 transition-colors">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('projects.edit', $project) }}" 
                               class="text-yellow-600 hover:text-yellow-800 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('projects.destroy', $project) }}" 
                                  method="POST" 
                                  class="inline-block"
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المشروع؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                        
                        <!-- حالة المشروع -->
                        @switch($project->status)
                            @case('active')
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Active
                                </span>
                                @break
                            @case('completed')
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    <i class="fas fa-flag-checkered mr-1"></i>
                                    Completed
                                </span>
                                @break
                            @case('on_hold')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    <i class="fas fa-pause-circle mr-1"></i>
                                    On Hold
                                </span>
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-md p-6 text-center">
                    <div class="mb-4">
                        <i class="fas fa-folder-open text-gray-400 text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-900 mb-1">No Projects Found</h3>
                    <p class="text-gray-600 mb-4">No projects have been created yet</p>
                    <a href="{{ route('projects.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Create New Project
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- الترقيم -->
        @if($projects->hasPages())
        <div class="mt-6">
            {{ $projects->links() }}
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const typeFilter = document.getElementById('project_type_filter');
    const statusFilter = document.getElementById('status_filter');
    const projectCards = document.querySelectorAll('.project-card');

    function filterProjects() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedType = typeFilter.value;
        const selectedStatus = statusFilter.value;

        projectCards.forEach(card => {
            const projectName = card.querySelector('.project-name').textContent.toLowerCase();
            const projectType = card.dataset.type;
            const projectStatus = card.dataset.status;

            const matchesSearch = projectName.includes(searchTerm);
            const matchesType = !selectedType || projectType === selectedType;
            const matchesStatus = !selectedStatus || projectStatus === selectedStatus;

            if (matchesSearch && matchesType && matchesStatus) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterProjects);
    typeFilter.addEventListener('change', filterProjects);
    statusFilter.addEventListener('change', filterProjects);
});
</script>
@endsection 