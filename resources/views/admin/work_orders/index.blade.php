@extends('layouts.app')



@push('styles')
<style>
.countdown-badge {
    font-size: 0.85rem;
    padding: 0.4rem 0.6rem;
}

.countdown-badge i {
    font-size: 0.9rem;
}

/* Enhanced Table Header Styles */
.table-dark th {
    background: linear-gradient(135deg,rgb(46, 110, 174) 0%,rgb(20, 72, 124) 100%) !important;
    border-color: #6c757d !important;
    color: #fff !important;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    padding: 1rem 0.5rem;
    position: relative;
    font-size: 0.9rem;
    line-height: 1.3;
}

.table-dark th i {
    color: #ffc107;
    font-size: 1rem;
    margin-bottom: 2px;
}

.table-dark th small {
    color: #e9ecef;
    font-weight: 400;
    font-size: 0.75rem;
}

.table-dark th:hover {
    background: linear-gradient(135deg,rgb(19, 90, 153) 0%,rgb(16, 93, 169) 100%) !important;
    transform: translateY(-2px);
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Table Body Enhancement */
.table-hover tbody tr:hover {
    background-color: #f8f9fa !important;
    transform: scale(1.005);
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.table-bordered td {
    border-color: #dee2e6 !important;
    padding: 0.75rem 0.5rem;
    vertical-align: middle;
}

/* Responsive Header Text */
@media (max-width: 768px) {
    .table-dark th {
        font-size: 0.8rem;
        padding: 0.75rem 0.3rem;
    }
    
    .table-dark th i {
        font-size: 0.9rem;
    }
}

/* Responsive Button Styles */
.btn-responsive {
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s ease;
    min-height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-responsive:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.btn-responsive i {
    font-size: 1rem;
    margin-left: 0.5rem;
}

/* Mobile Responsive Buttons */
@media (max-width: 991.98px) {
    .btn-responsive {
        padding: 0.6rem 0.8rem;
        font-size: 0.85rem;
        min-width: auto;
        flex: 1 1 auto;
        max-width: 200px;
    }
    
    .btn-responsive .btn-text {
        display: block;
    }
}

@media (max-width: 576px) {
    .btn-responsive {
        padding: 0.5rem 0.7rem;
        font-size: 0.8rem;
        min-height: 40px;
        border-radius: 6px;
    }
    
    .btn-responsive i {
        font-size: 0.9rem;
        margin-left: 0.3rem;
    }
    
    .btn-responsive .btn-text {
        font-size: 0.75rem;
        line-height: 1.2;
    }
}

/* Extra small screens - show only icons */
@media (max-width: 400px) {
    .btn-responsive {
        min-width: 45px;
        padding: 0.5rem;
    }
    
    .btn-responsive .btn-text {
        display: none;
    }
    
    .btn-responsive i {
        margin: 0;
        font-size: 1rem;
    }
}

/* Button Group Responsive Layout */
.d-flex.flex-wrap.gap-2 {
    gap: 0.5rem !important;
}

@media (max-width: 576px) {
    .d-flex.flex-wrap.gap-2 {
        gap: 0.3rem !important;
    }
}

/* Enhanced Button Colors and Effects */
.btn-responsive.btn-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    border: none;
    color: white;
}

.btn-responsive.btn-info:hover {
    background: linear-gradient(135deg, #138496 0%, #0f6674 100%);
    color: white;
}

.btn-responsive.btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    border: none;
    color: #212529;
}

.btn-responsive.btn-warning:hover {
    background: linear-gradient(135deg, #e0a800 0%, #c69500 100%);
    color: #212529;
}

.btn-responsive.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    color: white;
}

.btn-responsive.btn-success:hover {
    background: linear-gradient(135deg, #20c997 0%, #1e7e34 100%);
    color: white;
}

.btn-responsive.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
    color: white;
}

.btn-responsive.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    color: white;
}

/* Button Container Enhancements */
.col-12.col-lg-8,
.col-12.col-lg-4 {
    margin-bottom: 0.5rem;
}

@media (max-width: 991.98px) {
    .col-12.col-lg-8,
    .col-12.col-lg-4 {
        margin-bottom: 1rem;
    }
}

.filter-badge-secondary {
    background-color: #f8f9fa;
    color: #6c757d;
    border: 1px solid #dee2e6;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    margin: 0.1rem;
    border-radius: 0.375rem;
    display: inline-block;
}

/* Custom dropdown styles */
.dropdown-toggle::after {
    margin-left: auto;
}

.dropdown-menu {
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    padding: 0.5rem 0;
    min-width: 350px;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
    border: none;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

.dropdown-item .form-check {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.dropdown-item .form-check-input {
    margin-top: 0;
    margin-right: 0;
    transform: scale(1.1);
}

.dropdown-item .form-check-label {
    margin-bottom: 0;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    color: #495057;
    flex: 1;
}

.dropdown-item .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-weight: 600;
    min-width: 20px;
    text-align: center;
}

/* Special styling for select all option */
.dropdown-item:first-child {
    border-bottom: 1px solid #dee2e6;
    margin-bottom: 0.25rem;
    padding-bottom: 0.75rem;
}

.dropdown-item:first-child .form-check-label {
    font-weight: 700;
    color: #0d6efd;
}

/* Divider styling */
.dropdown-divider {
    margin: 0.25rem 0;
    border-color: #dee2e6;
}

/* Checked items highlighting */
.dropdown-item .form-check-input:checked + .form-check-label {
    background-color: rgba(13, 110, 253, 0.1);
    border-radius: 0.375rem;
    padding: 0.25rem 0.5rem;
    margin: -0.25rem -0.5rem;
    font-weight: 600;
}

/* Icon styling */
.dropdown-item .form-check-label i {
    opacity: 0.7;
    transition: opacity 0.2s ease;
}

.dropdown-item:hover .form-check-label i {
    opacity: 1;
}

/* Badge improvements */
.dropdown-item .badge {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Smooth scrollbar */
.dropdown-menu::-webkit-scrollbar {
    width: 6px;
}

.dropdown-menu::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.dropdown-menu::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.dropdown-menu::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* تم إزالة CSS الخاص بـ executionStatusDropdown لأنه غير مستخدم */

/* Styling for single status clear buttons */
.clear-single-status {
    font-size: 0.8rem;
    margin-left: 5px;
    cursor: pointer;
    color: #dc3545 !important;
    transition: all 0.2s ease;
}

.clear-single-status:hover {
    color: #b02a37 !important;
    transform: scale(1.1);
}

.filter-badge-secondary {
    position: relative;
    padding-right: 25px;
}

.filter-badge-secondary .clear-single-status {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
}
</style>
@endpush
        



@section('scripts')
<script>
// تحديث العداد التصاعدي لمدة التنفيذ
function updateCountdowns() {
    document.querySelectorAll('.countdown-badge').forEach(badge => {
        let workOrderId = badge.dataset.workOrder;
        let totalDays = parseInt(badge.dataset.start) || 0;
        let approvalDate = badge.dataset.approvalDate;
        let procedure155Date = badge.dataset.procedure155Date;
        
        // التحقق من حالة التنفيذ
        const executionStatus = badge.dataset.executionStatus;
        if (executionStatus && parseInt(executionStatus) >= 2) {
            badge.className = 'badge countdown-badge bg-info';
            if (parseInt(executionStatus) === 9) {
                badge.innerHTML = '<i class="fas fa-times me-1"></i>الغاء او تحويل امر العمل';
                badge.className = 'badge countdown-badge bg-danger';
            } else {
                badge.innerHTML = '<i class="fas fa-check me-1"></i>تم التسليم';
            }
            return;
        }
        
        // إذا كان هناك تاريخ تسليم إجراء 155، توقف العداد - أمر العمل انتهى
        if (procedure155Date && procedure155Date !== 'null' && procedure155Date !== '') {
            const startDate = new Date(approvalDate);
            const targetDate = new Date(procedure155Date);
            const today = new Date();
            
            startDate.setHours(0, 0, 0, 0);
            targetDate.setHours(0, 0, 0, 0);
            today.setHours(0, 0, 0, 0);
            
            // حساب عدد الأيام اللي انتهى فيها أمر العمل (من تاريخ الاعتماد لتاريخ إجراء 155)
            const completionDays = Math.floor((targetDate - startDate) / (1000 * 60 * 60 * 24));
            
            // تم إصدار إجراء 155 - أمر العمل انتهى - توقف العداد
            badge.className = 'badge countdown-badge bg-success';
            badge.innerHTML = `<i class="fas fa-check-circle me-1"></i> تم تنفيذ ${completionDays} يوم`;
            return;
        } else {
            // الحساب القديم بناءً على الأيام اليدوية - حساب الأيام المنقضية من تاريخ الاعتماد
            const startDate = new Date(approvalDate);
            const today = new Date();
            startDate.setHours(0, 0, 0, 0);
            today.setHours(0, 0, 0, 0);
            
            const daysSinceApproval = Math.floor((today - startDate) / (1000 * 60 * 60 * 24));

            if (totalDays > 0) {
                if (daysSinceApproval <= totalDays) {
                    const remainingDays = totalDays - daysSinceApproval;
                    badge.className = 'badge countdown-badge bg-success';
                    badge.innerHTML = `<i class="fas fa-clock me-1"></i>${remainingDays} يوم متبقي`;
                } else {
                    const overdueDays = daysSinceApproval - totalDays;
                    badge.className = 'badge countdown-badge bg-danger';
                    badge.innerHTML = `<i class="fas fa-exclamation-circle me-1"></i>متأخر ${overdueDays} يوم`;
                }
            } else {
                badge.className = 'badge countdown-badge bg-secondary';
                badge.innerHTML = '<i class="fas fa-minus-circle me-1"></i>غير محدد';
            }
        }
    });
}

// تحديث العداد عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    updateCountdowns();
});

// تحديث كل دقيقة
setInterval(updateCountdowns, 60000);

// تم إزالة Multi-select execution status functions لأنها غير مستخدمة حالياً

// Initialize on page load
// تم إزالة JavaScript الخاص بـ executionStatusDropdown لأنه غير مستخدم

// Function to clear single execution status
function clearSingleExecutionStatus(statusToRemove) {
    console.log('Clearing status:', statusToRemove); // Debug log
    
    try {
        const url = new URL(window.location.href);
        console.log('Current URL:', url.toString()); // Debug log
        
        // Get current statuses (could be single value or array)
        let currentStatuses = [];
        
        // Check for array format first
        const arrayStatuses = url.searchParams.getAll('execution_status[]');
        console.log('Array statuses:', arrayStatuses); // Debug log
        
        if (arrayStatuses.length > 0) {
            currentStatuses = arrayStatuses;
        } else {
            // Check for single value format
            const singleStatus = url.searchParams.get('execution_status');
            console.log('Single status:', singleStatus); // Debug log
            if (singleStatus) {
                currentStatuses = [singleStatus];
            }
        }
        
        console.log('Current statuses before filter:', currentStatuses); // Debug log
        
        // Remove the specific status
        const newStatuses = currentStatuses.filter(status => status !== statusToRemove);
        console.log('New statuses after filter:', newStatuses); // Debug log
        
        // Clear all execution_status parameters
        url.searchParams.delete('execution_status');
        url.searchParams.delete('execution_status[]');
        
        // Add back the remaining statuses
        if (newStatuses.length > 0) {
            newStatuses.forEach(status => {
                url.searchParams.append('execution_status[]', status);
            });
        }
        
        console.log('Final URL:', url.toString()); // Debug log
        
        // Redirect to the new URL
        window.location.href = url.toString();
        
    } catch (error) {
        console.error('Error in clearSingleExecutionStatus:', error);
        // Fallback: Use form submission method
        clearSingleExecutionStatusFallback(statusToRemove);
    }
}

// Fallback method using form submission
function clearSingleExecutionStatusFallback(statusToRemove) {
    console.log('Using fallback method for status:', statusToRemove);
    
    // Get current URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    
    // Create a form to submit the new parameters
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = window.location.pathname;
    
    // Add all current parameters except the one we want to remove
    for (const [key, value] of urlParams.entries()) {
        if (key === 'execution_status[]' && value === statusToRemove) {
            continue; // Skip this specific status
        }
        if (key === 'execution_status' && value === statusToRemove) {
            continue; // Skip this specific status
        }
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    }
    
    // Submit the form
    document.body.appendChild(form);
    form.submit();
}

// Simple alternative method
function clearSingleExecutionStatusSimple(statusToRemove) {
    console.log('Using simple method for status:', statusToRemove);
    
    // Get current page URL
    let currentUrl = window.location.href;
    
    // Remove the specific status from URL
    // Handle array format: execution_status[]=value
    const arrayPattern = new RegExp(`[&?]execution_status\\[\\]=${statusToRemove}`, 'g');
    currentUrl = currentUrl.replace(arrayPattern, '');
    
    // Handle single format: execution_status=value
    const singlePattern = new RegExp(`[&?]execution_status=${statusToRemove}`, 'g');
    currentUrl = currentUrl.replace(singlePattern, '');
    
    // Clean up URL (remove double &, fix ? issues)
    currentUrl = currentUrl.replace(/[&?]&+/g, '&').replace(/[?&]$/, '').replace(/\?&/, '?');
    
    // If no execution_status parameters left, make sure we don't have dangling &
    if (!currentUrl.includes('execution_status')) {
        currentUrl = currentUrl.replace(/[&?]$/, '');
    }
    
    console.log('Redirecting to:', currentUrl);
    window.location.href = currentUrl;
}
</script>
@endsection









@push('scripts')
<script>
// وظيفة لإعادة تعيين العداد (للاستخدام المستقبلي)
function resetCountdown(workOrderId) {
    localStorage.removeItem(`execution_countdown_${workOrderId}_last_update`);
    localStorage.removeItem(`execution_countdown_${workOrderId}_days`);
    localStorage.removeItem(`execution_countdown_${workOrderId}_start_date`);
    
    // إزالة تنبيهات التأخير
    for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        if (key && key.startsWith(`alert_shown_${workOrderId}_`)) {
            localStorage.removeItem(key);
        }
    }
}
</script>
@endpush

@section('content')
<div class="container">
    <!-- Project Selection Banner -->
    @if(isset($project))
        @if($project == 'riyadh')
        
        @elseif($project == 'madinah')
        
        @endif
    @else
        <!-- إذا لم يتم اختيار مشروع، وجه المستخدم لاختيار مشروع -->
        <div class="alert alert-warning mb-4 d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div>
                <strong>يرجى اختيار المشروع:</strong> يجب اختيار مشروع محدد للمتابعة
                <br>
                <a href="{{ route('project.selection') }}" class="btn btn-primary btn-sm mt-2">
                    <i class="fas fa-city me-1"></i> اختيار المشروع
                </a>
            </div>
        </div>
    @endif

    @if(isset($project))
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <span class="fs-5">أوامر العمل</span>
                        @if($project == 'riyadh')
                        <span class="badge bg-light text-dark ms-2">
                            <i class="fas fa-city me-1"></i>
                            الرياض
                        </span>
                        @elseif($project == 'madinah')
                        <span class="badge bg-light text-dark ms-2">
                            <i class="fas fa-mosque me-1"></i>
                            المدينة المنورة
                        </span>
                        @endif
                    </div>
                    <a href="{{ route('project.selection') }}" class="btn btn-outline-light btn-sm ms-3">
                        <i class="fas fa-exchange-alt me-1"></i> تغيير المشروع
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4 gy-3">
                        <!-- الأزرار الثلاثة على اليسار -->
                        <div class="col-12 col-lg-8">
                            <div class="d-flex flex-wrap gap-2 align-items-center justify-content-center justify-content-lg-start">
                            @php
                                $projectLower = strtolower($project ?? '');
                                $isRiyadh = str_contains($projectLower, 'riyadh') || str_contains($projectLower, 'الرياض') || 
                                            str_contains($projectLower, 'riyad') || str_contains($projectLower, 'رياض');
                                $isMadinah = str_contains($projectLower, 'medina') || str_contains($projectLower, 'المدينة') || 
                                             str_contains($projectLower, 'madinah');
                            @endphp
                            
                            @if($isRiyadh && auth()->user()->hasPermission('riyadh_materials_overview'))
                                <a href="{{ route('admin.materials.riyadh-overview') }}" class="btn btn-info btn-responsive">
                                    <i class="fas fa-eye me-1"></i>
                                    <span class="btn-text">موقف مواد المستودعات</span>
                                </a>
                            @elseif($isMadinah && auth()->user()->hasPermission('madinah_materials_overview'))
                                <a href="{{ route('admin.materials.madinah-overview') }}" class="btn btn-info btn-responsive">
                                    <i class="fas fa-eye me-1"></i>
                                    <span class="btn-text">موقف مواد المستودعات</span>
                                </a>
                            @endif
                            
                            @if(($isRiyadh && auth()->user()->hasPermission('riyadh_execution_productivity')) || 
                                ($isMadinah && auth()->user()->hasPermission('madinah_execution_productivity')))
                            <a href="{{ route('admin.work-orders.execution-productivity', ['project' => $project ?? 'riyadh']) }}" class="btn btn-purple btn-responsive" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: white;">
                                <i class="fas fa-chart-line me-1"></i>
                                <span class="btn-text">انتاجية التنفيذ</span>
                            </a>
                            @endif
                            
                            @if(($isRiyadh && auth()->user()->hasPermission('riyadh_license_details')) || 
                                ($isMadinah && auth()->user()->hasPermission('madinah_license_details')))
                            <a href="{{ route('admin.licenses.display', ['project' => $project ?? 'riyadh']) }}" class="btn btn-warning btn-responsive">
                                <i class="fas fa-file-contract me-1"></i>
                                <span class="btn-text">تفاصيل الجودة والرخص</span>
                            </a>
                            @endif
                            
                            <!-- @if(($isRiyadh && auth()->user()->hasPermission('riyadh_revenues')) || 
                                ($isMadinah && auth()->user()->hasPermission('madinah_revenues')) ||
                                auth()->user()->hasPermission('view_revenues'))
                            <a href="{{ route('admin.work-orders.revenues', ['project' => $project ?? 'riyadh']) }}" class="btn btn-success btn-responsive" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border: none; color: white;">
                                <i class="fas fa-chart-line me-1"></i>
                                <span class="btn-text">الإيرادات</span>
                            </a>
                            @endif -->
                            </div>
                        </div>

                        <!-- أزرار التصدير والإنشاء على اليمين -->
                        <div class="col-12 col-lg-4">
                            <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-end align-items-center">
                                <a href="{{ route('admin.work-orders.export.excel', ['project' => $project]) }}" class="btn btn-success btn-responsive">
                                    <i class="fas fa-file-excel me-1"></i>
                                    <span class="btn-text">تصدير إكسل</span>
                                </a>
                                <a href="{{ route('admin.work-orders.create', ['project' => $project]) }}" class="btn btn-primary btn-responsive">
                                    <i class="fas fa-plus me-1"></i>
                                    <span class="btn-text">إنشاء أمر عمل جديد</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    فلاتر البحث
                     <div class="mb-4 bg-light p-4 rounded shadow-sm border">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-filter me-2"></i>
                                بحث في أوامر العمل
                            </h5>
                           
                        </div>
                        <form action="" method="GET" class="row g-3">
                            <input type="hidden" name="project" value="{{ $project }}">
                            
                            <!-- الصف الأول -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="order_number" class="form-label fw-bold">
                                        <i class="fas fa-hashtag text-primary me-1"></i>
                                        رقم أمر العمل
                                    </label>
                                    <input type="text" class="form-control" id="order_number" name="order_number" 
                                        value="{{ request('order_number') }}" placeholder="ابحث برقم أمر العمل...">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="work_type" class="form-label fw-bold">
                                        <i class="fas fa-tools text-primary me-1"></i>
                                        نوع العمل
                                    </label>
                                    <select class="form-select" id="work_type" name="work_type">
                                        <option value="">جميع الأنواع</option>
                                        <option value="409" {{ request('work_type') == '409' ? 'selected' : '' }}>409 - ازالة-نقل شبكة على المشترك</option>
                                        <option value="408" {{ request('work_type') == '408' ? 'selected' : '' }}>408 - ازاله عداد على المشترك</option>
                                        <option value="460" {{ request('work_type') == '460' ? 'selected' : '' }}>460 - استبدال شبكات</option>
                                        <option value="901" {{ request('work_type') == '901' ? 'selected' : '' }}>901 - اضافة عداد طاقة شمسية</option>
                                        <option value="440" {{ request('work_type') == '440' ? 'selected' : '' }}>440 - الرفع المساحي</option>
                                        <option value="410" {{ request('work_type') == '410' ? 'selected' : '' }}>410 - انشاء محطة/محول لمشترك/مشتركين</option>
                                        <option value="801" {{ request('work_type') == '801' ? 'selected' : '' }}>801 - تركيب عداد ايصال سريع</option>
                                        <option value="804" {{ request('work_type') == '804' ? 'selected' : '' }}>804 - تركيب محطة ش ارضية VM ايصال سريع</option>
                                        <option value="805" {{ request('work_type') == '805' ? 'selected' : '' }}>805 - تركيب محطة ش هوائية VM ايصال سريع</option>
                                        <option value="480" {{ request('work_type') == '480' ? 'selected' : '' }}>480 - (تسليم مفتاح) تمويل خارجي</option>
                                        <option value="441" {{ request('work_type') == '441' ? 'selected' : '' }}>441 - تعزيز شبكة ارضية ومحطات</option>
                                        <option value="442" {{ request('work_type') == '442' ? 'selected' : '' }}>442 - تعزيز شبكة هوائية ومحطات</option>
                                        <option value="802" {{ request('work_type') == '802' ? 'selected' : '' }}>802 - شبكة ارضية VL ايصال سريع</option>
                                        <option value="803" {{ request('work_type') == '803' ? 'selected' : '' }}>803 - شبكة هوائية VL ايصال سريع</option>
                                        <option value="402" {{ request('work_type') == '402' ? 'selected' : '' }}>402 - توصيل عداد بحفرية شبكة ارضيه</option>
                                        <option value="401" {{ request('work_type') == '401' ? 'selected' : '' }}>401 - (عداد بدون حفرية) أرضي/هوائي</option>
                                        <option value="404" {{ request('work_type') == '404' ? 'selected' : '' }}>404 - عداد بمحطة شبكة ارضية VM</option>
                                        <option value="405" {{ request('work_type') == '405' ? 'selected' : '' }}>405 - توصيل عداد بمحطة شبكة هوائية VM</option>
                                        <option value="430" {{ request('work_type') == '430' ? 'selected' : '' }}>430 - مخططات منح وزارة البلدية</option>
                                        <option value="450" {{ request('work_type') == '450' ? 'selected' : '' }}>450 - مشاريع ربط محطات التحويل</option>
                                        <option value="403" {{ request('work_type') == '403' ? 'selected' : '' }}>403 - توصيل عداد شبكة هوائية VL</option>
                                        <option value="806" {{ request('work_type') == '806' ? 'selected' : '' }}>806 - ايصال وزارة الاسكان جهد منخفض</option>
                                        <option value="444" {{ request('work_type') == '444' ? 'selected' : '' }}>444 -  تحويل الشبكه من هوائي الي ارضي</option>
                                        <option value="111" {{ old('work_type') == '111' ? 'selected' : '' }}> -  Mv- طوارئ ضغط متوسط  </option>
                                        <option value="222" {{ old('work_type') == '222' ? 'selected' : '' }}> -  Lv - طوارئ ض منخفض </option>
                                        <option value="333" {{ old('work_type') == '333' ? 'selected' : '' }}> -  Oh  - طوارئ هوائي </option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="subscriber_name" class="form-label fw-bold">
                                        <i class="fas fa-user text-primary me-1"></i>
                                        اسم المشترك
                                    </label>
                                    <input type="text" class="form-control" id="subscriber_name" name="subscriber_name" 
                                        value="{{ request('subscriber_name') }}" placeholder="ابحث باسم المشترك...">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="district" class="form-label fw-bold">
                                        <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                        الحي
                                    </label>
                                    <input type="text" class="form-control" id="district" name="district" 
                                        value="{{ request('district') }}" placeholder="ابحث بالحي...">
                                </div>
                            </div>
                            
                            <!-- الصف الثاني -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="office" class="form-label fw-bold">
                                        <i class="fas fa-building text-primary me-1"></i>
                                        المكتب
                                    </label>
                                    <select class="form-select" id="office" name="office">
                                        <option value="">جميع المكاتب</option>
                                        @if($project == 'riyadh')
                                            <option value="خريص" {{ request('office') == 'خريص' ? 'selected' : '' }}>خريص</option>
                                            <option value="الشرق" {{ request('office') == 'الشرق' ? 'selected' : '' }}>الشرق</option>
                                            <option value="الشمال" {{ request('office') == 'الشمال' ? 'selected' : '' }}>الشمال</option>
                                            <option value="الجنوب" {{ request('office') == 'الجنوب' ? 'selected' : '' }}>الجنوب</option>
                                            <option value="الدرعية" {{ request('office') == 'الدرعية' ? 'selected' : '' }}>الدرعية</option>
                                        @elseif($project == 'madinah')
                                            <option value="المدينة المنورة" {{ request('office') == 'المدينة المنورة' ? 'selected' : '' }}>المدينة المنورة</option>
                                            <option value="ينبع" {{ request('office') == 'ينبع' ? 'selected' : '' }}>ينبع</option>
                                            <option value="خيبر" {{ request('office') == 'خيبر' ? 'selected' : '' }}>خيبر</option>
                                            <option value="مهد الذهب" {{ request('office') == 'مهد الذهب' ? 'selected' : '' }}>مهد الذهب</option>
                                            <option value="بدر" {{ request('office') == 'بدر' ? 'selected' : '' }}>بدر</option>
                                            <option value="الحناكية" {{ request('office') == 'الحناكية' ? 'selected' : '' }}>الحناكية</option>
                                            <option value="العلا" {{ request('office') == 'العلا' ? 'selected' : '' }}>العلا</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="consultant_name" class="form-label fw-bold">
                                        <i class="fas fa-user-tie text-primary me-1"></i>
                                        اسم الاستشاري
                                    </label>
                                    <input type="text" class="form-control" id="consultant_name" name="consultant_name" 
                                        value="{{ request('consultant_name') }}" placeholder="ابحث باسم الاستشاري...">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="station_number" class="form-label fw-bold">
                                        <i class="fas fa-broadcast-tower text-primary me-1"></i>
                                        رقم المحطة
                                    </label>
                                    <input type="text" class="form-control" id="station_number" name="station_number" 
                                        value="{{ request('station_number') }}" placeholder="ابحث برقم المحطة...">
                                </div>
                            </div>
                            
                            
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="execution_status" class="form-label fw-bold">
                                        <i class="fas fa-tasks text-primary me-1"></i>
                                        حالة التنفيذ
                                    </label>
                                    <!-- Select dropdown for execution status -->
                                    <select class="form-select" id="execution_status" name="execution_status" onchange="liveSearch()" style="height: 38px;">
                                        <option value="">كل الحالات</option>
                                        @php
                                            $statusLabels = [
                                                '1' => 'جاري العمل بالموقع',
                                                '2' => 'تم التنفيذ بالموقع وجاري تسليم 155',
                                                '3' => 'تم تسليم 155 جاري اصدار شهادة الانجاز',
                                                '4' => 'اعداد مستخلص الدفعة الجزئية الاولي وجاري الصرف',
                                                '5' => 'تم صرف مستخلص الدفعة الجزئية الاولي',
                                                '6' => 'اعداد مستخلص الدفعة الجزئية الثانية وجاري الصرف',
                                                '7' => 'تم الصرف وتم الانتهاء',
                                                '8' => 'تم اصدار شهادة الانجاز',
                                                '9' => 'تم الالغاء او تحويل امر العمل',
                                                '10' => 'تم اعداد المستخلص الكلي وجاري الصرف'
                                            ];
                                            $selectedStatus = request('execution_status');
                                        @endphp
                                        @foreach($statusLabels as $value => $label)
                                            <option value="{{ $value }}" {{ $selectedStatus == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="extract_number" class="form-label fw-bold">
                                        <i class="fas fa-file-invoice text-primary me-1"></i>
                                        رقم المستخلص
                                    </label>
                                    <input type="text" class="form-control" id="extract_number" name="extract_number" 
                                           value="{{ request('extract_number') }}" placeholder="ابحث برقم المستخلص...">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sort_by_date" class="form-label fw-bold">
                                        <i class="fas fa-sort text-primary me-1"></i>
                                        ترتيب التاريخ
                                    </label>
                                    <select class="form-select" id="sort_by_date" name="sort_by_date">
                                        <option value="">الترتيب الافتراضي</option>
                                        <option value="asc" {{ request('sort_by_date') == 'asc' ? 'selected' : '' }}>من الأقدم للأجدد</option>
                                        <option value="desc" {{ request('sort_by_date') == 'desc' ? 'selected' : '' }}>من الأجدد للأقدم</option>
                                    </select>
                                </div>
                            </div>
          
                            <!-- الصف الثالث - فلاتر التاريخ والقيمة -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="approval_date_from" class="form-label fw-bold">
                                        <i class="fas fa-calendar-alt text-primary me-1"></i>
                                        تاريخ الاعتماد من
                                    </label>
                                    <input type="date" class="form-control" id="approval_date_from" name="approval_date_from" 
                                        value="{{ request('approval_date_from') }}">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="approval_date_to" class="form-label fw-bold">
                                        <i class="fas fa-calendar-alt text-primary me-1"></i>
                                        تاريخ الاعتماد إلى
                                    </label>
                                    <input type="date" class="form-control" id="approval_date_to" name="approval_date_to" 
                                        value="{{ request('approval_date_to') }}">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="min_value" class="form-label fw-bold">
                                        <i class="fas fa-money-bill text-primary me-1"></i>
                                        قيمة أمر العمل من
                                    </label>
                                    <input type="number" class="form-control" id="min_value" name="min_value" 
                                        value="{{ request('min_value') }}" placeholder="أقل قيمة..." step="0.01">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="max_value" class="form-label fw-bold">
                                        <i class="fas fa-money-bill text-primary me-1"></i>
                                        قيمة أمر العمل إلى
                                    </label>
                                    <input type="number" class="form-control" id="max_value" name="max_value" 
                                        value="{{ request('max_value') }}" placeholder="أعلى قيمة..." step="0.01">
                                </div>
                            </div>
                            
                            <!-- أزرار البحث -->
                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-center gap-3">
                                    <button type="submit" class="btn btn-primary px-5" id="searchBtn">
                                        <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
                                        <i class="fas fa-search me-2"></i> بحث
                                    </button>
                                    
                                    <button type="button" class="btn btn-outline-primary px-4" onclick="toggleAdvancedFilters()">
                                        <i class="fas fa-sliders-h me-2"></i> فلاتر متقدمة
                                    </button>
                                    <button type="button" class="btn btn-outline-warning px-4" onclick="clearAllFilters()" id="clearAllBtn" style="display: none;">
                                        <i class="fas fa-eraser me-2"></i> مسح الكل
                                    </button>
                                </div>

                                <!-- عرض الفلاتر النشطة كـ badges -->
                                <div class="mt-3 text-center" id="activeFilters">
                                    @if(request()->hasAny(['order_number', 'work_type', 'subscriber_name', 'district', 'office', 'consultant_name', 'station_number', 'execution_status', 'approval_date_from', 'approval_date_to', 'min_value', 'max_value', 'sort_by_date']))
                                        <small class="text-muted">الفلاتر النشطة:</small><br>
                                        @if(request('order_number'))
                                            <span class="filter-badge">
                                                رقم أمر العمل: {{ request('order_number') }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('order_number')"></i>
                                            </span>
                                        @endif
                                        @if(request('work_type'))
                                            <span class="filter-badge">
                                                نوع العمل: {{ request('work_type') }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('work_type')"></i>
                                            </span>
                                        @endif
                                        @if(request('subscriber_name'))
                                            <span class="filter-badge">
                                                اسم المشترك: {{ request('subscriber_name') }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('subscriber_name')"></i>
                                            </span>
                                        @endif
                                        @if(request('district'))
                                            <span class="filter-badge">
                                                الحي: {{ request('district') }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('district')"></i>
                                            </span>
                                        @endif
                                        @if(request('office'))
                                            <span class="filter-badge">
                                                المكتب: {{ request('office') }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('office')"></i>
                                            </span>
                                        @endif
                                        @if(request('consultant_name'))
                                            <span class="filter-badge">
                                                الاستشاري: {{ request('consultant_name') }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('consultant_name')"></i>
                                            </span>
                                        @endif
                                        @if(request('station_number'))
                                            <span class="filter-badge">
                                                رقم المحطة: {{ request('station_number') }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('station_number')"></i>
                                            </span>
                                        @endif
                                        @if(request('execution_status'))
                                            @php
                                                $statusLabels = [
                                                    '1' => 'جاري العمل بالموقع',
                                                    '2' => 'تم التنفيذ بالموقع وجاري تسليم 155',
                                                    '3' => 'تم تسليم 155 جاري اصدار شهادة الانجاز',
                                                    '4' => 'اعداد مستخلص الدفعة الجزئية الاولي وجاري الصرف',
                                                    '5' => 'تم صرف مستخلص الدفعة الجزئية الاولي',
                                                    '6' => 'اعداد مستخلص الدفعة الجزئية الثانية وجاري الصرف',
                                                    '7' => 'تم الصرف وتم الانتهاء',
                                                    '8' => 'تم اصدار شهادة الانجاز',
                                                    '9' => 'تم الالغاء او تحويل امر العمل',
                                                    '10' => 'تم اعداد المستخلص الكلي وجاري الصرف'
                                                ];
                                            @endphp
                                            <span class="filter-badge">
                                                حالة التنفيذ: {{ $statusLabels[request('execution_status')] ?? request('execution_status') }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('execution_status')"></i>
                                            </span>
                                        @endif
                                        @if(request('extract_number'))
                                            <span class="filter-badge">
                                                رقم المستخلص: {{ request('extract_number') }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('extract_number')"></i>
                                            </span>
                                        @endif
                                        @if(request('approval_date_from'))
                                            <span class="filter-badge">
                                                من تاريخ: {{ request('approval_date_from') }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('approval_date_from')"></i>
                                            </span>
                                        @endif
                                        @if(request('approval_date_to'))
                                            <span class="filter-badge">
                                                إلى تاريخ: {{ request('approval_date_to') }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('approval_date_to')"></i>
                                            </span>
                                        @endif
                                        @if(request('min_value'))
                                            <span class="filter-badge">
                                                قيمة أدنى: {{ number_format(request('min_value'), 2) }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('min_value')"></i>
                                            </span>
                                        @endif
                                        @if(request('max_value'))
                                            <span class="filter-badge">
                                                قيمة أعلى: {{ number_format(request('max_value'), 2) }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('max_value')"></i>
                                            </span>
                                        @endif
                                        @if(request('sort_by_date'))
                                            <span class="filter-badge">
                                                ترتيب التاريخ: {{ request('sort_by_date') == 'asc' ? 'من الأقدم للأجدد' : 'من الأجدد للأقدم' }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('sort_by_date')"></i>
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>

                   عدد النتائج وعناصر التحكم 
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <div class="text-muted mb-2 mb-md-0">
                            <i class="fas fa-list me-1"></i>
                            عدد النتائج: {{ $workOrders->total() }} | عرض {{ $workOrders->firstItem() ?? 0 }} - {{ $workOrders->lastItem() ?? 0 }} من {{ $workOrders->total() }}
                        </div>
                        
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            @if(request('order_number') || request('work_type') || request('execution_status') || request('subscriber_name') || request('district') || request('office') || request('consultant_name') || request('station_number') || request('approval_date_from') || request('approval_date_to') || request('min_value') || request('max_value') || request('sort_by_date'))
                                <div class="text-muted">
                                    <i class="fas fa-filter me-1"></i>
                                    تم تطبيق الفلتر
                                    <small class="text-primary ms-2">
                                        ({{ collect([request('order_number'), request('work_type'), request('execution_status'), request('subscriber_name'), request('district'), request('office'), request('consultant_name'), request('station_number'), request('approval_date_from'), request('approval_date_to'), request('min_value'), request('max_value'), request('sort_by_date')])->filter()->count() }} فلتر نشط)
                                    </small>
                                </div>
                            @endif
                            
                            <!-- اختيار عدد النتائج في الصفحة -->
                            <div class="d-flex align-items-center">
                                <label for="per_page" class="form-label me-2 mb-0 text-muted">عرض:</label>
                                <select class="form-select form-select-sm" id="per_page" onchange="changePerPage(this.value)" style="width: auto;">
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    <option value="300" {{ request('per_page') == 300 ? 'selected' : '' }}>300</option>
                                    <option value="1000" {{ request('per_page') == 1000 ? 'selected' : '' }}>1000</option>
                                </select>
                                <span class="text-muted ms-2">نتيجة</span>
                            </div>
                        </div>
                    </div>

                    <!-- مؤشر البحث -->
                    <div id="searchLoader" class="text-center py-4 d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">جاري البحث...</span>
                        </div>
                        <p class="mt-2 text-muted">جاري البحث في أوامر العمل...</p>
                    </div>

                    <!-- عرض النتائج -->
                    <div class="table-responsive" id="resultsTable">
                        <table class="table table-bordered table-hover table-striped"
                               style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 10px; overflow: hidden;">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 4%;">
                                        <i class="fas fa-hashtag me-1"></i>
                                        #
                                    </th>
                                    <th class="text-center" style="width: 8%;">
                                        <i class="fas fa-file-contract me-1"></i>
                                        رقم أمر العمل
                                    </th>
                                    <th class="text-center" style="width: 6%;">
                                        <i class="fas fa-code me-1"></i>
                                        رقم نوع العمل
                                    </th>
                                    <th class="text-center" style="width: 12%;">
                                        <i class="fas fa-clipboard-list me-1"></i>
                                        وصف العمل
                                    </th>
                                    <th class="text-center" style="width: 10%;">
                                        <i class="fas fa-user me-1"></i>
                                        اسم المشترك
                                    </th>
                                    <th class="text-center" style="width: 8%;">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        الحي
                                    </th>
                                    <th class="text-center" style="width: 8%;">
                                        <i class="fas fa-building me-1"></i>
                                        المكتب
                                    </th>
                                    <th class="text-center" style="width: 10%;">
                                        <i class="fas fa-user-tie me-1"></i>
                                        اسم الاستشاري
                                    </th>
                                    <th class="text-center" style="width: 8%;">
                                        <i class="fas fa-broadcast-tower me-1"></i>
                                        رقم المحطة
                                    </th>
                                    <th class="text-center" style="width: 10%;">
                                        <i class="fas fa-calendar-check me-1"></i>
                                        تاريخ الاعتماد
                                        <br>
                                        <small><i class="fas fa-clock me-1"></i>مدة التنفيذ</small>
                                    </th>
                                    <th class="text-center" style="width: 10%;">
                                        <i class="fas fa-tasks me-1"></i>
                                        حالة التنفيذ
                                    </th>
                                    <th class="text-center" style="width: 8%;">
                                        <i class="fas fa-receipt me-1"></i>
                                        رقم المستخلص
                                    </th>
                                    <th class="text-center" style="width: 12%;">
                                        <i class="fas fa-money-bill-wave me-1"></i>
                                        قيمة أمر العمل المبدئية
                                        <br>
                                        <small>غير شامل الاستشاري</small>
                                    </th>
                                    <th class="text-center" style="width: 8%;">
                                        <i class="fas fa-cogs me-1"></i>
                                        الإجراءات
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="workOrdersTableBody">
                                @forelse ($workOrders as $workOrder)
                                    <tr class="work-order-row" data-id="{{ $workOrder->id }}" data-execution-status="{{ $workOrder->execution_status }}">
                                        <td class="text-center fw-bold text-primary">
                                            <span class="badge bg-light text-dark">{{ $loop->iteration + ($workOrders->currentPage() - 1) * $workOrders->perPage() }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-bold text-primary">{{ $workOrder->order_number }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $workOrder->work_type }}</span>
                                        </td>
                                        <td>
                                            @switch($workOrder->work_type)
                                                @case('409')
                                                 -ازالة-نقل شبكة على المشترك
                                                    @break
                                                @case('408')
                                                 - ازاله عداد على المشترك
                                                    @break
                                                @case('460')
                                                 - استبدال شبكات
                                                    @break
                                                @case('901')
                                                 - اضافة عداد طاقة شمسية
                                                    @break
                                                @case('440')
                                                 - الرفع المساحي
                                                    @break
                                                @case('410')
                                                 - انشاء محطة/محول لمشترك/مشتركين
                                                    @break
                                                @case('801')
                                                 - تركيب عداد ايصال سريع
                                                    @break
                                                @case('804')
                                                 - تركيب محطة ش ارضية VM ايصال سريع
                                                    @break
                                                @case('805')
                                                 - تركيب محطة ش هوائية VM ايصال سريع
                                                    @break
                                                @case('480')
                                                     - (تسليم مفتاح) تمويل خارجي
                                                    @break
                                                @case('441')
                                                     - تعزيز شبكة ارضية ومحطات
                                                    @break
                                                @case('442')
                                                     - تعزيز شبكة هوائية ومحطات
                                                    @break
                                                @case('802')
                                                     - شبكة ارضية VL ايصال سريع
                                                    @break
                                                @case('803')
                                                     - شبكة هوائية VL ايصال سريع
                                                    @break
                                                @case('402')
                                                     - توصيل عداد بحفرية شبكة ارضيه
                                                    @break
                                                @case('401')
                                                     - (عداد بدون حفرية) أرضي/هوائي
                                                    @break
                                                @case('404')
                                                     - عداد بمحطة شبكة ارضية VM
                                                    @break
                                                @case('405')
                                                     - توصيل عداد بمحطة شبكة هوائية VM
                                                    @break
                                                @case('430')
                                                     - مخططات منح وزارة البلدية
                                                    @break
                                                @case('450')
                                                     - مشاريع ربط محطات التحويل
                                                    @break
                                                @case('403')
                                                     - توصيل عداد شبكة هوائية VL
                                                    @break
                                                @case('806')
                                                     - ايصال وزارة الاسكان جهد منخفض
                                                    @break
                                                @case('444')
                                                     - تحويل الشبكه من هوائي الي ارضي
                                                    @break
                                                @case('111')
                                                     -   Mv- طوارئ ضغط متوسط
                                                    @break
                                                @case('222')
                                                     -    Lv - طوارئ ض منخفض
                                                    @break
                                                @case('333')
                                                     -     Oh  - طوارئ هوائي
                                                    @break
                                                @default
                                                    {{ $workOrder->work_type }}
                                            @endswitch
                                        </td>
                                        <td>{{ $workOrder->subscriber_name }}</td>
                                        <td>{{ $workOrder->district }}</td>
                                        <td>
                                            @switch($workOrder->office)
                                                @case('خريص')
                                                    <span class="badge bg-primary">خريص</span>
                                                    @break
                                                @case('الشرق')
                                                    <span class="badge bg-success">الشرق</span>
                                                    @break
                                                @case('الشمال')
                                                    <span class="badge bg-info">الشمال</span>
                                                    @break
                                                @case('الجنوب')
                                                    <span class="badge bg-warning">الجنوب</span>
                                                    @break
                                                @case('الدرعية')
                                                    <span class="badge bg-secondary">الدرعية</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-light text-dark">{{ $workOrder->office ?? 'غير محدد' }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $workOrder->consultant_name }}</td>
                                        <td>{{ $workOrder->station_number }}</td>
                                        <td>
                                            <div class="approval-date-container">
                                                <div class="date-section mb-1">
                                                    <small class="text-muted"></small><br>
                                                    <span class="date-value">{{ date('Y-m-d', strtotime($workOrder->approval_date)) }}</span>
                                                </div>
                                                @php
                                                    $remainingDays = $workOrder->manual_days ?? 0;
                                                @endphp
                                                <div class="countdown-section">
                                                    <small class="text-muted d-block"></small>
                                                    <span class="badge countdown-badge" 
                                                          data-start="{{ $workOrder->manual_days }}" 
                                                          data-approval-date="{{ $workOrder->approval_date }}"
                                                          data-procedure-155-date="{{ $workOrder->procedure_155_delivery_date }}"
                                                          data-work-order="{{ $workOrder->id }}"
                                                          data-execution-status="{{ $workOrder->execution_status }}">
                                                        <i class="fas fa-clock me-1"></i>
                                                        <span class="days-count">-</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                @switch($workOrder->execution_status)
                                                    @case('1')
                                                        <span class="badge" style="background-color:rgb(228, 196, 14)">جاري العمل بالموقع</span>
                                                        @break
                                                    @case('2')
                                                        <span class="badge" style="background-color:rgb(112, 68, 2)">تم التنفيذ بالموقع وجاري تسليم 155</span>
                                                        @break
                                                    @case('3')
                                                        <span class="badge" style="background-color:rgb(4, 163, 226); color: white">تم تسليم 155 جاري اصدار شهادة الانجاز</span>
                                                        @break
                                                    @case('4')
                                                        <span class="badge" style="background-color:rgb(86, 168, 110)">اعداد مستخلص الدفعة الجزئية الاولي وجاري الصرف</span>
                                                        @break
                                                    @case('5')
                                                        <span class="badge" style="background-color:rgb(39, 138, 83)">تم صرف مستخلص الدفعة الجزئية الاولي</span>
                                                        @break
                                                    @case('6')
                                                        <span class="badge" style="background-color:rgb(1, 128, 64)">اعداد مستخلص الدفعة الجزئية الثانية وجاري الصرف</span>
                                                        @break
                                                    @case('7')
                                                        <span class="badge" style="background-color:rgb(1, 119, 31)">تم الصرف وتم الانتهاء</span>
                                                        @break
                                                    @case('8')
                                                        <span class="badge" style="background-color:rgb(0, 66, 0)">تم اصدار شهادة الانجاز</span>
                                                        @break
                                                    @case('9')
                                                        <span class="badge" style="background-color:rgb(220, 53, 69)">تم الالغاء او تحويل امر العمل</span>
                                                        @break
                                                    @case('10')
                                                        <span class="badge" style="background-color:rgb(13, 110, 253)">تم اعداد المستخلص الكلي وجاري الصرف</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $workOrder->execution_status }}</span>
                                                @endswitch
                                                @if($workOrder->execution_status_date)
                                                    <small class="text-muted mt-1" style="font-size: 0.75rem;">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        {{ $workOrder->execution_status_date->format('Y-m-d') }}
                                                        <br>
                                                        <i class="fas fa-clock me-1"></i>
                                                        {{ $workOrder->execution_status_date->format('H:i') }}
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($workOrder->extract_number)
                                                <span class="badge bg-info">{{ $workOrder->extract_number }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($workOrder->order_value_without_consultant ?? 0, 2) }} ريال</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-sm btn-info">عرض</a>
                                                <a href="{{ route('admin.work-orders.edit', $workOrder) }}" class="btn btn-sm btn-primary">تعديل</a>
                                                
                                                @if(auth()->user()->is_admin)
                                                    <form action="{{ route('admin.work-orders.destroy', $workOrder) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا العنصر؟');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="noResultsRow">
                                        <td colspan="13" class="text-center">لا توجد أوامر عمل لهذا المشروع</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination and Per Page Selection -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="d-flex align-items-center">
                            <select class="form-select form-select-sm me-2" id="perPage" onchange="changePerPage(this.value)" style="width: auto;">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 نتائج</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 نتيجة</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 نتيجة</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 نتيجة</option>
                                <option value="300" {{ request('per_page') == 300 ? 'selected' : '' }}>300 نتيجة</option>
                                <option value="1000" {{ request('per_page') == 1000 ? 'selected' : '' }}>1000 نتيجة</option>
                            </select>
                            <span class="text-muted small">
                                عرض {{ $workOrders->firstItem() ?? 0 }} إلى {{ $workOrders->lastItem() ?? 0 }} من {{ $workOrders->total() }} نتيجة
                            </span>
                        </div>
                        <div class="pagination-container">
                            {{ $workOrders->appends(request()->query())->links() }}
                        </div>
                    </div>

                    <script>
                    function changePerPage(value) {
                        let url = new URL(window.location.href);
                        let params = new URLSearchParams(url.search);
                        params.set('per_page', value);
                        params.set('page', '1');
                        window.location.href = `${url.pathname}?${params.toString()}`;
                    }
                    </script>

                    <style>
                    .pagination {
                        margin: 0;
                        display: flex;
                        padding-right: 0;
                    }
                    .page-link {
                        padding: 0.375rem 0.75rem;
                        font-size: 0.875rem;
                        border-radius: 0.25rem;
                        margin: 0 2px;
                    }
                    .page-item.active .page-link {
                        background-color: #0d6efd;
                        border-color: #0d6efd;
                    }
                    .page-link:hover {
                        background-color: #e9ecef;
                        border-color: #dee2e6;
                    }
                    .form-select-sm {
                        font-size: 0.875rem;
                        padding: 0.25rem 2rem 0.25rem 0.5rem;
                    }
                    .pagination-container {
                        direction: rtl;
                    }
                    .pagination-container .pagination {
                        justify-content: center;
                    }
                    </style>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    /* تنسيق عمود تاريخ الاعتماد */
    .approval-date-container {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .date-section {
        display: flex;
        align-items: center;
    }

    .date-value {
        color: #666;
        font-size: 0.9rem;
    }

    .countdown-section {
        display: flex;
    }

    .countdown-badge {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
        border-radius: 4px;
    }

    .countdown-badge .days-count {
        font-weight: 600;
        background-color: rgba(130, 192, 211, 0.84);
        padding: 0.1rem 0.3rem;
        border-radius: 3px;
        margin: 0 0.2rem;
    }

    .countdown-badge.bg-success {
        background-color:rgb(8, 223, 58);
    }

    .countdown-badge.bg-danger {
        background-color:rgb(201, 0, 20);
    }

    .countdown-badge.bg-primary {
        background-color: #6c757d;
    }

    /* تنسيق عام للجدول */
    .table th, .table td {
        text-align: right;
    }
    
    .btn-group {
        display: flex;
    }
    
    .btn-group .btn {
        margin-left: 5px;
    }
    
    /* تحسين فلاتر البحث */
    .form-label {
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 8px 12px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    .advanced-filters {
        display: none;
        animation: slideDown 0.3s ease;
    }
    
    .advanced-filters.show {
        display: block;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .filter-badge {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.8rem;
        display: inline-block;
        margin: 2px;
    }
    
    .clear-filter {
        cursor: pointer;
        margin-left: 5px;
        opacity: 0.7;
    }
    
    .clear-filter:hover {
        opacity: 1;
    }
    
    /* تحسين تجربة التفاعل مع الحقول */
    .form-group.focused .form-label {
        color: #0d6efd;
        font-weight: 600;
    }
    
    .form-group.focused .form-control,
    .form-group.focused .form-select {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    /* تحسين مظهر الأزرار */
    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    /* تحسين مظهر البحث النشط */
    .search-active {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(13, 110, 253, 0); }
        100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); }
    }
    
    /* تحسين مظهر الجدول */
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
        transform: scale(1.001);
        transition: all 0.2s ease;
    }
    
    /* تأثيرات البحث المباشر */
    .search-result {
        background-color: rgba(40, 167, 69, 0.1) !important;
        border-left: 4px solid #28a745;
        animation: highlight 0.5s ease-in-out;
    }
    
    @keyframes highlight {
        0% { 
            background-color: rgba(255, 193, 7, 0.3);
            transform: scale(1.01);
        }
        100% { 
            background-color: rgba(40, 167, 69, 0.1);
            transform: scale(1);
        }
    }
    
    /* مؤشر التحميل */
    #searchLoader {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        border: 2px dashed #007bff;
    }
    
    .opacity-50 {
        opacity: 0.5;
        transition: opacity 0.3s ease;
    }
    
    /* تحسين الفلاتر النشطة */
    .form-control:not(:placeholder-shown),
    .form-select:not([value=""]) {
        border-left: 3px solid #28a745;
        background-color: rgba(40, 167, 69, 0.05);
    }
    
    /* تأثيرات النتائج المخفية */
    .work-order-row[style*="display: none"] {
        opacity: 0;
        transform: translateX(-10px);
        transition: all 0.3s ease;
    }
    
    /* تحسين مظهر النتائج الظاهرة */
    .work-order-row {
        transition: all 0.3s ease;
    }
    
    .work-order-row:not([style*="display: none"]) {
        opacity: 1;
        transform: translateX(0);
    }
    
    /* تحسين مظهر الجدول عند البحث */
    .table-responsive.searching {
        position: relative;
    }
    
    .table-responsive.searching::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 40%, rgba(0,123,255,0.1) 50%, transparent 60%);
        animation: searching 1.5s infinite;
        pointer-events: none;
        z-index: 1;
    }
    
    @keyframes searching {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    /* تحسين مظهر عداد النتائج */
    .results-counter {
        font-weight: 600;
        color: #495057;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 8px 16px;
        border-radius: 20px;
        border: 1px solid #dee2e6;
    }
    
    /* تحسين أزرار الفلاتر المتقدمة */
    .advanced-filters {
        border-top: 2px dashed #007bff;
        padding-top: 1rem;
        margin-top: 1rem;
    }
    
    .advanced-filters.show {
        animation: slideDown 0.5s ease;
        border-top-color: #28a745;
    }
    
    /* تحسين زر مسح الكل */
    #clearAllBtn {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    #clearAllBtn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(255, 193, 7, 0.3);
    }
    
    #clearAllBtn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }
    
    #clearAllBtn:hover::before {
        left: 100%;
    }
    
    /* تنسيق عرض تاريخ الاعتماد ومدة التنفيذ */
    .approval-date-container {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 140px;
    }
    
    .date-section {
        text-align: center;
        padding: 4px 6px;
        background-color: #f8f9fa;
        border-radius: 4px;
        border: 1px solid #e9ecef;
        font-size: 0.9rem;
    }
    
    .countdown-section {
        text-align: center;
    }
    
    .countdown-badge {
        font-size: 0.85rem;
        padding: 4px 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .countdown-badge.bg-warning {
        animation: pulse-warning 2s infinite;
    }
    
    .countdown-badge.bg-danger {
        animation: pulse-danger 2s infinite;
    }
    
    @keyframes pulse-warning {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    @keyframes pulse-danger {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }
    
    .date-value {
        font-weight: 600;
        color: #495057;
    }

    /* تنسيق أزرار التنقل */
    .pagination {
        gap: 5px;
    }

    .page-link {
        border-radius: 4px !important;
        color: #4e73df;
        padding: 8px 16px;
        font-weight: 500;
    }

    .page-item.active .page-link {
        background-color: #4e73df;
        border-color: #4e73df;
        color: white;
        box-shadow: 0 2px 4px rgba(78, 115, 223, 0.3);
    }

    .page-item.disabled .page-link {
        background-color: #f8f9fc;
        border-color: #e3e6f0;
        color: #858796;
    }

    /* تحديد الصفحة الحالية في Showing */
    .showing-text {
        color:rgb(128, 20, 20);
    }

    .showing-text .current-page {
        color: #4e73df;
        font-weight: bold;
        padding: 2px 6px;
        background-color: #f8f9fc;
        border-radius: 4px;
        margin: 0 2px;
    }
</style>

<script>
function toggleAdvancedFilters() {
    const advancedFilters = document.querySelectorAll('.advanced-filters');
    advancedFilters.forEach(filter => {
        filter.classList.toggle('show');
    });
    
    const button = event.target;
    const icon = button.querySelector('i');
    
    if (icon.classList.contains('fa-sliders-h')) {
        icon.classList.remove('fa-sliders-h');
        icon.classList.add('fa-times');
        button.innerHTML = '<i class="fas fa-times me-2"></i> إخفاء الفلاتر المتقدمة';
    } else {
        icon.classList.remove('fa-times');
        icon.classList.add('fa-sliders-h');
        button.innerHTML = '<i class="fas fa-sliders-h me-2"></i> فلاتر متقدمة';
    }
}

// تنظيف الفلاتر الفردية
function clearFilter(filterName) {
    const url = new URL(window.location);
    url.searchParams.delete(filterName);
    // Also delete array version for execution_status
    if (filterName === 'execution_status') {
        url.searchParams.delete('execution_status[]');
    }
    window.location.href = url.toString();
}

// تغيير عدد النتائج في الصفحة
function changePerPage(perPage) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', perPage);
    url.searchParams.delete('page'); // إعادة تعيين رقم الصفحة
    window.location.href = url.toString();
}

// متغيرات البحث المباشر
let searchTimeout;
let allWorkOrders = [];

// تخزين جميع أوامر العمل في الذاكرة للبحث السريع
function storeWorkOrders() {
    const rows = document.querySelectorAll('.work-order-row');
    allWorkOrders = Array.from(rows).map(row => ({
        element: row,
        id: row.dataset.id,
        orderNumber: row.cells[1].textContent.trim(),
        workType: row.cells[2].textContent.trim(),
        workDescription: row.cells[3].textContent.trim(),
        subscriberName: row.cells[4].textContent.trim(),
        district: row.cells[5].textContent.trim(),
        office: row.cells[6].textContent.trim(),
        consultantName: row.cells[7].textContent.trim(),
        stationNumber: row.cells[8].textContent.trim(),
        approvalDate: row.cells[9].querySelector('[data-approval-date]')?.getAttribute('data-approval-date') || row.cells[9].querySelector('.date-value')?.textContent.trim(),
        executionStatus: row.dataset.executionStatus,
        orderValue: row.cells[11].textContent.trim()
    }));
}

// البحث المباشر في أوامر العمل
function liveSearch() {
    const filters = {
        orderNumber: document.getElementById('order_number').value.toLowerCase(),
        workType: document.getElementById('work_type').value,
        subscriberName: document.getElementById('subscriber_name').value.toLowerCase(),
        district: document.getElementById('district').value.toLowerCase(),
        office: document.getElementById('office').value,
        consultantName: document.getElementById('consultant_name').value.toLowerCase(),
        stationNumber: document.getElementById('station_number').value.toLowerCase(),
        executionStatus: document.getElementById('execution_status').value,
        approvalDateFrom: document.getElementById('approval_date_from').value,
        approvalDateTo: document.getElementById('approval_date_to').value,
        minValue: parseFloat(document.getElementById('min_value').value) || 0,
        maxValue: parseFloat(document.getElementById('max_value').value) || Infinity
    };
    
    // إظهار مؤشر التحميل
    document.getElementById('searchLoader').classList.remove('d-none');
    document.getElementById('resultsTable').classList.add('opacity-50');
    document.getElementById('resultsTable').classList.add('searching');
    
    // تأخير البحث لتحسين الأداء
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        performSearch(filters);
    }, 300);
}

// تنفيذ البحث وإظهار النتائج
function performSearch(filters) {
    let visibleCount = 0;
    
    allWorkOrders.forEach(workOrder => {
        let isVisible = true;
        
        // فلتر رقم أمر العمل
        if (filters.orderNumber && !workOrder.orderNumber.toLowerCase().includes(filters.orderNumber)) {
            isVisible = false;
        }
        
        // فلتر نوع العمل
        if (filters.workType && workOrder.workType !== filters.workType) {
            isVisible = false;
        }
        
        // فلتر اسم المشترك
        if (filters.subscriberName && !workOrder.subscriberName.toLowerCase().includes(filters.subscriberName)) {
            isVisible = false;
        }
        
        // فلتر الحي
        if (filters.district && !workOrder.district.toLowerCase().includes(filters.district)) {
            isVisible = false;
        }
        
        // فلتر المكتب
        if (filters.office && !workOrder.office.includes(filters.office)) {
            isVisible = false;
        }
        
        // فلتر اسم الاستشاري
        if (filters.consultantName && !workOrder.consultantName.toLowerCase().includes(filters.consultantName)) {
            isVisible = false;
        }
        
        // فلتر رقم المحطة
        if (filters.stationNumber && !workOrder.stationNumber.toLowerCase().includes(filters.stationNumber)) {
            isVisible = false;
        }
        
                    // فلتر حالة التنفيذ
                        if (filters.executionStatus) {
                            const status = workOrder.executionStatus.toString();
                            if (status !== filters.executionStatus) {
                                isVisible = false;
                            }
                        }
        
        // فلتر التاريخ
        if (filters.approvalDateFrom || filters.approvalDateTo) {
            const approvalDate = new Date(workOrder.approvalDate);
            
            if (filters.approvalDateFrom) {
                const fromDate = new Date(filters.approvalDateFrom);
                if (approvalDate < fromDate) {
                    isVisible = false;
                }
            }
            
            if (filters.approvalDateTo) {
                const toDate = new Date(filters.approvalDateTo);
                if (approvalDate > toDate) {
                    isVisible = false;
                }
            }
        }
        
        // فلتر القيمة
        const orderValueMatch = workOrder.orderValue.match(/[\d,]+\.?\d*/);
        if (orderValueMatch) {
            const orderValue = parseFloat(orderValueMatch[0].replace(/,/g, ''));
            if (orderValue < filters.minValue || orderValue > filters.maxValue) {
                isVisible = false;
            }
        }
        
        // إظهار/إخفاء الصف
        if (isVisible) {
            workOrder.element.style.display = '';
            workOrder.element.classList.add('search-result');
            visibleCount++;
        } else {
            workOrder.element.style.display = 'none';
            workOrder.element.classList.remove('search-result');
        }
    });
    
    // إخفاء مؤشر التحميل
    document.getElementById('searchLoader').classList.add('d-none');
    document.getElementById('resultsTable').classList.remove('opacity-50');
    document.getElementById('resultsTable').classList.remove('searching');
    
    // إظهار/إخفاء رسالة "لا توجد نتائج"
    const noResultsRow = document.getElementById('noResultsRow');
    if (visibleCount === 0) {
        if (!noResultsRow) {
            const tbody = document.getElementById('workOrdersTableBody');
            const newRow = document.createElement('tr');
            newRow.id = 'noResultsRow';
            newRow.innerHTML = '<td colspan="13" class="text-center">لا توجد نتائج مطابقة للبحث</td>';
            tbody.appendChild(newRow);
        } else {
            noResultsRow.style.display = '';
            noResultsRow.innerHTML = '<td colspan="13" class="text-center">لا توجد نتائج مطابقة للبحث</td>';
        }
    } else {
        if (noResultsRow) {
            noResultsRow.style.display = 'none';
        }
    }
    
    // تحديث عداد النتائج
    updateResultsCounter(visibleCount);
    
    // إظهار/إخفاء زر "مسح الكل"
    const clearAllBtn = document.getElementById('clearAllBtn');
    if (hasActiveFilters()) {
        clearAllBtn.style.display = 'inline-block';
    } else {
        clearAllBtn.style.display = 'none';
    }
}

// تحديث عداد النتائج
function updateResultsCounter(visibleCount) {
    const counterElement = document.querySelector('.text-muted');
    if (counterElement) {
        const totalCount = allWorkOrders.length;
        counterElement.innerHTML = `<i class="fas fa-list me-1"></i>عدد النتائج: ${visibleCount} من ${totalCount}`;
        counterElement.classList.add('results-counter');
        
        // إضافة تأثير بصري عند تحديث العداد
        counterElement.style.animation = 'none';
        setTimeout(() => {
            counterElement.style.animation = 'pulse 0.5s ease-in-out';
        }, 10);
    }
}

// إعادة تعيين البحث
function resetSearch() {
    // مسح جميع حقول البحث
    document.querySelectorAll('input, select').forEach(input => {
        if (input.name !== 'project') {
            input.value = '';
        }
    });
    
    // إظهار جميع الصفوف
    allWorkOrders.forEach(workOrder => {
        workOrder.element.style.display = '';
        workOrder.element.classList.remove('search-result');
    });
    
    // إخفاء رسالة "لا توجد نتائج"
    const noResultsRow = document.getElementById('noResultsRow');
    if (noResultsRow) {
        noResultsRow.style.display = 'none';
    }
    
    // إخفاء زر "مسح الكل"
    document.getElementById('clearAllBtn').style.display = 'none';
    
    // تحديث عداد النتائج
    updateResultsCounter(allWorkOrders.length);
}

// مسح جميع الفلاتر (دالة منفصلة لزر "مسح الكل")
function clearAllFilters() {
    resetSearch();
}

// فحص إذا كان هناك فلاتر نشطة
function hasActiveFilters() {
    const inputs = document.querySelectorAll('#order_number, #work_type, #subscriber_name, #district, #office, #consultant_name, #station_number, #execution_status, #approval_date_from, #approval_date_to, #min_value, #max_value, #sort_by_date');
    
    return Array.from(inputs).some(input => {
        return input.value && input.value.trim() !== '';
    });
}

// التعامل مع مؤشر التحميل ووظائف الفلاتر
document.addEventListener('DOMContentLoaded', function() {
    // تخزين أوامر العمل في الذاكرة
    storeWorkOrders();
    
    // إضافة حدث لإظهار/إخفاء الفلاتر المتقدمة عند التحميل
    const dateFilters = document.querySelectorAll('[name="approval_date_from"], [name="approval_date_to"], [name="min_value"], [name="max_value"]');
    dateFilters.forEach(filter => {
        filter.closest('.col-md-3').classList.add('advanced-filters');
    });
    
    // إضافة أحداث البحث المباشر لجميع حقول الفلتر
    const searchInputs = document.querySelectorAll('#order_number, #work_type, #subscriber_name, #district, #office, #consultant_name, #station_number, #execution_status, #approval_date_from, #approval_date_to, #min_value, #max_value, #sort_by_date');
    searchInputs.forEach(input => {
        input.addEventListener('input', liveSearch);
        input.addEventListener('change', liveSearch);
    });
    
    // منع إرسال النموذج إذا كان البحث المباشر نشطاً
    const searchForm = document.querySelector('form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            liveSearch();
        });
    }
    
    // تحسين تجربة المستخدم مع الأحداث
    const inputs = document.querySelectorAll('input[type="text"], input[type="date"], input[type="number"]');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
    
    // إضافة tooltip للفلاتر
    const labels = document.querySelectorAll('.form-label');
    labels.forEach(label => {
        const icon = label.querySelector('i');
        if (icon) {
            label.setAttribute('title', 'اضغط للتركيز على هذا الحقل');
            label.style.cursor = 'pointer';
            label.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input, select');
                if (input) input.focus();
            });
        }
    });
});

// تحسين تجربة المستخدم مع Select2 للقوائم المنسدلة الطويلة
$(document).ready(function() {
    if (typeof $.fn.select2 !== 'undefined') {
        $('#work_type').select2({
            placeholder: 'اختر نوع العمل...',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return 'لا توجد نتائج';
                },
                searching: function() {
                    return 'جاري البحث...';
                }
            }
        });
    }
});
</script>
@endsection 