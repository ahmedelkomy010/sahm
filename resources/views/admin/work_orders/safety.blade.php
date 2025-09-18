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
            بيانات السلامة
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.work-orders.update-safety', $workOrder) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- البيانات الأساسية للسلامة -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label for="safety_notes" class="form-label fw-bold">
                            <i class="fas fa-sticky-note me-2 text-info"></i>
                            ملاحظات السلامة
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
                    
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="safety_status" class="form-label fw-bold">
                                    <i class="fas fa-check-circle me-2 text-success"></i>
                                    حالة السلامة
                                </label>
                                <select class="form-select @error('safety_status') is-invalid @enderror" 
                                        name="safety_status" 
                                        id="safety_status">
                                    <option value="">اختر حالة السلامة</option>
                                    <option value="مطابق" {{ old('safety_status', $workOrder->safety_status) == 'مطابق' ? 'selected' : '' }}>مطابق</option>
                                    <option value="غير مطابق" {{ old('safety_status', $workOrder->safety_status) == 'غير مطابق' ? 'selected' : '' }}>غير مطابق</option>
                                    <option value="يحتاج مراجعة" {{ old('safety_status', $workOrder->safety_status) == 'يحتاج مراجعة' ? 'selected' : '' }}>يحتاج مراجعة</option>
                                </select>
                                @error('safety_status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
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
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="inspection_date" class="form-label fw-bold">
                                    <i class="fas fa-calendar me-2 text-warning"></i>
                                    تاريخ التفتيش
                                </label>
                                <input type="date" 
                                       class="form-control @error('inspection_date') is-invalid @enderror" 
                                       name="inspection_date" 
                                       id="inspection_date" 
                                       value="{{ old('inspection_date', $workOrder->inspection_date ? $workOrder->inspection_date->format('Y-m-d') : '') }}">
                                @error('inspection_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- حقول الصور -->
                <div class="row">
                    <!-- صور التصاريح PERMITS -->
                    <div class="col-md-6 mb-3">
                        <label for="permits_images" class="form-label fw-bold">
                            <i class="fas fa-file-contract me-2 text-warning"></i>
                            صور التصاريح PERMITS
                        </label>
                        <input type="file" class="form-control @error('permits_images.*') is-invalid @enderror" 
                               name="permits_images[]" 
                               id="permits_images"
                               accept="image/*"
                               multiple>
                        <div class="form-text">يمكن رفع عدة صور (JPG, PNG, GIF) - الحد الأقصى 10 ميجابايت لكل صورة</div>
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
                        <input type="file" class="form-control @error('team_images.*') is-invalid @enderror" 
                               name="team_images[]" 
                               id="team_images"
                               accept="image/*"
                               multiple>
                        <div class="form-text">يمكن رفع عدة صور (JPG, PNG, GIF) - الحد الأقصى 10 ميجابايت لكل صورة</div>
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
                        <input type="file" class="form-control @error('equipment_images.*') is-invalid @enderror" 
                               name="equipment_images[]" 
                               id="equipment_images"
                               accept="image/*"
                               multiple>
                        <div class="form-text">يمكن رفع عدة صور (JPG, PNG, GIF) - الحد الأقصى 10 ميجابايت لكل صورة</div>
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
                            صور عامة للسلامة
                        </label>
                        <input type="file" class="form-control @error('general_images.*') is-invalid @enderror" 
                               name="general_images[]" 
                               id="general_images"
                               accept="image/*"
                               multiple>
                        <div class="form-text">يمكن رفع عدة صور (JPG, PNG, GIF) - الحد الأقصى 10 ميجابايت لكل صورة</div>
                        @error('general_images.*')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- صور اجتماع ما قبل بدء العمل TBT -->
                    <div class="col-md-4 mb-3">
                        <label for="tbt_images" class="form-label fw-bold">
                            <i class="fas fa-users-cog me-2 text-success"></i>
                            صور اجتماع ما قبل بدء العمل TBT
                        </label>
                        <input type="file" class="form-control @error('tbt_images.*') is-invalid @enderror" 
                               name="tbt_images[]" 
                               id="tbt_images"
                               accept="image/*"
                               multiple>
                        <div class="form-text">يمكن رفع عدة صور (JPG, PNG, GIF) - الحد الأقصى 10 ميجابايت لكل صورة</div>
                        @error('tbt_images.*')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
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
                                    
                                    <!-- زر الحذف -->
                                    <button type="button" 
                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 delete-safety-image"
                                            data-work-order="{{ $workOrder->id }}"
                                            data-category="{{ $category }}"
                                            data-index="{{ $index }}"
                                            title="حذف الصورة">
                                        <i class="fas fa-trash"></i>
                                    </button>
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

    <!-- سكشن مخالفات السلامة والكهرباء -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-danger text-white d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                مخالفات السلامة والكهرباء
            </div>
            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addViolationModal">
                <i class="fas fa-plus me-1"></i>إضافة مخالفة
            </button>
        </div>
        <div class="card-body">
            <!-- نموذج إضافة مخالفة سريع -->
            <div class="row mb-4 p-3 bg-light rounded">
                <h6 class="mb-3"><i class="fas fa-plus-circle me-2 text-danger"></i>إضافة مخالفة جديدة</h6>
                <form id="addViolationForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label class="form-label fw-bold">قيمة المخالفة</label>
                            <input type="number" step="0.01" class="form-control" name="violation_amount" placeholder="0.00" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label fw-bold">المتسبب في المخالفة</label>
                            <input type="text" class="form-control" name="violator" placeholder="اسم المتسبب" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label fw-bold">تاريخ المخالفة</label>
                            <input type="date" class="form-control" name="violation_date" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label fw-bold">إجراء</label>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-plus me-1"></i>إضافة
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label fw-bold">ملاحظات</label>
                            <textarea class="form-control" name="notes" rows="2" placeholder="ملاحظات إضافية (اختياري)"></textarea>
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
                            <th>تاريخ المخالفة</th>
                            <th>ملاحظات</th>
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
                                <td>{{ $violation->violation_date->format('Y-m-d') }}</td>
                                <td>
                                    @if($violation->notes)
                                        <span class="text-muted">{{ Str::limit($violation->notes, 50) }}</span>
                                    @else
                                        <span class="text-muted fst-italic">لا توجد ملاحظات</span>
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
                                <td colspan="6" class="text-center text-muted py-4">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
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
                
                fetch(`/admin/work-orders/${workOrderId}/safety-image/${category}/${index}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
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
                                    <td colspan="6" class="text-center text-muted py-4">
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
</script>

@endsection
