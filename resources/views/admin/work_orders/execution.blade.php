@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 fs-4">صفحة التنفيذ</h3>
                            <small class="text-white-50">أمر العمل رقم: {{ $workOrder->work_order_number ?? $workOrder->order_number }}</small>
                        </div>
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
                                }
                            @endphp
                            <span class="badge bg-{{ $badgeColor }} fs-6">
                                <i class="fas fa-clock me-1"></i>
                                {{ $daysText }}
                            </span>
                            <a href="{{ route('admin.work-orders.productivity', $workOrder) }}" class="btn btn-warning">
                                <i class="fas fa-chart-line"></i> الإنتاجية
                            </a>
                            <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-success">
                                <i class="fas fa-arrow-left"></i> عودة الي تفاصيل أمر العمل  
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- قسم معلومات أمر العمل -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body p-3">
                                    <h5 class="card-title text-primary mb-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        معلومات أمر العمل
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-hashtag text-primary me-2"></i>
                                                <div>
                                                    <small class="text-muted d-block">رقم الطلب</small>
                                                    <strong class="text-dark">{{ $workOrder->work_order_number ?? $workOrder->order_number ?? 'غير محدد' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-cogs text-success me-2"></i>
                                                <div>
                                                    <small class="text-muted d-block">نوع العمل</small>
                                                    <strong class="text-dark">{{ $workOrder->work_type ?? $workOrder->type ?? 'غير محدد' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user text-info me-2"></i>
                                                <div>
                                                    <small class="text-muted d-block">اسم المشترك</small>
                                                    <strong class="text-dark">{{ $workOrder->subscriber_name ?? $workOrder->customer_name ?? 'غير محدد' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-tasks text-warning me-2"></i>
                                                <div>
                                                    <small class="text-muted d-block">حالة التنفيذ</small>
                                                    <strong class="text-dark">
                                                        @switch($workOrder->execution_status)
                                                            @case(1)
                                                                جاري العمل
                                                                @break
                                                            @case(2)
                                                                تم تسليم 155
                                                                @break
                                                            @case(3)
                                                                صدرت شهادة
                                                                @break
                                                            @case(4)
                                                                تم اعتماد الشهادة
                                                                @break
                                                            @case(5)
                                                                مؤكد
                                                                @break
                                                            @case(6)
                                                                دخل مستخلص
                                                                @break
                                                            @case(7)
                                                                منتهي
                                                                @break
                                                            @default
                                                                غير محدد
                                                        @endswitch
                                                    </strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <!-- جدول بنود العمل -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 fs-5">
                            <i class="fas fa-list-alt me-2"></i>
                            بنود العمل - أمر العمل رقم {{ $workOrder->order_number }}
                        </h4>
                        <div class="d-flex align-items-center gap-3">
                            <!-- إضافة حقل التاريخ -->
                            <div class="d-flex align-items-center">
                                <label for="workDate" class="text-white me-2">التاريخ:</label>
                                <input type="date" id="workDate" class="form-control form-control-sm" 
                                       value="{{ request('date', now()->format('Y-m-d')) }}"
                                       onchange="updateWorkDate(this.value)">
                            </div>
                            <button type="button" class="btn btn-light btn-sm" onclick="refreshWorkItems()">
                                <i class="fas fa-sync-alt me-1"></i>
                                تحديث
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <!-- معلومات حول بنود العمل -->
                    <div class="p-4 border-bottom bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <button type="button" class="btn btn-primary" onclick="openModal()">
                                    <i class="fas fa-plus-circle me-1"></i>
                                    إضافة بند عمل جديد
                                </button>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="d-flex align-items-center justify-content-end gap-3">
                                    <div class="text-center">
                                        <div class="fw-bold text-primary fs-4">{{ $workOrder->workOrderItems->count() }}</div>
                                        <small class="text-muted">إجمالي البنود</small>
                                    </div>
                                    <div class="text-center">
                                        <div class="fw-bold text-success fs-4">
                                            {{ $workOrder->workOrderItems->where('executed_quantity', '>', 0)->count() }}
                                        </div>
                                        <small class="text-muted">البنود المنفذة</small>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                            $plannedQuantity = $workOrderItem->quantity ?? 0;
                                            $executedQuantity = $workOrderItem->executed_quantity ?? 0;
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
                                                       data-id="{{ $workOrderItem->id }}" style="width: 80px; background-color: #f8f9fa;" readonly>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-primary fw-bold planned-amount">{{ number_format($plannedAmount, 2) }} ريال</span>
                                            </td>
                                            <td class="text-center">
                                                <input type="number" step="0.01" value="{{ $executedQuantity }}" 
                                                       class="form-control form-control-sm text-center actual-quantity" 
                                                       data-id="{{ $workOrderItem->id }}" style="width: 80px;"
                                                       onchange="updateExecutedQuantity(this)">
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
                            <h5 class="text-muted">لا توجد بنود عمل مضافة</h5>
                            <p class="text-muted">قم بإضافة بنود العمل لبدء التنفيذ</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- قسم صور التنفيذ -->
<div class="card shadow-lg border-0 mt-4">
    <div class="card-header bg-primary text-white py-3">
        <h5 class="mb-0">
            <i class="fas fa-images me-2"></i>
            صور التنفيذ
        </h5>
    </div>
    <div class="card-body">
        <!-- نموذج رفع الصور -->
        <form id="uploadExecutionImagesForm" class="mb-4">
            <div class="row align-items-end">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="images" class="form-label">اختر الصور</label>
                        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" required>
                        <small class="text-muted">يمكنك اختيار عدة صور في نفس الوقت. الحد الأقصى لحجم كل صورة 10 ميجابايت.</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>
                        رفع الصور
                    </button>
                </div>
            </div>
        </form>

        <!-- عرض الصور -->
        <div id="executionImagesContainer" class="row g-3">
            @foreach($executionImages as $image)
                <div class="col-md-4 col-lg-3" id="image-{{ $image->id }}">
                    <div class="card h-100">
                        <img src="{{ Storage::url($image->file_path) }}" class="card-img-top" alt="صورة تنفيذ" style="height: 200px; object-fit: cover;">
                        <div class="card-body p-2">
                            <p class="card-text small mb-1">{{ $image->original_filename }}</p>
                            <p class="card-text small text-muted mb-2">
                                <i class="fas fa-clock me-1"></i>
                                {{ $image->created_at->format('Y-m-d H:i') }}
                            </p>
                            <button class="btn btn-sm btn-danger w-100 delete-image" data-image-id="{{ $image->id }}">
                                <i class="fas fa-trash-alt me-1"></i>
                                حذف
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
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

<!-- Custom Modal CSS -->
<style>
.custom-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}

.custom-modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 0;
    border: none;
    border-radius: 8px;
    width: 80%;
    max-width: 600px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.custom-modal-header {
    background-color: #007bff;
    color: white;
    padding: 20px;
    border-radius: 8px 8px 0 0;
    position: relative;
}

.custom-modal-header h5 {
    margin: 0;
    font-size: 1.25rem;
}

.custom-modal-close {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: white;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    background: none;
    border: none;
}

.custom-modal-close:hover {
    opacity: 0.7;
}

.custom-modal-body {
    padding: 20px;
}

.custom-modal-footer {
    padding: 20px;
    border-top: 1px solid #dee2e6;
    text-align: right;
}

.custom-modal-footer button {
    margin-left: 10px;
}
</style>

<!-- Custom Modal HTML -->
<div id="addWorkItemModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5>إضافة بند عمل جديد</h5>
            <button class="custom-modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="custom-modal-body">
            <form id="addWorkItemForm">
                @csrf
                <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">
                <input type="hidden" name="work_date" id="modalWorkDate">
                
                <div class="mb-3">
                    <label for="work_item_search" class="form-label">البحث عن بند العمل</label>
                    <div class="alert alert-info small mb-2">
                        <i class="fas fa-info-circle me-1"></i>
                        عرض بنود العمل لمشروع: {{ $project === 'riyadh' ? 'الرياض' : 'المدينة المنورة' }}
                    </div>
                    <input type="text" id="work_item_search" class="form-control" placeholder="اكتب رقم البند أو الوصف للبحث...">
                </div>
                
                <div class="mb-3">
                    <label for="work_item_id" class="form-label">اختر بند العمل</label>
                    <select name="work_item_id" id="work_item_id" class="form-select" required>
                        <option value="">-- اختر بند العمل --</option>
                        @foreach($workItems as $workItem)
                            <option value="{{ $workItem->id }}" 
                                    data-unit-price="{{ $workItem->unit_price }}" 
                                    data-unit="{{ $workItem->unit }}"
                                    data-code="{{ $workItem->code }}"
                                    data-description="{{ $workItem->description }}">
                                {{ $workItem->code }} - {{ $workItem->description }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="quantity" class="form-label">الكمية المخططة</label>
                            <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" readonly style="background-color: #f8f9fa; cursor: not-allowed;">
                            <small class="text-muted">سيتم تحديد الكمية من خلال الجدول</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="unit" class="form-label">الوحدة</label>
                            <input type="text" class="form-control" id="unit" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="unit_price" class="form-label">سعر الوحدة</label>
                            <input type="number" step="0.01" class="form-control" id="unit_price" readonly>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">ملاحظات</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                </div>
            </form>
        </div>
        <div class="custom-modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal()">إلغاء</button>
            <button type="submit" form="addWorkItemForm" class="btn btn-primary">إضافة البند</button>
        </div>
    </div>
</div>

@endsection 

@push('scripts')
<!-- تأكد من تحميل Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// وظائف Modal
function openModal() {
    document.getElementById('addWorkItemModal').style.display = 'block';
    // Set the current work date in the modal
    document.getElementById('modalWorkDate').value = currentWorkDate;
}

function closeModal() {
    document.getElementById('addWorkItemModal').style.display = 'none';
    // Reset the form
    document.getElementById('addWorkItemForm').reset();
    document.getElementById('unit').value = '';
    document.getElementById('unit_price').value = '';
    document.getElementById('quantity').value = '';
    
    // Reset search and show all options
    const workItemSearch = document.getElementById('work_item_search');
    const workItemSelect = document.getElementById('work_item_id');
    
    if (workItemSearch) {
        workItemSearch.value = '';
    }
    
    if (workItemSelect) {
        // Show all options
        for (let i = 1; i < workItemSelect.options.length; i++) {
            workItemSelect.options[i].style.display = 'block';
        }
        workItemSelect.value = '';
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('addWorkItemModal');
    if (event.target === modal) {
        closeModal();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Add event listener for opening modal
    const addWorkItemButton = document.querySelector('[data-bs-target="#addWorkItemModal"]');
    if (addWorkItemButton) {
        addWorkItemButton.removeAttribute('data-bs-toggle');
        addWorkItemButton.removeAttribute('data-bs-target');
        addWorkItemButton.addEventListener('click', function(e) {
            e.preventDefault();
            openModal();
        });
    }

    initializeEventListeners();
});

function initializeEventListeners() {
    const workItemIdSelect = document.getElementById('work_item_id');
    const addWorkItemForm = document.getElementById('addWorkItemForm');
    const workItemSearch = document.getElementById('work_item_search');
    const plannedQuantityInputs = document.querySelectorAll('.planned-quantity');
    const actualQuantityInputs = document.querySelectorAll('.actual-quantity');
    const deleteButtons = document.querySelectorAll('.delete-item');

    // وظيفة البحث في بنود العمل
    if (workItemSearch) {
        workItemSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const options = workItemIdSelect.options;
            
            for (let i = 1; i < options.length; i++) { // بدء من 1 لتجنب الخيار الافتراضي
                const option = options[i];
                const code = option.dataset.code ? option.dataset.code.toLowerCase() : '';
                const description = option.dataset.description ? option.dataset.description.toLowerCase() : '';
                
                if (code.includes(searchTerm) || description.includes(searchTerm)) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            }
            
            // إذا كان هناك نتيجة واحدة فقط، اختارها تلقائياً
            const visibleOptions = Array.from(options).filter(option => 
                option.style.display !== 'none' && option.value !== ''
            );
            
            if (visibleOptions.length === 1) {
                workItemIdSelect.value = visibleOptions[0].value;
                workItemIdSelect.dispatchEvent(new Event('change'));
            }
        });
    }

    if (workItemIdSelect) {
        workItemIdSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('unit_price').value = selectedOption.dataset.unitPrice || '';
            document.getElementById('unit').value = selectedOption.dataset.unit || '';
            
            // تعيين كمية افتراضية (1) عند اختيار بند
            if (selectedOption.value) {
                document.getElementById('quantity').value = '1.00';
            } else {
                document.getElementById('quantity').value = '';
            }
        });
    }

    if (addWorkItemForm) {
        addWorkItemForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // التحقق من أن البند محدد
            if (!document.getElementById('work_item_id').value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'تنبيه!',
                    text: 'يرجى اختيار بند العمل أولاً'
                });
                return;
            }
            
            const formData = new FormData(this);
            
            fetch('{{ route("admin.work-orders.add-work-item") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModal();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'تم بنجاح!',
                        text: 'تم إضافة بند العمل بنجاح',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
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
    }

    plannedQuantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            updateWorkItem(this);
        });
    });

    actualQuantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            updateWorkItem(this);
        });
    });

    deleteButtons.forEach(button => {
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
}

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
    formData.append('executed_quantity', actualQuantity);
    
    fetch(`/admin/work-orders/update-work-item/${itemId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // إظهار رسالة نجاح
            toastr.success('تم تحديث الكمية المنفذة بنجاح');
        } else {
            console.error('Failed to update work item');
            toastr.error('حدث خطأ أثناء تحديث الكمية المنفذة');
        }
    })
    .catch(error => {
        console.error('Error updating work item:', error);
        toastr.error('حدث خطأ أثناء تحديث الكمية المنفذة');
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
    let totalPlannedAmount = 0;
    let totalExecutedAmount = 0;
    
    document.querySelectorAll('#workItemsTable tbody tr').forEach(row => {
        const plannedAmount = parseFloat(row.querySelector('.planned-amount').textContent.replace(/[^\d.]/g, '')) || 0;
        const executedAmount = parseFloat(row.querySelector('.executed-amount').textContent.replace(/[^\d.]/g, '')) || 0;
        
        totalPlannedAmount += plannedAmount;
        totalExecutedAmount += executedAmount;
    });
    
    // تحديث الإجماليات في الجدول
    document.getElementById('totalPlanned').textContent = totalPlannedAmount.toFixed(2) + ' ريال';
    document.getElementById('totalExecuted').textContent = totalExecutedAmount.toFixed(2) + ' ريال';
    
    // تحديث فرق الإجمالي
    const totalDifference = totalPlannedAmount - totalExecutedAmount;
    const totalDiffElement = document.getElementById('totalDifference');
    
    if (totalDifference > 0) {
        totalDiffElement.innerHTML = `<span class="text-danger">-${totalDifference.toFixed(2)} ريال</span>`;
    } else if (totalDifference < 0) {
        totalDiffElement.innerHTML = `<span class="text-success">+${Math.abs(totalDifference).toFixed(2)} ريال</span>`;
    } else {
        totalDiffElement.innerHTML = `<span class="text-secondary">0.00 ريال</span>`;
    }
}
</script>

<script>
let currentWorkDate = '{{ request('date', now()->format('Y-m-d')) }}';

function updateWorkDate(date) {
    currentWorkDate = date;
    refreshWorkItems();
}

function refreshWorkItems() {
    // Reload the page with the new date
    window.location.href = `{{ route('admin.work-orders.execution', $workOrder->id) }}?date=${currentWorkDate}`;
}

function updateExecutedQuantity(input) {
    const workOrderItemId = input.dataset.id;
    const executedQuantity = input.value;
    
    // Send AJAX request to update the executed quantity
    fetch(`{{ url('admin/work-orders/update-work-item') }}/${workOrderItemId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            executed_quantity: executedQuantity,
            work_date: currentWorkDate
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the UI
            const row = input.closest('tr');
            const plannedQuantity = parseFloat(row.querySelector('.planned-quantity').value);
            const unitPrice = parseFloat(row.querySelector('.text-success.fw-bold').textContent.replace(/[^\d.-]/g, ''));
            const executedAmount = executedQuantity * unitPrice;
            const quantityDiff = plannedQuantity - executedQuantity;
            
            // Update executed amount
            row.querySelector('.executed-amount').textContent = `${executedAmount.toFixed(2)} ريال`;
            
            // Update quantity difference
            const diffElement = row.querySelector('.quantity-diff');
            if (quantityDiff > 0) {
                diffElement.innerHTML = `<span class="badge bg-danger">-${quantityDiff.toFixed(2)}</span>`;
            } else if (quantityDiff < 0) {
                diffElement.innerHTML = `<span class="badge bg-success">+${Math.abs(quantityDiff).toFixed(2)}</span>`;
            } else {
                diffElement.innerHTML = `<span class="badge bg-secondary">0.00</span>`;
            }
            
            // Show success message
            toastr.success('تم تحديث الكمية المنفذة بنجاح');
        } else {
            // Show error message
            toastr.error(data.message || 'حدث خطأ أثناء تحديث الكمية المنفذة');
            // Reset the input to its previous value
            input.value = input.defaultValue;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('حدث خطأ أثناء تحديث الكمية المنفذة');
        // Reset the input to its previous value
        input.value = input.defaultValue;
    });
}
</script>

<script>
// تحديث الإجمالي اليومي
function updateDailyTotals() {
    console.log('Updating daily totals...');
    const workOrderId = {{ $workOrder->id }};
    fetch(`/admin/work-orders/${workOrderId}/daily-totals`)
        .then(response => response.json())
        .then(data => {
            console.log('Received data:', data);
            if (data.success) {
                // تحديث الأعمال المدنية
                const civilWorksElement = document.querySelector('.card-title.mb-0.text-primary');
                if (civilWorksElement) {
                    civilWorksElement.textContent = number_format(data.civil_works_total, 2) + ' ريال';
                    console.log('Updated civil works:', civilWorksElement.textContent);
                } else {
                    console.warn('Civil works element not found');
                }
                
                // تحديث التركيبات
                const installationsElement = document.querySelector('.card-title.mb-0.text-success');
                if (installationsElement) {
                    installationsElement.textContent = number_format(data.installations_total, 2) + ' ريال';
                    console.log('Updated installations:', installationsElement.textContent);
                } else {
                    console.warn('Installations element not found');
                }
                
                // تحديث الأعمال الكهربائية
                const electricalWorksElement = document.querySelector('.card-title.mb-0.text-warning');
                if (electricalWorksElement) {
                    electricalWorksElement.textContent = number_format(data.electrical_works_total, 2) + ' ريال';
                    console.log('Updated electrical works:', electricalWorksElement.textContent);
                } else {
                    console.warn('Electrical works element not found');
                }
                
                // تحديث الإجمالي الكلي
                const grandTotalElement = document.querySelector('.card-title.mb-0.text-info');
                if (grandTotalElement) {
                    grandTotalElement.textContent = number_format(data.grand_total, 2) + ' ريال';
                    console.log('Updated grand total:', grandTotalElement.textContent);
                } else {
                    console.warn('Grand total element not found');
                }
            } else {
                console.error('Failed to update daily totals:', data.message);
            }
        })
        .catch(error => {
            console.error('Error fetching daily totals:', error);
        });
}

// تنسيق الأرقام
function number_format(number, decimals) {
    return parseFloat(number).toFixed(decimals);
}

// بيانات بنود العمل المُفلترة حسب المشروع
const workItems = @json($workItems);

// البحث في بنود العمل
document.getElementById('work_item_search').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const select = document.getElementById('work_item_id');
    const options = select.getElementsByTagName('option');
    
    // إخفاء جميع الخيارات أولاً
    for (let i = 1; i < options.length; i++) { // تجاهل الخيار الأول "-- اختر بند العمل --"
        const option = options[i];
        const text = option.textContent.toLowerCase();
        
        if (searchTerm === '' || text.includes(searchTerm)) {
            option.style.display = '';
        } else {
            option.style.display = 'none';
        }
    }
    
    // إعادة تعيين الاختيار إذا لم يعد مرئياً
    if (select.selectedIndex > 0 && options[select.selectedIndex].style.display === 'none') {
        select.selectedIndex = 0;
    }
});

// تشغيل التحديث عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // التحديث الأولي
    updateDailyTotals();
    
    // تحديث كل دقيقة
    setInterval(updateDailyTotals, 60000);
});

// JavaScript لرفع صور التنفيذ
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('uploadExecutionImagesForm');
    const imagesContainer = document.getElementById('executionImagesContainer');

    if (form) {
        // رفع الصور
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            const fileInput = document.getElementById('images');
            
            if (!fileInput.files.length) {
                toastr.error('يرجى اختيار صور للرفع');
                return;
            }
            
            for (let file of fileInput.files) {
                formData.append('images[]', file);
            }

            try {
                const response = await fetch("/admin/work-orders/{{ $workOrder->id }}/execution/images", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    // إضافة الصور الجديدة للعرض
                    result.images.forEach(image => {
                        const imageHtml = `
                            <div class="col-md-4 col-lg-3" id="image-${image.id}">
                                <div class="card h-100">
                                    <img src="${image.path}" class="card-img-top" alt="صورة تنفيذ" style="height: 200px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <p class="card-text small mb-1">${image.name}</p>
                                        <p class="card-text small text-muted mb-2">
                                            <i class="fas fa-clock me-1"></i>
                                            ${image.created_at}
                                        </p>
                                        <button class="btn btn-sm btn-danger w-100 delete-image" data-image-id="${image.id}">
                                            <i class="fas fa-trash-alt me-1"></i>
                                            حذف
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                        if (imagesContainer) {
                            imagesContainer.insertAdjacentHTML('afterbegin', imageHtml);
                        }
                    });

                    // إعادة تعيين النموذج
                    form.reset();
                    
                    // إظهار رسالة نجاح
                    if (typeof toastr !== 'undefined') {
                        toastr.success(result.message);
                    } else {
                        alert(result.message);
                    }
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(result.message);
                    } else {
                        alert(result.message);
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                if (typeof toastr !== 'undefined') {
                    toastr.error('حدث خطأ أثناء رفع الصور');
                } else {
                    alert('حدث خطأ أثناء رفع الصور');
                }
            }
        });
    }

    // حذف الصور
    document.addEventListener('click', async function(e) {
        if (e.target.matches('.delete-image') || e.target.closest('.delete-image')) {
            const button = e.target.matches('.delete-image') ? e.target : e.target.closest('.delete-image');
            const imageId = button.dataset.imageId;
            
            if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
                try {
                    const response = await fetch(`/admin/work-orders/{{ $workOrder->id }}/execution/images/${imageId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        // حذف عنصر الصورة من العرض
                        const imageElement = document.getElementById(`image-${imageId}`);
                        if (imageElement) {
                            imageElement.remove();
                        }
                        if (typeof toastr !== 'undefined') {
                            toastr.success(result.message);
                        } else {
                            alert(result.message);
                        }
                    } else {
                        if (typeof toastr !== 'undefined') {
                            toastr.error(result.message);
                        } else {
                            alert(result.message);
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    if (typeof toastr !== 'undefined') {
                        toastr.error('حدث خطأ أثناء حذف الصورة');
                    } else {
                        alert('حدث خطأ أثناء حذف الصورة');
                    }
                }
            }
        }
    });
});
</script>
@endpush 