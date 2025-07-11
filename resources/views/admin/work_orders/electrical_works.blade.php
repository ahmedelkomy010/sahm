@php($hideNavbar = true)
@extends('layouts.app')

@section('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .highlight {
        animation: highlight-animation 0.5s ease-in-out;
    }
    
    @keyframes highlight-animation {
        0% {
            background-color: rgba(255, 193, 7, 0.2);
        }
        100% {
            background-color: transparent;
        }
    }
    
    .electrical-total {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    
    .form-control:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0" style="font-weight:800; color:#2c3e50; letter-spacing:1px; display:flex; align-items:center;">
                <i class="fas fa-bolt me-2" style="color:#ffc107;"></i>
                أعمال الكهرباء - {{ $workOrder->order_number }}
            </h2>
            <a href="{{ route('admin.work-orders.execution', $workOrder) }}" 
               class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>عودة للتنفيذ
            </a>        </div>
    </div>
    <div class="row g-4">
        <!-- نموذج بيانات الأعمال الكهربائية -->
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-list-ul me-2"></i>
                    بيانات الأعمال الكهربائية
                </div>
                <div class="card-body">
                    <form id="electrical-works-form" action="{{ route('admin.work-orders.electrical-works.post', $workOrder) }}" method="POST">
                        @csrf
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 35%">البند</th>
                                        <th style="width: 25%">الطول</th>
                                        <th style="width: 20%">السعر</th>
                                        <th style="width: 20%">الإجمالي</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($electricalItems as $key => $label)
                                    <tr>
                                        <td>{{ $label }}</td>
                                        <td>
                                            <input type="number" 
                                                   step="1" 
                                                   min="0" 
                                                   class="form-control form-control-sm electrical-length" 
                                                   name="electrical_works[{{ $key }}][length]" 
                                                   value="{{ old('electrical_works.' . $key . '.length', isset($workOrder->electrical_works[$key]['length']) ? $workOrder->electrical_works[$key]['length'] : '') }}" 
                                                   placeholder="0" 
                                                   data-item="{{ $key }}">
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0" 
                                                   class="form-control form-control-sm electrical-price" 
                                                   name="electrical_works[{{ $key }}][price]" 
                                                   value="{{ old('electrical_works.' . $key . '.price', isset($workOrder->electrical_works[$key]['price']) ? $workOrder->electrical_works[$key]['price'] : '') }}" 
                                                   placeholder="0.00" 
                                                   data-item="{{ $key }}">
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   class="form-control form-control-sm electrical-total" 
                                                   name="electrical_works[{{ $key }}][total]" 
                                                   value="{{ old('electrical_works.' . $key . '.total', isset($workOrder->electrical_works[$key]['total']) ? $workOrder->electrical_works[$key]['total'] : '') }}" 
                                                   readonly>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                                                            </div>
                        
                        
                        <div id="auto-save-indicator" class="text-center mt-2" style="display: none;">
                            <small class="text-success">
                                <i class="fas fa-check me-1"></i>
                                تم الحفظ التلقائي
                            </small>
                        </div>
                        
                        <!-- أزرار الحفظ والإجراءات -->
                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-success px-4" onclick="saveElectricalWorks()">
                                <i class="fas fa-save me-2"></i>
                                حفظ البيانات
                            </button>
                            <button type="button" class="btn btn-info px-4 ms-2" onclick="updateAll()">
                                <i class="fas fa-calculator me-2"></i>
                                إعادة حساب الإجماليات
                            </button>
                        </div>
                        
                        @if($workOrder->electrical_works && count($workOrder->electrical_works) > 0)
                            
                        @endif
                        
                        <!-- تشخيص مؤقت -->
                       
                    </form>
                </div>
                                                            </div>
                                                            </div>

        <!-- جدول ملخص الأعمال الكهربائية -->
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #ff9f43 0%, #ff6b6b 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>
                            ملخص الأعمال الكهربائية
                        </h5>
                        <button type="button" class="btn btn-light btn-sm" onclick="printSummary()">
                            <i class="fas fa-print me-1"></i>
                            طباعة الملخص
                        </button>
                                                                </div>
                                                            </div>
                <div class="card-body">
                    <!-- إحصائيات سريعة -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center p-3">
                                    <i class="fas fa-ruler fa-2x mb-2"></i>
                                    <h3 class="mb-1" id="total-length">0</h3>
                                    <small>إجمالي الأطوال</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center p-3">
                                    <i class="fas fa-tasks fa-2x mb-2"></i>
                                    <h3 class="mb-1" id="total-items">0</h3>
                                    <small>عدد البنود</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-dark">
                                <div class="card-body text-center p-3">
                                    <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                                    <h3 class="mb-1" id="total-cost">0.00</h3>
                                    <small>التكلفة الإجمالية</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- جدول الملخص -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="summary-table">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 35%">البند</th>
                                    <th style="width: 25%">الطول</th>
                                    <th style="width: 20%">السعر</th>
                                    <th style="width: 20%">الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody id="summary-tbody">
                                <!-- سيتم ملء البيانات تلقائياً بواسطة JavaScript -->
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-start fw-bold">الإجمالي الكلي:</td>
                                    <td class="text-center fw-bold" id="total-amount">0.00</td>
                                </tr>
                            </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

        <!-- قسم رفع صور الأعمال الكهربائية -->
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-images me-2"></i>
                    صور الأعمال الكهربائية
                </div>
                <div class="card-body">
                    <form id="electrical-images-form" action="{{ route('admin.work-orders.electrical-works.images', $workOrder) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="electrical_works_images" class="form-label">اختر الصور</label>
                            <input type="file" class="form-control" id="electrical_works_images" name="electrical_works_images[]" multiple accept="image/*">
                            <div class="form-text">يمكنك اختيار عدة صور (حتى 70 صورة، كل صورة حتى 30 ميجا)</div>
                            <div id="electrical-images-preview" class="mt-3" style="display: none;">
                                <h6>معاينة الصور المختارة:</h6>
                                <div class="row g-2" id="electrical-preview-container"></div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-info px-4" id="upload-electrical-images-btn">
                                <i class="fas fa-upload me-2"></i>
                                رفع الصور
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- عرض صور الأعمال الكهربائية المرفوعة -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-images me-2"></i>
                        صور الأعمال الكهربائية المرفوعة
                        @if(isset($electricalWorksImages) && $electricalWorksImages->count() > 0)
                            <span class="badge bg-dark ms-2">{{ $electricalWorksImages->count() }}</span>
                        @endif
                    </div>
                    @if(isset($electricalWorksImages) && $electricalWorksImages->count() > 0)
                        <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#viewAllElectricalImagesModal">
                            <i class="fas fa-expand-alt me-1"></i>
                            عرض جميع الصور
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    @if(isset($electricalWorksImages) && $electricalWorksImages->count() > 0)
                        <div class="row g-3">
                            @foreach($electricalWorksImages as $image)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="card h-100 position-relative">
                                        <button type="button" 
                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 delete-electrical-image" 
                                                data-image-id="{{ $image->id }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteElectricalImageModal"
                                                style="z-index: 1;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <a href="#" class="text-decoration-none view-electrical-image" 
                                           data-bs-toggle="modal" 
                                           data-bs-target="#viewElectricalImageModal"
                                           data-image-url="{{ Storage::url($image->file_path) }}"
                                           data-image-name="{{ $image->original_filename }}"
                                           data-image-date="{{ \Carbon\Carbon::parse($image->created_at)->format('Y-m-d H:i') }}">
                                            <img src="{{ Storage::url($image->file_path) }}" 
                                                 class="card-img-top" 
                                                 alt="{{ $image->original_filename }}"
                                                 style="height: 200px; object-fit: cover;">
                                            <div class="card-body p-2">
                                                <p class="card-text small text-muted mb-0 text-truncate">
                                                    {{ $image->original_filename }}
                                                </p>
                                                <p class="card-text small text-muted mb-0">
                                                    {{ \Carbon\Carbon::parse($image->created_at)->format('Y-m-d H:i') }}
                                                </p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-images fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted mb-2">لم يتم رفع أي صور بعد</h5>
                            <p class="text-muted">استخدم النموذج أعلاه لرفع صور الأعمال الكهربائية</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal لعرض صورة واحدة للأعمال الكهربائية -->
    <div class="modal fade" id="viewElectricalImageModal" tabindex="-1" aria-labelledby="viewElectricalImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewElectricalImageModalLabel">عرض صورة الأعمال الكهربائية</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalElectricalImage" class="img-fluid" alt="" style="max-height: 80vh;">
                    <div class="mt-3">
                        <p class="mb-1"><strong>اسم الملف:</strong> <span id="modalElectricalImageName"></span></p>
                        <p class="text-muted small mb-0"><strong>تاريخ الرفع:</strong> <span id="modalElectricalImageDate"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal لعرض جميع صور الأعمال الكهربائية -->
    <div class="modal fade" id="viewAllElectricalImagesModal" tabindex="-1" aria-labelledby="viewAllElectricalImagesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewAllElectricalImagesModalLabel">جميع صور الأعمال الكهربائية</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        @if(isset($electricalWorksImages))
                            @foreach($electricalWorksImages as $image)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="card h-100">
                                        <img src="{{ Storage::url($image->file_path) }}" 
                                             class="card-img-top" 
                                             alt="{{ $image->original_filename }}"
                                             style="height: 200px; object-fit: cover; cursor: pointer;"
                                             onclick="document.getElementById('modalElectricalImage').src = this.src; document.getElementById('modalElectricalImageName').textContent = '{{ $image->original_filename }}'; document.getElementById('modalElectricalImageDate').textContent = '{{ \Carbon\Carbon::parse($image->created_at)->format('Y-m-d H:i') }}'; new bootstrap.Modal(document.getElementById('viewElectricalImageModal')).show();">
                                        <div class="card-body p-2">
                                            <p class="card-text small text-muted mb-0 text-truncate">
                                                {{ $image->original_filename }}
                                            </p>
                                            <p class="card-text small text-muted mb-0">
                                                {{ \Carbon\Carbon::parse($image->created_at)->format('Y-m-d H:i') }}
                                            </p>
                                            <p class="card-text small text-muted mb-0">
                                                {{ round($image->file_size / 1024 / 1024, 2) }} MB
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal لحذف صور الأعمال الكهربائية -->
    <div class="modal fade" id="deleteElectricalImageModal" tabindex="-1" aria-labelledby="deleteElectricalImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteElectricalImageModalLabel">تأكيد حذف الصورة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h5>تأكيد حذف الصورة</h5>
                        <p class="text-muted">هل أنت متأكد من حذف هذه الصورة؟ لا يمكن التراجع عن هذا الإجراء.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <form id="deleteElectricalImageForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-danger">حذف</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/simple-electrical.js') }}"></script>
@endpush

@endsection 