@extends('layouts.app')

@push('styles')
<style>
    .header-gradient {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        position: relative;
        overflow: hidden;
    }
    
    .header-gradient::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        opacity: 0.3;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back Button -->
        <div class="mb-8 flex justify-end">
            <a href="{{ route('projects.revenues', $project) }}" 
               class="inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-green-200 text-green-700 rounded-xl font-semibold shadow-lg hover:bg-white hover:shadow-xl transition-all duration-300 hover:transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Turnkey Revenues
            </a>
        </div>
        
        <!-- Folder Header -->
        <div class="header-gradient rounded-3xl shadow-2xl overflow-hidden mb-12 relative">
            <div class="relative z-10 p-8 text-center text-white">
                <div class="mb-6">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold mb-3">{{ $folderName }}</h1>
                    @if($description)
                    <p class="text-green-100 text-lg mt-3">{{ $description }}</p>
                    @endif
                    <p class="text-green-100 text-sm mt-2">{{ $project->name }} - Turnkey Revenues</p>
                </div>

                <div class="max-w-md mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-green-100 text-sm mb-1">Files in this folder</p>
                        <p class="font-semibold text-2xl">{{ count($files) }} Files</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-green-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-red-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <!-- Files List -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8">
                <h2 class="text-xl font-bold text-green-900 mb-6 text-right">الملفات</h2>
                
                @if(count($files) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($files as $file)
                        <div class="bg-gradient-to-br from-blue-50 to-white border-2 border-blue-100 rounded-xl p-6 hover:shadow-lg transition-all duration-300 hover:scale-105">
                            <div class="flex items-start mb-4">
                                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center ml-3 flex-shrink-0">
                                    @if(in_array(strtolower($file['extension']), ['jpg', 'jpeg', 'png', 'gif', 'svg']))
                                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    @elseif(strtolower($file['extension']) === 'pdf')
                                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    @else
                                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-green-900 mb-1 truncate text-right" title="{{ $file['name'] }}">{{ $file['name'] }}</h3>
                                    <p class="text-xs text-green-500">{{ number_format($file['size'] / 1024, 2) }} KB</p>
                                    <p class="text-xs text-green-400 mt-1">{{ $file['created_at'] }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2 mt-4 pt-4 border-t border-blue-100">
                                <a href="{{ $file['url'] }}" target="_blank" class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition-colors text-center">
                                    <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    عرض
                                </a>
                                <a href="{{ $file['url'] }}" download class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm transition-colors">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-green-900 mb-2">لا توجد ملفات</h3>
                        <p class="text-green-600">المجلد فارغ حالياً</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

