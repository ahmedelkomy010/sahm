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
                                        <!-- الأعمال المدنية -->
                                        <div class="col-md-3">
                                            <div class="card border-0 bg-primary bg-opacity-10">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-hard-hat text-primary fs-2 mb-2"></i>
                                                    <h6 class="card-subtitle mb-1 text-muted">الأعمال المدنية</h6>
                                                    <h5 class="card-title mb-1 text-primary" id="civilWorksTotal">0.00 ريال</h5>
                                                    <small class="text-muted">
                                                        <span id="civilWorksQuantity">0</span> متر - 
                                                        <span id="civilWorksCount">0</span> عنصر
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- التركيبات -->
                                        <div class="col-md-3">
                                            <div class="card border-0 bg-success bg-opacity-10">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-tools text-success fs-2 mb-2"></i>
                                                    <h6 class="card-subtitle mb-1 text-muted">التركيبات</h6>
                                                    <h5 class="card-title mb-1 text-success" id="installationsTotal">0.00 ريال</h5>
                                                    <small class="text-muted">
                                                        <span id="installationsQuantity">0</span> قطعة - 
                                                        <span id="installationsCount">0</span> عنصر
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- الأعمال الكهربائية -->
                                        <div class="col-md-3">
                                            <div class="card border-0 bg-warning bg-opacity-10">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-bolt text-warning fs-2 mb-2"></i>
                                                    <h6 class="card-subtitle mb-1 text-muted">أعمال الكهرباء</h6>
                                                    <h5 class="card-title mb-1 text-warning" id="electricalWorksTotal">0.00 ريال</h5>
                                                    <small class="text-muted">
                                                        <span id="electricalWorksLength">0</span> متر - 
                                                        <span id="electricalWorksCount">0</span> عنصر
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- الإجمالي -->
                                        <div class="col-md-3">
                                            <div class="card border-0 bg-info bg-opacity-10">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-calculator text-info fs-2 mb-2"></i>
                                                    <h6 class="card-subtitle mb-1 text-muted">الإجمالي العام</h6>
                                                    <h5 class="card-title mb-1 text-info" id="grandTotal">0.00 ريال</h5>
                                                    <small class="text-muted">جميع الأعمال</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تفاصيل الأعمال -->
                    <div id="productivityDetails" style="display: none;">
                        <!-- تفاصيل الأعمال المدنية -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-hard-hat me-2"></i>
                                    تفاصيل الأعمال المدنية
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped civil-works-table" id="civilWorksTable">
                                        <thead>
                                            <tr>
                                                <th style="width: 12%">التاريخ</th>
                                                <th style="width: 20%">نوع الحفرية</th>
                                                <th style="width: 25%">نوع الكابل</th>
                                                <th style="width: 13%">الطول</th>
                                                <th style="width: 15%">السعر</th>
                                                <th style="width: 15%">الإجمالي</th>
                                            </tr>
                                        </thead>
                                        <tbody id="civilWorksTableBody">
                                            <!-- سيتم ملؤها بواسطة JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- تفاصيل التركيبات -->
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-tools me-2"></i>
                                    تفاصيل التركيبات
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="installationsTable">
                                        <thead>
                                            <tr>
                                                <th>التاريخ</th>
                                                <th>نوع التركيب</th>
                                                <th>الكمية</th>
                                                <th>السعر</th>
                                                <th>الإجمالي</th>
                                            </tr>
                                        </thead>
                                        <tbody id="installationsTableBody">
                                            <!-- سيتم ملؤها بواسطة JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- تفاصيل الأعمال الكهربائية -->
                        <div class="card mb-4">
                            <div class="card-header bg-warning text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-bolt me-2"></i>
                                    تفاصيل الأعمال الكهربائية
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="electricalWorksTable">
                                        <thead>
                                            <tr>
                                                <th>التاريخ</th>
                                                <th>البند</th>
                                                <th>الطول</th>
                                                <th>السعر</th>
                                                <th>الإجمالي</th>
                                            </tr>
                                        </thead>
                                        <tbody id="electricalWorksTableBody">
                                            <!-- سيتم ملؤها بواسطة JavaScript -->
                                        </tbody>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // تعيين التاريخ الافتراضي (اليوم)
    setTodayFilter();
    
    // معالج إرسال النموذج
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
    // إخفاء رسالة عدم وجود بيانات
    document.getElementById('noDataMessage').style.display = 'none';
    
    // عرض الملخص
    document.getElementById('productivitySummary').style.display = 'block';
    document.getElementById('productivityDetails').style.display = 'block';
    
    // تحديث فترة التقرير
    const startDate = new Date(data.period.start_date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });
    const endDate = new Date(data.period.end_date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });
    document.getElementById('reportPeriod').textContent = `${startDate} - ${endDate}`;
    
    // تحديث الملخص
    document.getElementById('civilWorksTotal').textContent = formatCurrency(data.civil_works.total_amount);
    document.getElementById('civilWorksQuantity').textContent = formatNumber(data.civil_works.total_quantity);
    document.getElementById('civilWorksCount').textContent = data.civil_works.items_count;
    
    document.getElementById('installationsTotal').textContent = formatCurrency(data.installations.total_amount);
    document.getElementById('installationsQuantity').textContent = formatNumber(data.installations.total_quantity);
    document.getElementById('installationsCount').textContent = data.installations.items_count;
    
    document.getElementById('electricalWorksTotal').textContent = formatCurrency(data.electrical_works.total_amount);
    document.getElementById('electricalWorksLength').textContent = formatNumber(data.electrical_works.total_length);
    document.getElementById('electricalWorksCount').textContent = data.electrical_works.items_count;
    
    document.getElementById('grandTotal').textContent = formatCurrency(data.grand_total);
    
    // تحديث التفاصيل
    updateCivilWorksTable(data.civil_works.details);
    updateInstallationsTable(data.installations.details);
    updateElectricalWorksTable(data.electrical_works.details);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });
}

function updateCivilWorksTable(details) {
    const tbody = document.getElementById('civilWorksTableBody');
    tbody.innerHTML = '';
    
    if (details.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">لا توجد أعمال مدنية في هذه الفترة</td></tr>';
        return;
    }
    
    details.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${formatDate(item.date)}</td>
            <td class="text-center">${item.excavation_type || 'غير محدد'}</td>
            <td class="text-center">${item.cable_type || 'غير محدد'}</td>
            <td class="text-center">${formatNumber(item.length || 0)}</td>
            <td class="text-center">${formatCurrency(item.price || 0)}</td>
            <td class="text-center">${formatCurrency(item.total || 0)}</td>
        `;
        tbody.appendChild(row);
    });
}

function updateInstallationsTable(details) {
    const tbody = document.getElementById('installationsTableBody');
    tbody.innerHTML = '';
    
    if (details.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">لا توجد تركيبات في هذه الفترة</td></tr>';
        return;
    }
    
    details.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${formatDate(item.date)}</td>
            <td>${item.type}</td>
            <td class="text-center">${formatNumber(item.quantity)}</td>
            <td class="text-center">${formatCurrency(item.price)}</td>
            <td class="text-center">${formatCurrency(item.total)}</td>
        `;
        tbody.appendChild(row);
    });
}

function updateElectricalWorksTable(details) {
    const tbody = document.getElementById('electricalWorksTableBody');
    tbody.innerHTML = '';
    
    if (details.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">لا توجد أعمال كهربائية في هذه الفترة</td></tr>';
        return;
    }
    
    details.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${formatDate(item.date)}</td>
            <td class="text-center">${item.item_name || 'غير محدد'}</td>
            <td class="text-center">${formatNumber(item.length || 0)}</td>
            <td class="text-center">${formatCurrency(item.price || 0)}</td>
            <td class="text-center">${formatCurrency(item.total || 0)}</td>
        `;
        tbody.appendChild(row);
    });
}

function showNoDataMessage() {
    document.getElementById('productivitySummary').style.display = 'none';
    document.getElementById('productivityDetails').style.display = 'none';
    document.getElementById('noDataMessage').style.display = 'block';
}

function formatCurrency(amount) {
    return parseFloat(amount).toFixed(2) + ' ريال';
}

function formatNumber(number) {
    return parseFloat(number).toFixed(2);
}
</script>

<style>
.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.badge {
    font-size: 0.8em;
}

.fs-2 {
    font-size: 2rem !important;
}

/* تنسيق جدول الأعمال المدنية */
.civil-works-table {
    font-size: 0.95rem;
}

.civil-works-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    padding: 12px 8px;
    white-space: nowrap;
}

.civil-works-table td {
    vertical-align: middle;
    padding: 10px 8px;
}

.civil-works-table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

/* تنسيق الأرقام في الجدول */
.civil-works-table td:nth-child(4),
.civil-works-table td:nth-child(5),
.civil-works-table td:nth-child(6) {
    font-family: "Courier New", monospace;
    direction: ltr;
}

/* تنسيق التاريخ */
.civil-works-table td:first-child {
    font-family: "Courier New", monospace;
    direction: ltr;
    text-align: center;
}

/* تنسيق النصوص */
.civil-works-table td:nth-child(2),
.civil-works-table td:nth-child(3) {
    text-align: right;
}

/* تنسيق الخلايا الرقمية */
.civil-works-table .text-center {
    text-align: center !important;
}

/* تحسين مظهر الجدول على الشاشات الصغيرة */
@media (max-width: 768px) {
    .civil-works-table {
        font-size: 0.85rem;
    }
    
    .civil-works-table th,
    .civil-works-table td {
        padding: 8px 4px;
    }
}
</style>
@endsection 