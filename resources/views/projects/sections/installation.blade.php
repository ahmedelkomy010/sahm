@extends('layouts.app')

@push('styles')
<style>
    .installation-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border: 2px solid #e2e8f0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .installation-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--card-color);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }
    
    .installation-card:hover::before {
        transform: scaleX(1);
    }
    
    .installation-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        border-color: var(--card-color);
    }
    
    .upload-zone {
        border: 2px dashed #cbd5e1;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .upload-zone:hover {
        border-color: #8b5cf6;
        background: #f3f4f6;
    }
    
    .upload-zone.dragover {
        border-color: #8b5cf6;
        background: #ede9fe;
    }
    
    .file-item {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .file-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .header-gradient {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
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
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-violet-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back Button -->
        <div class="mb-8 flex justify-end">
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
            <div class="relative z-10 p-8 text-left text-white">
                <div class="mb-6">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold mb-3">Installation Management</h1>
                    <p class="text-purple-100 text-lg">{{ $project->name }} - Contract: {{ $project->contract_number }}</p>
                </div>
            </div>
        </div>

        <!-- Installation Documents Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-12">
            
            <!-- Subcontractor -->
            <div class="installation-card rounded-2xl p-8" style="--card-color: #3b82f6;">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Subcontractor</h3>
                        <p class="text-gray-600 text-sm">Subcontractor installation documents</p>
                    </div>
                </div>
                
                <!-- Upload Zone -->
                <div class="upload-zone rounded-xl p-6 mb-6 text-center">
                    <input type="file" id="subcontractor-files" class="hidden" multiple accept=".pdf,.xlsx,.xls,.doc,.docx">
                    <label for="subcontractor-files" class="cursor-pointer">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">Drop files here or click to upload</p>
                        <p class="text-gray-400 text-sm mt-2">PDF, Excel, Word files</p>
                    </label>
                </div>
                
                <!-- File List -->
                <div class="space-y-3" id="subcontractor-files-list">
                    <p class="text-gray-500 text-sm text-center py-4">No files uploaded yet</p>
                </div>
            </div>
            
            <!-- In House Team -->
            <div class="installation-card rounded-2xl p-8" style="--card-color: #10b981;">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1">In House Team</h3>
                        <p class="text-gray-600 text-sm">Internal team installation documents</p>
                    </div>
                </div>
                
                <!-- Upload Zone -->
                <div class="upload-zone rounded-xl p-6 mb-6 text-center">
                    <input type="file" id="inhouse-files" class="hidden" multiple accept=".pdf,.xlsx,.xls,.doc,.docx">
                    <label for="inhouse-files" class="cursor-pointer">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">Drop files here or click to upload</p>
                        <p class="text-gray-400 text-sm mt-2">PDF, Excel, Word files</p>
                    </label>
                </div>
                
                <!-- File List -->
                <div class="space-y-3" id="inhouse-files-list">
                    <p class="text-gray-500 text-sm text-center py-4">No files uploaded yet</p>
                </div>
            </div>
            
        </div>
        
        <!-- Second Row -->
        <div class="grid grid-cols-1 gap-8 mb-12">
            
            <!-- General Design Files -->
            <div class="installation-card rounded-2xl p-8" style="--card-color: #8b5cf6;">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1">General Files</h3>
                        <p class="text-gray-600 text-sm">General installation & planning documents</p>
                    </div>
                </div>
                
                <!-- Upload Zone -->
                <div class="upload-zone rounded-xl p-6 mb-6 text-center">
                    <input type="file" id="general-design-files" class="hidden" multiple accept=".pdf,.xlsx,.xls,.doc,.docx,.dwg,.dxf">
                    <label for="general-design-files" class="cursor-pointer">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">Drop files here or click to upload</p>
                        <p class="text-gray-400 text-sm mt-2">PDF, Excel, Word, CAD files</p>
                    </label>
                </div>
                
                <!-- File List -->
                <div class="space-y-3" id="general-design-files-list">
                    <p class="text-gray-500 text-sm text-center py-4">No files uploaded yet</p>
                </div>
            </div>
            
        </div>

        <!-- Installation Progress Overview -->
        <div class="installation-card rounded-2xl p-8 mb-8" style="--card-color: #8b5cf6;">
            <div class="text-left mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Installation Progress Overview</h2>
                <p class="text-gray-600">Track the progress of different installation phases</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="text-left">
                    <div class="w-20 h-20 bg-blue-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Subcontractor</h3>
                    <div class="text-3xl font-bold text-blue-600 mb-2">25%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 25%"></div>
                    </div>
                </div>
                
                <div class="text-left">
                    <div class="w-20 h-20 bg-green-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">In House Team</h3>
                    <div class="text-3xl font-bold text-green-600 mb-2">60%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 60%"></div>
                    </div>
                </div>
                
                <div class="text-left">
                    <div class="w-20 h-20 bg-purple-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">General Design</h3>
                    <div class="text-3xl font-bold text-purple-600 mb-2">40%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: 40%"></div>
                    </div>
                </div>
                
                <div class="text-left">
                    <div class="w-20 h-20 bg-yellow-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Overall Progress</h3>
                    <div class="text-3xl font-bold text-yellow-600 mb-2">42%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-600 h-2 rounded-full" style="width: 42%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Overall Progress -->
            <div class="text-left">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Overall Installation Progress</h4>
                <div class="text-4xl font-bold text-purple-600 mb-4">42%</div>
                <div class="w-full bg-gray-200 rounded-full h-4 max-w-md">
                    <div class="bg-gradient-to-r from-purple-500 to-violet-600 h-4 rounded-full" style="width: 42%"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// File upload functionality for installation
document.addEventListener('DOMContentLoaded', function() {
    const projectId = {{ $project->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Map input IDs to section names
    const sectionMap = {
        'subcontractor-files': 'subcontractor',
        'inhouse-files': 'inhouse',
        'general-design-files': 'general-design'
    };
    
    // Load existing files on page load
    Object.keys(sectionMap).forEach(inputId => {
        loadFiles(sectionMap[inputId], inputId);
    });
    
    // Handle file uploads for all three sections
    const fileInputs = ['subcontractor-files', 'inhouse-files', 'general-design-files'];
    
    fileInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        const uploadZone = input.parentElement;
        
        // Click to upload
        uploadZone.addEventListener('click', () => {
            input.click();
        });
        
        // Drag and drop
        uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadZone.classList.add('dragover');
        });
        
        uploadZone.addEventListener('dragleave', () => {
            uploadZone.classList.remove('dragover');
        });
        
        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            handleFiles(files, inputId);
        });
        
        // File selection
        input.addEventListener('change', (e) => {
            handleFiles(e.target.files, inputId);
        });
    });
    
    function handleFiles(files, inputId) {
        console.log('handleFiles called', {inputId, filesCount: files.length});
        
        const section = sectionMap[inputId];
        const formData = new FormData();
        
        console.log('Section mapped to:', section);
        
        Array.from(files).forEach(file => {
            console.log('Processing file:', file.name, 'Type:', file.type, 'Size:', file.size);
            if (isValidFile(file, inputId)) {
                formData.append('files[]', file);
                console.log('File added to FormData:', file.name);
            } else {
                console.log('Invalid file type:', file.name);
                showNotification('Invalid file type: ' + file.name + '. Please upload valid files.', 'error');
                return;
            }
        });
        
        if (formData.get('files[]') === null) {
            console.log('No valid files found');
            showNotification('Please select valid files to upload.', 'error');
            return;
        }
        
        formData.append('section', section);
        formData.append('_token', csrfToken);
        
        console.log('FormData prepared for upload:', {
            section: formData.get('section'),
            token: formData.get('_token') ? 'present' : 'missing',
            filesCount: formData.getAll('files[]').length
        });
        
        // Show loading state
        const uploadZone = document.getElementById(inputId).parentElement;
        const originalContent = uploadZone.innerHTML;
        uploadZone.innerHTML = `
            <div class="text-center">
                <svg class="animate-spin w-8 h-8 text-purple-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-600 font-medium">Uploading files...</p>
            </div>
        `;
        
        // Upload files
        fetch(`/projects/${projectId}/installation/upload`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Restore upload zone
                uploadZone.innerHTML = originalContent;
                
                // Reload files for this section
                loadFiles(section, inputId);
                
                // Show success message
                showNotification('Files uploaded successfully!', 'success');
            } else {
                throw new Error(data.message || 'Upload failed');
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            uploadZone.innerHTML = originalContent;
            showNotification('Upload failed: ' + error.message, 'error');
        });
    }
    
    function loadFiles(section, inputId) {
        fetch(`/projects/${projectId}/installation/${section}/files`)
            .then(response => response.json())
            .then(data => {
                const fileList = document.getElementById(inputId + '-list');
                fileList.innerHTML = '';
                
                if (data.files && data.files.length > 0) {
                    data.files.forEach(file => {
                        addFileToList(file, fileList, section);
                    });
                } else {
                    // Clear the sample data if no real files exist
                    fileList.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">No files uploaded yet</p>';
                }
            })
            .catch(error => {
                console.error('Error loading files:', error);
            });
    }
    
    function addFileToList(file, fileList, section) {
        const fileItem = document.createElement('div');
        fileItem.className = 'file-item rounded-lg p-3 flex items-center justify-between';
        fileItem.setAttribute('data-filename', file.filename);
        
        const fileIcon = getFileIcon(file.original_name);
        const iconColor = getIconColor(file.original_name);
        
        fileItem.innerHTML = `
            <div class="flex items-center">
                <div class="w-10 h-10 ${iconColor} rounded-lg flex items-center justify-center mr-3">
                    ${fileIcon}
                </div>
                <div>
                    <p class="font-medium text-gray-900 text-sm">${file.original_name}</p>
                    <p class="text-gray-500 text-xs">${file.size_formatted} • ${file.last_modified}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button class="text-blue-600 hover:text-blue-800 p-1" onclick="viewFile('${file.view_url}')" title="View">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
                <button class="text-green-600 hover:text-green-800 p-1" onclick="downloadFile('${file.download_url}')" title="Download">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </button>
                <button class="text-red-600 hover:text-red-800 p-1" onclick="deleteFile(this, '${section}', '${file.filename}')" title="Delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        `;
        
        fileList.appendChild(fileItem);
        
        // Add animation
        setTimeout(() => {
            fileItem.style.opacity = '0';
            fileItem.style.transform = 'translateY(20px)';
            fileItem.style.transition = 'all 0.3s ease';
            
            setTimeout(() => {
                fileItem.style.opacity = '1';
                fileItem.style.transform = 'translateY(0)';
            }, 10);
        }, 10);
    }
    
    function getFileIcon(filename) {
        const extension = filename.split('.').pop().toLowerCase();
        
        switch(extension) {
            case 'pdf':
                return `<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>`;
            case 'xlsx':
            case 'xls':
                return `<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>`;
            case 'doc':
            case 'docx':
                return `<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>`;
            case 'dwg':
            case 'dxf':
                return `<svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM7 21h16a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 002 2z"></path>
                </svg>`;
            default:
                return `<svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>`;
        }
    }
    
    function getIconColor(filename) {
        const extension = filename.split('.').pop().toLowerCase();
        
        switch(extension) {
            case 'pdf': return 'bg-red-100';
            case 'xlsx':
            case 'xls': return 'bg-green-100';
            case 'doc':
            case 'docx': return 'bg-blue-100';
            case 'dwg':
            case 'dxf': return 'bg-purple-100';
            default: return 'bg-gray-100';
        }
    }
    
    function isValidFile(file, inputId) {
        const fileExtension = file.name.split('.').pop().toLowerCase();
        
        if (inputId === 'general-design-files') {
            const validExtensions = ['pdf', 'xlsx', 'xls', 'doc', 'docx', 'dwg', 'dxf'];
            return validExtensions.includes(fileExtension);
        } else {
            const validExtensions = ['pdf', 'xlsx', 'xls', 'doc', 'docx'];
            return validExtensions.includes(fileExtension);
        }
    }
});

// File action functions
function viewFile(viewUrl) {
    window.open(viewUrl, '_blank');
}

function downloadFile(downloadUrl) {
    window.location.href = downloadUrl;
}

function deleteFile(button, section, filename) {
    if (confirm('Are you sure you want to delete this file?')) {
        const projectId = {{ $project->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/projects/${projectId}/installation/${section}/${filename}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const fileItem = button.closest('.file-item');
                fileItem.style.transition = 'all 0.3s ease';
                fileItem.style.opacity = '0';
                fileItem.style.transform = 'translateX(100px)';
                
                setTimeout(() => {
                    fileItem.remove();
                }, 300);
                
                showNotification('File deleted successfully!', 'success');
            } else {
                showNotification('Failed to delete file: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            showNotification('Failed to delete file', 'error');
        });
    }
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection