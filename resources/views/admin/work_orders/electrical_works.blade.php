@php($hideNavbar = true)
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0" style="font-weight:800; color:#2c3e50; letter-spacing:1px; display:flex; align-items:center;">
                <i class="fas fa-bolt me-2" style="color:#ffc107;"></i>
                أعمال الكهرباء - {{ $workOrder->order_number }}
            </h2>
            <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-outline-secondary">&larr; عودة</a>
        </div>
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
                    <form id="electrical-works-form" action="{{ route('admin.work-orders.electrical-works.store', $workOrder) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="table-light">
                                                    <tr>
                                                        <th style="width: 40%">البند</th>
                                        <th style="width: 35%">الحالة</th>
                                        <th style="width: 25%">العدد</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                    @foreach($electricalItems as $key => $label)
                                                    <tr>
                                        <td>{{ $label }}</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                <input type="radio" class="btn-check electrical-status" 
                                                       name="electrical_works[{{ $key }}][status]" 
                                                       id="{{ $key }}_completed" 
                                                       value="completed" 
                                                       {{ old('electrical_works.' . $key . '.status', isset($workOrder->electrical_works[$key]['status']) ? $workOrder->electrical_works[$key]['status'] : '') == 'completed' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-success" for="{{ $key }}_completed">تم</label>
                                                
                                                <input type="radio" class="btn-check electrical-status" 
                                                       name="electrical_works[{{ $key }}][status]" 
                                                       id="{{ $key }}_pending" 
                                                       value="pending" 
                                                       {{ old('electrical_works.' . $key . '.status', isset($workOrder->electrical_works[$key]['status']) ? $workOrder->electrical_works[$key]['status'] : '') == 'pending' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-warning" for="{{ $key }}_pending">قيد التنفيذ</label>
                                                
                                                <input type="radio" class="btn-check electrical-status" 
                                                       name="electrical_works[{{ $key }}][status]" 
                                                       id="{{ $key }}_na" 
                                                       value="na" 
                                                       {{ old('electrical_works.' . $key . '.status', isset($workOrder->electrical_works[$key]['status']) ? $workOrder->electrical_works[$key]['status'] : '') == 'na' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-secondary" for="{{ $key }}_na">لا ينطبق</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                            <input type="number" 
                                                   step="1" 
                                                   min="0" 
                                                   class="form-control form-control-sm electrical-quantity" 
                                                   name="electrical_works[{{ $key }}][quantity]" 
                                                   value="{{ old('electrical_works.' . $key . '.quantity', isset($workOrder->electrical_works[$key]['quantity']) && $workOrder->electrical_works[$key]['quantity'] !== null ? $workOrder->electrical_works[$key]['quantity'] : '') }}" 
                                                   placeholder="0" 
                                                   data-item="{{ $key }}">
                                                        </td>
                                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                                                            </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-warning px-4">
                                <i class="fas fa-save me-2"></i>
                                حفظ الأعمال الكهربائية
                            </button>
                        </div>
                        
                        <div id="auto-save-indicator" class="text-center mt-2" style="display: none;">
                            <small class="text-success">
                                <i class="fas fa-check me-1"></i>
                                تم الحفظ التلقائي
                            </small>
                        </div>
                        
                        @if($workOrder->electrical_works && count($workOrder->electrical_works) > 0)
                            <div class="alert alert-info mt-3 text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                تم استرداد البيانات المحفوظة سابقاً - يمكنك تعديلها وحفظها مرة أخرى
                            </div>
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
                            <div class="card bg-success text-white">
                                <div class="card-body text-center p-3">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <h3 class="mb-1" id="total-completed">0</h3>
                                    <small>تم التنفيذ</small>
                                                                </div>
                                                            </div>
                                                            </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-dark">
                                <div class="card-body text-center p-3">
                                    <i class="fas fa-clock fa-2x mb-2"></i>
                                    <h3 class="mb-1" id="total-pending">0</h3>
                                    <small>قيد التنفيذ</small>
                                                                </div>
                                                            </div>
                                                            </div>
                        <div class="col-md-4">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center p-3">
                                    <i class="fas fa-minus-circle fa-2x mb-2"></i>
                                    <h3 class="mb-1" id="total-na">0</h3>
                                    <small>لا ينطبق</small>
                                                                </div>
                                                            </div>
                                                                </div>
                                                            </div>

                    <!-- جدول الملخص -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="summary-table">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 40%">البند</th>
                                    <th style="width: 35%">الحالة</th>
                                    <th style="width: 25%">العدد</th>
                                                    </tr>
                            </thead>
                            <tbody id="summary-tbody">
                                <!-- سيتم ملء البيانات تلقائياً بواسطة JavaScript -->
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="2" class="text-start fw-bold">إجمالي البنود المنفذة:</td>
                                    <td class="text-center fw-bold" id="total-items">0</td>
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
<script>
// المتغيرات العامة
let autoSaveTimeout;



// تهيئة الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // إعداد الحفظ التلقائي
    setupAutoSave();
    
    // إعداد الحفظ اليدوي
    const form = document.getElementById('electrical-works-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // التحقق من أن النموذج جاهز للإرسال
            console.log('Form submitting...');
        });
    }
    
    // تحديث الملخص عند تغيير أي حقل
    document.querySelectorAll('.electrical-status, .electrical-quantity').forEach(input => {
        input.addEventListener('change', updateElectricalWorksSummary);
    });
    
    // إعداد عرض الصور
    setupElectricalImagesViewers();
    
    // إعداد رفع الصور
    setupElectricalImagesUpload();
    
    // تحديث أولي للملخص
    updateElectricalWorksSummary();
});

// إعداد عارضات الصور للأعمال الكهربائية
function setupElectricalImagesViewers() {
    // عرض صورة في النافذة المنبثقة
    const viewElectricalImageButtons = document.querySelectorAll('.view-electrical-image');
    viewElectricalImageButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const imageUrl = this.dataset.imageUrl;
            const imageName = this.dataset.imageName;
            const imageDate = this.dataset.imageDate;
            
            document.getElementById('modalElectricalImage').src = imageUrl;
            document.getElementById('modalElectricalImageName').textContent = imageName;
            document.getElementById('modalElectricalImageDate').textContent = imageDate;
        });
    });

    // حذف صورة
    const deleteElectricalButtons = document.querySelectorAll('.delete-electrical-image');
    deleteElectricalButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const imageId = this.dataset.imageId;
            const form = document.getElementById('deleteElectricalImageForm');
            const baseUrl = '{{ url("admin/work-orders/" . $workOrder->id . "/electrical-works/images") }}';
            form.action = `${baseUrl}/${imageId}`;
        });
    });
}

// إعداد رفع صور الأعمال الكهربائية
function setupElectricalImagesUpload() {
    const form = document.getElementById('electrical-images-form');
    const button = document.getElementById('upload-electrical-images-btn');
    const fileInput = document.getElementById('electrical_works_images');
    
    if (form && button && fileInput) {
        form.addEventListener('submit', function(e) {
            if (fileInput.files.length === 0) {
                e.preventDefault();
                alert('يرجى اختيار صور للرفع');
                return;
            }
            
            // تغيير نص الزر أثناء الرفع
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الرفع...';
            button.disabled = true;
        });
        
                    // إعادة تعيين الزر في حالة الخطأ
            form.addEventListener('error', function() {
                button.innerHTML = '<i class="fas fa-upload me-2"></i>رفع الصور';
                button.disabled = false;
            });
        }
        
        // معاينة الصور المختارة
        fileInput.addEventListener('change', function() {
            const preview = document.getElementById('electrical-images-preview');
            const container = document.getElementById('electrical-preview-container');
            
            container.innerHTML = '';
            
            if (this.files.length > 0) {
                preview.style.display = 'block';
                
                Array.from(this.files).slice(0, 10).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const col = document.createElement('div');
                            col.className = 'col-6 col-md-4 col-lg-3';
                            col.innerHTML = `
                                <div class="card">
                                    <img src="${e.target.result}" class="card-img-top" style="height: 100px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <small class="text-muted text-truncate d-block">${file.name}</small>
                                    </div>
                                </div>
                            `;
                            container.appendChild(col);
                        };
                        reader.readAsDataURL(file);
                    }
                });
                
                if (this.files.length > 10) {
                    const moreCol = document.createElement('div');
                    moreCol.className = 'col-6 col-md-4 col-lg-3';
                    moreCol.innerHTML = `
                        <div class="card bg-light d-flex align-items-center justify-content-center" style="height: 140px;">
                            <div class="text-center">
                                <i class="fas fa-plus fa-2x text-muted mb-2"></i>
                                <small class="text-muted">+${this.files.length - 10} صور أخرى</small>
                            </div>
                        </div>
                    `;
                    container.appendChild(moreCol);
                }
            } else {
                preview.style.display = 'none';
            }
        });
    }

// إعداد الحفظ التلقائي
function setupAutoSave() {
    const form = document.getElementById('electrical-works-form');
    if (!form) return;
    
    form.addEventListener('change', function(e) {
        if (e.target.classList.contains('electrical-status') || 
            e.target.classList.contains('electrical-quantity')) {
            
            // إلغاء التايمر السابق
            clearTimeout(autoSaveTimeout);
            
            // تأخير الحفظ لثانية واحدة
            autoSaveTimeout = setTimeout(() => {
                autoSaveElectricalWorks();
            }, 1000);
        }
    });
}

// دالة الحفظ التلقائي
function autoSaveElectricalWorks() {
    const form = document.getElementById('electrical-works-form');
    if (!form) return;
    
    const formData = new FormData(form);
    
    // التأكد من إرسال جميع قيم quantity بشكل صحيح
    document.querySelectorAll('input[type="number"].electrical-quantity').forEach(input => {
        const name = input.name;
        const value = input.value || '';
        
        // تحديث القيمة في FormData مع القيمة الفعلية من الحقل
        formData.set(name, value);
        
        console.log('Sending quantity:', name, '=', value);
    });
    
    // التأكد من إرسال جميع قيم status
    document.querySelectorAll('input[type="radio"].electrical-status:checked').forEach(input => {
        const name = input.name;
        const value = input.value;
        
        formData.set(name, value);
        console.log('Sending status:', name, '=', value);
    });
    
    // طباعة جميع البيانات المرسلة للتشخيص
    console.log('FormData contents:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    
    fetch('{{ route("admin.work-orders.electrical-works.store.post", $workOrder) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAutoSaveIndicator();
            // تحديث الملخص بعد الحفظ
            updateElectricalWorksSummary();
        } else {
            console.error('Auto-save failed:', data.message);
        }
    })
    .catch(error => {
        console.error('Auto-save error:', error);
    });
}

// عرض مؤشر الحفظ التلقائي
function showAutoSaveIndicator() {
    const indicator = document.getElementById('auto-save-indicator');
    if (indicator) {
        indicator.style.display = 'block';
        setTimeout(() => {
            indicator.style.display = 'none';
        }, 2000);
    }
}

// دالة تحديث ملخص الأعمال الكهربائية
function updateElectricalWorksSummary() {
    const tbody = document.getElementById('summary-tbody');
    const totalCompleted = document.getElementById('total-completed');
    const totalPending = document.getElementById('total-pending');
    const totalNA = document.getElementById('total-na');
    const totalItems = document.getElementById('total-items');
    
    if (!tbody) return;
    
    tbody.innerHTML = '';
    let completed = 0;
    let pending = 0;
    let na = 0;
    let totalQuantity = 0;
    
    // جمع البيانات من النموذج
    document.querySelectorAll('tr').forEach(row => {
        const label = row.querySelector('td')?.textContent?.trim();
        if (!label) return;
        
        const status = row.querySelector('input[type="radio"]:checked')?.value;
        const quantityValue = row.querySelector('input[type="number"]')?.value;
        const quantity = quantityValue && quantityValue !== '' ? parseInt(quantityValue) : 0;
        
        if (status) {
            // إضافة صف للجدول
            const newRow = tbody.insertRow();
            newRow.innerHTML = `
                <td>${label}</td>
                <td class="text-center">
                    ${getStatusBadge(status)}
                </td>
                <td class="text-center">
                    <span class="badge bg-primary rounded-pill">${quantity}</span>
                </td>
            `;
            
            // تحديث الإحصائيات
            if (status === 'completed') {
                completed++;
                totalQuantity += quantity;
            }
            else if (status === 'pending') pending++;
            else if (status === 'na') na++;
        }
    });
    
    // تحديث الإحصائيات
    totalCompleted.textContent = completed;
    totalPending.textContent = pending;
    totalNA.textContent = na;
    totalItems.textContent = totalQuantity;
    
    // إضافة رسالة إذا لم تكن هناك بيانات
    if (tbody.children.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="3" class="text-center text-muted py-4">
                    <i class="fas fa-info-circle fa-2x mb-2"></i><br>
                    لم يتم إدخال أي بيانات بعد
                </td>
            </tr>
        `;
    }
}

// دالة إرجاع شارة الحالة
function getStatusBadge(status) {
    switch(status) {
        case 'completed':
            return '<span class="badge bg-success">تم التنفيذ</span>';
        case 'pending':
            return '<span class="badge bg-warning text-dark">قيد التنفيذ</span>';
        case 'na':
            return '<span class="badge bg-secondary">لا ينطبق</span>';
        default:
            return '';
    }
}

// دالة طباعة الملخص
function printSummary() {
    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
        <html dir="rtl">
            <head>
                <title>ملخص الأعمال الكهربائية</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { font-family: Arial, sans-serif; }
                    .table th, .table td { padding: 8px; }
                    @media print {
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body class="p-4">
                <div class="text-center mb-4">
                    <h3>ملخص الأعمال الكهربائية</h3>
                    <p class="text-muted">تاريخ الطباعة: ${new Date().toLocaleDateString('ar-SA')}</p>
                </div>
                ${document.getElementById('summary-table').outerHTML}
                <div class="row mt-4">
                    <div class="col-4 text-center">
                        <h5>تم التنفيذ: ${document.getElementById('total-completed').textContent}</h5>
                    </div>
                    <div class="col-4 text-center">
                        <h5>قيد التنفيذ: ${document.getElementById('total-pending').textContent}</h5>
                    </div>
                    <div class="col-4 text-center">
                        <h5>لا ينطبق: ${document.getElementById('total-na').textContent}</h5>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <h5>إجمالي البنود المنفذة: ${document.getElementById('total-items').textContent}</h5>
                </div>
                <button class="btn btn-primary mt-4 no-print" onclick="window.print()">طباعة</button>
            </body>
        </html>
    `);
    printWindow.document.close();
}
</script>
@endpush

@endsection 