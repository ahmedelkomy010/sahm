@extends('layouts.app')

@section('title', 'تعديل مادة')
@section('header', 'تعديل المادة')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('admin.work-orders.materials') }}" class="btn btn-outline-dark">
            <i class="fas fa-arrow-right ml-1"></i>
            العودة إلى قائمة المواد
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

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="h4 m-0">تعديل المادة</h2>
        </div>
        <div class="card-body">
            <form action="{{ url('admin/work-orders/materials/' . $material->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="work_order_id" class="form-label">أمر العمل</label>
                        <select name="work_order_id" id="work_order_id" class="form-select" required>
                            <option value="">اختر أمر العمل</option>
                            @foreach($workOrders as $workOrder)
                                <option value="{{ $workOrder->id }}" {{ $material->work_order_id == $workOrder->id ? 'selected' : '' }}>
                                    {{ $workOrder->order_number }} - {{ $workOrder->subscriber_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">كود المادة</label>
                        <input type="text" class="form-control" id="code" name="code" value="{{ $material->code }}" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">وصف المادة</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required>{{ $material->description }}</textarea>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="planned_quantity" class="form-label">الكمية المخططة</label>
                        <input type="number" step="0.01" class="form-control" id="planned_quantity" name="planned_quantity" value="{{ $material->planned_quantity }}" required>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="unit" class="form-label">الوحدة</label>
                        <select class="form-select" id="unit" name="unit" required>
                            <option value="">اختر الوحدة</option>
                            <option value="L.M" {{ $material->unit == 'L.M' ? 'selected' : '' }}>L.M</option>
                            <option value="Ech" {{ $material->unit == 'Ech' ? 'selected' : '' }}>Ech</option>
                            <option value="Kit" {{ $material->unit == 'Kit' ? 'selected' : '' }}>Kit</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="line" class="form-label">السطر</label>
                        <input type="text" class="form-control" id="line" name="line" value="{{ $material->line }}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="check_in_file" class="form-label">CHECK IN</label>
                        @if($material->check_in_file)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $material->check_in_file) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-file"></i> عرض الملف الحالي
                                </a>
                            </div>
                        @endif
                        <input type="file" class="form-control" id="check_in_file" name="check_in_file">
                        <small class="form-text text-muted">اختياري: تحميل ملف جديد سيستبدل الملف الحالي</small>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="check_out_file" class="form-label">CHECK OUT</label>
                        @if($material->check_out_file)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $material->check_out_file) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-file"></i> عرض الملف الحالي
                                </a>
                            </div>
                        @endif
                        <input type="file" class="form-control" id="check_out_file" name="check_out_file">
                        <small class="form-text text-muted">اختياري: تحميل ملف جديد سيستبدل الملف الحالي</small>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="date_gatepass" class="form-label">DATE GATEPASS</label>
                        <input type="date" class="form-control" id="date_gatepass" name="date_gatepass" value="{{ $material->date_gatepass ? $material->date_gatepass->format('Y-m-d') : '' }}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="stock_in" class="form-label">STOCK IN</label>
                        <input type="number" step="0.01" class="form-control" id="stock_in" name="stock_in" value="{{ $material->stock_in }}">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="stock_out" class="form-label">STOCK OUT</label>
                        <input type="number" step="0.01" class="form-control" id="stock_out" name="stock_out" value="{{ $material->stock_out }}">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="actual_quantity" class="form-label">الكمية المنفذة الفعلية</label>
                        <input type="number" step="0.01" class="form-control" id="actual_quantity" name="actual_quantity" value="{{ $material->actual_quantity }}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="stock_in_file" class="form-label">ملف STOCK IN</label>
                        @if($material->stock_in_file)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $material->stock_in_file) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-file"></i> عرض الملف الحالي
                                </a>
                            </div>
                        @endif
                        <input type="file" class="form-control" id="stock_in_file" name="stock_in_file">
                        <small class="form-text text-muted">اختياري: تحميل ملف جديد سيستبدل الملف الحالي</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="stock_out_file" class="form-label">ملف STOCK OUT</label>
                        @if($material->stock_out_file)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $material->stock_out_file) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-file"></i> عرض الملف الحالي
                                </a>
                            </div>
                        @endif
                        <input type="file" class="form-control" id="stock_out_file" name="stock_out_file">
                        <small class="form-text text-muted">اختياري: تحميل ملف جديد سيستبدل الملف الحالي</small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">الفرق (يحسب تلقائياً)</label>
                        <input type="text" class="form-control bg-light" value="{{ $material->difference }}" readonly>
                    </div>
                </div>
                
                <div class="text-end">
                    <a href="{{ route('admin.work-orders.materials') }}" class="btn btn-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// قائمة أكواد المواد وأوصافها
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
    
    if (materialsData[code] && !description.dataset.userEdited) {
        description.value = materialsData[code];
    }
});

// تتبع تعديلات المستخدم على وصف المادة
document.getElementById('description').addEventListener('input', function() {
    this.dataset.userEdited = true;
});

// حساب الفرق بين الكمية المخططة والكمية الفعلية
function calculateDifference() {
    const plannedQuantity = parseFloat(document.getElementById('planned_quantity').value) || 0;
    const actualQuantity = parseFloat(document.getElementById('actual_quantity').value) || 0;
    const difference = plannedQuantity - actualQuantity;
    
    document.querySelector('.col-md-12.mb-3 input[readonly]').value = difference.toFixed(2);
}

// تحديث الفرق عند تغيير الكميات
document.getElementById('planned_quantity').addEventListener('input', calculateDifference);
document.getElementById('actual_quantity').addEventListener('input', calculateDifference);

// حساب الفرق عند تحميل الصفحة
calculateDifference();
</script>
@endsection 