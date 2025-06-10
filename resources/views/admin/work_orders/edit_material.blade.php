@extends('layouts.app')

@section('title', 'تعديل مادة')
@section('header', 'تعديل المادة')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('admin.work-orders.materials') }}" class="btn btn-outline-dark">
            <i class="fas fa-arrow-right ml-1"></i>
            العودة إلى قائمة المواد
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="h4 m-0">تعديل المادة</h2>
        </div>
        <div class="card-body">
            <form action="{{ url('admin/work-orders/materials/' . $material->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- إبقاء work_order_id كحقل مخفي للتوافق -->
                <input type="hidden" name="work_order_id" value="{{ $material->work_order_id }}">
                
                <div class="row">   
                    <div class="col-md-12 mb-3">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>أمر العمل الحالي:</strong> {{ $material->workOrder->order_number }} - {{ $material->workOrder->subscriber_name }}
                            <br><small class="text-muted">سيتم الاحتفاظ بنفس أمر العمل عند التحديث</small>
                        </div>
                        <!-- حقل مخفي للحفاظ على أمر العمل الحالي -->
                        <input type="hidden" name="work_order_id" value="{{ $material->work_order_id }}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">كود المادة</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="code" name="code" value="{{ $material->code }}" required>
                            <button type="button" class="btn btn-outline-secondary" id="search-material-btn" title="البحث عن وصف المادة">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="line" class="form-label">السطر</label>
                        <input type="text" class="form-control" id="line" name="line" value="{{ $material->line }}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">وصف المادة</label>
                        <div class="position-relative">
                            <textarea class="form-control" id="description" name="description" rows="4" required 
                                      style="min-height: 100px; resize: vertical;">{{ $material->description }}</textarea>
                            <div id="description-loader" class="position-absolute top-0 end-0 p-2" style="display: none;">
                                <i class="fas fa-spinner fa-spin text-primary"></i>
                            </div>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> يمكن البحث عن الوصف تلقائياً بكود المادة
                        </small>
                        <small class="text-success" id="description-status" style="display: none;">
                            <i class="fas fa-check-circle"></i> تم جلب الوصف تلقائياً
                        </small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="planned_quantity" class="form-label">الكمية المخططة</label>
                        <input type="number" step="0.01" class="form-control" id="planned_quantity" name="planned_quantity" value="{{ $material->planned_quantity }}" required>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="unit" class="form-label">الوحدة</label>
                        <select class="form-select" id="unit" name="unit" required>
                            <option value="">اختر الوحدة</option>
                            <option value="L.M" {{ $material->unit == 'L.M' ? 'selected' : '' }}>L.M</option>
                            <option value="Ech" {{ $material->unit == 'Ech' ? 'selected' : '' }}>Ech</option>
                            <option value="Kit" {{ $material->unit == 'Kit' ? 'selected' : '' }}>Kit</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="line" class="form-label">السطر</label>
                        <input type="text" class="form-control" id="line" name="line" value="{{ $material->line }}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="check_in_file" class="form-label">CHECK LIST</label>
                        @if($material->check_in_file)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $material->check_in_file) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-file"></i> عرض الملف الحالي
                                </a>
                            </div>
                        @endif
                        <input type="file" class="form-control" id="check_in_file" name="check_in_file">
                        <small class="form-text text-muted">اختياري: تحميل ملف جديد سيستبدل الملف الحالي</small>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="date_gatepass" class="form-label">DATE GATEPASS</label>
                        <input type="date" class="form-control" id="date_gatepass" name="date_gatepass" value="{{ $material->date_gatepass ? $material->date_gatepass->format('Y-m-d') : '' }}">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="gate_pass_file" class="form-label">GATE PASS</label>
                        @if($material->gate_pass_file)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $material->gate_pass_file) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-file"></i> عرض الملف الحالي
                                </a>
                            </div>
                        @endif
                        <input type="file" class="form-control" id="gate_pass_file" name="gate_pass_file">
                        <small class="form-text text-muted">اختياري: تحميل ملف جديد سيستبدل الملف الحالي</small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="store_in_file" class="form-label">STORE IN</label>
                        @if($material->store_in_file)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $material->store_in_file) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-file"></i> عرض الملف الحالي
                                </a>
                            </div>
                        @endif
                        <input type="file" class="form-control" id="store_in_file" name="store_in_file">
                        <small class="form-text text-muted">اختياري: تحميل ملف جديد سيستبدل الملف الحالي</small>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="store_out_file" class="form-label">STORE OUT</label>
                        @if($material->store_out_file)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $material->store_out_file) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-file"></i> عرض الملف الحالي
                                </a>
                            </div>
                        @endif
                        <input type="file" class="form-control" id="store_out_file" name="store_out_file">
                        <small class="form-text text-muted">اختياري: تحميل ملف جديد سيستبدل الملف الحالي</small>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="actual_quantity" class="form-label">الكمية المنفذة الفعلية</label>
                        <input type="number" step="0.01" class="form-control" id="actual_quantity" name="actual_quantity" value="{{ $material->actual_quantity }}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">الفرق (يحسب تلقائياً)</label>
                        <input type="text" class="form-control bg-light" value="{{ $material->difference }}" readonly>
                    </div>
                </div>
                
                <div class="text-end">
                    <a href="{{ route('admin.work-orders.materials') }}" class="btn btn-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تحديث select box أمر العمل
    const workOrderSelect = document.getElementById('work_order_id');

    // البحث عن وصف المادة
    const codeInput = document.getElementById('code');
    const descriptionTextarea = document.getElementById('description');
    const descriptionLoader = document.getElementById('description-loader');
    const descriptionStatus = document.getElementById('description-status');
    const searchBtn = document.getElementById('search-material-btn');
    
    let searchTimeout;
    
    // دالة البحث عن وصف المادة
    function searchMaterialDescription(code) {
        if (!code) return;
        
        descriptionLoader.style.display = 'block';
        descriptionStatus.style.display = 'none';
        
        fetch(`/admin/materials/description/${code}`)
            .then(response => response.json())
            .then(data => {
                descriptionLoader.style.display = 'none';
                
                if (data.description && data.description.trim()) {
                    descriptionTextarea.value = data.description;
                    descriptionStatus.style.display = 'block';
                    descriptionStatus.innerHTML = '<i class="fas fa-check-circle"></i> تم جلب الوصف تلقائياً';
                } else {
                    descriptionStatus.style.display = 'block';
                    descriptionStatus.innerHTML = '<i class="fas fa-exclamation-triangle text-warning"></i> لم يتم العثور على وصف لهذا الكود';
                    descriptionStatus.className = 'text-warning';
                }
            })
            .catch(error => {
                console.error('خطأ في جلب وصف المادة:', error);
                descriptionLoader.style.display = 'none';
                descriptionStatus.style.display = 'block';
                descriptionStatus.innerHTML = '<i class="fas fa-times-circle text-danger"></i> خطأ في جلب الوصف';
                descriptionStatus.className = 'text-danger';
            });
    }
    
    // البحث عند تغيير كود المادة
    if (codeInput) {
        codeInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const code = this.value.trim();
            
            if (code.length >= 2) {
                searchTimeout = setTimeout(() => {
                    searchMaterialDescription(code);
                }, 500);
            } else {
                descriptionLoader.style.display = 'none';
                descriptionStatus.style.display = 'none';
            }
        });
        
        codeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const code = this.value.trim();
                if (code) {
                    searchMaterialDescription(code);
                }
            }
        });
    }
    
    // البحث عند الضغط على زر البحث
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            const code = codeInput.value.trim();
            if (code) {
                searchMaterialDescription(code);
            } else {
                codeInput.focus();
                codeInput.style.borderColor = '#dc3545';
                setTimeout(() => {
                    codeInput.style.borderColor = '';
                }, 2000);
            }
        });
    }

    // حساب الفرق بين الكمية المخططة والكمية الفعلية
    function calculateDifference() {
        const plannedQuantity = parseFloat(document.getElementById('planned_quantity').value) || 0;
        const actualQuantity = parseFloat(document.getElementById('actual_quantity').value) || 0;
        const difference = plannedQuantity - actualQuantity;
        
        const diffInput = document.querySelector('.col-md-12.mb-3 input[readonly]');
        if (diffInput) {
            diffInput.value = difference.toFixed(2);
        }
    }

    // تحديث الفرق عند تغيير الكميات
    const plannedQtyInput = document.getElementById('planned_quantity');
    const actualQtyInput = document.getElementById('actual_quantity');
    
    if (plannedQtyInput) {
        plannedQtyInput.addEventListener('input', calculateDifference);
    }
    if (actualQtyInput) {
        actualQtyInput.addEventListener('input', calculateDifference);
    }

    // حساب الفرق عند تحميل الصفحة
    calculateDifference();
    
    // تحسين تجربة رفع الملفات
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const small = this.nextElementSibling;
                if (small && small.tagName === 'SMALL') {
                    small.innerHTML = `<i class="fas fa-check-circle text-success"></i> ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                }
            }
        });
    });
});
</script>
@endsection 