@extends('layouts.app')

@push('styles')
<style>
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
        background: #ffffff;
    }
    
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        transform: translateY(-2px);
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }
    
    .form-label .required {
        color: #ef4444;
        margin-left: 4px;
    }
    
    .date-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }
    
    .date-card {
        background: linear-gradient(145deg, #f8fafc 0%, #ffffff 100%);
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .date-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: #667eea;
    }
    
    .date-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--card-color, #667eea);
        border-radius: 16px 16px 0 0;
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .date-card:hover::before {
        transform: scaleX(1);
    }
    
    .submit-button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 16px 32px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .submit-button:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }
    
    .submit-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    }
    
    .warning-card {
        background: linear-gradient(145deg, #fef3c7 0%, #fde68a 100%);
        border: 1px solid #f59e0b;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        position: relative;
    }
    
    .warning-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #f59e0b;
        border-radius: 16px 16px 0 0;
    }
    
    @media (max-width: 768px) {
        .date-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .date-card {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="form-container rounded-3xl shadow-2xl overflow-hidden">
            
            <!-- Header -->
            <div class="form-header p-8 text-center text-white relative z-10">
                <div class="mb-6">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold mb-3">Create New Project</h1>
                    <p class="text-blue-100 text-lg">Enter the details for your new project</p>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8 relative z-10">

                <!-- Warning Message -->
                <div class="warning-card">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-yellow-800 mb-2">Important Notice</h3>
                            <div class="text-yellow-700">
                                <p class="mb-2">Some data cannot be modified after project creation:</p>
                                <ul class="list-disc list-inside space-y-1 ml-4">
                                    <li>Contract Number</li>
                                    <li>Project Type</li>
                                    <li>Project Location</li>
                                </ul>
                                <p class="mt-2">Please verify all information before creating the project.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('projects.store') }}" method="POST">
                    @csrf

                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                    <!-- Project Name -->
                        <div class="form-field">
                            <label for="name" class="form-label">
                                Project Name <span class="required">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                                   class="form-input"
                                   placeholder="Enter project name"
                               required>
                        @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contract Number -->
                        <div class="form-field">
                            <label for="contract_number" class="form-label">
                                Contract Number <span class="required">*</span>
                        </label>
                        <input type="text" 
                               name="contract_number" 
                               id="contract_number"
                               value="{{ old('contract_number') }}"
                                   class="form-input"
                                   placeholder="e.g: 4400015737"
                               required>
                        @error('contract_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Project Type -->
                        <div class="form-field">
                            <label for="project_type" class="form-label">
                                Project Type <span class="required">*</span>
                        </label>
                        <select name="project_type" 
                                id="project_type" 
                                    class="form-select"
                                required>
                                <option value="">Select project type</option>
                                <option value="OH33KV" {{ old('project_type') == 'OH33KV' ? 'selected' : '' }}>OH 33KV</option>
                                <option value="UG33KV" {{ old('project_type') == 'UG33KV' ? 'selected' : '' }}>UG 33KV</option>
                                <option value="S|S33KV" {{ old('project_type') == 'S|S33KV' ? 'selected' : '' }}>S|S 33KV</option>
                                <option value="UG132KV" {{ old('project_type') == 'UG132KV' ? 'selected' : '' }}>UG 132 KV</option>
                        </select>
                        @error('project_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Project Location -->
                        <div class="form-field">
                            <label for="location" class="form-label">
                                Project Location <span class="required">*</span>
                        </label>
                        <input type="text" 
                               name="location" 
                               id="location"
                               value="{{ old('location') }}"
                                   class="form-input"
                                   placeholder="Enter project location"
                               required>
                        @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        </div>

                    <!-- Amount -->
                        <div class="form-field">
                            <label for="amount" class="form-label">
                                Amount (SAR)
                        </label>
                        <input type="number" 
                               name="amount" 
                               id="amount"
                               value="{{ old('amount') }}"
                                   class="form-input"
                                   placeholder="Enter project amount"
                               step="0.01"
                               min="0">
                        @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        </div>

                    </div>

                    <!-- Project Description -->
                    <div class="form-field mb-8">
                        <label for="description" class="form-label">
                            Project Description
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="form-textarea"
                                  placeholder="Enter a brief description of the project">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Project Dates Section -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">Project Timeline</h3>
                        
                        <div class="date-grid">
                            
                            <!-- SRGN Date -->
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
                                           value="{{ old('srgn_date') }}"
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
                                           value="{{ old('kick_off_date') }}"
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
                                           value="{{ old('tcc_date') }}"
                                           class="form-input">
                                    @error('tcc_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- PAC Date -->
                            <div class="date-card" style="--card-color: #8b5cf6;">
                                <div class="form-field">
                                    <label for="pac_date" class="form-label">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            PAC Date
                                        </div>
                                    </label>
                                    <input type="date" 
                                           name="pac_date" 
                                           id="pac_date"
                                           value="{{ old('pac_date') }}"
                                           class="form-input">
                                    @error('pac_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- FAT Date -->
                            <div class="date-card" style="--card-color: #f59e0b;">
                                <div class="form-field">
                                    <label for="fat_date" class="form-label">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            FAC Date
                                        </div>
                                    </label>
                                    <input type="date" 
                                           name="fat_date" 
                                           id="fat_date"
                                           value="{{ old('fat_date') }}"
                                           class="form-input">
                                    @error('fat_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Confirmation Section -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 mb-8">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 mt-1">
                            <input type="checkbox" 
                                   id="confirm_warning" 
                                       class="w-5 h-5 text-blue-600 border-2 border-gray-300 rounded-lg focus:ring-blue-500 focus:ring-2 transition-all"
                                   required>
                            </div>
                            <div class="flex-1">
                                <label for="confirm_warning" class="text-gray-700 font-medium cursor-pointer">
                                    I acknowledge that I have read the notice and understand that some data cannot be modified after project creation.
                            </label>
                            </div>
                        </div>
                        </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                            <button type="submit" 
                                    id="submit_button"
                                class="submit-button w-full sm:w-auto">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create Project
                            </button>
                            
                            <a href="{{ route('project.type-selection') }}" 
                           class="inline-flex items-center text-gray-600 hover:text-gray-800 font-medium transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Previous Page
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitButton = document.getElementById('submit_button');
    const confirmCheckbox = document.getElementById('confirm_warning');

    // Form submission validation
    form.addEventListener('submit', function(e) {
        if (!confirmCheckbox.checked) {
            e.preventDefault();
            alert('Please confirm that you have read the notice before creating the project.');
            return false;
        }
    });

    // Update button state based on checkbox
    confirmCheckbox.addEventListener('change', function() {
        submitButton.disabled = !this.checked;
    });

    // Set initial button state
    submitButton.disabled = true;

    // Add smooth animations to form inputs
    const inputs = document.querySelectorAll('.form-input, .form-select, .form-textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });

    // Add hover effects to date cards
    const dateCards = document.querySelectorAll('.date-card');
    dateCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endsection 