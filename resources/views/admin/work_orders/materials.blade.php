@extends('layouts.app')

@section('title', 'إدارة المواد')
@section('header', 'إدارة المواد')

@section('content')
<style>
    .materials-container {
        background: linear-gradient(135deg,rgb(236, 237, 243) 0%,rgb(255, 255, 255) 100%);
        min-height: 100vh;
        padding: 20px 0;
    }
    
    .card-modern {
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        margin-bottom: 30px;
    }
    
    .card-header-modern {
        background: linear-gradient(45deg, #2196F3, #21CBF3);
        color: white;
        border-radius: 20px 20px 0 0;
        padding: 25px;
        border: none;
    }
    
    .card-header-modern h3 {
        margin: 0;
        font-weight: 600;
        font-size: 1.4rem;
    }
    
    .form-control-modern {
        border: 2px solid #e3f2fd;
        border-radius: 15px;
        padding: 12px 18px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
    }
    
    .form-control-modern:focus {
        border-color: #2196F3;
        box-shadow: 0 0 20px rgba(33, 150, 243, 0.2);
        background: white;
        transform: translateY(-2px);
    }
    
    .btn-modern {
        border-radius: 25px;
        padding: 12px 30px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }
    
    .btn-primary-modern {
        background: linear-gradient(45deg, #2196F3, #21CBF3);
        color: white;
    }
    
    .btn-primary-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(33, 150, 243, 0.3);
        color: white;
    }
    
    .btn-success-modern {
        background: linear-gradient(45deg,rgb(82, 78, 78),rgb(91, 94, 91));
        color: white;
    }
    
    .btn-success-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(76, 175, 80, 0.3);
        color: white;
    }
    
    .btn-warning-modern {
        background: linear-gradient(45deg, #FF9800, #FFB74D);
        color: white;
    }
    
    .btn-warning-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(255, 152, 0, 0.3);
        color: white;
    }
    
    .btn-danger-modern {
        background: linear-gradient(45deg, #f44336, #ef5350);
        color: white;
    }
    
    .btn-danger-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(244, 67, 54, 0.3);
        color: white;
    }
    
    .table-modern {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        background: white;
    }
    
    .table-modern thead {
        background: linear-gradient(45deg, #2196F3, #21CBF3);
        color: white;
    }
    
    .table-modern th {
        border: none;
        padding: 18px 15px;
        font-weight: 600;
        text-align: center;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .table-modern td {
        border: none;
        padding: 15px;
        vertical-align: middle;
        text-align: center;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .table-modern tbody tr:hover {
        background: rgba(33, 150, 243, 0.05);
        transform: scale(1.01);
        transition: all 0.3s ease;
    }
    
    .alert-modern {
        border: none;
        border-radius: 15px;
        padding: 20px 25px;
        margin-bottom: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .alert-success-modern {
        background: linear-gradient(45deg, #d4edda, #c3e6cb);
        color: #155724;
        border-left: 5px solid #28a745;
    }
    
    .alert-danger-modern {
        background: linear-gradient(45deg, #f8d7da, #f5c6cb);
        color: #721c24;
        border-left: 5px solid #dc3545;
    }
    
    .statistics-card {
        background: linear-gradient(45deg,rgb(80, 83, 94),rgb(65, 61, 70));
        color: white;
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        margin-bottom: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .statistics-card h4 {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 10px;
    }
    
    .badge-modern {
        border-radius: 25px;
        padding: 8px 15px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .section-divider {
        height: 3px;
        background: linear-gradient(45deg, #2196F3, #21CBF3);
        border-radius: 5px;
        margin: 30px 0;
    }
    
    .floating-action {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
    }
    
    .floating-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(45deg, #2196F3, #21CBF3);
        color: white;
        border: none;
        font-size: 1.5rem;
        box-shadow: 0 10px 25px rgba(33, 150, 243, 0.3);
        transition: all 0.3s ease;
    }
    
    .floating-btn:hover {
        transform: translateY(-5px) scale(1.1);
        box-shadow: 0 15px 35px rgba(33, 150, 243, 0.4);
    }
    
    .form-section {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .required-field::after {
        content: " *";
        color: #dc3545;
        font-weight: bold;
    }
    
    .file-upload-area {
        border: 2px dashed #e3f2fd;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .file-upload-area:hover {
        border-color: #2196F3;
        background: rgba(33, 150, 243, 0.05);
    }

    .table-compact {
        font-size: 0.85rem;
    }

    .table-compact th,
    .table-compact td {
        padding: 0.4rem 0.3rem;
        vertical-align: middle;
        border-top: 1px solid #dee2e6;
    }

    .table-compact .btn-xs {
        padding: 0.2rem 0.4rem;
        font-size: 0.7rem;
        line-height: 1.2;
    }

    .badge-compact {
        font-size: 0.7rem;
        padding: 0.3rem 0.5rem;
    }

    .file-upload-area {
        border: 2px dashed #e3e6f0;
        border-radius: 0.35rem;
        padding: 1rem;
        text-align: center;
        transition: all 0.3s;
        background-color: #f8f9fc;
    }

    .file-upload-area:hover {
        border-color: #5a67d8;
        background-color: #f1f3ff;
    }

    .difference-positive {
        color: #e74c3c;
        font-weight: bold;
    }

    .difference-negative {
        color:rgb(70, 71, 71);
        font-weight: bold;
    }

    .difference-zero {
        color: #95a5a6;
    }

    .bg-purple {
        background-color: #9b59b6 !important;
    }

    .bg-teal {
        background-color:rgb(52, 61, 59) !important;
    }

    .table-compact .badge-compact {
        white-space: nowrap;
    }

    .bg-gradient-info {
        background: linear-gradient(45deg,rgb(58, 68, 70),rgb(74, 85, 87)) !important;
    }

    .bg-gradient-warning {
        background: linear-gradient(45deg,rgb(83, 81, 74),rgb(83, 81, 74)) !important;
    }

    .bg-gradient-danger {
        background: linear-gradient(45deg,rgb(94, 88, 88),rgb(58, 53, 54)) !important;
    }

    .bg-gradient-secondary {
        background: linear-gradient(45deg, #6c757d, #5a6268) !important;
    }

    .card {
        transition: transform 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .table-responsive {
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    .table tfoot {
        background-color: #f8f9fa !important;
        border-top: 2px solid #dee2e6;
    }
    
    /* فلتر البحث */
    .materials-filter {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 1px solid #e9ecef;
        border-radius: 15px;
        transition: all 0.3s ease;
    }
    
    .materials-filter:hover {
        border-color: #2196F3;
        box-shadow: 0 5px 15px rgba(33, 150, 243, 0.1);
    }
    
    #material-code-filter:focus {
        border-color: #2196F3;
        box-shadow: 0 0 20px rgba(33, 150, 243, 0.2);
    }
    
    .filter-stats {
        font-size: 0.85rem;
    }
    
    .materials-table-card {
        transition: all 0.3s ease;
    }
    
    .materials-table-card[style*="display: none"] {
        opacity: 0;
        transform: translateY(-10px);
    }
</style>

<div class="materials-container">
    <div class="container-fluid py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>يرجى مراجعة الأخطاء التالية:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- زر الرجوع -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('admin.work-orders.index') }}" class="btn btn-secondary btn-modern me-3">
                            <i class="fas fa-arrow-right me-2"></i>
                            الرجوع لأوامر العمل
                        </a>
                        @if(isset($currentWorkOrder))
                        <div class="alert alert-primary mb-0 p-2">
                            <i class="fas fa-clipboard-list me-2"></i>
                            <strong>أمر العمل:</strong> {{ $currentWorkOrder->order_number }}
                            <br><small>{{ $currentWorkOrder->subscriber_name }}</small>
                        </div>
                        @endif
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary me-2">
                            <i class="fas fa-box me-1"></i>
                            {{ $materials->count() }} مادة
                        </span>
                        <!-- تنقل بين أوامر العمل -->
                        @if($workOrders->count() > 1)
                        <div class="dropdown">
                           
                            <ul class="dropdown-menu dropdown-menu-end">
                                @foreach($workOrders as $workOrder)
                                    <li>
                                        <a class="dropdown-item {{ isset($currentWorkOrder) && $currentWorkOrder->id == $workOrder->id ? 'active' : '' }}" 
                                           href="{{ route('admin.work-orders.materials', $workOrder->id) }}">
                                            <i class="fas fa-clipboard-list me-2"></i>
                                            {{ $workOrder->order_number }}
                                            <br><small class="text-muted">{{ $workOrder->subscriber_name }}</small>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- قسم إضافة المواد -->
            <div class="col-lg-5">
                <div class="card card-modern">
                    <div class="card-header card-header-modern">
                        <h3>
                            <i class="fas fa-plus-circle me-2"></i>
                            إضافة مادة جديدة
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.work-orders.materials.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            
                            <!-- معلومات أمر العمل - تلقائي -->
                            <div class="form-section">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-clipboard-list me-2"></i>
                                    معلومات أمر العمل
                                </h5>
                                <div class="mb-3">
                                    @if(isset($currentWorkOrder))
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <strong>أمر العمل الحالي:</strong> {{ $currentWorkOrder->order_number }}
                                        <br><small>{{ $currentWorkOrder->subscriber_name }}</small>
                                        <br><small class="text-muted">سيتم إضافة المواد لهذا الأمر تلقائياً</small>
                                    </div>
                                    @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>تنبيه:</strong> لم يتم تحديد أمر عمل. يرجى اختيار أمر عمل أولاً.
                                    </div>
                                    @endif
                                    
                                    @if($workOrders->count() > 0)
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>أوامر العمل المتاحة:</strong>
                                            <ul class="mb-0 mt-2">
                                                @foreach($workOrders->take(5) as $workOrder)
                                                    <li>{{ $workOrder->order_number }} - {{ $workOrder->subscriber_name }}</li>
                                                @endforeach
                                                @if($workOrders->count() > 5)
                                                    <li><small class="text-muted">... و {{ $workOrders->count() - 5 }} أوامر أخرى</small></li>
                                                @endif
                                            </ul>
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <strong>تنبيه:</strong> لا توجد أوامر عمل متاحة. يرجى إنشاء أمر عمل أولاً.
                                        </div>
                                    @endif
                                    
                                    <!-- حقل مخفي للتوافق مع الكنترولر -->
                                    <input type="hidden" id="work_order_id" name="work_order_id" value="{{ isset($currentWorkOrder) ? $currentWorkOrder->id : '1' }}">
                                </div>
                            </div>

                            <!-- معلومات المادة -->
                            <div class="form-section">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-box me-2"></i>
                                    معلومات المادة
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="code" class="form-label required-field">كود المادة</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-modern" id="code" name="code" 
                                                   placeholder="أدخل كود المادة" required autocomplete="off">
                                            <button type="button" class="btn btn-outline-secondary" id="search-material-btn" title="البحث عن وصف المادة">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback">يرجى إدخال كود المادة</div>
                                        <small class="text-muted">سيتم البحث عن الوصف تلقائياً عند إدخال الكود</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="line" class="form-label">السطر</label>
                                        <input type="text" class="form-control form-control-modern" id="line" name="line" placeholder="رقم السطر">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label required-field">وصف المادة</label>
                                    <div class="position-relative">
                                        <textarea class="form-control form-control-modern" id="description" name="description" 
                                                  rows="3" placeholder="وصف تفصيلي للمادة" required></textarea>
                                        <div id="description-loader" class="position-absolute top-0 end-0 p-2" style="display: none;">
                                            <i class="fas fa-spinner fa-spin text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">يرجى إدخال وصف المادة</div>
                                    <small class="text-success" id="description-status" style="display: none;">
                                        <i class="fas fa-check-circle"></i> تم جلب الوصف تلقائياً
                                    </small>
                                </div>

                                <!-- الكميات -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="planned_quantity" class="form-label">الكمية المخططة</label>
                                        <input type="number" step="0.01" class="form-control form-control-modern" id="planned_quantity" name="planned_quantity" placeholder="0.00" value="0">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="actual_quantity" class="form-label">الكمية الفعلية</label>
                                        <input type="number" step="0.01" class="form-control form-control-modern" id="actual_quantity" name="actual_quantity" placeholder="0.00" value="0">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="spent_quantity" class="form-label">الكمية المصروفة</label>
                                        <input type="number" step="0.01" class="form-control form-control-modern" id="spent_quantity" name="spent_quantity" placeholder="0.00" value="0">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="executed_site_quantity" class="form-label">الكمية المنفذة بالموقع</label>
                                        <input type="number" step="0.01" class="form-control form-control-modern" id="executed_site_quantity" name="executed_site_quantity" placeholder="0.00" value="0">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="unit" class="form-label">الوحدة</label>
                                        <select class="form-control form-control-modern" id="unit" name="unit">
                                    
                                            <option value="L.M">L.M</option>
                                            <option value="Kit">Kit</option>
                                            <option value="Ech">Ech</option>
                                            
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="date_gatepass" class="form-label">DATE GATEPASS</label>
                                        <input type="date" class="form-control form-control-modern" id="date_gatepass" name="date_gatepass">
                                    </div>
                                </div>
                            </div>

                            <!-- المرفقات -->
                            <div class="form-section">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-paperclip me-2"></i>
                                    المرفقات والملفات
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="check_in_file" class="form-label">CHECK LIST</label>
                                        <div class="file-upload-area">
                                            <input type="file" class="form-control" id="check_in_file" name="check_in_file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                            <small class="text-muted">PDF, صور، Word (حد أقصى 10 ميجا)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="gate_pass_file" class="form-label">Gate Pass</label>
                                        <div class="file-upload-area">
                                            <input type="file" class="form-control" id="gate_pass_file" name="gate_pass_file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                            <small class="text-muted">PDF, صور، Word (حد أقصى 10 ميجا)</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="store_in_file" class="form-label">STORE IN</label>
                                        <div class="file-upload-area">
                                            <input type="file" class="form-control" id="store_in_file" name="store_in_file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                            <small class="text-muted">PDF, صور، Word (حد أقصى 10 ميجا)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="store_out_file" class="form-label">STORE OUT</label>
                                        <div class="file-upload-area">
                                            <input type="file" class="form-control" id="store_out_file" name="store_out_file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                            <small class="text-muted">PDF, صور، Word (حد أقصى 10 ميجا)</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="ddo_file" class="form-label">مرفق DDO</label>
                                        <div class="file-upload-area">
                                            <input type="file" class="form-control" id="ddo_file" name="ddo_file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                            <small class="text-muted">PDF, صور، Word (حد أقصى 10 ميجا)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- أزرار الحفظ -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary-modern btn-modern">
                                    <i class="fas fa-save me-2"></i>
                                    حفظ المادة
                                </button>
                                <button type="submit" name="save_and_continue" value="1" class="btn btn-success-modern btn-modern">
                                    <i class="fas fa-plus me-2"></i>
                                    حفظ وإضافة مادة أخرى
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- قسم عرض المواد -->
            <div class="col-lg-7">
                <!-- فلتر البحث -->
                <div class="card card-modern materials-filter mb-3">
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-md-6">
                                <label for="material-code-filter" class="form-label">
                                    <i class="fas fa-filter me-1"></i>
                                    فلتر حسب كود المادة
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control" id="material-code-filter" 
                                           placeholder="ابحث بكود المادة..." autocomplete="off">
                                    <button type="button" class="btn btn-outline-secondary" id="clear-filter-btn" title="مسح الفلتر">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-end filter-stats">
                                    <span class="badge bg-info" id="total-materials">
                                        <i class="fas fa-box me-1"></i>
                                        <span id="visible-count">{{ $materials->count() }}</span> مادة ظاهرة
                                    </span>
                                    <span class="badge bg-secondary" id="total-hidden" style="display: none;">
                                        <i class="fas fa-eye-slash me-1"></i>
                                        <span id="hidden-count">0</span> مادة مخفية
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- جدول مواد أمر العمل الحالي -->
                @if(isset($currentWorkOrder) && $materials->count() > 0)
                <div class="card card-modern mb-4 materials-table-card">
                    <div class="card-header card-header-modern d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                <i class="fas fa-clipboard-list me-2"></i>
                                رقم الطلب: {{ $currentWorkOrder->order_number }}
                            </h5>
                            <small class="text-white-50">
                                <i class="fas fa-user me-1"></i>
                                {{ $currentWorkOrder->subscriber_name }}
                                <span class="mx-2">|</span>
                                <i class="fas fa-box me-1"></i>
                                {{ $materials->count() }} مادة
                            </small>
                        </div>
                        <div>
                            <span class="badge bg-light text-dark">{{ $materials->count() }} مادة</span>
                            <a href="{{ route('admin.work-orders.materials.export.excel', ['work_order' => $currentWorkOrder->order_number]) }}" 
                               class="btn btn-success-modern btn-sm ms-2" target="_blank">
                                <i class="fas fa-file-excel me-1"></i>
                                تصدير إكسل
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-modern table-compact mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 100px;">كود المادة</th>
                                        <th style="width: 350px;">الوصف</th>
                                        <th style="width: 60px;">السطر</th>
                                        <th style="width: 80px;">كمية مخططة</th>
                                        <th style="width: 80px;">كمية فعلية</th>
                                        <th style="width: 80px;">كمية مصروفة</th>
                                        <th style="width: 80px;">كمية منفذة</th>
                                        <th style="width: 70px;">الفرق</th>
                                        <th style="width: 60px;">الوحدة</th>
                                        <th style="width: 100px;">DATE GATEPASS</th>
                                        <th style="width: 80px;">الملفات</th>
                                        <th style="width: 80px;">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materials as $material)
                                        <tr>
                                            <td>
                                                <span class="badge badge-compact bg-primary">{{ $material->code }}</span>
                                            </td>
                                            <td>
                                                <div style="max-width: 350px; white-space: normal; line-height: 1.4;" title="{{ $material->description }}">
                                                    {{ $material->description }}
                                                </div>
                                            </td>
                                            <td><small>{{ $material->line ?? '-' }}</small></td>
                                            <td>
                                                <span class="badge badge-compact bg-info">{{ number_format($material->planned_quantity ?? 0, 1) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-compact bg-warning">{{ number_format($material->actual_quantity ?? 0, 1) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-compact bg-purple text-white">{{ number_format($material->spent_quantity ?? 0, 1) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-compact bg-teal text-white">{{ number_format($material->executed_site_quantity ?? 0, 1) }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $diff = ($material->planned_quantity ?? 0) - ($material->actual_quantity ?? 0);
                                                    if ($diff > 0) {
                                                        $badgeClass = 'bg-danger';
                                                        $icon = 'fas fa-arrow-up';
                                                        $textClass = 'difference-positive';
                                                    } elseif ($diff < 0) {
                                                        $badgeClass = 'bg-success';
                                                        $icon = 'fas fa-arrow-down';
                                                        $textClass = 'difference-negative';
                                                    } else {
                                                        $badgeClass = 'bg-secondary';
                                                        $icon = 'fas fa-equals';
                                                        $textClass = 'difference-zero';
                                                    }
                                                @endphp
                                                <span class="badge badge-compact {{ $badgeClass }} {{ $textClass }}">
                                                    <i class="{{ $icon }} me-1"></i>
                                                    {{ number_format(abs($diff), 1) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="badge badge-compact bg-dark">{{ $material->unit ?? '-' }}</small>
                                            </td>
                                            <td>
                                                @if($material->date_gatepass)
                                                    <small class="badge badge-compact bg-info text-white" title="{{ $material->date_gatepass->format('Y-m-d') }}">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        {{ $material->date_gatepass->format('d/m') }}
                                                    </small>
                                                @else
                                                    <small class="text-muted">-</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @if($material->check_in_file)
                                                        <a href="{{ asset('storage/' . $material->check_in_file) }}" target="_blank" 
                                                           class="btn btn-xs btn-outline-success" title="CHECK LIST" data-bs-toggle="tooltip">
                                                            <i class="fas fa-list-check"></i>
                                                        </a>
                                                    @endif
                                                    @if($material->gate_pass_file)
                                                        <a href="{{ asset('storage/' . $material->gate_pass_file) }}" target="_blank" 
                                                           class="btn btn-xs btn-outline-primary" title="Gate Pass" data-bs-toggle="tooltip">
                                                            <i class="fas fa-id-card"></i>
                                                        </a>
                                                    @endif
                                                    @if($material->store_in_file)
                                                        <a href="{{ asset('storage/' . $material->store_in_file) }}" target="_blank" 
                                                           class="btn btn-xs btn-outline-info" title="STORE IN" data-bs-toggle="tooltip">
                                                            <i class="fas fa-sign-in-alt"></i>
                                                        </a>
                                                    @endif
                                                    @if($material->store_out_file)
                                                        <a href="{{ asset('storage/' . $material->store_out_file) }}" target="_blank" 
                                                           class="btn btn-xs btn-outline-warning" title="STORE OUT" data-bs-toggle="tooltip">
                                                            <i class="fas fa-sign-out-alt"></i>
                                                        </a>
                                                    @endif
                                                    @if($material->ddo_file)
                                                        <a href="{{ asset('storage/' . $material->ddo_file) }}" target="_blank" 
                                                           class="btn btn-xs btn-outline-danger" title="مرفق DDO" data-bs-toggle="tooltip">
                                                            <i class="fas fa-file-contract"></i>
                                                        </a>
                                                    @endif
                                                    @if(!$material->check_in_file && !$material->gate_pass_file && !$material->store_in_file && !$material->store_out_file && !$material->ddo_file)
                                                        <small class="text-muted">لا توجد ملفات</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group-vertical" style="gap: 2px;">
                                                    <a href="{{ route('admin.work-orders.materials.edit', $material) }}" 
                                                       class="btn btn-xs btn-warning-modern" style="font-size: 0.7rem; padding: 4px 8px;" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.work-orders.materials.destroy', $material) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-xs btn-danger-modern" 
                                                                style="font-size: 0.7rem; padding: 4px 8px;"
                                                                onclick="return confirm('هل أنت متأكد من حذف هذه المادة؟')" title="حذف">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-secondary">
                                    <tr style="font-weight: bold;">
                                        <td colspan="3" class="text-center">
                                            <i class="fas fa-calculator me-2"></i>إجمالي أمر العمل
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-white">{{ number_format($materials->sum('planned_quantity'), 1) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-white">{{ number_format($materials->sum('actual_quantity'), 1) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-purple text-white">{{ number_format($materials->sum('spent_quantity'), 1) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-teal text-white">{{ number_format($materials->sum('executed_site_quantity'), 1) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $totalDiff = $materials->sum('planned_quantity') - $materials->sum('actual_quantity');
                                                $diffClass = $totalDiff > 0 ? 'bg-danger' : ($totalDiff < 0 ? 'bg-success' : 'bg-secondary');
                                            @endphp
                                            <span class="badge {{ $diffClass }} text-white">{{ number_format(abs($totalDiff), 1) }}</span>
                                        </td>
                                        <td colspan="3" class="text-center text-muted">
                                            <small>{{ $materials->count() }} مادة</small>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @elseif(isset($currentWorkOrder))
                <div class="card card-modern">
                    <div class="card-body text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-box-open fa-3x mb-3"></i>
                            <h5>لا توجد مواد مسجلة حتى الآن</h5>
                            <p>قم بإضافة أول مادة باستخدام النموذج على اليسار</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="card card-modern">
                    <div class="card-body text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                            <h5>لم يتم تحديد أمر عمل</h5>
                            <p>يرجى اختيار أمر عمل من القائمة أعلاه</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- تم حذف شريط التصفح لأن النظام يعرض جميع المواد الآن -->
            </div>
        </div>
    </div>
</div>

<!-- زر الإجراءات السريعة -->
<div class="floating-action">
    <button class="floating-btn" data-bs-toggle="tooltip" data-bs-placement="left" title="إجراءات سريعة">
        <i class="fas fa-cog"></i>
    </button>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تفعيل التحقق من صحة النموذج
    const forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // تفعيل الـ tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // البحث التلقائي عن وصف المادة
    const codeInput = document.getElementById('code');
    const descriptionTextarea = document.getElementById('description');
    const descriptionLoader = document.getElementById('description-loader');
    const descriptionStatus = document.getElementById('description-status');
    const searchBtn = document.getElementById('search-material-btn');
    
    let searchTimeout;
    
    // دالة البحث عن الوصف
    function searchMaterialDescription(code) {
        if (!code || code.length < 2) {
            return;
        }
        
        descriptionLoader.style.display = 'block';
        descriptionStatus.style.display = 'none';
        
        // إخفاء رسالة التحميل بعد 3 ثوان إذا لم يتم العثور على نتيجة
        const loadingTimeout = setTimeout(() => {
            descriptionLoader.style.display = 'none';
        }, 3000);
        
        fetch(`/admin/materials/description/${encodeURIComponent(code)}`)
            .then(response => response.json())
            .then(data => {
                clearTimeout(loadingTimeout);
                descriptionLoader.style.display = 'none';
                
                if (data.description && data.description.trim() !== '') {
                    descriptionTextarea.value = data.description;
                    descriptionStatus.style.display = 'block';
                    descriptionTextarea.style.borderColor = '#28a745';
                    
                    // إخفاء رسالة النجاح بعد 3 ثوان
                    setTimeout(() => {
                        descriptionStatus.style.display = 'none';
                        descriptionTextarea.style.borderColor = '';
                    }, 3000);
                } else {
                    // لم يتم العثور على وصف
                    descriptionTextarea.placeholder = 'لم يتم العثور على وصف للكود المدخل، يرجى إدخال الوصف يدوياً';
                }
            })
            .catch(error => {
                clearTimeout(loadingTimeout);
                descriptionLoader.style.display = 'none';
                console.error('خطأ في البحث عن الوصف:', error);
                descriptionTextarea.placeholder = 'خطأ في البحث، يرجى إدخال الوصف يدوياً';
            });
    }
    
    // البحث عند كتابة الكود (مع تأخير)
    if (codeInput) {
        codeInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const code = this.value.trim();
            
            if (code.length >= 2) {
                searchTimeout = setTimeout(() => {
                    searchMaterialDescription(code);
                }, 500); // تأخير نصف ثانية
            } else {
                descriptionLoader.style.display = 'none';
                descriptionStatus.style.display = 'none';
                descriptionTextarea.placeholder = 'وصف تفصيلي للمادة';
            }
        });
        
        // البحث عند الضغط على Enter
        codeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const code = this.value.trim();
                if (code) {
                    searchMaterialDescription(code);
                }
            }
        });
    }
    
    // البحث عند الضغط على زر البحث
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            const code = codeInput.value.trim();
            if (code) {
                searchMaterialDescription(code);
            } else {
                codeInput.focus();
                codeInput.style.borderColor = '#dc3545';
                setTimeout(() => {
                    codeInput.style.borderColor = '';
                }, 2000);
            }
        });
    }

    // تحسين تجربة رفع الملفات
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const uploadArea = this.closest('.file-upload-area');
                uploadArea.style.backgroundColor = 'rgba(76, 175, 80, 0.1)';
                uploadArea.style.borderColor = '#4CAF50';
                
                // إظهار اسم الملف
                let fileInfo = uploadArea.querySelector('.file-info');
                if (!fileInfo) {
                    fileInfo = document.createElement('div');
                    fileInfo.className = 'file-info mt-2';
                    uploadArea.appendChild(fileInfo);
                }
                fileInfo.innerHTML = `<small class="text-success"><i class="fas fa-check-circle"></i> ${file.name}</small>`;
            }
        });
    });

    // حساب الفرق تلقائياً مع عرض جميع الفروقات
    const plannedQty = document.getElementById('planned_quantity');
    const actualQty = document.getElementById('actual_quantity');
    const spentQty = document.getElementById('spent_quantity');
    const executedQty = document.getElementById('executed_site_quantity');
    
    function calculateDifferences() {
        const planned = parseFloat(plannedQty.value) || 0;
        const actual = parseFloat(actualQty.value) || 0;
        const spent = parseFloat(spentQty.value) || 0;
        const executed = parseFloat(executedQty.value) || 0;
        
        const mainDifference = planned - actual;
        const spentDiff = actual - spent;
        const executedDiff = spent - executed;
        
        // إضافة visual feedback للفروقات
        let diffDisplay = document.getElementById('differences-display');
        if (!diffDisplay) {
            diffDisplay = document.createElement('div');
            diffDisplay.id = 'differences-display';
            diffDisplay.className = 'mt-3 p-3 bg-light rounded';
            executedQty.parentNode.parentNode.appendChild(diffDisplay);
        }
        
        if (planned || actual || spent || executed) {
            let mainBadgeClass = mainDifference > 0 ? 'bg-danger' : (mainDifference < 0 ? 'bg-success' : 'bg-secondary');
            let spentBadgeClass = spentDiff > 0 ? 'bg-warning' : (spentDiff < 0 ? 'bg-info' : 'bg-secondary');
            let executedBadgeClass = executedDiff > 0 ? 'bg-primary' : (executedDiff < 0 ? 'bg-dark' : 'bg-secondary');
            
            diffDisplay.innerHTML = `
                <h6 class="mb-2">الفروقات المحسوبة:</h6>
                <div class="row">
                    <div class="col-md-4">
                        <small class="badge ${mainBadgeClass} w-100">
                            <i class="fas fa-calculator"></i> مخطط - فعلي: ${mainDifference.toFixed(2)}
                        </small>
                    </div>
                    <div class="col-md-4">
                        <small class="badge ${spentBadgeClass} w-100">
                            <i class="fas fa-minus"></i> فعلي - مصروف: ${spentDiff.toFixed(2)}
                        </small>
                    </div>
                    <div class="col-md-4">
                        <small class="badge ${executedBadgeClass} w-100">
                            <i class="fas fa-wrench"></i> مصروف - منفذ: ${executedDiff.toFixed(2)}
                        </small>
                    </div>
                </div>
            `;
        } else {
            diffDisplay.innerHTML = '';
        }
    }
    
    if (plannedQty && actualQty && spentQty && executedQty) {
        plannedQty.addEventListener('input', calculateDifferences);
        actualQty.addEventListener('input', calculateDifferences);
        spentQty.addEventListener('input', calculateDifferences);
        executedQty.addEventListener('input', calculateDifferences);
    }
    
    // تحسين UX للنموذج
    const form = document.querySelector('form');
    if (form) {
        // إضافة تأكيد عند إرسال النموذج
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]:focus') || this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';
                
                // إعادة تفعيل الزر بعد 5 ثوان للحماية من التعليق
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = submitBtn.getAttribute('data-original-text') || 'حفظ';
                }, 5000);
            }
        });
        
        // حفظ النص الأصلي للأزرار
        const submitBtns = form.querySelectorAll('button[type="submit"]');
        submitBtns.forEach(btn => {
            btn.setAttribute('data-original-text', btn.innerHTML);
        });
    }

    // إخفاء رسائل النجاح تلقائياً بعد 5 ثواني
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-success');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // تأكيد الحذف
    document.querySelectorAll('form[method="POST"] button[type="submit"]').forEach(function(button) {
        if(button.textContent.includes('fas fa-trash')) {
            button.addEventListener('click', function(e) {
                if(!confirm('هل أنت متأكد من حذف هذه المادة؟ لن تتمكن من التراجع عن هذا الإجراء.')) {
                    e.preventDefault();
                }
            });
        }
    });

    // التأكد من القيم الرقمية قبل إرسال النموذج
    document.querySelector('form').addEventListener('submit', function(e) {
        // الحقول الرقمية
        const numericFields = ['planned_quantity', 'actual_quantity', 'spent_quantity', 'executed_site_quantity'];
        
        numericFields.forEach(function(fieldName) {
            const field = document.getElementById(fieldName);
            if (field && (field.value === '' || field.value === null)) {
                field.value = '0';
            }
        });
    });

    // عداد الملفات المرفوعة
    document.querySelectorAll('input[type="file"]').forEach(function(input) {
        input.addEventListener('change', function() {
            const fileName = this.files[0] ? this.files[0].name : 'لم يتم اختيار ملف';
            const label = this.closest('.file-upload-area').querySelector('small');
            if(label && this.files[0]) {
                label.textContent = fileName + ' - ' + (this.files[0].size / 1024 / 1024).toFixed(2) + ' MB';
                label.style.color = '#28a745';
            }
        });
    });

    // تم حذف أزرار اختيار أمر العمل لأن النظام يعمل تلقائياً الآن

    // فلتر البحث حسب كود المادة
    const materialCodeFilter = document.getElementById('material-code-filter');
    const clearFilterBtn = document.getElementById('clear-filter-btn');
    const visibleCountSpan = document.getElementById('visible-count');
    const hiddenCountSpan = document.getElementById('hidden-count');
    const totalHiddenBadge = document.getElementById('total-hidden');
    
    if (materialCodeFilter) {
        materialCodeFilter.addEventListener('input', function() {
            const filterValue = this.value.toLowerCase().trim();
            let visibleCount = 0;
            let hiddenCount = 0;
            
            // البحث في جميع الصفوف
            const allRows = document.querySelectorAll('.materials-table-card tbody tr');
            const allTables = document.querySelectorAll('.materials-table-card');
            
            allRows.forEach(function(row) {
                const codeCell = row.querySelector('td:first-child span.badge');
                if (codeCell) {
                    const codeText = codeCell.textContent.toLowerCase();
                    const shouldShow = filterValue === '' || codeText.includes(filterValue);
                    
                    row.style.display = shouldShow ? '' : 'none';
                    
                    if (shouldShow) {
                        visibleCount++;
                    } else {
                        hiddenCount++;
                    }
                }
            });
            
            // تحديث الجداول - إخفاء الجداول التي لا تحتوي على صفوف ظاهرة
            allTables.forEach(function(table) {
                const visibleRowsInTable = table.querySelectorAll('tbody tr:not([style*="display: none"])');
                const shouldShowTable = visibleRowsInTable.length > 0 || filterValue === '';
                table.style.display = shouldShowTable ? '' : 'none';
            });
            
            // تحديث العدادات
            visibleCountSpan.textContent = visibleCount;
            hiddenCountSpan.textContent = hiddenCount;
            
            if (hiddenCount > 0) {
                totalHiddenBadge.style.display = 'inline-block';
            } else {
                totalHiddenBadge.style.display = 'none';
            }
            
            // تمييز حقل البحث
            if (filterValue) {
                materialCodeFilter.style.backgroundColor = '#fff3cd';
                materialCodeFilter.style.borderColor = '#ffc107';
            } else {
                materialCodeFilter.style.backgroundColor = '';
                materialCodeFilter.style.borderColor = '';
            }
        });
        
        // زر مسح الفلتر
        if (clearFilterBtn) {
            clearFilterBtn.addEventListener('click', function() {
                materialCodeFilter.value = '';
                materialCodeFilter.dispatchEvent(new Event('input'));
                materialCodeFilter.focus();
            });
        }
        
        // البحث عند الضغط على Enter
        materialCodeFilter.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });
    }
});
</script>
@endpush
@endsection 