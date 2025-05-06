@extends('layouts.app')

@section('title', 'المواد')
@section('header', 'جدول المواد')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('admin.work-orders.index') }}" class="btn btn-outline-dark">
            <i class="fas fa-arrow-right ml-1"></i>
            العودة إلى القائمة
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

    <div class="card shadow mb-6">
        <div class="card-header bg-primary text-white">
            <h2 class="h4 m-0">إضافة مادة جديدة</h2>
        </div>
        <div class="card-body">
            <form action="{{ url('admin/work-orders/materials') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="work_order_id" class="form-label">أمر العمل</label>
                        <select name="work_order_id" id="work_order_id" class="form-select" required>
                            <option value="">اختر أمر العمل</option>
                            @foreach($workOrders as $workOrder)
                                <option value="{{ $workOrder->id }}">{{ $workOrder->order_number }} - {{ $workOrder->subscriber_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">كود المادة</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">وصف المادة</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="planned_quantity" class="form-label">الكمية المخططة</label>
                        <input type="number" step="0.01" class="form-control" id="planned_quantity" name="planned_quantity" required>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="unit" class="form-label">الوحدة</label>
                        <select class="form-select" id="unit" name="unit" required>
                            <option value="">اختر الوحدة</option>
                            <option value="L.M">L.M</option>
                            <option value="Ech">Ech</option>
                            <option value="Kit">Kit</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="line" class="form-label">السطر</label>
                        <input type="text" class="form-control" id="line" name="line">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="check_in_file" class="form-label">CHECK IN</label>
                        <input type="file" class="form-control" id="check_in_file" name="check_in_file">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="check_out_file" class="form-label">CHECK OUT</label>
                        <input type="file" class="form-control" id="check_out_file" name="check_out_file">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="date_gatepass" class="form-label">DATE GATEPASS</label>
                        <input type="date" class="form-control" id="date_gatepass" name="date_gatepass">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="stock_in" class="form-label">STOCK IN</label>
                        <input type="number" step="0.01" class="form-control" id="stock_in" name="stock_in">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="stock_out" class="form-label">STOCK OUT</label>
                        <input type="number" step="0.01" class="form-control" id="stock_out" name="stock_out">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="actual_quantity" class="form-label">الكمية المنفذة الفعلية</label>
                        <input type="number" step="0.01" class="form-control" id="actual_quantity" name="actual_quantity">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="stock_in_file" class="form-label">ملف STOCK IN</label>
                        <input type="file" class="form-control" id="stock_in_file" name="stock_in_file">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="stock_out_file" class="form-label">ملف STOCK OUT</label>
                        <input type="file" class="form-control" id="stock_out_file" name="stock_out_file">
                    </div>
                </div>
                
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">حفظ المادة</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="h4 m-0">جدول المواد</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>كد المادة</th>
                            <th>وصف المادة</th>
                            <th>الكمية المخططة</th>
                            <th>الوحدة</th>
                            <th>السطر</th>
                            <th>CHECK IN</th>
                            <th>CHECK OUT</th>
                            <th>DATE GATEPASS</th>
                            <th>STOCK IN</th>
                            <th>STOCK OUT</th>
                            <th>الكمية المنفذة الفعلية</th>
                            <th>الفرق</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($materials->count() > 0)
                            @foreach($materials as $material)
                                <tr>
                                    <td>{{ $material->code }}</td>
                                    <td>{{ Str::limit($material->description, 50) }}</td>
                                    <td>{{ $material->planned_quantity }}</td>
                                    <td>{{ $material->unit }}</td>
                                    <td>{{ $material->line }}</td>
                                    <td>
                                        @if($material->check_in_file)
                                            <a href="{{ asset('storage/' . $material->check_in_file) }}" target="_blank" class="btn btn-sm btn-success">
                                                <i class="fas fa-file-download"></i>
                                            </a>
                                        @else
                                            <span class="badge bg-light text-dark">لا يوجد</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($material->check_out_file)
                                            <a href="{{ asset('storage/' . $material->check_out_file) }}" target="_blank" class="btn btn-sm btn-success">
                                                <i class="fas fa-file-download"></i>
                                            </a>
                                        @else
                                            <span class="badge bg-light text-dark">لا يوجد</span>
                                        @endif
                                    </td>
                                    <td>{{ $material->date_gatepass ? $material->date_gatepass->format('Y-m-d') : '-' }}</td>
                                    <td>
                                        {{ $material->stock_in }}
                                        @if($material->stock_in_file)
                                            <a href="{{ asset('storage/' . $material->stock_in_file) }}" target="_blank" class="ms-1 btn btn-sm btn-info">
                                                <i class="fas fa-file-download"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $material->stock_out }}
                                        @if($material->stock_out_file)
                                            <a href="{{ asset('storage/' . $material->stock_out_file) }}" target="_blank" class="ms-1 btn btn-sm btn-info">
                                                <i class="fas fa-file-download"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $material->actual_quantity }}</td>
                                    <td>{{ $material->difference }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.work-orders.materials.edit', $material) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.work-orders.materials.destroy', $material) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذه المادة؟')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-primary view-material" data-material="{{ json_encode($material) }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="13" class="text-center">لا توجد مواد مسجلة</td>
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
                                        <strong>CHECK IN:</strong>
                                        <div id="modal-check-in-file" class="d-block mt-1"></div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>CHECK OUT:</strong>
                                        <div id="modal-check-out-file" class="d-block mt-1"></div>
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
                                        <strong>STOCK IN:</strong>
                                        <span id="modal-stock-in" class="d-block mt-1 p-2 bg-light rounded"></span>
                                        <div id="modal-stock-in-file" class="d-block mt-1"></div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>STOCK OUT:</strong>
                                        <span id="modal-stock-out" class="d-block mt-1 p-2 bg-light rounded"></span>
                                        <div id="modal-stock-out-file" class="d-block mt-1"></div>
                                    </div>
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
const editMaterialUrl = "{{ url('admin/work-orders/materials') }}";

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
document.getElementById('code').addEventListener('input', function() {
    const code = this.value.trim();
    const description = document.getElementById('description');
    
    if (materialsData[code]) {
        description.value = materialsData[code];
    }
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
        document.getElementById('modal-stock-in').textContent = materialData.stock_in || '-';
        document.getElementById('modal-stock-out').textContent = materialData.stock_out || '-';
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
        
        const checkOutFileElement = document.getElementById('modal-check-out-file');
        checkOutFileElement.innerHTML = '';
        if (materialData.check_out_file) {
            checkOutFileElement.innerHTML = `
                <a href="${storageUrl}/${materialData.check_out_file}" target="_blank" class="btn btn-sm btn-success mt-2">
                    <i class="fas fa-file-download"></i> تحميل الملف
                </a>`;
        } else {
            checkOutFileElement.innerHTML = '<span class="badge bg-light text-dark mt-2">لا يوجد ملف</span>';
        }
        
        // معالجة ملفات STOCK IN/OUT
        const stockInFileElement = document.getElementById('modal-stock-in-file');
        stockInFileElement.innerHTML = '';
        if (materialData.stock_in_file) {
            stockInFileElement.innerHTML = `
                <a href="${storageUrl}/${materialData.stock_in_file}" target="_blank" class="btn btn-sm btn-info mt-2">
                    <i class="fas fa-file-download"></i> تحميل الملف
                </a>`;
        } else {
            stockInFileElement.innerHTML = '<span class="badge bg-light text-dark mt-2">لا يوجد ملف</span>';
        }
        
        const stockOutFileElement = document.getElementById('modal-stock-out-file');
        stockOutFileElement.innerHTML = '';
        if (materialData.stock_out_file) {
            stockOutFileElement.innerHTML = `
                <a href="${storageUrl}/${materialData.stock_out_file}" target="_blank" class="btn btn-sm btn-info mt-2">
                    <i class="fas fa-file-download"></i> تحميل الملف
                </a>`;
        } else {
            stockOutFileElement.innerHTML = '<span class="badge bg-light text-dark mt-2">لا يوجد ملف</span>';
        }
        
        // تعيين رابط التعديل
        document.getElementById('modal-edit-link').href = `${editMaterialUrl}/${materialData.id}/edit`;
        
        // عرض النافذة المنبثقة
        const modal = new bootstrap.Modal(document.getElementById('materialDetailsModal'));
        modal.show();
    });
});
</script>
@endsection 