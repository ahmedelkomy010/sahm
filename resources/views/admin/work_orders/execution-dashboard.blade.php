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
                        <a href="{{ isset($project) && $project == 'madinah' ? '/admin/work-orders/productivity/madinah' : '/admin/work-orders/productivity/riyadh' }}" class="btn btn-light">
                                <i class="fas fa-arrow-right me-2"></i>
                                العودة للداشبورد الرئيسي
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 bg-warning bg-opacity-10">
                        <div class="card-body text-center">
                            <i class="fas fa-clipboard-check text-warning fs-2 mb-2"></i>
                            <h4 class="mb-1 text-warning" id="totalExecutedOrders">0</h4>
                            <small class="text-muted">إجمالي أوامر العمل المنتهية</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-success bg-opacity-10">
                        <div class="card-body text-center">
                            <i class="fas fa-money-check-alt text-success fs-2 mb-2"></i>
                            <h4 class="mb-1 text-success" id="totalExecutedValue">0</h4>
                            <small class="text-muted">السعر الإجمالي المنفذ</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-info bg-opacity-10">
                        <div class="card-body text-center">
                            <i class="fas fa-percentage text-info fs-2 mb-2"></i>
                            <h4 class="mb-1 text-info" id="executionPercentage">0%</h4>
                            <small class="text-muted">نسبة الإنجاز</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-primary bg-opacity-10">
                        <div class="card-body text-center">
                            <i class="fas fa-calculator text-primary fs-2 mb-2"></i>
                            <h4 class="mb-1 text-primary" id="averageExecutedValue">0</h4>
                            <small class="text-muted">متوسط القيمة المنفذة</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- حالات التنفيذ -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0" style="background-color: rgb(228, 196, 14, 0.1);">
                        <div class="card-body text-center">
                            <i class="fas fa-tools fs-2 mb-2" style="color: rgb(228, 196, 14);"></i>
                            <h4 class="mb-1" style="color: rgb(228, 196, 14);" id="status1Count">0</h4>
                            <small class="text-muted">جاري العمل بالموقع</small>
                        </div>
                    </div>
                </div>
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
                            <small class="text-muted">تم اعداد المستخلص الدفعة الجزئية الاولي</small>
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
                            <div class="col-md-4">
                                <label for="order_number" class="form-label">رقم أمر العمل</label>
                                <input type="text" class="form-control" id="order_number" name="order_number" placeholder="ابحث برقم الأمر">
                            </div>
                            
                            <div class="col-md-4">
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
                            
                            <div class="col-md-4">
                                <label class="form-label">إجراءات</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-search me-1"></i>
                                        بحث 
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="clearFilters()">
                                        <i class="fas fa-times me-1"></i>
                                        مسح الفلاتر
                                    </button>
                                    <button type="button" class="btn btn-success" onclick="exportResults()">
                                        <i class="fas fa-download me-1"></i>
                                        تصدير النتائج
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="refreshData()">
                                        <i class="fas fa-sync me-1"></i>
                                        تحديث البيانات
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
    
    data.forEach((order, orderIndex) => {
        const executionPercentage = calculateExecutionPercentage(parseFloat(order.executed_value), parseFloat(order.initial_value));
        
        // إنشاء كارت لكل أمر عمل
        const orderCard = document.createElement('div');
        orderCard.className = 'card mb-4 border-0 shadow-sm';
        
        let workItemsHtml = '';
        if (order.work_items && order.work_items.length > 0) {
            let totalPlannedAmount = 0;
            let totalExecutedAmount = 0;
            
            const itemsRows = order.work_items.map((item, itemIndex) => {
                totalPlannedAmount += parseFloat(item.planned_amount || 0);
                totalExecutedAmount += parseFloat(item.executed_amount || 0);
                const difference = parseFloat(item.difference || 0);
                
                let diffBadge = '';
                if (difference > 0) {
                    diffBadge = `<span class="badge bg-danger">-${difference.toFixed(2)}</span>`;
                } else if (difference < 0) {
                    diffBadge = `<span class="badge bg-success">+${Math.abs(difference).toFixed(2)}</span>`;
                } else {
                    diffBadge = `<span class="badge bg-secondary">0.00</span>`;
                }
                
                return `
                    <tr>
                        <td class="text-center">${itemIndex + 1}</td>
                        <td class="text-center"><span class="badge bg-primary">${item.code}</span></td>
                        <td class="text-start">${item.description}</td>
                        <td class="text-center"><span class="badge bg-info">${item.unit}</span></td>
                        <td class="text-center"><span class="text-success fw-bold">${formatCurrency(item.unit_price)}</span></td>
                        <td class="text-center">${parseFloat(item.planned_quantity || 0).toFixed(2)}</td>
                        <td class="text-center"><span class="text-primary fw-bold">${formatCurrency(item.planned_amount)}</span></td>
                        <td class="text-center">${parseFloat(item.executed_quantity || 0).toFixed(2)}</td>
                        <td class="text-center"><span class="text-success fw-bold">${formatCurrency(item.executed_amount)}</span></td>
                        <td class="text-center">${diffBadge}</td>
                    </tr>
                `;
            }).join('');
            
            workItemsHtml = `
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 0.9rem;">
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
                                <th class="text-center" style="width: 10%">السعر الإجمالي المنفذ</th>
                                <th class="text-center" style="width: 8%">فرق المنفذة - المخططة</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsRows}
                        </tbody>
                        <tfoot class="table-light">
                            <tr class="fw-bold">
                                <td colspan="6" class="text-end">الإجمالي:</td>
                                <td class="text-center text-primary">${formatCurrency(totalPlannedAmount)}</td>
                                <td class="text-center text-success">-</td>
                                <td class="text-center text-success">${formatCurrency(totalExecutedAmount)}</td>
                                <td class="text-center">
                                    ${totalPlannedAmount > totalExecutedAmount ? 
                                        `<span class="text-danger">-${formatCurrency(totalPlannedAmount - totalExecutedAmount)}</span>` :
                                        totalPlannedAmount < totalExecutedAmount ?
                                        `<span class="text-success">+${formatCurrency(totalExecutedAmount - totalPlannedAmount)}</span>` :
                                        `<span class="text-secondary">${formatCurrency(0)}</span>`
                                    }
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            `;
        } else {
            workItemsHtml = `
                <div class="text-center py-4">
                    <i class="fas fa-clipboard-list fa-2x text-muted mb-2"></i>
                    <p class="text-muted">لا توجد بنود عمل مضافة لهذا الأمر</p>
                </div>
            `;
        }
        
        orderCard.innerHTML = `
            <div class="card-header bg-warning text-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">
                            <i class="fas fa-list-alt me-2"></i>
                            بنود العمل - أمر العمل رقم ${order.order_number}
                        </h5>
                        <div class="d-flex align-items-center gap-3">
                            <small>
                                <i class="fas fa-user me-1"></i>
                                ${order.subscriber_name || 'غير محدد'}
                            </small>
                            <small>
                                <i class="fas fa-building me-1"></i>
                                ${order.office || 'غير محدد'}
                            </small>
                            <small>
                                <i class="fas fa-money-check-alt me-1"></i>
                                القيمة المنفذة: ${formatCurrency(order.executed_value)}
                            </small>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light text-dark">
                                ${order.work_items ? order.work_items.length : 0} بند
                            </span>
                            <a href="/admin/work-orders/${order.id}" class="btn btn-light btn-sm">
                                <i class="fas fa-eye me-1"></i> عرض التفاصيل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                ${workItemsHtml}
            </div>
        `;
        
        container.appendChild(orderCard);
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
    document.getElementById('averageExecutedValue').textContent = formatCurrency(summary.average_executed_value || 0);
    document.getElementById('totalCount').textContent = summary.total_orders || 0;
    
    // Update status counts
    if (summary.status_counts) {
        document.getElementById('status1Count').textContent = summary.status_counts.status_1 || 0;
        document.getElementById('status2Count').textContent = summary.status_counts.status_2 || 0;
        document.getElementById('status3Count').textContent = summary.status_counts.status_3 || 0;
        document.getElementById('status4Count').textContent = summary.status_counts.status_4 || 0;
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
</script>
@endpush

@endsection
