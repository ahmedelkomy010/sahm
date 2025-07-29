@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 fs-4">
                                <i class="fas fa-chart-bar me-2"></i>
                                تقرير الإنتاجية العام
                                @if(isset($project))
                                    @if($project == 'riyadh')
                                    <span class="badge bg-light text-dark ms-2">
                                        <i class="fas fa-city me-1"></i>
                                        مشروع الرياض
                                    </span>
                                    @elseif($project == 'madinah')
                                    <span class="badge bg-light text-dark ms-2">
                                        <i class="fas fa-mosque me-1"></i>
                                        مشروع المدينة المنورة
                                    </span>
                                    @endif
                                @endif
                            </h3>
                            <small class="text-white-50">تقرير شامل لجميع أوامر العمل</small>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- فلتر التاريخ -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body p-3">
                                    <h5 class="card-title text-primary mb-3">
                                        <i class="fas fa-filter me-2"></i>
                                        فلترة حسب التاريخ
                                    </h5>
                                    <form id="dateFilterForm">
                                        @csrf
                                        <input type="hidden" name="project" value="{{ $project ?? '' }}">
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="start_date" class="form-label">تاريخ البداية</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                            <input type="date" class="form-control" id="start_date" name="start_date">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="end_date" class="form-label">تاريخ النهاية</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                            <input type="date" class="form-control" id="end_date" name="end_date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">فترات سريعة</label>
                                                <div class="d-flex gap-2 flex-wrap">
                                                    <div class="btn-group w-100">
                                                        <button type="button" class="btn btn-outline-primary" onclick="setTodayFilter()">
                                                            <i class="fas fa-calendar-day me-1"></i>
                                                            اليوم
                                                        </button>
                                                        <button type="button" class="btn btn-outline-primary" onclick="setWeekFilter()">
                                                            <i class="fas fa-calendar-week me-1"></i>
                                                            أسبوع
                                                        </button>
                                                        <button type="button" class="btn btn-outline-primary" onclick="setMonthFilter()">
                                                            <i class="fas fa-calendar-alt me-1"></i>
                                                            شهر
                                                        </button>
                                                        <button type="button" class="btn btn-outline-primary" onclick="setHalfYearFilter()">
                                                            <i class="fas fa-calendar-alt me-1"></i>
                                                            6 شهور
                                                        </button>
                                                        <button type="button" class="btn btn-outline-primary" onclick="setYearFilter()">
                                                            <i class="fas fa-calendar-alt me-1"></i>
                                                            سنة
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search me-1"></i>
                                                    عرض التقرير
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ملخص الإحصائيات -->
                    <div id="summarySection" class="row mb-4" style="display: none;">
                        <div class="col-12">
                            <div class="card border-0 bg-success bg-opacity-10">
                                <div class="card-body p-3">
                                    <h5 class="card-title text-success mb-3">
                                        <i class="fas fa-chart-pie me-2"></i>
                                        الملخص العام
                                        <span id="reportPeriod" class="badge bg-success ms-2"></span>
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <div class="card border-0 bg-primary bg-opacity-10">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-file-alt text-primary fs-2 mb-2"></i>
                                                    <h6 class="card-subtitle mb-1 text-muted">عدد أوامر العمل</h6>
                                                    <h5 class="card-title mb-1 text-primary" id="totalWorkOrders">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-0 bg-success bg-opacity-10">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-calculator text-success fs-2 mb-2"></i>
                                                    <h6 class="card-subtitle mb-1 text-muted">إجمالي الكميات المنفذة</h6>
                                                    <h5 class="card-title mb-1 text-success" id="totalExecutedQuantity">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-0 bg-info bg-opacity-10">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-list-ol text-info fs-2 mb-2"></i>
                                                    <h6 class="card-subtitle mb-1 text-muted">إجمالي البنود المسجلة</h6>
                                                    <h5 class="card-title mb-1 text-info" id="totalItemsCount">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-0 bg-danger bg-opacity-10">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-check-circle text-danger fs-2 mb-2"></i>
                                                    <h6 class="card-subtitle mb-1 text-muted">البنود المنفذة</h6>
                                                    <h5 class="card-title mb-1 text-danger" id="totalExecutedItemsCount">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 mt-2">
                                        <div class="col-md-6">
                                            <div class="card border-0 bg-warning bg-opacity-10">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-money-bill-wave text-warning fs-2 mb-2"></i>
                                                    <h6 class="card-subtitle mb-1 text-muted">إجمالي القيمة المنفذة</h6>
                                                    <h5 class="card-title mb-1 text-warning" id="totalAmount">0.00 ريال</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border-0 bg-secondary bg-opacity-10">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-percentage text-secondary fs-2 mb-2"></i>
                                                    <h6 class="card-subtitle mb-1 text-muted">نسبة الإنجاز</h6>
                                                    <h5 class="card-title mb-1 text-secondary" id="completionRate">0%</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تفاصيل أوامر العمل -->
                    <div id="workOrdersSection" style="display: none;">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-list-alt me-2"></i>
                                    تفاصيل أوامر العمل
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="workOrdersList">
                                    <!-- سيتم ملؤها بواسطة JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- رسالة عدم وجود بيانات -->
                    <div id="noDataMessage" class="text-center py-5" style="display: none;">
                        <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد بيانات للفترة المحددة</h5>
                        <p class="text-muted">يرجى تحديد فترة زمنية أخرى أو التأكد من وجود بيانات منفذة</p>
                    </div>

                    <!-- loading spinner -->
                    <div id="loadingSpinner" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">جاري التحميل...</span>
                        </div>
                        <p class="mt-2 text-muted">جاري تحميل البيانات...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<!-- تأكد من تحميل Bootstrap CSS -->
<style>
    .accordion-button:not(.collapsed) {
        background-color: #e7f1ff;
        color: #0c63e4;
    }
    
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0,0,0,.125);
    }
    
    .accordion-item {
        border: 1px solid rgba(0,0,0,.125);
    }
    
    .accordion-button::after {
        margin-right: auto;
        margin-left: 0;
    }
</style>
@endpush

@push('scripts')
<!-- تأكد من تحميل Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تعيين تاريخ اليوم كافتراضي
    setTodayFilter();
    
    // تهيئة الأكورديون
    initializeAccordion();
    
    document.getElementById('dateFilterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        loadGeneralProductivityReport();
    });
});

function formatDateToISO(date) {
    return date.toISOString().split('T')[0];
}

function setDateRange(startDate, endDate) {
    document.getElementById('start_date').value = formatDateToISO(startDate);
    document.getElementById('end_date').value = formatDateToISO(endDate);
    // تشغيل البحث مباشرة بعد تحديد التاريخ
    document.getElementById('dateFilterForm').dispatchEvent(new Event('submit'));
}

function setTodayFilter() {
    const today = new Date();
    today.setHours(0, 0, 0, 0); // بداية اليوم
    const endOfDay = new Date(today);
    endOfDay.setHours(23, 59, 59, 999); // نهاية اليوم
    setDateRange(today, endOfDay);
}

function setWeekFilter() {
    const today = new Date();
    today.setHours(23, 59, 59, 999); // نهاية اليوم الحالي
    
    const startOfWeek = new Date(today);
    startOfWeek.setDate(today.getDate() - 6); // آخر 7 أيام
    startOfWeek.setHours(0, 0, 0, 0); // بداية اليوم
    
    setDateRange(startOfWeek, today);
}

function setMonthFilter() {
    const today = new Date();
    today.setHours(23, 59, 59, 999); // نهاية اليوم الحالي
    
    const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    startOfMonth.setHours(0, 0, 0, 0); // بداية اليوم
    
    setDateRange(startOfMonth, today);
}

function setHalfYearFilter() {
    const today = new Date();
    today.setHours(23, 59, 59, 999); // نهاية اليوم الحالي
    
    const startDate = new Date(today);
    startDate.setMonth(today.getMonth() - 6);
    startDate.setHours(0, 0, 0, 0); // بداية اليوم
    
    setDateRange(startDate, today);
}

function setYearFilter() {
    const today = new Date();
    today.setHours(23, 59, 59, 999); // نهاية اليوم الحالي
    
    const startDate = new Date(today);
    startDate.setFullYear(today.getFullYear() - 1);
    startDate.setHours(0, 0, 0, 0); // بداية اليوم
    
    setDateRange(startDate, today);
}

function loadGeneralProductivityReport() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const project = document.querySelector('input[name="project"]').value;
    
    if (!startDate || !endDate) {
        alert('يرجى تحديد تاريخ البداية والنهاية');
        return;
    }
    
    // إخفاء جميع الأقسام وإظهار loading
    hideAllSections();
    document.getElementById('loadingSpinner').style.display = 'block';
    
    const url = `{{ route('general-productivity-data') }}?start_date=${startDate}&end_date=${endDate}&project=${project}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            console.log('Received data:', data); // Debug log
            document.getElementById('loadingSpinner').style.display = 'none';
            
            if (data.success && data.data.work_orders.length > 0) {
                console.log('Data is valid, displaying report'); // Debug log
                displayGeneralProductivityReport(data.data);
            } else {
                console.log('No data or error:', data); // Debug log
                showNoDataMessage();
            }
        })
        .catch(error => {
            console.error('Error loading general productivity report:', error);
            document.getElementById('loadingSpinner').style.display = 'none';
            showNoDataMessage();
        });
}

function hideAllSections() {
    document.getElementById('summarySection').style.display = 'none';
    document.getElementById('workOrdersSection').style.display = 'none';
    document.getElementById('noDataMessage').style.display = 'none';
    document.getElementById('loadingSpinner').style.display = 'none';
}

function initializeAccordion() {
    document.querySelectorAll('.accordion-button').forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-bs-target');
            const content = document.querySelector(target);
            
            // إغلاق كل الأقسام المفتوحة
            document.querySelectorAll('.accordion-collapse').forEach(item => {
                if (item !== content && item.classList.contains('show')) {
                    item.classList.remove('show');
                    item.previousElementSibling.querySelector('.accordion-button').classList.add('collapsed');
                }
            });
            
            // تبديل حالة القسم الحالي
            if (content) {
                content.classList.toggle('show');
                this.classList.toggle('collapsed');
            }
        });
    });
}

function displayGeneralProductivityReport(data) {
    document.getElementById('summarySection').style.display = 'block';
    document.getElementById('workOrdersSection').style.display = 'block';
    
    // تحديث فترة التقرير
    const startDate = new Date(data.period.start_date).toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' });
    const endDate = new Date(data.period.end_date).toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' });
    document.getElementById('reportPeriod').textContent = `${startDate} - ${endDate}`;
    
    // تحديث الملخص
    const summary = data.summary;
    document.getElementById('totalWorkOrders').textContent = summary.total_work_orders;
    document.getElementById('totalExecutedQuantity').textContent = formatNumber(summary.total_executed_quantity);
    document.getElementById('totalItemsCount').textContent = summary.total_items_count;
    
    // حساب إجمالي البنود المنفذة
    let totalExecutedItems = 0;
    data.work_orders.forEach(workOrder => {
        totalExecutedItems += workOrder.executed_items_count || 0;
    });
    document.getElementById('totalExecutedItemsCount').textContent = totalExecutedItems;
    
    // حساب نسبة الإنجاز
    const completionRate = summary.total_items_count > 0 ? 
        Math.round((totalExecutedItems / summary.total_items_count) * 100) : 0;
    document.getElementById('completionRate').textContent = completionRate + '%';
    
    document.getElementById('totalAmount').textContent = formatCurrency(summary.total_amount);
    
    // تحديث تفاصيل أوامر العمل
    const workOrdersList = document.getElementById('workOrdersList');
    workOrdersList.innerHTML = '';
    
    data.work_orders.forEach((workOrder, index) => {
        const workOrderCard = document.createElement('div');
        workOrderCard.className = 'card mb-4';
        
                                workOrderCard.innerHTML = `
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>أمر العمل رقم: ${workOrder.order_number}</strong>
                        <br>
                        <small class="text-muted">${workOrder.subscriber_name} - ${workOrder.district || 'غير محدد'}</small>
                    </div>
                    <div>
                        <span class="badge bg-primary me-2">${workOrder.items_count} بند</span>
                        <span class="badge bg-success me-2">${workOrder.executed_items_count || 0} منفذ</span>
                        <span class="badge bg-warning">${formatCurrency(workOrder.total_value)}</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>نوع العمل:</strong> ${workOrder.work_type}
                    </div>
                    <div class="col-md-3">
                        <strong>الحي:</strong> ${workOrder.district || 'غير محدد'}
                    </div>
                    <div class="col-md-3">
                        <strong>إجمالي الكمية المنفذة:</strong> ${formatNumber(workOrder.executed_quantity)}
                    </div>
                    <div class="col-md-3">
                        <strong>نسبة الإنجاز:</strong> 
                        <span class="badge bg-${workOrder.completion_rate >= 100 ? 'success' : (workOrder.completion_rate > 0 ? 'warning' : 'secondary')} fs-6">
                            ${workOrder.completion_rate}%
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-info small mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>ملخص سريع:</strong> 
                            ${workOrder.items_count} بند إجمالي، 
                            ${workOrder.executed_items_count || 0} بند منفذ، 
                            ${workOrder.items_count - (workOrder.executed_items_count || 0)} بند متبقي |
                            <strong>القيمة:</strong> ${formatCurrency(workOrder.total_value)} إجمالي
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>رقم البند والتاريخ</th>
                                <th>وصف البند</th>
                                <th>الوحدة</th>
                                <th>الكمية المخططة</th>
                                <th>الكمية المنفذة</th>
                                <th>سعر الوحدة</th>
                                <th>المبلغ المخطط</th>
                                <th>المبلغ المنفذ</th>
                                <th>نسبة الإنجاز</th>
                                <th>حالة التنفيذ</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${workOrder.work_items.map(item => `
                                <tr class="${item.executed_quantity > 0 ? '' : 'table-light'}">
                                    <td>
                                        <span class="badge bg-primary">${item.work_item_code}</span>
                                        ${item.work_date ? `<br><small class="text-muted mt-1 d-block"><i class="fas fa-calendar me-1"></i>${new Date(item.work_date).toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' })}</small>` : ''}
                                    </td>
                                    <td>
                                        ${item.work_item_description}
                                        ${item.notes ? `<br><small class="text-muted"><i class="fas fa-sticky-note me-1"></i>${item.notes}</small>` : ''}
                                    </td>
                                    <td class="text-center">${item.unit}</td>
                                    <td class="text-center">${formatNumber(item.planned_quantity)}</td>
                                    <td class="text-center">
                                        <span class="${item.executed_quantity > 0 ? 'text-success fw-bold' : 'text-muted'}">${formatNumber(item.executed_quantity)}</span>
                                    </td>
                                    <td class="text-center">${formatCurrency(item.unit_price)}</td>
                                    <td class="text-center">${formatCurrency(item.planned_amount)}</td>
                                    <td class="text-center">
                                        <span class="${item.executed_amount > 0 ? 'text-success fw-bold' : 'text-muted'}">${formatCurrency(item.executed_amount)}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-${item.completion_percentage > 0 ? (item.completion_percentage >= 100 ? 'success' : 'warning') : 'secondary'}">
                                            ${item.completion_percentage}%
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-${item.executed_quantity > 0 ? (item.completion_percentage >= 100 ? 'success' : 'warning') : 'secondary'}">
                                            ${item.executed_quantity > 0 ? (item.completion_percentage >= 100 ? 'مكتمل' : 'جاري التنفيذ') : 'لم يبدأ'}
                                        </span>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                        <tfoot class="table-light">
                            <tr class="fw-bold">
                                <td colspan="6" class="text-end">الإجمالي:</td>
                                <td class="text-center">${formatCurrency(workOrder.work_items.reduce((sum, item) => sum + item.planned_amount, 0))}</td>
                                <td class="text-center">${formatCurrency(workOrder.work_items.reduce((sum, item) => sum + item.executed_amount, 0))}</td>
                                <td class="text-center">
                                    <span class="badge bg-info">${workOrder.completion_rate}%</span>
                                </td>
                                <td class="text-center">-</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        `;
        
        workOrdersList.appendChild(workOrderCard);
    });
}

function showNoDataMessage() {
    document.getElementById('noDataMessage').style.display = 'block';
}

function formatNumber(number) {
    return new Intl.NumberFormat('ar-SA', { 
        minimumFractionDigits: 2, 
        maximumFractionDigits: 2 
    }).format(number || 0);
}

function formatCurrency(amount) {
    return formatNumber(amount) + ' ريال';
}
</script>
@endpush

@endsection 