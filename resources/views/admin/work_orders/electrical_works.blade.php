@php($hideNavbar = true)
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0" style="font-weight:800; color:#2c3e50; letter-spacing:1px; display:flex; align-items:center;">
                <i class="fas fa-bolt me-2" style="color:#ffc107;"></i>
                أعمال الكهرباء - {{ $workOrder->order_number }}
            </h2>
            <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-outline-secondary">&larr; عودة</a>
        </div>
    </div>
    <div class="row g-4">
        <!-- نموذج بيانات الأعمال الكهربائية -->
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-list-ul me-2"></i>
                    بيانات الأعمال الكهربائية
                </div>
                <div class="card-body">
                    <form id="electrical-works-form" action="{{ route('admin.work-orders.electrical-works.store', $workOrder) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="table-light">
                                                    <tr>
                                                        <th style="width: 40%">البند</th>
                                        <th style="width: 35%">الحالة</th>
                                        <th style="width: 25%">العدد</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                    @foreach($electricalItems as $key => $label)
                                                    <tr>
                                        <td>{{ $label }}</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                <input type="radio" class="btn-check electrical-status" 
                                                       name="electrical_works[{{ $key }}][status]" 
                                                       id="{{ $key }}_completed" 
                                                       value="completed" 
                                                       {{ old('electrical_works.' . $key . '.status', isset($workOrder->electrical_works[$key]['status']) ? $workOrder->electrical_works[$key]['status'] : '') == 'completed' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-success" for="{{ $key }}_completed">تم</label>
                                                
                                                <input type="radio" class="btn-check electrical-status" 
                                                       name="electrical_works[{{ $key }}][status]" 
                                                       id="{{ $key }}_pending" 
                                                       value="pending" 
                                                       {{ old('electrical_works.' . $key . '.status', isset($workOrder->electrical_works[$key]['status']) ? $workOrder->electrical_works[$key]['status'] : '') == 'pending' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-warning" for="{{ $key }}_pending">قيد التنفيذ</label>
                                                
                                                <input type="radio" class="btn-check electrical-status" 
                                                       name="electrical_works[{{ $key }}][status]" 
                                                       id="{{ $key }}_na" 
                                                       value="na" 
                                                       {{ old('electrical_works.' . $key . '.status', isset($workOrder->electrical_works[$key]['status']) ? $workOrder->electrical_works[$key]['status'] : '') == 'na' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-secondary" for="{{ $key }}_na">لا ينطبق</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                            <input type="number" 
                                                   step="1" 
                                                   min="0" 
                                                   class="form-control form-control-sm electrical-quantity" 
                                                   name="electrical_works[{{ $key }}][quantity]" 
                                                   value="{{ old('electrical_works.' . $key . '.quantity', isset($workOrder->electrical_works[$key]['quantity']) && $workOrder->electrical_works[$key]['quantity'] !== null ? $workOrder->electrical_works[$key]['quantity'] : '') }}" 
                                                   placeholder="0" 
                                                   data-item="{{ $key }}">
                                                        </td>
                                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                                                            </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-warning px-4">
                                <i class="fas fa-save me-2"></i>
                                حفظ الأعمال الكهربائية
                            </button>
                        </div>
                        
                        <div id="auto-save-indicator" class="text-center mt-2" style="display: none;">
                            <small class="text-success">
                                <i class="fas fa-check me-1"></i>
                                تم الحفظ التلقائي
                            </small>
                        </div>
                        
                        @if($workOrder->electrical_works && count($workOrder->electrical_works) > 0)
                            <div class="alert alert-info mt-3 text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                تم استرداد البيانات المحفوظة سابقاً - يمكنك تعديلها وحفظها مرة أخرى
                            </div>
                        @endif
                        
                        <!-- تشخيص مؤقت -->
                        <div class="alert alert-warning mt-2" style="font-size: 11px; max-height: 200px; overflow: auto;">
                            <strong>تشخيص البيانات:</strong><br>
                            Raw data: {{ json_encode($workOrder->getOriginal('electrical_works')) }}<br>
                            Electrical works accessor: {{ json_encode($workOrder->electrical_works) }}<br>
                            Sample status for 'al_500_joint': {{ $workOrder->electrical_works['al_500_joint']['status'] ?? 'not set' }}<br>
                            Sample quantity for 'al_500_joint': {{ $workOrder->electrical_works['al_500_joint']['quantity'] ?? 'not set' }}
                        </div>
                    </form>
                </div>
                                                            </div>
                                                            </div>

        <!-- جدول ملخص الأعمال الكهربائية -->
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #ff9f43 0%, #ff6b6b 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>
                            ملخص الأعمال الكهربائية
                        </h5>
                        <button type="button" class="btn btn-light btn-sm" onclick="printSummary()">
                            <i class="fas fa-print me-1"></i>
                            طباعة الملخص
                        </button>
                                                                </div>
                                                            </div>
                <div class="card-body">
                    <!-- إحصائيات سريعة -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center p-3">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <h3 class="mb-1" id="total-completed">0</h3>
                                    <small>تم التنفيذ</small>
                                                                </div>
                                                            </div>
                                                            </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-dark">
                                <div class="card-body text-center p-3">
                                    <i class="fas fa-clock fa-2x mb-2"></i>
                                    <h3 class="mb-1" id="total-pending">0</h3>
                                    <small>قيد التنفيذ</small>
                                                                </div>
                                                            </div>
                                                            </div>
                        <div class="col-md-4">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center p-3">
                                    <i class="fas fa-minus-circle fa-2x mb-2"></i>
                                    <h3 class="mb-1" id="total-na">0</h3>
                                    <small>لا ينطبق</small>
                                                                </div>
                                                            </div>
                                                                </div>
                                                            </div>

                    <!-- جدول الملخص -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="summary-table">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 40%">البند</th>
                                    <th style="width: 35%">الحالة</th>
                                    <th style="width: 25%">العدد</th>
                                                    </tr>
                            </thead>
                            <tbody id="summary-tbody">
                                <!-- سيتم ملء البيانات تلقائياً بواسطة JavaScript -->
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="2" class="text-start fw-bold">إجمالي البنود المنفذة:</td>
                                    <td class="text-center fw-bold" id="total-items">0</td>
                                                    </tr>
                            </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

        <!-- قسم رفع صور الأعمال الكهربائية -->
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-images me-2"></i>
                    صور الأعمال الكهربائية
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.work-orders.electrical-works.images', $workOrder) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="electrical_works_images" class="form-label">اختر الصور</label>
                            <input type="file" class="form-control" id="electrical_works_images" name="electrical_works_images[]" multiple accept="image/*">
                            <div class="form-text">يمكنك اختيار عدة صور (حتى 70 صورة، كل صورة حتى 30 ميجا)</div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-info px-4">
                                <i class="fas fa-upload me-2"></i>
                                رفع الصور
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// المتغيرات العامة
let autoSaveTimeout;



// تهيئة الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // إعداد الحفظ التلقائي
    setupAutoSave();
    
    // إعداد الحفظ اليدوي
    const form = document.getElementById('electrical-works-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // التحقق من أن النموذج جاهز للإرسال
            console.log('Form submitting...');
        });
    }
    
    // تحديث الملخص عند تغيير أي حقل
    document.querySelectorAll('.electrical-status, .electrical-quantity').forEach(input => {
        input.addEventListener('change', updateElectricalWorksSummary);
    });
    
    // تحديث أولي للملخص
    updateElectricalWorksSummary();
});

// إعداد الحفظ التلقائي
function setupAutoSave() {
    const form = document.getElementById('electrical-works-form');
    if (!form) return;
    
    form.addEventListener('change', function(e) {
        if (e.target.classList.contains('electrical-status') || 
            e.target.classList.contains('electrical-quantity')) {
            
            // إلغاء التايمر السابق
            clearTimeout(autoSaveTimeout);
            
            // تأخير الحفظ لثانية واحدة
            autoSaveTimeout = setTimeout(() => {
                autoSaveElectricalWorks();
            }, 1000);
        }
    });
}

// دالة الحفظ التلقائي
function autoSaveElectricalWorks() {
    const form = document.getElementById('electrical-works-form');
    if (!form) return;
    
    const formData = new FormData(form);
    
    // التأكد من إرسال جميع قيم quantity بشكل صحيح
    document.querySelectorAll('input[type="number"].electrical-quantity').forEach(input => {
        const name = input.name;
        const value = input.value || '';
        
        // تحديث القيمة في FormData مع القيمة الفعلية من الحقل
        formData.set(name, value);
        
        console.log('Sending quantity:', name, '=', value);
    });
    
    // التأكد من إرسال جميع قيم status
    document.querySelectorAll('input[type="radio"].electrical-status:checked').forEach(input => {
        const name = input.name;
        const value = input.value;
        
        formData.set(name, value);
        console.log('Sending status:', name, '=', value);
    });
    
    // طباعة جميع البيانات المرسلة للتشخيص
    console.log('FormData contents:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    
    fetch('{{ route("admin.work-orders.electrical-works.store.post", $workOrder) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAutoSaveIndicator();
            // تحديث الملخص بعد الحفظ
            updateElectricalWorksSummary();
        } else {
            console.error('Auto-save failed:', data.message);
        }
    })
    .catch(error => {
        console.error('Auto-save error:', error);
    });
}

// عرض مؤشر الحفظ التلقائي
function showAutoSaveIndicator() {
    const indicator = document.getElementById('auto-save-indicator');
    if (indicator) {
        indicator.style.display = 'block';
        setTimeout(() => {
            indicator.style.display = 'none';
        }, 2000);
    }
}

// دالة تحديث ملخص الأعمال الكهربائية
function updateElectricalWorksSummary() {
    const tbody = document.getElementById('summary-tbody');
    const totalCompleted = document.getElementById('total-completed');
    const totalPending = document.getElementById('total-pending');
    const totalNA = document.getElementById('total-na');
    const totalItems = document.getElementById('total-items');
    
    if (!tbody) return;
    
    tbody.innerHTML = '';
    let completed = 0;
    let pending = 0;
    let na = 0;
    let totalQuantity = 0;
    
    // جمع البيانات من النموذج
    document.querySelectorAll('tr').forEach(row => {
        const label = row.querySelector('td')?.textContent?.trim();
        if (!label) return;
        
        const status = row.querySelector('input[type="radio"]:checked')?.value;
        const quantityValue = row.querySelector('input[type="number"]')?.value;
        const quantity = quantityValue && quantityValue !== '' ? parseInt(quantityValue) : 0;
        
        if (status) {
            // إضافة صف للجدول
            const newRow = tbody.insertRow();
            newRow.innerHTML = `
                <td>${label}</td>
                <td class="text-center">
                    ${getStatusBadge(status)}
                </td>
                <td class="text-center">
                    <span class="badge bg-primary rounded-pill">${quantity}</span>
                </td>
            `;
            
            // تحديث الإحصائيات
            if (status === 'completed') {
                completed++;
                totalQuantity += quantity;
            }
            else if (status === 'pending') pending++;
            else if (status === 'na') na++;
        }
    });
    
    // تحديث الإحصائيات
    totalCompleted.textContent = completed;
    totalPending.textContent = pending;
    totalNA.textContent = na;
    totalItems.textContent = totalQuantity;
    
    // إضافة رسالة إذا لم تكن هناك بيانات
    if (tbody.children.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="3" class="text-center text-muted py-4">
                    <i class="fas fa-info-circle fa-2x mb-2"></i><br>
                    لم يتم إدخال أي بيانات بعد
                </td>
            </tr>
        `;
    }
}

// دالة إرجاع شارة الحالة
function getStatusBadge(status) {
    switch(status) {
        case 'completed':
            return '<span class="badge bg-success">تم التنفيذ</span>';
        case 'pending':
            return '<span class="badge bg-warning text-dark">قيد التنفيذ</span>';
        case 'na':
            return '<span class="badge bg-secondary">لا ينطبق</span>';
        default:
            return '';
    }
}

// دالة طباعة الملخص
function printSummary() {
    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
        <html dir="rtl">
            <head>
                <title>ملخص الأعمال الكهربائية</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { font-family: Arial, sans-serif; }
                    .table th, .table td { padding: 8px; }
                    @media print {
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body class="p-4">
                <div class="text-center mb-4">
                    <h3>ملخص الأعمال الكهربائية</h3>
                    <p class="text-muted">تاريخ الطباعة: ${new Date().toLocaleDateString('ar-SA')}</p>
                </div>
                ${document.getElementById('summary-table').outerHTML}
                <div class="row mt-4">
                    <div class="col-4 text-center">
                        <h5>تم التنفيذ: ${document.getElementById('total-completed').textContent}</h5>
                    </div>
                    <div class="col-4 text-center">
                        <h5>قيد التنفيذ: ${document.getElementById('total-pending').textContent}</h5>
                    </div>
                    <div class="col-4 text-center">
                        <h5>لا ينطبق: ${document.getElementById('total-na').textContent}</h5>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <h5>إجمالي البنود المنفذة: ${document.getElementById('total-items').textContent}</h5>
                </div>
                <button class="btn btn-primary mt-4 no-print" onclick="window.print()">طباعة</button>
            </body>
        </html>
    `);
    printWindow.document.close();
}
</script>
@endpush

@endsection 