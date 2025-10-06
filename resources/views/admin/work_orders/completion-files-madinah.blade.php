@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                أوامر العمل التي تحتاج لرفع ملفات (المدينة المنورة)
                            </h3>
                            <p class="mb-0 opacity-90">
                                <i class="fas fa-mosque me-1"></i>
                                عرض جميع أوامر العمل التي لم يتم رفع ملفات انتهاء العمل عليها
                            </p>
                        </div>
                        <div>
                            <a href="/admin/work-orders/productivity/madinah" 
                               class="btn btn-light btn-lg shadow-sm">
                                <i class="fas fa-arrow-right me-2"></i>
                                العودة إلى لوحة التحكم - المدينة
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ $workOrders->total() }}</h2>
                            <p class="mb-0">أوامر تحتاج لرفع ملفات</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #0e8c80 0%, #2ec96a 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-mosque fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">المدينة المنورة</h2>
                            <p class="mb-0">مشروع المدينة المنورة</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Work Orders Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2 text-warning"></i>
                            قائمة أوامر العمل التي تحتاج لرفع ملفات انتهاء العمل
                        </h5>
                        <a href="{{ route('admin.work-orders.completion.export', ['city' => 'madinah']) }}" 
                           class="btn btn-success">
                            <i class="fas fa-file-excel me-1"></i>
                            تصدير Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($workOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>رقم أمر العمل</th>
                                    <th>نوع العمل</th>
                                    <th class="text-center">تاريخ الاعتماد</th>
                                    <th class="text-center">حالة الرفع</th>
                                    <th class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workOrders as $index => $order)
                                <tr>
                                    <td class="text-center">{{ $workOrders->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $order->order_number }}</strong>
                                    </td>
                                    <td>
                                        {{ $order->work_type ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $order->approval_date ? $order->approval_date->format('Y-m-d') : '-' }}
                                    </td>
                                    <td class="text-center">
                                        @if($order->completion_files_count > 0)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>
                                                تم الرفع
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                لم يتم الرفع
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.work-orders.show', $order->id) }}" 
                                           class="btn btn-sm btn-primary me-1" 
                                           title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($order->completion_files_count > 0)
                                        <button onclick="showFiles({{ $order->id }})" 
                                                class="btn btn-sm btn-success" 
                                                title="عرض الملفات">
                                            <i class="fas fa-folder-open"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $workOrders->links() }}
                    </div>
                    @else
                    <div class="alert alert-success text-center">
                        <i class="fas fa-check-circle fa-2x mb-3"></i>
                        <p class="mb-0">جميع أوامر العمل تم رفع ملفات انتهاء العمل عليها ✓</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal لعرض الملفات -->
<div class="modal fade" id="filesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-folder-open me-2"></i>
                    ملفات بعد انتهاء العمل
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="filesContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }
    
    .badge {
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
    }
    
    .file-item {
        padding: 10px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        margin-bottom: 10px;
        background: #f8f9fa;
    }
    
    .file-item:hover {
        background: #e9ecef;
    }
</style>
@endpush

@push('scripts')
<script>
function showFiles(workOrderId) {
    const modal = new bootstrap.Modal(document.getElementById('filesModal'));
    modal.show();
    
    // جلب الملفات
    fetch(`/admin/work-orders/${workOrderId}/completion-files`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let html = '';
                if (data.files.length > 0) {
                    data.files.forEach(file => {
                        html += `
                            <div class="file-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-file-alt text-success me-2"></i>
                                    <strong>${file.original_filename}</strong>
                                    <br>
                                    <small class="text-muted">
                                        الحجم: ${(file.file_size / 1024).toFixed(2)} KB | 
                                        التاريخ: ${new Date(file.created_at).toLocaleDateString('ar-EG')}
                                    </small>
                                </div>
                                <div>
                                    <a href="/storage/${file.file_path}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-download"></i> تحميل
                                    </a>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<div class="alert alert-info">لا توجد ملفات</div>';
                }
                document.getElementById('filesContent').innerHTML = html;
            }
        })
        .catch(error => {
            document.getElementById('filesContent').innerHTML = '<div class="alert alert-danger">حدث خطأ في تحميل الملفات</div>';
        });
}
</script>
@endpush
@endsection

