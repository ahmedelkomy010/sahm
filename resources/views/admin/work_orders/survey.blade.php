@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 fs-4">المسح</h3>
                    <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right"></i> عودة
                    </a>
                </div>

                <div class="card-body p-4">
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
                                                <label for="start_coordinates" class="form-label fw-bold">إحداثيات البداية</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control @error('start_coordinates') is-invalid @enderror" id="start_coordinates" name="start_coordinates" value="{{ old('start_coordinates') }}" placeholder="أدخل رابط إحداثيات نقطة البداية">
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
                                                <label for="end_coordinates" class="form-label fw-bold">إحداثيات النهاية</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control @error('end_coordinates') is-invalid @enderror" id="end_coordinates" name="end_coordinates" value="{{ old('end_coordinates') }}" placeholder="أدخل رابط إحداثيات نقطة النهاية">
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
                                                <label class="form-label fw-bold">المعوقات</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="has_obstacles" id="obstacles_yes" value="1" {{ old('has_obstacles') == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="obstacles_yes">
                                                        نعم
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="has_obstacles" id="obstacles_no" value="0" {{ old('has_obstacles') == 0 ? 'checked' : '' }}>
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
                                            <div class="col-md-6">
                                                <label for="obstacles_notes" class="form-label fw-bold">ملاحظات</label>
                                                <textarea class="form-control @error('obstacles_notes') is-invalid @enderror" id="obstacles_notes" name="obstacles_notes" rows="3" placeholder="أدخل أي ملاحظات إضافية هنا...">{{ old('obstacles_notes') }}</textarea>
                                                @error('obstacles_notes')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="site_images" class="form-label fw-bold">صور الموقع</label>
                                            <input type="file" class="form-control @error('site_images.*') is-invalid @enderror" id="site_images" name="site_images[]" multiple accept="image/*">
                                            <div class="form-text">الحد الأقصى لحجم كل صورة هو 30 ميجابايت.</div>
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
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="viewImagesModalLabel{{ $survey->id }}">صور الموقع</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                @php
                                                                    $validFiles = $survey->files->filter(function($file) {
                                                                        return \App\Helpers\FileHelper::getFileUrl($file->file_path) !== null;
                                                                    });
                                                                @endphp
                                                                @forelse($validFiles as $file)
                                                                    @php
                                                                        $fileUrl = \App\Helpers\FileHelper::getFileUrl($file->file_path);
                                                                    @endphp
                                                                    @if($fileUrl)
                                                                        <div class="col-md-4 mb-3">
                                                                            <div class="card">
                                                                                <img src="{{ $fileUrl }}" class="card-img-top" alt="{{ $file->original_filename }}" 
                                                                                     onerror="this.parentElement.parentElement.style.display='none'">
                                                                                <div class="card-body">
                                                                                    <p class="card-text small">{{ $file->original_filename }}</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @empty
                                                                    <div class="col-12">
                                                                        <div class="alert alert-info">
                                                                            لا توجد صور مرفوعة
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
            // Fill form with survey data
            document.getElementById('survey_id').value = data.survey.id;
            document.getElementById('start_coordinates').value = data.survey.start_coordinates;
            document.getElementById('end_coordinates').value = data.survey.end_coordinates;
            document.querySelector(`input[name="has_obstacles"][value="${data.survey.has_obstacles ? 1 : 0}"]`).checked = true;
            document.getElementById('obstacles_notes').value = data.survey.obstacles_notes || '';
            
            // Show existing images
            const existingImages = document.getElementById('existingImages');
            const imagesContainer = document.getElementById('imagesContainer');
            imagesContainer.innerHTML = '';
            
            if (data.survey.images && data.survey.images.length > 0) {
                data.survey.images.forEach(image => {
                    const col = document.createElement('div');
                    col.className = 'col-md-4';
                    col.innerHTML = `
                        <div class="card">
                            <img src="${image.url}" class="card-img-top" alt="${image.name}">
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
            
            // Update modal title
            document.getElementById('createSurveyModalLabel').textContent = 'تعديل المسح';
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('createSurveyModal'));
            modal.show();
        } else {
            throw new Error(data.message || 'حدث خطأ أثناء تحميل بيانات المسح');
        }
    })
    .catch(error => {
        // إزالة مؤشر التحميل
        loadingAlert.remove();

        console.error('Error:', error);
        
        // إظهار رسالة الخطأ
        const errorAlert = document.createElement('div');
        errorAlert.className = 'alert alert-danger';
        errorAlert.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${error.message}`;
        document.querySelector('.card-body').prepend(errorAlert);

        // إخفاء رسالة الخطأ بعد 5 ثواني
        setTimeout(() => {
            errorAlert.remove();
        }, 5000);
    });
}

// Handle form submission
document.getElementById('surveyForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const surveyId = document.getElementById('survey_id').value;
    
    // Show loading state
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> جاري الحفظ...';
    submitButton.disabled = true;
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
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
        if (data.success) {
            // Close the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('createSurveyModal'));
            if (modal) {
                modal.hide();
            }
            
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
            
        } else {
            throw new Error(data.message || 'حدث خطأ أثناء حفظ المسح');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Show error message
        const errorAlert = document.createElement('div');
        errorAlert.className = 'alert alert-danger alert-dismissible fade show';
        errorAlert.innerHTML = `
            <i class="fas fa-exclamation-circle me-2"></i>
            حدث خطأ أثناء حفظ المسح: ${error.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Insert error message at the top of the modal body
        const modalBody = document.querySelector('#createSurveyModal .modal-body');
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