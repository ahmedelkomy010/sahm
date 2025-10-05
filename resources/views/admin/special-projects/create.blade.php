@extends('layouts.app')

@push('styles')
<style>
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
    
    .form-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .form-label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .submit-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
    
    .cancel-btn {
        background: #f1f5f9;
        color: #64748b;
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        border: 1px solid #cbd5e1;
        transition: all 0.3s ease;
    }
    
    .cancel-btn:hover {
        background: #e2e8f0;
        color: #475569;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-100 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="form-header rounded-3xl shadow-2xl overflow-hidden mb-8 relative">
            <div class="relative z-10 p-8">
                <div class="flex justify-between items-center">
                    <div class="text-right text-white">
                        <h1 class="text-4xl font-bold mb-2">إنشاء مشروع خاص جديد</h1>
                        <p class="text-purple-100 text-lg">أدخل تفاصيل المشروع الخاص</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.special-projects.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 text-white rounded-xl font-semibold transition-all duration-300 hover:bg-white/30">
                            <i class="fas fa-arrow-right me-2"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="form-container p-8">
            @if(session('success'))
                <div class="alert alert-success mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-right">
                    <i class="fas fa-check-circle text-green-600 me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-right">
                    <i class="fas fa-exclamation-circle text-red-600 me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <ul class="mb-0 text-right">
                        @foreach ($errors->all() as $error)
                            <li class="text-red-700">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.special-projects.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Hidden field for project type -->
                <input type="hidden" name="project_type" value="special">

                <!-- Project Name -->
                <div class="mb-4">
                    <label for="name" class="form-label text-right d-block">
                        <i class="fas fa-briefcase text-purple-600 me-2"></i>
                        اسم المشروع <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           placeholder="أدخل اسم المشروع"
                           required>
                </div>

                <!-- Contract Number -->
                <div class="mb-4">
                    <label for="contract_number" class="form-label text-right d-block">
                        <i class="fas fa-file-contract text-purple-600 me-2"></i>
                        رقم العقد <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="contract_number" 
                           name="contract_number" 
                           value="{{ old('contract_number') }}"
                           placeholder="أدخل رقم العقد"
                           required>
                </div>

                <div class="row">
                    <!-- Location -->
                    <div class="col-md-6 mb-4">
                        <label for="location" class="form-label text-right d-block">
                            <i class="fas fa-map-marker-alt text-purple-600 me-2"></i>
                            الموقع <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="location" 
                               name="location" 
                               value="{{ old('location') }}"
                               placeholder="أدخل موقع المشروع"
                               required>
                    </div>

                    <!-- Amount -->
                    <div class="col-md-6 mb-4">
                        <label for="amount" class="form-label text-right d-block">
                            <i class="fas fa-money-bill-wave text-purple-600 me-2"></i>
                            قيمة المشروع (ريال سعودي)
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-control" 
                               id="amount" 
                               name="amount" 
                               value="{{ old('amount') }}"
                               placeholder="0.00">
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="form-label text-right d-block">
                        <i class="fas fa-align-right text-purple-600 me-2"></i>
                        وصف المشروع
                    </label>
                    <textarea class="form-control" 
                              id="description" 
                              name="description" 
                              rows="4"
                              placeholder="أدخل وصف تفصيلي للمشروع">{{ old('description') }}</textarea>
                </div>

                <!-- Timeline Section -->
                <div class="mb-4 p-4 bg-purple-50 rounded-xl">
                    <h5 class="font-bold text-purple-900 mb-4 text-right">
                        <i class="fas fa-calendar-alt me-2"></i>
                        الجدول الزمني للمشروع
                    </h5>
                    
                    <div class="row">
                        <!-- SRGN Date -->
                        <div class="col-md-6 mb-3">
                            <label for="srgn_date" class="form-label text-right d-block">تاريخ التوقيع (SRGN)</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="srgn_date" 
                                   name="srgn_date" 
                                   value="{{ old('srgn_date') }}">
                        </div>

                        <!-- Kick Off Date -->
                        <div class="col-md-6 mb-3">
                            <label for="kick_off_date" class="form-label text-right d-block">تاريخ بدء العمل</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="kick_off_date" 
                                   name="kick_off_date" 
                                   value="{{ old('kick_off_date') }}">
                        </div>

                        <!-- TCC Date -->
                        <div class="col-md-6 mb-3">
                            <label for="tcc_date" class="form-label text-right d-block">تاريخ TCC</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="tcc_date" 
                                   name="tcc_date" 
                                   value="{{ old('tcc_date') }}">
                        </div>

                        <!-- PAC Date -->
                        <div class="col-md-6 mb-3">
                            <label for="pac_date" class="form-label text-right d-block">تاريخ PAC</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="pac_date" 
                                   name="pac_date" 
                                   value="{{ old('pac_date') }}">
                        </div>

                        <!-- FAT Date -->
                        <div class="col-md-6 mb-3">
                            <label for="fat_date" class="form-label text-right d-block">تاريخ FAT</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="fat_date" 
                                   name="fat_date" 
                                   value="{{ old('fat_date') }}">
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-3 mt-6">
                    <a href="{{ route('admin.special-projects.index') }}" class="cancel-btn">
                        <i class="fas fa-times me-2"></i>
                        إلغاء
                    </a>
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-save me-2"></i>
                        حفظ المشروع
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

