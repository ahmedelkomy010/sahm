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
    
    /* تعديل مخصص للنماذج */
    .ltr-page .form-input,
    .ltr-page .form-select,
    .ltr-page .form-textarea {
        text-align: left;
    }
    
    .ltr-page .form-label {
        text-align: left;
    }
    
    /* تعديل أيقونات الأسهم للتنسيق LTR */
    .ltr-page .back-arrow {
        transform: scaleX(-1);
    }
    .form-container {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        position: relative;
        overflow: hidden;
    }
    
    .form-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }
    
    .form-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        opacity: 0.3;
    }
    
    .form-field {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: rgba(255, 255, 255, 0.9);
    }
    
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background: white;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        font-size: 14px;
        letter-spacing: 0.025em;
    }
    
    .date-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .date-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--card-color);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .date-card:hover::before {
        transform: scaleX(1);
    }
    
    .date-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
        border-color: var(--card-color);
    }
    
    .submit-button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 16px 32px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .submit-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }
    
    .submit-button:active {
        transform: translateY(0);
    }
    
    .submit-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    .back-button {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        color: #667eea;
        padding: 12px 24px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
    }
    
    .back-button:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        text-decoration: none;
    }
    
    .warning-box {
        background: linear-gradient(145deg, #fef3cd 0%, #fde68a 100%);
        border: 2px solid #f59e0b;
        border-radius: 16px;
        padding: 20px;
        margin: 24px 0;
        position: relative;
        overflow: hidden;
    }
    
    .warning-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: #f59e0b;
    }
    
    .checkbox-wrapper {
        display: flex;
        align-items: center;
        margin-top: 16px;
        padding: 16px;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .checkbox-wrapper:hover {
        background: rgba(255, 255, 255, 0.9);
        border-color: #f59e0b;
    }
    
    .checkbox-wrapper input[type="checkbox"] {
        width: 20px;
        height: 20px;
        margin-right: 12px;
        accent-color: #f59e0b;
    }
    
    .checkbox-wrapper label {
        font-weight: 500;
        color: #92400e;
        cursor: pointer;
        flex: 1;
    }
    
    .page-background {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        position: relative;
    }
    
    .page-background::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpolygon points='50 0 60 40 100 50 60 60 50 100 40 60 0 50 40 40'/%3E%3C/g%3E%3C/svg%3E") repeat;
        opacity: 0.6;
    }
</style>
@endpush

@section('content')
<div class="page-background ltr-page">
    <div class="container mx-auto px-4 py-12 relative z-10">
        <div class="max-w-4xl mx-auto">
            
            <!-- Back Button -->
            <div class="mb-8">
                <a href="{{ route('project.type-selection') }}" 
                   class="back-button">
                    <svg class="w-4 h-4 mr-2 back-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Projects
                </a>
            </div>
            
            <!-- Form Container -->
            <div class="form-container rounded-3xl shadow-2xl overflow-hidden">
                
                <!-- Header -->
                <div class="form-header p-8 text-center text-white relative z-10">
                    <div class="mb-4">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold mb-2">Edit Project</h1>
                        <p class="text-blue-100">Update project information and details</p>
                    </div>
                </div>
                
                <!-- Form Content -->
                <div class="p-8">
                    <form action="{{ route('projects.update', $project) }}" method="POST" id="editProjectForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            
                            <!-- Left Column -->
                            <div class="space-y-6">
                                
                                <!-- Project Name -->
                                <div class="form-field">
                                    <label for="name" class="form-label">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            Project Name *
                                        </div>
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           id="name"
                                           value="{{ old('name', $project->name) }}"
                                           class="form-input" 
                                           placeholder="Enter project name"
                                           required>
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Contract Number -->
                                <div class="form-field">
                                    <label for="contract_number" class="form-label">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Contract Number *
                                        </div>
                                    </label>
                                    <input type="text" 
                                           name="contract_number" 
                                           id="contract_number"
                                           value="{{ old('contract_number', $project->contract_number) }}"
                                           class="form-input" 
                                           placeholder="Enter contract number"
                                           required>
                                    @error('contract_number')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Project Type -->
                                <div class="form-field">
                                    <label for="project_type" class="form-label">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                            Project Type *
                                        </div>
                                    </label>
                                    <select name="project_type" 
                                            id="project_type" 
                                            class="form-select"
                                            required>
                                        <option value="">Select project type</option>
                                        <option value="OH33KV" {{ old('project_type', $project->project_type) == 'OH33KV' ? 'selected' : '' }}>OH 33KV</option>
                                        <option value="UA33LW" {{ old('project_type', $project->project_type) == 'UA33LW' ? 'selected' : '' }}>UA 33LW</option>
                                        <option value="S/S33KV" {{ old('project_type', $project->project_type) == 'S/S33KV' ? 'selected' : '' }}>S/S 33KV</option>
                                        <option value="UG132KV" {{ old('project_type', $project->project_type) == 'UG132KV' ? 'selected' : '' }}>UG 132 KV</option>
                                    </select>
                                    @error('project_type')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Location -->
                                <div class="form-field">
                                    <label for="location" class="form-label">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Location *
                                        </div>
                                    </label>
                                    <input type="text" 
                                           name="location" 
                                           id="location"
                                           value="{{ old('location', $project->location) }}"
                                           class="form-input" 
                                           placeholder="Enter project location"
                                           required>
                                    @error('location')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Amount -->
                                <div class="form-field">
                                    <label for="amount" class="form-label">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Amount *
                                        </div>
                                    </label>
                                    <input type="number" 
                                           name="amount" 
                                           id="amount"
                                           value="{{ old('amount', $project->amount) }}"
                                           class="form-input" 
                                           placeholder="Enter project amount"
                                           step="0.01"
                                           min="0">
                                    @error('amount')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                            </div>
                            
                            <!-- Right Column -->
                            <div class="space-y-6">
                                
                                <!-- Description -->
                                <div class="form-field">
                                    <label for="description" class="form-label">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                            </svg>
                                            Description
                                        </div>
                                    </label>
                                    <textarea name="description" 
                                              id="description"
                                              rows="4"
                                              class="form-textarea" 
                                              placeholder="Enter project description (optional)">{{ old('description', $project->description) }}</textarea>
                                    @error('description')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Status -->
                                <div class="form-field">
                                    <label for="status" class="form-label">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Project Status *
                                        </div>
                                    </label>
                                    <select name="status" 
                                            id="status" 
                                            class="form-select"
                                            required>
                                        <option value="">Select project status</option>
                                        <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="on_hold" {{ old('status', $project->status) == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                            </div>
                        </div>
                        
                        <!-- Date Fields Grid -->
                        <div class="mt-12">
                            <h3 class="text-xl font-semibold text-gray-800 mb-6 text-center">
                                <svg class="w-6 h-6 inline-block mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Project Timeline
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <!-- SIGN Date -->
                                <div class="date-card" style="--card-color: #3b82f6;">
                                    <div class="form-field">
                                        <label for="srgn_date" class="form-label">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                SIGN Date
                                            </div>
                                        </label>
                                        <input type="date" 
                                               name="srgn_date" 
                                               id="srgn_date"
                                               value="{{ old('srgn_date', $project->srgn_date ? $project->srgn_date->format('Y-m-d') : '') }}"
                                               class="form-input">
                                        @error('srgn_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- KICK OFF MEETING Date -->
                                <div class="date-card" style="--card-color: #8b5cf6;">
                                    <div class="form-field">
                                        <label for="kick_off_date" class="form-label">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                KICK OFF MEETING Date
                                            </div>
                                        </label>
                                        <input type="date" 
                                               name="kick_off_date" 
                                               id="kick_off_date"
                                               value="{{ old('kick_off_date', $project->kick_off_date ? $project->kick_off_date->format('Y-m-d') : '') }}"
                                               class="form-input">
                                        @error('kick_off_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- TCC Date -->
                                <div class="date-card" style="--card-color: #10b981;">
                                    <div class="form-field">
                                        <label for="tcc_date" class="form-label">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                TCC Date
                                            </div>
                                        </label>
                                        <input type="date" 
                                               name="tcc_date" 
                                               id="tcc_date"
                                               value="{{ old('tcc_date', $project->tcc_date ? $project->tcc_date->format('Y-m-d') : '') }}"
                                               class="form-input">
                                        @error('tcc_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- PAC Date -->
                                <div class="date-card" style="--card-color: #f59e0b;">
                                    <div class="form-field">
                                        <label for="pac_date" class="form-label">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                PAC Date
                                            </div>
                                        </label>
                                        <input type="date" 
                                               name="pac_date" 
                                               id="pac_date"
                                               value="{{ old('pac_date', $project->pac_date ? $project->pac_date->format('Y-m-d') : '') }}"
                                               class="form-input">
                                        @error('pac_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- FAT Date -->
                                <div class="date-card" style="--card-color: #ef4444;">
                                    <div class="form-field">
                                        <label for="fat_date" class="form-label">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                FAC Date
                                            </div>
                                        </label>
                                        <input type="date" 
                                               name="fat_date" 
                                               id="fat_date"
                                               value="{{ old('fat_date', $project->fat_date ? $project->fat_date->format('Y-m-d') : '') }}"
                                               class="form-input">
                                        @error('fat_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                        <!-- Warning Box -->
                        <div class="warning-box">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-amber-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-amber-800 mb-2">Important Notice</h4>
                                    <p class="text-amber-700 text-sm leading-relaxed mb-4">
                                        You are about to update project information. Please ensure all details are correct before proceeding. 
                                        Changes will be saved immediately and may affect project workflows and reporting.
                                    </p>
                                    
                                    <div class="checkbox-wrapper">
                                        <input type="checkbox" 
                                               id="confirmUpdate" 
                                               name="confirm_update"
                                               onchange="toggleSubmitButton()">
                                        <label for="confirmUpdate">
                                            I confirm that I have reviewed all information and want to proceed with updating this project.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="text-center mt-8">
                            <button type="submit" 
                                    class="submit-button" 
                                    id="submitButton"
                                    disabled>
                                <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Update Project
                            </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSubmitButton() {
    const checkbox = document.getElementById('confirmUpdate');
    const submitButton = document.getElementById('submitButton');
    
    if (checkbox.checked) {
        submitButton.disabled = false;
        submitButton.style.opacity = '1';
        submitButton.style.cursor = 'pointer';
    } else {
        submitButton.disabled = true;
        submitButton.style.opacity = '0.6';
        submitButton.style.cursor = 'not-allowed';
    }
}

// Form validation
document.getElementById('editProjectForm').addEventListener('submit', function(e) {
    const requiredFields = ['name', 'contract_number', 'project_type', 'location', 'status'];
    let isValid = true;
    
    requiredFields.forEach(function(fieldName) {
        const field = document.getElementById(fieldName);
        if (!field.value.trim()) {
            isValid = false;
            field.style.borderColor = '#ef4444';
        } else {
            field.style.borderColor = '#e2e8f0';
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Please fill in all required fields.');
        return false;
    }
    
    const confirmCheckbox = document.getElementById('confirmUpdate');
    if (!confirmCheckbox.checked) {
        e.preventDefault();
        alert('Please confirm that you want to update this project.');
        return false;
    }
});
</script>
@endsection
