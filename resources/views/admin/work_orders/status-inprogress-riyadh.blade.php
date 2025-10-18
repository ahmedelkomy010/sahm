@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">
                                <i class="fas fa-cogs me-2"></i>
                                أوامر العمل - جاري العمل بالموقع (الرياض)
                            </h3>
                            <p class="mb-0 opacity-90">
                                <i class="fas fa-city me-1"></i>
                                عرض جميع أوامر العمل بالرياض التي حالتها: جاري العمل بالموقع
                            </p>
                        </div>
                        <div>
                            <a href="/admin/work-orders/productivity/riyadh" 
                               class="btn btn-light btn-lg shadow-sm">
                                <i class="fas fa-arrow-right me-2"></i>
                                العودة إلى لوحة التحكم - الرياض
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-clipboard-list fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ $workOrders->total() }}</h2>
                            <p class="mb-0">إجمالي أوامر العمل</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 bg-success">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-money-bill-wave fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ number_format($totalValue, 0) }}</h2>
                            <p class="mb-0">إجمالي القيمة المبدئية</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 bg-primary">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-city fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">الرياض</h2>
                            <p class="mb-0">مشروع الرياض</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-filter me-2"></i>
                        خيارات العرض والتصدير
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.work-orders.status.inprogress.riyadh') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">عدد النتائج في الصفحة</label>
                            <select class="form-select" name="per_page" onchange="this.form.submit()">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label d-block">&nbsp;</label>
                            <a href="{{ route('admin.work-orders.status.export-inprogress', ['project' => 'riyadh']) }}" 
                               class="btn btn-success w-100">
                                <i class="fas fa-file-excel me-2"></i>
                                تصدير إلى Excel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Work Orders Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2 text-info"></i>
                        قائمة أوامر العمل
                    </h5>
                </div>
                <div class="card-body">
                    @if($workOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>رقم أمر العمل</th>
                                    <th>نوع أمر العمل</th>
                                    <th>المكتب</th>
                                    <th class="text-center">القيمة المبدئية</th>
                                    <th class="text-center">الحالة</th>
                                    <th class="text-center">تعديل ملاحظات أمر العمل</th>
                                    <th class="text-center">تاريخ الاعتماد</th>
                                    <th class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workOrders as $index => $order)
                                <tr>
                                    <td class="text-center">{{ $workOrders->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $order->work_order_number }}</strong>
                                    </td>
                                    <td>{{ $order->work_type ?? '-' }}</td>
                                    <td>{{ $order->office ?? '-' }}</td>
                                    <td class="text-center">
                                        <strong class="text-primary">
                                            {{ number_format($order->order_value_without_consultant ?? 0, 2) }}
                                        </strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">
                                            <i class="fas fa-cogs me-1"></i>
                                            جاري العمل بالموقع
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" 
                                                class="btn btn-sm btn-warning" 
                                                onclick="openNotesModal({{ $order->id }}, '{{ addslashes($order->work_order_number) }}', '{{ addslashes($order->notes ?? '') }}')"
                                                title="تعديل الملاحظات">
                                            <i class="fas fa-edit me-1"></i>
                                            تعديل
                                        </button>
                                        @if($order->notes)
                                            <small class="d-block text-muted mt-1" title="{{ $order->notes }}">
                                                {{ Str::limit($order->notes, 20) }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $order->approval_date ? \Carbon\Carbon::parse($order->approval_date)->format('Y-m-d') : '-' }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.work-orders.show', $order->id) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
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
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                        <p class="mb-0">لا توجد أوامر عمل بحالة "جاري العمل بالموقع" في الرياض حالياً</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal تعديل الملاحظات -->
<div class="modal fade" id="notesModal" tabindex="-1" aria-hidden="true" style="z-index: 9999 !important;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    تعديل ملاحظات أمر العمل
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="notesForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="workOrderId" name="work_order_id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">رقم أمر العمل:</label>
                        <p class="text-primary" id="workOrderNumber"></p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label fw-bold">
                            <i class="fas fa-sticky-note me-1"></i>
                            الملاحظات:
                        </label>
                        <textarea class="form-control" 
                                  id="notes" 
                                  name="notes" 
                                  rows="5" 
                                  placeholder="أدخل ملاحظات أمر العمل هنا..."></textarea>
                        <small class="text-muted">يمكنك كتابة أي ملاحظات خاصة بأمر العمل</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    إلغاء
                </button>
                <button type="button" class="btn btn-warning" onclick="saveNotes()">
                    <i class="fas fa-save me-1"></i>
                    حفظ الملاحظات
                </button>
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
    }
    
    .badge {
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
    }
    
    #notesModal {
        z-index: 9999 !important;
    }
    
    #notesModal .modal-dialog {
        z-index: 10000 !important;
        position: relative !important;
    }
    
    #notesModal .modal-content {
        z-index: 10001 !important;
        position: relative !important;
    }
    
    .modal-backdrop {
        z-index: 1040 !important;
    }
</style>
@endpush

@push('scripts')
<script>
function openNotesModal(workOrderId, workOrderNumber, currentNotes) {
    document.getElementById('workOrderId').value = workOrderId;
    document.getElementById('workOrderNumber').textContent = workOrderNumber;
    document.getElementById('notes').value = currentNotes;
    
    const modalElement = document.getElementById('notesModal');
    const modal = new bootstrap.Modal(modalElement, {
        backdrop: true,
        keyboard: true
    });
    
    // إصلاح الـ z-index عند فتح المودال
    modalElement.addEventListener('shown.bs.modal', function () {
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => {
            backdrop.style.setProperty('z-index', '1040', 'important');
        });
        modalElement.style.setProperty('z-index', '9999', 'important');
    }, { once: true });
    
    modal.show();
}

function saveNotes() {
    const workOrderId = document.getElementById('workOrderId').value;
    const notes = document.getElementById('notes').value;
    const saveButton = event.target;
    
    // إظهار مؤشر التحميل
    saveButton.disabled = true;
    saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري الحفظ...';
    
    // إرسال البيانات
    fetch(`/admin/work-orders/${workOrderId}/update-notes`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ notes: notes })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // إغلاق المودال
            const modal = bootstrap.Modal.getInstance(document.getElementById('notesModal'));
            modal.hide();
            
            // إظهار رسالة نجاح
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
            alertDiv.style.zIndex = '9999';
            alertDiv.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                ${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);
            
            // إزالة الرسالة بعد 3 ثواني
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
            
            // إعادة تحميل الصفحة لتحديث البيانات
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            alert('حدث خطأ: ' + (data.message || 'فشل حفظ الملاحظات'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء حفظ الملاحظات');
    })
    .finally(() => {
        saveButton.disabled = false;
        saveButton.innerHTML = '<i class="fas fa-save me-1"></i> حفظ الملاحظات';
    });
}
</script>
@endpush
@endsection

