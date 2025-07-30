@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- رأس الصفحة مع الأزرار -->
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition duration-200">
                    <i class="fas fa-arrow-right ml-2"></i>
                    رجوع للوحة التحكم
                </a>
                <a href="{{ route('projects.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200">
                    <i class="fas fa-plus ml-2"></i>
                    إنشاء مشروع جديد
                </a>
            </div>
            <div class="text-right">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">المشاريع</h1>
                <p class="text-gray-600">إدارة وعرض المشاريع</p>
            </div>
        </div>

        <!-- إحصائيات المشاريع -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-gray-900">{{ \App\Models\Project::count() }}</div>
                <div class="text-sm text-gray-600">إجمالي المشاريع</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ \App\Models\Project::where('project_type', 'civil')->count() }}</div>
                <div class="text-sm text-gray-600">المشاريع المدنية</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-yellow-500">{{ \App\Models\Project::where('project_type', 'electrical')->count() }}</div>
                <div class="text-sm text-gray-600">المشاريع الكهربائية</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-purple-600">{{ \App\Models\Project::where('project_type', 'mixed')->count() }}</div>
                <div class="text-sm text-gray-600">المشاريع المختلطة</div>
            </div>
        </div>

        <!-- قائمة المشاريع -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse(\App\Models\Project::latest()->get() as $project)
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                <div class="p-6">
                    <!-- رأس البطاقة -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $project->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $project->contract_number }}</p>
                        </div>
                        <div>
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

                    <!-- أزرار الإجراءات -->
                    <div class="mt-6 flex justify-end space-x-2 space-x-reverse">
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
    </div>
</div>
@endsection 