@extends('layouts.app')

@section('css')
<style>
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
    }
    
    .btn-group .btn {
        margin-left: 2px;
        margin-right: 2px;
    }
    
    /* تحسين عرض الجدول */
    #licensesTable {
        width: 100%;
        min-width: 1200px; /* يضمن أن الجدول سيحتوي جميع الأعمدة دون ضغط */
    }
    
    #licensesTable th, #licensesTable td {
        white-space: nowrap;
        padding: 0.5rem;
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    /* تصميم أعمدة العرض والإخفاء */
    .form-check.form-switch {
        padding-right: 2.5em;
    }
    
    .column-toggle:checked + .form-check-label {
        font-weight: bold;
    }
    
    /* تحسين عرض نافذة الاختبارات */
    .test-details-btn {
        white-space: nowrap;
    }
    
    .modal-header, .modal-footer {
        padding: 0.75rem 1rem;
    }
    
    .modal-body {
        padding: 1.25rem;
    }
    
    .modal-lg {
        max-width: 800px;
    }
    
    .modal .table {
        margin-bottom: 0;
    }
    
    .modal .badge {
        padding: 0.4em 0.6em;
        font-size: 85%;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <h3 class="mb-0 fs-4 text-center text-md-start">بيانات الرخص</h3>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <a href="{{ route('admin.work-orders.licenses') }}" class="btn btn-back btn-sm">
                                <i class="fas fa-arrow-right"></i> العودة لصفحة الرخص
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- فلتر وبحث -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form action="{{ route('admin.work-orders.licenses.data') }}" method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control" placeholder="بحث..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-select">
                                        <option value="">حالة الرخصة</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>سارية</option>
                                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>منتهية</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="type" class="form-select">
                                        <option value="">نوع الرخصة</option>
                                        @foreach($licenses->pluck('license_type')->unique() as $type)
                                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> بحث
                                    </button>
                                    <a href="{{ route('admin.work-orders.licenses.data') }}" class="btn btn-secondary">
                                        <i class="fas fa-redo"></i> إعادة تعيين
                                    </a>
                                </div>
                            </form>
                    </div>
                </div>

                    <!-- جدول الرخص -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>رقم أمر العمل</th>
                                    <th>رقم الرخصة</th>
                                    <th>نوع الرخصة</th>
                                    <th>تاريخ الرخصة</th>
                                    <th>تاريخ البداية</th>
                                    <th>تاريخ النهاية</th>
                                    <th>حالة الرخصة</th>
                                    <th>طول الرخصة</th>
                                    <th>الحظر</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($licenses as $license)
                                    <tr>
                                        <td>{{ $license->workOrder->order_number ?? 'غير محدد' }}</td>
                                        <td>{{ $license->license_number ?? 'غير محدد' }}</td>
                                        <td>{{ $license->license_type ?? 'غير محدد' }}</td>
                                        <td>{{ $license->license_date ? \Carbon\Carbon::parse($license->license_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                        <td>{{ $license->license_start_date ? \Carbon\Carbon::parse($license->license_start_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                        <td>{{ $license->license_end_date ? \Carbon\Carbon::parse($license->license_end_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                        <td>
                                            @php
                                                $endDate = $license->license_extension_end_date ?? $license->license_end_date;
                                                $daysLeft = \Carbon\Carbon::parse($endDate)->diffInDays(now(), false);
                                                $statusClass = $daysLeft > 0 ? 'danger' : 'success';
                                                $statusText = $daysLeft > 0 ? 'منتهية' : 'سارية';
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                            @if($daysLeft !== null)
                                                <small class="text-muted d-block">
                                                    {{ $daysLeft > 0 ? 'انتهت منذ ' . abs($daysLeft) . ' يوم' : 'متبقي ' . abs($daysLeft) . ' يوم' }}
                                                        </small>
                                            @endif
                                        </td>
                                        <td>{{ $license->license_length ? $license->license_length . ' متر' : 'غير محدد' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $license->has_restriction ? 'danger' : 'success' }}">
                                                {{ $license->has_restriction ? 'نعم' : 'لا' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.licenses.show', $license->id) }}" class="btn btn-info btn-sm" title="عرض التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.licenses.edit', $license->id) }}" class="btn btn-primary btn-sm" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">لا توجد رخص مسجلة</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
            </div>
            
                    <!-- الترقيم -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $licenses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* تنسيق الأزرار */
.btn {
    transition: all 0.3s ease;
    border: none;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-back {
    background-color: #795548;
    color: white;
}

.btn-back:hover {
    background-color: #6D4C41;
    color: white;
}

/* تحسين الهيدر */
.bg-gradient-primary {
    background: linear-gradient(45deg, #1976D2, #2196F3);
}

/* تحسين الجداول */
.table {
    margin-bottom: 0;
}

.table th {
    font-weight: 600;
    white-space: nowrap;
    background-color: #f8f9fa;
}

.table td {
    vertical-align: middle;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}

/* تحسين البطاقات */
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    margin-bottom: 1.5rem;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.card-header {
    border-bottom: 1px solid rgba(0,0,0,.125);
    background-color: #f8f9fa;
}

.card-header h4 {
    font-weight: 600;
    margin: 0;
}

/* تحسين الشارات */
.badge {
    padding: 0.5em 1em;
    font-weight: 500;
    border-radius: 0.5rem;
}

/* تحسين الأيقونات */
.fas {
    margin-right: 0.5rem;
}

/* تحسين الروابط */
a {
    text-decoration: none;
}

/* تحسين التجاوب */
@media (max-width: 768px) {
    .table-responsive {
        border: 0;
    }
    
    .table th,
    .table td {
        white-space: normal;
    }
    
    .btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
    }
}
</style>

<!-- قسم سجل المخالفات -->
<div class="card mt-4" id="violations">
    <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-exclamation-triangle me-2"></i>
            سجل المخالفات
        </h5>
        <div class="stats d-flex gap-3">
            <span class="badge bg-light text-dark" title="إجمالي المخالفات">
                <i class="fas fa-list me-1"></i>
                <span id="total-violations">{{ $license->violations->count() }}</span>
            </span>
            <span class="badge bg-warning" title="المخالفات المعلقة">
                <i class="fas fa-clock me-1"></i>
                <span id="pending-violations">{{ $license->violations->where('payment_status', 'pending')->count() }}</span>
            </span>
            <span class="badge bg-success" title="المخالفات المدفوعة">
                <i class="fas fa-check me-1"></i>
                <span id="paid-violations">{{ $license->violations->where('payment_status', 'paid')->count() }}</span>
            </span>
            <span class="badge bg-info" title="إجمالي المبالغ">
                <i class="fas fa-money-bill me-1"></i>
                <span id="total-amount">{{ number_format($license->violations->sum('violation_amount'), 2) }} ر.س</span>
            </span>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="violationsTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>رقم المخالفة</th>
                        <th>التاريخ</th>
                        <th>النوع</th>
                        <th>المبلغ</th>
                        <th>الطرف المسؤول</th>
                        <th>حالة الدفع</th>
                        <th>تاريخ الاستحقاق</th>
                        <th>المرفقات</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($license->violations as $index => $violation)
                    <tr data-violation-id="{{ $violation->id }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $violation->violation_number }}</td>
                        <td>{{ $violation->violation_date }}</td>
                        <td>{{ $violation->violation_type }}</td>
                        <td>{{ number_format($violation->violation_amount, 2) }} ر.س</td>
                        <td>{{ $violation->responsible_party }}</td>
                        <td>
                            <span class="badge {{ $violation->payment_status === 'paid' ? 'bg-success' : 'bg-warning' }}">
                                {{ $violation->payment_status === 'paid' ? 'مدفوع' : 'معلق' }}
                            </span>
                        </td>
                        <td>{{ $violation->payment_due_date }}</td>
                        <td>
                            @if($violation->attachment_path)
                            <a href="{{ $violation->attachment_url }}" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-primary" onclick="editViolation({{ $violation->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger" onclick="deleteViolation({{ $violation->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="no-violations-row">
                        <td colspan="10" class="text-center">لا توجد مخالفات مسجلة</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- نموذج تحرير المخالفة -->
<div class="modal fade" id="violationModal" tabindex="-1" aria-labelledby="violationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="violationModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    تحرير المخالفة
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="violationForm">
                    <input type="hidden" id="violation_id" name="id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="violation_number" class="form-label">رقم المخالفة</label>
                            <input type="text" class="form-control" id="violation_number" name="violation_number" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="violation_date" class="form-label">تاريخ المخالفة</label>
                            <input type="date" class="form-control" id="violation_date" name="violation_date" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="violation_type" class="form-label">نوع المخالفة</label>
                            <select class="form-select" id="violation_type" name="violation_type" required>
                                <option value="">اختر نوع المخالفة</option>
                                <option value="تجاوز المدة">تجاوز المدة</option>
                                <option value="مخالفة فنية">مخالفة فنية</option>
                                <option value="عدم الالتزام بالمواصفات">عدم الالتزام بالمواصفات</option>
                                <option value="أخرى">أخرى</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="violation_amount" class="form-label">مبلغ المخالفة</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="violation_amount" name="violation_amount" step="0.01" required>
                                <span class="input-group-text">ر.س</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="responsible_party" class="form-label">الطرف المسؤول</label>
                            <input type="text" class="form-control" id="responsible_party" name="responsible_party" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="payment_status" class="form-label">حالة الدفع</label>
                            <select class="form-select" id="payment_status" name="payment_status" required>
                                <option value="pending">معلق</option>
                                <option value="paid">مدفوع</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="payment_due_date" class="form-label">تاريخ استحقاق الدفع</label>
                            <input type="date" class="form-control" id="payment_due_date" name="payment_due_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="attachment" class="form-label">المرفقات</label>
                            <input type="file" class="form-control" id="attachment" name="attachment">
                            <div id="current_attachment" class="mt-2" style="display: none;">
                                <small class="text-muted">المرفق الحالي: </small>
                                <a href="#" target="_blank" id="attachment_link"></a>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">ملاحظات</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <button type="button" class="btn btn-success" onclick="saveViolation()">
                    <i class="fas fa-save me-1"></i>
                    حفظ
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // دالة تحرير المخالفة
    function editViolation(id) {
        fetch(`/admin/violations/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const violation = data.data;
                    document.getElementById('violation_id').value = violation.id;
                    document.getElementById('violation_number').value = violation.violation_number;
                    document.getElementById('violation_date').value = violation.violation_date;
                    document.getElementById('violation_type').value = violation.violation_type;
                    document.getElementById('violation_amount').value = violation.violation_amount;
                    document.getElementById('responsible_party').value = violation.responsible_party;
                    document.getElementById('payment_status').value = violation.payment_status;
                    document.getElementById('payment_due_date').value = violation.payment_due_date;
                    
                    const modal = new bootstrap.Modal(document.getElementById('violationModal'));
                    modal.show();
                } else {
                    toastr.error('حدث خطأ أثناء تحميل بيانات المخالفة');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('حدث خطأ أثناء تحميل بيانات المخالفة');
            });
    }

    // دالة حذف المخالفة
    function deleteViolation(id) {
        if (confirm('هل أنت متأكد من حذف هذه المخالفة؟')) {
            fetch(`/admin/violations/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const row = document.querySelector(`tr[data-violation-id="${id}"]`);
                    if (row) {
                        row.remove();
                        updateViolationStats();
                    }
                    toastr.success('تم حذف المخالفة بنجاح');
                } else {
                    toastr.error(result.message || 'حدث خطأ أثناء حذف المخالفة');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('حدث خطأ أثناء حذف المخالفة');
            });
        }
    }

    // دالة تحديث إحصائيات المخالفات
    function updateViolationStats() {
        const violations = Array.from(document.querySelectorAll('#violationsTable tbody tr')).filter(row => !row.id.includes('no-violations'));
        const totalViolations = violations.length;
        const paidViolations = violations.filter(row => row.querySelector('td:nth-child(7) .badge').classList.contains('bg-success')).length;
        const pendingViolations = totalViolations - paidViolations;
        
        let totalAmount = 0;
        violations.forEach(row => {
            const amount = parseFloat(row.querySelector('td:nth-child(5)').textContent.replace(/[^\d.-]/g, ''));
            if (!isNaN(amount)) {
                totalAmount += amount;
            }
        });
        
        document.getElementById('total-violations').textContent = totalViolations;
        document.getElementById('pending-violations').textContent = pendingViolations;
        document.getElementById('paid-violations').textContent = paidViolations;
        document.getElementById('total-amount').textContent = new Intl.NumberFormat('ar-SA').format(totalAmount) + ' ر.س';
    }

    // دالة حفظ المخالفة
    function saveViolation() {
        const form = document.getElementById('violationForm');
        const formData = new FormData(form);
        const violationId = document.getElementById('violation_id').value;
        
        // إضافة معرف الرخصة
        formData.append('license_id', '{{ $license->id }}');
        
        // إضافة CSRF token
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        // إذا كان تحديث لمخالفة موجودة
        if (violationId) {
            formData.append('_method', 'PUT');
        }
        
        // تعطيل زر الحفظ وإظهار حالة التحميل
        const saveButton = document.querySelector('#violationModal .modal-footer .btn-success');
        const originalButtonText = saveButton.innerHTML;
        saveButton.disabled = true;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';
        
        // إرسال البيانات
        fetch(violationId ? `/admin/violations/${violationId}` : '/admin/violations', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                // تحديث الجدول
                if (violationId) {
                    // تحديث المخالفة الموجودة
                    const row = document.querySelector(`#violationsTable tr[data-violation-id="${violationId}"]`);
                    if (row) {
                        updateViolationRow(row, result.data);
                    }
                } else {
                    // إضافة مخالفة جديدة
                    addViolationToTable(result.data);
                }
                
                // تحديث الإحصائيات
                updateViolationStats();
                
                // إغلاق النافذة المنبثقة
                const modal = bootstrap.Modal.getInstance(document.getElementById('violationModal'));
                modal.hide();
                
                // عرض رسالة النجاح
                toastr.success('تم حفظ المخالفة بنجاح');
                
                // إعادة تعيين النموذج
                form.reset();
            } else {
                toastr.error(result.message || 'حدث خطأ أثناء حفظ المخالفة');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('حدث خطأ أثناء حفظ المخالفة');
        })
        .finally(() => {
            // إعادة تفعيل زر الحفظ
            saveButton.disabled = false;
            saveButton.innerHTML = originalButtonText;
        });
    }
</script>
@endpush
@endsection 