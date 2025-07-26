@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 fs-4">
                                <i class="fas fa-chart-line me-2"></i>
                                تقرير الإنتاجية
                            </h3>
                            <small class="text-white-50">أمر العمل رقم: {{ $workOrder->work_order_number ?? $workOrder->order_number }}</small>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <a href="{{ route('admin.work-orders.execution', $workOrder) }}" class="btn btn-light">
                                <i class="fas fa-arrow-left"></i> العودة إلى التنفيذ
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- فلترة التاريخ -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body p-3">
                                    <h5 class="card-title text-primary mb-3">
                                        <i class="fas fa-filter me-2"></i>
                                        فلترة حسب التاريخ
                                    </h5>
                                    <form id="dateFilterForm">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label for="start_date" class="form-label">تاريخ البداية</label>
                                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="end_date" class="form-label">تاريخ النهاية</label>
                                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">&nbsp;</label>
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-primary flex-fill">
                                                        <i class="fas fa-search me-1"></i>
                                                        عرض التقرير
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary" onclick="setTodayFilter()">
                                                        اليوم
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary" onclick="setWeekFilter()">
                                                        هذا الأسبوع
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary" onclick="setMonthFilter()">
                                                        هذا الشهر
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ملخص الإنتاجية -->
                    <div id="productivitySummary" class="row mb-4" style="display: none;">
                        <div class="col-12">
                            <div class="card border-0 bg-primary bg-opacity-10">
                                <div class="card-body p-3">
                                    <h5 class="card-title text-primary mb-3">
                                        <i class="fas fa-chart-bar me-2"></i>
                                        ملخص الإنتاجية
                                        <span id="reportPeriod" class="badge bg-primary ms-2"></span>
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="card border-0 bg-primary bg-opacity-10">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-tasks text-primary fs-2 mb-2"></i>
                                                    <h6 class="card-subtitle mb-1 text-muted">إجمالي البنود المنفذة</h6>
                                                    <h5 class="card-title mb-1 text-primary" id="workItemsTotal">0.00 ريال</h5>
                                                    <small class="text-muted">
                                                        <span id="workItemsCount">0</span> بند
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card border-0 bg-success bg-opacity-10">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-calculator text-success fs-2 mb-2"></i>
                                                    <h6 class="card-subtitle mb-1 text-muted">إجمالي الكميات المنفذة</h6>
                                                    <h5 class="card-title mb-1 text-success" id="totalExecutedQuantity">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card border-0 bg-info bg-opacity-10">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-list-ol text-info fs-2 mb-2"></i>
                                                    <h6 class="card-subtitle mb-1 text-muted">عدد البنود</h6>
                                                    <h5 class="card-title mb-1 text-info" id="itemsCount">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تفاصيل بنود العمل -->
                    <div id="workItemsDetails" style="display: none;">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-list-alt me-2"></i>
                                    تفاصيل بنود العمل
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>التاريخ</th>
                                                <th>رقم البند</th>
                                                <th>الوصف</th>
                                                <th>الوحدة</th>
                                                <th>الكمية المخططة</th>
                                                <th>الكمية المنفذة</th>
                                                <th>سعر الوحدة</th>
                                                <th>الإجمالي</th>
                                            </tr>
                                        </thead>
                                        <tbody id="workItemsTableBody">
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr class="fw-bold">
                                                <td colspan="5" class="text-end">الإجمالي:</td>
                                                <td class="text-center" id="footerTotalQuantity">0</td>
                                                <td class="text-center">-</td>
                                                <td class="text-center" id="footerTotalAmount">0.00 ريال</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- رسالة عدم وجود بيانات -->
                    <div id="noDataMessage" class="text-center py-5" style="display: none;">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد بيانات للفترة المحددة</h5>
                        <p class="text-muted">يرجى تحديد فترة زمنية أخرى أو التأكد من وجود بيانات منفذة</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    setTodayFilter();
    
    document.getElementById('dateFilterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        loadProductivityReport();
    });
});

function setTodayFilter() {
    const today = new Date();
    document.getElementById('start_date').value = today.toISOString().split('T')[0];
    document.getElementById('end_date').value = today.toISOString().split('T')[0];
    loadProductivityReport();
}

function setWeekFilter() {
    const today = new Date();
    const startOfWeek = new Date(today);
    startOfWeek.setDate(today.getDate() - today.getDay());
    
    document.getElementById('start_date').value = startOfWeek.toISOString().split('T')[0];
    document.getElementById('end_date').value = today.toISOString().split('T')[0];
    loadProductivityReport();
}

function setMonthFilter() {
    const today = new Date();
    const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    
    document.getElementById('start_date').value = startOfMonth.toISOString().split('T')[0];
    document.getElementById('end_date').value = today.toISOString().split('T')[0];
    loadProductivityReport();
}

function loadProductivityReport() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    if (!startDate || !endDate) {
        alert('يرجى تحديد تاريخ البداية والنهاية');
        return;
    }
    
    const workOrderId = {{ $workOrder->id }};
    const url = `/admin/work-orders/${workOrderId}/productivity-report?start_date=${startDate}&end_date=${endDate}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayProductivityReport(data);
            } else {
                showNoDataMessage();
                console.error('Error:', data.message);
            }
        })
        .catch(error => {
            console.error('Error loading productivity report:', error);
            showNoDataMessage();
        });
}

function displayProductivityReport(data) {
    document.getElementById('noDataMessage').style.display = 'none';
    document.getElementById('productivitySummary').style.display = 'block';
    document.getElementById('workItemsDetails').style.display = 'block';
    
    // تحديث فترة التقرير
    const startDate = new Date(data.period.start_date).toLocaleDateString('en-GB', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });
    const endDate = new Date(data.period.end_date).toLocaleDateString('en-GB', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });
    document.getElementById('reportPeriod').textContent = `${startDate} - ${endDate}`;
    
    // تحديث الملخص
    document.getElementById('workItemsTotal').textContent = formatCurrency(data.work_items.total_amount);
    document.getElementById('totalExecutedQuantity').textContent = formatNumber(data.work_items.total_executed_quantity);
    document.getElementById('itemsCount').textContent = data.work_items.items_count;
    
    // تحديث جدول التفاصيل
    const tableBody = document.getElementById('workItemsTableBody');
    tableBody.innerHTML = '';
    
    data.work_items.details.forEach(item => {
        const row = document.createElement('tr');
        const itemDate = item.date ? new Date(item.date).toLocaleDateString('en-GB', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
        }) : '-';
        
        row.innerHTML = `
            <td>${itemDate}</td>
            <td><span class="badge bg-primary">${item.code}</span></td>
            <td>${item.description}</td>
            <td>${item.unit}</td>
            <td class="text-center">${formatNumber(item.planned_quantity)}</td>
            <td class="text-center">${formatNumber(item.executed_quantity)}</td>
            <td class="text-center">${formatCurrency(item.unit_price)}</td>
            <td class="text-center">${formatCurrency(item.total_amount)}</td>
        `;
        tableBody.appendChild(row);
    });
    
    // تحديث الإجماليات في تذييل الجدول
    document.getElementById('footerTotalQuantity').textContent = formatNumber(data.work_items.total_executed_quantity);
    document.getElementById('footerTotalAmount').textContent = formatCurrency(data.work_items.total_amount);
}

function showNoDataMessage() {
    document.getElementById('productivitySummary').style.display = 'none';
    document.getElementById('workItemsDetails').style.display = 'none';
    document.getElementById('noDataMessage').style.display = 'block';
}

function formatNumber(number) {
    return new Intl.NumberFormat('ar-SA', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(number);
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('ar-SA', { 
        minimumFractionDigits: 2, 
        maximumFractionDigits: 2 
    }).format(amount) + ' ريال';
}
</script>
@endpush

@endsection 