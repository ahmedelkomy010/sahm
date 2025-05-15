@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 fs-4">إجراءات ما بعد التنفيذ</h3>
                    <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right"></i> عودة
                    </a>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- الصف الأول للمرفقات -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-file-alt fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">بيان كميات التنفيذ</h4>
                                    <p class="card-text text-muted mb-4">تحميل بيان كميات التنفيذ الخاص بأمر العمل</p>
                                    <div class="d-grid">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fileModal" 
                                                onclick="setupFileUpload('بيان كميات التنفيذ', 'quantities_statement')">
                                            {{ $workOrder->quantities_statement_file ? 'تعديل الملف' : 'رفع الملف' }}
                                        </button>
                                        @if($workOrder->quantities_statement_file)
                                            <a href="{{ Storage::url($workOrder->quantities_statement_file) }}" class="btn btn-info mt-2" target="_blank">
                                                <i class="fas fa-eye"></i> عرض الملف
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-boxes fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">كميات المواد النهائية</h4>
                                    <p class="card-text text-muted mb-4">تحميل قائمة كميات المواد النهائية المستخدمة</p>
                                    <div class="d-grid">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fileModal" 
                                                onclick="setupFileUpload('كميات المواد النهائية', 'final_materials')">
                                            {{ $workOrder->final_materials_file ? 'تعديل الملف' : 'رفع الملف' }}
                                        </button>
                                        @if($workOrder->final_materials_file)
                                            <a href="{{ Storage::url($workOrder->final_materials_file) }}" class="btn btn-info mt-2" target="_blank">
                                                <i class="fas fa-eye"></i> عرض الملف
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-ruler fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">ورقة القياس النهائية</h4>
                                    <p class="card-text text-muted mb-4">تحميل ورقة القياس النهائية للمشروع</p>
                                    <div class="d-grid">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fileModal" 
                                                onclick="setupFileUpload('ورقة القياس النهائية', 'final_measurement')">
                                            {{ $workOrder->final_measurement_file ? 'تعديل الملف' : 'رفع الملف' }}
                                        </button>
                                        @if($workOrder->final_measurement_file)
                                            <a href="{{ Storage::url($workOrder->final_measurement_file) }}" class="btn btn-info mt-2" target="_blank">
                                                <i class="fas fa-eye"></i> عرض الملف
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- الصف الثاني للمرفقات -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-vial fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">اختبارات التربة</h4>
                                    <p class="card-text text-muted mb-4">تحميل نتائج اختبارات التربة للموقع</p>
                                    <div class="d-grid">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fileModal" 
                                                onclick="setupFileUpload('اختبارات التربة', 'soil_tests')">
                                            {{ $workOrder->soil_tests_file ? 'تعديل الملف' : 'رفع الملف' }}
                                        </button>
                                        @if($workOrder->soil_tests_file)
                                            <a href="{{ Storage::url($workOrder->soil_tests_file) }}" class="btn btn-info mt-2" target="_blank">
                                                <i class="fas fa-eye"></i> عرض الملف
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-drafting-compass fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">الرسم الهندسي للموقع</h4>
                                    <p class="card-text text-muted mb-4">تحميل الرسومات الهندسية النهائية للموقع</p>
                                    <div class="d-grid">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fileModal" 
                                                onclick="setupFileUpload('الرسم الهندسي للموقع', 'site_drawing')">
                                            {{ $workOrder->site_drawing_file ? 'تعديل الملف' : 'رفع الملف' }}
                                        </button>
                                        @if($workOrder->site_drawing_file)
                                            <a href="{{ Storage::url($workOrder->site_drawing_file) }}" class="btn btn-info mt-2" target="_blank">
                                                <i class="fas fa-eye"></i> عرض الملف
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-file-invoice fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">تعديل المقايسة</h4>
                                    <p class="card-text text-muted mb-4">تحميل المقايسة المعدلة بعد التنفيذ</p>
                                    <div class="d-grid">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fileModal" 
                                                onclick="setupFileUpload('تعديل المقايسة', 'modified_estimate')">
                                            {{ $workOrder->modified_estimate_file ? 'تعديل الملف' : 'رفع الملف' }}
                                        </button>
                                        @if($workOrder->modified_estimate_file)
                                            <a href="{{ Storage::url($workOrder->modified_estimate_file) }}" class="btn btn-info mt-2" target="_blank">
                                                <i class="fas fa-eye"></i> عرض الملف
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- الصف الثالث للمرفقات -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-certificate fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">شهادة الانجاز</h4>
                                    <p class="card-text text-muted mb-4">تحميل شهادة إنجاز المشروع</p>
                                    <div class="d-grid">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fileModal" 
                                                onclick="setupFileUpload('شهادة الانجاز', 'completion_certificate')">
                                            {{ $workOrder->completion_certificate_file ? 'تعديل الملف' : 'رفع الملف' }}
                                        </button>
                                        @if($workOrder->completion_certificate_file)
                                            <a href="{{ Storage::url($workOrder->completion_certificate_file) }}" class="btn btn-info mt-2" target="_blank">
                                                <i class="fas fa-eye"></i> عرض الملف
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-file-contract fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">نموذج 200</h4>
                                    <p class="card-text text-muted mb-4">تحميل نموذج 200 الخاص بالمشروع</p>
                                    <div class="d-grid">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fileModal" 
                                                onclick="setupFileUpload('نموذج 200', 'form_200')">
                                            {{ $workOrder->form_200_file ? 'تعديل الملف' : 'رفع الملف' }}
                                        </button>
                                        @if($workOrder->form_200_file)
                                            <a href="{{ Storage::url($workOrder->form_200_file) }}" class="btn btn-info mt-2" target="_blank">
                                                <i class="fas fa-eye"></i> عرض الملف
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-file-alt fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">نموذج 190</h4>
                                    <p class="card-text text-muted mb-4">تحميل نموذج 190 الخاص بالمشروع</p>
                                    <div class="d-grid">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fileModal" 
                                                onclick="setupFileUpload('نموذج 190', 'form_190')">
                                            {{ $workOrder->form_190_file ? 'تعديل الملف' : 'رفع الملف' }}
                                        </button>
                                        @if($workOrder->form_190_file)
                                            <a href="{{ Storage::url($workOrder->form_190_file) }}" class="btn btn-info mt-2" target="_blank">
                                                <i class="fas fa-eye"></i> عرض الملف
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- الصف الرابع للمرفقات -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-clipboard-check fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">اختبارات ما قبل التشغيل 211</h4>
                                    <p class="card-text text-muted mb-4">تحميل نتائج اختبارات ما قبل التشغيل</p>
                                    <div class="d-grid">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fileModal" 
                                                onclick="setupFileUpload('اختبارات ما قبل التشغيل 211', 'pre_operation_tests')">
                                            {{ $workOrder->pre_operation_tests_file ? 'تعديل الملف' : 'رفع الملف' }}
                                        </button>
                                        @if($workOrder->pre_operation_tests_file)
                                            <a href="{{ Storage::url($workOrder->pre_operation_tests_file) }}" class="btn btn-info mt-2" target="_blank">
                                                <i class="fas fa-eye"></i> عرض الملف
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-file-invoice-dollar fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">رقم المستخلص</h4>
                                    <p class="card-text text-muted mb-4">تحميل نسخة من المستخلص النهائي</p>
                                    <div class="d-grid">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fileModal" 
                                                onclick="setupFileUpload('رقم المستخلص', 'extract_number')">
                                            {{ $workOrder->extract_number_file ? 'تعديل الملف' : 'رفع الملف' }}
                                        </button>
                                        @if($workOrder->extract_number_file)
                                            <a href="{{ Storage::url($workOrder->extract_number_file) }}" class="btn btn-info mt-2" target="_blank">
                                                <i class="fas fa-eye"></i> عرض الملف
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- جدول المرفقات -->
            <div class="card mt-4 shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h3 class="mb-0 fs-4">جدول مرفقات إجراءات ما بعد التنفيذ</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">نوع المرفق</th>
                                    <th scope="col">الحالة</th>
                                    <th scope="col">تاريخ الرفع</th>
                                    <th scope="col">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
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
                                        'extract_number_file' => 'رقم المستخلص'
                                    ];
                                    
                                    $fileTypes2 = [
                                        'quantities_statement' => 'quantities_statement_file',
                                        'final_materials' => 'final_materials_file',
                                        'final_measurement' => 'final_measurement_file',
                                        'soil_tests' => 'soil_tests_file',
                                        'site_drawing' => 'site_drawing_file',
                                        'modified_estimate' => 'modified_estimate_file',
                                        'completion_certificate' => 'completion_certificate_file',
                                        'form_200' => 'form_200_file',
                                        'form_190' => 'form_190_file',
                                        'pre_operation_tests' => 'pre_operation_tests_file',
                                        'extract_number' => 'extract_number_file'
                                    ];
                                @endphp
                                
                                @foreach($fileTypes as $column => $title)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $title }}</td>
                                        <td>
                                            @if($workOrder->$column)
                                                <span class="badge bg-success">تم الرفع</span>
                                            @else
                                                <span class="badge bg-danger">غير مرفق</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($workOrder->$column)
                                                @php
                                                    $filePath = $workOrder->$column;
                                                    $log = DB::table('work_order_logs')
                                                        ->where('work_order_id', $workOrder->id)
                                                        ->where('section', 'post_execution')
                                                        ->where('data', 'like', '%' . $filePath . '%')
                                                        ->orderBy('created_at', 'desc')
                                                        ->first();
                                                @endphp
                                                @if($log)
                                                    {{ date('Y-m-d H:i', strtotime($log->created_at)) }}
                                                @else
                                                    -
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($workOrder->$column)
                                                <div class="btn-group" role="group">
                                                    <a href="{{ Storage::url($workOrder->$column) }}" class="btn btn-info btn-sm" target="_blank">
                                                        <i class="fas fa-eye"></i> عرض
                                                    </a>
                                                    @php
                                                        $fileType = array_search($column, $fileTypes2);
                                                    @endphp
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#fileModal" 
                                                            onclick="setupFileUpload('{{ $title }}', '{{ $fileType }}')">
                                                        <i class="fas fa-edit"></i> تعديل
                                                    </button>
                                                </div>
                                            @else
                                                @php
                                                    $fileType = array_search($column, $fileTypes2);
                                                @endphp
                                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#fileModal" 
                                                        onclick="setupFileUpload('{{ $title }}', '{{ $fileType }}')">
                                                    <i class="fas fa-upload"></i> رفع
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal للتحميل -->
<div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.work-orders.upload-post-execution-file', $workOrder->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="fileModalLabel">رفع ملف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fileType" class="form-label">نوع الملف</label>
                        <input type="text" class="form-control" id="fileType" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="fileInput" class="form-label">اختر الملف</label>
                        <input type="file" class="form-control" id="fileInput" name="file" required>
                        <div class="form-text">الملفات المدعومة: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG</div>
                    </div>
                    <input type="hidden" name="file_type" id="fileTypeInput">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
}

.icon-wrapper {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(74, 144, 226, 0.1);
    border-radius: 50%;
}

.card-title {
    color: var(--text-color);
    font-weight: 600;
    font-size: 1.25rem;
}

.btn-primary {
    padding: 0.6rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(74, 144, 226, 0.2);
}

/* تنسيق الجدول */
.table th {
    font-weight: 600;
}

.table-responsive {
    overflow-x: auto;
}

.badge {
    font-size: 0.85rem;
    padding: 0.4em 0.7em;
}
</style>

@section('scripts')
<script>
    function setupFileUpload(title, fileType) {
        document.getElementById('fileType').value = title;
        document.getElementById('fileTypeInput').value = fileType;
        document.getElementById('fileModalLabel').innerText = 'رفع ملف: ' + title;
    }
</script>
@endsection 