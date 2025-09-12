@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-gradient-success text-white py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 fs-3">
                                <i class="fas fa-inbox me-3"></i>
                                لوحة متابعة الاستلامات
                            </h2>
                            <p class="mb-0 text-white-75">
                                @if(isset($project))
                                    @if($project == 'riyadh')
                                        <i class="fas fa-city me-2"></i>مشروع الرياض
                                    @elseif($project == 'madinah')
                                        <i class="fas fa-mosque me-2"></i>مشروع المدينة المنورة
                                    @endif
                                @endif
                                - عرض وتحليل أوامر العمل المستلمة
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
                    <div class="card border-0 bg-success bg-opacity-10">
                        <div class="card-body text-center">
                            <i class="fas fa-clipboard-check text-success fs-2 mb-2"></i>
                            <h4 class="mb-1 text-success" id="totalReceivedOrders">0</h4>
                            <small class="text-muted">إجمالي أوامر العمل المستلمة</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-info bg-opacity-10">
                        <div class="card-body text-center">
                            <i class="fas fa-money-bill-wave text-info fs-2 mb-2"></i>
                            <h4 class="mb-1 text-info" id="totalInitialValue">0</h4>
                            <small class="text-muted">إجمالي القيمة المبدئية (بدون استشاري)</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-warning bg-opacity-10">
                        <div class="card-body text-center">
                            <i class="fas fa-percentage text-warning fs-2 mb-2"></i>
                            <h4 class="mb-1 text-warning" id="receivedPercentage">0%</h4>
                            <small class="text-muted">نسبة من إجمالي الأوامر</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-primary bg-opacity-10">
                        <div class="card-body text-center">
                            <i class="fas fa-calculator text-primary fs-2 mb-2"></i>
                            <h4 class="mb-1 text-primary" id="averageOrderValue">0</h4>
                            <small class="text-muted">متوسط قيمة الأمر</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-filter me-2 text-success"></i>
                        فلاتر البحث والتصفية
                    </h5>
                </div>
                <div class="card-body">
                    <form id="receiptsFilterForm">
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
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-search me-1"></i>
                                        بحث وتصفية
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="clearFilters()">
                                        <i class="fas fa-times me-1"></i>
                                        مسح الفلاتر
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="exportResults()">
                                        <i class="fas fa-download me-1"></i>
                                        تصدير النتائج
                                    </button>
                                    <button type="button" class="btn btn-primary" onclick="refreshData()">
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
                            <i class="fas fa-list me-2 text-success"></i>
                            نتائج أوامر العمل المستلمة
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
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">جاري التحميل...</span>
                        </div>
                        <p class="mt-2 text-muted">جاري تحميل البيانات...</p>
                    </div>

                    <!-- No Data Message -->
                    <div id="noDataMessage" class="text-center py-5" style="display: none;">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد أوامر عمل مستلمة</h5>
                        <p class="text-muted">جرب تغيير معايير البحث أو التأكد من وجود أوامر عمل</p>
                    </div>

                    <!-- Results Table -->
                    <div id="resultsTable" class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>رقم أمر العمل</th>
                                    <th>المكتب</th>
                                    <th>القيمة المبدئية (بدون استشاري)</th>
                                    <th>تاريخ الاستلام</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody id="resultsTableBody">
                                <!-- Results will be populated here -->
                            </tbody>
                        </table>
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
    .bg-gradient-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
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
    loadReceivedOrders();
    
    // Setup form submission
    document.getElementById('receiptsFilterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        loadReceivedOrders();
    });
    
    // Auto-refresh every 2 minutes
    setInterval(loadReceivedOrders, 120000);
});

let currentPage = 1;
let currentView = 'table';

// Load received work orders
function loadReceivedOrders(page = 1) {
    currentPage = page;
    showLoading();
    
    const formData = new FormData(document.getElementById('receiptsFilterForm'));
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
    console.log('Making API call to:', `/api/work-orders/receipts?${params.toString()}`);
    fetch(`/api/work-orders/receipts?${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('API Response:', data);
            hideLoading();
            if (data.success) {
                displayResults(data.data);
                updateSummaryCards(data.summary);
                updatePagination(data.pagination);
            } else {
                console.log('API returned error:', data);
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
    console.log('displayResults called with data:', data);
    if (data.length === 0) {
        console.log('No data to display');
        showNoData();
        return;
    }
    
    console.log('Displaying', data.length, 'records');
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
    
    const tbody = document.getElementById('resultsTableBody');
    tbody.innerHTML = '';
    
    data.forEach((order, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${((currentPage - 1) * parseInt(document.getElementById('perPage').value)) + index + 1}</td>
            <td>
                <strong class="text-success">${order.order_number}</strong>
            </td>
            <td>
                <span class="badge bg-secondary">${order.office || 'غير محدد'}</span>
            </td>
            <td>
                <strong class="text-info">${formatCurrency(order.order_value_without_consultant)}</strong>
            </td>
            <td>
                <small class="text-muted">${formatDate(order.received_at)}</small>
            </td>
            <td>
                <a href="/admin/work-orders/${order.id}" class="btn btn-outline-success btn-sm" title="عرض التفاصيل">
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
        const card = document.createElement('div');
        card.className = 'col-md-6 col-lg-4';
        card.innerHTML = `
            <div class="card work-order-card h-100">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong class="text-success">${order.order_number}</strong>
                        <span class="badge bg-secondary">${order.office || 'غير محدد'}</span>
                    </div>
                </div>
                <div class="card-body">                    
                    <div class="mb-3">
                        <small class="text-muted">القيمة المبدئية (بدون استشاري):</small>
                        <div><strong class="text-info fs-5">${formatCurrency(order.order_value_without_consultant)}</strong></div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">تاريخ الاستلام:</small>
                        <div><small class="text-muted">${formatDate(order.received_at)}</small></div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="/admin/work-orders/${order.id}" class="btn btn-outline-success btn-sm w-100">
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
    document.getElementById('totalReceivedOrders').textContent = summary.total_orders || 0;
    document.getElementById('totalInitialValue').textContent = formatCurrency(summary.total_value || 0);
    document.getElementById('receivedPercentage').textContent = (summary.percentage || 0) + '%';
    document.getElementById('averageOrderValue').textContent = formatCurrency(summary.average_value || 0);
    document.getElementById('totalCount').textContent = summary.total_orders || 0;
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
        paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadReceivedOrders(${pagination.current_page - 1})">السابق</a></li>`;
    }
    
    // Page numbers
    for (let i = Math.max(1, pagination.current_page - 2); i <= Math.min(pagination.total_pages, pagination.current_page + 2); i++) {
        paginationHTML += `<li class="page-item ${i === pagination.current_page ? 'active' : ''}">
            <a class="page-link" href="#" onclick="loadReceivedOrders(${i})">${i}</a>
        </li>`;
    }
    
    // Next button
    if (pagination.current_page < pagination.total_pages) {
        paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadReceivedOrders(${pagination.current_page + 1})">التالي</a></li>`;
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
        loadReceivedOrders(currentPage);
    }
}

function changePerPage(perPage) {
    loadReceivedOrders(1); // Reset to first page
}

function clearFilters() {
    document.getElementById('receiptsFilterForm').reset();
    loadReceivedOrders(1);
}

function refreshData() {
    loadReceivedOrders(currentPage);
}

function exportResults() {
    const formData = new FormData(document.getElementById('receiptsFilterForm'));
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
    window.open(`/api/work-orders/receipts/export?${params.toString()}`, '_blank');
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'SAR',
        minimumFractionDigits: 2
    }).format(amount || 0);
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });
}
</script>
@endpush

@endsection
