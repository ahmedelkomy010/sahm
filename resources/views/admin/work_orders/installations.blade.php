@php($hideNavbar = true)
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0" style="font-weight:800; color:#2c3e50; letter-spacing:1px; display:flex; align-items:center;">
                <i class="fas fa-tools me-2" style="color:#007bff;"></i>
                إدارة التركيبات - {{ $workOrder->order_number }}
            </h2>
            <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-outline-secondary">&larr; عودة</a>
        </div>
    </div>
    <div class="row g-4">
        <!-- نموذج بيانات التركيبات منفصل -->
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">بيانات التركيبات</div>
                <div class="card-body">
                    <form id="installations-form" action="{{ route('admin.work-orders.installations.store', $workOrder) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>نوع التركيب</th>
                                        <th>الحالة</th>
                                        <th>العدد</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($installations as $key => $label)
                                    <tr>
                                        <td>{{ $label }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                <input type="radio" class="btn-check installation-status" name="installations[{{ $key }}][status]" id="{{ $key }}_yes" value="yes" {{ old('installations.' . $key . '.status', isset($workOrder->installations[$key]['status']) ? $workOrder->installations[$key]['status'] : '') == 'yes' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-success" for="{{ $key }}_yes">نعم</label>
                                                <input type="radio" class="btn-check installation-status" name="installations[{{ $key }}][status]" id="{{ $key }}_no" value="no" {{ old('installations.' . $key . '.status', isset($workOrder->installations[$key]['status']) ? $workOrder->installations[$key]['status'] : '') == 'no' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-danger" for="{{ $key }}_no">لا</label>
                                                <input type="radio" class="btn-check installation-status" name="installations[{{ $key }}][status]" id="{{ $key }}_na" value="na" {{ old('installations.' . $key . '.status', isset($workOrder->installations[$key]['status']) ? $workOrder->installations[$key]['status'] : '') == 'na' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-secondary" for="{{ $key }}_na">لا ينطبق</label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" step="1" min="0" class="form-control form-control-sm installation-quantity" name="installations[{{ $key }}][quantity]" value="{{ old('installations.' . $key . '.quantity', isset($workOrder->installations[$key]['quantity']) ? $workOrder->installations[$key]['quantity'] : '0') }}" placeholder="0" data-installation="{{ $key }}">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary px-4">حفظ بيانات التركيبات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- جدول ملخص التركيبات -->
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>
                            ملخص التركيبات
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
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center p-3">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <h3 class="mb-1" id="total-completed">0</h3>
                                    <small>تم التركيب</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center p-3">
                                    <i class="fas fa-times-circle fa-2x mb-2"></i>
                                    <h3 class="mb-1" id="total-pending">0</h3>
                                    <small>لم يتم التركيب</small>
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
                                    <th style="width: 40%">نوع التركيب</th>
                                    <th style="width: 30%">الحالة</th>
                                    <th style="width: 30%">العدد</th>
                                </tr>
                            </thead>
                            <tbody id="summary-tbody">
                                <!-- سيتم ملء البيانات تلقائياً بواسطة JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- نموذج رفع صور التركيبات -->
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-images me-2"></i>
                    رفع صور التركيبات
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.work-orders.installations.images', $workOrder) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="installations_images" class="form-label">اختر صور التركيبات</label>
                            <input type="file" class="form-control" id="installations_images" name="installations_images[]" multiple accept="image/*">
                            <div class="form-text">يمكنك اختيار عدة صور (حتى 70 صورة، كل صورة حتى 30 ميجا)</div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success px-4">حفظ الصور</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- عرض صور التركيبات المرفوعة -->
    @if($installationImages->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-images me-2"></i>
                        صور التركيبات المرفوعة
                    </div>
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#viewAllImagesModal">
                        <i class="fas fa-expand-alt me-1"></i>
                        عرض جميع الصور
                    </button>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($installationImages as $image)
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="card h-100 position-relative">
                                    <button type="button" 
                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 delete-image" 
                                            data-image-id="{{ $image->id }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteImageModal"
                                            style="z-index: 1;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <a href="#" class="text-decoration-none view-image" 
                                       data-bs-toggle="modal" 
                                       data-bs-target="#viewImageModal"
                                       data-image-url="{{ Storage::url($image->file_path) }}"
                                       data-image-name="{{ $image->original_filename }}"
                                       data-image-date="{{ \Carbon\Carbon::parse($image->created_at)->format('Y-m-d H:i') }}">
                                        <img src="{{ Storage::url($image->file_path) }}" 
                                             class="card-img-top" 
                                             alt="{{ $image->original_filename }}"
                                             style="height: 200px; object-fit: cover;">
                                        <div class="card-body p-2">
                                            <p class="card-text small text-muted mb-0 text-truncate">
                                                {{ $image->original_filename }}
                                            </p>
                                            <p class="card-text small text-muted mb-0">
                                                {{ \Carbon\Carbon::parse($image->created_at)->format('Y-m-d H:i') }}
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal لعرض صورة واحدة -->
    <div class="modal fade" id="viewImageModal" tabindex="-1" aria-labelledby="viewImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewImageModalLabel">عرض الصورة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalImage" class="img-fluid" alt="">
                    <div class="mt-3">
                        <p class="mb-1" id="modalImageName"></p>
                        <p class="text-muted small mb-0" id="modalImageDate"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal لعرض جميع الصور -->
    <div class="modal fade" id="viewAllImagesModal" tabindex="-1" aria-labelledby="viewAllImagesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewAllImagesModalLabel">جميع صور التركيبات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        @foreach($installationImages as $image)
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="card h-100">
                                    <a href="#" class="text-decoration-none view-image" 
                                       data-bs-toggle="modal" 
                                       data-bs-target="#viewImageModal"
                                       data-image-url="{{ Storage::url($image->file_path) }}"
                                       data-image-name="{{ $image->original_filename }}"
                                       data-image-date="{{ \Carbon\Carbon::parse($image->created_at)->format('Y-m-d H:i') }}">
                                        <img src="{{ Storage::url($image->file_path) }}" 
                                             class="card-img-top" 
                                             alt="{{ $image->original_filename }}"
                                             style="height: 200px; object-fit: cover;">
                                        <div class="card-body p-2">
                                            <p class="card-text small text-muted mb-0 text-truncate">
                                                {{ $image->original_filename }}
                                            </p>
                                            <p class="card-text small text-muted mb-0">
                                                {{ \Carbon\Carbon::parse($image->created_at)->format('Y-m-d H:i') }}
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal لحذف صورة -->
    <div class="modal fade" id="deleteImageModal" tabindex="-1" aria-labelledby="deleteImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteImageModalLabel">تأكيد حذف الصورة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    هل أنت متأكد من حذف هذه الصورة؟
                </div>
                <div class="modal-footer">
                    <form id="deleteImageForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-danger">حذف</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // عرض صورة في النافذة المنبثقة
    const viewImageButtons = document.querySelectorAll('.view-image');
    viewImageButtons.forEach(button => {
        button.addEventListener('click', function() {
            const imageUrl = this.dataset.imageUrl;
            const imageName = this.dataset.imageName;
            const imageDate = this.dataset.imageDate;
            
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('modalImageName').textContent = imageName;
            document.getElementById('modalImageDate').textContent = imageDate;
        });
    });

    // حذف صورة
    const deleteButtons = document.querySelectorAll('.delete-image');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const imageId = this.dataset.imageId;
            const form = document.getElementById('deleteImageForm');
            form.action = `/admin/work-orders/installations/images/${imageId}`;
        });
    });
});

// دالة تحديث ملخص التركيبات
function updateInstallationsSummary() {
    const tbody = document.getElementById('summary-tbody');
    const totalCompleted = document.getElementById('total-completed');
    const totalPending = document.getElementById('total-pending');
    const totalNA = document.getElementById('total-na');
    
    if (!tbody) return;
    
    tbody.innerHTML = '';
    let completed = 0;
    let pending = 0;
    let na = 0;
    
    // جمع البيانات من النموذج
    document.querySelectorAll('tr').forEach(row => {
        const label = row.querySelector('td')?.textContent?.trim();
        if (!label) return;
        
        const status = row.querySelector('input[type="radio"]:checked')?.value;
        const quantity = parseInt(row.querySelector('input[type="number"]')?.value || '0');
        
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
            if (status === 'yes') completed++;
            else if (status === 'no') pending++;
            else if (status === 'na') na++;
        }
    });
    
    // تحديث الإحصائيات
    totalCompleted.textContent = completed;
    totalPending.textContent = pending;
    totalNA.textContent = na;
    
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
        case 'yes':
            return '<span class="badge bg-success">تم التركيب</span>';
        case 'no':
            return '<span class="badge bg-danger">لم يتم التركيب</span>';
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
                <title>ملخص التركيبات</title>
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
                    <h3>ملخص التركيبات</h3>
                    <p class="text-muted">تاريخ الطباعة: ${new Date().toLocaleDateString('ar-SA')}</p>
                </div>
                ${document.getElementById('summary-table').outerHTML}
                <div class="row mt-4">
                    <div class="col-4 text-center">
                        <h5>تم التركيب: ${document.getElementById('total-completed').textContent}</h5>
                    </div>
                    <div class="col-4 text-center">
                        <h5>لم يتم التركيب: ${document.getElementById('total-pending').textContent}</h5>
                    </div>
                    <div class="col-4 text-center">
                        <h5>لا ينطبق: ${document.getElementById('total-na').textContent}</h5>
                    </div>
                </div>
                <button class="btn btn-primary mt-4 no-print" onclick="window.print()">طباعة</button>
            </body>
        </html>
    `);
    printWindow.document.close();
}

// إضافة مستمعي الأحداث
document.addEventListener('DOMContentLoaded', function() {
    // تحديث الملخص عند تغيير أي حقل
    document.querySelectorAll('.installation-status, .installation-quantity').forEach(input => {
        input.addEventListener('change', updateInstallationsSummary);
    });
    
    // تحديث أولي للملخص
    updateInstallationsSummary();
});
</script>
@endpush

@endsection 