@extends('layouts.app')

@section('title', 'المواد')
@section('header', 'جدول المواد')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- القسم الأيمن - نموذج إضافة المواد -->
        <div class="col-md-6">
            <div class="card shadow-lg mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-plus-circle ml-2"></i>
                        إضافة مادة جديدة
                    </h3>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    <form action="{{ route('admin.work-orders.materials.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        
                        <!-- معلومات أمر العمل -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">معلومات أمر العمل</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="work_order_id" class="form-label fw-bold">أمر العمل</label>
                                    <select name="work_order_id" id="work_order_id" class="form-select form-select-lg">
                                        <option value=""> أمر العمل</option>
                                        @foreach($workOrders as $workOrder)
                                            <option value="{{ $workOrder->id }}">{{ $workOrder->order_number }} - {{ $workOrder->subscriber_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">يرجى اختيار أمر العمل</div>
                                </div>
                            </div>
                        </div>

                        <!-- معلومات المادة الأساسية -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">معلومات المادة الأساسية</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="code" class="form-label fw-bold">كود المادة</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                            <input type="text" class="form-control" id="code" name="code" required>
                                        </div>
                                        <div class="invalid-feedback">يرجى إدخال كود المادة</div>
                                        <!-- حقل الوصف التلقائي -->
                                        <input type="text" class="form-control mt-2" id="auto_description" placeholder="سيظهر الوصف هنا تلقائيًا" readonly>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="line" class="form-label fw-bold">السطر</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                            <input type="text" class="form-control" id="line" name="line">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="description" class="form-label fw-bold"> المادة</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-align-right"></i></span>
                                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                    </div>
                                    <div id="desc-alert" class="text-danger mt-1" style="display:none; font-size:0.95em;"></div>
                                    <div class="invalid-feedback">يرجى إدخال وصف المادة</div>
                                </div>
                            </div>
                        </div>

                        <!-- معلومات الكميات -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">معلومات الكميات</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="planned_quantity" class="form-label fw-bold">الكمية المخططة</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calculator"></i></span>
                                            <input type="number" step="0.01" class="form-control" id="planned_quantity" name="planned_quantity">
                                        </div>
                                        <label for="line" class="form-label fw-bold">الكمية المصروفة</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                            <input type="text" class="form-control" id="line" name="line">
                                        </div>
                                    </div>
                                        <div class="invalid-feedback">يرجى إدخال الكمية المخططة</div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="unit" class="form-label fw-bold">الوحدة</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-ruler"></i></span>
                                            <select class="form-select" id="unit" name="unit">
                                                <option value="">اختر الوحدة</option>
                                                <option value="L.M">L.M</option>
                                                <option value="Ech">Ech</option>
                                                <option value="Kit">Kit</option>
                                            </select>
                                        </div>
                                        <div class="invalid-feedback">يرجى اختيار الوحدة</div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="actual_quantity" class="form-label fw-bold">الكمية المنفذة</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                            <input type="number" step="0.01" class="form-control" id="actual_quantity" name="actual_quantity">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- المرفقات والملفات -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">المرفقات والملفات</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="check_in_file" class="form-label fw-bold">CHECK LIST</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                            <input type="file" class="form-control" id="check_in_file" name="check_in_file">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="date_gatepass" class="form-label fw-bold">DATE GATEPASS</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            <input type="date" class="form-control" id="date_gatepass" name="date_gatepass">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="gate_pass_file" class="form-label fw-bold">GATE PASS</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-export"></i></span>
                                            <input type="file" class="form-control" id="gate_pass_file" name="gate_pass_file">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="store_in_file" class="form-label fw-bold">STORE IN</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-import"></i></span>
                                            <input type="file" class="form-control" id="store_in_file" name="store_in_file">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="store_out_file" class="form-label fw-bold">STORE OUT</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-export"></i></span>
                                            <input type="file" class="form-control" id="store_out_file" name="store_out_file">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="store_out_file" class="form-label fw-bold">DDO</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-export"></i></span>
                                            <input type="file" class="form-control" id="store_out_file" name="store_out_file">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-save ml-2"></i>
                                حفظ المادة
                            </button>
                            <button type="submit" class="btn btn-success btn-lg px-5" name="save_and_continue" value="1">
                                <i class="fas fa-plus ml-2"></i>
                                حفظ وإضافة مادة أخرى
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- القسم الأيسر - جدول المواد -->
        <div class="col-12 mt-5">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-list ml-2"></i>
                        جدول المواد
                    </h3>
                    <a href="{{ route('admin.work-orders.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-right ml-1"></i>
                        العودة للقائمة
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle ml-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle ml-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>كود المادة</th>
                                    <th>الوصف</th>
                                    <th>أمر العمل</th>
                                    <th>السطر</th>
                                    <th>الكمية المخططة</th>
                                    <th>الكمية المنفذة</th>
                                    <th>الكمية المصروفة</th>
                                    <th>الفرق</th>
                                    <th>الوحدة</th>
                                    <th>تاريخ الإضافة</th>
                                    <th>CHECK LIST</th>
                                    <th>GATE PASS</th>
                                    <th>STORE IN</th>
                                    <th>STORE OUT</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($materials->count() > 0)
                                    @foreach($materials as $material)
                                        <tr>
                                            <td>{{ $material->code }}</td>
                                            <td>{{ $material->description }}</td>
                                            <td>
                                                @if($material->workOrder)
                                                    {{ $material->workOrder->order_number }} - {{ $material->workOrder->subscriber_name }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $material->line }}</td>
                                            <td>{{ $material->planned_quantity }}</td>
                                            <td>{{ $material->actual_quantity }}</td>
                                            <td>{{ $material->difference }}</td>
                                            <td>{{ $material->unit }}</td>
                                            <td>{{ $material->created_at ? $material->created_at->format('Y-m-d H:i') : '-' }}</td>
                                            <td>
                                                @if($material->check_in_file)
                                                    <a href="{{ asset('storage/' . $material->check_in_file) }}" target="_blank" class="btn btn-sm btn-success">تحميل</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($material->gate_pass_file)
                                                    <a href="{{ asset('storage/' . $material->gate_pass_file) }}" target="_blank" class="btn btn-sm btn-success">تحميل</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($material->store_in_file)
                                                    <a href="{{ asset('storage/' . $material->store_in_file) }}" target="_blank" class="btn btn-sm btn-success">تحميل</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($material->store_out_file)
                                                    <a href="{{ asset('storage/' . $material->store_out_file) }}" target="_blank" class="btn btn-sm btn-success">تحميل</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-primary view-material" data-material="{{ json_encode($material) }}" title="عرض التفاصيل">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a href="{{ route('admin.work-orders.materials.edit', $material) }}" class="btn btn-sm btn-warning" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.work-orders.materials.destroy', $material) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذه المادة؟')" title="حذف">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="14" class="text-center py-4">
                                            <div class="alert alert-info mb-0">
                                                <i class="fas fa-info-circle ml-2"></i>
                                                لا توجد مواد مسجلة
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $materials->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal لعرض تفاصيل المادة -->
<div class="modal fade" id="materialDetailsModal" tabindex="-1" aria-labelledby="materialDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="materialDetailsModalLabel">تفاصيل المادة</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="m-0">معلومات أساسية</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>كود المادة:</strong>
                                        <span id="modal-code" class="d-block mt-1 p-2 bg-light rounded"></span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>وصف المادة:</strong>
                                        <div id="modal-description" class="d-block mt-1 p-2 bg-light rounded"></div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>أمر العمل:</strong>
                                        <span id="modal-work-order" class="d-block mt-1 p-2 bg-light rounded"></span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>السطر:</strong>
                                        <span id="modal-line" class="d-block mt-1 p-2 bg-light rounded"></span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>الكمية المصروفة:</strong>
                                        <span id="modal-line" class="d-block mt-1 p-2 bg-light rounded"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="m-0">معلومات الكمية</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>الكمية المخططة:</strong>
                                        <span id="modal-planned-quantity" class="d-block mt-1 p-2 bg-light rounded"></span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>الوحدة:</strong>
                                        <span id="modal-unit" class="d-block mt-1 p-2 bg-light rounded"></span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>الكمية المنفذة الفعلية:</strong>
                                        <span id="modal-actual-quantity" class="d-block mt-1 p-2 bg-light rounded"></span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>الفرق:</strong>
                                        <span id="modal-difference" class="d-block mt-1 p-2 bg-light rounded"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="m-0">الملفات والمرفقات</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>CHECK LIST:</strong>
                                        <div id="modal-check-in-file" class="d-block mt-1"></div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>GATE PASS:</strong>
                                        <div id="modal-gate-pass-file" class="d-block mt-1"></div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>STORE IN:</strong>
                                        <div id="modal-store-in-file" class="d-block mt-1"></div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>STORE OUT:</strong>
                                        <div id="modal-store-out-file" class="d-block mt-1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="m-0">معلومات المخزون</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>DATE GATEPASS:</strong>
                                        <span id="modal-date-gatepass" class="d-block mt-1 p-2 bg-light rounded"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <a href="#" id="modal-edit-link" class="btn btn-warning">تعديل المادة</a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// تعريف مسار التخزين للاستخدام في JavaScript
const storageUrl = "{{ asset('storage') }}";
// تعريف مسار تعديل المواد
const editMaterialUrl = "{{ route('admin.work-orders.materials.edit', '') }}";

// قاعدة بيانات مؤقتة لأكواد المواد والأوصاف - يمكن استبدالها بطلب AJAX للحصول على البيانات من السيرفر
const materialsData = {
    "M001": "أنابيب مياه بلاستيكية قطر 50 مم",
    "M002": "أنابيب صرف صحي 100 مم",
    "M003": "محابس مياه معدنية 3/4 بوصة",
    "M004": "وصلات بلاستيكية T شكل",
    "M005": "صمامات تحكم بالضغط",
    // يمكن إضافة المزيد من الأكواد والأوصاف
};

// تحديث وصف المادة عند تغيير كود المادة
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('code');
    const descriptionInput = document.getElementById('description');
    const autoDescriptionInput = document.getElementById('auto_description');
    const descAlert = document.getElementById('desc-alert');
    
    let typingTimer;
    const doneTypingInterval = 500; // انتظر 500 مللي ثانية بعد توقف الكتابة
    
    codeInput.addEventListener('input', function() {
        clearTimeout(typingTimer);
        const code = this.value.trim();
        
        if (code) {
            typingTimer = setTimeout(function() {
                // جلب الوصف من الخادم
                fetch(`/admin/work-orders/materials/get-description/${code}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.description) {
                            autoDescriptionInput.value = data.description;
                            descriptionInput.value = data.description; // نسخ الوصف إلى حقل الوصف الرئيسي
                            descAlert.style.display = 'none';
                        } else {
                            autoDescriptionInput.value = '';
                            descAlert.textContent = 'لم يتم العثور على وصف لهذا الكود';
                            descAlert.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        descAlert.textContent = 'حدث خطأ أثناء جلب الوصف';
                        descAlert.style.display = 'block';
                    });
            }, doneTypingInterval);
        } else {
            autoDescriptionInput.value = '';
            descriptionInput.value = '';
            descAlert.style.display = 'none';
        }
    });
});

// معالجة عرض تفاصيل المادة في النافذة المنبثقة
document.querySelectorAll('.view-material').forEach(button => {
    button.addEventListener('click', function() {
        const materialData = JSON.parse(this.getAttribute('data-material'));
        
        // تعبئة بيانات المادة في النافذة المنبثقة
        document.getElementById('modal-code').textContent = materialData.code;
        document.getElementById('modal-description').textContent = materialData.description;
        document.getElementById('modal-work-order').textContent = materialData.work_order ? 
            materialData.work_order.order_number + ' - ' + materialData.work_order.subscriber_name : 'غير محدد';
        document.getElementById('modal-line').textContent = materialData.line || '-';
        document.getElementById('modal-planned-quantity').textContent = materialData.planned_quantity;
        document.getElementById('modal-unit').textContent = materialData.unit;
        document.getElementById('modal-actual-quantity').textContent = materialData.actual_quantity || '-';
        document.getElementById('modal-difference').textContent = materialData.difference || '-';
        document.getElementById('modal-date-gatepass').textContent = materialData.date_gatepass || '-';
        
        // معالجة ملفات CHECK IN/OUT
        const checkInFileElement = document.getElementById('modal-check-in-file');
        checkInFileElement.innerHTML = '';
        if (materialData.check_in_file) {
            checkInFileElement.innerHTML = `
                <a href="${storageUrl}/${materialData.check_in_file}" target="_blank" class="btn btn-sm btn-success mt-2">
                    <i class="fas fa-file-download"></i> تحميل الملف
                </a>`;
        } else {
            checkInFileElement.innerHTML = '<span class="badge bg-light text-dark mt-2">لا يوجد ملف</span>';
        }
        
        // معالجة ملف GATE PASS
        const gatePassFileElement = document.getElementById('modal-gate-pass-file');
        gatePassFileElement.innerHTML = '';
        if (materialData.gate_pass_file) {
            gatePassFileElement.innerHTML = `
                <a href="${storageUrl}/${materialData.gate_pass_file}" target="_blank" class="btn btn-sm btn-success mt-2">
                    <i class="fas fa-file-download"></i> تحميل الملف
                </a>`;
        } else {
            gatePassFileElement.innerHTML = '<span class="badge bg-light text-dark mt-2">لا يوجد ملف</span>';
        }
        
        // معالجة ملف STORE IN
        const storeInFileElement = document.getElementById('modal-store-in-file');
        storeInFileElement.innerHTML = '';
        if (materialData.store_in_file) {
            storeInFileElement.innerHTML = `
                <a href="${storageUrl}/${materialData.store_in_file}" target="_blank" class="btn btn-sm btn-success mt-2">
                    <i class="fas fa-file-download"></i> تحميل الملف
                </a>`;
        } else {
            storeInFileElement.innerHTML = '<span class="badge bg-light text-dark mt-2">لا يوجد ملف</span>';
        }
        
        // معالجة ملف STORE OUT
        const storeOutFileElement = document.getElementById('modal-store-out-file');
        storeOutFileElement.innerHTML = '';
        if (materialData.store_out_file) {
            storeOutFileElement.innerHTML = `
                <a href="${storageUrl}/${materialData.store_out_file}" target="_blank" class="btn btn-sm btn-success mt-2">
                    <i class="fas fa-file-download"></i> تحميل الملف
                </a>`;
        } else {
            storeOutFileElement.innerHTML = '<span class="badge bg-light text-dark mt-2">لا يوجد ملف</span>';
        }
        
        // تعيين رابط التعديل
        document.getElementById('modal-edit-link').href = "{{ route('admin.work-orders.materials.edit', '') }}/" + materialData.id;
        
        // عرض النافذة المنبثقة
        const modal = new bootstrap.Modal(document.getElementById('materialDetailsModal'));
        modal.show();
    });
});

// إضافة التحقق من صحة النموذج
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>
@endsection 