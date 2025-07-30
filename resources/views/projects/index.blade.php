@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- رأس الصفحة -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">المشاريع</h1>
                <a href="{{ route('projects.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-lg transform transition hover:scale-105 duration-200 flex items-center">
                    <i class="fas fa-plus ml-2"></i>
                    مشروع جديد
                </a>
            </div>
            <p class="mt-2 text-sm text-gray-600">إدارة وعرض جميع المشاريع</p>
        </div>

        <!-- قسم البحث والفلترة -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex flex-wrap gap-4">
                <!-- البحث -->
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <input type="text" 
                               id="search" 
                               placeholder="ابحث عن مشروع..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                
                <!-- فلتر نوع المشروع -->
                <div class="w-48">
                    <select id="project_type_filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">كل الأنواع</option>
                        <option value="civil">أعمال مدنية</option>
                        <option value="electrical">أعمال كهربائية</option>
                        <option value="mixed">أعمال مختلطة</option>
                    </select>
                </div>

                <!-- فلتر الحالة -->
                <div class="w-48">
                    <select id="status_filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">كل الحالات</option>
                        <option value="active">نشط</option>
                        <option value="completed">مكتمل</option>
                        <option value="on_hold">متوقف</option>
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
                                @case('civil')
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        <i class="fas fa-building ml-1"></i>
                                        أعمال مدنية
                                    </span>
                                    @break
                                @case('electrical')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        <i class="fas fa-bolt ml-1"></i>
                                        أعمال كهربائية
                                    </span>
                                    @break
                                @case('mixed')
                                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        <i class="fas fa-tools ml-1"></i>
                                        أعمال مختلطة
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
                        <div class="flex space-x-2 space-x-reverse">
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
                                    <i class="fas fa-check-circle ml-1"></i>
                                    نشط
                                </span>
                                @break
                            @case('completed')
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    <i class="fas fa-flag-checkered ml-1"></i>
                                    مكتمل
                                </span>
                                @break
                            @case('on_hold')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    <i class="fas fa-pause-circle ml-1"></i>
                                    متوقف
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
                    <h3 class="text-xl font-medium text-gray-900 mb-1">لا توجد مشاريع</h3>
                    <p class="text-gray-600 mb-4">لم يتم إنشاء أي مشاريع حتى الآن</p>
                    <a href="{{ route('projects.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200">
                        <i class="fas fa-plus ml-2"></i>
                        إنشاء مشروع جديد
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