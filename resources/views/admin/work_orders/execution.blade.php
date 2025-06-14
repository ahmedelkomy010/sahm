@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 fs-4">صفحة التنفيذ</h3>
                        <div class="d-flex align-items-center gap-3">
                            @php
                                $createdDate = $workOrder->created_at;
                                $daysPassed = (int) $createdDate->diffInDays(now());
                                $isToday = $createdDate->isToday();
                                $isYesterday = $createdDate->isYesterday();
                                
                                if ($isToday) {
                                    $daysText = 'اليوم';
                                    $badgeColor = 'success';
                                } elseif ($isYesterday) {
                                    $daysText = 'أمس';
                                    $badgeColor = 'info';
                                } elseif ($daysPassed <= 7) {
                                    $daysText = $daysPassed . ' ' . ($daysPassed == 1 ? 'يوم' : 'أيام');
                                    $badgeColor = 'warning';
                                } elseif ($daysPassed <= 30) {
                                    $daysText = $daysPassed . ' يوم';
                                    $badgeColor = 'danger';
                                } else {
                                    $daysText = $daysPassed . ' يوم';
                                    $badgeColor = 'dark';
                                }
                            @endphp
                            
                            <div class="bg-white bg-opacity-20 rounded-pill px-3 py-2 duration-counter">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span class="fw-medium">مدة أمر العمل:</span>
                                    <span class="badge bg-{{ $badgeColor }} fw-bold">{{ $daysText }}</span>
                                </div>
                                <small class="text-white-50 d-block mt-1">
                                    تم الإنشاء: {{ $createdDate->format('Y-m-d H:i') }}
                                </small>
                            </div>
                            
                            <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-right"></i> عودة
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- قسم الأعمال المدنية -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-hard-hat fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">الأعمال المدنية</h4>
                                    <p class="card-text text-muted mb-4">إدارة وتوثيق الأعمال المدنية للمشروع</p>
                                    <a href="{{ route('admin.work-orders.civil-works', $workOrder) }}" class="btn btn-primary w-100">
                                        <i class="fas fa-arrow-left ml-1"></i>
                                        الانتقال إلى الأعمال المدنية
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- قسم التركيبات -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-tools fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">التركيبات</h4>
                                    <p class="card-text text-muted mb-4">إدارة وتوثيق أعمال التركيبات للمشروع</p>
                                    <a href="{{ route('admin.work-orders.installations', $workOrder) }}" class="btn btn-primary w-100">
                                        <i class="fas fa-arrow-left ml-1"></i>
                                        الانتقال إلى التركيبات
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- قسم أعمال التمديد والكهرباء -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-bolt fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">أعمال الكهرباء</h4>
                                    <p class="card-text text-muted mb-4">إدارة وتوثيق أعمال الكهرباء للمشروع</p>
                                    <a href="{{ route('admin.work-orders.electrical-works', $workOrder) }}" class="btn btn-primary w-100">
                                        <i class="fas fa-arrow-left ml-1"></i>
                                        الانتقال إلى أعمال الكهرباء
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول بنود العمل -->
    <div class="row justify-content-center mt-4">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white py-3">
                    <h4 class="mb-0 fs-5">
                        <i class="fas fa-list-alt me-2"></i>
                        بنود العمل - أمر العمل رقم {{ $workOrder->order_number }}
                    </h4>
                </div>
                <div class="card-body p-0">
                    <!-- نموذج إضافة بند عمل جديد -->
                    <div class="p-4 border-bottom bg-light">
                        <h5 class="mb-3">
                            <i class="fas fa-plus-circle text-success me-2"></i>
                            إضافة بند عمل جديد
                        </h5>
                        <form id="addWorkItemForm" class="row g-3">
                            @csrf
                            <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                            
                            <div class="col-md-2">
                                <label class="form-label">رقم البند</label>
                                <input type="text" name="work_item_code" class="form-control" placeholder="مثال: 001" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">وصف البند</label>
                                <input type="text" name="work_item_description" class="form-control" placeholder="وصف البند" required>
                            </div>
                            
                            <div class="col-md-1">
                                <label class="form-label">الوحدة</label>
                                <input type="text" name="unit" class="form-control" placeholder="عدد" required>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label">سعر الوحدة</label>
                                <input type="number" step="0.01" name="unit_price" class="form-control" placeholder="0.00" required>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label">الكمية المخططة</label>
                                <input type="number" step="0.01" name="planned_quantity" class="form-control" placeholder="0.00" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">الكمية المنفذة</label>
                                <input type="number" step="0.01" name="actual_quantity" class="form-control" placeholder="0.00">
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus me-1"></i>
                                    إضافة البند
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    مسح
                                </button>
                            </div>
                        </form>
                    </div>

                    @if($workOrder->workOrderItems && $workOrder->workOrderItems->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="workItemsTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="width: 5%">#</th>
                                        <th class="text-center" style="width: 10%">رقم البند</th>
                                        <th style="width: 25%">وصف البند</th>
                                        <th class="text-center" style="width: 8%">الوحدة</th>
                                        <th class="text-center" style="width: 10%">سعر الوحدة</th>
                                        <th class="text-center" style="width: 10%">الكمية المخططة</th>
                                        <th class="text-center" style="width: 10%">السعر الإجمالي المخطط</th>
                                        <th class="text-center" style="width: 10%">الكمية المنفذة</th>
                                        <th class="text-center" style="width: 8%">فرق الكمية</th>
                                        <th class="text-center" style="width: 10%">السعر الإجمالي المنفذ</th>
                                        <th class="text-center" style="width: 6%">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalPlannedAmount = 0;
                                        $totalExecutedAmount = 0;
                                    @endphp
                                    @foreach($workOrder->workOrderItems as $index => $workOrderItem)
                                        @php
                                            $workItem = $workOrderItem->workItem ?? null;
                                            $plannedQuantity = $workOrderItem->planned_quantity ?? 0;
                                            $executedQuantity = $workOrderItem->actual_quantity ?? 0;
                                            $unitPrice = $workItem ? ($workItem->unit_price ?? 0) : 0;
                                            $plannedAmount = $plannedQuantity * $unitPrice;
                                            $executedAmount = $executedQuantity * $unitPrice;
                                            $quantityDifference = $plannedQuantity - $executedQuantity;
                                            
                                            $totalPlannedAmount += $plannedAmount;
                                            $totalExecutedAmount += $executedAmount;
                                        @endphp
                                        <tr id="row-{{ $workOrderItem->id }}">
                                            <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">{{ $workItem ? ($workItem->code ?? '-') : '-' }}</span>
                                            </td>
                                            <td class="text-start">{{ $workItem ? ($workItem->description ?? '-') : '-' }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ $workItem ? ($workItem->unit ?? 'عدد') : 'عدد' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-success fw-bold">{{ number_format($unitPrice, 2) }} ريال</span>
                                            </td>
                                            <td class="text-center">
                                                <input type="number" step="0.01" value="{{ $plannedQuantity }}" 
                                                       class="form-control form-control-sm text-center planned-quantity" 
                                                       data-id="{{ $workOrderItem->id }}" style="width: 80px;">
                                            </td>
                                            <td class="text-center">
                                                <span class="text-primary fw-bold planned-amount">{{ number_format($plannedAmount, 2) }} ريال</span>
                                            </td>
                                            <td class="text-center">
                                                <input type="number" step="0.01" value="{{ $executedQuantity }}" 
                                                       class="form-control form-control-sm text-center actual-quantity" 
                                                       data-id="{{ $workOrderItem->id }}" style="width: 80px;">
                                            </td>
                                            <td class="text-center quantity-diff">
                                                @if($quantityDifference > 0)
                                                    <span class="badge bg-danger">-{{ number_format($quantityDifference, 2) }}</span>
                                                @elseif($quantityDifference < 0)
                                                    <span class="badge bg-success">+{{ number_format(abs($quantityDifference), 2) }}</span>
                                                @else
                                                    <span class="badge bg-secondary">0.00</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="text-success fw-bold executed-amount">{{ number_format($executedAmount, 2) }} ريال</span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm delete-item" data-id="{{ $workOrderItem->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr class="fw-bold">
                                        <td colspan="6" class="text-end">الإجمالي:</td>
                                        <td class="text-center text-primary" id="totalPlanned">{{ number_format($totalPlannedAmount, 2) }} ريال</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center" id="totalDifference">
                                            @php
                                                $totalDifference = $totalPlannedAmount - $totalExecutedAmount;
                                            @endphp
                                            @if($totalDifference > 0)
                                                <span class="text-danger">-{{ number_format($totalDifference, 2) }} ريال</span>
                                            @elseif($totalDifference < 0)
                                                <span class="text-success">+{{ number_format(abs($totalDifference), 2) }} ريال</span>
                                            @else
                                                <span class="text-secondary">0.00 ريال</span>
                                            @endif
                                        </td>
                                        <td class="text-center text-success" id="totalExecuted">{{ number_format($totalExecutedAmount, 2) }} ريال</td>
                                        <td class="text-center">-</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد بنود عمل مضافة لهذا الأمر</h5>
                            <p class="text-muted">استخدم النموذج أعلاه لإضافة بنود العمل</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
}

.icon-wrapper {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(74, 144, 226, 0.1);
    border-radius: 50%;
}

.card-title {
    color: var(--text-color);
    font-weight: 600;
    font-size: 1.25rem;
}

.btn-primary {
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(74, 144, 226, 0.2);
}

/* تنسيق عداد الأيام */
.bg-opacity-20 {
    --bs-bg-opacity: 0.2;
}

.duration-counter {
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.duration-counter .badge {
    font-size: 0.85rem;
    padding: 0.4rem 0.8rem;
    border-radius: 50px;
}

.text-white-50 {
    --bs-text-opacity: 0.75;
    color: rgba(var(--bs-white-rgb), var(--bs-text-opacity)) !important;
}

@media (max-width: 768px) {
    .col-md-4 {
        margin-bottom: 1rem;
    }
    
    .duration-counter {
        font-size: 0.85rem;
    }
    
    .duration-counter .badge {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
    }
    
    .card-header .d-flex {
        flex-direction: column;
        gap: 1rem !important;
    }
    
    .duration-counter {
        order: -1;
        align-self: stretch;
        text-align: center;
    }
}

/* تنسيق جدول بنود العمل */
#workItemsTable {
    font-size: 0.9rem;
}

#workItemsTable th {
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    white-space: nowrap;
    padding: 12px 8px;
}

#workItemsTable td {
    vertical-align: middle;
    padding: 10px 8px;
}

#workItemsTable .badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}

#workItemsTable .text-start {
    text-align: right !important;
    padding-right: 15px;
}

/* تحسين ألوان الجدول */
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.075);
}

.table tfoot tr {
    border-top: 2px solid #dee2e6;
}

.table tfoot td {
    font-size: 0.95rem;
    padding: 15px 8px;
}

/* تحسين responsive للجدول */
@media (max-width: 1200px) {
    #workItemsTable {
        font-size: 0.8rem;
    }
    
    #workItemsTable th,
    #workItemsTable td {
        padding: 8px 6px;
    }
    
    #workItemsTable .badge {
        font-size: 0.7rem;
        padding: 0.25em 0.5em;
    }
}

@media (max-width: 768px) {
    #workItemsTable {
        font-size: 0.75rem;
    }
    
    #workItemsTable th,
    #workItemsTable td {
        padding: 6px 4px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // إضافة بند عمل جديد
    document.getElementById('addWorkItemForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("admin.work-orders.add-work-item") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'تم بنجاح!',
                    text: 'تم إضافة بند العمل بنجاح',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // إعادة تحميل الصفحة لعرض البيانات الجديدة
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ!',
                    text: data.message || 'حدث خطأ أثناء إضافة بند العمل'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'خطأ!',
                text: 'حدث خطأ في الاتصال'
            });
        });
    });

    // تحديث الكميات عند التغيير
    document.querySelectorAll('.planned-quantity, .actual-quantity').forEach(input => {
        input.addEventListener('change', function() {
            updateWorkItem(this);
        });
    });

    // حذف بند عمل
    document.querySelectorAll('.delete-item').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-id');
            
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم حذف بند العمل نهائياً',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteWorkItem(itemId);
                }
            });
        });
    });
});

function updateWorkItem(input) {
    const itemId = input.getAttribute('data-id');
    const row = document.getElementById('row-' + itemId);
    const plannedInput = row.querySelector('.planned-quantity');
    const actualInput = row.querySelector('.actual-quantity');
    
    const plannedQuantity = parseFloat(plannedInput.value) || 0;
    const actualQuantity = parseFloat(actualInput.value) || 0;
    
    // الحصول على سعر الوحدة من النص
    const unitPriceText = row.querySelector('.text-success.fw-bold').textContent;
    const unitPrice = parseFloat(unitPriceText.replace(/[^\d.]/g, '')) || 0;
    
    // حساب المبالغ
    const plannedAmount = plannedQuantity * unitPrice;
    const executedAmount = actualQuantity * unitPrice;
    const quantityDiff = plannedQuantity - actualQuantity;
    
    // تحديث العرض
    row.querySelector('.planned-amount').textContent = plannedAmount.toFixed(2) + ' ريال';
    row.querySelector('.executed-amount').textContent = executedAmount.toFixed(2) + ' ريال';
    
    // تحديث فرق الكمية
    const diffCell = row.querySelector('.quantity-diff');
    if (quantityDiff > 0) {
        diffCell.innerHTML = `<span class="badge bg-danger">-${quantityDiff.toFixed(2)}</span>`;
    } else if (quantityDiff < 0) {
        diffCell.innerHTML = `<span class="badge bg-success">+${Math.abs(quantityDiff).toFixed(2)}</span>`;
    } else {
        diffCell.innerHTML = `<span class="badge bg-secondary">0.00</span>`;
    }
    
    // تحديث الإجماليات
    updateTotals();
    
    // إرسال التحديث للخادم
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('planned_quantity', plannedQuantity);
    formData.append('actual_quantity', actualQuantity);
    
    fetch(`{{ url('admin/work-orders/update-work-item') }}/${itemId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.error('Failed to update work item');
        }
    })
    .catch(error => {
        console.error('Error updating work item:', error);
    });
}

function deleteWorkItem(itemId) {
    fetch(`{{ url('admin/work-orders/delete-work-item') }}/${itemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('row-' + itemId).remove();
            updateTotals();
            
            Swal.fire({
                icon: 'success',
                title: 'تم الحذف!',
                text: 'تم حذف بند العمل بنجاح',
                timer: 2000,
                showConfirmButton: false
            });
            
            // إذا لم تعد هناك بنود، أعد تحميل الصفحة لإظهار الرسالة الفارغة
            if (document.querySelectorAll('#workItemsTable tbody tr').length === 0) {
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'خطأ!',
                text: data.message || 'حدث خطأ أثناء حذف بند العمل'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'خطأ!',
            text: 'حدث خطأ في الاتصال'
        });
    });
}

function updateTotals() {
    let totalPlanned = 0;
    let totalExecuted = 0;
    
    document.querySelectorAll('#workItemsTable tbody tr').forEach(row => {
        const plannedText = row.querySelector('.planned-amount').textContent;
        const executedText = row.querySelector('.executed-amount').textContent;
        
        totalPlanned += parseFloat(plannedText.replace(/[^\d.]/g, '')) || 0;
        totalExecuted += parseFloat(executedText.replace(/[^\d.]/g, '')) || 0;
    });
    
    const totalDiff = totalPlanned - totalExecuted;
    
    // تحديث الإجماليات
    document.getElementById('totalPlanned').textContent = totalPlanned.toFixed(2) + ' ريال';
    document.getElementById('totalExecuted').textContent = totalExecuted.toFixed(2) + ' ريال';
    
    // تحديث فرق الإجمالي
    const totalDiffElement = document.getElementById('totalDifference');
    if (totalDiff > 0) {
        totalDiffElement.innerHTML = `<span class="text-danger">-${totalDiff.toFixed(2)} ريال</span>`;
    } else if (totalDiff < 0) {
        totalDiffElement.innerHTML = `<span class="text-success">+${Math.abs(totalDiff).toFixed(2)} ريال</span>`;
    } else {
        totalDiffElement.innerHTML = `<span class="text-secondary">0.00 ريال</span>`;
    }
}
</script>

@endsection 