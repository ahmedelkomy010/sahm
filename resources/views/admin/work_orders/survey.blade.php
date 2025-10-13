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
                                                    <label for="site_images" class="form-label">
                                                        صور الموقع 
                                                        <small class="text-muted">(حد أقصى 50 ملف)</small>
                                                    </label>
                                                    <div class="input-group">
                                                                                                            <input type="file" 
                                                           class="form-control @error('site_images.*') is-invalid @enderror" 
                                                           id="site_images" 
                                                           name="site_images[]" 
                                                           accept="image/*,application/pdf" 
                                                           multiple
                                                           onchange="validateFileCount(this, 50)">
                                                        <button type="button" 
                                                                class="btn btn-outline-primary" 
                                                                onclick="openCamera()">
                                                            <i class="fas fa-camera"></i>
                                                        </button>
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        يمكنك اختيار عدة صور أو ملفات PDF في نفس الوقت
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
                                        <h5 class="modal-title" id="viewImagesModalLabel{{ $survey->id }}">ملفات المسح</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            @forelse($survey->files as $file)
                                                <div class="col-md-4">
                                                    <div class="card h-100">
                                                        @if(in_array(strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']))
                                                            @php
                                                                $imageUrl = \App\Helpers\FileHelper::getImageUrl($file->file_path);
                                                            @endphp
                                                            @if($imageUrl)
                                                                <img src="{{ $imageUrl }}" 
                                                                     class="card-img-top" 
                                                                     alt="{{ $file->original_filename }}" 
                                                                     style="height: 200px; object-fit: cover; cursor: pointer;" 
                                                                     onclick="viewImage('{{ $imageUrl }}', '{{ $file->original_filename }}')"
                                                                     title="انقر لعرض الصورة">
                                                            @else
                                                                <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                                                    <i class="fas fa-image fa-3x text-muted"></i>
                                                                </div>
                                                            @endif
                                                        @elseif(strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION)) == 'pdf')
                                                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                                                <i class="fas fa-file-pdf fa-5x text-danger"></i>
                                                            </div>
                                                        @else
                                                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                                                <i class="fas fa-file fa-3x text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div class="card-body">
                                                            <p class="card-text small">{{ $file->original_filename }}</p>
                                                            <div class="btn-group w-100">
                                                                @if(in_array(strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']))
                                                                    @php
                                                                        $imageUrl = \App\Helpers\FileHelper::getImageUrl($file->file_path);
                                                                    @endphp
                                                                    @if($imageUrl)
                                                                        <button type="button" 
                                                                                class="btn btn-sm btn-primary"
                                                                                onclick="viewImage('{{ $imageUrl }}', '{{ $file->original_filename }}')">
                                                                            <i class="fas fa-eye"></i> عرض
                                                                        </button>
                                                                    @endif
                                                                @elseif(strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION)) == 'pdf')
                                                                    <a href="{{ asset('storage/' . $file->file_path) }}" 
                                                                       target="_blank" 
                                                                       class="btn btn-sm btn-primary">
                                                                        <i class="fas fa-eye"></i> عرض
                                                                    </a>
                                                                @endif
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-danger delete-survey-image"
                                                                        data-file-id="{{ $file->id }}"
                                                                        onclick="deleteSurveyImage({{ $file->id }})">
                                                                    <i class="fas fa-trash"></i> حذف
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-12">
                                                    <div class="alert alert-info mb-0">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        لا توجد ملفات لهذا المسح
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
                                            <th>عدد الملفات</th>
                                            <th>تاريخ المسح</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody id="surveyTableBody">
                                        @forelse($workOrder->surveys as $index => $survey)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
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
                                                    @if($survey->has_obstacles)
                                                        <span class="badge bg-danger">نعم</span>
                                                    @else
                                                        <span class="badge bg-success">لا</span>
                                                    @endif
                                                </td>
                                                <td class="notes-cell">
                                                    @if($survey->obstacles_notes)
                                                        <div class="notes-content" id="notes-{{ $survey->id }}">
                                                            {{ $survey->obstacles_notes }}
                                                        </div>
                                                        <span class="expand-btn" onclick="toggleNotes('notes-{{ $survey->id }}')">
                                                            عرض المزيد
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
                                                            <i class="fas fa-folder-open"></i> عرض الملفات
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
                                                            <h5 class="modal-title" id="viewImagesModalLabel{{ $survey->id }}">ملفات المسح</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row g-3">
                                                                @forelse($survey->files as $file)
                                                                    @php
                                                                        $imageUrl = \App\Helpers\FileHelper::getImageUrl($file->file_path);
                                                                    @endphp
                                                                    <div class="col-md-4">
                                                                        <div class="card h-100">
                                                            @if($imageUrl)
                                                                <img src="{{ $imageUrl }}" 
                                                                     class="card-img-top" 
                                                                     alt="{{ $file->original_filename }}" 
                                                                     style="height: 200px; object-fit: cover; cursor: pointer;" 
                                                                     onclick="viewImage('{{ $imageUrl }}', '{{ $file->original_filename }}')"
                                                                     title="انقر لعرض الصورة">
                                                            @elseif(strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION)) == 'pdf')
                                                                                <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                                                                    <i class="fas fa-file-pdf fa-5x text-danger"></i>
                                                                                </div>
                                                                            @else
                                                                                <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                                                                    <i class="fas fa-file fa-3x text-muted"></i>
                                                                                </div>
                                                                            @endif
                                                                            <div class="card-body">
                                                                                <p class="card-text small">{{ $file->original_filename }}</p>
                                                                                <div class="btn-group w-100">
                                                                                    @if(in_array(strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']) && $imageUrl)
                                                                                        <button type="button" 
                                                                                                class="btn btn-sm btn-primary"
                                                                                                onclick="viewImage('{{ $imageUrl }}', '{{ $file->original_filename }}')">
                                                                                            <i class="fas fa-eye"></i> عرض
                                                                                        </button>
                                                                                    @elseif(strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION)) == 'pdf')
                                                                                        <a href="{{ asset('storage/' . $file->file_path) }}" 
                                                                                           target="_blank" 
                                                                                           class="btn btn-sm btn-primary">
                                                                                            <i class="fas fa-eye"></i> عرض
                                                                                        </a>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @empty
                                                                    <div class="col-12">
                                                                        <div class="alert alert-info mb-0">
                                                                            <i class="fas fa-info-circle me-2"></i>
                                                                            لا توجد ملفات لهذا المسح
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
                                                <td colspan="7" class="text-center">لا توجد عمليات مسح مسجلة</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- قسم ملفات بعد انتهاء العمل -->
                    @if($workOrder->surveys->sum(function($survey) { return $survey->completionFiles->count(); }) > 0)
                    <div class="card mt-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                ملفات بعد انتهاء العمل
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($workOrder->surveys as $survey)
                                @if($survey->completionFiles->count() > 0)
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <h6 class="text-success mb-0">
                                                <i class="fas fa-folder me-2"></i>
                                                مسح رقم {{ $loop->iteration }} - {{ $survey->created_at->format('Y-m-d H:i') }}
                                            </h6>
                                            <span class="badge bg-success">
                                                {{ $survey->completionFiles->count() }} ملف
                                            </span>
                                        </div>
                                        
                                        <div class="row g-3">
                                            @foreach($survey->completionFiles as $file)
                                                <div class="col-md-3">
                                                    <div class="card h-100 border-success">
                                                        @if(in_array(strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']))
                                                            @php
                                                                $imageUrl = \App\Helpers\FileHelper::getImageUrl($file->file_path);
                                                            @endphp
                                                            @if($imageUrl)
                                                                <img src="{{ $imageUrl }}" 
                                                                     class="card-img-top" 
                                                                     alt="{{ $file->original_filename }}" 
                                                                     style="height: 150px; object-fit: cover; cursor: pointer;" 
                                                                     onclick="viewImage('{{ $imageUrl }}', '{{ $file->original_filename }}')"
                                                                     title="انقر لعرض الصورة">
                                                            @else
                                                                <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 150px;">
                                                                    <i class="fas fa-image fa-2x text-muted"></i>
                                                                </div>
                                                            @endif
                                                        @elseif(strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION)) == 'pdf')
                                                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 150px;">
                                                                <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                                            </div>
                                                        @elseif(in_array(strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION)), ['doc', 'docx']))
                                                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 150px;">
                                                                <i class="fas fa-file-word fa-3x text-primary"></i>
                                                            </div>
                                                        @elseif(in_array(strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION)), ['xls', 'xlsx']))
                                                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 150px;">
                                                                <i class="fas fa-file-excel fa-3x text-success"></i>
                                                            </div>
                                                        @else
                                                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 150px;">
                                                                <i class="fas fa-file fa-2x text-muted"></i>
                                                            </div>
                                                        @endif
                                                        
                                                        <div class="card-body p-2">
                                                            <p class="card-text small mb-2" title="{{ $file->original_filename }}">
                                                                {{ Str::limit($file->original_filename, 25) }}
                                                            </p>
                                                            <div class="btn-group w-100" role="group">
                                                                @if(in_array(strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']))
                                                                    @php
                                                                        $imageUrl = \App\Helpers\FileHelper::getImageUrl($file->file_path);
                                                                    @endphp
                                                                    @if($imageUrl)
                                                                        <button type="button" 
                                                                                class="btn btn-sm btn-success"
                                                                                onclick="viewImage('{{ $imageUrl }}', '{{ $file->original_filename }}')">
                                                                            <i class="fas fa-eye"></i>
                                                                        </button>
                                                                    @endif
                                                                @else
                                                                    <a href="{{ asset('storage/' . $file->file_path) }}" 
                                                                       target="_blank" 
                                                                       class="btn btn-sm btn-success">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                @endif
                                                                <a href="{{ asset('storage/' . $file->file_path) }}" 
                                                                   download="{{ $file->original_filename }}"
                                                                   class="btn btn-sm btn-primary">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-danger"
                                                                        onclick="deleteCompletionFile({{ $file->id }})">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        @if(!$loop->last)
                                            <hr class="my-4">
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- قسم رفع ملفات بعد انتهاء العمل -->
                    <div class="card mt-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-upload me-2"></i>
                                رفع ملفات بعد انتهاء العمل
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($workOrder->surveys->count() > 0)
                                <form id="completionFilesForm" enctype="multipart/form-data" class="mb-4">
                                    @csrf
                                    <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="survey_selection" class="form-label fw-bold">
                                                <i class="fas fa-list me-2 text-primary"></i>
                                                اختر المسح المرتبط
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="survey_selection" name="survey_id" required>
                                                <option value="">-- اختر المسح --</option>
                                                @foreach($workOrder->surveys as $survey)
                                                    <option value="{{ $survey->id }}">
                                                        مسح رقم {{ $loop->iteration }} - {{ $survey->created_at->format('Y-m-d H:i') }}
                                                        ({{ $survey->files->count() }} ملف موقع)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="completion_files_upload" class="form-label fw-bold">
                                                <i class="fas fa-paperclip me-2 text-success"></i>
                                                  مرفقات
                                                <small class="text-muted">(يمكن رفع أكثر من 50 ملف)</small>
                                            </label>
                                            <div class="input-group">
                                                <input type="file" 
                                                       class="form-control" 
                                                       id="completion_files_upload" 
                                                       name="completion_files[]" 
                                                       accept="image/*,application/pdf,.doc,.docx,.xlsx,.xls" 
                                                       multiple
                                                       onchange="validateCompletionUploadFiles(this)">
                                                <button type="button" 
                                                        class="btn btn-outline-primary" 
                                                        onclick="openCompletionUploadCamera()">
                                                    <i class="fas fa-camera"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="form-text text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                أنواع الملفات المدعومة: JPG, PNG, PDF, DOC, DOCX, XLS, XLSX - حد أقصى 50MB لكل ملف
                                            </div>
                                        </div>
                                    </div>

                                    <!-- عداد الملفات المحددة -->
                                    <div id="uploadFilesCount" class="mb-3" style="display: none;">
                                        <div class="alert alert-info py-2">
                                            <i class="fas fa-file-alt me-2"></i>
                                            تم اختيار <span id="uploadFilesNumber">0</span> ملف
                                        </div>
                                    </div>

                                    <!-- معاينة الملفات المحددة -->
                                    <div id="selectedFilesPreview" class="mb-3" style="display: none;">
                                        <h6 class="text-primary">
                                            <i class="fas fa-eye me-2"></i>
                                            معاينة الملفات المحددة
                                        </h6>
                                        <div id="previewContainer" class="row g-2">
                                            <!-- سيتم إضافة معاينة الملفات هنا -->
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-secondary" onclick="clearCompletionFiles()">
                                            <i class="fas fa-times me-1"></i>
                                            مسح الملفات
                                        </button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-upload me-1"></i>
                                            رفع الملفات
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    يجب إنشاء مسح أولاً قبل رفع ملفات بعد الانتهاء
                                </div>
                            @endif
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
        if (!['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'].includes(file.type)) {
            showError(`الملف ${file.name} ليس من النوع المدعوم. الصيغ المدعومة هي: JPG, JPEG, PNG, PDF`, modalBody);
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

// دالة للتحقق من طول النص وإخفاء زر العرض إذا كان النص قصير
function checkNotesLength() {
    document.querySelectorAll('.notes-content').forEach(notes => {
        const expandBtn = notes.nextElementSibling;
        if (notes.scrollHeight <= notes.clientHeight) {
            expandBtn.style.display = 'none';
        } else {
            expandBtn.style.display = 'inline-block';
        }
    });
}

// تشغيل فحص طول النص عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    checkNotesLength();
    
    // إعادة فحص طول النص عند تغيير حجم النافذة
    window.addEventListener('resize', checkNotesLength);
});

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
        if (!file.type.match('image/jpeg') && !file.type.match('image/png') && !file.type.match('image/jpg') && !file.type.match('application/pdf')) {
            alert(`الملف ${file.name} ليس من النوع المدعوم. الصيغ المدعومة هي: JPG, JPEG, PNG, PDF`);
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
        
        let previewElement;
        
        if (file.type === 'application/pdf') {
            // إنشاء معاينة للـ PDF
            previewElement = document.createElement('div');
            previewElement.className = 'card-img-top d-flex align-items-center justify-content-center bg-light';
            previewElement.style.height = '200px';
            previewElement.innerHTML = '<i class="fas fa-file-pdf fa-5x text-danger"></i>';
        } else {
            // إنشاء معاينة للصورة
            previewElement = document.createElement('img');
            previewElement.className = 'card-img-top';
            previewElement.style.height = '200px';
            previewElement.style.objectFit = 'cover';
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewElement.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
        
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
        card.appendChild(previewElement);
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

// دالة للتحكم في عرض/إخفاء النص
function toggleNotes(elementId) {
    const notesElement = document.getElementById(elementId);
    const expandBtn = notesElement.nextElementSibling;
    
    if (notesElement.classList.contains('expanded')) {
        notesElement.classList.remove('expanded');
        expandBtn.textContent = 'عرض المزيد';
        // تمرير إلى أعلى النص
        notesElement.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } else {
        notesElement.classList.add('expanded');
        expandBtn.textContent = 'عرض أقل';
    }
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

// دالة عرض الصورة في نافذة منبثقة محسنة
function viewImage(imageUrl, filename) {
    // إنشاء النافذة المنبثقة
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'imageViewModal';
    modal.setAttribute('tabindex', '-1');
    modal.setAttribute('aria-hidden', 'true');
    
    modal.innerHTML = `
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-image me-2"></i>عرض الصورة - ${filename}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-dark d-flex flex-column" style="height: calc(100vh - 140px);">
                    <!-- أزرار التحكم -->
                    <div class="image-controls mb-3 text-center">
                        <div class="btn-group me-3" role="group">
                            <button type="button" class="btn btn-outline-light btn-sm" onclick="rotateImage(-90)" title="تدوير يساراً">
                                <i class="fas fa-undo"></i> تدوير يساراً
                            </button>
                            <button type="button" class="btn btn-outline-light btn-sm" onclick="rotateImage(90)" title="تدوير يميناً">
                                <i class="fas fa-redo"></i> تدوير يميناً
                            </button>
                        </div>
                        <div class="btn-group me-3" role="group">
                            <button type="button" class="btn btn-outline-light btn-sm" onclick="zoomImage(1.2)" title="تكبير">
                                <i class="fas fa-search-plus"></i> تكبير
                            </button>
                            <button type="button" class="btn btn-outline-light btn-sm" onclick="zoomImage(0.8)" title="تصغير">
                                <i class="fas fa-search-minus"></i> تصغير
                            </button>
                            <button type="button" class="btn btn-outline-light btn-sm" onclick="resetImageTransform()" title="إعادة تعيين">
                                <i class="fas fa-expand-arrows-alt"></i> إعادة تعيين
                            </button>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-light btn-sm" onclick="toggleFullscreen()" title="ملء الشاشة">
                                <i class="fas fa-expand"></i> ملء الشاشة
                            </button>
                        </div>
                    </div>
                    
                    <!-- حاوي الصورة -->
                    <div class="image-container flex-grow-1 d-flex align-items-center justify-content-center position-relative overflow-hidden" 
                         style="cursor: grab;" 
                         onmousedown="startImageDrag(event)"
                         onmousemove="dragImage(event)"
                         onmouseup="stopImageDrag()"
                         onmouseleave="stopImageDrag()">
                        <img id="viewedImage" 
                             src="${imageUrl}" 
                             alt="${filename}" 
                             class="img-fluid user-select-none"
                             style="max-width: 100%; max-height: 100%; object-fit: contain; transition: transform 0.3s ease;"
                             draggable="false">
                    </div>
                    
                    <!-- معلومات الصورة -->
                    <div class="image-info text-center mt-2 text-white-50">
                        <small id="imageInfoText">استخدم الأزرار أعلاه للتحكم في عرض الصورة</small>
                        <div class="keyboard-shortcuts mt-2">
                            <small class="text-muted">
                                <i class="fas fa-keyboard me-1"></i>
                                الاختصارات: ← → (تدوير) | + - (تكبير/تصغير) | R (إعادة تعيين) | F (ملء الشاشة) | Esc (إغلاق)
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-dark border-top border-secondary">
                    <a href="${imageUrl}" download="${filename}" class="btn btn-success">
                        <i class="fas fa-download me-1"></i> تحميل الصورة
                    </a>
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> إغلاق
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // إضافة النافذة إلى الصفحة
    document.body.appendChild(modal);
    
    // إظهار النافذة
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
    
    // تهيئة متغيرات التحكم في الصورة
    window.imageRotation = 0;
    window.imageScale = 1;
    window.imageTranslateX = 0;
    window.imageTranslateY = 0;
    window.isDragging = false;
    window.dragStartX = 0;
    window.dragStartY = 0;
    
    // إضافة مستمعي الأحداث للتحكم بالماوس
    const imageContainer = modal.querySelector('.image-container');
    imageContainer.addEventListener('wheel', function(e) {
        e.preventDefault();
        const zoomFactor = e.deltaY > 0 ? 0.9 : 1.1;
        zoomImage(zoomFactor);
    });
    
    // إضافة دعم التحكم بلوحة المفاتيح
    const keyboardHandler = function(e) {
        switch(e.key) {
            case 'ArrowLeft':
                e.preventDefault();
                rotateImage(-90);
                break;
            case 'ArrowRight':
                e.preventDefault();
                rotateImage(90);
                break;
            case '+':
            case '=':
                e.preventDefault();
                zoomImage(1.2);
                break;
            case '-':
                e.preventDefault();
                zoomImage(0.8);
                break;
            case 'r':
            case 'R':
                e.preventDefault();
                resetImageTransform();
                break;
            case 'f':
            case 'F':
                e.preventDefault();
                toggleFullscreen();
                break;
            case 'Escape':
                bootstrapModal.hide();
                break;
        }
    };
    
    document.addEventListener('keydown', keyboardHandler);
    
    // إزالة النافذة عند الإغلاق
    modal.addEventListener('hidden.bs.modal', function () {
        document.body.removeChild(modal);
        // إزالة مستمع لوحة المفاتيح
        document.removeEventListener('keydown', keyboardHandler);
        // تنظيف المتغيرات العامة
        delete window.imageRotation;
        delete window.imageScale;
        delete window.imageTranslateX;
        delete window.imageTranslateY;
        delete window.isDragging;
        delete window.dragStartX;
        delete window.dragStartY;
    });
}

// دوال التحكم في الصورة
function rotateImage(degrees) {
    window.imageRotation = (window.imageRotation + degrees) % 360;
    updateImageTransform();
    updateImageInfo();
}

function zoomImage(factor) {
    window.imageScale *= factor;
    // تحديد الحد الأدنى والأقصى للتكبير
    window.imageScale = Math.max(0.1, Math.min(window.imageScale, 5));
    updateImageTransform();
    updateImageInfo();
}

function resetImageTransform() {
    window.imageRotation = 0;
    window.imageScale = 1;
    window.imageTranslateX = 0;
    window.imageTranslateY = 0;
    updateImageTransform();
    updateImageInfo();
}

function toggleFullscreen() {
    const modal = document.getElementById('imageViewModal');
    const modalDialog = modal.querySelector('.modal-dialog');
    
    if (modalDialog.classList.contains('modal-fullscreen')) {
        modalDialog.classList.remove('modal-fullscreen');
        modalDialog.classList.add('modal-xl');
        modal.querySelector('[onclick="toggleFullscreen()"] i').className = 'fas fa-expand';
        modal.querySelector('[onclick="toggleFullscreen()"]').title = 'ملء الشاشة';
    } else {
        modalDialog.classList.remove('modal-xl');
        modalDialog.classList.add('modal-fullscreen');
        modal.querySelector('[onclick="toggleFullscreen()"] i').className = 'fas fa-compress';
        modal.querySelector('[onclick="toggleFullscreen()"]').title = 'تصغير الشاشة';
    }
}

function updateImageTransform() {
    const image = document.getElementById('viewedImage');
    if (image) {
        const transform = `rotate(${window.imageRotation}deg) scale(${window.imageScale}) translate(${window.imageTranslateX}px, ${window.imageTranslateY}px)`;
        image.style.transform = transform;
    }
}

function updateImageInfo() {
    const infoText = document.getElementById('imageInfoText');
    if (infoText) {
        infoText.textContent = `التدوير: ${window.imageRotation}° | التكبير: ${Math.round(window.imageScale * 100)}%`;
    }
}

// دوال السحب والإفلات
function startImageDrag(e) {
    if (window.imageScale > 1) { // السماح بالسحب فقط عند التكبير
        window.isDragging = true;
        window.dragStartX = e.clientX - window.imageTranslateX;
        window.dragStartY = e.clientY - window.imageTranslateY;
        document.getElementById('viewedImage').style.cursor = 'grabbing';
    }
}

function dragImage(e) {
    if (window.isDragging) {
        e.preventDefault();
        window.imageTranslateX = e.clientX - window.dragStartX;
        window.imageTranslateY = e.clientY - window.dragStartY;
        updateImageTransform();
    }
}

function stopImageDrag() {
    window.isDragging = false;
    const image = document.getElementById('viewedImage');
    if (image) {
        image.style.cursor = window.imageScale > 1 ? 'grab' : 'default';
    }
}

// دالة للتحقق من عدد الملفات المسموح برفعها
function validateFileCount(input, maxFiles) {
    const files = input.files;
    
    if (files.length > maxFiles) {
        // إظهار رسالة تحذير
        const alert = document.createElement('div');
        alert.className = 'alert alert-warning alert-dismissible fade show mt-2';
        alert.innerHTML = `
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>تنبيه:</strong> يمكنك رفع حد أقصى ${maxFiles} ملف فقط. تم اختيار ${files.length} ملف.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // إضافة التنبيه بعد حقل الإدخال
        input.parentNode.parentNode.appendChild(alert);
        
        // مسح الملفات الزائدة
        const dt = new DataTransfer();
        for (let i = 0; i < Math.min(files.length, maxFiles); i++) {
            dt.items.add(files[i]);
        }
        input.files = dt.files;
        
        // إزالة التنبيه بعد 5 ثواني
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
        
        // تحديث معاينة الملفات
        updateFilePreview();
    } else {
        // إظهار رسالة نجاح
        const successAlert = document.createElement('div');
        successAlert.className = 'alert alert-success alert-dismissible fade show mt-2';
        successAlert.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            تم اختيار ${files.length} ملف بنجاح.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        input.parentNode.parentNode.appendChild(successAlert);
        
        // إزالة التنبيه بعد 3 ثواني
        setTimeout(() => {
            if (successAlert.parentNode) {
                successAlert.remove();
            }
        }, 3000);
        
        // تحديث معاينة الملفات
        updateFilePreview();
    }
}


// دالة لحذف ملفات بعد انتهاء العمل
function deleteCompletionFile(fileId) {
    if (confirm('هل أنت متأكد من حذف هذا الملف؟')) {
        fetch(`{{ url('admin/work-orders/survey/files') }}/${fileId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // إعادة تحميل الصفحة لإظهار التغييرات
                location.reload();
            } else {
                alert('حدث خطأ أثناء حذف الملف: ' + (data.message || 'خطأ غير معروف'));
            }
        })
        .catch(error => {
            console.error('خطأ:', error);
            alert('حدث خطأ أثناء حذف الملف');
        });
    }
}

// دوال قسم رفع ملفات بعد الانتهاء في أسفل الصفحة
function validateCompletionUploadFiles(input) {
    const files = input.files;
    const maxFiles = 100;
    const maxSizePerFile = 50 * 1024 * 1024; // 50MB
    
    // التحقق من عدد الملفات
    if (files.length > maxFiles) {
        showUploadAlert('warning', `يمكنك رفع حد أقصى ${maxFiles} ملف فقط. تم اختيار ${files.length} ملف.`);
        
        // تقليل عدد الملفات للحد المسموح
        const dt = new DataTransfer();
        for (let i = 0; i < Math.min(files.length, maxFiles); i++) {
            dt.items.add(files[i]);
        }
        input.files = dt.files;
    }
    
    // التحقق من حجم كل ملف
    let hasOversizedFiles = false;
    for (let i = 0; i < input.files.length; i++) {
        if (input.files[i].size > maxSizePerFile) {
            hasOversizedFiles = true;
            break;
        }
    }
    
    if (hasOversizedFiles) {
        showUploadAlert('warning', 'بعض الملفات تتجاوز الحد الأقصى المسموح (50MB لكل ملف).');
    }
    
    // تحديث عداد الملفات ومعاينتها
    updateUploadFilesCount(input.files.length);
    previewSelectedFiles(input.files);
    
    if (input.files.length > 0 && !hasOversizedFiles) {
        showUploadAlert('success', `تم اختيار ${input.files.length} ملف بنجاح.`);
    }
}

function showUploadAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-warning';
    const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
    
    const alert = document.createElement('div');
    alert.className = `alert ${alertClass} alert-dismissible fade show mt-2`;
    alert.innerHTML = `
        <i class="fas ${iconClass} me-2"></i>
        <strong>${type === 'success' ? 'نجح' : 'تنبيه'}:</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.getElementById('completion_files_upload').parentNode.parentNode.parentNode;
    container.appendChild(alert);
    
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}

function updateUploadFilesCount(count) {
    const countElement = document.getElementById('uploadFilesCount');
    const numberElement = document.getElementById('uploadFilesNumber');
    
    if (count > 0) {
        numberElement.textContent = count;
        countElement.style.display = 'block';
    } else {
        countElement.style.display = 'none';
    }
}

function previewSelectedFiles(files) {
    const previewSection = document.getElementById('selectedFilesPreview');
    const previewContainer = document.getElementById('previewContainer');
    
    if (files.length > 0) {
        previewContainer.innerHTML = '';
        
        Array.from(files).forEach((file, index) => {
            const col = document.createElement('div');
            col.className = 'col-md-2 col-sm-3 col-4';
            
            const extension = file.name.split('.').pop().toLowerCase();
            let iconClass = 'fa-file';
            let iconColor = 'text-muted';
            
            if (['jpg', 'jpeg', 'png'].includes(extension)) {
                iconClass = 'fa-image';
                iconColor = 'text-primary';
            } else if (extension === 'pdf') {
                iconClass = 'fa-file-pdf';
                iconColor = 'text-danger';
            } else if (['doc', 'docx'].includes(extension)) {
                iconClass = 'fa-file-word';
                iconColor = 'text-primary';
            } else if (['xls', 'xlsx'].includes(extension)) {
                iconClass = 'fa-file-excel';
                iconColor = 'text-success';
            }
            
            col.innerHTML = `
                <div class="card h-100 text-center p-2">
                    <div class="card-body p-1">
                        <i class="fas ${iconClass} fa-2x ${iconColor} mb-2"></i>
                        <p class="card-text small mb-0" title="${file.name}">
                            ${file.name.length > 15 ? file.name.substring(0, 15) + '...' : file.name}
                        </p>
                        <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                    </div>
                </div>
            `;
            
            previewContainer.appendChild(col);
        });
        
        previewSection.style.display = 'block';
    } else {
        previewSection.style.display = 'none';
    }
}

function openCompletionUploadCamera() {
    const input = document.getElementById('completion_files_upload');
    if (input) {
        input.setAttribute('accept', 'image/*');
        input.setAttribute('capture', 'environment');
        input.click();
        
        setTimeout(() => {
            input.setAttribute('accept', 'image/*,application/pdf,.doc,.docx,.xlsx,.xls');
            input.removeAttribute('capture');
        }, 100);
    }
}

function clearCompletionFiles() {
    const input = document.getElementById('completion_files_upload');
    const previewSection = document.getElementById('selectedFilesPreview');
    const countElement = document.getElementById('uploadFilesCount');
    
    input.value = '';
    previewSection.style.display = 'none';
    countElement.style.display = 'none';
    
    showUploadAlert('success', 'تم مسح جميع الملفات المحددة.');
}

// معالجة إرسال النموذج
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('completionFilesForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const surveyId = document.getElementById('survey_selection').value;
            const filesInput = document.getElementById('completion_files_upload');
            
            if (!surveyId) {
                alert('يرجى اختيار المسح المرتبط');
                return;
            }
            
            if (!filesInput.files.length) {
                alert('يرجى اختيار ملفات للرفع');
                return;
            }
            
            const formData = new FormData();
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            formData.append('survey_id', surveyId);
            
            Array.from(filesInput.files).forEach(file => {
                formData.append('completion_images[]', file);
            });
            
            // إظهار رسالة التحميل
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> جاري الرفع...';
            
            fetch(`{{ route('admin.work-orders.survey.completion-files', $workOrder) }}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showUploadAlert('success', 'تم رفع الملفات بنجاح!');
                    clearCompletionFiles();
                    // إعادة تحميل الصفحة لإظهار الملفات الجديدة
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    showUploadAlert('warning', 'حدث خطأ أثناء رفع الملفات: ' + (data.message || 'خطأ غير معروف'));
                }
            })
            .catch(error => {
                console.error('خطأ:', error);
                showUploadAlert('warning', 'حدث خطأ أثناء رفع الملفات');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
});
</script>

<style>
.table th, .table td {
    vertical-align: middle;
}

.notes-cell {
    min-width: 250px;
    max-width: 400px;
    position: relative;
}

.notes-content {
    white-space: pre-wrap;
    word-wrap: break-word;
    max-height: 4.5em;
    overflow: hidden;
    position: relative;
}

.notes-content.expanded {
    max-height: none;
}

.notes-content:not(.expanded)::after {
    content: "...";
    position: absolute;
    bottom: 0;
    right: 0;
    background: linear-gradient(to left, white 50%, transparent);
    padding-left: 20px;
}

.expand-btn {
    color: #4e73df;
    cursor: pointer;
    font-size: 0.8rem;
    margin-top: 0.25rem;
    display: inline-block;
}

.expand-btn:hover {
    text-decoration: underline;
}

.card-img-top {
    height: 200px;
    object-fit: cover;
}

.card-img-top[onclick] {
    transition: transform 0.3s ease, filter 0.3s ease;
}

.card-img-top[onclick]:hover {
    transform: scale(1.05);
    filter: brightness(1.1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.modal-xl {
    max-width: 90%;
}

@media (max-width: 768px) {
    .modal-xl {
        max-width: 100%;
    }
}

/* تحسينات عارض الصور المطور */
#imageViewModal .modal-content {
    background: #212529;
    border: none;
}

#imageViewModal .modal-header {
    border-bottom: 1px solid #495057;
}

#imageViewModal .modal-footer {
    border-top: 1px solid #495057;
}

.image-controls {
    background: rgba(0, 0, 0, 0.3);
    border-radius: 10px;
    padding: 15px;
    backdrop-filter: blur(5px);
}

.image-controls .btn {
    margin: 0 2px;
    border-radius: 8px;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.image-controls .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.2);
}

.image-container {
    background: radial-gradient(circle at center, rgba(255, 255, 255, 0.1) 0%, rgba(0, 0, 0, 0.8) 100%);
    border-radius: 10px;
    position: relative;
    overflow: hidden;
}

.image-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        linear-gradient(45deg, transparent 45%, rgba(255, 255, 255, 0.02) 49%, rgba(255, 255, 255, 0.02) 51%, transparent 55%),
        linear-gradient(-45deg, transparent 45%, rgba(255, 255, 255, 0.02) 49%, rgba(255, 255, 255, 0.02) 51%, transparent 55%);
    background-size: 20px 20px;
    pointer-events: none;
    opacity: 0.3;
}

#viewedImage {
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.3));
}

.image-info {
    background: rgba(0, 0, 0, 0.6);
    border-radius: 20px;
    padding: 8px 16px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* تأثيرات الأزرار */
.btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
}

/* تحسينات للشاشات الصغيرة */
@media (max-width: 768px) {
    .image-controls {
        padding: 10px;
        margin-bottom: 15px;
    }
    
    .image-controls .btn {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
        margin: 1px;
    }
    
    .image-controls .btn-group {
        margin-right: 8px;
        margin-bottom: 8px;
    }
    
    .image-controls .btn-group:last-child {
        margin-right: 0;
    }
    
    .modal-body {
        padding: 10px;
    }
    
    #imageViewModal .modal-title {
        font-size: 1rem;
    }
}

/* تأثيرات التحميل */
.image-container.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 40px;
    height: 40px;
    margin: -20px 0 0 -20px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* تحسين شكل المؤشر */
.image-container[style*="cursor: grab"] {
    cursor: grab !important;
}

.image-container[style*="cursor: grabbing"] {
    cursor: grabbing !important;
}

/* تأثير النقر على الأزرار */
.image-controls .btn:active {
    transform: translateY(0) scale(0.95);
}

/* تحسين شكل الـ tooltip */
.image-controls .btn[title]:hover::after {
    content: attr(title);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    white-space: nowrap;
    z-index: 1000;
    margin-bottom: 5px;
}

.image-controls .btn[title]:hover::before {
    content: '';
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 4px solid transparent;
    border-top-color: rgba(0, 0, 0, 0.8);
    z-index: 1000;
}

/* إصلاح مشكلة الـ modal backdrop */
.modal-backdrop {
    z-index: 1050 !important;
    pointer-events: none !important;
}

#createSurveyModal {
    z-index: 1055 !important;
}

#createSurveyModal .modal-dialog {
    z-index: 1056 !important;
    pointer-events: auto !important;
}

#createSurveyModal .modal-content {
    pointer-events: auto !important;
    position: relative;
    z-index: 1057 !important;
}

#createSurveyModal .modal-body {
    pointer-events: auto !important;
}

#createSurveyModal input,
#createSurveyModal textarea,
#createSurveyModal select,
#createSurveyModal button {
    pointer-events: auto !important;
    user-select: text !important;
    -webkit-user-select: text !important;
    -moz-user-select: text !important;
    -ms-user-select: text !important;
}

#createSurveyModal input,
#createSurveyModal textarea {
    cursor: text !important;
}

#createSurveyModal select,
#createSurveyModal button {
    cursor: pointer !important;
}
</style>

<script>
// إصلاح مشكلة الـ backdrop للنافذة المنبثقة
(function() {
    const createSurveyModal = document.getElementById('createSurveyModal');
    
    if (createSurveyModal) {
        // عند فتح النافذة
        createSurveyModal.addEventListener('show.bs.modal', function() {
            setTimeout(function() {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(function(backdrop) {
                    backdrop.style.pointerEvents = 'none';
                });
            }, 50);
        });
        
        // عند ظهور النافذة بشكل كامل
        createSurveyModal.addEventListener('shown.bs.modal', function() {
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(function(backdrop) {
                backdrop.style.pointerEvents = 'none';
            });
            
            // تفعيل جميع الحقول
            const allInputs = createSurveyModal.querySelectorAll('input, textarea, select, button');
            allInputs.forEach(function(element) {
                element.style.pointerEvents = 'auto';
                element.removeAttribute('readonly');
                element.removeAttribute('disabled');
                element.style.userSelect = 'text';
            });
            
            // التركيز على أول حقل
            const firstInput = createSurveyModal.querySelector('input:not([type="hidden"])');
            if (firstInput) {
                setTimeout(function() {
                    firstInput.focus();
                    firstInput.click();
                }, 100);
            }
        });
    }
    
    // مراقبة مستمرة للـ backdrop
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1 && node.classList && node.classList.contains('modal-backdrop')) {
                    node.style.pointerEvents = 'none';
                }
            });
        });
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
})();
</script>
@endsection 