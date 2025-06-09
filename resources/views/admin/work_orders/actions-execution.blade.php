@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0" style="font-size:2.1rem; font-weight:800; color:#2c3e50; letter-spacing:1px; display:flex; align-items:center;">
            <i class="fas fa-tasks me-2" style="color:#007bff;"></i>
            إجراءات ما بعد التنفيذ
        </h2>
        <a href="{{ route('admin.work-orders.show', $workOrder->id) }}" class="btn btn-outline-secondary">&larr; عودة إلى تفاصيل أمر العمل</a>
    </div>
    {{-- رسائل التنبيه --}}
    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger text-center">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- كارد الحقول النصية --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">بيانات أوامر الشراء والإدخال</div>
        <div class="card-body">
            <form action="{{ route('admin.work-orders.update', $workOrder->id) }}" method="POST" class="row g-2 align-items-center mb-3">
                @csrf
                @method('PUT')
                <input type="hidden" name="_section" value="extract_number_group">
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">رقم أمر الشراء:</label>
                    <input type="text" name="purchase_order_number" class="form-control" value="{{ old('purchase_order_number', $workOrder->purchase_order_number) }}">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">صحيفة الإدخال:</label>
                    <input type="text" name="entry_sheet" class="form-control" value="{{ $workOrder->entry_sheet }}">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">رقم المستخلص:</label>
                    <input type="text" name="extract_number" class="form-control" value="{{ old('extract_number', $workOrder->extract_number) }}">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">قيمة التنفيذ الفعلي للاستشاري:</label>
                    <input type="number" step="0.01" name="actual_execution_value_consultant" class="form-control" value="{{ old('actual_execution_value_consultant', $workOrder->actual_execution_value_consultant) }}">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">قيمة التنفيذ الفعلي غير شامل الاستشاري:</label>
                    <input type="number" step="0.01" name="actual_execution_value_without_consultant" class="form-control" value="{{ old('actual_execution_value_without_consultant', $workOrder->actual_execution_value_without_consultant) }}">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">قيمة الدفعة الجزئية الأولى غير شامل الضريبة:</label>
                    <input type="number" step="0.01" name="first_partial_payment_without_tax" class="form-control" value="{{ old('first_partial_payment_without_tax', $workOrder->first_partial_payment_without_tax) }}">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">قيمة الدفعة الجزئية الثانية شامل الضريبة الدفعتين:</label>
                    <input type="number" step="0.01" name="second_partial_payment_with_tax" class="form-control" value="{{ old('second_partial_payment_with_tax', $workOrder->second_partial_payment_with_tax) }}">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">قيمة الضريبة:</label>
                    <input type="number" step="0.01" name="tax_value" class="form-control" value="{{ old('tax_value', $workOrder->tax_value) }}">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">تاريخ تسليم إجراء 155:</label>
                    <input type="date" name="procedure_155_delivery_date" class="form-control" value="{{ old('procedure_155_delivery_date', $workOrder->procedure_155_delivery_date ? $workOrder->procedure_155_delivery_date->format('Y-m-d') : '') }}">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label">القيمة الكلية النهائية:</label>
                    <input type="number" step="0.01" name="final_total_value" class="form-control" value="{{ old('final_total_value', $workOrder->final_total_value) }}">
                </div>
                <div class="form-group mb-3">
                                    <label for="execution_status" class="form-label fw-bold">حالة تنفيذ أمر العمل</label>
                                    <select id="execution_status" class="form-select @error('execution_status') is-invalid @enderror" name="execution_status">
                                        <option value="2" {{ old('execution_status', $workOrder->execution_status) == '2' ? 'selected' : '' }}>جاري العمل ...</option>
                                        <option value="1" {{ old('execution_status', $workOrder->execution_status) == '1' ? 'selected' : '' }}> تم تسليم 155 ولم تصدر شهادة انجاز </option>
                                        <option value="3" {{ old('execution_status', $workOrder->execution_status) == '3' ? 'selected' : '' }}> صدرت شهادة ولم تعتمد</option>
                                        <option value="4" {{ old('execution_status', $workOrder->execution_status) == '4' ? 'selected' : '' }}> تم اعتماد شهادة الانجاز</option>
                                        <option value="5" {{ old('execution_status', $workOrder->execution_status) == '5' ? 'selected' : '' }}>مؤكد ولم تدخل مستخلص </option>
                                        <option value="6" {{ old('execution_status', $workOrder->execution_status) == '6' ? 'selected' : '' }}> دخلت مستخلص ولم تصرف </option>
                                        <option value="7" {{ old('execution_status', $workOrder->execution_status) == '7' ? 'selected' : '' }}> منتهي تم الصرف </option>
                                    </select>
                                    @error('execution_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                <div class="col-12 text-center mt-3">
                    <button type="submit" class="btn btn-primary px-4">حفظ البيانات</button>
                </div>
            </form>
        </div>
    </div>

    {{-- كارد المرفقات --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">المرفقات</div>
        <div class="card-body">
            <!-- فورم رفع الملفات -->
            <form id="uploadFilesForm" action="{{ route('admin.work-orders.upload-post-execution-file', $workOrder->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    @php
                        $fileTypes = [
                            'quantities_statement_file' => 'بيان كميات التنفيذ',
                            'final_materials_file' => 'كميات المواد النهائية',
                            'final_measurement_file' => 'ورقة القياس النهائية',
                            'soil_tests_file' => 'اختبارات التربة',
                            'site_drawing_file' => 'الرسم الهندسي للموقع',
                            'modified_estimate_file' => 'تعديل المقايسة',
                            'completion_certificate_file' => 'شهادة الانجاز',
                            'form_200_file' => 'نموذج 200',
                            'form_190_file' => 'نموذج 190',
                            'pre_operation_tests_file' => 'اختبارات ما قبل التشغيل 211',
                            'first_payment_extract_file' => 'مستخلص دفعة أولى',
                            'second_payment_extract_file' => 'مستخلص دفعة ثانية',
                            'total_extract_file' => 'مستخلص كلي',
                        ];
                    @endphp
                    @foreach($fileTypes as $field => $label)
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ $label }}</label>
                            <input type="file" name="{{ $field }}" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            @error($field)
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary px-4">رفع الملفات</button>
                </div>
            </form>

            <!-- جدول عرض المرفقات -->
            <div class="table-responsive mt-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>نوع المرفق</th>
                            <th>الحالة الحالية</th>
                            <th>عرض الملف</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fileTypes as $field => $label)
                            @php
                                $file = $workOrder->files->where('file_category', 'post_execution')
                                    ->where('attachment_type', $field)
                                    ->first();
                            @endphp
                            <tr>
                                <td>{{ $label }}</td>
                                <td>
                                    @if($file)
                                        <span class="badge bg-success">تم الرفع</span>
                                        <small class="d-block text-muted mt-1">{{ $file->created_at->format('Y-m-d H:i') }}</small>
                                    @else
                                        <span class="badge bg-secondary">لم يتم الرفع</span>
                                    @endif
                                </td>
                                <td>
                                    @if($file)
                                        <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-file-pdf"></i> عرض
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- صور التنفيذ --}}
    @if(isset($executionImages) && $executionImages->count())
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <span>صور التنفيذ (الأعمال المدنية، التركيبات، الكهرباء)</span>
            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#viewAllImagesModal">
                <i class="fas fa-images"></i> عرض جميع الصور
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($executionImages as $img)
                    <div class="col-6 col-md-3 col-lg-2 mb-3">
                        <div class="card h-100">
                            <img src="{{ Storage::url($img->file_path) }}" 
                                 class="card-img-top" 
                                 style="height: 120px; object-fit: cover;"
                                 alt="صورة التنفيذ"
                                 data-bs-toggle="modal" 
                                 data-bs-target="#viewImageModal"
                                 data-image-url="{{ Storage::url($img->file_path) }}"
                                 data-image-name="{{ $img->original_filename }}"
                                 data-image-date="{{ $img->created_at->format('Y-m-d H:i') }}"
                                 style="cursor: pointer;">
                            <div class="card-body p-2">
                                <small class="text-muted d-block text-truncate">{{ $img->original_filename }}</small>
                                <small class="text-muted d-block">{{ $img->created_at->format('Y-m-d H:i') }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Modal لعرض صورة واحدة --}}
    <div class="modal fade" id="viewImageModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">عرض الصورة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalImage" class="img-fluid" alt="صورة التنفيذ">
                    <div class="mt-2">
                        <small class="text-muted" id="modalImageName"></small><br>
                        <small class="text-muted" id="modalImageDate"></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal لعرض جميع الصور --}}
    <div class="modal fade" id="viewAllImagesModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">جميع صور التنفيذ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @foreach($executionImages as $img)
                            <div class="col-6 col-md-4 col-lg-3 mb-3">
                                <div class="card h-100">
                                    <img src="{{ Storage::url($img->file_path) }}" 
                                         class="card-img-top" 
                                         style="height: 150px; object-fit: cover; cursor: pointer;"
                                         onclick="openImageModal(this)"
                                         data-image-url="{{ Storage::url($img->file_path) }}"
                                         data-image-name="{{ $img->original_filename }}"
                                         data-image-date="{{ $img->created_at->format('Y-m-d H:i') }}"
                                         alt="صورة التنفيذ">
                                    <div class="card-body p-2">
                                        <small class="text-muted d-block text-truncate">{{ $img->original_filename }}</small>
                                        <small class="text-muted d-block">{{ $img->created_at->format('Y-m-d H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- مرفقات الفاتورة --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span>مرفقات الفاتورة</span>
            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#uploadInvoiceAttachmentsModal">
                <i class="fas fa-upload"></i> رفع مرفقات جديدة
            </button>
        </div>
        <div class="card-body">
            @if($workOrder->invoiceAttachments->count() > 0)
                <div class="row">
                    @foreach($workOrder->invoiceAttachments as $attachment)
                        <div class="col-6 col-md-4 col-lg-3 mb-3">
                            <div class="card h-100">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-info">{{ $attachment->file_type ?? 'غير محدد' }}</span>
                                        <div class="btn-group">
                                            <a href="{{ Storage::url($attachment->file_path) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-info"
                                               title="عرض الملف">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <h6 class="card-title text-truncate mb-1" title="{{ $attachment->original_filename }}">
                                        {{ $attachment->original_filename }}
                                    </h6>
                                    @if($attachment->description)
                                        <p class="card-text small text-muted mb-1">{{ $attachment->description }}</p>
                                    @endif
                                    <small class="text-muted d-block">
                                        {{ $attachment->created_at->format('Y-m-d H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-file-invoice fa-3x mb-3"></i>
                    <p class="mb-0">لا توجد مرفقات للفاتورة</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal رفع مرفقات جديدة --}}
    <div class="modal fade" id="uploadInvoiceAttachmentsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">رفع مرفقات الفاتورة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.work-orders.invoice-attachments.store', $workOrder) }}" 
                      method="POST" 
                      enctype="multipart/form-data"
                      id="uploadInvoiceAttachmentsForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اختر الملفات (يمكن اختيار أكثر من ملف)</label>
                            <input type="file" 
                                   name="attachments[]" 
                                   class="form-control" 
                                   multiple 
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   required>
                            <small class="text-muted">يمكن رفع حتى 20 ملف. الأنواع المدعومة: PDF, JPG, JPEG, PNG</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">نوع المرفقات</label>
                            <input type="text" 
                                   name="file_type" 
                                   class="form-control" 
                                   placeholder="مثال: فاتورة، إشعار، مستند..."
                                   required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">وصف المرفقات (اختياري)</label>
                            <textarea name="description" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="أدخل وصفاً مختصراً للمرفقات..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> رفع المرفقات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- صور التنفيذ للأعمال المدنية --}}
    @php
        $civilWorksExecutionImages = \App\Models\WorkOrderFile::where('work_order_id', $workOrder->id)
                                                    ->where('file_category', 'civil_exec')
            ->where('file_type', 'like', 'image/%')
            ->orderBy('created_at', 'desc')
            ->get();
    @endphp
    
    @if($civilWorksExecutionImages->count() > 0)
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span>
                <i class="fas fa-camera me-2"></i>
                صور التنفيذ - الأعمال المدنية
                <span class="badge bg-light text-dark ms-2">{{ $civilWorksExecutionImages->count() }} صورة</span>
            </span>
            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#viewCivilWorksImagesModal">
                <i class="fas fa-images"></i> عرض جميع الصور
            </button>
        </div>
        <div class="card-body">
            <div class="alert alert-success border-0">
                <i class="fas fa-info-circle me-2"></i>
                هذه الصور تم رفعها من صفحة الأعمال المدنية وتُظهر تفاصيل التنفيذ الفعلي
            </div>
            
            <div class="row">
                @foreach($civilWorksExecutionImages->take(8) as $image)
                    <div class="col-6 col-md-3 col-lg-2 mb-3">
                        <div class="card h-100 border-success">
                            <img src="@imageUrl($image->file_path)" 
                                 class="card-img-top" 
                                 style="height: 120px; object-fit: cover; cursor: pointer;"
                                 alt="صورة الأعمال المدنية"
                                 data-bs-toggle="modal" 
                                 data-bs-target="#viewCivilWorksImageModal"
                                 data-image-url="@imageUrl($image->file_path)"
                                 data-image-name="{{ $image->original_filename }}"
                                 data-image-date="{{ $image->created_at->format('Y-m-d H:i') }}">
                            <div class="card-body p-2">
                                <small class="text-success fw-bold d-block text-truncate">{{ $image->original_filename }}</small>
                                <small class="text-muted d-block">{{ $image->created_at->format('Y-m-d H:i') }}</small>
                                <small class="badge bg-success">{{ round($image->file_size / 1024 / 1024, 2) }} MB</small>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                @if($civilWorksExecutionImages->count() > 8)
                    <div class="col-12 text-center mt-3">
                        <p class="text-muted">
                            <i class="fas fa-images me-2"></i>
                            و {{ $civilWorksExecutionImages->count() - 8 }} صورة أخرى... 
                            <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#viewCivilWorksImagesModal">
                                اضغط لعرض الجميع
                            </button>
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-secondary text-white">
            <i class="fas fa-camera me-2"></i>
            صور التنفيذ - الأعمال المدنية
        </div>
        <div class="card-body text-center py-5">
            <i class="fas fa-camera fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">لا توجد صور للأعمال المدنية</h5>
            <p class="text-muted mb-3">يمكنك رفع الصور من صفحة الأعمال المدنية</p>
            <a href="{{ route('admin.work-orders.civil-works', $workOrder) }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>الذهاب لرفع الصور
            </a>
        </div>
    </div>
    @endif

    {{-- Modal لعرض صورة واحدة من الأعمال المدنية --}}
    <div class="modal fade" id="viewCivilWorksImageModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-camera me-2"></i>صورة الأعمال المدنية
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalCivilWorksImage" class="img-fluid rounded shadow" alt="صورة الأعمال المدنية">
                    <div class="mt-3 p-3 bg-light rounded">
                        <div class="row">
                            <div class="col-md-6">
                                <strong class="text-success">اسم الملف:</strong>
                                <p class="mb-1" id="modalCivilWorksImageName"></p>
                            </div>
                            <div class="col-md-6">
                                <strong class="text-success">تاريخ الرفع:</strong>
                                <p class="mb-1" id="modalCivilWorksImageDate"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <a href="" id="downloadCivilWorksImageBtn" target="_blank" class="btn btn-success">
                        <i class="fas fa-download me-2"></i>تحميل الصورة
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal لعرض جميع صور الأعمال المدنية --}}
    <div class="modal fade" id="viewCivilWorksImagesModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-images me-2"></i>
                        جميع صور الأعمال المدنية ({{ $civilWorksExecutionImages->count() }} صورة)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @foreach($civilWorksExecutionImages as $image)
                            <div class="col-6 col-md-4 col-lg-3 mb-3">
                                <div class="card h-100 border-success">
                                    <img src="@imageUrl($image->file_path)" 
                                         class="card-img-top" 
                                         style="height: 150px; object-fit: cover; cursor: pointer;"
                                         onclick="openCivilWorksImageModal(this)"
                                         data-image-url="@imageUrl($image->file_path)"
                                         data-image-name="{{ $image->original_filename }}"
                                         data-image-date="{{ $image->created_at->format('Y-m-d H:i') }}"
                                         alt="صورة الأعمال المدنية">
                                    <div class="card-body p-2">
                                        <small class="text-success fw-bold d-block text-truncate">{{ $image->original_filename }}</small>
                                        <small class="text-muted d-block">{{ $image->created_at->format('Y-m-d H:i') }}</small>
                                        <small class="badge bg-success">{{ round($image->file_size / 1024 / 1024, 2) }} MB</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($civilWorksExecutionImages->count() == 0)
                        <div class="text-center py-5">
                            <i class="fas fa-camera fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد صور للأعمال المدنية</h5>
                            <p class="text-muted">يمكنك رفع الصور من صفحة الأعمال المدنية</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // تحديث بيانات الصورة في المودال عند النقر على صورة
        document.querySelectorAll('[data-bs-target="#viewImageModal"]').forEach(img => {
            img.addEventListener('click', function() {
                const modal = document.getElementById('viewImageModal');
                modal.querySelector('#modalImage').src = this.dataset.imageUrl;
                modal.querySelector('#modalImageName').textContent = this.dataset.imageName;
                modal.querySelector('#modalImageDate').textContent = this.dataset.imageDate;
            });
        });

        // التعامل مع modal صور الأعمال المدنية
        document.querySelectorAll('[data-bs-target="#viewCivilWorksImageModal"]').forEach(img => {
            img.addEventListener('click', function() {
                const modal = document.getElementById('viewCivilWorksImageModal');
                modal.querySelector('#modalCivilWorksImage').src = this.dataset.imageUrl;
                modal.querySelector('#modalCivilWorksImageName').textContent = this.dataset.imageName;
                modal.querySelector('#modalCivilWorksImageDate').textContent = this.dataset.imageDate;
                modal.querySelector('#downloadCivilWorksImageBtn').href = this.dataset.imageUrl;
            });
        });

        // دالة لفتح مودال الصورة من عرض جميع الصور
        function openImageModal(img) {
            const modal = document.getElementById('viewImageModal');
            modal.querySelector('#modalImage').src = img.dataset.imageUrl;
            modal.querySelector('#modalImageName').textContent = img.dataset.imageName;
            modal.querySelector('#modalImageDate').textContent = img.dataset.imageDate;
            new bootstrap.Modal(modal).show();
        }

        // دالة لفتح مودال صور الأعمال المدنية
        function openCivilWorksImageModal(img) {
            const modal = document.getElementById('viewCivilWorksImageModal');
            modal.querySelector('#modalCivilWorksImage').src = img.dataset.imageUrl;
            modal.querySelector('#modalCivilWorksImageName').textContent = img.dataset.imageName;
            modal.querySelector('#modalCivilWorksImageDate').textContent = img.dataset.imageDate;
            modal.querySelector('#downloadCivilWorksImageBtn').href = img.dataset.imageUrl;
            new bootstrap.Modal(modal).show();
        }

        // التحقق من عدد الملفات قبل الرفع
        document.getElementById('uploadInvoiceAttachmentsForm').addEventListener('submit', function(e) {
            const fileInput = this.querySelector('input[type="file"]');
            if (fileInput.files.length > 20) {
                e.preventDefault();
                alert('يمكن رفع 20 ملف كحد أقصى');
            }
        });

        document.getElementById('uploadFilesForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تم رفع الملفات بنجاح!');
                    location.reload();
                } else {
                    alert(data.message || 'حدث خطأ أثناء رفع الملفات');
                }
            })
            .catch(error => {
                alert('حدث خطأ أثناء رفع الملفات');
                console.error(error);
            });
        });
    </script>
    @endpush
</div>
@endsection 