@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header text-white py-4" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                المخالفات (المدينة المنورة)
                            </h3>
                            <p class="mb-0 opacity-90">
                                <i class="fas fa-mosque me-1"></i>
                                عرض جميع مخالفات أوامر العمل في مشروع المدينة المنورة
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.quality.safety-violations.madinah') }}" class="btn btn-warning">
                                <i class="fas fa-hard-hat me-2"></i>
                                مخالفات السلامة
                            </a>
                            <a href="/admin/work-orders/productivity/madinah" class="btn btn-light">
                                <i class="fas fa-arrow-right me-2"></i>
                                العودة إلى لوحة التحكم
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-ban fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ $violationsCount }}</h2>
                            <p class="mb-0">عدد المخالفات</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-money-bill-wave fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ number_format($totalAmount, 2) }}</h2>
                            <p class="mb-0">إجمالي قيمة المخالفات (ريال)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #0e8c80 0%, #2ec96a 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-mosque fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">المدينة المنورة</h2>
                            <p class="mb-0">مشروع المدينة المنورة</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-filter me-2 text-primary"></i>
                        فلاتر البحث والتصفية
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.quality.violations.madinah') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fas fa-file-alt me-1"></i>
                                رقم أمر العمل
                            </label>
                            <input type="text" 
                                   name="order_number" 
                                   class="form-control" 
                                   placeholder="ابحث برقم أمر العمل"
                                   value="{{ request('order_number') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fas fa-tools me-1"></i>
                                نوع العمل
                            </label>
                            <input type="text" 
                                   name="work_type" 
                                   class="form-control" 
                                   placeholder="ابحث بنوع العمل"
                                   value="{{ request('work_type') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fas fa-tag me-1"></i>
                                نوع المخالفة
                            </label>
                            <select name="violation_type" class="form-select">
                                <option value="">-- الكل --</option>
                                <option value="جسيمة" {{ request('violation_type') == 'جسيمة' ? 'selected' : '' }}>جسيمة</option>
                                <option value="غير جسيمة" {{ request('violation_type') == 'غير جسيمة' ? 'selected' : '' }}>غير جسيمة</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fas fa-money-check-alt me-1"></i>
                                حالة السداد
                            </label>
                            <select name="payment_status" class="form-select">
                                <option value="">-- الكل --</option>
                                <option value="1" {{ request('payment_status') == '1' ? 'selected' : '' }}>مسددة</option>
                                <option value="0" {{ request('payment_status') == '0' ? 'selected' : '' }}>غير مسددة</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fas fa-calendar-alt me-1"></i>
                                من تاريخ
                            </label>
                            <input type="date" 
                                   name="date_from" 
                                   class="form-control"
                                   value="{{ request('date_from') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fas fa-calendar-alt me-1"></i>
                                إلى تاريخ
                            </label>
                            <input type="date" 
                                   name="date_to" 
                                   class="form-control"
                                   value="{{ request('date_to') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fas fa-money-bill-wave me-1"></i>
                                قيمة المخالفة (من)
                            </label>
                            <input type="number" 
                                   name="amount_from" 
                                   class="form-control" 
                                   placeholder="0.00"
                                   step="0.01"
                                   value="{{ request('amount_from') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fas fa-money-bill-wave me-1"></i>
                                قيمة المخالفة (إلى)
                            </label>
                            <input type="number" 
                                   name="amount_to" 
                                   class="form-control" 
                                   placeholder="0.00"
                                   step="0.01"
                                   value="{{ request('amount_to') }}">
                        </div>

                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>
                                    بحث
                                </button>
                                <a href="{{ route('admin.quality.violations.madinah') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo me-1"></i>
                                    إعادة تعيين
                                </a>
                                <a href="{{ route('admin.quality.violations.export', ['city' => 'madinah']) }}" class="btn btn-success ms-auto">
                                    <i class="fas fa-file-excel me-1"></i>
                                    تصدير Excel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Violations Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2 text-danger"></i>
                            قائمة المخالفات
                            @if(request()->hasAny(['order_number', 'work_type', 'violation_type', 'payment_status', 'date_from', 'date_to', 'amount_from', 'amount_to']))
                                <span class="badge bg-primary">{{ $violations->total() }} نتيجة</span>
                            @endif
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>رقم أمر العمل</th>
                                    <th>نوع المخالفة</th>
                                    <th class="text-center">تاريخ المخالفة</th>
                                    <th class="text-center">قيمة المخالفة</th>
                                    <th>الوصف</th>
                                    <th class="text-center">الحالة</th>
                                    <th class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($violations as $index => $violation)
                                <tr>
                                    <td class="text-center">{{ $violations->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $violation->workOrder->order_number ?? 'غير محدد' }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            {{ $violation->violation_type ?? 'غير محدد' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ $violation->violation_date ? $violation->violation_date->format('Y-m-d') : '-' }}
                                    </td>
                                    <td class="text-center">
                                        <strong class="text-danger">
                                            {{ number_format($violation->violation_amount ?? 0, 2) }} ريال
                                        </strong>
                                    </td>
                                    <td>
                                        <small>{{ $violation->description ?? $violation->violation_description ?? '-' }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($violation->status == 'نشط' || $violation->status == 'active')
                                            <span class="badge bg-danger">نشط</span>
                                        @elseif($violation->status == 'قيد المعالجة' || $violation->status == 'in_progress')
                                            <span class="badge bg-warning">قيد المعالجة</span>
                                        @elseif($violation->status == 'تم الحل' || $violation->status == 'resolved')
                                            <span class="badge bg-success">تم الحل</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $violation->status ?? 'نشط' }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.work-orders.show', $violation->work_order_id) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                                            <p class="mb-0">لا توجد مخالفات مسجلة حالياً</p>
                                            <button class="btn btn-primary btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#addViolationModal">
                                                <i class="fas fa-plus me-1"></i>
                                                إضافة أول مخالفة
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($violations->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $violations->links() }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Add Violation Modal -->
<div class="modal fade" id="addViolationModal" tabindex="-1" aria-labelledby="addViolationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);">
                <h5 class="modal-title text-white" id="addViolationModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>
                    إضافة مخالفة جديدة
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addViolationForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="work_order_number" class="form-label">
                                <i class="fas fa-file-alt me-1"></i>
                                رقم أمر العمل <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="work_order_number" name="work_order_number" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="violation_type" class="form-label">
                                <i class="fas fa-tag me-1"></i>
                                نوع المخالفة <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="violation_type" name="violation_type" required>
                                <option value="">-- اختر نوع المخالفة --</option>
                                <option value="تأخير">تأخير في التنفيذ</option>
                                <option value="جودة">عدم مطابقة الجودة</option>
                                <option value="سلامة">مخالفة السلامة</option>
                                <option value="مواد">استخدام مواد غير مطابقة</option>
                                <option value="أخرى">أخرى</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="violation_date" class="form-label">
                                <i class="fas fa-calendar-alt me-1"></i>
                                تاريخ المخالفة <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" id="violation_date" name="violation_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="violation_amount" class="form-label">
                                <i class="fas fa-money-bill-wave me-1"></i>
                                قيمة المخالفة (ريال) <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" id="violation_amount" name="violation_amount" step="0.01" min="0" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="violation_description" class="form-label">
                            <i class="fas fa-align-right me-1"></i>
                            الوصف
                        </label>
                        <textarea class="form-control" id="violation_description" name="violation_description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="violation_status" class="form-label">
                            <i class="fas fa-info-circle me-1"></i>
                            الحالة
                        </label>
                        <select class="form-select" id="violation_status" name="violation_status">
                            <option value="نشط">نشط</option>
                            <option value="قيد المعالجة">قيد المعالجة</option>
                            <option value="تم الحل">تم الحل</option>
                            <option value="ملغي">ملغي</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    إلغاء
                </button>
                <button type="button" class="btn btn-primary" onclick="saveViolation()">
                    <i class="fas fa-save me-1"></i>
                    حفظ المخالفة
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set today's date as default
    document.getElementById('violation_date').valueAsDate = new Date();
});

function saveViolation() {
    const form = document.getElementById('addViolationForm');
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    
    // TODO: Send data to backend
    console.log('Violation data:', data);
    
    // Close modal and show success message
    const modal = bootstrap.Modal.getInstance(document.getElementById('addViolationModal'));
    modal.hide();
    
    // Show success notification
    alert('تم إضافة المخالفة بنجاح! ✅\nسيتم إعادة تحميل الصفحة...');
    
    // Reload page
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}
</script>
@endpush

@endsection

