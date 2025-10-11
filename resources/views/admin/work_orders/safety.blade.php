@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0" style="font-size:2.1rem; font-weight:800; color:#2c3e50; letter-spacing:1px; display:flex; align-items:center;">
            <i class="fas fa-shield-alt me-2" style="color:#28a745;"></i>
            السلامة
        </h2>
        <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-success">
            <i class="fas fa-arrow-right"></i> عودة الي تفاصيل أمر العمل  
        </a>
    </div>

    <!-- معلومات أمر العمل -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-hashtag text-primary me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">رقم أمر العمل</small>
                                    <strong class="text-primary fs-6">{{ $workOrder->order_number }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-tools text-warning me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">نوع العمل</small>
                                    <strong class="fs-6">{{ $workOrder->work_type }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user text-info me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">اسم المشترك</small>
                                    <strong class="fs-6">{{ $workOrder->subscriber_name }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-map-marker-alt text-danger me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">الحي</small>
                                    <strong class="fs-6">{{ $workOrder->district }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- رسائل التنبيه --}}
    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    <!-- بيانات السلامة -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-success text-white d-flex align-items-center">
            <i class="fas fa-shield-alt me-2"></i>
        صور السلامة
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.work-orders.update-safety', $workOrder) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- حقول الصور -->
                <div class="row">
                    <!-- صور اجتماع ما قبل بدء العمل TBT -->
                    <div class="col-md-4 mb-3">
                        <label for="tbt_images" class="form-label fw-bold">
                            <i class="fas fa-users-cog me-2 text-success"></i>
                            صور اجتماع ما قبل بدء العمل TBT
                        </label>
                        <div class="input-group">
                            <input type="file" class="form-control @error('tbt_images.*') is-invalid @enderror" 
                                   name="tbt_images[]" 
                                   id="tbt_images"
                                   accept="image/*"
                                   multiple>
                        </div>
                        <div class="form-text">يمكن رفع عدة صور (JPG, PNG, GIF) - الحد الأقصى 100 ميجابايت لكل صورة</div>
                        @error('tbt_images.*')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <!-- صور التصاريح PERMITS -->
                    <div class="col-md-6 mb-3">
                        <label for="permits_images" class="form-label fw-bold">
                            <i class="fas fa-file-contract me-2 text-warning"></i>
                            صور التصاريح PERMITS
                        </label>
                        <div class="input-group">
                            <input type="file" class="form-control @error('permits_images.*') is-invalid @enderror" 
                                   name="permits_images[]" 
                                   id="permits_images"
                                   accept="image/*"
                                   multiple>
                        </div>
                        <div class="form-text">يمكن رفع عدة صور (JPG, PNG, GIF) - الحد الأقصى 100 ميجابايت لكل صورة</div>
                        @error('permits_images.*')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- صور فريق العمل والتأهيل للعمالة -->
                    <div class="col-md-6 mb-3">
                        <label for="team_images" class="form-label fw-bold">
                            <i class="fas fa-users me-2 text-info"></i>
                            صور فريق العمل والتأهيل للعمالة
                        </label>
                        <div class="input-group">
                            <input type="file" class="form-control @error('team_images.*') is-invalid @enderror" 
                                   name="team_images[]" 
                                   id="team_images"
                                   accept="image/*"
                                   multiple>
                        </div>
                        <div class="form-text">يمكن رفع عدة صور (JPG, PNG, GIF) - الحد الأقصى 100 ميجابايت لكل صورة</div>
                        @error('team_images.*')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- صور المعدات والتأهيل للمعدات -->
                    <div class="col-md-4 mb-3">
                        <label for="equipment_images" class="form-label fw-bold">
                            <i class="fas fa-tools me-2 text-primary"></i>
                            صور المعدات والتأهيل للمعدات
                        </label>
                        <div class="input-group">
                            <input type="file" class="form-control @error('equipment_images.*') is-invalid @enderror" 
                                   name="equipment_images[]" 
                                   id="equipment_images"
                                   accept="image/*"
                                   multiple>
                        </div>
                        <div class="form-text">يمكن رفع عدة صور (JPG, PNG, GIF) - الحد الأقصى 100 ميجابايت لكل صورة</div>
                        @error('equipment_images.*')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- صور عامة -->
                    <div class="col-md-4 mb-3">
                        <label for="general_images" class="form-label fw-bold">
                            <i class="fas fa-images me-2 text-secondary"></i>
                            صور عامة للموقعٍ
                        </label>
                        <div class="input-group">
                            <input type="file" class="form-control @error('general_images.*') is-invalid @enderror" 
                                   name="general_images[]" 
                                   id="general_images"
                                   accept="image/*"
                                   multiple>
                        </div>
                        <div class="form-text">يمكن رفع عدة صور (JPG, PNG, GIF) - الحد الأقصى 100 ميجابايت لكل صورة</div>
                        @error('general_images.*')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    
                </div>
                 <!-- البيانات بيانات التفتيش اليومي  -->
                 <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-shield-alt me-2"></i>
                        بيانات التفتيش اليومي 
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- مسؤول السلامة -->
                            <div class="col-md-4 mb-3">
                                <label for="safety_officer" class="form-label fw-bold">
                                    <i class="fas fa-user-tie me-2 text-primary"></i>
                                    مسؤول السلامة
                                </label>
                                <input type="text" 
                                    class="form-control @error('safety_officer') is-invalid @enderror" 
                                    name="safety_officer" 
                                    id="safety_officer" 
                                    value="{{ old('safety_officer', $workOrder->safety_officer) }}"
                                    placeholder="اسم مسؤول السلامة">
                                @error('safety_officer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <!-- عرض سجل مسؤولي السلامة -->
                                @if(isset($safetyHistory) && $safetyHistory->where('safety_officer', '!=', null)->count() > 0)
                                <div class="safety-officer-history mt-2">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <small class="text-primary fw-bold">
                                            <i class="fas fa-history me-1"></i>
                                            سجل مسؤولي السلامة ({{ $safetyHistory->where('safety_officer', '!=', null)->count() }})
                                        </small>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleSafetyOfficerHistory()">
                                            <i class="fas fa-eye" id="officer-toggle-icon"></i>
                                            <span id="officer-toggle-text">عرض</span>
                                        </button>
                                    </div>
                                    <div id="safety-officer-history" style="display: none;">
                                        @foreach($safetyHistory->where('safety_officer', '!=', null)->take(5) as $index => $history)
                                        <div class="history-item mb-1 p-2 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded">
                                            <small class="text-primary fw-bold d-flex align-items-center justify-content-between">
                                                <div>
                                                    <i class="fas fa-user-tie me-1"></i>
                                                    {{ $history->safety_officer }}
                                                    @if($index === 0)
                                                        <span class="badge bg-primary ms-2">الحالي</span>
                                                    @endif
                                                </div>
                                                <div class="text-muted small">
                                                    {{ \Carbon\Carbon::parse($history->created_at)->diffForHumans() }}
                                                </div>
                                            </small>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- حالة السلامة -->
                            <div class="col-md-4 mb-3">
                                <label for="safety_status" class="form-label fw-bold">
                                    <i class="fas fa-check-circle me-2 text-success"></i>
                                    حالة السلامة
                                </label>
                                <select class="form-select @error('safety_status') is-invalid @enderror" 
                                        name="safety_status" 
                                        id="safety_status"
                                        onchange="toggleNonComplianceFields()">
                                    <option value="">اختر حالة السلامة</option>
                                    <option value="مطابق" {{ old('safety_status', $workOrder->safety_status) == 'مطابق' ? 'selected' : '' }}>
                                        <i class="fas fa-check text-success"></i> مطابق
                                    </option>
                                    <option value="غير مطابق" {{ old('safety_status', $workOrder->safety_status) == 'غير مطابق' ? 'selected' : '' }}>
                                        <i class="fas fa-times text-danger"></i> غير مطابق
                                    </option>
                                    
                                </select>
                                @error('safety_status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <!-- عرض سجل حالة السلامة -->
                                @if(isset($safetyHistory) && $safetyHistory->where('safety_status', '!=', null)->count() > 0)
                                <div class="safety-status-history mt-2">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <small class="text-success fw-bold">
                                            <i class="fas fa-history me-1"></i>
                                            سجل حالة السلامة ({{ $safetyHistory->where('safety_status', '!=', null)->count() }})
                                        </small>
                                        <button type="button" class="btn btn-sm btn-outline-success" onclick="toggleSafetyStatusHistory()">
                                            <i class="fas fa-eye" id="status-toggle-icon"></i>
                                            <span id="status-toggle-text">عرض</span>
                                        </button>
                                    </div>
                                    <div id="safety-status-history" style="display: none;">
                                        @foreach($safetyHistory->where('safety_status', '!=', null)->take(5) as $index => $history)
                                        <div class="history-item mb-1 p-2 {{ $history->safety_status == 'مطابق' ? 'bg-success' : 'bg-danger' }} bg-opacity-10 border {{ $history->safety_status == 'مطابق' ? 'border-success' : 'border-danger' }} border-opacity-25 rounded">
                                            <small class="{{ $history->safety_status == 'مطابق' ? 'text-success' : 'text-danger' }} fw-bold d-flex align-items-center justify-content-between">
                                                <div>
                                                    <i class="fas {{ $history->safety_status == 'مطابق' ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                                                    {{ $history->safety_status }}
                                                    @if($index === 0)
                                                        <span class="badge {{ $history->safety_status == 'مطابق' ? 'bg-success' : 'bg-danger' }} ms-2">الحالي</span>
                                                    @endif
                                                </div>
                                                <div class="text-muted small">
                                                    {{ \Carbon\Carbon::parse($history->created_at)->diffForHumans() }}
                                                </div>
                                            </small>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- تاريخ التفتيش -->
                            <div class="col-md-4 mb-3">
                                <label for="inspection_date" class="form-label fw-bold">
                                    <i class="fas fa-calendar me-2 text-warning"></i>
                                    تاريخ التفتيش
                                </label>
                                <input type="date" 
                                    class="form-control @error('inspection_date') is-invalid @enderror" 
                                    name="inspection_date" 
                                    id="inspection_date" 
                                    value=""
                                    placeholder="أدخل تاريخ تفتيش جديد"
                                    onchange="updateSavedDateDisplay()">
                                @error('inspection_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                
                                
                                
                                <!-- عرض جميع التواريخ المحفوظة -->
                                @if(isset($inspectionDates) && $inspectionDates->count() > 0)
                                <div class="saved-dates-history mt-2">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <small class="text-success fw-bold">
                                            <i class="fas fa-history me-1"></i>
                                            سجل تواريخ التفتيش ({{ $inspectionDates->count() }})
                                        </small>
                                        <button type="button" class="btn btn-sm btn-outline-success" onclick="toggleInspectionHistory()">
                                            <i class="fas fa-eye" id="toggle-icon"></i>
                                            <span id="toggle-text">عرض</span>
                                        </button>
                                    </div>
                                    <div id="inspection-history" style="display: none;">
                                        @foreach($inspectionDates as $index => $inspectionDate)
                                        <div class="saved-date-display mb-2 p-2 bg-success bg-opacity-10 border border-success border-opacity-25 rounded">
                                            <small class="text-success fw-bold d-flex align-items-center justify-content-between">
                                                <div>
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    {{ $inspectionDate->inspection_date->format('Y-m-d') }}
                                                    <span class="text-muted">{{ $inspectionDate->inspection_date->format('(l, F j, Y)') }}</span>
                                                    @if($index === 0)
                                                        <span class="badge bg-success ms-2">الأحدث</span>
                                                    @endif
                                                </div>
                                                <div class="text-muted small">
                                                    {{ $inspectionDate->created_at->diffForHumans() }}
                                                </div>
                                            </small>
                                            @if($inspectionDate->inspector_name && $inspectionDate->inspector_name !== 'غير محدد')
                                            <div class="text-muted small mt-1">
                                                <i class="fas fa-user me-1"></i>
                                                المفتش: {{ $inspectionDate->inspector_name }}
                                            </div>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @elseif($workOrder->inspection_date)
                                <!-- عرض التاريخ القديم للتوافق مع النظام السابق -->
                                <div class="saved-date-display mt-2 p-2 bg-success bg-opacity-10 border border-success border-opacity-25 rounded">
                                    <small class="text-success fw-bold">
                                        <i class="fas fa-check-circle me-1"></i>
                                        تاريخ محفوظ: {{ $workOrder->inspection_date->format('Y-m-d') }}
                                        <span class="text-muted">{{ $workOrder->inspection_date->format('(l, F j, Y)') }}</span>
                                    </small>
                                </div>
                                @endif
                                
                                <!-- منطقة عرض التاريخ الجديد بعد التغيير -->
                                <div id="new-date-display" class="new-date-display mt-2 p-2 bg-info bg-opacity-10 border border-info border-opacity-25 rounded" style="display: none;">
                                    <small class="text-info fw-bold">
                                        <i class="fas fa-clock me-1"></i>
                                        تاريخ جديد: <span id="new-date-text"></span>
                                        <span class="text-muted" id="new-date-formatted"></span>
                                    </small>
                                </div>
                            </div>

                            <!-- أسباب عدم المطابقة (تظهر فقط عند اختيار غير مطابق) -->
                            <div class="col-12 mb-3" id="non_compliance_reasons_field" style="display: none;">
                                <label for="non_compliance_reasons" class="form-label fw-bold">
                                    <i class="fas fa-exclamation-triangle me-2 text-danger"></i>
                                    أسباب عدم المطابقة
                                </label>
                                <textarea class="form-control @error('non_compliance_reasons') is-invalid @enderror" 
                                    name="non_compliance_reasons" 
                                    id="non_compliance_reasons" 
                                    rows="4" 
                                    placeholder="اذكر أسباب عدم المطابقة بالتفصيل...">{{ old('non_compliance_reasons', $workOrder->non_compliance_reasons ?? '') }}</textarea>
                                @error('non_compliance_reasons')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- مرفقات عدم المطابقة (تظهر فقط عند اختيار غير مطابق) -->
                            <div class="col-12 mb-3" id="non_compliance_attachments_field" style="display: none;">
                                <label for="non_compliance_attachments" class="form-label fw-bold">
                                    <i class="fas fa-paperclip me-2 text-danger"></i>
                                    مرفقات عدم المطابقة
                                </label>
                                <input type="file" 
                                    class="form-control @error('non_compliance_attachments.*') is-invalid @enderror" 
                                    name="non_compliance_attachments[]" 
                                    id="non_compliance_attachments"
                                    accept="image/*,.pdf,.doc,.docx"
                                    multiple>
                                <div class="form-text">يمكن رفع عدة ملفات (صور، PDF، Word) - الحد الأقصى 50 ميجابايت لكل ملف</div>
                                @error('non_compliance_attachments.*')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- ملاحظات السلامة -->
                            <div class="col-12">
                                <label for="safety_notes" class="form-label fw-bold">
                                    <i class="fas fa-sticky-note me-2 text-info"></i>
                                     اجراءات التصحيح المطلوب
                                </label>
                                <textarea class="form-control @error('safety_notes') is-invalid @enderror" 
                                    name="safety_notes" 
                                    id="safety_notes" 
                                    rows="4" 
                                    placeholder="أدخل ملاحظات السلامة...">{{ old('safety_notes', $workOrder->safety_notes) }}</textarea>
                                @error('safety_notes')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                 </div>
                

                <div class="text-center">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-2"></i>حفظ بيانات السلامة
                    </button>
                </div>
            </form>
        </div>
    </div>



    <!-- عرض الصور المرفوعة -->
    @php
        $imageCategories = [
            'permits' => [
                'title' => 'صور التصاريح PERMITS',
                'icon' => 'fa-file-contract',
                'color' => 'warning',
                'images' => $workOrder->safety_permits_images ?? []
            ],
            'team' => [
                'title' => 'صور فريق العمل والتأهيل للعمالة',
                'icon' => 'fa-users',
                'color' => 'info',
                'images' => $workOrder->safety_team_images ?? []
            ],
            'equipment' => [
                'title' => 'صور المعدات والتأهيل للمعدات',
                'icon' => 'fa-tools',
                'color' => 'primary',
                'images' => $workOrder->safety_equipment_images ?? []
            ],
            'general' => [
                'title' => 'صور عامة للسلامة',
                'icon' => 'fa-images',
                'color' => 'secondary',
                'images' => $workOrder->safety_general_images ?? []
            ],
            'tbt' => [
                'title' => 'صور اجتماع ما قبل بدء العمل TBT',
                'icon' => 'fa-users-cog',
                'color' => 'success',
                'images' => $workOrder->safety_tbt_images ?? []
            ]
        ];
    @endphp

    @foreach($imageCategories as $category => $data)
        @if(count($data['images']) > 0)
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-{{ $data['color'] }} text-white d-flex align-items-center">
                <i class="fas {{ $data['icon'] }} me-2"></i>
                {{ $data['title'] }} ({{ count($data['images']) }})
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($data['images'] as $index => $imagePath)
                        <div class="col-md-3 col-sm-4 col-6">
                            <div class="card border safety-image-card" data-category="{{ $category }}" data-index="{{ $index }}">
                                <div class="position-relative">
                                    <img src="{{ Storage::url($imagePath) }}" 
                                         class="card-img-top safety-image" 
                                         alt="صورة {{ $data['title'] }}"
                                         style="height: 200px; object-fit: cover; cursor: pointer;"
                                         onclick="showImageModal('{{ Storage::url($imagePath) }}', '{{ $data['title'] }}')">
                                    
                                    <!-- أزرار العمليات -->
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <!-- زر العرض -->
                                        <button type="button" 
                                                class="btn btn-primary btn-sm me-1"
                                                onclick="showImageModal('{{ Storage::url($imagePath) }}', '{{ $data['title'] }}')"
                                                title="عرض الصورة">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <!-- زر الحذف -->
                                        <button type="button" 
                                                class="btn btn-danger btn-sm delete-safety-image"
                                                data-work-order="{{ $workOrder->id }}"
                                                data-category="{{ $category }}"
                                                data-index="{{ $index }}"
                                                title="حذف الصورة">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-2 text-center">
                                    <small class="text-muted">صورة {{ $index + 1 }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    @endforeach

    <!-- عرض مرفقات عدم المطابقة -->
    @if(isset($workOrder->non_compliance_attachments) && is_array($workOrder->non_compliance_attachments) && count($workOrder->non_compliance_attachments) > 0)
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-danger text-white d-flex align-items-center">
            <i class="fas fa-paperclip me-2"></i>
            مرفقات عدم المطابقة ({{ count($workOrder->non_compliance_attachments) }})
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($workOrder->non_compliance_attachments as $index => $attachmentPath)
                    <div class="col-md-3 col-sm-4 col-6">
                        <div class="card border non-compliance-attachment-card" data-index="{{ $index }}">
                            <div class="position-relative">
                                @php
                                    $fileExtension = pathinfo($attachmentPath, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                @endphp
                                
                                @if($isImage)
                                    <img src="{{ Storage::url($attachmentPath) }}" 
                                         class="card-img-top" 
                                         alt="مرفق عدم المطابقة"
                                         style="height: 200px; object-fit: cover; cursor: pointer;"
                                         onclick="showImageModal('{{ Storage::url($attachmentPath) }}', 'مرفق عدم المطابقة')">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                        <div class="text-center">
                                            <i class="fas fa-file-alt fs-1 text-secondary mb-2"></i>
                                            <br>
                                            <small class="text-muted">{{ strtoupper($fileExtension) }}</small>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- أزرار العمليات -->
                                <div class="position-absolute top-0 end-0 m-2">
                                    @if($isImage)
                                        <!-- زر العرض للصور -->
                                        <button type="button" 
                                                class="btn btn-primary btn-sm me-1"
                                                onclick="showImageModal('{{ Storage::url($attachmentPath) }}', 'مرفق عدم المطابقة')"
                                                title="عرض الصورة">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <!-- زر العرض للملفات -->
                                        <a href="{{ Storage::url($attachmentPath) }}" 
                                           target="_blank" 
                                           class="btn btn-primary btn-sm me-1"
                                           title="عرض الملف">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    <!-- زر الحذف -->
                                    <button type="button" 
                                            class="btn btn-danger btn-sm delete-non-compliance-attachment"
                                            data-work-order="{{ $workOrder->id }}"
                                            data-index="{{ $index }}"
                                            title="حذف المرفق">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-2 text-center">
                                <small class="text-muted d-block">{{ basename($attachmentPath) }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- سكشن مخالفات السلامة والكهرباء -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-danger text-white d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                مخالفات السلامة والكهرباء
            </div>
            
        </div>
        <div class="card-body">
            <!-- نموذج إضافة مخالفة سريع -->
            <div class="row mb-4 p-3 bg-light rounded">
                <h6 class="mb-3"><i class="fas fa-plus-circle me-2 text-danger"></i>إضافة مخالفة جديدة</h6>
                <form id="addViolationForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label class="form-label fw-bold">قيمة المخالفة</label>
                            <input type="number" step="0.01" class="form-control" name="violation_amount" placeholder="0.00" required>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="form-label fw-bold">المتسبب في المخالفة</label>
                            <input type="text" class="form-control" name="violator" placeholder="اسم المتسبب" required>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="form-label fw-bold">
                                <i class="fas fa-building me-1"></i>
                                جهة المخالفة
                            </label>
                            <select class="form-select" name="violation_source" required>
                                <option value="">اختر الجهة</option>
                                <option value="internal">
                                    <i class="fas fa-home"></i> داخلية
                                </option>
                                <option value="external">
                                    <i class="fas fa-external-link-alt"></i> خارجية
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label fw-bold">تاريخ المخالفة</label>
                            <input type="date" class="form-control" name="violation_date" required>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-12 mb-2">
                            <label class="form-label fw-bold">وصف المخالفة</label>
                            <textarea class="form-control" name="description" rows="2" placeholder="اكتب وصف تفصيلي للمخالفة" required></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold">ملاحظات</label>
                            <textarea class="form-control" name="notes" rows="2" placeholder="ملاحظات إضافية (اختياري)"></textarea>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold">مرفقات المخالفة</label>
                            <input type="file" class="form-control" name="violation_attachments[]" multiple 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif" 
                                   title="يمكن رفع ملفات PDF, Word, أو صور">
                            <small class="text-muted">يمكن اختيار عدة ملفات (PDF, Word, صور)</small>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label fw-bold">إجراء</label>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-plus me-1"></i>حفظ مخالفة
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- جدول المخالفات -->
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="violationsTable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>قيمة المخالفة</th>
                            <th>المتسبب</th>
                            <th>جهة المخالفة</th>
                            <th>تاريخ المخالفة</th>
                            <th>وصف المخالفة</th>
                            <th>ملاحظات</th>
                            <th>مرفقات</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($safetyViolations as $index => $violation)
                            <tr data-violation-id="{{ $violation->id }}">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge bg-danger fs-6">
                                        {{ number_format($violation->violation_amount, 2) }} ﷼
                                    </span>
                                </td>
                                <td>{{ $violation->violator }}</td>
                                <td>
                                    <span class="badge {{ $violation->violation_source_badge_class }} fs-6">
                                        @if($violation->violation_source === 'internal')
                                            <i class="fas fa-home me-1"></i>
                                        @else
                                            <i class="fas fa-external-link-alt me-1"></i>
                                        @endif
                                        {{ $violation->violation_source_label }}
                                    </span>
                                </td>
                                <td>{{ $violation->violation_date->format('Y-m-d') }}</td>
                                <td>
                                    @if($violation->description)
                                        <span class="text-dark">{{ Str::limit($violation->description, 100) }}</span>
                                    @else
                                        <span class="text-muted fst-italic">لا يوجد وصف</span>
                                    @endif
                                </td>
                                <td>
                                    @if($violation->notes)
                                        <span class="text-muted">{{ Str::limit($violation->notes, 50) }}</span>
                                    @else
                                        <span class="text-muted fst-italic">لا توجد ملاحظات</span>
                                    @endif
                                </td>
                                <td>
                                    @if($violation->attachments && count($violation->attachments) > 0)
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($violation->attachments as $attachment)
                                                <a href="{{ Storage::url($attachment) }}" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="{{ basename($attachment) }}">
                                                    <i class="fas fa-paperclip me-1"></i>
                                                    {{ Str::limit(basename($attachment), 10) }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic">لا توجد مرفقات</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-violation" 
                                            data-violation-id="{{ $violation->id }}"
                                            title="حذف المخالفة">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="noViolationsRow">
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-check-circle fs-3 text-success mb-2"></i>
                                    <br>لا توجد مخالفات سلامة مسجلة
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- إجمالي المخالفات -->
            @if($safetyViolations->count() > 0)
            <div class="row mt-3">
                <div class="col-md-6 offset-md-6">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h5 class="mb-1">إجمالي قيمة المخالفات</h5>
                            <h3 class="mb-0" id="totalViolations">
                                {{ number_format($safetyViolations->sum('violation_amount'), 2) }} ﷼
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal لعرض الصورة بالحجم الكامل -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">عرض الصورة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="صورة">
            </div>
            <div class="modal-footer">
                <a id="downloadImage" href="" download class="btn btn-primary">
                    <i class="fas fa-download me-2"></i>تحميل الصورة
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<style>
/* تحسين شكل أزرار العمليات على الصور */
.safety-image-card:hover .position-absolute {
    opacity: 1;
}

.safety-image-card .position-absolute {
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.safety-image-card .position-absolute .btn {
    box-shadow: 0 2px 4px rgba(0,0,0,0.3);
    backdrop-filter: blur(2px);
}

.non-compliance-attachment-card:hover .position-absolute {
    opacity: 1;
}

.non-compliance-attachment-card .position-absolute {
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.non-compliance-attachment-card .position-absolute .btn {
    box-shadow: 0 2px 4px rgba(0,0,0,0.3);
    backdrop-filter: blur(2px);
}

/* تنسيق عرض التاريخ المحفوظ */
.saved-date-display {
    animation: slideInFromLeft 0.5s ease-out;
}

.new-date-display {
    animation: slideInFromRight 0.5s ease-out;
}

@keyframes slideInFromLeft {
    0% {
        opacity: 0;
        transform: translateX(-20px);
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInFromRight {
    0% {
        opacity: 0;
        transform: translateX(20px);
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInFromTop {
    0% {
        opacity: 0;
        transform: translateY(-20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

/* تحسين مظهر التاريخ المعروض */
.saved-date-display small,
.new-date-display small {
    font-size: 0.8rem;
    line-height: 1.4;
}

.saved-date-display .text-muted,
.new-date-display .text-muted {
    font-size: 0.75rem;
    display: block;
    margin-top: 2px;
}

/* تأثيرات رسالة تأكيد الحفظ */
@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.02);
        opacity: 0.9;
    }
}

@keyframes fadeOut {
    0% {
        opacity: 1;
        transform: translateY(0);
    }
    100% {
        opacity: 0;
        transform: translateY(-10px);
    }
}

.saved-confirmation {
    border-left: 4px solid #ffffff;
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}
</style>

<script>
// دالة إظهار/إخفاء حقول عدم المطابقة
function toggleNonComplianceFields() {
    const safetyStatus = document.getElementById('safety_status').value;
    const reasonsField = document.getElementById('non_compliance_reasons_field');
    const attachmentsField = document.getElementById('non_compliance_attachments_field');
    
    if (safetyStatus === 'غير مطابق') {
        reasonsField.style.display = 'block';
        attachmentsField.style.display = 'block';
        // جعل حقل الأسباب مطلوب
        document.getElementById('non_compliance_reasons').setAttribute('required', 'required');
    } else {
        reasonsField.style.display = 'none';
        attachmentsField.style.display = 'none';
        // إزالة خاصية المطلوب
        document.getElementById('non_compliance_reasons').removeAttribute('required');
    }
}

// دالة تحديث عرض التاريخ المحفوظ
function updateSavedDateDisplay() {
    const dateInput = document.getElementById('inspection_date');
    const newDateDisplay = document.getElementById('new-date-display');
    const newDateText = document.getElementById('new-date-text');
    const newDateFormatted = document.getElementById('new-date-formatted');
    
    if (dateInput.value) {
        // تحويل التاريخ إلى تنسيق قابل للقراءة
        const selectedDate = new Date(dateInput.value);
        const formattedDate = formatDateToArabic(selectedDate);
        
        newDateText.textContent = dateInput.value;
        newDateFormatted.textContent = `(${formattedDate})`;
        newDateDisplay.style.display = 'block';
        
        // إضافة تأثير بصري لتأكيد التغيير
        newDateDisplay.classList.add('animate__animated', 'animate__fadeIn');
        setTimeout(() => {
            newDateDisplay.classList.remove('animate__animated', 'animate__fadeIn');
        }, 1000);
    } else {
        newDateDisplay.style.display = 'none';
    }
}

// دالة تنسيق التاريخ باللغة العربية
function formatDateToArabic(date) {
    const arabicMonths = [
        'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
        'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
    ];
    
    const arabicDays = [
        'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'
    ];
    
    const dayName = arabicDays[date.getDay()];
    const dayNumber = date.getDate();
    const monthName = arabicMonths[date.getMonth()];
    const year = date.getFullYear();
    
    return `${dayName}, ${dayNumber} ${monthName} ${year}`;
}

// دالة تبديل عرض سجل تواريخ التفتيش
function toggleInspectionHistory() {
    const historyDiv = document.getElementById('inspection-history');
    const toggleIcon = document.getElementById('toggle-icon');
    const toggleText = document.getElementById('toggle-text');
    
    if (historyDiv.style.display === 'none') {
        historyDiv.style.display = 'block';
        toggleIcon.className = 'fas fa-eye-slash';
        toggleText.textContent = 'إخفاء';
        
        // تأثير انيميشن
        historyDiv.style.animation = 'slideInFromTop 0.3s ease-out';
    } else {
        historyDiv.style.display = 'none';
        toggleIcon.className = 'fas fa-eye';
        toggleText.textContent = 'عرض';
    }
}

// دالة تبديل عرض سجل مسؤولي السلامة
function toggleSafetyOfficerHistory() {
    const historyDiv = document.getElementById('safety-officer-history');
    const toggleIcon = document.getElementById('officer-toggle-icon');
    const toggleText = document.getElementById('officer-toggle-text');
    
    if (historyDiv.style.display === 'none') {
        historyDiv.style.display = 'block';
        toggleIcon.className = 'fas fa-eye-slash';
        toggleText.textContent = 'إخفاء';
        
        // تأثير انيميشن
        historyDiv.style.animation = 'slideInFromTop 0.3s ease-out';
    } else {
        historyDiv.style.display = 'none';
        toggleIcon.className = 'fas fa-eye';
        toggleText.textContent = 'عرض';
    }
}

// دالة تبديل عرض سجل حالة السلامة
function toggleSafetyStatusHistory() {
    const historyDiv = document.getElementById('safety-status-history');
    const toggleIcon = document.getElementById('status-toggle-icon');
    const toggleText = document.getElementById('status-toggle-text');
    
    if (historyDiv.style.display === 'none') {
        historyDiv.style.display = 'block';
        toggleIcon.className = 'fas fa-eye-slash';
        toggleText.textContent = 'إخفاء';
        
        // تأثير انيميشن
        historyDiv.style.animation = 'slideInFromTop 0.3s ease-out';
    } else {
        historyDiv.style.display = 'none';
        toggleIcon.className = 'fas fa-eye';
        toggleText.textContent = 'عرض';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // تشغيل الدالة عند تحميل الصفحة لإظهار الحقول إذا كانت القيمة محفوظة مسبقاً
    toggleNonComplianceFields();
    
    // معالج النموذج الرئيسي للسلامة
    const safetyForm = document.querySelector('form[action*="update-safety"]');
    if (safetyForm) {
        safetyForm.addEventListener('submit', function(e) {
            const dateInput = document.getElementById('inspection_date');
            if (dateInput && dateInput.value) {
                // حفظ التاريخ المحدد لعرضه بعد إعادة التحميل
                sessionStorage.setItem('lastSavedInspectionDate', dateInput.value);
                sessionStorage.setItem('shouldClearDateField', 'true');
            }
        });
    }
    
    // التحقق من وجود تاريخ محفوظ في الجلسة وعرضه
    const lastSavedDate = sessionStorage.getItem('lastSavedInspectionDate');
    const shouldClearField = sessionStorage.getItem('shouldClearDateField');
    
    if (lastSavedDate) {
        // إنشاء رسالة تأكيد الحفظ
        showDateSavedMessage(lastSavedDate);
        // إزالة التاريخ من التخزين المؤقت
        sessionStorage.removeItem('lastSavedInspectionDate');
    }
    
    if (shouldClearField === 'true') {
        // مسح حقل التاريخ لإظهار أنه جاهز لتاريخ جديد
        const dateInput = document.getElementById('inspection_date');
        if (dateInput) {
            dateInput.value = '';
        }
        sessionStorage.removeItem('shouldClearDateField');
    }
    // معالج حذف صور السلامة
    document.querySelectorAll('.delete-safety-image').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
                const workOrderId = this.dataset.workOrder;
                const category = this.dataset.category;
                const index = this.dataset.index;
                const imageCard = this.closest('.col-md-3');
                
                fetch(`{{ url('admin/work-orders') }}/${workOrderId}/safety-image/${category}/${index}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        imageCard.remove();
                        
                        // تحديث عداد الصور في العنوان
                        const cardHeader = imageCard.closest('.card').querySelector('.card-header');
                        const currentCount = cardHeader.textContent.match(/\((\d+)\)/);
                        if (currentCount) {
                            const newCount = parseInt(currentCount[1]) - 1;
                            if (newCount === 0) {
                                // إخفاء القسم كاملاً إذا لم تعد هناك صور
                                imageCard.closest('.card').remove();
                            } else {
                                cardHeader.innerHTML = cardHeader.innerHTML.replace(/\(\d+\)/, `(${newCount})`);
                            }
                        }
                        
                        // إظهار رسالة نجاح
                        showSuccessMessage('تم حذف الصورة بنجاح');
                    } else {
                        alert('حدث خطأ أثناء حذف الصورة');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء حذف الصورة');
                });
            }
        });
    });

    // معالج إضافة مخالفة
    document.getElementById('addViolationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // تعطيل الزر أثناء الإرسال
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>جاري الإضافة...';
        
        fetch(`/admin/work-orders/{{ $workOrder->id }}/safety-violation`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // إعادة تحميل الصفحة لعرض المخالفة الجديدة
                location.reload();
            } else {
                alert('حدث خطأ أثناء إضافة المخالفة');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء إضافة المخالفة');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    // معالج حذف مخالفة
    document.querySelectorAll('.delete-violation').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('هل أنت متأكد من حذف هذه المخالفة؟')) {
                const violationId = this.dataset.violationId;
                const violationRow = this.closest('tr');
                
                fetch(`/admin/work-orders/safety-violation/${violationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        violationRow.remove();
                        
                        // إعادة ترقيم الصفوف
                        updateRowNumbers();
                        
                        // تحديث الإجمالي
                        updateTotalViolations();
                        
                        // إظهار رسالة "لا توجد مخالفات" إذا لم تعد هناك مخالفات
                        const tbody = document.querySelector('#violationsTable tbody');
                        if (tbody.children.length === 0) {
                            tbody.innerHTML = `
                                <tr id="noViolationsRow">
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-check-circle fs-3 text-success mb-2"></i>
                                        <br>لا توجد مخالفات سلامة مسجلة
                                    </td>
                                </tr>
                            `;
                        }
                        
                        showSuccessMessage('تم حذف المخالفة بنجاح');
                    } else {
                        alert('حدث خطأ أثناء حذف المخالفة');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء حذف المخالفة');
                });
            }
        });
    });

    // معالج حذف مرفقات عدم المطابقة
    document.querySelectorAll('.delete-non-compliance-attachment').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (confirm('هل أنت متأكد من حذف هذا المرفق؟')) {
                const workOrderId = this.dataset.workOrder;
                const index = this.dataset.index;
                const attachmentCard = this.closest('.col-md-3');
                
                fetch(`/admin/work-orders/${workOrderId}/non-compliance-attachment/${index}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        attachmentCard.remove();
                        
                        // تحديث عداد المرفقات في العنوان
                        const cardHeader = attachmentCard.closest('.card').querySelector('.card-header');
                        const currentCount = cardHeader.textContent.match(/\((\d+)\)/);
                        if (currentCount) {
                            const newCount = parseInt(currentCount[1]) - 1;
                            if (newCount === 0) {
                                // إخفاء القسم كاملاً إذا لم تعد هناك مرفقات
                                attachmentCard.closest('.card').remove();
                            } else {
                                cardHeader.innerHTML = cardHeader.innerHTML.replace(/\(\d+\)/, `(${newCount})`);
                            }
                        }
                        
                        // إظهار رسالة نجاح
                        showSuccessMessage('تم حذف المرفق بنجاح');
                    } else {
                        alert('حدث خطأ أثناء حذف المرفق');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء حذف المرفق');
                });
            }
        });
    });
});

// دالة إعادة ترقيم الصفوف
function updateRowNumbers() {
    const rows = document.querySelectorAll('#violationsTable tbody tr[data-violation-id]');
    rows.forEach((row, index) => {
        row.querySelector('td:first-child').textContent = index + 1;
    });
}

// دالة تحديث إجمالي المخالفات
function updateTotalViolations() {
    const violationRows = document.querySelectorAll('#violationsTable tbody tr[data-violation-id]');
    let total = 0;
    
    violationRows.forEach(row => {
        const amountText = row.querySelector('.badge').textContent;
        const amount = parseFloat(amountText.replace(/[^\d.-]/g, ''));
        if (!isNaN(amount)) {
            total += amount;
        }
    });
    
    const totalElement = document.getElementById('totalViolations');
    if (totalElement) {
        if (total > 0) {
            totalElement.textContent = new Intl.NumberFormat('ar-SA', {
                style: 'currency',
                currency: 'SAR'
            }).format(total);
        } else {
            // إخفاء قسم الإجمالي إذا لم تعد هناك مخالفات
            totalElement.closest('.row').style.display = 'none';
        }
    }
}

// دالة عرض الصورة في Modal
function showImageModal(imageSrc, title) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('downloadImage').href = imageSrc;
    document.getElementById('imageModalLabel').textContent = title;
    
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}

// دالة عرض رسالة النجاح
function showSuccessMessage(message) {
    const alertHtml = `
        <div class="alert alert-success text-center alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', alertHtml);
    
    // إزالة الرسالة تلقائياً بعد 3 ثوان
    setTimeout(() => {
        const alert = document.querySelector('.alert-success');
        if (alert) {
            alert.remove();
        }
    }, 3000);
}

// دالة عرض رسالة تأكيد حفظ التاريخ
function showDateSavedMessage(savedDate) {
    const formattedDate = formatDateToArabic(new Date(savedDate));
    const dateFieldContainer = document.querySelector('#inspection_date').closest('.col-md-4');
    
    // إنشاء رسالة تأكيد الحفظ
    const savedConfirmationHtml = `
        <div class="saved-confirmation mt-2 p-3 bg-success text-white rounded shadow-sm" style="animation: pulse 2s ease-in-out;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2 fs-5"></i>
                <div>
                    <strong>تم حفظ التاريخ بنجاح!</strong>
                    <div class="small mt-1">
                        ${savedDate} - ${formattedDate}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // إضافة الرسالة أسفل حقل التاريخ
    dateFieldContainer.insertAdjacentHTML('beforeend', savedConfirmationHtml);
    
    // إزالة الرسالة تلقائياً بعد 5 ثوان
    setTimeout(() => {
        const confirmation = dateFieldContainer.querySelector('.saved-confirmation');
        if (confirmation) {
            confirmation.style.animation = 'fadeOut 0.5s ease-out';
            setTimeout(() => confirmation.remove(), 500);
        }
    }, 5000);
}

// دالة فتح كاميرا الجوال
function openCamera(inputId) {
    const input = document.getElementById(inputId);
    if (input) {
        // تغيير خاصية accept لتشمل الكاميرا
        input.setAttribute('accept', 'image/*');
        input.setAttribute('capture', 'environment'); // استخدام الكاميرا الخلفية
        input.click();
    }
}
</script>

@endsection
