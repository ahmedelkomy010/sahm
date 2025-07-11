@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 fs-4">المسح</h3>
                    <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-success">
                            <i class="fas fa-arrow-right"></i> عودة الي تفاصيل أمر العمل  
                        </a>
                </div>

                <div class="card-body p-4">
                    <!-- معلومات أمر العمل -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card bg-light border-0">
                                <div class="card-body py-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-hashtag text-primary me-2"></i>
                                                <div>
                                                    <small class="text-muted d-block">رقم الطلب</small>
                                                    <strong class="text-primary">{{ $workOrder->order_number }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-tools text-warning me-2"></i>
                                                <div>
                                                    <small class="text-muted d-block">نوع العمل</small>
                                                    <strong>{{ $workOrder->work_type }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user text-info me-2"></i>
                                                <div>
                                                    <small class="text-muted d-block">اسم المشترك</small>
                                                    <strong>{{ $workOrder->subscriber_name }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-map-marked-alt text-success me-2"></i>
                                                <div>
                                                    <small class="text-muted d-block">عدد مرات المسح </small>
                                                    <strong class="text-success">{{ $workOrder->surveys->count() }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-end mb-4">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSurveyModal" onclick="resetSurveyForm()">
                            <i class="fas fa-plus me-1"></i> إنشاء مسح جديد
                        </button>
                    </div>

                    <!-- Create/Edit Survey Modal -->
                    <div class="modal fade" id="createSurveyModal" tabindex="-1" aria-labelledby="createSurveyModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createSurveyModalLabel">إنشاء مسح جديد</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="surveyForm" action="{{ route('admin.work-orders.survey.store', $workOrder) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="survey_id" id="survey_id">
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="start_coordinates" class="form-label fw-bold">إحداثيات البداية <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control @error('start_coordinates') is-invalid @enderror" id="start_coordinates" name="start_coordinates" value="{{ old('start_coordinates') }}" required placeholder="أدخل رابط إحداثيات نقطة البداية">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="copyCoordinates('start_coordinates')">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                                @error('start_coordinates')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label for="end_coordinates" class="form-label fw-bold">إحداثيات النهاية <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control @error('end_coordinates') is-invalid @enderror" id="end_coordinates" name="end_coordinates" value="{{ old('end_coordinates') }}" required placeholder="أدخل رابط إحداثيات نقطة النهاية">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="copyCoordinates('end_coordinates')">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                                @error('end_coordinates')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">المعوقات <span class="text-danger">*</span></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="has_obstacles" id="obstacles_yes" value="1" {{ old('has_obstacles') == 1 ? 'checked' : '' }} required onchange="toggleObstaclesNotes()">
                                                    <label class="form-check-label" for="obstacles_yes">
                                                        نعم
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="has_obstacles" id="obstacles_no" value="0" {{ old('has_obstacles') == 0 ? 'checked' : '' }} required onchange="toggleObstaclesNotes()">
                                                    <label class="form-check-label" for="obstacles_no">
                                                        لا
                                                    </label>
                                                </div>
                                                @error('has_obstacles')
                                                    <div class="invalid-feedback d-block">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6" id="obstacles_notes_container" style="display: none;">
                                                <label for="obstacles_notes" class="form-label fw-bold">ملاحظات المعوقات <span class="text-danger">*</span></label>
                                                <textarea class="form-control @error('obstacles_notes') is-invalid @enderror" id="obstacles_notes" name="obstacles_notes" rows="3" placeholder="يرجى وصف المعوقات الموجودة...">{{ old('obstacles_notes') }}</textarea>
                                                @error('obstacles_notes')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <div class="form-text text-muted">
                                                    <i class="fas fa-info-circle"></i> يرجى تحديد نوع المعوقات وتفاصيلها
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="site_images" class="form-label fw-bold">صور الموقع</label>
                                            <input type="file" class="form-control @error('site_images.*') is-invalid @enderror" id="site_images" name="site_images[]" multiple accept="image/*">
                                            <div class="form-text">
                                                <i class="fas fa-info-circle"></i> الحد الأقصى لحجم كل صورة هو 30 ميجابايت. الصيغ المدعومة: JPG, JPEG, PNG
                                            </div>
                                            @error('site_images.*')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <!-- Preview of existing images -->
                                        <div id="existingImages" class="row g-3 mb-3" style="display: none;">
                                            <div class="col-12">
                                                <label class="form-label fw-bold">الصور الحالية</label>
                                            </div>
                                            <div id="imagesContainer" class="row g-3">
                                                <!-- سيتم إضافة الصور هنا ديناميكياً -->
                                            </div>
                                        </div>

                                        <!-- Modal for viewing images -->
                                        @forelse($workOrder->surveys as $survey)
                                            <div class="modal fade" id="viewImagesModal{{ $survey->id }}" tabindex="-1" aria-labelledby="viewImagesModalLabel{{ $survey->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="viewImagesModalLabel{{ $survey->id }}">صور المسح</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row g-3">
                                                                @forelse($survey->files as $file)
                                                                    @php
                                                                        $imageUrl = \App\Helpers\FileHelper::getImageUrl($file->file_path);
                                                                    @endphp
                                                                    @if($imageUrl)
                                                                        <div class="col-md-4">
                                                                            <div class="card h-100">
                                                                                <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $file->original_filename }}" style="height: 200px; object-fit: cover;">
                                                                                <div class="card-body">
                                                                                    <p class="card-text small">{{ $file->original_filename }}</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @empty
                                                                    <div class="col-12">
                                                                        <div class="alert alert-info mb-0">
                                                                            <i class="fas fa-info-circle me-2"></i>
                                                                            لا توجد صور لهذا المسح
                                                                        </div>
                                                                    </div>
                                                                @endforelse
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                        @endforelse
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> حفظ المسح
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">نتائج المسح</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>إحداثيات البداية</th>
                                            <th>إحداثيات النهاية</th>
                                            <th>المعوقات</th>
                                            <th>الملاحظات</th>
                                            <th>عدد الصور</th>
                                            <th>تاريخ المسح</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody id="surveyTableBody">
                                        @forelse($workOrder->surveys as $index => $survey)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    @if($survey->start_coordinates)
                                                        <div class="d-flex align-items-center">
                                                            <a href="{{ $survey->start_coordinates }}" target="_blank" class="text-primary me-2">
                                                                <i class="fas fa-map-marker-alt"></i> عرض الإحداثيات
                                                            </a>
                                                            <button class="btn btn-sm btn-outline-secondary" onclick="copyCoordinates('{{ $survey->start_coordinates }}')">
                                                                <i class="fas fa-copy"></i>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">غير متوفر</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($survey->end_coordinates)
                                                        <div class="d-flex align-items-center">
                                                            <a href="{{ $survey->end_coordinates }}" target="_blank" class="text-primary me-2">
                                                                <i class="fas fa-map-marker-alt"></i> عرض الإحداثيات
                                                            </a>
                                                            <button class="btn btn-sm btn-outline-secondary" onclick="copyCoordinates('{{ $survey->end_coordinates }}')">
                                                                <i class="fas fa-copy"></i>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">غير متوفر</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($survey->has_obstacles)
                                                        <span class="badge bg-danger">نعم</span>
                                                    @else
                                                        <span class="badge bg-success">لا</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($survey->obstacles_notes)
                                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $survey->obstacles_notes }}">
                                                            {{ $survey->obstacles_notes }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">لا توجد ملاحظات</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ $survey->files->count() }}
                                                    </span>
                                                </td>
                                                <td>{{ $survey->created_at->format('Y-m-d H:i') }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewImagesModal{{ $survey->id }}">
                                                            <i class="fas fa-images"></i> عرض الصور
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-primary" onclick="editSurvey({{ $survey->id }})">
                                                            <i class="fas fa-edit"></i> تعديل
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal for viewing images -->
                                            <div class="modal fade" id="viewImagesModal{{ $survey->id }}" tabindex="-1" aria-labelledby="viewImagesModalLabel{{ $survey->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="viewImagesModalLabel{{ $survey->id }}">صور المسح</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row g-3">
                                                                @forelse($survey->files as $file)
                                                                    @php
                                                                        $imageUrl = \App\Helpers\FileHelper::getImageUrl($file->file_path);
                                                                    @endphp
                                                                    @if($imageUrl)
                                                                        <div class="col-md-4">
                                                                            <div class="card h-100">
                                                                                <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $file->original_filename }}" style="height: 200px; object-fit: cover;">
                                                                                <div class="card-body">
                                                                                    <p class="card-text small">{{ $file->original_filename }}</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @empty
                                                                    <div class="col-12">
                                                                        <div class="alert alert-info mb-0">
                                                                            <i class="fas fa-info-circle me-2"></i>
                                                                            لا توجد صور لهذا المسح
                                                                        </div>
                                                                    </div>
                                                                @endforelse
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">لا توجد عمليات مسح مسجلة</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyCoordinates(coordinates) {
    navigator.clipboard.writeText(coordinates).then(() => {
        alert('تم نسخ الإحداثيات بنجاح');
    }).catch(err => {
        console.error('فشل نسخ الإحداثيات:', err);
    });
}

function toggleObstaclesNotes() {
    const obstaclesNotesContainer = document.getElementById('obstacles_notes_container');
    const obstaclesNotesField = document.getElementById('obstacles_notes');
    
    if (document.querySelector('input[name="has_obstacles"]:checked')?.value === '1') {
        obstaclesNotesContainer.style.display = 'block';
        obstaclesNotesField.required = true;
    } else {
        obstaclesNotesContainer.style.display = 'none';
        obstaclesNotesField.required = false;
        obstaclesNotesField.value = ''; // مسح المحتوى عند اختيار "لا"
    }
}

// تشغيل الوظيفة عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    toggleObstaclesNotes();
    
    // إضافة event listener لحقل الملاحظات لإزالة رسالة الخطأ عند الكتابة
    const obstaclesNotesField = document.getElementById('obstacles_notes');
    obstaclesNotesField.addEventListener('input', function() {
        this.classList.remove('is-invalid');
        // إزالة أي رسائل خطأ موجودة
        const errorAlerts = document.querySelectorAll('.modal-body .alert-danger');
        errorAlerts.forEach(alert => {
            if (alert.textContent.includes('ملاحظات المعوقات')) {
                alert.remove();
            }
        });
    });
});

// Function to reset survey form
function resetSurveyForm() {
    // Reset form fields
    document.getElementById('surveyForm').reset();
    // Clear survey_id to ensure we're creating a new record, not updating
    document.getElementById('survey_id').value = '';
    // Hide existing images section
    document.getElementById('existingImages').style.display = 'none';
    // Update modal title
    document.getElementById('createSurveyModalLabel').textContent = 'إنشاء مسح جديد';
    // إخفاء حقل الملاحظات عند إعادة تعيين النموذج
    document.getElementById('obstacles_notes_container').style.display = 'none';
}

function editSurvey(surveyId) {
    // إظهار مؤشر التحميل
    const loadingAlert = document.createElement('div');
    loadingAlert.className = 'alert alert-info';
    loadingAlert.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري تحميل بيانات المسح...';
    document.querySelector('.card-body').prepend(loadingAlert);

    // Get survey data
    fetch(`/admin/work-orders/survey/${surveyId}/edit`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        // إزالة مؤشر التحميل
        loadingAlert.remove();

        if (data.success) {
            // تحديث عنوان النموذج
            document.getElementById('createSurveyModalLabel').textContent = 'تعديل المسح';
            
            // Fill form with survey data
            document.getElementById('survey_id').value = data.survey.id;
            document.getElementById('start_coordinates').value = data.survey.start_coordinates;
            document.getElementById('end_coordinates').value = data.survey.end_coordinates;
            
            // تحديث حالة المعوقات
            const hasObstacles = data.survey.has_obstacles ? '1' : '0';
            document.querySelector(`input[name="has_obstacles"][value="${hasObstacles}"]`).checked = true;
            document.getElementById('obstacles_notes').value = data.survey.obstacles_notes || '';
            
            // تحديث عرض حقل الملاحظات بناءً على حالة المعوقات
            toggleObstaclesNotes();
            
            // عرض الصور الحالية
            const existingImages = document.getElementById('existingImages');
            const imagesContainer = document.getElementById('imagesContainer');
            imagesContainer.innerHTML = '';
            
            if (data.survey.images && data.survey.images.length > 0) {
                data.survey.images.forEach(image => {
                    const col = document.createElement('div');
                    col.className = 'col-md-4';
                    col.innerHTML = `
                        <div class="card h-100">
                            <img src="${image.url}" class="card-img-top" alt="${image.name}" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <p class="card-text small">${image.name}</p>
                            </div>
                        </div>
                    `;
                    imagesContainer.appendChild(col);
                });
                existingImages.style.display = 'block';
            } else {
                existingImages.style.display = 'none';
            }
            
            // فتح النموذج
            const modal = new bootstrap.Modal(document.getElementById('createSurveyModal'));
            modal.show();
        } else {
            throw new Error(data.message || 'حدث خطأ أثناء تحميل بيانات المسح');
        }
    })
    .catch(error => {
        // إزالة مؤشر التحميل
        loadingAlert.remove();
        
        // عرض رسالة الخطأ
        const errorAlert = document.createElement('div');
        errorAlert.className = 'alert alert-danger alert-dismissible fade show';
        errorAlert.innerHTML = `
            <i class="fas fa-exclamation-triangle me-2"></i>
            ${error.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.querySelector('.card-body').prepend(errorAlert);
        
        // إخفاء رسالة الخطأ بعد 5 ثواني
        setTimeout(() => {
            if (errorAlert.parentNode) {
                errorAlert.remove();
            }
        }, 5000);
    });
}

// Handle form submission
document.getElementById('surveyForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // التحقق من صحة البيانات
    const hasObstacles = document.querySelector('input[name="has_obstacles"]:checked');
    const obstaclesNotes = document.getElementById('obstacles_notes');
    
    if (hasObstacles && hasObstacles.value === '1' && !obstaclesNotes.value.trim()) {
        // إظهار رسالة خطأ
        const errorAlert = document.createElement('div');
        errorAlert.className = 'alert alert-danger alert-dismissible fade show';
        errorAlert.innerHTML = `
            <i class="fas fa-exclamation-triangle me-2"></i>
            يرجى إدخال ملاحظات المعوقات عند اختيار "نعم"
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // إدراج رسالة الخطأ في أعلى النموذج
        const modalBody = this.querySelector('.modal-body');
        modalBody.insertBefore(errorAlert, modalBody.firstChild);
        
        // التركيز على حقل الملاحظات
        obstaclesNotes.focus();
        obstaclesNotes.classList.add('is-invalid');
        
        // إزالة رسالة الخطأ بعد 5 ثواني
        setTimeout(() => {
            if (errorAlert.parentNode) {
                errorAlert.remove();
            }
            obstaclesNotes.classList.remove('is-invalid');
        }, 5000);
        
        return;
    }
    
    const formData = new FormData(this);
    const surveyId = document.getElementById('survey_id').value;
    
    // Show loading state
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> جاري الحفظ...';
    submitButton.disabled = true;

    // تحديد URL وطريقة الطلب بناءً على وجود معرف المسح
    let url = this.action;
    let method = 'POST';
    
    if (surveyId) {
        url = `/admin/work-orders/survey/${surveyId}`;
        method = 'PUT';
        formData.append('_method', 'PUT'); // Laravel method spoofing
    }
    
    fetch(url, {
        method: method,
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || `HTTP error! status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        // Show success message
        const successAlert = document.createElement('div');
        successAlert.className = 'alert alert-success alert-dismissible fade show';
        successAlert.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            ${data.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Insert success message at the top of the card body
        const cardBody = document.querySelector('.card-body');
        cardBody.insertBefore(successAlert, cardBody.firstChild);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            if (successAlert.parentNode) {
                successAlert.remove();
            }
        }, 5000);
        
        // Reload the page to show updated data
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    })
    .catch(error => {
        // Show error message
        const errorAlert = document.createElement('div');
        errorAlert.className = 'alert alert-danger alert-dismissible fade show';
        errorAlert.innerHTML = `
            <i class="fas fa-exclamation-triangle me-2"></i>
            ${error.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Insert error message at the top of the form
        const modalBody = this.querySelector('.modal-body');
        modalBody.insertBefore(errorAlert, modalBody.firstChild);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            if (errorAlert.parentNode) {
                errorAlert.remove();
            }
        }, 5000);
    })
    .finally(() => {
        // Restore button state
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
});
</script>

<style>
.table th, .table td {
    vertical-align: middle;
}

.text-truncate {
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.card-img-top {
    height: 200px;
    object-fit: cover;
}

.modal-xl {
    max-width: 90%;
}

@media (max-width: 768px) {
    .modal-xl {
        max-width: 100%;
    }
}
</style>
@endsection 