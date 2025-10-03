@extends('layouts.app')

@push('styles')
<style>
    .header-gradient {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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
            <a href="{{ route('projects.study', $project) }}" 
               class="inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-yellow-200 text-yellow-700 rounded-xl font-semibold shadow-lg hover:bg-white hover:shadow-xl transition-all duration-300 hover:transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Study
            </a>
        </div>
        
        <!-- Header -->
        <div class="header-gradient rounded-3xl shadow-2xl overflow-hidden mb-12 relative">
            <div class="relative z-10 p-8 text-center text-white">
                <div class="mb-6">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold mb-3">Commercial Files</h1>
                    <p class="text-yellow-100 text-lg">{{ $project->name }}</p>
                    <p class="text-yellow-100 text-sm mt-2">Contract: {{ $project->contract_number }}</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-yellow-100 text-sm mb-1">Project Type</p>
                        <p class="font-semibold text-lg">{{ $project->getProjectTypeText() }}</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-yellow-100 text-sm mb-1">Location</p>
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

        <!-- Create Folder & Upload Commercial Files -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="p-8">
                <h2 class="text-xl font-bold text-yellow-900 mb-6 text-left">Folders & Files Management</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Create Folder -->
                    <div class="bg-gradient-to-br from-yellow-50 to-white rounded-xl p-6 border-2 border-yellow-100">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-yellow-900">Create New Folder</h3>
                        </div>
                        
                        <form action="{{ route('projects.study.commercial-files.create-folder', $project) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="folder_name" class="block text-sm font-medium text-yellow-700 mb-2 text-left">Folder Name</label>
                                <input type="text" 
                                       id="folder_name" 
                                       name="folder_name" 
                                       required
                                       class="w-full px-4 py-3 border border-yellow-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-left"
                                       placeholder="Example: Technical Specifications">
                                @error('folder_name')
                                    <p class="mt-1 text-sm text-red-600 text-left">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="folder_description" class="block text-sm font-medium text-yellow-700 mb-2 text-left">Description (Optional)</label>
                                <textarea id="folder_description" 
                                          name="folder_description" 
                                          rows="2"
                                          class="w-full px-4 py-3 border border-yellow-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-left"
                                          placeholder="Folder description..."></textarea>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-3 px-6 rounded-lg shadow-sm transition duration-150 ease-in-out flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create Folder
                            </button>
                        </form>
                    </div>
                    
                    <!-- Upload Multiple Files -->
                    <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl p-6 border-2 border-blue-100">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-yellow-900">Upload Multiple Files</h3>
                        </div>
                        
                        <form action="{{ route('projects.study.commercial-files.upload-files', $project) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="folder_id" class="block text-sm font-medium text-yellow-700 mb-2 text-left">Select Folder</label>
                                <select id="folder_id" 
                                        name="folder_id" 
                                        class="w-full px-4 py-3 border border-yellow-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-left">
                                    <option value="">Main Folder</option>
                                    @foreach($folders as $folder)
                                    <option value="{{ $folder['name'] }}">{{ $folder['name'] }} ({{ $folder['file_count'] }} Files)</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="files" class="block text-sm font-medium text-yellow-700 mb-2 text-left">
                                    Choose Files
                                    <span class="text-xs text-yellow-500">(You can select multiple files)</span>
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
                                           class="flex items-center justify-center w-full px-4 py-8 border-2 border-dashed border-yellow-300 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all">
                                        <div class="text-center">
                                            <svg class="w-10 h-10 text-yellow-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            <p class="text-sm text-yellow-600">Click to select files</p>
                                            <p class="text-xs text-yellow-500 mt-1">Or drag and drop files here</p>
                                        </div>
                                    </label>
                    </div>
                                <div id="file-list" class="mt-3 space-y-2"></div>
                                @error('files')
                                    <p class="mt-1 text-sm text-red-600 text-left">{{ $message }}</p>
                                @enderror
                    </div>
                            
                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-sm transition duration-150 ease-in-out flex items-center justify-center">
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
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-yellow-900">Folders & Files</h2>
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <span class="text-sm text-yellow-500">
                            {{ count($folders) }} Folders | {{ count($files) }} Files
                        </span>
                    </div>
                </div>
                
                @if(count($folders) > 0 || count($files) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Folders -->
                        @foreach($folders as $folder)
                        <div class="bg-gradient-to-br from-yellow-50 to-white border-2 border-yellow-100 rounded-xl p-4 hover:shadow-lg transition-all duration-300 hover:scale-105 cursor-pointer">
                            <div class="flex items-start mb-3">
                                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-yellow-900 mb-1 truncate text-left">{{ $folder['name'] }}</h3>
                                    @if($folder['description'])
                                    <p class="text-xs text-yellow-500 line-clamp-2 text-left">{{ $folder['description'] }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between text-sm pt-3 border-t border-yellow-100">
                                <span class="text-yellow-500">{{ $folder['file_count'] }} Files</span>
                                <span class="text-yellow-400 text-xs">{{ $folder['created_at'] }}</span>
                            </div>
                            
                            <div class="flex items-center gap-2 mt-3">
                                <a href="{{ route('projects.study.commercial-files.folder', ['project' => $project, 'folderName' => $folder['name']]) }}" class="flex-1 px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg text-sm transition-colors text-center">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                    </svg>
                                    Open Folder
                                </a>
                            </div>
                        </div>
                        @endforeach
                        
                        <!-- الملفات في Main Folder -->
                        @foreach($files as $file)
                        <div class="bg-gradient-to-br from-blue-50 to-white border-2 border-blue-100 rounded-xl p-4 hover:shadow-lg transition-all duration-300 hover:scale-105">
                            <div class="flex items-start mb-3">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-yellow-900 mb-1 truncate text-left">{{ $file['name'] }}</h3>
                                    <p class="text-xs text-yellow-500">{{ number_format($file['size'] / 1024, 2) }} KB</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between text-xs pt-3 border-t border-blue-100">
                                <span class="text-yellow-400">{{ $file['created_at'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-yellow-900 mb-2">No Folders or Files

                        <p class="text-yellow-600">Start by creating a new folder or uploading files
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
function updateFileList(input) {
    const fileList = document.getElementById('file-list');
    fileList.innerHTML = '';
    
    if (input.files.length > 0) {
        Array.from(input.files).forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200';
            fileItem.innerHTML = `
                <div class="flex items-center flex-1">
                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-yellow-900 text-left">${file.name}</p>
                        <p class="text-xs text-yellow-500 text-left">${formatFileSize(file.size)}</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            `;
            fileList.appendChild(fileItem);
        });
        
        // إضافة ملخص
        const summary = document.createElement('div');
        summary.className = 'mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200';
        summary.innerHTML = `
            <p class="text-sm text-blue-700 font-medium text-left">
                تم اختيار ${input.files.length} ${input.files.length === 1 ? 'ملف' : 'ملفات'}
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
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        }
        
        function unhighlight(e) {
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
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
