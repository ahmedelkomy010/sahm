@extends('layouts.admin')

@section('title', 'تعديل الرخصة رقم ' . $license->license_number)

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0 bg-warning text-dark">
                <div class="card-body text-center py-4">
                    <h1 class="display-5 fw-bold mb-2">تعديل الرخصة</h1>
                    <h2 class="h3 mb-3">رقم الرخصة: {{ $license->license_number }}</h2>
                    <p class="mb-1">أمر العمل رقم: {{ $license->workOrder->order_number ?? 'غير محدد' }}</p>
                    <p class="mb-3">اسم المشترك: {{ $license->workOrder->subscriber_name ?? 'غير محدد' }}</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.licenses.show', $license) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right me-2"></i>رجوع لعرض التفاصيل
                        </a>
                        <a href="{{ route('admin.work-orders.license', $license->workOrder) }}" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i>رجوع لإدارة الرخص
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">تعديل الرخصة رقم: {{ $license->license_number }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.licenses.update', $license) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">رقم الرخصة</label>
                                <input type="text" class="form-control" name="license_number" 
                                       value="{{ old('license_number', $license->license_number) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">تاريخ الإصدار</label>
                                <input type="date" class="form-control" name="license_date" 
                                       value="{{ old('license_date', $license->license_date ? $license->license_date->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">نوع الرخصة</label>
                                <select class="form-select" name="license_type">
                                    <option value="">اختر نوع الرخصة</option>
                                    <option value="emergency" {{ $license->license_type == 'emergency' ? 'selected' : '' }}>طوارئ</option>
                                    <option value="project" {{ $license->license_type == 'project' ? 'selected' : '' }}>مشروع</option>
                                    <option value="normal" {{ $license->license_type == 'normal' ? 'selected' : '' }}>عادي</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">قيمة الرخصة</label>
                                <input type="number" step="0.01" class="form-control" name="license_value" 
                                       value="{{ old('license_value', $license->license_value) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">قيمة التمديد</label>
                                <input type="number" step="0.01" class="form-control" name="extension_value" 
                                       value="{{ old('extension_value', $license->extension_value) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">جهة الحظر</label>
                                <input type="text" class="form-control" name="restriction_authority" 
                                       value="{{ old('restriction_authority', $license->restriction_authority) }}">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">ملاحظات شهادة التنسيق</label>
                                <textarea class="form-control" name="coordination_certificate_notes" rows="3">{{ old('coordination_certificate_notes', $license->coordination_certificate_notes) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ملاحظات عامة</label>
                                <textarea class="form-control" name="notes" rows="3">{{ old('notes', $license->notes) }}</textarea>
                            </div>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('admin.licenses.show', $license) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>إلغاء
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>حفظ التعديلات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
}

.card-header {
    font-weight: 600;
}

.form-label {
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control:focus, .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تحسين تجربة المستخدم مع النماذج
    const form = document.querySelector('form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = document.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';
            }
        });
    }
});
</script>
@endsection 