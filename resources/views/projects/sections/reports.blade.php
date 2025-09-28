@extends('layouts.app')

@push('styles')
<style>
    .reports-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border: 2px solid #e2e8f0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .reports-card::before {
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
    
    .reports-card:hover::before {
        transform: scaleX(1);
    }
    
    .reports-card:hover {
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
        border-color: #0ea5e9;
        background: #f0f9ff;
    }
    
    .upload-zone.dragover {
        border-color: #0ea5e9;
        background: #e0f2fe;
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
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
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
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-sky-50 to-blue-100 py-12">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold mb-3">Reports Management</h1>
                    <p class="text-sky-100 text-lg">{{ $project->name }} - Contract: {{ $project->contract_number }}</p>
                </div>
            </div>
        </div>

        <!-- Reports Documents Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-12">
            
            <!-- Weekly Reports -->
            <div class="reports-card rounded-2xl p-8" style="--card-color: #10b981;">
                <div class="flex items-center mb-6 justify-end">
                    <div class="text-right mr-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Weekly Reports</h3>
                        <p class="text-gray-600 text-sm">Weekly progress & status reports</p>
                    </div>
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Upload Zone -->
                <div class="upload-zone rounded-xl p-6 mb-6 text-center">
                    <input type="file" id="weekly-files" class="hidden" multiple accept=".pdf,.xlsx,.xls,.doc,.docx">
                    <label for="weekly-files" class="cursor-pointer">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">Drop files here or click to upload</p>
                        <p class="text-gray-400 text-sm mt-2">PDF, Excel, Word files</p>
                    </label>
                </div>
                
                <!-- File List -->
                <div class="space-y-3" id="weekly-files-list">
                    <p class="text-gray-500 text-sm text-center py-4">No files uploaded yet</p>
                </div>
            </div>
            
            <!-- Bi-weekly Reports -->
            <div class="reports-card rounded-2xl p-8" style="--card-color: #f59e0b;">
                <div class="flex items-center mb-6 justify-end">
                    <div class="text-right mr-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Bi-weekly Reports</h3>
                        <p class="text-gray-600 text-sm">Bi-weekly progress & milestone reports</p>
                    </div>
                    <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Upload Zone -->
                <div class="upload-zone rounded-xl p-6 mb-6 text-center">
                    <input type="file" id="biweekly-files" class="hidden" multiple accept=".pdf,.xlsx,.xls,.doc,.docx">
                    <label for="biweekly-files" class="cursor-pointer">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">Drop files here or click to upload</p>
                        <p class="text-gray-400 text-sm mt-2">PDF, Excel, Word files</p>
                    </label>
                </div>
                
                <!-- File List -->
                <div class="space-y-3" id="biweekly-files-list">
                    <p class="text-gray-500 text-sm text-center py-4">No files uploaded yet</p>
                </div>
            </div>
            
            <!-- Monthly Reports -->
            <div class="reports-card rounded-2xl p-8" style="--card-color: #8b5cf6;">
                <div class="flex items-center mb-6 justify-end">
                    <div class="text-right mr-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Monthly Reports</h3>
                        <p class="text-gray-600 text-sm">Monthly summary & comprehensive reports</p>
                    </div>
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Upload Zone -->
                <div class="upload-zone rounded-xl p-6 mb-6 text-center">
                    <input type="file" id="monthly-files" class="hidden" multiple accept=".pdf,.xlsx,.xls,.doc,.docx">
                    <label for="monthly-files" class="cursor-pointer">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">Drop files here or click to upload</p>
                        <p class="text-gray-400 text-sm mt-2">PDF, Excel, Word files</p>
                    </label>
                </div>
                
                <!-- File List -->
                <div class="space-y-3" id="monthly-files-list">
                    <p class="text-gray-500 text-sm text-center py-4">No files uploaded yet</p>
                </div>
            </div>
            
            <!-- General Design Files -->
            <div class="reports-card rounded-2xl p-8" style="--card-color: #ef4444;">
                <div class="flex items-center mb-6 justify-end">
                    <div class="text-right mr-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">General Design Files</h3>
                        <p class="text-gray-600 text-sm">General design documents & specifications</p>
                    </div>
                    <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
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

        <!-- Reports Progress Overview -->
        <div class="reports-card rounded-2xl p-8 mb-8" style="--card-color: #0ea5e9;">
            <div class="text-left mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Reports Progress Overview</h2>
                <p class="text-gray-600">Track the progress of different reporting phases</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="text-left">
                    <div class="w-20 h-20 bg-green-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Weekly Reports</h3>
                    <div class="text-3xl font-bold text-green-600 mb-2">85%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 85%"></div>
                    </div>
                </div>
                
                <div class="text-left">
                    <div class="w-20 h-20 bg-yellow-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Bi-weekly Reports</h3>
                    <div class="text-3xl font-bold text-yellow-600 mb-2">70%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-600 h-2 rounded-full" style="width: 70%"></div>
                    </div>
                </div>
                
                <div class="text-left">
                    <div class="w-20 h-20 bg-purple-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Monthly Reports</h3>
                    <div class="text-3xl font-bold text-purple-600 mb-2">65%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: 65%"></div>
                    </div>
                </div>
                
                <div class="text-left">
                    <div class="w-20 h-20 bg-red-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Design Files</h3>
                    <div class="text-3xl font-bold text-red-600 mb-2">90%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-red-600 h-2 rounded-full" style="width: 90%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Overall Progress -->
            <div class="text-left">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Overall Reports Progress</h4>
                <div class="text-4xl font-bold text-sky-600 mb-4">78%</div>
                <div class="w-full bg-gray-200 rounded-full h-4 max-w-md">
                    <div class="bg-gradient-to-r from-sky-500 to-blue-600 h-4 rounded-full" style="width: 78%"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// File upload functionality for reports
document.addEventListener('DOMContentLoaded', function() {
    const projectId = {{ $project->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Map input IDs to section names
    const sectionMap = {
        'weekly-files': 'weekly',
        'biweekly-files': 'biweekly',
        'monthly-files': 'monthly',
        'general-design-files': 'general-design'
    };
    
    // Load existing files on page load
    Object.keys(sectionMap).forEach(inputId => {
        loadFiles(sectionMap[inputId], inputId);
    });
    
    // Handle file uploads for all four sections
    const fileInputs = ['weekly-files', 'biweekly-files', 'monthly-files', 'general-design-files'];
    
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
        
        console.log('Uploading to URL:', `/projects/${projectId}/reports/upload`);
        
        // Show loading state
        const uploadZone = document.getElementById(inputId).parentElement;
        const originalContent = uploadZone.innerHTML;
        uploadZone.innerHTML = `
            <div class="text-center">
                <svg class="animate-spin w-8 h-8 text-sky-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-600 font-medium">Uploading files...</p>
            </div>
        `;
        
        // Upload files
        fetch(`/projects/${projectId}/reports/upload`, {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': csrfToken }
        })
        .then(response => {
            console.log('Upload response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Upload response:', data);
            if (data.success) {
                uploadZone.innerHTML = originalContent;
                loadFiles(section, inputId);
                showNotification('Files uploaded successfully!', 'success');
                
                // Clear file input
                document.getElementById(inputId).value = '';
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
        console.log('Loading files for section:', section, 'inputId:', inputId);
        fetch(`/projects/${projectId}/reports/${section}/files`)
            .then(response => response.json())
            .then(data => {
                const fileList = document.getElementById(inputId + '-list');
                fileList.innerHTML = '';
                if (data.files && data.files.length > 0) {
                    data.files.forEach(file => {
                        addFileToList(file, fileList, section);
                    });
                } else {
                    fileList.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">No files uploaded yet</p>';
                }
            })
            .catch(error => { 
                console.error('Error loading files:', error); 
                const fileList = document.getElementById(inputId + '-list');
                fileList.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">Error loading files</p>';
            });
    }
    
    function addFileToList(file, fileList, section) {
        const fileItem = document.createElement('div');
        fileItem.className = 'file-item rounded-lg p-3 flex items-center justify-between';
        fileItem.setAttribute('data-filename', file.filename);
        fileItem.style.opacity = '0';
        fileItem.style.transform = 'translateY(10px)';
        fileItem.style.transition = 'all 0.3s ease';
        
        const fileIcon = getFileIcon(file.original_name);
        const iconColor = getIconColor(file.original_name);
        
        console.log('Adding file to list:', file.original_name, 'Section:', section);
        
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
        
        // Animate in
        setTimeout(() => {
            fileItem.style.opacity = '1';
            fileItem.style.transform = 'translateY(0)';
        }, 10);
    }
    
    function getFileIcon(filename) {
        const extension = filename.split('.').pop().toLowerCase();
        
        switch (extension) {
            case 'pdf':
                return `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>`;
            case 'xlsx':
            case 'xls':
                return `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>`;
            case 'doc':
            case 'docx':
                return `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>`;
            case 'dwg':
            case 'dxf':
                return `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>`;
            default:
                return `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>`;
        }
    }
    
    function getIconColor(filename) {
        const extension = filename.split('.').pop().toLowerCase();
        
        switch (extension) {
            case 'pdf':
                return 'bg-red-100 text-red-600';
            case 'xlsx':
            case 'xls':
                return 'bg-green-100 text-green-600';
            case 'doc':
            case 'docx':
                return 'bg-blue-100 text-blue-600';
            case 'dwg':
            case 'dxf':
                return 'bg-purple-100 text-purple-600';
            default:
                return 'bg-gray-100 text-gray-600';
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

function viewFile(viewUrl) {
    window.open(viewUrl, '_blank');
}

function downloadFile(downloadUrl) {
    window.location.href = downloadUrl;
}

function deleteFile(button, section, filename) {
    if (!confirm('Are you sure you want to delete this file?')) {
        return;
    }
    
    const projectId = {{ $project->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/projects/${projectId}/reports/${section}/${filename}`, {
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
            fileItem.style.opacity = '0';
            fileItem.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                fileItem.remove();
            }, 300);
            showNotification('File deleted successfully!', 'success');
        } else {
            throw new Error(data.message || 'Delete failed');
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        showNotification('Delete failed: ' + error.message, 'error');
    });
}

function showNotification(message, type) {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(n => n.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.style.transform = 'translateX(100%)';
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    // Animate out and remove
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 3000);
}
</script>
@endsection