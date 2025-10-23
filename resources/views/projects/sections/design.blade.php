@extends('layouts.app')

@push('styles')
<style>
    .header-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    
    .section-card {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .section-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: var(--card-color);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .section-card:hover::before {
        transform: scaleX(1);
    }
    
    .section-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-100 py-12" dir="ltr">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back Button -->
        <div class="mb-8 flex justify-start">
            <a href="{{ route('projects.show', $project) }}" 
               class="inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 text-gray-700 rounded-xl font-semibold shadow-lg hover:bg-white hover:shadow-xl transition-all duration-300 hover:transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Project
            </a>
        </div>
        
        <!-- Header -->
        <div class="header-gradient rounded-3xl shadow-2xl overflow-hidden mb-12 relative">
            <div class="relative z-10 p-8 text-center text-white">
                <div class="mb-6">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm14 0a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold mb-3">Design</h1>
                    <p class="text-purple-100 text-lg">{{ $project->name }}</p>
                    <p class="text-purple-100 text-sm mt-2">Contract: {{ $project->contract_number }}</p>
        </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-purple-100 text-sm mb-1">Project Type</p>
                        <p class="font-semibold text-lg">{{ $project->getProjectTypeText() }}</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-purple-100 text-sm mb-1">Location</p>
                        <p class="font-semibold text-lg">{{ $project->location }}</p>
                    </div>
                </div>
            </div>
                </div>
                
        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
                        <div class="flex items-center">
                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                <p class="text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <!-- Design Sections Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            
            <!-- Detail Design Section -->
            <a href="{{ route('projects.design.detail', $project) }}" 
               class="section-card block bg-white rounded-2xl shadow-xl overflow-hidden" 
               style="--card-color: #10b981;">
                <div class="p-8">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Detail Design</h3>
                            <p class="text-gray-600">Detailed engineering plans</p>
                    </div>
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                </div>
                
                    <div class="bg-gradient-to-br from-green-50 to-white rounded-xl p-6 border-2 border-green-100">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-green-600 mb-1">{{ $detailFoldersCount ?? 0 }}</div>
                                <p class="text-sm text-gray-600">Folders</p>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-green-600 mb-1">{{ $detailFilesCount ?? 0 }}</div>
                                <p class="text-sm text-gray-600">Files</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex items-center justify-center text-green-600 font-semibold">
                        <span>Manage Detail Design</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                    </div>
                </div>
            </a>
            
            <!-- Base Design Section -->
            <a href="{{ route('projects.design.base', $project) }}" 
               class="section-card block bg-white rounded-2xl shadow-xl overflow-hidden" 
               style="--card-color: #f59e0b;">
                <div class="p-8">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Base Design</h3>
                            <p class="text-gray-600">Foundation & base plans</p>
                        </div>
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                    
                    <div class="bg-gradient-to-br from-yellow-50 to-white rounded-xl p-6 border-2 border-yellow-100">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-yellow-600 mb-1">{{ $baseFoldersCount ?? 0 }}</div>
                                <p class="text-sm text-gray-600">Folders</p>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-yellow-600 mb-1">{{ $baseFilesCount ?? 0 }}</div>
                                <p class="text-sm text-gray-600">Files</p>
                            </div>
                    </div>
                </div>
                
                    <div class="mt-6 flex items-center justify-center text-yellow-600 font-semibold">
                        <span>Manage Base Design</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </a>
            
        </div>

        <!-- Folders & Files Management -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="p-8">
                <h2 class="text-xl font-bold text-purple-900 mb-6 text-left">Folders & Attachments Management</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Create Folder -->
                    <div class="bg-gradient-to-br from-purple-50 to-white rounded-xl p-6 border-2 border-purple-100">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-purple-900">Create New Folder</h3>
                        </div>
                        
                        <form action="{{ route('projects.design.create-folder', $project) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="folder_name" class="block text-sm font-medium text-purple-700 mb-2 text-left">Folder Name</label>
                                <input type="text" 
                                       id="folder_name" 
                                       name="folder_name" 
                                       required
                                       class="w-full px-4 py-3 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-left"
                                       placeholder="Example: Technical Specifications">
                                @error('folder_name')
                                    <p class="mt-1 text-sm text-red-600 text-left">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="folder_description" class="block text-sm font-medium text-purple-700 mb-2 text-left">Description (Optional)</label>
                                <textarea id="folder_description" 
                                          name="folder_description" 
                                          rows="2"
                                          class="w-full px-4 py-3 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-left"
                                          placeholder="Folder description..."></textarea>
                        </div>
                            
                            <button type="submit" 
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg shadow-sm transition duration-150 ease-in-out flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create Folder
                            </button>
                        </form>
                    </div>
                    
                    <!-- Upload Multiple Files -->
                    <div class="bg-gradient-to-br from-purple-50 to-white rounded-xl p-6 border-2 border-purple-100">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-purple-900">Upload Multiple Files</h3>
                        </div>
                        
                        <form action="{{ route('projects.design.upload-files', $project) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="folder_id" class="block text-sm font-medium text-purple-700 mb-2 text-left">Select Folder</label>
                                <select id="folder_id" 
                                        name="folder_id" 
                                        class="w-full px-4 py-3 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-left">
                                    <option value="">Main Folder</option>
                                    @foreach($folders as $folder)
                                    <option value="{{ $folder['name'] }}">{{ $folder['name'] }} ({{ $folder['file_count'] }} files)</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="files" class="block text-sm font-medium text-purple-700 mb-2 text-left">
                                    Select Files
                                    <span class="text-xs text-purple-500">(You can select multiple files)</span>
                                </label>
                                <div class="relative">
                                    <input type="file" 
                                           id="files" 
                                           name="files[]" 
                                           multiple
                                           required
                                           class="hidden"
                                           onchange="updateFileList(this)">
                                    <label for="files" 
                                           class="flex items-center justify-center w-full px-4 py-8 border-2 border-dashed border-purple-300 rounded-lg cursor-pointer hover:border-purple-500 hover:bg-purple-50 transition-all">
                                        <div class="text-center">
                                            <svg class="w-10 h-10 text-purple-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            <p class="text-sm text-purple-600">Click to select files</p>
                                            <p class="text-xs text-purple-500 mt-1">or drag and drop files here</p>
                                        </div>
                                    </label>
                                </div>
                                <div id="file-list" class="mt-3 space-y-2"></div>
                                @error('files')
                                    <p class="mt-1 text-sm text-red-600 text-left">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <button type="submit" 
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg shadow-sm transition duration-150 ease-in-out flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Upload Files
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Folders and Files List -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-purple-900">Folders and Files</h2>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-purple-500">
                            {{ count($folders) }} Folders | {{ count($files) }} Files
                        </span>
                    </div>
                </div>
                
                @if(count($folders) > 0 || count($files) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Folders -->
                        @foreach($folders as $folder)
                        <div class="bg-gradient-to-br from-purple-50 to-white border-2 border-purple-100 rounded-xl p-4 hover:shadow-lg transition-all duration-300 hover:scale-105 cursor-pointer">
                            <div class="flex items-start mb-3">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                    </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-purple-900 mb-1 truncate text-left">{{ $folder['name'] }}</h3>
                                    @if($folder['description'])
                                    <p class="text-xs text-purple-500 line-clamp-2 text-left">{{ $folder['description'] }}</p>
                                    @endif
                    </div>
                </div>
                
                            <div class="flex items-center justify-between text-sm pt-3 border-t border-purple-100">
                                <span class="text-purple-500">{{ $folder['file_count'] }} files</span>
                                <span class="text-purple-400 text-xs">{{ $folder['created_at'] }}</span>
                            </div>
                            
                            <div class="flex items-center gap-2 mt-3">
                                <a href="{{ route('projects.design.folder', ['project' => $project, 'folderName' => $folder['name']]) }}" class="flex-1 px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm transition-colors text-center">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                    </svg>
                                    Open
                                </a>
                                <button onclick="openRenameModal('{{ $folder['name'] }}')" class="flex-1 px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm transition-colors text-center">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Rename
                                </button>
                            </div>
                        </div>
                        @endforeach
                        
                        <!-- Files in main folder -->
                        @foreach($files as $file)
                        <div class="bg-gradient-to-br from-purple-50 to-white border-2 border-purple-100 rounded-xl p-4 hover:shadow-lg transition-all duration-300 hover:scale-105">
                            <div class="flex items-start mb-3">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-purple-900 mb-1 truncate text-left">{{ $file['name'] }}</h3>
                                    <p class="text-xs text-purple-500">{{ number_format($file['size'] / 1024, 2) }} KB</p>
                    </div>
                </div>
                
                            <div class="flex items-center justify-between text-xs pt-3 border-t border-purple-100">
                                <span class="text-purple-400">{{ $file['created_at'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                        </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-purple-900 mb-2">No Folders or Files</h3>
                        <p class="text-purple-600">Start by creating a new folder or uploading files</p>
                    </div>
                @endif
                    </div>
                </div>

        <!-- Design Submittals Section -->
        <div class="mt-12 bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-6">
                <h2 class="text-2xl font-bold text-white text-left">Design Submittals</h2>
            </div>
            
            <div class="p-8">
                <!-- Add Submittal Form -->
                <form action="{{ route('projects.design.submittal.store', $project) }}" method="POST" class="mb-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 text-left">FAMILY</label>
                            <input type="text" name="family" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Submittal Code</label>
                            <input type="text" name="description_code" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Rev</label>
                            <input type="text" name="rev" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Submittal</label>
                            <input type="text" name="description" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Last Status</label>
                            <input type="text" name="last_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Submitting Date</label>
                            <input type="date" name="submitting_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Reply Date</label>
                            <input type="date" name="reply_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Reply Status</label>
                            <select name="reply_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Select Status</option>
                                <option value="Total Submittals">Total Submittals</option>
                                <option value="Approved">Approved</option>
                                <option value="Approved With Note">Approved With Note</option>
                                <option value="Comments">Comments</option>
                                <option value="Under Review">Under Review</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Notes</label>
                            <input type="text" name="notes" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold transition-colors duration-200">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Submittal
                        </button>
                    </div>
                </form>
                
                <!-- Submittals Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-purple-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-purple-900 uppercase tracking-wider">FAMILY</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-purple-900 uppercase tracking-wider">Submittal Code</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-purple-900 uppercase tracking-wider">Rev</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-purple-900 uppercase tracking-wider">Submittal</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-purple-900 uppercase tracking-wider">Last Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-purple-900 uppercase tracking-wider">Submitting Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-purple-900 uppercase tracking-wider">Reply Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-purple-900 uppercase tracking-wider">Reply Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-purple-900 uppercase tracking-wider">Notes</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-purple-900 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($submittals as $submittal)
                            <tr class="hover:bg-purple-50 transition-colors">
                                <td class="px-4 py-3 text-sm text-gray-900 text-left">{{ $submittal->family }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 text-left">{{ $submittal->description_code }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 text-left">{{ $submittal->rev }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 text-left">{{ $submittal->description }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 text-left">{{ $submittal->last_status }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 text-left">{{ $submittal->submitting_date ? $submittal->submitting_date->format('Y-m-d') : '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 text-left">{{ $submittal->reply_date ? $submittal->reply_date->format('Y-m-d') : '-' }}</td>
                                <td class="px-4 py-3 text-sm text-left">
                                    @if($submittal->reply_status)
                                        @php
                                            $statusColors = [
                                                'Approved' => 'bg-green-100 text-green-800',
                                                'Approved With Note' => 'bg-yellow-100 text-yellow-800',
                                                'Comments' => 'bg-orange-100 text-orange-800',
                                                'Under Review' => 'bg-blue-100 text-blue-800',
                                                'Cancelled' => 'bg-red-100 text-red-800',
                                                'Total Submittals' => 'bg-purple-100 text-purple-800',
                                            ];
                                            $colorClass = $statusColors[$submittal->reply_status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                            {{ $submittal->reply_status }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 text-left">{{ $submittal->notes }}</td>
                                <td class="px-4 py-3 text-sm text-left">
                                    <div class="flex gap-2">
                                        <button onclick="openEditSubmittalModal({{ json_encode($submittal) }})" class="text-blue-600 hover:text-blue-800" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <form action="{{ route('projects.design.submittal.delete', [$project, $submittal]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <!-- <button type="submit" onclick="return confirm('Are you sure you want to delete this submittal?')" class="text-red-600 hover:text-red-800" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button> -->
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="px-4 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-lg font-medium">No Submittals Found</p>
                                        <p class="text-sm">Add your first submittal using the form above</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Rename Folder Modal -->
<div id="renameModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Rename Folder</h3>
                <button onclick="closeRenameModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="renameForm" action="{{ route('projects.design.rename-folder', $project) }}" method="POST">
                @csrf
                <input type="hidden" id="oldFolderName" name="old_name">
                
                <div class="mb-4">
                    <label for="newFolderName" class="block text-sm font-medium text-gray-700 mb-2 text-left">New Folder Name</label>
                    <input type="text" 
                           id="newFolderName" 
                           name="new_name" 
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                           placeholder="Enter new folder name">
                </div>
                
                <div class="flex gap-3 justify-end">
                    <button type="button" 
                            onclick="closeRenameModal()"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg text-sm transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm transition-colors">
                        Rename
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Submittal Modal -->
<div id="editSubmittalModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Edit Submittal</h3>
                <button onclick="closeEditSubmittalModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="editSubmittalForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 text-left">FAMILY</label>
                        <input type="text" name="family" id="edit_family" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Submittal Code</label>
                        <input type="text" name="description_code" id="edit_description_code" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Rev</label>
                        <input type="text" name="rev" id="edit_rev" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Submittal</label>
                        <input type="text" name="description" id="edit_description" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Last Status</label>
                        <input type="text" name="last_status" id="edit_last_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Submitting Date</label>
                        <input type="date" name="submitting_date" id="edit_submitting_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Reply Date</label>
                        <input type="date" name="reply_date" id="edit_reply_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Reply Status</label>
                        <select name="reply_status" id="edit_reply_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Select Status</option>
                            <option value="Total Submittals">Total Submittals</option>
                            <option value="Approved">Approved</option>
                            <option value="Approved With Note">Approved With Note</option>
                            <option value="Comments">Comments</option>
                            <option value="Under Review">Under Review</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Notes</label>
                        <input type="text" name="notes" id="edit_notes" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                </div>
                
                <div class="flex gap-3 justify-end">
                    <button type="button" 
                            onclick="closeEditSubmittalModal()"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg text-sm transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm transition-colors">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openRenameModal(folderName) {
    document.getElementById('oldFolderName').value = folderName;
    document.getElementById('newFolderName').value = folderName;
    document.getElementById('renameModal').classList.remove('hidden');
}

function closeRenameModal() {
    document.getElementById('renameModal').classList.add('hidden');
    document.getElementById('newFolderName').value = '';
}

function openEditSubmittalModal(submittal) {
    const projectId = {{ $project->id }};
    const form = document.getElementById('editSubmittalForm');
    form.action = `/projects/${projectId}/design/submittal/${submittal.id}`;
    
    document.getElementById('edit_family').value = submittal.family || '';
    document.getElementById('edit_description_code').value = submittal.description_code || '';
    document.getElementById('edit_rev').value = submittal.rev || '';
    document.getElementById('edit_description').value = submittal.description || '';
    document.getElementById('edit_last_status').value = submittal.last_status || '';
    document.getElementById('edit_submitting_date').value = submittal.submitting_date || '';
    document.getElementById('edit_reply_date').value = submittal.reply_date || '';
    document.getElementById('edit_reply_status').value = submittal.reply_status || '';
    document.getElementById('edit_notes').value = submittal.notes || '';
    
    document.getElementById('editSubmittalModal').classList.remove('hidden');
}

function closeEditSubmittalModal() {
    document.getElementById('editSubmittalModal').classList.add('hidden');
}

function updateFileList(input) {
    const fileList = document.getElementById('file-list');
    fileList.innerHTML = '';
    
    if (input.files.length > 0) {
        Array.from(input.files).forEach((file, index) => {
        const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-3 bg-purple-50 rounded-lg border border-purple-200';
        fileItem.innerHTML = `
                <div class="flex items-center flex-1">
                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-purple-900 text-left">${file.name}</p>
                        <p class="text-xs text-purple-500 text-left">${formatFileSize(file.size)}</p>
                </div>
                </div>
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
        `;
        fileList.appendChild(fileItem);
        });
        
        // Add summary
        const summary = document.createElement('div');
        summary.className = 'mt-3 p-3 bg-purple-50 rounded-lg border border-purple-200';
        summary.innerHTML = `
            <p class="text-sm text-purple-700 font-medium text-left">
                ${input.files.length} file${input.files.length !== 1 ? 's' : ''} selected
            </p>
        `;
        fileList.appendChild(summary);
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Drag and drop support
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.querySelector('label[for="files"]');
    const fileInput = document.getElementById('files');
    
    if (dropZone && fileInput) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight(e) {
            dropZone.classList.add('border-purple-500', 'bg-purple-50');
        }
        
        function unhighlight(e) {
            dropZone.classList.remove('border-purple-500', 'bg-purple-50');
        }
        
        dropZone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            updateFileList(fileInput);
        }
    }
});
</script>
@endpush
@endsection
