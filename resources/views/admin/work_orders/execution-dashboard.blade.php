@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-gradient-warning text-white py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 fs-3">
                                <i class="fas fa-check-circle me-3"></i>
                                لوحة متابعة أوامر المنفذة
                            </h2>
                            <p class="mb-0 text-white-75">
                                @if(isset($project))
                                    @if($project == 'riyadh')
                                        <i class="fas fa-city me-2"></i>مشروع الرياض
                                    @elseif($project == 'madinah')
                                        <i class="fas fa-mosque me-2"></i>مشروع المدينة المنورة
                                    @endif
                                @endif
                                - عرض أوامر العمل  والمكتملة التنفيذ فقط
                            </p>
                        </div>
                        <div class="text-end">
                            @if(isset($project) && $project == 'madinah')
                                <a href="{{ route('admin.work-orders.productivity.madinah') }}" class="btn btn-light">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    العودة للوحة تحكم المدينة المنورة
                                </a>
                            @else
                                <a href="{{ route('admin.work-orders.productivity.riyadh') }}" class="btn btn-light">
                                <i class="fas fa-arrow-right me-2"></i>
                                    العودة للوحة تحكم الرياض
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 bg-warning bg-opacity-10">
                        <div class="card-body text-center">
                            <i class="fas fa-clipboard-check text-warning fs-2 mb-2"></i>
                            <h4 class="mb-1 text-warning" id="totalExecutedOrders">0</h4>
                            <small class="text-muted">إجمالي أوامر العمل المنتهية</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 bg-success bg-opacity-10">
                        <div class="card-body text-center">
                            <i class="fas fa-money-check-alt text-success fs-2 mb-2"></i>
                            <h4 class="mb-1 text-success" id="totalExecutedValue">0</h4>
                            <small class="text-muted">السعر الإجمالي المنفذ</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 bg-info bg-opacity-10">
                        <div class="card-body text-center">
                            <i class="fas fa-percentage text-info fs-2 mb-2"></i>
                            <h4 class="mb-1 text-info" id="executionPercentage">0%</h4>
                            <small class="text-muted">نسبة الإنجاز</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- حالات التنفيذ -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0" style="background-color: rgb(112, 68, 2, 0.1);">
                        <div class="card-body text-center">
                            <i class="fas fa-clipboard-check fs-2 mb-2" style="color: rgb(112, 68, 2);"></i>
                            <h4 class="mb-1" style="color: rgb(112, 68, 2);" id="status2Count">0</h4>
                            <small class="text-muted">تم التنفيذ بالموقع وجاري تسليم 155</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0" style="background-color: rgb(165, 0, 52, 0.1);">
                        <div class="card-body text-center">
                            <i class="fas fa-file-certificate fs-2 mb-2" style="color: rgb(165, 0, 52);"></i>
                            <h4 class="mb-1" style="color: rgb(165, 0, 52);" id="status3Count">0</h4>
                            <small class="text-muted">تم تسليم 155 جاري اصدار شهادة الانجاز</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0" style="background-color: rgb(0, 149, 54, 0.1);">
                        <div class="card-body text-center">
                            <i class="fas fa-file-invoice-dollar fs-2 mb-2" style="color: rgb(0, 149, 54);"></i>
                            <h4 class="mb-1" style="color: rgb(0, 149, 54);" id="status4Count">0</h4>
                            <small class="text-muted">اعداد المستخلص الدفعة الجزئية الاولي</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0" style="background-color: rgb(41, 128, 185, 0.1);">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fs-2 mb-2" style="color: rgb(41, 128, 185);"></i>
                            <h4 class="mb-1" style="color: rgb(41, 128, 185);" id="status5Count">0</h4>
                            <small class="text-muted">تم صرف المستخلص الدفعة الجزئية الاولي</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-0" style="background-color: rgb(155, 89, 182, 0.1);">
                        <div class="card-body text-center">
                            <i class="fas fa-file-invoice fs-2 mb-2" style="color: rgb(155, 89, 182);"></i>
                            <h4 class="mb-1" style="color: rgb(155, 89, 182);" id="status6Count">0</h4>
                            <small class="text-muted">اعداد المستخلص الدفعة الجزئية الثانية وجاري الصرف</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0" style="background-color: rgb(52, 152, 219, 0.1);">
                        <div class="card-body text-center">
                            <i class="fas fa-file-contract fs-2 mb-2" style="color: rgb(52, 152, 219);"></i>
                            <h4 class="mb-1" style="color: rgb(52, 152, 219);" id="status10Count">0</h4>
                            <small class="text-muted">تم اعداد المستخلص الكلي وجاري الصرف</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-filter me-2 text-warning"></i>
                        فلاتر البحث والتصفية
                    </h5>
                </div>
                <div class="card-body">
                    <form id="executionFilterForm">
                        @csrf
                        <input type="hidden" name="project" value="{{ $project ?? '' }}">
                        
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="order_number" class="form-label">رقم أمر العمل</label>
                                <input type="text" class="form-control" id="order_number" name="order_number" placeholder="ابحث برقم الأمر">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="office" class="form-label">المكتب</label>
                                <select class="form-select" id="office" name="office">
                                    <option value="">جميع المكاتب</option>
                                    @if(isset($project) && $project == 'riyadh')
                                        <option value="خريص">خريص</option>
                                        <option value="الشرق">الشرق</option>
                                        <option value="الشمال">الشمال</option>
                                        <option value="الجنوب">الجنوب</option>
                                        <option value="الدرعية">الدرعية</option>
                                    @elseif(isset($project) && $project == 'madinah')
                                        <option value="المدينة المنورة">المدينة المنورة</option>
                                        <option value="ينبع">ينبع</option>
                                        <option value="خيبر">خيبر</option>
                                        <option value="مهد الذهب">مهد الذهب</option>
                                        <option value="بدر">بدر</option>
                                        <option value="الحناكية">الحناكية</option>
                                        <option value="العلا">العلا</option>
                                    @endif
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">تاريخ البداية</label>
                                <input type="date" class="form-control" id="start_date" name="start_date">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">تاريخ النهاية</label>
                                <input type="date" class="form-control" id="end_date" name="end_date">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">فلاتر سريعة</label>
                                <div class="btn-group w-100" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="setDateRange('week')">أسبوع</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="setDateRange('month')">شهر</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="setDateRange('3months')">3 أشهر</button>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <label class="form-label">إجراءات</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-search me-1"></i>
                                        بحث وتصفية
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="clearFilters()">
                                        <i class="fas fa-times me-1"></i>
                                        مسح الفلاتر
                                    </button>
                                    <button type="button" class="btn btn-success" onclick="exportResults()">
                                        <i class="fas fa-download me-1"></i>
                                        تصدير Excel
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="refreshData()">
                                        <i class="fas fa-sync me-1"></i>
                                        تحديث
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Section -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2 text-warning"></i>
                            نتائج أوامر العمل المنتهية والمكتملة
                        </h5>
                        <div class="d-flex align-items-center gap-3">
                            <small class="text-muted">
                                عرض <span id="resultsCount">0</span> من إجمالي <span id="totalCount">0</span> أمر عمل
                            </small>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="changeView('table')">
                                    <i class="fas fa-table"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="changeView('cards')">
                                    <i class="fas fa-th-large"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-warning" role="status">
                            <span class="visually-hidden">جاري التحميل...</span>
                        </div>
                        <p class="mt-2 text-muted">جاري تحميل البيانات...</p>
                    </div>

                    <!-- No Data Message -->
                    <div id="noDataMessage" class="text-center py-5" style="display: none;">
                        <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد أوامر عمل منتهية</h5>
                        <p class="text-muted">جرب تغيير معايير البحث أو التأكد من وجود أوامر عمل مكتملة التنفيذ</p>
                    </div>

                    <!-- Results Table -->
                    <div id="resultsTable" class="table-responsive">
                        <div id="workOrdersList">
                            <!-- Work orders with their items will be populated here -->
                        </div>
                    </div>

                    <!-- Results Cards View -->
                    <div id="resultsCards" class="p-3" style="display: none;">
                        <div class="row g-3" id="resultsCardsContainer">
                            <!-- Card results will be populated here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <select class="form-select form-select-sm" id="perPage" onchange="changePerPage(this.value)" style="width: auto;">
                        <option value="10">10 نتائج</option>
                        <option value="25" selected>25 نتيجة</option>
                        <option value="50">50 نتيجة</option>
                        <option value="100">100 نتيجة</option>
                    </select>
                </div>
                <nav id="paginationNav">
                    <!-- Pagination will be populated here -->
                </nav>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-gradient-warning {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    }
    
    .dashboard-card {
        transition: all 0.3s ease;
        border-radius: 10px;
    }
    
    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .table th {
        font-weight: 600;
        font-size: 0.9rem;
        border-bottom: 2px solid #dee2e6;
    }
    
    .table td {
        font-size: 0.9rem;
        vertical-align: middle;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.4em 0.8em;
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.15);
    }
    
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    .work-order-card {
        border: 1px solid #e3e6f0;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .work-order-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the page
    loadExecutedOrders();
    
    // Setup form submission
    document.getElementById('executionFilterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        loadExecutedOrders();
    });
    
    // Auto-refresh every 2 minutes
    setInterval(loadExecutedOrders, 120000);
});

let currentPage = 1;
let currentView = 'table';

// Load executed work orders
function loadExecutedOrders(page = 1) {
    currentPage = page;
    showLoading();
    
    const formData = new FormData(document.getElementById('executionFilterForm'));
    const params = new URLSearchParams();
    
    // Add form data to params
    for (let [key, value] of formData.entries()) {
        if (value) {
            params.append(key, value);
        }
    }
    
    // Add pagination
    params.append('page', page);
    params.append('per_page', document.getElementById('perPage').value);
    
    // Make API call
    console.log('Making API call to:', `/api/work-orders/execution?${params.toString()}`);
    fetch(`/api/work-orders/execution?${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log('API Response:', data);
            hideLoading();
            if (data.success && data.data && data.data.length > 0) {
                displayResults(data.data);
                updateSummaryCards(data.summary);
                updatePagination(data.pagination);
            } else {
                console.log('No data or unsuccessful response');
                showNoData();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            hideLoading();
            showNoData();
        });
}

// Display results
function displayResults(data) {
    if (data.length === 0) {
        showNoData();
        return;
    }
    
    hideNoData();
    
    if (currentView === 'table') {
        displayTableResults(data);
    } else {
        displayCardResults(data);
    }
    
    document.getElementById('resultsCount').textContent = data.length;
}

// Display table results
function displayTableResults(data) {
    document.getElementById('resultsTable').style.display = 'block';
    document.getElementById('resultsCards').style.display = 'none';
    
    const container = document.getElementById('workOrdersList');
    container.innerHTML = '';
    
    // إنشاء جدول لأوامر العمل
    const table = document.createElement('table');
    table.className = 'table table-hover mb-0';
    
    // إنشاء header الجدول
    table.innerHTML = `
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>رقم أمر العمل</th>
                <th>اسم المشترك</th>
                <th>المكتب</th>
                <th>حالة التنفيذ</th>
                <th>القيمة المبدئية</th>
                <th>القيمة المنفذة</th>
                <th>الإجراءات</th>
                            </tr>
                        </thead>
        <tbody id="tableBody"></tbody>
    `;
    
    container.appendChild(table);
    const tbody = document.getElementById('tableBody');
    
    data.forEach((order, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${((currentPage - 1) * parseInt(document.getElementById('perPage').value)) + index + 1}</td>
            <td>
                <strong class="text-warning">${order.order_number}</strong>
            </td>
            <td>${order.subscriber_name || 'غير محدد'}</td>
            <td>
                <span class="badge bg-secondary">${order.office || 'غير محدد'}</span>
            </td>
            <td>
                ${getStatusBadge(order.execution_status, order.execution_status_text)}
            </td>
            <td>
                <strong class="text-info">${formatCurrency(order.initial_value)}</strong>
            </td>
            <td>
                <strong class="text-success">${formatCurrency(order.executed_value)}</strong>
            </td>
            <td>
                <a href="/admin/work-orders/${order.id}" class="btn btn-outline-warning btn-sm" title="عرض التفاصيل">
                    <i class="fas fa-eye"></i>
                </a>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Display card results
function displayCardResults(data) {
    document.getElementById('resultsTable').style.display = 'none';
    document.getElementById('resultsCards').style.display = 'block';
    
    const container = document.getElementById('resultsCardsContainer');
    container.innerHTML = '';
    
    data.forEach(order => {
        const executionPercentage = calculateExecutionPercentage(order.executed_value, order.initial_value);
        const card = document.createElement('div');
        card.className = 'col-md-6 col-lg-4';
        card.innerHTML = `
            <div class="card work-order-card h-100">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong class="text-warning">${order.order_number}</strong>
                        <span class="badge bg-secondary">${order.office || 'غير محدد'}</span>
                    </div>
                </div>
                <div class="card-body">                    
                    <div class="mb-3">
                        <small class="text-muted">السعر الإجمالي المنفذ:</small>
                        <div><strong class="text-success fs-5">${formatCurrency(order.executed_value)}</strong></div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="/admin/work-orders/${order.id}" class="btn btn-outline-warning btn-sm w-100">
                        <i class="fas fa-eye me-1"></i> عرض
                    </a>
                </div>
            </div>
        `;
        container.appendChild(card);
    });
}

// Update summary cards
function updateSummaryCards(summary) {
    document.getElementById('totalExecutedOrders').textContent = summary.total_orders || 0;
    document.getElementById('totalExecutedValue').textContent = formatCurrency(summary.total_executed_value || 0);
    document.getElementById('executionPercentage').textContent = (summary.execution_percentage || 0) + '%';
    document.getElementById('totalCount').textContent = summary.total_orders || 0;
    
    // Update status counts
    if (summary.status_counts) {
        document.getElementById('status2Count').textContent = summary.status_counts.status_2 || 0;
        document.getElementById('status3Count').textContent = summary.status_counts.status_3 || 0;
        document.getElementById('status4Count').textContent = summary.status_counts.status_4 || 0;
        document.getElementById('status5Count').textContent = summary.status_counts.status_5 || 0;
        document.getElementById('status6Count').textContent = summary.status_counts.status_6 || 0;
        document.getElementById('status10Count').textContent = summary.status_counts.status_10 || 0;
    }
}

// Update pagination
function updatePagination(pagination) {
    const nav = document.getElementById('paginationNav');
    if (!pagination || pagination.total_pages <= 1) {
        nav.innerHTML = '';
        return;
    }
    
    let paginationHTML = '<ul class="pagination pagination-sm mb-0">';
    
    // Previous button
    if (pagination.current_page > 1) {
        paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadExecutedOrders(${pagination.current_page - 1})">السابق</a></li>`;
    }
    
    // Page numbers
    for (let i = Math.max(1, pagination.current_page - 2); i <= Math.min(pagination.total_pages, pagination.current_page + 2); i++) {
        paginationHTML += `<li class="page-item ${i === pagination.current_page ? 'active' : ''}">
            <a class="page-link" href="#" onclick="loadExecutedOrders(${i})">${i}</a>
        </li>`;
    }
    
    // Next button
    if (pagination.current_page < pagination.total_pages) {
        paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadExecutedOrders(${pagination.current_page + 1})">التالي</a></li>`;
    }
    
    paginationHTML += '</ul>';
    nav.innerHTML = paginationHTML;
}

// Utility functions
function showLoading() {
    document.getElementById('loadingSpinner').style.display = 'block';
    document.getElementById('resultsTable').style.display = 'none';
    document.getElementById('resultsCards').style.display = 'none';
    document.getElementById('noDataMessage').style.display = 'none';
}

function hideLoading() {
    document.getElementById('loadingSpinner').style.display = 'none';
}

function showNoData() {
    document.getElementById('noDataMessage').style.display = 'block';
    document.getElementById('resultsTable').style.display = 'none';
    document.getElementById('resultsCards').style.display = 'none';
    document.getElementById('resultsCount').textContent = '0';
}

function hideNoData() {
    document.getElementById('noDataMessage').style.display = 'none';
}

function changeView(view) {
    currentView = view;
    const buttons = document.querySelectorAll('.btn-group button');
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    // Reload current data with new view
    if (document.getElementById('resultsTableBody').children.length > 0) {
        // Re-display current data
        loadExecutedOrders(currentPage);
    }
}

function changePerPage(perPage) {
    loadExecutedOrders(1); // Reset to first page
}

function setDateRange(range) {
    const today = new Date();
    const startDate = new Date();
    const endDate = new Date();
    
    switch(range) {
        case 'week':
            startDate.setDate(today.getDate() - 7);
            break;
        case 'month':
            startDate.setMonth(today.getMonth() - 1);
            break;
        case '3months':
            startDate.setMonth(today.getMonth() - 3);
            break;
    }
    
    // Format dates to YYYY-MM-DD
    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };
    
    document.getElementById('start_date').value = formatDate(startDate);
    document.getElementById('end_date').value = formatDate(endDate);
    
    // Auto-submit the form
    loadExecutedOrders(1);
}

function clearFilters() {
    document.getElementById('executionFilterForm').reset();
    loadExecutedOrders(1);
}

function refreshData() {
    loadExecutedOrders(currentPage);
}

function exportResults() {
    const formData = new FormData(document.getElementById('executionFilterForm'));
    const params = new URLSearchParams();
    
    // Add project parameter
    @if(isset($project))
        params.append('project', '{{ $project }}');
    @else
        params.append('project', 'riyadh');
    @endif
    
    for (let [key, value] of formData.entries()) {
        if (value) {
            params.append(key, value);
        }
    }
    
    params.append('export', 'excel');
    window.open(`/api/work-orders/execution/export?${params.toString()}`, '_blank');
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'SAR',
        minimumFractionDigits: 2
    }).format(amount || 0);
}

function calculateExecutionPercentage(executed, initial) {
    if (!executed || !initial || initial === 0) return 0;
    return Math.round((executed / initial) * 100);
}

// Get status badge
function getStatusBadge(status, statusText = null) {
    const statusMap = {
        '2': { label: 'تم التنفيذ بالموقع وجاري تسليم 155', color: 'rgb(112, 68, 2)' },
        '3': { label: 'تم تسليم 155 جاري اصدار شهادة الانجاز', color: 'rgb(165, 0, 52)' },
        '4': { label: 'تم اصدار مستخلص الدفعه الجزئية الاولي وجاري الصرف', color: 'rgb(0, 56, 101)' },
        '5': { label: 'تم صرف مستخلص الدفعة الجزئية الاولي ', color: 'rgb(195, 195, 195)' },
        '6': { label: 'اعداد المستخلص الدفعة الجزئية الثانية وجاري الصرف', color: 'rgb(155, 89, 182)' },
        '8': { label: 'تم اصدار شهادة الانجاز', color: 'rgb(0, 149, 54)' },
        '9': { label: 'الغاء او تحويل امر العمل', color: 'rgb(0, 0, 0)' },
        '10': { label: 'تم اعداد المستخلص الكلي وجاري الصرف', color: 'rgb(52, 152, 219)' }
    };
    
    const statusInfo = statusMap[status];
    if (statusInfo) {
        return `<span class="badge" style="background-color: ${statusInfo.color}">${statusInfo.label}</span>`;
    }
    
    // إذا كان عندنا نص الحالة من الـ API
    if (statusText) {
        return `<span class="badge bg-secondary">${statusText}</span>`;
    }
    
    return `<span class="badge bg-secondary">${status || 'غير محدد'}</span>`;
}
</script>
@endpush

@endsection
