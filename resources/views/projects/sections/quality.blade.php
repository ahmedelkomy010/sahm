@extends('layouts.app')

@push('styles')
<style>
    .quality-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border: 2px solid #e2e8f0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .quality-card::before {
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
    
    .quality-card:hover::before {
        transform: scaleX(1);
    }
    
    .quality-card:hover {
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
        border-color: #10b981;
        background: #f0fdf4;
    }
    
    .upload-zone.dragover {
        border-color: #10b981;
        background: #dcfce7;
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
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-100 py-12">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold mb-3">Quality Management</h1>
                    <p class="text-green-100 text-lg">{{ $project->name }} - Contract: {{ $project->contract_number }}</p>
                </div>
            </div>
        </div>

        <!-- Quality Documents Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-12">
            
            <!-- RFT (Request for Test) -->
            <div class="quality-card rounded-2xl p-8" style="--card-color: #3b82f6;">
                <div class="flex items-center mb-6 justify-end">
                    <div class="text-right mr-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">RFT (Request for Test)</h3>
                        <p class="text-gray-600 text-sm">Request for testing documents & procedures</p>
                    </div>
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Upload Zone -->
                <div class="upload-zone rounded-xl p-6 mb-6 text-center">
                    <input type="file" id="rft-files" class="hidden" multiple accept=".pdf,.xlsx,.xls,.doc,.docx">
                    <label for="rft-files" class="cursor-pointer">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">Drop files here or click to upload</p>
                        <p class="text-gray-400 text-sm mt-2">PDF, Excel, Word files</p>
                    </label>
                </div>
                
                <!-- File List -->
                <div class="space-y-3" id="rft-files-list">
                    <p class="text-gray-500 text-sm text-center py-4">No files uploaded yet</p>
                </div>
            </div>
            
            <!-- NCR (Non-Conformance Report) -->
            <div class="quality-card rounded-2xl p-8" style="--card-color: #ef4444;">
                <div class="flex items-center mb-6 justify-end">
                    <div class="text-right mr-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">NCR (Non-Conformance Report)</h3>
                        <p class="text-gray-600 text-sm">Non-conformance reports & corrective actions</p>
                    </div>
                    <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Upload Zone -->
                <div class="upload-zone rounded-xl p-6 mb-6 text-center">
                    <input type="file" id="ncr-files" class="hidden" multiple accept=".pdf,.xlsx,.xls,.doc,.docx">
                    <label for="ncr-files" class="cursor-pointer">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">Drop files here or click to upload</p>
                        <p class="text-gray-400 text-sm mt-2">PDF, Excel, Word files</p>
                    </label>
                </div>
                
                <!-- File List -->
                <div class="space-y-3" id="ncr-files-list">
                    <p class="text-gray-500 text-sm text-center py-4">No files uploaded yet</p>
                </div>
            </div>
            
            <!-- Quality Plan -->
            <div class="quality-card rounded-2xl p-8" style="--card-color: #10b981;">
                <div class="flex items-center mb-6 justify-end">
                    <div class="text-right mr-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Quality Plan</h3>
                        <p class="text-gray-600 text-sm">Quality planning & control procedures</p>
                    </div>
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Upload Zone -->
                <div class="upload-zone rounded-xl p-6 mb-6 text-center">
                    <input type="file" id="quality-plan-files" class="hidden" multiple accept=".pdf,.xlsx,.xls,.doc,.docx">
                    <label for="quality-plan-files" class="cursor-pointer">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">Drop files here or click to upload</p>
                        <p class="text-gray-400 text-sm mt-2">PDF, Excel, Word files</p>
                    </label>
                </div>
                
                <!-- File List -->
                <div class="space-y-3" id="quality-plan-files-list">
                    <p class="text-gray-500 text-sm text-center py-4">No files uploaded yet</p>
                </div>
            </div>
            
            <!-- Time Line -->
            <div class="quality-card rounded-2xl p-8" style="--card-color: #f59e0b;">
                <div class="flex items-center mb-6 justify-end">
                    <div class="text-right mr-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Time Line</h3>
                        <p class="text-gray-600 text-sm">Quality timeline & milestone schedules</p>
                    </div>
                    <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Upload Zone -->
                <div class="upload-zone rounded-xl p-6 mb-6 text-center">
                    <input type="file" id="timeline-files" class="hidden" multiple accept=".pdf,.xlsx,.xls,.doc,.docx">
                    <label for="timeline-files" class="cursor-pointer">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">Drop files here or click to upload</p>
                        <p class="text-gray-400 text-sm mt-2">PDF, Excel, Word files</p>
                    </label>
                </div>
                
                <!-- File List -->
                <div class="space-y-3" id="timeline-files-list">
                    <p class="text-gray-500 text-sm text-center py-4">No files uploaded yet</p>
                </div>
            </div>
            
            <!-- Internal Audit -->
            <div class="quality-card rounded-2xl p-8" style="--card-color: #8b5cf6;">
                <div class="flex items-center mb-6 justify-end">
                    <div class="text-right mr-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Internal Audit</h3>
                        <p class="text-gray-600 text-sm">Internal audit reports & findings</p>
                    </div>
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Upload Zone -->
                <div class="upload-zone rounded-xl p-6 mb-6 text-center">
                    <input type="file" id="internal-audit-files" class="hidden" multiple accept=".pdf,.xlsx,.xls,.doc,.docx">
                    <label for="internal-audit-files" class="cursor-pointer">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">Drop files here or click to upload</p>
                        <p class="text-gray-400 text-sm mt-2">PDF, Excel, Word files</p>
                    </label>
                </div>
                
                <!-- File List -->
                <div class="space-y-3" id="internal-audit-files-list">
                    <p class="text-gray-500 text-sm text-center py-4">No files uploaded yet</p>
                </div>
            </div>
            
            <!-- General Design Files -->
            <div class="quality-card rounded-2xl p-8" style="--card-color: #6366f1;">
                <div class="flex items-center mb-6 justify-end">
                    <div class="text-right mr-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">General Files</h3>
                        <p class="text-gray-600 text-sm">General  & quality</p>
                    </div>
                    <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        <!-- Quality Progress Overview -->
        <div class="quality-card rounded-2xl p-8 mb-8" style="--card-color: #10b981;">
            <div class="text-left mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Quality Progress Overview</h2>
                <p class="text-gray-600">Track the progress of different quality phases</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="text-left">
                    <div class="w-20 h-20 bg-blue-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Testing & RFT</h3>
                    <div class="text-3xl font-bold text-blue-600 mb-2">75%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
                    </div>
                </div>
                
                <div class="text-left">
                    <div class="w-20 h-20 bg-green-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Quality Planning</h3>
                    <div class="text-3xl font-bold text-green-600 mb-2">90%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 90%"></div>
                    </div>
                </div>
                
                <div class="text-left">
                    <div class="w-20 h-20 bg-purple-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Auditing</h3>
                    <div class="text-3xl font-bold text-purple-600 mb-2">60%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: 60%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Overall Progress -->
            <div class="text-left">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Overall Quality Progress</h4>
                <div class="text-4xl font-bold text-green-600 mb-4">75%</div>
                <div class="w-full bg-gray-200 rounded-full h-4 max-w-md">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-4 rounded-full" style="width: 75%"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// File upload functionality for quality
document.addEventListener('DOMContentLoaded', function() {
    const projectId = {{ $project->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Map input IDs to section names
    const sectionMap = {
        'rft-files': 'rft',
        'ncr-files': 'ncr',
        'quality-plan-files': 'quality-plan',
        'timeline-files': 'timeline',
        'internal-audit-files': 'internal-audit',
        'general-design-files': 'general-design'
    };
    
    // Load existing files on page load
    Object.keys(sectionMap).forEach(inputId => {
        loadFiles(sectionMap[inputId], inputId);
    });
    
    // Handle file uploads for all six sections
    const fileInputs = ['rft-files', 'ncr-files', 'quality-plan-files', 'timeline-files', 'internal-audit-files', 'general-design-files'];
    
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
        
        console.log('Uploading to URL:', `/projects/${projectId}/quality/upload`);
        
        // Show loading state
        const uploadZone = document.getElementById(inputId).parentElement;
        const originalContent = uploadZone.innerHTML;
        uploadZone.innerHTML = `
            <div class="text-center">
                <svg class="animate-spin w-8 h-8 text-green-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-600 font-medium">Uploading files...</p>
            </div>
        `;
        
        // Upload files
        fetch(`/projects/${projectId}/quality/upload`, {
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
        fetch(`/projects/${projectId}/quality/${section}/files`)
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
    
    fetch(`/projects/${projectId}/quality/${section}/${filename}`, {
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