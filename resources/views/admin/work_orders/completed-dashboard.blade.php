@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-gradient-secondary text-white py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 fs-3">
                                <i class="fas fa-check-circle me-3"></i>
                                لوحة متابعة الأوامر المنتهية والمصروفة
                            </h2>
                            <p class="mb-0 text-white-75">
                                @if(isset($project))
                                    @if($project == 'riyadh')
                                        <i class="fas fa-city me-2"></i>مشروع الرياض
                                    @elseif($project == 'madinah')
                                        <i class="fas fa-mosque me-2"></i>مشروع المدينة المنورة
                                    @endif
                                @endif
                                - عرض وتحليل أوامر العمل المنتهية والمصروفة
                            </p>
                        </div>
                        <div class="text-end">
                        <a href="/admin/work-orders/productivity/riyadh" class="btn btn-light">
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
                    <div class="card border-0 bg-secondary bg-opacity-10">
                        <div class="card-body text-center">
                            <i class="fas fa-clipboard-check text-secondary fs-2 mb-2"></i>
                            <h4 class="mb-1 text-secondary" id="totalCompletedOrders">0</h4>
                            <small class="text-muted">أوامر مكتملة</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-success bg-opacity-10">
                        <div class="card-body text-center">
                            <i class="fas fa-money-check-alt text-success fs-2 mb-2"></i>
                            <h4 class="mb-1 text-success" id="totalFinalValue">0</h4>
                            <small class="text-muted">القيمة الكلية النهائية</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-info bg-opacity-10">
                        <div class="card-body text-center">
                            <i class="fas fa-percentage text-info fs-2 mb-2"></i>
                            <h4 class="mb-1 text-info" id="completionPercentage">0%</h4>
                            <small class="text-muted">نسبة الإنجاز الكامل</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-primary bg-opacity-10">
                        <div class="card-body text-center">
                            <i class="fas fa-calculator text-primary fs-2 mb-2"></i>
                            <h4 class="mb-1 text-primary" id="averageFinalValue">0</h4>
                            <small class="text-muted">متوسط القيمة النهائية</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-filter me-2 text-secondary"></i>
                        فلاتر البحث والتصفية
                    </h5>
                </div>
                <div class="card-body">
                    <form id="completedFilterForm">
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
                                <label for="date_from" class="form-label">من تاريخ</label>
                                <input type="date" class="form-control" id="date_from" name="date_from">
                            </div>

                            <div class="col-md-4">
                                <label for="date_to" class="form-label">إلى تاريخ</label>
                                <input type="date" class="form-control" id="date_to" name="date_to">
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label">إجراءات</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fas fa-search me-1"></i>
                                        بحث وتصفية
                                    </button>
                                    <button type="button" class="btn btn-light" onclick="clearFilters()">
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
                            <i class="fas fa-list me-2 text-secondary"></i>
                            نتائج الأوامر المنتهية والمصروفة
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
                        <div class="spinner-border text-secondary" role="status">
                            <span class="visually-hidden">جاري التحميل...</span>
                        </div>
                        <p class="mt-2 text-muted">جاري تحميل البيانات...</p>
                    </div>

                    <!-- No Data Message -->
                    <div id="noDataMessage" class="text-center py-5" style="display: none;">
                        <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد أوامر عمل منتهية ومصروفة</h5>
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
                                    <th>القيمة المبدئية</th>
                                    <th>القيمة الكلية النهائية</th>
                                    <th>تاريخ الصرف</th>
                                    <th>حالة التنفيذ</th>
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
    .bg-gradient-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
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
        border-color: #6c757d;
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.15);
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
    loadCompletedOrders();
    
    // Setup form submission
    document.getElementById('completedFilterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        loadCompletedOrders();
    });
    
    // Auto-refresh every 2 minutes
    setInterval(loadCompletedOrders, 120000);
});

let currentPage = 1;
let currentView = 'table';

// Load completed work orders
function loadCompletedOrders(page = 1) {
    currentPage = page;
    showLoading();
    
    const formData = new FormData(document.getElementById('completedFilterForm'));
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
    fetch(`/api/work-orders/completed?${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                displayResults(data.data);
                updateSummaryCards(data.summary);
                updatePagination(data.pagination);
            } else {
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
    
    const tbody = document.getElementById('resultsTableBody');
    tbody.innerHTML = '';
    
    data.forEach((order, index) => {
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td>${((currentPage - 1) * parseInt(document.getElementById('perPage').value)) + index + 1}</td>
            <td>
                <strong class="text-secondary">${order.order_number}</strong>
            </td>
            <td>
                <span class="badge bg-secondary">${order.office || 'غير محدد'}</span>
            </td>
            <td>
                <strong class="text-muted">${formatCurrency(order.initial_value)}</strong>
            </td>
            <td>
                <strong class="text-success">${formatCurrency(order.final_value)}</strong>
            </td>
            <td>
                <small class="text-muted">${formatDate(order.payment_date)}</small>
            </td>
            <td>
                <span class="badge bg-success">منتهي تم الصرف</span>
            </td>
            <td>
                <div class="btn-group btn-group-sm">
                    <a href="/admin/work-orders/${order.id}" class="btn btn-outline-secondary" title="عرض التفاصيل">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
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
                                <strong class="text-secondary">${order.order_number}</strong>
                                <span class="badge bg-secondary">${order.office || 'غير محدد'}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">حالة التنفيذ:</small>
                                <div><span class="badge bg-success">منتهي تم الصرف</span></div>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted">القيمة المبدئية:</small>
                                <div><strong class="text-muted">${formatCurrency(order.initial_value)}</strong></div>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted">القيمة الكلية النهائية:</small>
                                <div><strong class="text-success fs-5">${formatCurrency(order.final_value)}</strong></div>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted">تاريخ الصرف:</small>
                                <div><small class="text-muted">${formatDate(order.payment_date)}</small></div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="btn-group w-100">
                                <a href="/admin/work-orders/${order.id}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-eye me-1"></i> عرض
                                </a>
                            </div>
                        </div>
                    </div>
        `;
        container.appendChild(card);
    });
}

// Update summary cards
function updateSummaryCards(summary) {
    document.getElementById('totalCompletedOrders').textContent = summary.total_orders || 0;
    document.getElementById('totalFinalValue').textContent = formatCurrency(summary.total_final_value || 0);
    document.getElementById('completionPercentage').textContent = (summary.completion_percentage || 0) + '%';
    document.getElementById('averageFinalValue').textContent = formatCurrency(summary.average_final_value || 0);
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
        paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadCompletedOrders(${pagination.current_page - 1})">السابق</a></li>`;
    }
    
    // Page numbers
    for (let i = Math.max(1, pagination.current_page - 2); i <= Math.min(pagination.total_pages, pagination.current_page + 2); i++) {
        paginationHTML += `<li class="page-item ${i === pagination.current_page ? 'active' : ''}">
            <a class="page-link" href="#" onclick="loadCompletedOrders(${i})">${i}</a>
        </li>`;
    }
    
    // Next button
    if (pagination.current_page < pagination.total_pages) {
        paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadCompletedOrders(${pagination.current_page + 1})">التالي</a></li>`;
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
        loadCompletedOrders(currentPage);
    }
}

function changePerPage(perPage) {
    loadCompletedOrders(1); // Reset to first page
}

function clearFilters() {
    document.getElementById('completedFilterForm').reset();
    loadCompletedOrders(1);
}

function refreshData() {
    loadCompletedOrders(currentPage);
}

function exportResults() {
    const formData = new FormData(document.getElementById('completedFilterForm'));
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
    window.open(`/api/work-orders/completed/export?${params.toString()}`, '_blank');
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
