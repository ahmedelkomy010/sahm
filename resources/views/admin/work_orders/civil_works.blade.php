<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>الأعمال المدنية - {{ $workOrder->order_number }}</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Work Order Configuration -->
    <meta name="work-order-id" content="{{ $workOrder->id }}">
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Tajawal:400,500,700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    
    <!-- jQuery (needed for Toastr) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    
    <!-- تهيئة Toastr -->
    <script>
        // تهيئة Toastr
        $(document).ready(function() {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut",
                "rtl": true
            };
        });
    </script>
</head>
<body>
    <div class="container-fluid py-4">
        

        <!-- بقية المحتوى -->
        <div class="main-container">
            <h1 class="page-title">
                <i class="fas fa-hard-hat me-3"></i>
                الأعمال المدنية   {{ $workOrder->work_order_number }}
            </h1>

            <!-- رسائل التنبيه -->
            @if(session('success'))
                <div class="alert alert-success shadow-sm">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger shadow-sm">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                    @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            @endif

            <!-- زر العودة -->
            <div class="text-start mb-4">
                <a href="{{ route('admin.work-orders.execution', $workOrder) }}" 
                   class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>عودة للتنفيذ
                </a>
            </div>
            <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-light border-0 shadow-sm">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-md-3 col-sm-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-hashtag text-primary me-2 fs-5"></i>
                                    <div>
                                        <small class="text-muted d-block">رقم الطلب</small>
                                        <strong class="text-primary fs-6">{{ $workOrder->order_number }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-tools text-warning me-2 fs-5"></i>
                                    <div>
                                        <small class="text-muted d-block">نوع العمل</small>
                                        <strong class="fs-6">{{ $workOrder->work_type }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user text-info me-2 fs-5"></i>
                                    <div>
                                        <small class="text-muted d-block">اسم المشترك</small>
                                        <strong class="fs-6">{{ $workOrder->subscriber_name }}</strong>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3 col-sm-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-flag-checkered text-success me-2 fs-5"></i>
                                    <div>
                                        <small class="text-muted d-block">حالة التنفيذ</small>
                                        <strong class="text-success fs-6">
                                            @switch($workOrder->execution_status)
                                                @case(2)
                                                    تم تسليم 155
                                                    @break
                                                @case(1)
                                                    جاري العمل
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

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- جدول الملخص اليومي للحفريات -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="card-header bg-transparent border-0 text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-magic me-2"></i>
                                    الملخص اليومي
                                    <span class="badge bg-light text-dark ms-2">
                                        <i class="fas fa-sync-alt fa-spin"></i>
                                        تحديث تلقائي
                                    </span>
                                </h5>
                                <small class="text-light">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ date('Y-m-d H:i') }}
                                </small>
                            </div>
                        </div>
                        <div class="card-body bg-white" style="border-radius: 0 0 15px 15px;">
                            
                            <!-- إحصائيات سريعة -->
                            <div class="row mb-4">
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <div class="card bg-light border-0 text-center" style="border-radius: 15px;">
                                        <div class="card-body py-3">
                                            <div class="text-primary display-6 mb-2">
                                                <i class="fas fa-hammer"></i>
                                            </div>
                                            <h4 class="mb-1" id="daily-items-count">0</h4>
                                            <small class="text-muted">إجمالي البنود</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <div class="card bg-light border-0 text-center" style="border-radius: 15px;">
                                        <div class="card-body py-3">
                                            <div class="text-success display-6 mb-2">
                                                <i class="fas fa-plug"></i>
                                            </div>
                                            <h4 class="mb-1" id="daily-cables-count">0</h4>
                                            <small class="text-muted">إجمالي الكابلات</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <div class="card bg-light border-0 text-center" style="border-radius: 15px;">
                                        <div class="card-body py-3">
                                            <div class="text-warning display-6 mb-2">
                                                <i class="fas fa-ruler-horizontal"></i>
                                            </div>
                                            <h4 class="mb-1" id="daily-total-length">0</h4>
                                            <small class="text-muted">إجمالي الطول (متر)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <div class="card bg-light border-0 text-center" style="border-radius: 15px;">
                                        <div class="card-body py-3">
                                            <div class="text-danger display-6 mb-2">
                                                <i class="fas fa-coins"></i>
                                            </div>
                                            <h4 class="mb-1" id="daily-total-cost">0.00</h4>
                                            <small class="text-muted">إجمالي التكلفة (ريال)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- إجمالي المبلغ الكلي -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="card bg-success text-white border-0 text-center" style="border-radius: 15px;">
                                        <div class="card-body py-3">
                                            <div class="display-6 mb-2">
                                                <i class="fas fa-calculator"></i>
                                            </div>
                                            <h3 class="mb-1" id="total-amount">0.00</h3>
                                            <h6 class="mb-0">إجمالي المبلغ الكلي (ريال)</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- أزرار التحكم في الجدول -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-dark mb-0">
                                    <i class="fas fa-table me-2"></i>
                                    ملخص الحفريات اليومي
                                </h6>
                                
                            </div>

                                                                        <!-- الجدول الديناميكي -->
                            <div class="table-responsive">
                                <table id="daily-excavation-table" class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                <i class="fas fa-hashtag"></i>
                                                #
                                            </th>
                                            <th class="text-center">
                                                <i class="fas fa-hard-hat"></i>
                                                نوع الحفرية
                                            </th>
                                            <th class="text-center">
                                                <i class="fas fa-plug"></i>
                                                نوع الكابل
                                            </th>
                                            <th class="text-center">
                                                <i class="fas fa-arrows-alt-h"></i>
                                                الطول (م)
                                            </th>
                                            <th class="text-center">
                                                <i class="fas fa-money-bill-wave"></i>
                                                السعر (ريال)
                                            </th>
                                            <th class="text-center">
                                                <i class="fas fa-calculator"></i>
                                                الإجمالي (ريال)
                                            </th>
                                            <th class="text-center">
                                                <i class="fas fa-info-circle"></i>
                                                التفاصيل
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="daily-excavation-tbody">
                                        <tr id="no-data-row">
                                            <td colspan="7">
                                                <div class="empty-state-content">
                                                    <i class="fas fa-clipboard-list fa-3x"></i>
                                                    <h5>لا توجد بيانات حفريات</h5>
                                                    <p>سيتم إضافة البيانات تلقائياً عند إدخال القياسات</p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle"></i>
                                                        ابدأ بإدخال الطول والسعر في النماذج أعلاه
                                                    </small>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- أزرار التحكم -->
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="d-flex gap-3">
                                    <button type="button" class="btn btn-success btn-lg shadow-sm" id="save-daily-summary-btn">
                                        <i class="fas fa-save me-2"></i>
                                        <span class="fw-bold">حفظ الملخص</span>
                                    </button>
                                    <button type="button" class="btn btn-info btn-lg shadow-sm" id="export-daily-summary-btn">
                                        <i class="fas fa-file-excel me-2"></i>
                                        <span class="fw-bold">تصدير إكسل</span>
                                    </button>
                                    <button type="button" class="btn btn-warning btn-lg shadow-sm" onclick="window.clearSavedData()">
                                        <i class="fas fa-trash-alt me-2"></i>
                                        <span class="fw-bold">مسح البيانات</span>
                                    </button>
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-clock me-1"></i>
                                    آخر تحديث: <span id="last-update-time">--</span>
                                </div>
                            </div>
                            

                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.work-orders.civil-works.store', $workOrder) }}" 
                  enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <!-- كارد الحفريات الأساسية -->
                    <div class="col-md-12">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">الحفريات الأساسية</div>
                            <div class="card-body">
                                <!-- حفريات تربة ترابية غير مسفلتة -->
                                <div class="mb-4">
                                    <h6 class="fw-bold mb-2">حفريات تربة ترابية غير مسفلتة</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 20%">نوع الكابل</th>
                                                    <th style="width: 15%">الطول (متر)</th>
                                                    <th style="width: 10%">العرض (متر)</th>
                                                    <th style="width: 10%">العمق (متر)</th>
                                                    <th style="width: 12%">الإجمالي</th>
                                                    <th style="width: 13%">السعر (ريال)</th>
                                                    <th style="width: 20%">الإجمالي النهائي</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach([ ' كابل منخفض', '2 كابل منخفض', '3 كابل منخفض', '4 كابل منخفض', '1 كابل متوسط', '2 كابل متوسط', '3 كابل متوسط', '4 كابل متوسط'] as $cable)
                                            <tr>
                                                    <td class="align-middle">{{ $cable }} <span class="badge bg-info">12345678900</span></td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calc-length" 
                                                                   name="excavation_unsurfaced_soil[{{ $loop->index }}]" 
                                                                   data-row="{{ $loop->index }}"
                                                                   data-table="unsurfaced_soil"
                                                                   value="{{ old('excavation_unsurfaced_soil.' . $loop->index, $workOrder->excavation_unsurfaced_soil[$loop->index] ?? '') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">متر</span>
                                                        </div>
                                                    </td>
                                                    <td><span class="text-muted">-</span></td>
                                                    <td><span class="text-muted">-</span></td>
                                                    <td><span class="text-muted">-</span></td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control price-input calc-price" 
                                                                   name="excavation_unsurfaced_soil_price[{{ $loop->index }}]" 
                                                                   data-row="{{ $loop->index }}"
                                                                   data-table="unsurfaced_soil"
                                                                   value="{{ old('excavation_unsurfaced_soil_price.' . $loop->index, $workOrder->excavation_unsurfaced_soil_price[$loop->index] ?? '') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">ريال</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control bg-success text-white fw-bold total-calc" 
                                                                   id="total_unsurfaced_soil_{{ $loop->index }}" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <span class="input-group-text bg-success text-white">ريال</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                <!-- حفر مفتوح اكبر من 4 كابلات - حقول منفصلة -->
                                                <tr class="table-warning">
                                                    <td class="align-middle fw-bold">حفر مفتوح اكبر من 4 كابلات <span class="badge bg-info">12345678900</span></td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input excavation-calc calc-volume-length" 
                                                                   name="excavation_unsurfaced_soil_open[length]" 
                                                                   data-type="length"
                                                                   data-target="unsurfaced_soil_open"
                                                                   data-table="unsurfaced_soil_open"
                                                                   value="{{ old('excavation_unsurfaced_soil_open.length') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">م</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input excavation-calc calc-volume-width" 
                                                                   name="excavation_unsurfaced_soil_open[width]" 
                                                                   data-type="width"
                                                                   data-target="unsurfaced_soil_open"
                                                                   data-table="unsurfaced_soil_open"
                                                                   value="{{ old('excavation_unsurfaced_soil_open.width') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">م</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input excavation-calc calc-volume-depth" 
                                                                   name="excavation_unsurfaced_soil_open[depth]" 
                                                                   data-type="depth"
                                                                   data-target="unsurfaced_soil_open"
                                                                   data-table="unsurfaced_soil_open"
                                                                   value="{{ old('excavation_unsurfaced_soil_open.depth') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">م</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control bg-light fw-bold text-primary" 
                                                                   id="total_unsurfaced_soil_open" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <span class="input-group-text bg-primary text-white">م³</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control price-input calc-volume-price" 
                                                                   name="excavation_unsurfaced_soil_open_price" 
                                                                   data-table="unsurfaced_soil_open"
                                                                   value="{{ old('excavation_unsurfaced_soil_open_price', $workOrder->excavation_unsurfaced_soil_open_price ?? '') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">ريال</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control bg-success text-white fw-bold volume-total-calc" 
                                                                   id="final_total_unsurfaced_soil_open" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <span class="input-group-text bg-success text-white">ريال</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- حفريات تربة ترابية مسفلتة -->
                                <div class="mb-4">
                                    <h6 class="fw-bold mb-2">حفريات تربة ترابية مسفلتة</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 20%">نوع الكابل</th>
                                                    <th style="width: 15%">الطول (متر)</th>
                                                    <th style="width: 10%">العرض (متر)</th>
                                                    <th style="width: 10%">العمق (متر)</th>
                                                    <th style="width: 12%">الإجمالي</th>
                                                    <th style="width: 13%">السعر (ريال)</th>
                                                    <th style="width: 20%">الإجمالي النهائي</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach([ ' كابل منخفض', '2 كابل منخفض', '3 كابل منخفض', '4 كابل منخفض', '1 كابل متوسط', '2 كابل متوسط', '3 كابل متوسط', '4 كابل متوسط'] as $cable)
                                            <tr>
                                                    <td class="align-middle">{{ $cable }} <span class="badge bg-info">12345678900</span></td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calc-length" 
                                                                   name="excavation_surfaced_soil[{{ $loop->index }}]" 
                                                                   data-row="{{ $loop->index }}"
                                                                   data-table="surfaced_soil"
                                                                   value="{{ old('excavation_surfaced_soil.' . $loop->index, $workOrder->excavation_surfaced_soil[$loop->index] ?? '') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">متر</span>
                                                        </div>
                                                    </td>
                                                    <td><span class="text-muted">-</span></td>
                                                    <td><span class="text-muted">-</span></td>
                                                    <td><span class="text-muted">-</span></td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control price-input calc-price" 
                                                                   name="excavation_surfaced_soil_price[{{ $loop->index }}]" 
                                                                   data-row="{{ $loop->index }}"
                                                                   data-table="surfaced_soil"
                                                                   value="{{ old('excavation_surfaced_soil_price.' . $loop->index, $workOrder->excavation_surfaced_soil_price[$loop->index] ?? '') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">ريال</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control bg-success text-white fw-bold total-calc" 
                                                                   id="total_surfaced_soil_{{ $loop->index }}" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <span class="input-group-text bg-success text-white">ريال</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                <!-- حفر مفتوح اكبر من 4 كابلات - حقول منفصلة -->
                                                <tr class="table-warning">
                                                    <td class="align-middle fw-bold">حفر مفتوح اكبر من 4 كابلات <span class="badge bg-info">12345678900</span></td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input excavation-calc calc-volume-length" 
                                                                   name="excavation_surfaced_soil_open[length]" 
                                                                   data-type="length"
                                                                   data-target="surfaced_soil_open"
                                                                   data-table="surfaced_soil_open"
                                                                   value="{{ old('excavation_surfaced_soil_open.length') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">م</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input excavation-calc calc-volume-width" 
                                                                   name="excavation_surfaced_soil_open[width]" 
                                                                   data-type="width"
                                                                   data-target="surfaced_soil_open"
                                                                   data-table="surfaced_soil_open"
                                                                   value="{{ old('excavation_surfaced_soil_open.width') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">م</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input excavation-calc calc-volume-depth" 
                                                                   name="excavation_surfaced_soil_open[depth]" 
                                                                   data-type="depth"
                                                                   data-target="surfaced_soil_open"
                                                                   data-table="surfaced_soil_open"
                                                                   value="{{ old('excavation_surfaced_soil_open.depth') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">م</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control bg-light fw-bold text-primary" 
                                                                   id="total_surfaced_soil_open" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <span class="input-group-text bg-primary text-white">م³</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control price-input calc-volume-price" 
                                                                   name="excavation_surfaced_soil_open_price" 
                                                                   data-table="surfaced_soil_open"
                                                                   value="{{ old('excavation_surfaced_soil_open_price', $workOrder->excavation_surfaced_soil_open_price ?? '') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">ريال</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control bg-success text-white fw-bold volume-total-calc" 
                                                                   id="final_total_surfaced_soil_open" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <span class="input-group-text bg-success text-white">ريال</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- حفريات تربة صخرية مسفلتة -->
                                <div class="subsection mb-3">
                                    <h6 class="subsection-title">حفريات تربة صخرية مسفلتة</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                                <tr>
                                                    <th style="width: 20%">نوع الكابل</th>
                                                    <th style="width: 15%">الطول (متر)</th>
                                                    <th style="width: 10%">العرض (متر)</th>
                                                    <th style="width: 10%">العمق (متر)</th>
                                                    <th style="width: 12%">الإجمالي</th>
                                                    <th style="width: 13%">السعر (ريال)</th>
                                                    <th style="width: 20%">الإجمالي النهائي</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach([ 'كابل منخفض', '2 كابل منخفض', '3 كابل منخفض', '4 كابل منخفض', '1 كابل متوسط', '2 كابل متوسط', '3 كابل متوسط   ', '4 كابل متوسط'] as $cable)
                                            <tr>
                                                    <td class="align-middle">{{ $cable }} <span class="badge bg-info">12345678900</span></td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calc-length" 
                                                                   name="excavation_surfaced_rock[{{ $loop->index }}]" 
                                                                   data-row="{{ $loop->index }}"
                                                                   data-table="surfaced_rock"
                                                                   value="{{ old('excavation_surfaced_rock.' . $loop->index) }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">متر</span>
                                                        </div>
                                                    </td>
                                                    <td><span class="text-muted">-</span></td>
                                                    <td><span class="text-muted">-</span></td>
                                                    <td><span class="text-muted">-</span></td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control price-input calc-price" 
                                                                   name="excavation_surfaced_rock_price[{{ $loop->index }}]" 
                                                                   data-row="{{ $loop->index }}"
                                                                   data-table="surfaced_rock"
                                                                   value="{{ old('excavation_surfaced_rock_price.' . $loop->index, $workOrder->excavation_surfaced_rock_price[$loop->index] ?? '') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">ريال</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control bg-success text-white fw-bold total-calc" 
                                                                   id="total_surfaced_rock_{{ $loop->index }}" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <span class="input-group-text bg-success text-white">ريال</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                <!-- حفر مفتوح اكبر من 4 كابلات - حقول منفصلة -->
                                                <tr class="table-warning">
                                                    <td class="align-middle fw-bold">حفر مفتوح اكبر من 4 كابلات <span class="badge bg-info">12345678900</span></td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input excavation-calc calc-volume-length" 
                                                                   name="excavation_surfaced_rock_open[length]" 
                                                                   data-type="length"
                                                                   data-target="surfaced_rock_open"
                                                                   data-table="surfaced_rock_open"
                                                                   value="{{ old('excavation_surfaced_rock_open.length') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">م</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input excavation-calc calc-volume-width" 
                                                                   name="excavation_surfaced_rock_open[width]" 
                                                                   data-type="width"
                                                                   data-target="surfaced_rock_open"
                                                                   data-table="surfaced_rock_open"
                                                                   value="{{ old('excavation_surfaced_rock_open.width') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">م</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input excavation-calc calc-volume-depth" 
                                                                   name="excavation_surfaced_rock_open[depth]" 
                                                                   data-type="depth"
                                                                   data-target="surfaced_rock_open"
                                                                   data-table="surfaced_rock_open"
                                                                   value="{{ old('excavation_surfaced_rock_open.depth') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">م</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control bg-light fw-bold text-primary" 
                                                                   id="total_surfaced_rock_open" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <span class="input-group-text bg-primary text-white">م³</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control price-input calc-volume-price" 
                                                                   name="excavation_surfaced_rock_open_price" 
                                                                   data-table="surfaced_rock_open"
                                                                   value="{{ old('excavation_surfaced_rock_open_price', $workOrder->excavation_surfaced_rock_open_price ?? '') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">ريال</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control bg-success text-white fw-bold volume-total-calc" 
                                                                   id="final_total_surfaced_rock_open" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <span class="input-group-text bg-success text-white">ريال</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- حفريات تربة صخرية غير مسفلتة -->
                                <div class="subsection mb-3">
                                    <h6 class="subsection-title">حفريات تربة صخرية غير مسفلتة</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                                <tr>
                                                    <th style="width: 20%">نوع الكابل</th>
                                                    <th style="width: 15%">الطول (متر)</th>
                                                    <th style="width: 10%">العرض (متر)</th>
                                                    <th style="width: 10%">العمق (متر)</th>
                                                    <th style="width: 12%">الإجمالي</th>
                                                    <th style="width: 13%">السعر (ريال)</th>
                                                    <th style="width: 20%">الإجمالي النهائي</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach([ 'كابل منخفض', '2 كابل منخفض', '3 كابل منخفض', '4 كابل منخفض', '1 كابل متوسط', '2 كابل متوسط', '3 كابل متوسط', '4 كابل متوسط'] as $cable)
                                            <tr>
                                                    <td class="align-middle">{{ $cable }} <span class="badge bg-info">12345678900</span></td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calc-length" 
                                                                   name="excavation_unsurfaced_rock[{{ $loop->index }}]" 
                                                                   data-row="{{ $loop->index }}"
                                                                   data-table="unsurfaced_rock"
                                                                   value="{{ old('excavation_unsurfaced_rock.' . $loop->index) }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">متر</span>
                                                        </div>
                                                    </td>
                                                    <td><span class="text-muted">-</span></td>
                                                    <td><span class="text-muted">-</span></td>
                                                    <td><span class="text-muted">-</span></td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control price-input calc-price" 
                                                                   name="excavation_unsurfaced_rock_price[{{ $loop->index }}]" 
                                                                   data-row="{{ $loop->index }}"
                                                                   data-table="unsurfaced_rock"
                                                                   value="{{ old('excavation_unsurfaced_rock_price.' . $loop->index, $workOrder->excavation_unsurfaced_rock_price[$loop->index] ?? '') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">ريال</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control bg-success text-white fw-bold total-calc" 
                                                                   id="total_unsurfaced_rock_{{ $loop->index }}" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <span class="input-group-text bg-success text-white">ريال</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                <!-- حفر مفتوح اكبر من 4 كابلات - حقول منفصلة -->
                                                <tr class="table-warning">
                                                    <td class="align-middle fw-bold">حفر مفتوح اكبر من 4 كابلات <span class="badge bg-info">12345678900</span></td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input excavation-calc calc-volume-length" 
                                                                   name="excavation_unsurfaced_rock_open[length]" 
                                                                   data-type="length"
                                                                   data-target="unsurfaced_rock_open"
                                                                   data-table="unsurfaced_rock_open"
                                                                   value="{{ old('excavation_unsurfaced_rock_open.length') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">م</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input excavation-calc calc-volume-width" 
                                                                   name="excavation_unsurfaced_rock_open[width]" 
                                                                   data-type="width"
                                                                   data-target="unsurfaced_rock_open"
                                                                   data-table="unsurfaced_rock_open"
                                                                   value="{{ old('excavation_unsurfaced_rock_open.width') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">م</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input excavation-calc calc-volume-depth" 
                                                                   name="excavation_unsurfaced_rock_open[depth]" 
                                                                   data-type="depth"
                                                                   data-target="unsurfaced_rock_open"
                                                                   data-table="unsurfaced_rock_open"
                                                                   value="{{ old('excavation_unsurfaced_rock_open.depth') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">م</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control bg-light fw-bold text-primary" 
                                                                   id="total_unsurfaced_rock_open" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <span class="input-group-text bg-primary text-white">م³</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control price-input calc-volume-price" 
                                                                   name="excavation_unsurfaced_rock_open_price" 
                                                                   data-table="unsurfaced_rock_open"
                                                                   value="{{ old('excavation_unsurfaced_rock_open_price', $workOrder->excavation_unsurfaced_rock_open_price ?? '') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">ريال</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control bg-success text-white fw-bold volume-total-calc" 
                                                                   id="final_total_unsurfaced_rock_open" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <span class="input-group-text bg-success text-white">ريال</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- حفريات دقيقة -->
                                <div class="subsection mb-3">
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- القسم الثاني: الحفر المفتوح -->
                    <div class="col-md-12">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-success text-white">الحفر المفتوح </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th style="width: 30%">نوع الحفر</th>
                                                <th style="width: 25%">الطول (متر)</th>
                                                <th style="width: 25%">السعر (ريال)</th>
                                                <th style="width: 20%">الإجمالي النهائي</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="align-middle">أسفلت طبقة أولى <span class="badge bg-info">12345678900</span></td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input calc-area-length" 
                                                               name="open_excavation[first_asphalt][length]" 
                                                               data-table="first_asphalt"
                                                               value="{{ old('open_excavation.first_asphalt.length') }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control price-input calc-area-price" 
                                                               name="open_excavation[first_asphalt][price]" 
                                                               data-table="first_asphalt"
                                                               value="{{ old('open_excavation.first_asphalt.price', $workOrder->open_excavation['first_asphalt']['price'] ?? '') }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">ريال</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control bg-success text-white fw-bold area-total-calc" 
                                                               id="final_total_first_asphalt" 
                                                               readonly 
                                                               value="0.00">
                                                        <span class="input-group-text bg-success text-white">ريال</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle">كشط واعادة السفلتة  <span class="badge bg-info">12345678900</span></td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input calc-area-length" 
                                                               name="open_excavation[asphalt_scraping][length]" 
                                                               data-table="asphalt_scraping"
                                                               value="{{ old('open_excavation.asphalt_scraping.length') }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control price-input calc-area-price" 
                                                               name="open_excavation[asphalt_scraping][price]" 
                                                               data-table="asphalt_scraping"
                                                               value="{{ old('open_excavation.asphalt_scraping.price', $workOrder->open_excavation['asphalt_scraping']['price'] ?? '') }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">ريال</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control bg-success text-white fw-bold area-total-calc" 
                                                               id="final_total_asphalt_scraping" 
                                                               readonly 
                                                               value="0.00">
                                                        <span class="input-group-text bg-success text-white">ريال</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                

                                <!-- حفريات دقيقة -->
                                <div class="subsection mb-3">
                                    <h6 class="subsection-title">حفريات دقيقة</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                                <tr>
                                                    <th style="width: 25%">نوع الحفر</th>
                                                    <th style="width: 15%">الأبعاد</th>
                                                    <th style="width: 20%">الطول (متر)</th>
                                                    <th style="width: 20%">السعر (ريال)</th>
                                                    <th style="width: 20%">الإجمالي النهائي</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="align-middle">حفر متوسط <span class="badge bg-info">12345678900</span></td>
                                                    <td class="align-middle">20 × 80</td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calc-precise-length" 
                                                                   name="excavation_precise[medium]" 
                                                                   data-type="medium"
                                                                   value="{{ old('excavation_precise.medium', $workOrder->excavation_precise['medium'] ?? '') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">م</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control price-input calc-precise-price" 
                                                                   name="excavation_precise[medium_price]" 
                                                                   data-type="medium"
                                                                   value="{{ old('excavation_precise.medium_price', $workOrder->excavation_precise['medium_price'] ?? '') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">ريال</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control bg-success text-white fw-bold precise-total-calc" 
                                                                   id="final_total_precise_medium" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <span class="input-group-text bg-success text-white">ريال</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle">حفر منخفض <span class="badge bg-info">12345678900</span></td>
                                                    <td class="align-middle">20 × 56</td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calc-precise-length" 
                                                                   name="excavation_precise[low]" 
                                                                   data-type="low"
                                                                   value="{{ old('excavation_precise.low', $workOrder->excavation_precise['low'] ?? '') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">م</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control price-input calc-precise-price" 
                                                                   name="excavation_precise[low_price]" 
                                                                   data-type="low"
                                                                   value="{{ old('excavation_precise.low_price', $workOrder->excavation_precise['low_price'] ?? '') }}"
                                                                   placeholder="0.00">
                                                            <span class="input-group-text">ريال</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control bg-success text-white fw-bold precise-total-calc" 
                                                                   id="final_total_precise_low" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <span class="input-group-text bg-success text-white">ريال</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                            <td class="align-middle">تمديد كيبل 4x70 منخفض <span class="badge bg-info">12345678900</span></td>
                                                            <td>-</td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="number" step="0.01" min="0" class="form-control calc-electrical-length" name="electrical_items[cable_4x70_low][meters]" data-type="cable_4x70_low" value="{{ old('electrical_items.cable_4x70_low.meters', '0') }}" placeholder="0.00">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">متر</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="number" step="0.01" class="form-control price-input calc-electrical-price" 
                                                                           name="electrical_items[cable_4x70_low][price]" 
                                                                           data-type="cable_4x70_low"
                                                                           value="{{ old('electrical_items.cable_4x70_low.price', $workOrder->electrical_items['cable_4x70_low']['price'] ?? '') }}"
                                                                           placeholder="0.00">
                                                                    <span class="input-group-text">ريال</span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control bg-success text-white fw-bold electrical-total-calc" 
                                                                           id="final_total_cable_4x70_low" 
                                                                           readonly 
                                                                           value="0.00">
                                                                    <span class="input-group-text bg-success text-white">ريال</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">تمديد كيبل 4x185 منخفض <span class="badge bg-info">12345678900</span></td>
                                                            <td>-</td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="number" step="0.01" min="0" class="form-control calc-electrical-length" name="electrical_items[cable_4x185_low][meters]" data-type="cable_4x185_low" value="{{ old('electrical_items.cable_4x185_low.meters', '0') }}" placeholder="0.00">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">متر</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="number" step="0.01" class="form-control price-input calc-electrical-price" 
                                                                           name="electrical_items[cable_4x185_low][price]" 
                                                                           data-type="cable_4x185_low"
                                                                           value="{{ old('electrical_items.cable_4x185_low.price', $workOrder->electrical_items['cable_4x185_low']['price'] ?? '') }}"
                                                                           placeholder="0.00">
                                                                    <span class="input-group-text">ريال</span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control bg-success text-white fw-bold electrical-total-calc" 
                                                                           id="final_total_cable_4x185_low" 
                                                                           readonly 
                                                                           value="0.00">
                                                                    <span class="input-group-text bg-success text-white">ريال</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">تمديد كيبل 4x300 منخفض <span class="badge bg-info">12345678900</span></td>
                                                            <td>-</td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="number" step="0.01" min="0" class="form-control calc-electrical-length" name="electrical_items[cable_4x300_low][meters]" data-type="cable_4x300_low" value="{{ old('electrical_items.cable_4x300_low.meters', '0') }}" placeholder="0.00">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">متر</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="number" step="0.01" class="form-control price-input calc-electrical-price" 
                                                                           name="electrical_items[cable_4x300_low][price]" 
                                                                           data-type="cable_4x300_low"
                                                                           value="{{ old('electrical_items.cable_4x300_low.price', $workOrder->electrical_items['cable_4x300_low']['price'] ?? '') }}"
                                                                           placeholder="0.00">
                                                                    <span class="input-group-text">ريال</span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control bg-success text-white fw-bold electrical-total-calc" 
                                                                           id="final_total_cable_4x300_low" 
                                                                           readonly 
                                                                           value="0.00">
                                                                    <span class="input-group-text bg-success text-white">ريال</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">تمديد كيبل 3x500 متوسط <span class="badge bg-info">12345678900</span></td>
                                                            <td>-</td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="number" step="0.01" min="0" class="form-control calc-electrical-length" name="electrical_items[cable_3x500_med][meters]" data-type="cable_3x500_med" value="{{ old('electrical_items.cable_3x500_med.meters', '0') }}" placeholder="0.00">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">متر</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="number" step="0.01" class="form-control price-input calc-electrical-price" 
                                                                           name="electrical_items[cable_3x500_med][price]" 
                                                                           data-type="cable_3x500_med"
                                                                           value="{{ old('electrical_items.cable_3x500_med.price', $workOrder->electrical_items['cable_3x500_med']['price'] ?? '') }}"
                                                                           placeholder="0.00">
                                                                    <span class="input-group-text">ريال</span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control bg-success text-white fw-bold electrical-total-calc" 
                                                                           id="final_total_cable_3x500_med" 
                                                                           readonly 
                                                                           value="0.00">
                                                                    <span class="input-group-text bg-success text-white">ريال</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">تمديد كيبل 3x400 متوسط <span class="badge bg-info">12345678900</span></td>
                                                            <td>-</td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="number" step="0.01" min="0" class="form-control calc-electrical-length" name="electrical_items[cable_3x400_med][meters]" data-type="cable_3x400_med" value="{{ old('electrical_items.cable_3x400_med.meters', '0') }}" placeholder="0.00">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">متر</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="number" step="0.01" class="form-control price-input calc-electrical-price" 
                                                                           name="electrical_items[cable_3x400_med][price]" 
                                                                           data-type="cable_3x400_med"
                                                                           value="{{ old('electrical_items.cable_3x400_med.price', $workOrder->electrical_items['cable_3x400_med']['price'] ?? '') }}"
                                                                           placeholder="0.00">
                                                                    <span class="input-group-text">ريال</span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control bg-success text-white fw-bold electrical-total-calc" 
                                                                           id="final_total_cable_3x400_med" 
                                                                           readonly 
                                                                           value="0.00">
                                                                    <span class="input-group-text bg-success text-white">ريال</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>    
                    </div>  
                    <!-- قسم رفع الصور -->
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-info text-white">
                                <i class="fas fa-images me-2"></i>
                                صور الأعمال المدنية
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    يمكنك رفع حتى 50 صورة بحجم إجمالي لا يتجاوز 30 ميجابايت
                                </div>
                                
                                <div class="mb-3">
                                    <label for="civil_works_images" class="form-label">اختر الصور</label>
                                    <input type="file" 
                                           class="form-control" 
                                           id="civil_works_images" 
                                           name="civil_works_images[]" 
                                           multiple 
                                           accept="image/*"
                                           data-max-files="50"
                                           data-max-size="31457280">
                                    <div class="form-text">اختر عدة صور معاً</div>
                                </div>

                                <div id="images-preview" class="row g-2">
                                    <!-- سيتم إضافة معاينات الصور هنا -->
                                </div>

                                <!-- عرض الصور المرفوعة -->
                                @if(isset($workOrder->civilWorksFiles) && $workOrder->civilWorksFiles->count() > 0)
                                    <div class="mt-3 uploaded-images">
                                        <h6 class="mb-2">الصور المرفوعة</h6>
                                        <div class="row g-2">
                                            @foreach($workOrder->civilWorksFiles as $file)
                                                <div class="col-6" data-image-id="{{ $file->id }}">
                                                    <div class="card">
                                                        <img src="{{ asset('storage/' . $file->file_path) }}" 
                                                             class="card-img-top" 
                                                             style="height: 100px; object-fit: cover;">
                                                        <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                                            <small class="text-muted">{{ round($file->file_size / 1024 / 1024, 2) }} MB</small>
                                                            <button type="button" 
                                                                    class="btn btn-danger btn-sm" 
                                                                    onclick="deleteImage({{ $file->id }})"
                                                                    title="حذف الصورة">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- زر حفظ الصور -->
                                <div class="text-center mt-3">
                                    <button type="button" class="btn btn-success btn-sm" onclick="saveImages()">
                                        <i class="fas fa-save me-2"></i>
                                        حفظ الصور
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- قسم رفع المرفقات -->
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-warning text-dark">
                                <i class="fas fa-paperclip me-2"></i>
                                المرفقات والمستندات
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <i class="fas fa-info-circle me-2"></i>
                                    يمكنك رفع المرفقات (PDF, DOC, XLS, PPT, TXT) - حجم أقصى 20 ميجابايت لكل ملف
                                </div>
                                
                                <div class="mb-3">
                                    <label for="civil_works_attachments" class="form-label">اختر المرفقات</label>
                                    <input type="file" 
                                           class="form-control" 
                                           id="civil_works_attachments" 
                                           name="civil_works_attachments[]" 
                                           multiple 
                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar"
                                           data-max-files="20"
                                           data-max-file-size="20971520">
                                </div>

                                <div id="attachments-preview" class="mb-3">
                                    <!-- سيتم إضافة معاينات المرفقات هنا -->
                                </div>

                                <!-- عرض المرفقات المرفوعة -->
                                @if(isset($workOrder->civilWorksAttachments) && $workOrder->civilWorksAttachments->count() > 0)
                                    <div class="uploaded-attachments mt-3">
                                        <h6 class="mb-2">المرفقات المرفوعة</h6>
                                        @foreach($workOrder->civilWorksAttachments as $file)
                                            @php
                                                // دالة تحديد أيقونة الملف
                                                $extension = pathinfo($file->original_filename, PATHINFO_EXTENSION);
                                                $fileIcon = match(strtolower($extension)) {
                                                    'pdf' => 'pdf',
                                                    'doc', 'docx' => 'word',
                                                    'xls', 'xlsx' => 'excel',
                                                    'ppt', 'pptx' => 'powerpoint',
                                                    'txt' => 'alt',
                                                    'zip', 'rar' => 'archive',
                                                    default => 'alt'
                                                };
                                                
                                                // دالة تنسيق حجم الملف
                                                $fileSize = $file->file_size;
                                                if ($fileSize >= 1073741824) {
                                                    $formattedSize = number_format($fileSize / 1073741824, 2) . ' GB';
                                                } elseif ($fileSize >= 1048576) {
                                                    $formattedSize = number_format($fileSize / 1048576, 2) . ' MB';
                                                } elseif ($fileSize >= 1024) {
                                                    $formattedSize = number_format($fileSize / 1024, 2) . ' KB';
                                                } else {
                                                    $formattedSize = $fileSize . ' bytes';
                                                }
                                            @endphp
                                            <div class="d-flex align-items-center border rounded p-2 mb-2 attachment-item" data-attachment-id="{{ $file->id }}">
                                                <i class="fas fa-file-{{ $fileIcon }} text-primary me-2"></i>
                                                <div class="flex-grow-1">
                                                    <div class="text-truncate" title="{{ $file->original_filename }}">
                                                        {{ $file->original_filename }}
                                                    </div>
                                                    <small class="text-muted">{{ $formattedSize }}</small>
                                                </div>
                                                <div class="btn-group btn-group-sm ms-2">
                                                    <a href="{{ asset('storage/' . $file->file_path) }}" class="btn btn-outline-primary" target="_blank" title="عرض الملف">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteAttachment({{ $file->id }})" title="حذف الملف">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- زر حفظ المرفقات -->
                                <div class="text-center mt-3">
                                    <button type="button" class="btn btn-warning btn-sm" onclick="saveAttachments()">
                                        <i class="fas fa-save me-2"></i>
                                        حفظ المرفقات
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                <!-- زر التثبيت النهائي -->
                
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    
    <!-- تهيئة Toastr -->
    <script>
        // تهيئة Toastr
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "rtl": true
        };
    </script>
    
    <!-- Civil Works Professional System -->
    <script src="{{ asset('js/civil-works-professional.js') }}?v={{ time() }}"></script>
    
    <!-- تحميل البيانات المحفوظة -->
    <script>
        // تهيئة نظام الأعمال المدنية المحترف
        const workOrderId = {{ $workOrder->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const savedDailyData = @json($savedDailyData ?? []);
        
        console.log('🏗️ بدء تهيئة نظام الأعمال المدنية المحترف');
        console.log('📋 معرف أمر العمل:', workOrderId);
        console.log('💾 البيانات المحفوظة:', savedDailyData);
        
        // متغير للتحكم في التهيئة لمنع التكرار
        let systemInitialized = false;
        
        // دالة التهيئة الآمنة مع التحقق من وجود الدالة
        async function initializeSystem() {
            if (systemInitialized) {
                console.log('🟡 النظام تم تهيئته مسبقاً');
                return;
            }
            
            try {
                // التحقق من وجود الدالة قبل الاستدعاء
                if (typeof window.initializeCivilWorks !== 'function') {
                    console.error('❌ دالة initializeCivilWorks غير موجودة. التحقق من تحميل الملف...');
                    console.log('📋 الكائنات المتاحة:', Object.keys(window).filter(key => key.includes('civil') || key.includes('Civil')));
                    return;
                }
                
                systemInitialized = true;
                
                // تهيئة النظام الجديد
                const success = await window.initializeCivilWorks(workOrderId, csrfToken, savedDailyData);
                
                if (success) {
                    console.log('✅ تم تهيئة النظام بنجاح');
                    
                    // عرض إحصائيات النظام
                    if (window.civilWorksSystem && typeof window.civilWorksSystem.getSystemStats === 'function') {
                        const stats = window.civilWorksSystem.getSystemStats();
                        console.log('📊 إحصائيات النظام:', stats);
                    }
                } else {
                    console.error('❌ فشل في تهيئة النظام');
                }
                
            } catch (error) {
                console.error('❌ خطأ في تهيئة النظام:', error);
                systemInitialized = false;
            }
        }
        
        // تهيئة النظام عند تحميل الصفحة مع تأخير للتأكد من تحميل الملف
        function startInitialization() {
            // تأخير بسيط للتأكد من تحميل الملف
            setTimeout(() => {
                initializeSystem();
            }, 500);
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', startInitialization);
        } else {
            startInitialization();
        }
    </script>
</body>
</html> 