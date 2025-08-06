@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 fs-4">تعديل أمر العمل</h3>
                    <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right me-1"></i> عودة لتفاصيل أمر العمل
                    </a>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.work-orders.update', $workOrder) }}" class="custom-form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="order_number" class="form-label fw-bold">رقم أمر العمل</label>
                                    <input id="order_number" type="text" class="form-control @error('order_number') is-invalid @enderror" name="order_number" value="{{ old('order_number', $workOrder->order_number) }}" autofocus>
                                    @error('order_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="work_type" class="form-label fw-bold">نوع العمل</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input id="work_type_number" type="number" min="1" max="999" class="form-control mb-2" placeholder="أدخل رقم نوع العمل" value="{{ old('work_type', $workOrder->work_type) }}">
                                        </div>
                                        <div class="col-md-8">
                                            <select id="work_type" class="form-select @error('work_type') is-invalid @enderror" name="work_type">
                                                <option value="">اختر نوع العمل</option>
                                                <option value="409" {{ old('work_type', $workOrder->work_type) == '409' ? 'selected' : '' }}> -ازالة-نقل شبكة على المشترك</option>
                                                <option value="408" {{ old('work_type', $workOrder->work_type) == '408' ? 'selected' : '' }}>-  ازاله عداد على المشترك</option>
                                                <option value="460" {{ old('work_type', $workOrder->work_type) == '460' ? 'selected' : '' }}>-  استبدال شبكات</option>
                                                <option value="901" {{ old('work_type', $workOrder->work_type) == '901' ? 'selected' : '' }}> -   اضافة  عداد  طاقة  شمسية</option>
                                                <option value="440" {{ old('work_type', $workOrder->work_type) == '440' ? 'selected' : '' }}> - الرفع المساحي</option>
                                                <option value="410" {{ old('work_type', $workOrder->work_type) == '410' ? 'selected' : '' }}>-  انشاء محطة/محول لمشترك/مشتركين </option>
                                                <option value="801" {{ old('work_type', $workOrder->work_type) == '801' ? 'selected' : '' }}>-  تركيب عداد  ايصال سريع </option>
                                                <option value="804" {{ old('work_type', $workOrder->work_type) == '804' ? 'selected' : '' }}> -  تركيب محطة ش ارضية VM ايصال سريع</option>
                                                <option value="805" {{ old('work_type', $workOrder->work_type) == '805' ? 'selected' : '' }}> - تركيب محطة ش هوائية VM ايصال سريع</option>
                                                <option value="480" {{ old('work_type', $workOrder->work_type) == '480' ? 'selected' : '' }}> -  (تسليم مفتاح) تمويل خارجي </option>
                                                <option value="441" {{ old('work_type', $workOrder->work_type) == '441' ? 'selected' : '' }}> -  تعزيز  شبكة  أرضية  ومحطات </option>
                                                <option value="442" {{ old('work_type', $workOrder->work_type) == '442' ? 'selected' : '' }}> -  تعزيز شبكة هوائية ومحطات </option>
                                                <option value="802" {{ old('work_type', $workOrder->work_type) == '802' ? 'selected' : '' }}> -  شبكة ارضية VL ايصال سريع</option>
                                                <option value="803" {{ old('work_type', $workOrder->work_type) == '803' ? 'selected' : '' }}> -  شبكة هوائية VL ايصال سريع</option>
                                                <option value="402" {{ old('work_type', $workOrder->work_type) == '402' ? 'selected' : '' }}>-  توصيل عداد بحفرية شبكة ارضيه </option>
                                                <option value="401" {{ old('work_type', $workOrder->work_type) == '401' ? 'selected' : '' }}> -  (عداد بدون حفرية ) أرضي/هوائي </option>
                                                <option value="404" {{ old('work_type', $workOrder->work_type) == '404' ? 'selected' : '' }}> -  عداد بمحطة شبكة ارضية VM</option>
                                                <option value="405" {{ old('work_type', $workOrder->work_type) == '405' ? 'selected' : '' }}> -  توصيل عداد بمحطة شبكة هوائية VM</option>
                                                <option value="430" {{ old('work_type', $workOrder->work_type) == '430' ? 'selected' : '' }}> -  مخططات منح  وزارة  البلدية </option>
                                                <option value="450" {{ old('work_type', $workOrder->work_type) == '450' ? 'selected' : '' }}>- مشاريع ربط محطات التحويل</option>
                                                <option value="403" {{ old('work_type', $workOrder->work_type) == '403' ? 'selected' : '' }}> -  توصيل عداد شبكة هوائية VL</option>
                                            </select>
                                        </div>
                                    </div>
                                    @error('work_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="work_description" class="form-label fw-bold">وصف العمل والتعليق</label>
                                    <textarea id="work_description" class="form-control @error('work_description') is-invalid @enderror" name="work_description" rows="5" placeholder="أدخل وصف العمل والتعليق هنا...">{{ old('work_description', $workOrder->work_description) }}</textarea>
                                    @error('work_description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="approval_date" class="form-label fw-bold">تاريخ الاعتماد</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input id="approval_date" type="date" class="form-control @error('approval_date') is-invalid @enderror" name="approval_date" value="{{ old('approval_date', $workOrder->approval_date->format('Y-m-d')) }}" onchange="updateCountdown()">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-clock me-1"></i>
                                                    <span id="countdown" class="text-muted">-</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">المتبقي</span>
                                                <input type="number" id="manual_days" name="manual_days" class="form-control @error('manual_days') is-invalid @enderror" min="0" value="{{ old('manual_days', $workOrder->manual_days) }}" placeholder="أدخل عدد الأيام المتبقية" onchange="updateManualDays(this.value)">
                                                <span class="input-group-text bg-light">يوم</span>
                                            </div>
                                        </div>
                                    </div>
                                    @error('manual_days')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    @error('approval_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="subscriber_name" class="form-label fw-bold">اسم المشترك</label>
                                    <input id="subscriber_name" type="text" class="form-control @error('subscriber_name') is-invalid @enderror" name="subscriber_name" value="{{ old('subscriber_name', $workOrder->subscriber_name) }}">
                                    @error('subscriber_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="district" class="form-label fw-bold">الحي</label>
                                    <input id="district" type="text" class="form-control @error('district') is-invalid @enderror" name="district" value="{{ old('district', $workOrder->district) }}">
                                    @error('district')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="municipality" class="form-label fw-bold">البلدية</label>
                                    <input id="municipality" type="text" class="form-control @error('municipality') is-invalid @enderror" name="municipality" value="{{ old('municipality', $workOrder->municipality) }}">
                                    @error('municipality')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="station_number" class="form-label fw-bold">رقم المحطة</label>
                                    <input id="station_number" type="text" class="form-control @error('station_number') is-invalid @enderror" name="station_number" value="{{ old('station_number', $workOrder->station_number) }}">
                                    @error('station_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="consultant_name" class="form-label fw-bold">اسم الاستشاري</label>
                                    <input id="consultant_name" type="text" class="form-control @error('consultant_name') is-invalid @enderror" name="consultant_name" value="{{ old('consultant_name', $workOrder->consultant_name) }}">
                                    @error('consultant_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="office" class="form-label fw-bold">المكتب</label>
                                    <select id="office" class="form-select @error('office') is-invalid @enderror" name="office">
                                        <option value="">اختر المكتب</option>
                                        @if($project == 'riyadh')
                                            <option value="خريص" {{ old('office', $workOrder->office) == 'خريص' ? 'selected' : '' }}>خريص</option>
                                            <option value="الشرق" {{ old('office', $workOrder->office) == 'الشرق' ? 'selected' : '' }}>الشرق</option>
                                            <option value="الشمال" {{ old('office', $workOrder->office) == 'الشمال' ? 'selected' : '' }}>الشمال</option>
                                            <option value="الجنوب" {{ old('office', $workOrder->office) == 'الجنوب' ? 'selected' : '' }}>الجنوب</option>
                                            <option value="الدرعية" {{ old('office', $workOrder->office) == 'الدرعية' ? 'selected' : '' }}>الدرعية</option>
                                        @elseif($project == 'madinah')
                                            <option value="المدينة المنورة" {{ old('office', $workOrder->office) == 'المدينة المنورة' ? 'selected' : '' }}>المدينة المنورة</option>
                                            <option value="ينبع" {{ old('office', $workOrder->office) == 'ينبع' ? 'selected' : '' }}>ينبع</option>
                                            <option value="خيبر" {{ old('office', $workOrder->office) == 'خيبر' ? 'selected' : '' }}>خيبر</option>
                                            <option value="مهد الذهب" {{ old('office', $workOrder->office) == 'مهد الذهب' ? 'selected' : '' }}>مهد الذهب</option>
                                            <option value="بدر" {{ old('office', $workOrder->office) == 'بدر' ? 'selected' : '' }}>بدر</option>
                                            <option value="الحناكية" {{ old('office', $workOrder->office) == 'الحناكية' ? 'selected' : '' }}>الحناكية</option>
                                            <option value="العلا" {{ old('office', $workOrder->office) == 'العلا' ? 'selected' : '' }}>العلا</option>
                                        @endif
                                    </select>
                                    @error('office')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="order_value_with_consultant" class="form-label fw-bold">قيمة أمر العمل المبدئية شامل الاستشاري</label>
                                            <div class="input-group">
                                                <span class="input-group-text">﷼</span>
                                                <input id="order_value_with_consultant" type="number" step="0.01" class="form-control @error('order_value_with_consultant') is-invalid @enderror" name="order_value_with_consultant" value="{{ old('order_value_with_consultant', $workOrder->order_value_with_consultant) }}">
                                            </div>
                                            @error('order_value_with_consultant')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="order_value_without_consultant" class="form-label fw-bold">قيمة أمر العمل المبدئية بدون استشاري</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₪</span>
                                                <input id="order_value_without_consultant" type="number" step="0.01" class="form-control @error('order_value_without_consultant') is-invalid @enderror" name="order_value_without_consultant" value="{{ old('order_value_without_consultant', $workOrder->order_value_without_consultant) }}">
                                            </div>
                                            @error('order_value_without_consultant')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="execution_status" class="form-label fw-bold">حالة تنفيذ أمر العمل</label>
                                    <select id="execution_status" class="form-select @error('execution_status') is-invalid @enderror" name="execution_status">
                                        <option value="1" {{ old('execution_status', $workOrder->execution_status) == '1' ? 'selected' : '' }}>جاري العمل ...</option>
                                        <option value="2" {{ old('execution_status', $workOrder->execution_status) == '2' ? 'selected' : '' }}> تم تسليم 155 ولم تصدر شهادة انجاز </option>
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

                                <div class="form-section mb-4">
                                    <h4 class="section-title mb-3">المرفقات</h4>
                                    <div class="row">
                                        
                                        
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-paperclip text-secondary me-2"></i>
                                                المرفقات
                                            </label>
                                            <input type="file" class="form-control @error('files.attachments.*') is-invalid @enderror" 
                                                   name="files[attachments][]" 
                                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                                                   multiple>
                                            <div class="form-text">
                                                PDF, Word, Excel, أو صورة - الحد الأقصى 20 ميجابايت لكل ملف
                                            </div>
                                            
                                            @php
                                                $currentFiles = $workOrder->files()
                                                    ->where('file_category', 'basic_attachments')
                                                    ->get();
                                            @endphp
                                            
                                            @if($currentFiles->count() > 0)
                                                <div class="mt-3">
                                                    <h6 class="fw-bold mb-2">المرفقات الحالية:</h6>
                                                    <div class="row g-2">
                                                        @foreach($currentFiles as $file)
                                                            <div class="col-md-6">
                                                                <div class="p-2 bg-light rounded">
                                                                    <div class="d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            @php
                                                                                $extension = pathinfo($file->original_filename, PATHINFO_EXTENSION);
                                                                                $iconClass = match(strtolower($extension)) {
                                                                                    'pdf' => 'fa-file-pdf',
                                                                                    'doc', 'docx' => 'fa-file-word',
                                                                                    'xls', 'xlsx' => 'fa-file-excel',
                                                                                    'jpg', 'jpeg', 'png' => 'fa-file-image',
                                                                                    default => 'fa-file'
                                                                                };
                                                                            @endphp
                                                                            <i class="fas {{ $iconClass }} text-primary me-2"></i>
                                                                            <div style="max-width: 200px;">
                                                                                <small class="d-block text-truncate">{{ $file->original_filename }}</small>
                                                                                <small class="text-muted">{{ number_format($file->file_size / 1024, 1) }} KB</small>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-1">
                                                                            <a href="{{ Storage::url($file->file_path) }}" 
                                                                               target="_blank" 
                                                                               class="btn btn-sm btn-outline-primary"
                                                                               title="عرض الملف">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>
                                                                            <button type="button" 
                                                                                    class="btn btn-sm btn-outline-danger delete-file"
                                                                                    data-file-id="{{ $file->id }}"
                                                                                    title="حذف الملف">
                                                                                <i class="fas fa-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @error('files.attachments.*')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="alert alert-info mt-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        يمكنك رفع ملفات (PDF, JPG, PNG, DOC, DOCX, XLS, XLSX) - الحد الأقصى 20 ميجابايت لكل ملف
                                    </div>
                                </div>

                                <div class="form-group d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-secondary px-4">
                                        <i class="fas fa-times me-2"></i> إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-2"></i> حفظ التغييرات
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* تخصيص النموذج */
    .custom-form label {
        color: #333;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }
    
    .custom-form .form-control,
    .custom-form .form-select {
        border: 1px solid #ddd;
        padding: 0.6rem 0.75rem;
        transition: all 0.3s;
        border-radius: 4px;
    }
    
    .custom-form .form-control:focus,
    .custom-form .form-select:focus {
        border-color: #3490dc;
        box-shadow: 0 0 0 0.2rem rgba(52, 144, 220, 0.15);
    }
    
    .custom-form .input-group-text {
        background-color: #f8f9fa;
        border-color: #ddd;
    }
    
    /* تخصيص الأزرار */
    .custom-form .btn {
        border-radius: 4px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .custom-form .btn-primary {
        background-color: #3490dc;
        border-color: #3490dc;
    }
    
    .custom-form .btn-primary:hover {
        background-color: #2779bd;
        border-color: #2779bd;
    }
    
    .custom-form .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    .custom-form .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #5a6268;
    }
    
    /* تنسيق البطاقة */
    .card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    
    .card-header {
        border-bottom: 0;
        font-weight: 600;
        padding: 1.5rem;
    }
    
    .card-body {
        padding: 2rem;
    }
    
    /* تصميم للاتجاه من اليمين إلى اليسار */
    body, .form-group {
        text-align: right;
    }
    
    .input-group .input-group-text {
        border-radius: 0 0.25rem 0.25rem 0;
    }
    
    .input-group .form-control {
        border-radius: 0.25rem 0 0 0.25rem;
    }
    
    /* تنسيق المرفقات */
    .form-check {
        margin-bottom: 0.5rem;
    }
    
    .form-check-input {
        margin-left: 0.5rem;
    }
    
    .alert {
        border-radius: 4px;
        padding: 1rem;
    }
    
    .alert-info {
        background-color: #e3f2fd;
        border-color: #90caf9;
        color: #0d47a1;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // تحديث العداد التنازلي عند تحميل الصفحة
    updateCountdown();

    // إضافة معالج حذف الملفات
    document.querySelectorAll('.delete-file').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('هل أنت متأكد من حذف هذا الملف؟')) {
                const fileId = this.dataset.fileId;
                const fileCard = this.closest('.col-md-6');
                
                fetch(`/admin/work-orders/files/${fileId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fileCard.remove();
                        // إذا لم يتبق أي ملفات، إخفاء قسم "المرفقات الحالية"
                        const filesContainer = document.querySelector('.row.g-2');
                        if (filesContainer && filesContainer.children.length === 0) {
                            filesContainer.closest('.mt-3').remove();
                        }
                    } else {
                        alert('حدث خطأ أثناء حذف الملف');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء حذف الملف');
                });
            }
        });
    });
    
    const workTypeSelect = document.getElementById('work_type');
    const workTypeNumber = document.getElementById('work_type_number');
    const workDescriptionInput = document.getElementById('work_description');
    
    // Function to get work type description
    function getWorkTypeDescription(workType) {
        const descriptions = {
            '409': 'ازالة-نقل شبكة على المشترك',
            '408': 'ازاله عداد على المشترك',
            '460': 'استبدال شبكات',
            '901': 'اضافة عداد طاقة شمسية',
            '440': 'الرفع المساحي',
            '410': 'انشاء محطة/محول لمشترك/مشتركين',
            '801': 'تركيب عداد ايصال سريع',
            '804': 'تركيب محطة ش ارضية VM ايصال سريع',
            '805': 'تركيب محطة ش هوائية VM ايصال سريع',
            '480': '(تسليم مفتاح) تمويل خارجي',
            '441': 'تعزيز شبكة أرضية ومحطات',
            '442': 'تعزيز شبكة هوائية ومحطات',
            '802': 'شبكة ارضية VL ايصال سريع',
            '803': 'شبكة هوائية VL ايصال سريع',
            '402': 'توصيل عداد بحفرية شبكة ارضيه',
            '401': '(عداد بدون حفرية) أرضي/هوائي',
            '404': 'عداد بمحطة شبكة ارضية VM',
            '405': 'توصيل عداد بمحطة شبكة هوائية VM',
            '430': 'مخططات منح وزارة البلدية',
            '450': 'مشاريع ربط محطات التحويل',
            '403': 'توصيل عداد شبكة هوائية VL'
        };
        return descriptions[workType] || '';
    }
    
    // Function to update work description
    function updateWorkDescription(workType) {
        if (workType) {
            const description = getWorkTypeDescription(workType);
            if (description) {
                // دائماً قم بتعيين الوصف الجديد مباشرة
                workDescriptionInput.value = description;
            }
        }
    }
    
    // When the selector changes
    workTypeSelect.addEventListener('change', function() {
        const workType = this.value;
        if (workType) {
            // Update the number field
            workTypeNumber.value = workType;
            // Update the description
            updateWorkDescription(workType);
        }
    });
    
    // When the number field changes
    workTypeNumber.addEventListener('input', function() {
        const workType = this.value;
        if (workType) {
            // Check if a matching option exists
            let matchFound = false;
            for (let i = 0; i < workTypeSelect.options.length; i++) {
                if (workTypeSelect.options[i].value === workType) {
                    workTypeSelect.value = workType;
                    matchFound = true;
                    break;
                }
            }
            
            // Update the description regardless of match
            updateWorkDescription(workType);
        }
    });
    
    // Update description with initial value if it exists
    if (workTypeSelect.value) {
        updateWorkDescription(workTypeSelect.value);
    }
});

// تحديث العداد التنازلي
function updateCountdown() {
    const approvalDateInput = document.getElementById('approval_date');
    const countdownElement = document.getElementById('countdown');
    const manualDaysInput = document.getElementById('manual_days');
    
    // إذا كان هناك قيمة في حقل الأيام اليدوية، نستخدمها
    if (manualDaysInput.value) {
        const days = parseInt(manualDaysInput.value);
        if (days > 0) {
            countdownElement.textContent = days + ' يوم متبقي';
            countdownElement.className = 'text-success';
        } else {
            countdownElement.textContent = 'منتهي';
            countdownElement.className = 'text-danger';
        }
        return;
    }
    
    if (!approvalDateInput.value) {
        countdownElement.textContent = '-';
        countdownElement.className = 'text-muted';
        return;
    }
    
    const approvalDate = new Date(approvalDateInput.value);
    const now = new Date();
    
    // تعيين وقت منتصف الليل للتواريخ للمقارنة الدقيقة
    approvalDate.setHours(0, 0, 0, 0);
    now.setHours(0, 0, 0, 0);
    
    // حساب الفرق بالأيام
    const diffTime = approvalDate - now;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    // تحديث العرض
    if (diffDays > 0) {
        countdownElement.textContent = diffDays + ' يوم متبقي';
        countdownElement.className = 'text-success';
        // تحديث حقل الأيام اليدوية
        manualDaysInput.value = diffDays;
    } else if (diffDays < 0) {
        countdownElement.textContent = Math.abs(diffDays) + ' يوم متأخر';
        countdownElement.className = 'text-danger';
        // تحديث حقل الأيام اليدوية
        manualDaysInput.value = 0;
        // إظهار تنبيه
        Swal.fire({
            title: 'تنبيه!',
            text: 'تاريخ الاعتماد متأخر بـ ' + Math.abs(diffDays) + ' يوم',
            icon: 'warning',
            confirmButtonText: 'حسناً',
            customClass: {
                confirmButton: 'btn btn-primary'
            }
        });
    } else {
        countdownElement.textContent = 'اليوم';
        countdownElement.className = 'text-primary';
        // تحديث حقل الأيام اليدوية
        manualDaysInput.value = 0;
    }
}

// تحديث الأيام المتبقية يدوياً
function updateManualDays(days) {
    const countdownElement = document.getElementById('countdown');
    days = parseInt(days);
    
    if (days && days > 0) {
        countdownElement.textContent = days + ' يوم متبقي';
        countdownElement.className = 'text-success';
    } else if (days === 0) {
        countdownElement.textContent = 'منتهي';
        countdownElement.className = 'text-danger';
    } else {
        countdownElement.textContent = '-';
        countdownElement.className = 'text-muted';
        // إعادة تعيين القيمة إلى 0 إذا كانت سالبة
        document.getElementById('manual_days').value = 0;
    }
}

// تحديث العداد عند تحميل الصفحة وكل دقيقة
document.addEventListener('DOMContentLoaded', updateCountdown);
setInterval(updateCountdown, 60000);
</script>

@endsection 