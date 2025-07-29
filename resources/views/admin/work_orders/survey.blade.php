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
                                                    <small class="text-muted d-block">رقم أمر العمل</small>
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
                                                <div class="form-group">
                                                    <label for="start_coordinates" class="form-label required">إحداثيات نقطة البداية</label>
                                                    <div class="input-group">
                                                        <input type="text" 
                                                               class="form-control @error('start_coordinates') is-invalid @enderror" 
                                                               id="start_coordinates" 
                                                               name="start_coordinates"
                                                               placeholder="24°48'40.6"N 46°39'00.5"E"
                                                               value="{{ old('start_coordinates') }}"
                                                               required>
                                                        <a href="#" 
                                                           onclick="openGoogleMaps('start_coordinates'); return false;" 
                                                           class="btn btn-outline-secondary">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                        </a>
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        انسخ الإحداثيات مباشرة من خرائط جوجل
                                                    </small>
                                                    @error('start_coordinates')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="end_coordinates" class="form-label required">إحداثيات نقطة النهاية</label>
                                                    <div class="input-group">
                                                        <input type="text" 
                                                               class="form-control @error('end_coordinates') is-invalid @enderror" 
                                                               id="end_coordinates" 
                                                               name="end_coordinates"
                                                               placeholder=":24°48'40.6"N 46°39'00.5"E"
                                                               value="{{ old('end_coordinates') }}"
                                                               required>
                                                        <a href="#" 
                                                           onclick="openGoogleMaps('end_coordinates'); return false;" 
                                                           class="btn btn-outline-secondary">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                        </a>
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        انسخ الإحداثيات مباشرة من خرائط جوجل
                                                    </small>
                                                    @error('end_coordinates')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
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

                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="site_images" class="form-label">صور الموقع</label>
                                                    <div class="input-group">
                                                        <input type="file" 
                                                               class="form-control @error('site_images.*') is-invalid @enderror" 
                                                               id="site_images" 
                                                               name="site_images[]" 
                                                               accept="image/*" 
                                                               multiple>
                                                        <button type="button" 
                                                                class="btn btn-outline-primary" 
                                                                onclick="openCamera()">
                                                            <i class="fas fa-camera"></i>
                                                        </button>
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        يمكنك اختيار عدة صور في نفس الوقت
                                                    </small>
                                                    @error('site_images.*')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
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

                    <!-- Modal for viewing images -->
                    @foreach($workOrder->surveys as $survey)
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
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-danger delete-survey-image"
                                                                        data-file-id="{{ $file->id }}"
                                                                        onclick="deleteSurveyImage({{ $file->id }})">
                                                                    <i class="fas fa-trash"></i> حذف
                                                                </button>
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
                    @endforeach

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
                                                            <a href="https://www.google.com/maps/search/?api=1&query={{ $survey->start_coordinates }}" 
                                                               target="_blank" 
                                                               class="text-primary text-decoration-none">
                                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                                {{ $survey->start_coordinates }}
                                                            </a>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">غير متوفر</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($survey->end_coordinates)
                                                        <div class="d-flex align-items-center">
                                                            <a href="https://www.google.com/maps/search/?api=1&query={{ $survey->end_coordinates }}" 
                                                               target="_blank" 
                                                               class="text-primary text-decoration-none">
                                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                                {{ $survey->end_coordinates }}
                                                            </a>
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
                                                        <form action="{{ route('admin.work-orders.survey.destroy', [$workOrder, $survey]) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المسح؟');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="fas fa-trash"></i> حذف
                                                            </button>
                                                        </form>
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
    // إعادة تعيين قائمة الملفات المحذوفة
    window.deletedFiles = [];
    
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

// دالة مساعدة لتنسيق الإحداثيات بشكل موحد
function formatCoordinates(coordinates) {
    try {
        const [lat, lng] = parseCoordinates(coordinates);
        return `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    } catch (error) {
        return coordinates; // إرجاع النص الأصلي إذا فشل التحليل
    }
}

// تحديث دالة معالجة النموذج لتنسيق الإحداثيات قبل الإرسال
document.getElementById('surveyForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // التحقق من صحة البيانات
    const hasObstacles = document.querySelector('input[name="has_obstacles"]:checked');
    const obstaclesNotes = document.getElementById('obstacles_notes');
    const startCoordinates = document.getElementById('start_coordinates');
    const endCoordinates = document.getElementById('end_coordinates');
    const submitButton = this.querySelector('button[type="submit"]');
    const modalBody = this.querySelector('.modal-body');
    
    // إزالة رسائل الخطأ السابقة
    modalBody.querySelectorAll('.alert').forEach(alert => alert.remove());
    
    // التحقق من الإحداثيات
    if (!startCoordinates.value.trim()) {
        showError('يرجى إدخال إحداثيات نقطة البداية', modalBody);
        startCoordinates.focus();
        return;
    }

    if (!endCoordinates.value.trim()) {
        showError('يرجى إدخال إحداثيات نقطة النهاية', modalBody);
        endCoordinates.focus();
        return;
    }

    try {
        // محاولة تحليل الإحداثيات للتأكد من صحتها وتنسيقها
        startCoordinates.value = formatCoordinates(startCoordinates.value);
        endCoordinates.value = formatCoordinates(endCoordinates.value);
    } catch (error) {
        showError(error.message, modalBody);
        return;
    }
    
    if (hasObstacles && hasObstacles.value === '1' && !obstaclesNotes.value.trim()) {
        showError('يرجى إدخال ملاحظات المعوقات عند اختيار "نعم"', modalBody);
        obstaclesNotes.focus();
        obstaclesNotes.classList.add('is-invalid');
        return;
    }
    
    // التحقق من الصور
    const siteImages = document.getElementById('site_images').files;
    for (let i = 0; i < siteImages.length; i++) {
        const file = siteImages[i];
        
        // التحقق من نوع الملف
        if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
            showError(`الملف ${file.name} ليس صورة صالحة. الصيغ المدعومة هي: JPG, JPEG, PNG`, modalBody);
            return;
        }
        
        // التحقق من حجم الملف
        if (file.size > 30 * 1024 * 1024) {
            showError(`الملف ${file.name} أكبر من 30 ميجابايت`, modalBody);
            return;
        }
    }
    
    const formData = new FormData(this);
    const surveyId = document.getElementById('survey_id').value;
    
    // إضافة الملفات المحذوفة إلى FormData
    const deletedFiles = window.deletedFiles || [];
    if (deletedFiles.length > 0) {
        formData.delete('deleted_files[]'); // حذف أي قيم سابقة
        deletedFiles.forEach(fileId => {
            formData.append('deleted_files[]', fileId.toString());
        });
    }
    
    // إظهار حالة التحميل
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> جاري الحفظ...';
    submitButton.disabled = true;
    
    // إضافة مؤشر تقدم رفع الملفات
    const progressContainer = document.createElement('div');
    progressContainer.className = 'progress mt-3';
    progressContainer.innerHTML = `
        <div class="progress-bar progress-bar-striped progress-bar-animated" 
             role="progressbar" 
             style="width: 0%" 
             aria-valuenow="0" 
             aria-valuemin="0" 
             aria-valuemax="100">0%</div>
    `;
    modalBody.appendChild(progressContainer);

    // تحديد URL وطريقة الطلب
    let url = this.action;
    let method = 'POST';
    
    if (surveyId) {
        url = `/admin/work-orders/survey/${surveyId}`;
        method = 'PUT';
        formData.append('_method', 'PUT');
    }
    
    // إنشاء كائن XMLHttpRequest للتحكم في رفع الملفات
    const xhr = new XMLHttpRequest();
    
    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            const percentComplete = (e.loaded / e.total) * 100;
            const progressBar = progressContainer.querySelector('.progress-bar');
            progressBar.style.width = percentComplete + '%';
            progressBar.setAttribute('aria-valuenow', percentComplete);
            progressBar.textContent = Math.round(percentComplete) + '%';
        }
    };
    
    xhr.onload = function() {
        try {
            const response = JSON.parse(xhr.responseText);
            if (xhr.status === 200 && response.success) {
                // إعادة تعيين قائمة الملفات المحذوفة
                window.deletedFiles = [];
                showSuccess(response.message);
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                throw new Error(response.message || 'حدث خطأ أثناء حفظ المسح');
            }
        } catch (error) {
            showError(error.message, modalBody);
        } finally {
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
            progressContainer.remove();
        }
    };
    
    xhr.onerror = function() {
        showError('حدث خطأ في الاتصال بالخادم', modalBody);
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
        progressContainer.remove();
    };
    
    xhr.open(method, url, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    xhr.send(formData);
});

// دالة مساعدة لعرض رسائل الخطأ
function showError(message, container) {
    const errorAlert = document.createElement('div');
    errorAlert.className = 'alert alert-danger alert-dismissible fade show mt-3';
    errorAlert.innerHTML = `
        <i class="fas fa-exclamation-triangle me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    container.insertBefore(errorAlert, container.firstChild);
    
    setTimeout(() => {
        if (errorAlert.parentNode) {
            errorAlert.remove();
        }
    }, 5000);
}

// دالة مساعدة لعرض رسائل النجاح
function showSuccess(message) {
    const successAlert = document.createElement('div');
    successAlert.className = 'alert alert-success alert-dismissible fade show';
    successAlert.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    document.querySelector('.card-body').insertBefore(successAlert, document.querySelector('.card-body').firstChild);
    
    setTimeout(() => {
        if (successAlert.parentNode) {
            successAlert.remove();
        }
    }, 5000);
}

// إضافة معاينة الصور قبل الرفع
document.getElementById('site_images').addEventListener('change', function(e) {
    const previewContainer = document.createElement('div');
    previewContainer.className = 'row g-3 mt-3';
    previewContainer.id = 'imagePreviewContainer';
    
    // إزالة المعاينة السابقة إن وجدت
    const oldPreview = document.getElementById('imagePreviewContainer');
    if (oldPreview) {
        oldPreview.remove();
    }
    
    Array.from(this.files).forEach((file, index) => {
        // التحقق من نوع الملف
        if (!file.type.match('image/jpeg') && !file.type.match('image/png') && !file.type.match('image/jpg')) {
            toastr.error(`الملف ${file.name} ليس صورة صالحة. الصيغ المدعومة هي: JPG, JPEG, PNG`);
            return;
        }
        
        // التحقق من حجم الملف (30MB)
        if (file.size > 30 * 1024 * 1024) {
            toastr.error(`الملف ${file.name} أكبر من 30 ميجابايت`);
            return;
        }
        
        const col = document.createElement('div');
        col.className = 'col-md-4';
        
        const card = document.createElement('div');
        card.className = 'card h-100';
        
        const img = document.createElement('img');
        img.className = 'card-img-top';
        img.style.height = '200px';
        img.style.objectFit = 'cover';
        
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
        
        const cardBody = document.createElement('div');
        cardBody.className = 'card-body';
        
        const fileName = document.createElement('p');
        fileName.className = 'card-text small';
        fileName.textContent = file.name;
        
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-sm btn-danger';
        removeBtn.innerHTML = '<i class="fas fa-times"></i> إزالة';
        removeBtn.onclick = function() {
            col.remove();
            
            // إنشاء FileList جديد بدون الملف المحذوف
            const dt = new DataTransfer();
            const input = document.getElementById('site_images');
            const { files } = input;
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (i !== index) {
                    dt.items.add(file);
                }
            }
            
            input.files = dt.files;
        };
        
        cardBody.appendChild(fileName);
        cardBody.appendChild(removeBtn);
        card.appendChild(img);
        card.appendChild(cardBody);
        col.appendChild(card);
        previewContainer.appendChild(col);
    });
    
    if (previewContainer.children.length > 0) {
        this.parentElement.appendChild(previewContainer);
    }
});

// تحسين وظيفة حذف الصور
function deleteSurveyImage(fileId) {
    if (!confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
        return;
    }
    
    const imageCard = document.querySelector(`[data-file-id="${fileId}"]`).closest('.col-md-4');
    const loadingOverlay = document.createElement('div');
    loadingOverlay.className = 'position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50';
    loadingOverlay.innerHTML = '<div class="spinner-border text-light" role="status"></div>';
    imageCard.style.position = 'relative';
    imageCard.appendChild(loadingOverlay);

    // إرسال طلب حذف إلى الخادم
    fetch(`/admin/work-orders/survey/files/${fileId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // إخفاء الصورة من الواجهة
            imageCard.remove();
            
            // التحقق مما إذا كانت هناك صور متبقية
            const imagesContainer = document.getElementById('imagesContainer');
            if (imagesContainer && imagesContainer.children.length === 0) {
                const noImagesAlert = document.createElement('div');
                noImagesAlert.className = 'col-12';
                noImagesAlert.innerHTML = `
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        لا توجد صور لهذا المسح
                    </div>
                `;
                imagesContainer.appendChild(noImagesAlert);
            }
        } else {
            // إزالة شاشة التحميل وإظهار رسالة الخطأ
            loadingOverlay.remove();
            alert('حدث خطأ أثناء حذف الصورة');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // إزالة شاشة التحميل وإظهار رسالة الخطأ
        loadingOverlay.remove();
        alert('حدث خطأ أثناء حذف الصورة');
    });
}

// إضافة متغير عام لتتبع الملفات المحذوفة
window.deletedFiles = [];

function openGoogleMaps(inputId) {
    const input = document.getElementById(inputId);
    const coordinates = input.value.trim();
    
    let url;
    if (coordinates) {
        try {
            const [lat, lng] = parseCoordinates(coordinates);
            url = `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`;
        } catch (error) {
            // إذا فشل تحليل الإحداثيات، افتح الخريطة بدون إحداثيات محددة
            url = 'https://www.google.com/maps';
        }
    } else {
        // إذا لم تكن هناك إحداثيات، افتح الخريطة في المملكة العربية السعودية
        url = 'https://www.google.com/maps/@24.7136,46.6753,11z';
    }
    
    // فتح الخريطة في نافذة جديدة
    const mapWindow = window.open(url, '_blank', 'width=800,height=600');
    
    // إضافة رسالة للمستخدم
    const modalBody = document.querySelector('.modal-body');
    const helpText = document.createElement('div');
    helpText.className = 'alert alert-info alert-dismissible fade show mt-2';
    helpText.innerHTML = `
        <i class="fas fa-info-circle me-2"></i>
        للحصول على الإحداثيات:
        <ol class="mb-0 mt-2">
            <li>انقر بزر الماوس الأيمن على الموقع المطلوب</li>
            <li>انسخ الإحداثيات التي تظهر في أول القائمة</li>
            <li>الصق الإحداثيات في الحقل المناسب</li>
        </ol>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // إزالة أي رسائل مساعدة سابقة
    modalBody.querySelectorAll('.alert-info').forEach(alert => alert.remove());
    modalBody.appendChild(helpText);
    
    // إزالة الرسالة بعد 10 ثواني
    setTimeout(() => {
        if (helpText.parentNode) {
            helpText.remove();
        }
    }, 10000);
}

function parseCoordinates(coordinates) {
    // تنظيف النص من أي مسافات زائدة
    coordinates = coordinates.trim();
    
    // محاولة تحليل التنسيق العشري (مثل: 24.796583, 46.800361)
    const decimalFormat = coordinates.match(/^(-?\d+\.?\d*)[,\s]+(-?\d+\.?\d*)$/);
    if (decimalFormat) {
        const lat = parseFloat(decimalFormat[1]);
        const lng = parseFloat(decimalFormat[2]);
        
        // التحقق من صحة نطاق الإحداثيات
        if (lat < -90 || lat > 90) {
            throw new Error('خط العرض يجب أن يكون بين -90 و 90 درجة');
        }
        if (lng < -180 || lng > 180) {
            throw new Error('خط الطول يجب أن يكون بين -180 و 180 درجة');
        }
        
        return [lat, lng];
    }
    
    // محاولة تحليل تنسيق الدرجات (مثل: 24°47'47.7"N 46°48'01.3"E)
    const dmsFormat = coordinates.match(/(\d+)°(\d+)'(\d+\.?\d*)"([NS])\s+(\d+)°(\d+)'(\d+\.?\d*)"([EW])/);
    if (dmsFormat) {
        const [_, latDeg, latMin, latSec, latDir, lngDeg, lngMin, lngSec, lngDir] = dmsFormat;
        
        let latitude = parseFloat(latDeg) + parseFloat(latMin)/60 + parseFloat(latSec)/3600;
        let longitude = parseFloat(lngDeg) + parseFloat(lngMin)/60 + parseFloat(lngSec)/3600;
        
        if (latDir === 'S') latitude = -latitude;
        if (lngDir === 'W') longitude = -longitude;
        
        // التحقق من صحة نطاق الإحداثيات
        if (latitude < -90 || latitude > 90) {
            throw new Error('خط العرض يجب أن يكون بين -90 و 90 درجة');
        }
        if (longitude < -180 || longitude > 180) {
            throw new Error('خط الطول يجب أن يكون بين -180 و 180 درجة');
        }
        
        return [latitude, longitude];
    }
    
    // محاولة تحليل تنسيق URL خرائط جوجل
    const urlFormat = coordinates.match(/[?&]q=(-?\d+\.?\d*),(-?\d+\.?\d*)/);
    if (urlFormat) {
        const lat = parseFloat(urlFormat[1]);
        const lng = parseFloat(urlFormat[2]);
        
        // التحقق من صحة نطاق الإحداثيات
        if (lat < -90 || lat > 90) {
            throw new Error('خط العرض يجب أن يكون بين -90 و 90 درجة');
        }
        if (lng < -180 || lng > 180) {
            throw new Error('خط الطول يجب أن يكون بين -180 و 180 درجة');
        }
        
        return [lat, lng];
    }

    // محاولة تحليل أي رقمين عشريين متتاليين
    const numbers = coordinates.match(/-?\d+\.?\d*/g);
    if (numbers && numbers.length >= 2) {
        const lat = parseFloat(numbers[0]);
        const lng = parseFloat(numbers[1]);
        
        // التحقق من صحة نطاق الإحداثيات
        if (lat < -90 || lat > 90) {
            throw new Error('خط العرض يجب أن يكون بين -90 و 90 درجة');
        }
        if (lng < -180 || lng > 180) {
            throw new Error('خط الطول يجب أن يكون بين -180 و 180 درجة');
        }
        
        return [lat, lng];
    }
    
    throw new Error('تنسيق الإحداثيات غير مدعوم. الرجاء استخدام أحد التنسيقات التالية:\n' +
                   '- عشري: 24.796583, 46.800361\n' +
                   '- درجات: 24°47\'47.7"N 46°48\'01.3"E\n' +
                   '- رابط خرائط جوجل');
}

function openGoogleMapsFromTable(coordinates) {
    try {
        const [lat, lng] = parseCoordinates(coordinates);
        if (lat && lng) {
            const googleMapsUrl = `https://www.google.com/maps?q=${lat},${lng}`;
            window.open(googleMapsUrl, '_blank');
        } else {
            alert('تنسيق الإحداثيات غير صحيح');
        }
    } catch (error) {
        console.error('خطأ في معالجة الإحداثيات:', error);
        alert('حدث خطأ في فتح الموقع على الخريطة');
    }
}

// تحديث دالة parseCoordinates لتكون أكثر مرونة
function parseCoordinates(coordinates) {
    // تنظيف النص من أي مسافات زائدة
    coordinates = coordinates.trim();
    
    // محاولة تحليل التنسيق العشري (مثل: 24.796583, 46.800361)
    const decimalFormat = coordinates.match(/^(-?\d+\.?\d*)[,\s]+(-?\d+\.?\d*)$/);
    if (decimalFormat) {
        return [parseFloat(decimalFormat[1]), parseFloat(decimalFormat[2])];
    }
    
    // محاولة تحليل تنسيق الدرجات (مثل: 24°47'47.7"N 46°48'01.3"E)
    const dmsFormat = coordinates.match(/(\d+)°(\d+)'(\d+\.?\d*)"([NS])\s+(\d+)°(\d+)'(\d+\.?\d*)"([EW])/);
    if (dmsFormat) {
        const [_, latDeg, latMin, latSec, latDir, lngDeg, lngMin, lngSec, lngDir] = dmsFormat;
        
        let latitude = parseFloat(latDeg) + parseFloat(latMin)/60 + parseFloat(latSec)/3600;
        let longitude = parseFloat(lngDeg) + parseFloat(lngMin)/60 + parseFloat(lngSec)/3600;
        
        if (latDir === 'S') latitude = -latitude;
        if (lngDir === 'W') longitude = -longitude;
        
        return [latitude, longitude];
    }
    
    // محاولة تحليل تنسيق URL خرائط جوجل
    const urlFormat = coordinates.match(/[?&]q=(-?\d+\.?\d*),(-?\d+\.?\d*)/);
    if (urlFormat) {
        return [parseFloat(urlFormat[1]), parseFloat(urlFormat[2])];
    }

    // محاولة تحليل أي رقمين عشريين متتاليين
    const numbers = coordinates.match(/-?\d+\.?\d*/g);
    if (numbers && numbers.length >= 2) {
        return [parseFloat(numbers[0]), parseFloat(numbers[1])];
    }
    
    throw new Error('تنسيق الإحداثيات غير مدعوم');
}

// إضافة مستمع للأحداث للتحقق من الإحداثيات عند الإدخال
document.addEventListener('DOMContentLoaded', function() {
    ['start_coordinates', 'end_coordinates'].forEach(inputId => {
        const input = document.getElementById(inputId);
        const feedbackDiv = document.createElement('div');
        feedbackDiv.className = 'invalid-feedback';
        input.parentNode.appendChild(feedbackDiv);
        
        input.addEventListener('input', function() {
            validateCoordinatesInput(this);
        });
        
        input.addEventListener('paste', function(e) {
            // السماح بلصق النص أولاً
            setTimeout(() => {
                validateCoordinatesInput(this);
            }, 100);
        });
        
        // إضافة مستمع لحدث الفقدان التركيز لتنسيق الإحداثيات
        input.addEventListener('blur', function() {
            try {
                const formatted = formatCoordinates(this.value);
                this.value = formatted;
                validateCoordinatesInput(this);
            } catch (error) {
                // تجاهل الأخطاء هنا لأن التحقق سيتم عند الإرسال
            }
        });
    });
});

function validateCoordinatesInput(input) {
    const feedbackDiv = input.parentNode.querySelector('.invalid-feedback');
    
    try {
        if (!input.value.trim()) {
            input.classList.remove('is-valid', 'is-invalid');
            feedbackDiv.textContent = '';
            return;
        }
        
        const [lat, lng] = parseCoordinates(input.value);
        
        // إظهار القيم المحللة كتلميح
        feedbackDiv.textContent = `تم التعرف على: خط العرض ${lat.toFixed(6)}، خط الطول ${lng.toFixed(6)}`;
        feedbackDiv.style.color = 'green';
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
    } catch (error) {
        feedbackDiv.textContent = error.message;
        feedbackDiv.style.color = '';  // إعادة تعيين اللون للون الافتراضي للخطأ
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
    }
}

function openCamera() {
    // إنشاء عنصر input جديد من نوع file مع خصائص الكاميرا
    const cameraInput = document.createElement('input');
    cameraInput.type = 'file';
    cameraInput.accept = 'image/*';
    cameraInput.capture = 'environment'; // استخدام الكاميرا الخلفية
    cameraInput.style.display = 'none';
    
    // إضافة معالج حدث عند اختيار صورة
    cameraInput.addEventListener('change', function(e) {
        const files = e.target.files;
        if (!files.length) return;
        
        // إضافة الصورة الملتقطة إلى حقل الصور
        const siteImagesInput = document.getElementById('site_images');
        const newFileList = new DataTransfer();
        
        // إضافة الملفات الموجودة مسبقاً
        if (siteImagesInput.files.length) {
            Array.from(siteImagesInput.files).forEach(file => {
                newFileList.items.add(file);
            });
        }
        
        // إضافة الصورة الجديدة
        newFileList.items.add(files[0]);
        
        // تحديث قائمة الملفات
        siteImagesInput.files = newFileList.files;
        
        // إظهار رسالة نجاح
        showImagePreview(files[0]);
    });
    
    // إضافة العنصر للصفحة وتفعيله
    document.body.appendChild(cameraInput);
    cameraInput.click();
    
    // حذف العنصر بعد الاستخدام
    setTimeout(() => {
        document.body.removeChild(cameraInput);
    }, 1000);
}

function showImagePreview(file) {
    // إنشاء معاينة للصورة
    const reader = new FileReader();
    reader.onload = function(e) {
        // إنشاء تنبيه نجاح مع معاينة الصورة
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show mt-2';
        alert.innerHTML = `
            <div class="d-flex align-items-center">
                <img src="${e.target.result}" 
                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                <div>
                    <strong>تم التقاط الصورة بنجاح!</strong><br>
                    <small>${file.name}</small>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // إضافة التنبيه بعد حقل الصور
        const inputGroup = document.querySelector('#site_images').closest('.form-group');
        inputGroup.appendChild(alert);
        
        // حذف التنبيه بعد 3 ثواني
        setTimeout(() => {
            alert.remove();
        }, 3000);
    };
    reader.readAsDataURL(file);
}
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